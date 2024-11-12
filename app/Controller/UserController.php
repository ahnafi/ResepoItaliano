<?php

namespace Controller;

use Config\Database;
use Exception\ValidationException;
use Model\UserRegisterRequest;
use Repository\UserRepository;
use Service\UserService;
use App\View;

class UserController
{

    private UserService $userService;

    public function __construct()
    {
        $connection = Database::getConnection();

        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
    }

    public function register():void {
        View::render("register",[
            "title" => "Register",
        ]);
    }

    public function postRegister():void {
        try{

            $request = new UserRegisterRequest();
            $request->username = $_POST["username"];
            $request->email = $_POST["email"];
            $request->password = $_POST["password"];

            $this->userService->register($request);

            View::redirect("/");
        }catch (ValidationException $e){
//            alert

            View::redirect("/register");
        }
    }

}