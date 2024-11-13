<?php

namespace Domain;

class RecipeImage
{
    public ?int $imageId = null;
    public int $recipeId;
    public string $imageName;
}