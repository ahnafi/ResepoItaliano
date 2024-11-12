<?php

namespace Repository;

use Domain\User;
use Exception\ValidationException;

class UserRepository
{
    private ?\PDO $connection = null;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users (username,email,password) values (?,?,?)");
        $statement->execute([$user->username, $user->email, $user->password]);
        $user->id = $this->connection->lastInsertId();
        return $user;
    }

    public function findByField(string $field, $value): ?User
    {

        if (!in_array($field, ['user_id', 'email'])) {
            throw new ValidationException("Field {$field} not exist");
        }

        $statement = $this->connection->prepare("SELECT user_id , username, email , password, profile FROM users WHERE $field = ?");
        $statement->execute([$value]);

        try {

            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['user_id'];
                $user->username = $row['username'];
                $user->email = $row['email'];
                $user->password = $row['password'];
                $user->profile = $row['profile'];
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
        $statement = $this->connection->prepare("UPDATE users SET username = ?, password = ?,profile = ? WHERE user_id = ?");
        $statement->execute([$user->username, $user->password, $user->profile, $user->id]);
        return $user;
    }


}