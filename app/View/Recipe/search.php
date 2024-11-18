<?php
$data = $model["data"] ?? [];
$recipes = $data["recipes"];
$totalRecipes = $data["total"];
$category = [
    'Pizza',
    'Pasta',
    'Risotto',
    'Gelato',
    'Tiramisu',
    'Burrata',
    'Bruschetta'
];
?>

<!--navbar-->
<?php
include_once __DIR__ . "/../Components/navbar.php";
?>

<!-- Recipe Section Start -->
<div class="search-container">
    <div class="search">
        <h2 class="title-font-size">
            <?php
            $text = "Mencari ";

            $title = isset($_GET["title"]) ? htmlspecialchars($_GET["title"]) : '';
            $categoryKey = $_GET["category"] ?? null;

            if ($title) {
                $text .= $title;
            }

            if ($categoryKey !== null && isset($category[$categoryKey])) {
                $text .= ", " . htmlspecialchars($category[$categoryKey]);
            }

            if ($title == null and $categoryKey == null) $text = "Resep Italy";

            echo $text;
            ?>
        </h2>
        <!-- <h2 class="title-font-size">Hasil pencarian "Enak"</h2> -->
        <div class="search-list">
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-item">
                    <a href="/recipe/<?= $recipe['recipeId'] ?>">
                        <img src="/images/recipes/<?= $recipe['image'] ?>" alt="image of <?= $recipe['name'] ?>"/>
                    </a>
                    <div class="user-recipe">
                        <img src="/images/profiles/<?= $recipe['user']->profileImage ?? 'default.jpg' ?>"
                             alt="<?= $recipe['user']->username ?> Foto Profil"/>
                        <p><?= $recipe['user']->username ?></p>
                    </div>
                    <div class="recipe-item-content">
                        <a href="/recipe/<?= $recipe['recipeId'] ?>">
                            <h3 class="subtitle-font-size">
                                <?= $recipe['name'] ?>
                            </h3>
                        </a>
                        <p>
                            <?php
                            $ingredients = [];
                            foreach (explode("###", $recipe['ingredients']) as $ingredient) {
                                $ingredients[] = trim($ingredient);
                            }
                            echo truncateText(implode(" | ", $ingredients), 50);
                            ?>
                        </p>
                    </div>
                    <div class="recipe-time-past small-font-size">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/>
                        </svg>
                        <span><?= timeAgo($recipe['createdAt']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Recipe Section End -->
