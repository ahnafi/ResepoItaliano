<?php

namespace Middleware;

use App\View;
use Config\Database;
use Service\SessionService;
use Repository\UserRepository;
use Repository\SessionRepository;

class MustUserMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function before(): void
    {
        $user = $this->sessionService->current();
        if ($user->role != "user") {
            View::redirect('/error');
        }
    }
}