<?php

namespace Model;

use Domain\Recipe;

class RecipeSearchResponse
{
    public array $recipes;
    public int $total;
}