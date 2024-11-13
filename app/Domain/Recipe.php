<?php

namespace Domain;

class Recipe
{
    public ?int $recipeId = null;
    public string $name;
    public string $ingredients;
    public string $steps;
    public ?string $note = null;
    public ?string $image = null;
    public ?string $createdAt = null;
    public ?int $userId = null;
    public ?int $categoryId = null;
//    not in table database
    public ?string $categoryName = null;
}