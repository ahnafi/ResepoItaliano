<?php

namespace Service;

use Config\Database;
use Domain\User;
use Exception\ValidationException;
use Model\UserLoginRequest;
use Model\UserLoginResponse;
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

            $user = $this->userRepository->findByField("email", $request->email);

            if ($user) {
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
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }

    }

    private function ValidateUserRegiterRequest(UserRegisterRequest $request): void
    {
        if ($request->username == "" || $request->email == "" || $request->password == "" || empty($request->username) || empty($request->email) || empty($request->password)) {
            throw new ValidationException("Username , email, password is required");
        }

        if (!$this->isValidEmail($request->email)) {
            throw new ValidationException("Your email is not valid");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->ValidateUserLoginRequest($request);

        $user = $this->userRepository->findByField("email", $request->email);
        if ($user == null) {
            throw new ValidationException("Email or password is not correct");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Email or password is not correct");
        }

    }

    private function ValidateUserLoginRequest(UserLoginRequest $request): void
    {
        if ($request->password == "" || empty($request->password) || empty($request->email) || $request->email == "") {
            throw new ValidationException("Username or password is required");
        }
        if (!$this->isValidEmail($request->email)) throw new ValidationException("Email is not valid");
    }

}
