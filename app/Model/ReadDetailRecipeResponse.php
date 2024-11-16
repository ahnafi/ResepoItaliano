<?php

namespace Model;

use Domain\Recipe;

class ReadDetailRecipeResponse
{
    public ?Recipe $recipe = null;
    public ?array $images = null;
}