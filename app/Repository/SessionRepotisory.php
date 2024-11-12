<?php

namespace Repository;
use Domain\session;

class SessionRepotisory
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

    public function findById(string $id): ?Session
    {
        $statement = $this->connection->prepare("SELECT session_id, user_id from sessions WHERE session_id = ?");
        $statement->execute([$id]);

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