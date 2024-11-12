<?php

namespace Controller;

use Config\Database;
use Exception\ValidationException;
use Model\UserRegisterRequest;
use Repository\SessionRepotisory;
use Repository\UserRepository;
use Service\SessionService;
use Service\UserService;
use App\View;
use App\Flasher;

class UserController
{

    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();

        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepotisory($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function register(): void
    {
        View::render("register", [
            "title" => "Register",
        ]);
    }

    public function postRegister(): void
    {
        try {
            $request = new UserRegisterRequest();
            $request->username = $_POST["username"];
            $request->email = $_POST["email"];
            $request->password = $_POST["password"];

            $result = $this->userService->register($request);
            //langsung login
            $this->sessionService->create($result->user->id);
            View::redirect("/");
        } catch (ValidationException $e) {
            Flasher::setFlash("register failed : " . $e->getMessage());
            View::redirect("/register");
        }
    }

}