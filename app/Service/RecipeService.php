<?php

namespace Service;

use Config\Database;
use Domain\Recipe;

//use Domain\RecipeImage;
use Exception\ValidationException;
use Model\CreateRecipeRequest;
use Model\DeleteRecipeRequest;
use Model\ReadDetailRecipeResponse;
use Model\RecipeSearchParams;
use Model\RecipeSearchResponse;
use Model\SearchRecipeResponse;
use Model\UpdateRecipeRequest;
use Repository\CategoryRepository;

//use Repository\RecipeImageRepository;
use Repository\RecipeRepository;
use Repository\UserRepository;

class RecipeService
{
    private RecipeRepository $recipeRepository;
    private CategoryRepository $categoryRepository;
//    private RecipeImageRepository $recipeImageRepository;
    private UserRepository $userRepository;
    private string $uploadDir = __DIR__ . "/../../public/images/recipes/";

    public function __construct(RecipeRepository $recipeRepository, CategoryRepository $categoryRepository, UserRepository $userRepository)
    {
        $this->recipeRepository = $recipeRepository;
        $this->categoryRepository = $categoryRepository;
//        $this->recipeImageRepository = $recipeImageRepository;
        $this->userRepository = $userRepository;
    }

    public function uploadRecipe(CreateRecipeRequest $request): void
    {
        $this->ValidateCreateRecipeRequest($request);

        try {
            Database::beginTransaction();

            $category = $this->categoryRepository->find($request->categoryId);
            if ($category === null) {
                throw new ValidationException("Category not found");
            }

            $recipe = new Recipe();
            $recipe->name = $request->name;
            $recipe->ingredients = $request->ingredients;
            $recipe->steps = $request->steps;
            $recipe->note = $request->note;
            $recipe->categoryId = $request->categoryId;
            $recipe->userId = $request->userId;

            if ($request->image && isset($request->image["tmp_name"])) {
                $extension = pathinfo($request->image["name"], PATHINFO_EXTENSION);
                $photoName = uniqid() . "." . $extension;

                move_uploaded_file($request->image["tmp_name"], $this->uploadDir . $photoName);

                $recipe->image = $photoName;
            }

            $this->recipeRepository->save($recipe);

//            $recipe = $this->recipeRepository->save($recipe);

//            $banner = null;
//
//            if (!empty($request->recipeImages)) {
//
//                foreach ($request->recipeImages["tmp_name"] as $index => $tmp) {
//
//                    $extension = pathinfo($request->recipeImages["name"][$index], PATHINFO_EXTENSION);
//                    $imgNames = uniqid() . "." . $extension;
//                    $banner = $imgNames;
//                    if (move_uploaded_file($tmp, $uploadDir . $imgNames)) {
//                        $recipeImg = new RecipeImage();
//                        $recipeImg->recipeId = $recipe->recipeId;
//                        $recipeImg->imageName = $imgNames;
//                        $this->recipeImageRepository->save($recipeImg);
//                    }
//                }
//            }
//
//            $recipe->image = $banner;
//            $this->recipeRepository->update($recipe);

            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function ValidateCreateRecipeRequest(CreateRecipeRequest $request): void
    {
        if ($request->userId == null or $request->userId == "" || $request->name == null or $request->name == "" || $request->ingredients == null or $request->ingredients == "" || $request->steps == null or $request->steps == "" || $request->note == null or $request->note == "" || $request->categoryId == null or $request->categoryId == "") {
            throw new ValidationException ("title , ingredients ,steps ,category cannot be empty");
        }

        if ($request->image == null && isset($request->image["tmp_name"])) {
            throw new ValidationException ("image cannot be empty");
        }

        if ($request->image["error"] != UPLOAD_ERR_OK) {
            throw new ValidationException ("image error");
        }

        $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($request->image["type"], $validTypes)) {
            throw new ValidationException ("image type is not allowed");
        }

        if ($request->image["size"] > 2 * 1024 * 1024) {
            throw new ValidationException ("image size is too large");
        }

//        if ($request->recipeImages == null || count($request->recipeImages) === 0) {
//            throw new ValidationException("minimum 1 image required");
//        }
//
//        foreach ($request->recipeImages["error"] as $err) {
//            if ($err !== UPLOAD_ERR_OK) {
//                throw new ValidationException("invalid file");
//            }
//        }
//
//        foreach ($request->recipeImages["type"] as $file) {
//            if (!in_array($file, $validTypes)) {
//                throw new ValidationException("Image type not allowed");
//            }
//        }
//
//        foreach ($request->recipeImages["size"] as $file) {
//            if ($file > 2 * 1024 * 1024) {
//                throw new ValidationException("Maximum file size exceeded");
//            }
//        }
    }

    public function detailRecipe(int $recipeId): ReadDetailRecipeResponse
    {
        $this->ValidateDetailRecipe($recipeId);

        $recipe = $this->recipeRepository->find($recipeId);

        if ($recipe === null) {
            throw new ValidationException("recipe not found");
        }

//        $images = $this->recipeImageRepository->findByRecipe($recipe->recipeId);

        $result = new ReadDetailRecipeResponse();
        $result->recipe = $recipe;
//        $result->images = $images;
        return $result;
    }

    private function ValidateDetailRecipe(int $request): void
    {
        if ($request == null || trim($request) === '') {
            throw new ValidationException("ID Recipe Required");
        }
    }

    public function updateRecipe(UpdateRecipeRequest $request): void
    {
        $this->ValidateUpdateRecipeRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField("user_id", $request->userId);

            if ($user == null) {
                throw new ValidationException("user not found");
            }

            $recipe = $this->recipeRepository->find($request->recipeId);
            if ($recipe == null) {
                throw new ValidationException("recipe not found");
            }

            if ($user->id != $recipe->user->id) {
                throw new ValidationException("user cannot edit recipe");
            }

            $newRecipe = new Recipe();
            $newRecipe->recipeId = $recipe->recipeId;
            $newRecipe->name = $request->name;
            $newRecipe->ingredients = $request->ingredients;
            $newRecipe->steps = $request->steps;
            $newRecipe->note = $request->note;
            $newRecipe->categoryId = $request->categoryId;

            if ($recipe->image != null && $request->image['tmp_name'] != "") {
                unlink($this->uploadDir . $recipe->image);
            }

            if ($request->image && isset($request->image["tmp_name"])) {
                $extension = pathinfo($request->image["name"], PATHINFO_EXTENSION);
                $imageName = uniqid() . "." . $extension;

                move_uploaded_file($request->image["tmp_name"], $this->uploadDir . $imageName);

                $newRecipe->image = $imageName;
            }

            $this->recipeRepository->update($newRecipe);

            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function ValidateUpdateRecipeRequest(UpdateRecipeRequest $request): void
    {
        if ($request->recipeId == "" or $request->recipeId == null || $request->name == null or $request->name == "" || $request->ingredients == null or $request->ingredients == "" || $request->steps == null or $request->steps == "" || $request->categoryId == null or $request->categoryId == "") {
            throw new ValidationException ("title , ingredients ,steps ,category cannot be empty");
        }
    }

    public function deleteRecipe(DeleteRecipeRequest $request): void
    {
        $this->ValidateDeleteRecipeRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField("user_id", $request->userId);
            if ($user === null) {
                throw new ValidationException("user not found");
            }

            $recipe = $this->recipeRepository->find($request->recipeId);
            if ($recipe === null) {
                throw new ValidationException("recipe not found");
            }

            if ($user->id != $recipe->user->id) {
                throw new ValidationException("user cannot delete recipe");
            }

            if ($recipe->image != null) {
                unlink($this->uploadDir . $recipe->image);
            }

//            $images = $this->recipeImageRepository->findByRecipe($recipe->recipeId);
//            $dirfile = __DIR__ . "/../../public/images/recipes/";
//            foreach ($images as $image) {
//                $pathImg = $dirfile . $image->imageName;
//
//                if (file_exists($pathImg)) {
//                    unlink($pathImg);
//                }
//
//                $this->recipeImageRepository->delete($image->imageId);
//            }

            $this->recipeRepository->delete($recipe->recipeId);

            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function ValidateDeleteRecipeRequest(DeleteRecipeRequest $request): void
    {
        if ($request->userId == null or empty($request->userId || $request->recipeId == null or trim($request->recipeId) != "" || $request->userId == "" || $request->recipeId == "")) {
            throw new ValidationException("UserId and recipeId required");
        }
    }

    public function searchRecipe(RecipeSearchParams $request): SearchRecipeResponse
    {
        $this->validateSearchRecipeRequest($request);
        $recipe = $this->recipeRepository->search($request);
        $result = new SearchRecipeResponse();
        $result->recipes = $recipe->recipes;
        $result->totalRecipes = $recipe->total;
        return $result;
    }

    private function validateSearchRecipeRequest(RecipeSearchParams $request): void
    {
//        if ($request->title == null && $request->category == null) {
//            throw new ValidationException("title , category cannot be empty");
//        }
    }

    public function UserRecipes(RecipeSearchParams $request): RecipeSearchResponse
    {
        $this->validateUserRecipesRequest($request);
        $user = $this->userRepository->findByField("user_id", $request->userId);
        if ($user === null) {
            throw new ValidationException("user not found");
        }
        $recipes = $this->recipeRepository->search($request);
        $result = new RecipeSearchResponse();
        $result->recipes = $recipes->recipes;
        $result->total = $recipes->total;
        return $result;
    }

    private function validateUserRecipesRequest(RecipeSearchParams $request): void
    {
        if ($request->userId == null or empty($request->userId)) {
            throw new ValidationException("userId required");
        }
    }

}