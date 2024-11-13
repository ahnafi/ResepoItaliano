<?php

namespace Repository;

use Domain\Recipe;
use Model\RecipeSearchParams;
use Model\RecipeSearchResponse;

class RecipeRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Recipe $recipe): Recipe
    {

        $statement = $this->connection->prepare("INSERT INTO recipes (name,ingredients,steps,note,image,user_id,category_id) VALUES (?,?,?,?,?,?)");
        $statement->execute([$recipe->name, $recipe->ingredients, $recipe->steps, $recipe->note, $recipe->image, $recipe->userId, $recipe->categoryId]);
        $recipe->recipeId = $this->connection->lastInsertId();

        return $recipe;
    }

    public function update(Recipe $recipe): Recipe
    {
        $statement = $this->connection->prepare("UPDATE recipes SET name=?,ingredients=?,steps=?,note=?,category_id=?, image = ? WHERE id=?");
        $statement->execute([$recipe->name, $recipe->ingredients, $recipe->steps, $recipe->note, $recipe->categoryId, $recipe->image, $recipe->recipeId]);

        return $recipe;
    }

    public function delete(int $recipeId): void
    {
        $statement = $this->connection->prepare("DELETE FROM recipes WHERE id=?");
        $statement->execute([$recipeId]);
    }

    public function find(int $recipeId): ?Recipe
    {
        $statement = $this->connection->prepare("SELECT recipes.*, categories.name AS category_name
       FROM recipes
        INNER JOIN categories ON recipes.category_id = categories.category_id WHERE id=?");
        $statement->execute([$recipeId]);

        try {
            if ($row = $statement->fetch()) {
                $recipe = new Recipe();
                $recipe->recipeId = $row['recipe_id'];
                $recipe->name = $row['name'];
                $recipe->ingredients = $row['ingredients'];
                $recipe->steps = $row['steps'];
                $recipe->note = $row['note'];
                $recipe->userId = $row['user_id'];
                $recipe->createdAt = $row['created_at'];
                $recipe->categoryId = $row['category_id'];
                $recipe->categoryName = $row['category_name'];
                return $recipe;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function search(RecipeSearchParams $params): RecipeSearchResponse
    {
        $limit = 20;
        $offset = ($params->page - 1) * $limit;

        // Query untuk menghitung total data sesuai filter (tanpa LIMIT dan OFFSET)
        $countQuery = "
        SELECT COUNT(*) AS total
        FROM recipes
        INNER JOIN categories ON recipes.category_id = categories.category_id
        WHERE 1=1
    ";

        $queryParams = [];
        if ($params->title !== null) {
            $countQuery .= " AND recipes.name LIKE ?";
            $queryParams[] = "%{$params->title}%";
        }

        if ($params->category !== null) {
            $countQuery .= " AND recipes.category_id = ?";
            $queryParams[] = $params->category;
        }

        if ($params->userId !== null) {
            $countQuery .= " AND recipes.user_id = ?";
            $queryParams[] = $params->userId;
        }

        // Jalankan query untuk menghitung total data
        $countStatement = $this->connection->prepare($countQuery);

        foreach ($queryParams as $index => $param) {
            $countStatement->bindValue($index + 1, $param);
        }

        $countStatement->execute();
        $total = $countStatement->fetchColumn();

        // Query untuk mengambil data dengan LIMIT dan OFFSET
        $query = "
        SELECT recipes.*, categories.name AS category_name
        FROM recipes
        INNER JOIN categories ON recipes.category_id = categories.category_id
        WHERE 1=1
    ";

        if ($params->title !== null) {
            $query .= " AND recipes.name LIKE ?";
        }

        if ($params->category !== null) {
            $query .= " AND recipes.category_id = ?";
        }

        if ($params->userId !== null) {
            $query .= " AND recipes.user_id = ?";
        }

        $query .= " LIMIT ? OFFSET ?";
        $statement = $this->connection->prepare($query);

        foreach ($queryParams as $index => $param) {
            $statement->bindValue($index + 1, $param);
        }

        // Bind nilai untuk limit dan offset
        $statement->bindValue(count($queryParams) + 1, $limit, \PDO::PARAM_INT);
        $statement->bindValue(count($queryParams) + 2, $offset, \PDO::PARAM_INT);

        $statement->execute();

        $recipes = [];

        // Ambil hasil dan masukkan ke dalam objek Recipe
        while ($row = $statement->fetch()) {
            $recipe = new Recipe();
            $recipe->recipeId = $row['recipe_id'];
            $recipe->name = $row['name'];
            $recipe->ingredients = $row['ingredients'];
            $recipe->steps = $row['steps'];
            $recipe->note = $row['note'];
            $recipe->userId = $row['user_id'];
            $recipe->categoryId = $row['category_id'];
            $recipe->createdAt = $row['created_at'];
            $recipe->categoryName = $row['category_name'];

            $recipes[] = $recipe;
        }

        // Return data resep dan total count
        $result = new RecipeSearchResponse();
        $result->total = $total;
        $result->recipes = $recipes;
        return $result;
    }


}