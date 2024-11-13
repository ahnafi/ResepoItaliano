<?php

namespace Service;

use Config\Database;
use Domain\Recipe;
use Domain\RecipeImage;
use Exception\ValidationException;
use Model\CreateRecipeRequest;
use Model\DeleteRecipeRequest;
use Model\ReadDetailRecipeResponse;
use Model\RecipeSearchParams;
use Model\RecipeSearchResponse;
use Model\SearchRecipeResponse;
use Repository\CategoryRepository;
use Repository\RecipeImageRepository;
use Repository\RecipeRepository;
use Repository\UserRepository;

class RecipeService
{
    private RecipeRepository $recipeRepository;
    private CategoryRepository $categoryRepository;
    private RecipeImageRepository $recipeImageRepository;
    private UserRepository $userRepository;

    public function __construct(RecipeRepository $recipeRepository, CategoryRepository $categoryRepository, RecipeImageRepository $recipeImageRepository, UserRepository $userRepository)
    {
        $this->recipeRepository = $recipeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->recipeImageRepository = $recipeImageRepository;
        $this->userRepository = $userRepository;
    }

    public function uploadRecipe(CreateRecipeRequest $request): void
    {
        $this->ValidateCreateRecipeRequest($request);
        try {
            Database::rollbackTransaction();

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

            $recipe = $this->recipeRepository->save($recipe);

            $banner = null;

            if (!empty($request->photos)) {
                $uploadDir = __DIR__ . "/../../public/img/recipes/";

                foreach ($request->photos["tmp_name"] as $index => $tmp) {

                    $extension = pathinfo($request->photos["name"][$index], PATHINFO_EXTENSION);
                    $imgNames = uniqid() . "." . $extension;
                    $banner = $imgNames;

                    if (move_uploaded_file($tmp, $uploadDir . $imgNames)) {
                        $recipeImg = new RecipeImage();
                        $recipeImg->recipeId = $recipe->recipeId;
                        $recipeImg->imageName = $imgNames;
                        $this->recipeImageRepository->save($recipeImg);
                    }
                }
            }

            $recipe->image = $banner;
            $this->recipeRepository->update($recipe);
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

        if ($request->recipeImages == null || count($request->recipeImages) === 0) {
            throw new ValidationException("minimum 1 image required");
        }

        foreach ($request->recipeImages["error"] as $err) {
            if ($err !== UPLOAD_ERR_OK) {
                throw new ValidationException("invalid file");
            }
        }

        foreach ($request->recipeImages["type"] as $file) {
            $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file, $validTypes)) {
                throw new ValidationException("Image type not allowed");
            }
        }

        foreach ($request->recipeImages["size"] as $file) {
            $maxSize = 2 * 1024 * 1024;
            if ($file > $maxSize) {
                throw new ValidationException("Maximum file size exceeded");
            }
        }
    }

    public function detailRecipe(int $recipeId): ReadDetailRecipeResponse
    {
        $this->ValidateDetailRecipe($recipeId);

        $recipe = $this->recipeRepository->find($recipeId);

        if ($recipe === null) {
            throw new ValidationException("recipe not found");
        }

        $images = $this->recipeImageRepository->findByRecipe($recipe->recipeId);

        $result = new ReadDetailRecipeResponse();
        $result->recipe = $recipe;
        $result->images = $images;
        return $result;
    }

    private function ValidateDetailRecipe(int $request): void
    {
        if ($request == null || trim($request) === '') {
            throw new ValidationException("ID Recipe Required");
        }
    }

    public function updateRecipe($request): void
    {
        $this->ValidateUpdateRecipeRequest($request);

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

            if ($user->id != $recipe->userId) {
                throw new ValidationException("user cannot edit recipe");
            }

            $recipe->name = $request->name;
            $recipe->ingredients = $request->ingredients;
            $recipe->steps = $request->steps;
            $recipe->note = $request->note;
            $recipe->categoryId = $request->categoryId;
            $this->recipeRepository->update($recipe);

            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function ValidateUpdateRecipeRequest($request): void
    {
        if ($request->name == null or $request->name == "" || $request->ingredients == null or $request->ingredients == "" || $request->steps == null or $request->steps == "" || $request->note == null or $request->note == "" || $request->categoryId == null or $request->categoryId == "") {
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

            if ($user->id != $recipe->userId) {
                throw new ValidationException("user cannot delete recipe");
            }

            $images = $this->recipeImageRepository->findByRecipe($recipe->recipeId);
            $dirfile = __DIR__ . "/../../public/img/recipes/";
            foreach ($images as $image) {
                $pathImg = $dirfile . $image->imageName;

                if (file_exists($pathImg)) {
                    unlink($pathImg);
                }

                $this->recipeImageRepository->delete($image->imageId);
            }

            $this->recipeRepository->delete($recipe->recipeId);

            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function ValidateDeleteRecipeRequest(DeleteRecipeRequest $request): void
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
        if ($request->title == null && $request->category == null) {
            throw new ValidationException("title , category cannot be empty");
        }
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