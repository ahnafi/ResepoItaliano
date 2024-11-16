<?php

namespace Domain;

class SavedRecipes
{
    public ?int $savedId = null;
    public int $recipeId;
    public int $userId;
}