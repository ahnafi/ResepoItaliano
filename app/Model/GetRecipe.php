<?php

namespace Model;

use Domain\Category;
use Domain\User;

class GetRecipe
{
    public ?int $recipeId = null;
    public string $name;
    public string $ingredients;
    public string $steps;
    public ?string $note = null;
    public ?string $image = null;
    public ?string $createdAt = null;
    public ?User $user = null;
    public ?Category $category = null;
}