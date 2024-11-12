<?php

namespace Service;

use Exception\ValidationException;
use Repository\SessionRepotisory;
use Repository\UserRepository;
use Domain\Session;
use Domain\User;

class SessionService
{
    public static string $SESSION_NAME = "X-RESEPOITALIANO-SESSION-X";
    private SessionRepotisory $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepotisory $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(int $userId): Session
    {
        if ($row = $this->sessionRepository->find("user_id", $userId)) $this->sessionRepository->deleteById($row->sessionId);

        $session = new session();
        $session->sessionId = uniqid();
        $session->userId = $userId;

        $this->sessionRepository->save($session);

        $_SESSION[self::$SESSION_NAME] = $session->sessionId;

        return $session;
    }

    public function destroy(): void
    {
        $sessionId = $_SESSION[self::$SESSION_NAME];
        $this->sessionRepository->deleteById($sessionId);

        $_SESSION[self::$SESSION_NAME] = null;
    }

    public function current(): ?User
    {
        $sessionId = $_SESSION[self::$SESSION_NAME] ?? "";
        $session = $this->sessionRepository->find("session_id", $sessionId);

        if ($session == null) {
            return null;
        }

        return $this->userRepository->findByField("user_id", $session->userId);
    }
}