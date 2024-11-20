<?php

namespace Service;

use Config\Database;
use Domain\User;
use Exception\ValidationException;
use Model\UserLoginRequest;
use Model\UserLoginResponse;
use Model\UserPasswordRequest;
use Model\UserRegisterRequest;
use Model\UserRegisterResponse;
use Model\UserUpdateRequest;
use Model\UserUpdateResponse;
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

    public function update(UserUpdateRequest $request): UserUpdateResponse
    {
        $this->ValidateUserUpdateRequest($request);
        $pathFile = __DIR__ . "./../../public/images/profiles/";

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField("user_id", $request->userId);

            if ($user == null) {
                throw new ValidationException("User not found");
            }

            $user->username = $request->username;

            if ($user->profileImage != null && $request->photo != null) {
                unlink($pathFile . $user->profileImage);
            }

            if ($request->photo && isset($request->photo["tmp_name"])) {
                $extension = pathinfo($request->photo["name"], PATHINFO_EXTENSION);
                $photoName = uniqid() . "." . $extension;

                move_uploaded_file($request->photo["tmp_name"], $pathFile . $photoName);

                $user->profileImage = $photoName;
            }

            $this->userRepository->update($user);

            Database::commitTransaction();
            $response = new UserUpdateResponse();
            $response->user = $user;
            return $response;

        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function ValidateUserUpdateRequest(UserUpdateRequest $request): void
    {
        if ($request->userId == "" || empty($request->userId) || $request->username == "") {
            throw new ValidationException("Username is required");
        }

        if ($request->photo == null && isset($request->photo["tmp_name"])) {
            throw new ValidationException ("image cannot be empty");
        }

        if ($request->photo["error"] != UPLOAD_ERR_OK) {
            throw new ValidationException ("image error");
        }

        $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($request->photo["type"], $validTypes)) {
            throw new ValidationException ("image type is not allowed");
        }

        if ($request->photo["size"] > 2 * 1024 * 1024) {
            throw new ValidationException ("image size is too large");
        }

    }

    public function updatePassword(UserPasswordRequest $request): UserUpdateResponse
    {
        $this->ValidateUserPasswordRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField("user_id", $request->userId);

            if ($user == null) {
                throw new ValidationException("User not found");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Old password is not correct");
            }

            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->update($user);

            Database::commitTransaction();
            $response = new UserUpdateResponse();
            $response->user = $user;
            return $response;

        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function ValidateUserPasswordRequest(UserPasswordRequest $request): void
    {
        if ($request->password == "" || $request->userId == "" || empty($request->id) || empty($request->oldPassword) || $request->oldPassword == "" || empty($request->password)) {
            throw new ValidationException("new Password is required");
        }

        if ($request->password == $request->oldPassword) {
            throw new ValidationException("Old password can not be same as new password");
        }

        if ($request->password != $request->password_confirmation) {
            throw new ValidationException("Passwords do not match");
        }
    }

}
