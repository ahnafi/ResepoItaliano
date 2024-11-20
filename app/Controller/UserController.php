<?php

namespace Controller;

use Config\Database;
use Exception\ValidationException;
use Model\RecipeSearchParams;
use Model\UserLoginRequest;
use Model\UserPasswordRequest;
use Model\UserRegisterRequest;
use Model\UserUpdateRequest;
use Repository\CategoryRepository;
use Repository\RecipeRepository;
use Repository\SavedRecipeRepository;
use Repository\SessionRepository;
use Repository\UserRepository;
use Service\RecipeService;
use Service\SavedRecipeService;
use Service\SessionService;
use Service\UserService;
use App\View;
use App\Flasher;

class UserController
{

    private UserService $userService;
    private SessionService $sessionService;
    private RecipeService $recipeService;
    private SavedRecipeService $savedRecipeService;

    public function __construct()
    {
        $connection = Database::getConnection();

        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $recipeRepository = new RecipeRepository($connection);
        $savedRecipeRepository = new SavedRecipeRepository($connection);
        $this->savedRecipeService = new SavedRecipeService($savedRecipeRepository, $userRepository, $recipeRepository);

        $categoryRepository = new CategoryRepository($connection);
        $this->recipeService = new RecipeService($recipeRepository, $categoryRepository, $userRepository);
    }

    public function register(): void
    {
        View::render("User/register", [
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
//            //langsung login
//            $this->sessionService->create($result->user->id);
            Flasher::setFlash("Registration successfull");
            View::redirect("/login");
        } catch (ValidationException $e) {
            Flasher::setFlash("register failed : " . $e->getMessage());
            View::redirect("/register");
        }
    }

    public function login(): void
    {
        View::render("User/login", [
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
            "user" => (array)$user,
        ];

        View::render("User/update", $model);
    }

    public function postUpdate(): void
    {
        $user = $this->sessionService->current();

        try {

            $request = new UserUpdateRequest();
            $request->username = $_POST["username"];
            $request->userId = $user->id;
            $request->photo = $_FILES['profile'] ?? null;
//            if (isset($_FILES['profile']) && $_FILES['profile']['error'] == UPLOAD_ERR_OK) {
//                $request->photo = $_FILES['profile'];
//            } else {
//                $request->photo = null;
//            }

            $this->userService->update($request);
            Flasher::setFlash("profile updated successfully");
            View::redirect("/user/profile");
        } catch (ValidationException $e) {
            Flasher::setFlash("update failed : " . $e->getMessage());
            View::redirect("/user/profile");
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
            View::redirect("/user/profile/password");
        } catch (ValidationException $e) {
            Flasher::setFlash("update password failed : " . $e->getMessage());
            View::redirect("/user/profile/password");
        }
    }

    public function logout(): void
    {
        $this->sessionService->destroy();
        View::redirect("/");
    }

    public function profile(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Profile",
            "user" => (array)$user,
        ];

        View::render("User/profile", $model);
    }

    public function savedRecipes(): void
    {
        $user = $this->sessionService->current();

        $saved = $this->savedRecipeService->getSavedRecipes($user->id);

        $model = [
            "title" => "saved recipes",
            "user" => (array)$user,
            "savedRecipes" => $saved
        ];

        View::render("User/saved", $model);
    }

    public function manageRecipes()
    {
        $user = $this->sessionService->current();
        $req = new RecipeSearchParams();
        $req->userId = $user->id;
        $req->page = (isset($_GET['page']) && $_GET['page'] !== "") ? (int)$_GET['page'] : 1;
        $recipe = $this->recipeService->UserRecipes($req);

        $model = [
            "title" => "Manage saved recipes",
            "user" => (array)$user,
            'total' => $recipe->total,
            'recipes' => $recipe->recipes
        ];

        View::render("User/manage", $model);
    }

    public function password()
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Change password",
            "user" => (array)$user,
        ];

        View::render("User/password", $model);
    }

}