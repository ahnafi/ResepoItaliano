<?php

namespace Model;

class CreateRecipeRequest
{
    public ?string $name = null;
    public ?string $ingredients = null;
    public ?string $steps = null;
    public ?string $note = null;
    public ?array $image = null;
    public ?int $userId = null;
    public ?int $categoryId = null;
//    public ?array $recipeImages = null;
}