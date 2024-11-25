<?php

namespace Controller;

use Config\Database;
use Exception\ValidationException;
use Model\AdminUpdateUserRequest;
use Model\RecipeSearchParams;
use Model\UserRegisterRequest;
use Model\UserSearchParams;
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

class AdminController
{
    private UserService $userService;
    private SessionService $sessionService;
    private RecipeService $recipeService;

    public function __construct()
    {
        $connection = Database::getConnection();

        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $recipeRepository = new RecipeRepository($connection);

        $categoryRepository = new CategoryRepository($connection);
        $this->recipeService = new RecipeService($recipeRepository, $categoryRepository, $userRepository);
    }

    public function profile()
    {
        $user = $this->sessionService->current();

        $model = [
            'title' => 'Pengaturan Akun',
            'user' => (array)$user,
        ];

        View::render('Admin/profile', $model);
    }

    public function password()
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Ganti Password",
            "user" => (array)$user,
        ];

        View::render("Admin/password", $model);
    }

    public function manageRecipes()
    {
        $user = $this->sessionService->current();
        $req = new RecipeSearchParams();
        $req->title = htmlspecialchars($_GET['title'] ?? "");
        $req->category = (int)htmlspecialchars($_GET['cat'] ?? "");
        $req->userId = (int)htmlspecialchars($_GET['userId'] ?? "");
        $req->page = (isset($_GET['page']) && $_GET['page'] !== "") ? (int)$_GET['page'] : 1;
        $recipe = $this->recipeService->searchRecipe($req);

        $model = [
            "title" => "Kelola Resep",
            "user" => (array)$user,
            'total' => $recipe->totalRecipes,
            'recipes' => $recipe->recipes
        ];

        View::render("Admin/manageRecipes", $model);
    }

    public function manageUsers()
    {
        $user = $this->sessionService->current();

        $req = new UserSearchParams();
        $req->username = htmlspecialchars($_GET['username'] ?? "");
        $req->email = htmlspecialchars($_GET['email'] ?? "");
        $req->role = htmlspecialchars($_GET['role'] ?? "");
        $req->page = (isset($_GET['page']) && $_GET['page'] !== "") ? (int)$_GET['page'] : 1;

        $result = $this->userService->getAllUsers($req);

        $model = [
            "title" => "Kelola User",
            "user" => (array)$user,
            'data' => [
                'users' => $result->users,
                'total' => $result->total
            ]
        ];

        View::render("Admin/manageUsers", $model);
    }

    public function registerAdmin()
    {
        $user = $this->sessionService->current();

        $model = [
            'title' => 'Tambahkan Admin Baru',
            'user' => (array)$user,
        ];

        View::render('Admin/register', $model);
    }

    public function postRegisterAdmin(): void
    {
        try {
            $request = new UserRegisterRequest();
            $request->username = $_POST["username"];
            $request->email = $_POST["email"];
            $request->password = $_POST["password"];
            $request->role = "admin";

            $result = $this->userService->register($request);
//            //langsung login
//            $this->sessionService->create($result->user->id);
            Flasher::setFlash("Akun berhasil dibuat");
            View::redirect("/admin/profile/register-admin");
        } catch (ValidationException $e) {
            Flasher::setFlash("register failed : " . $e->getMessage());
            View::redirect("/admin/profile/register-admin");
        }
    }

    public function updateUser(int $userId)
    {
        $user = $this->sessionService->current();

        try {
            $updateUser = $this->userService->find($userId);
            $model = [
                'title' => 'Ubah Profil User',
                'user' => (array)$user,
                'updateUser' => (array)$updateUser
            ];

            View::render('Admin/updateUser', $model);
        } catch (ValidationException $exception) {
            Flasher::setFlash('error : ' . $exception->getMessage());
            View::redirect("/admin/profile/manage-users");
        }

    }

    public function postUpdateUser($userId): void
    {
        $user = $this->sessionService->current();

        try {

            $request = new AdminUpdateUserRequest();
            $request->userId = (int)$userId;
            $request->adminId = $user->id;
            $request->username = $_POST["username"] ?? null;
            $request->password = $_POST["newPassword"] ?? null;
            $request->profileImage = $_FILES['profile']['tmp_name'] != "" ? $_FILES['profile'] : null;
            $this->userService->adminUpdateUser($request);

            Flasher::setFlash("Update data user berhasil");
            View::redirect("/admin/profile/manage-users");
        } catch (ValidationException $e) {
            Flasher::setFlash("Update user failed : " . $e->getMessage());
            View::redirect("/admin/profile/manage-users");
        }
    }
}