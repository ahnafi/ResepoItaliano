<?php

namespace Repository;

use Domain\RecipeImage;

class RecipeImageRepository
{

    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(RecipeImage $postImage): RecipeImage
    {
        $statement = $this->connection->prepare("INSERT INTO recipe_images (recipe_id, image_name) VALUES (?, ?)");
        $statement->execute([$postImage->recipeId, $postImage->imageName]);
        return $postImage;
    }

    public function findByRecipe(int $postId): array
    {
        $statement = $this->connection->prepare("SELECT image_id,recipe_id,image_name FROM recipe_images WHERE recipe_id = ?");
        $statement->execute([$postId]);

        $data = [];
        while ($rows = $statement->fetchAll(\PDO::FETCH_ASSOC)) {
            foreach ($rows as $row) {

                $img = new RecipeImage();
                $img->imageId = $row['image_id'];
                $img->recipeId = $row['recipe_id'];
                $img->imageName = $row['image_name'];

                $data[] = $img;
            }
        }

        return $data;

    }

    public function update(RecipeImage $postImage): RecipeImage
    {
        $statement = $this->connection->prepare("UPDATE recipe_images SET image_name = ? WHERE image_id = ?");
        $statement->execute([$postImage->imageName, $postImage->imageId]);
        return $postImage;
    }

    public function delete(int $postImageId): void
    {
        $statement = $this->connection->prepare("DELETE FROM recipe_images WHERE image_id = ?");
        $statement->execute([$postImageId]);
    }

    public function deleteAll(): void
    {
        $statement = $this->connection->prepare("DELETE FROM recipe_images");
        $statement->execute();
    }

}
