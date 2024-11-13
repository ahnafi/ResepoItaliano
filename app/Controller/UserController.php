<?php

namespace Controller;

use Config\Database;
use Exception\ValidationException;
use Model\UserLoginRequest;
use Model\UserPasswordRequest;
use Model\UserRegisterRequest;
use Model\UserUpdateRequest;
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

    public function login(): void
    {
        View::render("login", [
            "title" => "Login",
        ]);
    }

    public function postLogin(): void
    {
        try {
            $request = new UserLoginRequest();
            $request->email = $_POST["email"];
            $request->password = $_POST["password"];

            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect("/");
        } catch (ValidationException $e) {
            Flasher::setFlash("login failed : " . $e->getMessage());
            View::redirect("/login");
        }
    }

    public function update(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Update profile",
            "user" => [
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email,
                "photo_profile" => $user->profile
            ],
        ];

        View::render("update", $model);
    }

    public function postUpdate(): void
    {
        $user = $this->sessionService->current();

        try {

            $request = new UserUpdateRequest();
            $request->username = $_POST["username"];
            if (isset($_FILES['profile']) && $_FILES['profile']['error'] == UPLOAD_ERR_OK) {
                $request->photo = $_FILES['profile'];
            } else {
                $request->photo = null;
            }

            $this->userService->update($request);
            Flasher::setFlash("profile updated successfully");
            View::redirect("/profile");
        } catch (ValidationException $e) {
            Flasher::setFlash("update failed : " . $e->getMessage());
            View::redirect("/update");
        }
    }

    public function postPassword(): void
    {
        $user = $this->sessionService->current();

        try {
            $request = new UserPasswordRequest();
            $request->password = $_POST["newPassword"];
            $request->oldPassword = $_POST["oldPassword"];
            $request->password_confirmation = $_POST["confirmPassword"];
            $request->userId = $user->id;

            $this->userService->updatePassword($request);

            Flasher::setFlash("password updated successfully");
            View::redirect("/profile");
        } catch (ValidationException $e) {
            Flasher::setFlash("update password failed : " . $e->getMessage());
            View::redirect("/profile");
        }
    }

    public function logout(): void
    {
        $this->sessionService->destroy();
        View::redirect("/");
    }

}