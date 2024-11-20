<?php

namespace Service;

use Config\Database;
use Domain\SavedRecipes;
use Exception\ValidationException;
use Model\AddSavedRecipesRequest;
use Model\RemoveSavedRecipe;
use Repository\RecipeRepository;
use Repository\SavedRecipeRepository;
use Repository\UserRepository;

class SavedRecipeService
{
    private SavedRecipeRepository $savedRecipeRepository;
    private UserRepository $userRepository;
    private RecipeRepository $recipeRepository;

    public function __construct(SavedRecipeRepository $savedRecipeRepository, UserRepository $userRepository, RecipeRepository $recipeRepository)
    {
        $this->savedRecipeRepository = $savedRecipeRepository;
        $this->userRepository = $userRepository;
        $this->recipeRepository = $recipeRepository;
    }

    public function add(AddSavedRecipesRequest $request): SavedRecipes
    {
        $this->validateAddSavedRecipeRequest($request);
        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField("user_id", $request->userId);

            if ($user == null) {
                throw new ValidationException("user not found");
            }

            $recipe = $this->recipeRepository->find($request->recipeId);

            // validasi apakah sudah ada saved recipes yang sudah disimpan
            if ($this->savedRecipeRepository->alreadySaved($user->id, $recipe->recipeId)) {
                throw new ValidationException("recipe has already been saved");
            }

            $saved = new SavedRecipes();
            $saved->recipeId = $recipe->recipeId;
            $saved->userId = $user->id;

            $saved = $this->savedRecipeRepository->save($saved);

            Database::commitTransaction();
            return $saved;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateAddSavedRecipeRequest(AddSavedRecipesRequest $request): void
    {
        if ($request->userId == null || $request->recipeId == null or $request->userId == "" or $request->recipeId == "" or empty($request->userId) or empty($request->recipeId)) {
            throw new ValidationException("UserId and recipeId are required");
        }
    }

    public function remove(RemoveSavedRecipe $request): void
    {
        $this->validateRemoveSavedRecipeRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField("user_id", $request->userId);

            if ($user == null) {
                throw new ValidationException("user not found");
            }

            $saved = $this->savedRecipeRepository->find($request->savedId);

            if ($saved == null) {
                throw new ValidationException("saved recipe not found");
            }

            if ($this->recipeRepository->find($saved->recipeId) == null) {
                throw new ValidationException("recipe not found");
            }

            $this->savedRecipeRepository->delete($saved);

            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateRemoveSavedRecipeRequest(RemoveSavedRecipe $request): void
    {
        if ($request->userId == null || $request->savedId == null or $request->userId == "" or $request->savedId == "" or empty($request->userId)) {
            throw new ValidationException("UserId and SaveId are required");
        }
    }

    public function getSavedRecipes(int $userId): array
    {
        if ($userId == null) {
            throw new ValidationException("user not found");
        }

        $user = $this->userRepository->findByField("user_id", $userId);
        if ($user == null) {
            throw new ValidationException("user not found");
        }

        return $this->savedRecipeRepository->getSaved($user->id);
    }

}