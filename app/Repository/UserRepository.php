<?php

namespace Repository;

use Domain\User;
use Exception\ValidationException;
use Model\UserSearchParams;
use Model\UserSearchResponse;

class UserRepository
{
    private ?\PDO $connection = null;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users (username,email,password,role) values (?,?,?,?)");
        $statement->execute([$user->username, $user->email, $user->password, $user->role]);
        $user->id = $this->connection->lastInsertId();
        return $user;
    }

    public function findByField(string $field, $value): ?User
    {

        if (!in_array($field, ['user_id', 'email'])) {
            throw new ValidationException("Field {$field} not exist");
        }

        $statement = $this->connection->prepare("SELECT user_id , username, email , password, profile_image, role FROM users WHERE $field = ?");
        $statement->execute([$value]);

        try {

            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['user_id'];
                $user->username = $row['username'];
                $user->email = $row['email'];
                $user->password = $row['password'];
                $user->profileImage = $row['profile_image'];
                $user->role = $row['role'];
                return $user;
            } else {
                return null;
            }

        } finally {
            $statement->closeCursor();
        }
    }

    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET username = ?, password = ?,profile_image = ? WHERE user_id = ?");
        $statement->execute([$user->username, $user->password, $user->profileImage, $user->id]);
        return $user;
    }

    public function search(UserSearchParams $params): UserSearchResponse
    {
        $limit = 50;
        $offset = ($params->page - 1) * $limit;

        // Query untuk menghitung total data sesuai filter (tanpa LIMIT dan OFFSET)
        $countQuery = "SELECT COUNT(*) AS total FROM users WHERE 1=1";
        $queryParams = [];

        if ($params->username != null) {
            $countQuery .= " AND users.username LIKE ?";
            $queryParams[] = "%{$params->username}%";
        }

        if ($params->email != null) {
            $countQuery .= " AND users.email LIKE ?";
            $queryParams[] = "%{$params->email}%";
        }

        if ($params->role != null) {
            $countQuery .= " AND users.role = ?";
            $queryParams[] = "{$params->role}";
        }

        $countStatement = $this->connection->prepare($countQuery);

        foreach ($queryParams as $index => $param) {
            $countStatement->bindValue($index + 1, $param);
        }

        $countStatement->execute();
        $total = $countStatement->fetchColumn();

        // Query untuk mengambil data pengguna
        $query = "SELECT users.* FROM users WHERE 1=1";
        $queryParams = []; // Reset queryParams untuk kueri pengguna

        if ($params->username != null) {
            $query .= " AND users.username LIKE ?";
            $queryParams[] = "%{$params->username}%";
        }

        if ($params->email != null) {
            $query .= " AND users.email LIKE ?";
            $queryParams[] = "%{$params->email}%";
        }

        if ($params->role != null) {
            $query .= " AND users.role = ?";
            $queryParams[] = "{$params->role}";
        }

        $query .= " LIMIT ? OFFSET ?";
        $statement = $this->connection->prepare($query);

        // Mengikat parameter untuk kueri pengguna
        foreach ($queryParams as $index => $param) {
            $statement->bindValue($index + 1, $param);
        }

        // Mengikat LIMIT dan OFFSET
        $statement->bindValue(count($queryParams) + 1, $limit, \PDO::PARAM_INT);
        $statement->bindValue(count($queryParams) + 2, $offset, \PDO::PARAM_INT);

        $statement->execute();

        $users = [];
        while ($row = $statement->fetch()) {
            $user = new User();
            $user->id = $row['user_id'];
            $user->username = $row['username'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->profileImage = $row['profile_image'];
            $user->role = $row['role'];
            $users[] = (array)$user;
        }

        $res = new UserSearchResponse();
        $res->users = $users;
        $res->total = $total;
        return $res;
    }

}