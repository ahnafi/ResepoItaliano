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

//use Repository\RecipeImageRepository;
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
//        $recipeImageRepository = new RecipeImageRepository($connection);
        $categoryRepository = new CategoryRepository($connection);
        $this->recipeService = new RecipeService($recipeRepository, $categoryRepository, $userRepository);

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
            $model["user"] = (array)$user;
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
            "title" => "Tentang Kami",
        ];

        if ($user != null) {
            $model["user"] = (array)$user;
        }

        View::render("Home/about", $model);
    }

    public function error(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "404 Halaman Tidak Ditemukan",
        ];

        if ($user != null) {
            $model["user"] = (array)$user;
        }

        View::render("error", $model);
    }

    public function addRecipe(): void
    {
        $user = $this->sessionService->current();

        $model = [
            "title" => "Tambahkan Resep",
            "user" => (array)$user,
        ];

        View::render("Recipe/create", $model);
    }

    public function postAddRecipe(): void
    {
        $user = $this->sessionService->current();

        try {
            $req = new CreateRecipeRequest();
            $req->userId = $user->id;
            $req->name = htmlspecialchars($_POST['title']);
            $req->ingredients = htmlspecialchars($_POST['ingredients']);
            $req->steps = htmlspecialchars($_POST['steps']);
            $req->categoryId = (int)htmlspecialchars($_POST['categoryId']);
            $req->note = htmlspecialchars($_POST['note'] ?? null);
            $req->image = $_FILES['image'] ?? null;
//            if (isset($_FILES['photos'])) {
//                $req->recipeImages = $_FILES['photos'];
//            } else {
//                $req->recipeImages = [];
//            }

            $this->recipeService->uploadRecipe($req);
            Flasher::setFlash("Resep berhasil ditambahkan");
            View::redirect("/");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/recipe/add");
        }
    }

    public function detail($recipeId): void
    {
        $user = $this->sessionService->current();

        $model = [];

        if ($user != null) {
            $model["user"] = (array)$user;
        }

        try {

            $res = $this->recipeService->detailRecipe($recipeId);
            $model["title"] = $res->recipe->name;
            $model["recipe"] = [
                "recipe_id" => $recipeId,
                "title" => $res->recipe->name,
                "ingredients" => $res->recipe->ingredients,
                "steps" => $res->recipe->steps,
                "banner" => $res->recipe->image,
                "note" => $res->recipe->note,
                "created_at" => $res->recipe->createdAt,
//                "recipe_images" => $res->,

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
            "title" => "Cari Resep",
        ];

        if ($user != null) {
            $model["user"] = (array)$user;
        }
        try {

            $req = new RecipeSearchParams();
            $req->title = htmlspecialchars($_GET['title'] ?? "");
            $req->category = (int)htmlspecialchars($_GET['cat'] ?? "");
            $req->page = (isset($_GET['page']) && $_GET['page'] !== "") ? (int)$_GET['page'] : 1;

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
            "title" => "Perbarui Resep",
            "user" => (array)$user,
        ];

        try {
            $recipe = $this->recipeService->detailRecipe($recipeId);
            $model["recipe"] = (array)$recipe->recipe;

            View::render("Recipe/update", $model);
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/");
        }
    }

    public function postUpdateRecipe($recipeId): void
    {
        $user = $this->sessionService->current();
        $redirect = $user->role == 'user' ? '/user/profile' : '/admin/profile';
        try {
            $req = new UpdateRecipeRequest();
            $req->recipeId = (int)$recipeId;
            $req->name = htmlspecialchars($_POST['title']);
            $req->steps = htmlspecialchars($_POST['steps']);
            $req->note = htmlspecialchars($_POST['note']);
            $req->categoryId = (int)htmlspecialchars($_POST['categoryId']);
            $req->ingredients = htmlspecialchars($_POST['ingredients']);
            $req->userId = $user->id;

            // Memeriksa apakah file gambar diunggah dengan benar
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && $_FILES['image']['size'] > 0) {
                $req->image = $_FILES['image'];
            } else {
                $req->image = null; // Jika tidak ada file yang diunggah, set ke null
            }

            var_dump($req);
            $this->recipeService->updateRecipe($req);
            Flasher::setFlash("Resep berhasil diperbarui");
            View::redirect($redirect . "/manage-recipes");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/");
        }
    }

    public function postRemoveRecipe(): void
    {
        $user = $this->sessionService->current();
        $redirect = $user->role == 'user' ? '/user/profile' : '/admin/profile';
        try {
            $req = new DeleteRecipeRequest();
            $req->recipeId = (int)htmlspecialchars($_POST['recipeId']);
            $req->userId = $user->id;
            $this->recipeService->deleteRecipe($req);

            Flasher::setFlash("Resep berhasil dihapus");
            View::redirect("$redirect/manage-recipes");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("$redirect/manage-recipes");
        }
    }

    public function postSaveRecipe($recipeId): void
    {
        $user = $this->sessionService->current();

        try {
            $req = new AddSavedRecipesRequest();

            $req->userId = $user->id;
            $req->recipeId = (int)$recipeId;

            $this->savedRecipeService->add($req);

            Flasher::setFlash("Resep berhasil disimpan");
            View::redirect("/recipe/$recipeId");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/recipe/$recipeId");
        }
    }

    public function postRemoveSavedRecipe(): void
    {
        $user = $this->sessionService->current();

        try {
            $req = new RemoveSavedRecipe();
            $req->userId = $user->id;
            $req->savedId = (int)htmlspecialchars($_POST['savedId']);
            var_dump($req);
            $this->savedRecipeService->remove($req);
            Flasher::setFlash("Resep berhasil dihapus dari daftar simpan");
            View::redirect("/user/profile/saved-recipe");
        } catch (ValidationException $exception) {
            Flasher::setFlash("Error : " . $exception->getMessage());
            View::redirect("/");
        }
    }

    public function postEmail()
    {
        $toEmail = "admin@pedulirasa.co.id";
        $subject = "Hubungi Admin";
        $name = $_POST['name'];
        $email = $_POST['email'];
        $note = $_POST['message'];

        $message = "\nNama : $name \nEmail : $email \n\n$note";

        if (mail($toEmail, $subject, $message)) {
            Flasher::setFlash("Email berhasil dikirim");
        } else {
            Flasher::setFlash("Email gagal dikirim");
        }
        View::redirect("/about");
    }

}