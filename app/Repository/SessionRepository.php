<?php

namespace Repository;

use Domain\Session;
use Exception\ValidationException;

class SessionRepository
{
    private ?\PDO $connection = null;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Session $session): Session
    {
        $statement = $this->connection->prepare("INSERT INTO sessions(session_id, user_id) VALUES (?, ?)");
        $statement->execute([$session->sessionId, $session->userId]);
        return $session;
    }

    public function find(string $field,$value): ?Session
    {
        if(!in_array($field,['session_id','user_id'])){
            throw new ValidationException("Session id or user id is invalid");
        }

        $statement = $this->connection->prepare("SELECT session_id, user_id from sessions WHERE $field = ?");
        $statement->execute([$value]);

        try {
            if ($row = $statement->fetch()) {
                $session = new Session();
                $session->sessionId = $row['session_id'];
                $session->userId = $row['user_id'];
                return $session;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM sessions WHERE session_id = ?");
        $statement->execute([$id]);
    }

}