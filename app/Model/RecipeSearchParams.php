<?php

namespace Model;

class RecipeSearchParams
{
    public ?string $title = null;
    public ?int $category = null;
    public ?int $userId = null;
    public int $page = 1;

}