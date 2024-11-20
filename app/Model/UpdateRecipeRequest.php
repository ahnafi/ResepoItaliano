<?php

namespace Model;

class UpdateRecipeRequest
{
    public ?int $recipeId = null;
    public ?string $name = null;
    public ?string $ingredients = null;
    public ?string $steps = null;
    public ?string $note = null;
    public ?array $image = null;
    public ?int $userId = null;
    public ?int $categoryId = null;
}