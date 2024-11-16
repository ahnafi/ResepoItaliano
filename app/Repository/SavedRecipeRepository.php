<?php

namespace Repository;

use Domain\SavedRecipes;

class SavedRecipeRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(SavedRecipes $savedRecipes): SavedRecipes
    {
        $statement = $this->connection->prepare("INSERT INTO saved_recipes (recipe_id,user_id) VALUES (?,?)");
        $statement->execute([$savedRecipes->recipeId, $savedRecipes->userId]);
        $savedRecipes->savedId = $this->connection->lastInsertId();
        return $savedRecipes;
    }

    public function delete(SavedRecipes $savedRecipes)
    {
        $statement = $this->connection->prepare("DELETE FROM saved_recipes WHERE saved_id = ? AND user_id = ?");
        $statement->execute([$savedRecipes->savedId, $savedRecipes->userId]);
    }

    public function find(int $savedRecipesId): ?SavedRecipes
    {
        $statement = $this->connection->prepare("SELECT saved_id,recipe_id,user_id FROM saved_recipes WHERE saved_id = ?");
        $statement->execute([$savedRecipesId]);

        try {
            if ($row = $statement->fetch()) {
                $savedRecipes = new SavedRecipes();
                $savedRecipes->savedId = $row['saved_id'];
                $savedRecipes->recipeId = $row['recipe_id'];
                $savedRecipes->userId = $row['user_id'];
                return $savedRecipes;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function getSaved(int $userId): array
    {
        // Query untuk mengambil resep yang disimpan berdasarkan user_id
        $query = "
        SELECT  
            recipes.recipe_id, 
            recipes.name, 
            recipes.image, 
            recipes.created_at, 
            categories.category_name ,
            saved_id
        FROM saved_recepies
        INNER JOIN recipes ON saved_recepies.recipe_id = recipes.recipe_id
        INNER JOIN categories ON recipes.category_id = categories.category_id
        WHERE saved_recepies.user_id = ?
        ORDER BY recipes.created_at DESC
    ";

        $statement = $this->connection->prepare($query);

        $statement->bindValue(1, $userId, \PDO::PARAM_INT);

        $statement->execute();

        // Ambil hasilnya
        $savedRecipes = [];
        while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $savedRecipes[] = [
                'saved_id' => $row['saved_id'],
                'recipe_id' => $row['recipe_id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'created_at' => $row['created_at'],
                'category_name' => $row['category_name'],
            ];
        }

        // Return array berisi resep yang disimpan
        return $savedRecipes;
    }
}