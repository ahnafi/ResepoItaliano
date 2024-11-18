<?php

namespace Controller;

use App\Flasher;
use App\View;
use Config\Database;
use Domain\Recipe;
use Exception\ValidationException;
use Model\AddSavedRecipesRequest;
use Model\CreateRecipeRequest;
use Model\DeleteRecipeRequest;
use Model\RecipeSearchParams;
use Model\RemoveSavedRecipe;
use Model\UpdateRecipeRequest;
use Repository\CategoryRepository;
use Repository\RecipeImageRepository;
use Repository\RecipeRepository;
use Repository\SavedRecipeRepository;
use Repository\SessionRepository;
use Repository\UserRepository;
use Service\RecipeService;
use Service\SavedRecipeService;
use Service\SessionService;

class HomeController
{
    private SessionService $sessionService;
    private RecipeService $recipeService;
    private SavedRecipeService $savedRecipeService;

    public function __construct()
    {
        $connection = Database::getConnection();

        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $recipeRepository = new RecipeRepository($connection);
        $recipeImageRepository = new RecipeImageRepository($connection);
        $categoryRepository = new CategoryRepository($connection);
        $this->recipeService = new RecipeService($recipeRepository, $categoryRepository, $recipeImageRepository, $userRepository);

        $savedRecipeRepository = new SavedRecipeRepository($connection);
        $this->savedRecipeService = new SavedRecipeService($savedRecipeRepository, $userRepository, $recipeRepository);
    }

    public function home(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Beranda",
        ];

        if ($user != null) {
            $model["user"] = $user;
        }

        $result = $this->recipeService->searchRecipe(new RecipeSearchParams());

        $model["data"] = [
            "recipes" => $result->recipes,
            "total" => $result->totalRecipes
        ];

        View::render("Home/index", $model);
    }

    public function about(): void
    {
        $user = $this->sessionService->current();


        $model = [
            "title" => "About ",
        ];

        if ($user != null) {
            $model["user"] = $user;
        }

        View::render("Home/about", $model);
    }

    public function error(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "404 Page Not Found",
        ];

        if ($user != null) {
            $model["user"] = $user;
        }

        View::render("error", $model);
    }

    public function addRecipe(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Create Recipe",
            "user" => $user,
        ];

        View::render("Recipe/create", $model);
    }

    public function postAddRecipe(): void
    {
        $user = $this->sessionService->current();

        try {

            $req = new CreateRecipeRequest();
            $req->userId = $user->id;
            $req->name = htmlspecialchars($_POST['name']);
            $req->ingredients = htmlspecialchars($_POST['ingredients']);
            $req->steps = htmlspecialchars($_POST['steps']);
            $req->categoryId = htmlspecialchars($_POST['categoryId']);
            $req->note = htmlspecialchars($_POST['note'] ?? null);

            if (isset($_FILES['photos'])) {
                $req->recipeImages = $_FILES['photos'];
            } else {
                $req->recipeImages = [];
            }

            $this->recipeService->uploadRecipe($req);
            Flasher::setFlash("Success create recipe");
            View::redirect("/");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/recipe/add");
        }
    }

    public function detail($recipeId): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Detail Recipe",
        ];

        if ($user != null) {
            $model["user"] = $user;
        }

        try {

            $res = $this->recipeService->detailRecipe($recipeId);

            $model["recipe"] = [
                "recipe_id" => $recipeId,
                "title" => $res->recipe->name,
                "ingredients" => $res->recipe->ingredients,
                "steps" => $res->recipe->steps,
                "banner" => $res->recipe->image,
                "note" => $res->recipe->note,
                "created_at" => $res->recipe->createdAt,
                "recipe_images" => $res->images,

                "creator_id" => $res->recipe->user->id,
                "creator_name" => $res->recipe->user->username,
                "creator_email" => $res->recipe->user->email,
                "creator_image" => $res->recipe->user->profileImage,

                "category_id" => $res->recipe->category->category_id,
                "category_name" => $res->recipe->category->category_name,
            ];

            View::render("Recipe/detail", $model);
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/");
        }
    }

    public function search(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Search recipes",
        ];

        if ($user != null) {
            $model["user"] = $user;
        }
        try {

            $req = new RecipeSearchParams();
            $req->title = htmlspecialchars($_GET['title'] ?? "");
            $req->category = (int)htmlspecialchars($_GET['cat'] ?? "");
            $req->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            $result = $this->recipeService->searchRecipe($req);

            $model["data"] = [
                "recipes" => $result->recipes,
                "total" => $result->totalRecipes
            ];

            View::render("Recipe/search", $model);
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/");
        }
    }

    public function updateRecipe($recipeId): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Update Recipe",
            "user" => $user,
        ];

        try {
            $recipe = $this->recipeService->detailRecipe($recipeId);
            $model["recipe"] = $recipe->recipe;

            View::render("Recipe/update", $model);
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/");
        }
    }

    public function postUpdateRecipe(): void
    {
        $user = $this->sessionService->current();

        try {
            $req = new UpdateRecipeRequest();
            $req->recipeId = htmlspecialchars($_POST['recipeId']);
            $req->steps = htmlspecialchars($_POST['steps']);
            $req->name = htmlspecialchars($_POST['name']);
            $req->note = htmlspecialchars($_POST['note'] ?? null);
            $req->categoryId = (int)htmlspecialchars($_POST['categoryId']);
            $req->ingredients = htmlspecialchars($_POST['ingredients']);
            $req->userId = $user->id;

            $this->recipeService->updateRecipe($req);
            View::redirect("/profile");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/profile");
        }
    }

    public function postRemoveRecipe(): void
    {
        $user = $this->sessionService->current();

        try {
            $req = new DeleteRecipeRequest();
            $req->recipeId = htmlspecialchars($_POST['recipeId']);
            $req->userId = $user->id;
            $this->recipeService->deleteRecipe($req);

            Flasher::setFlash("Success delete recipe");
            View::redirect("/profile");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/profile");
        }
    }

    public function postSaveRecipe(): void
    {
        $user = $this->sessionService->current();

        try {

            $req = new AddSavedRecipesRequest();

            $req->userId = $user->id;
            $req->recipeId = htmlspecialchars($_POST['recipeId']);

            $this->savedRecipeService->add($req);

            Flasher::setFlash("Success saving recipe");
            View::redirect("/recipe/$req->recipeId");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/");
        }
    }

    public function postRemoveSavedRecipe(): void
    {
        $user = $this->sessionService->current();

        try {
            $req = new RemoveSavedRecipe();
            $req->userId = $user->id;
            $req->savedId = htmlspecialchars($_POST['savedId']);
            $this->savedRecipeService->remove($req);
            Flasher::setFlash("Success removing recipe");
            View::redirect("/profile");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/profile");
        }
    }

}