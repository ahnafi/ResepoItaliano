<?php

namespace Service;

use Config\Database;
use Domain\User;
use Exception\ValidationException;
use Model\UserRegisterRequest;
use Model\UserRegisterResponse;
use Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    private function isValidEmail(string $email)
    {
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        return preg_match($pattern, $email) === 1;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->ValidateUserRegiterRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField("email",$request->email);

            if($user){
                throw new ValidationException("User already exists");
            }

            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $result = $this->userRepository->save($user);

            Database::commitTransaction();

            $response = new UserRegisterResponse();
            $response->user = $result;
            return $response;
        }catch (\Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }

    }

    private function ValidateUserRegiterRequest(UserRegisterRequest $request): void
    {
        if ($request->username == "" || $request->email == "" || $request->password == "") {
            throw new ValidationException("Username , email, password is required");
        }

        if($this->isValidEmail($request->email)){
            throw new ValidationException("Your email is not valid");
        }
    }

}
