<?php

namespace Model;

use Domain\Recipe;

class ReadDetailRecipeResponse
{
    public Recipe $recipe;
    public ?array $images = null;
}