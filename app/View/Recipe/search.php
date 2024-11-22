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

//current url
$url = $_SERVER["REQUEST_URI"];

// Pagination setup
$perPage = count($recipes) > 0 ? count($recipes) : 20; // Jumlah resep per halaman
$totalPages = ceil($totalRecipes / $perPage); // Hitung total halaman
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ambil halaman saat ini
$currentPage = max(1, min($totalPages, $currentPage)); // Validasi halaman saat ini

// Function to build pagination URL
function buildPaginationUrl($page)
{
    $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
    parse_str($parsedUrl['query'] ?? '', $queryParams);
    unset($queryParams['page']); // Hapus parameter 'page' jika ada
    $queryParams['page'] = $page; // Tambahkan parameter 'page' baru
    $newQuery = http_build_query($queryParams); // Bangun kembali query string
    return $parsedUrl['path'] . ($newQuery ? '?' . $newQuery : ''); // Gabungkan kembali
}

?>

<!--navbar-->
<?php
include_once __DIR__ . "/../Components/navbar.php";
?>

<!-- Recipe Section Start -->
<div class="search-container">
    <div class="search">
        <div class="search-head">
            <h1 class="title-font-size">
                <?php
                $text = "Mau masak ";
                $title = isset($_GET["title"]) ? htmlspecialchars($_GET["title"]) : '';
                $categoryKey = $_GET["cat"] ?? null;

                if ($title == null and $categoryKey == "") $text = "Mau Masak Apa Kita Hari Ini?";
                else if ($title) {
                    $text .= $title;
                } else {
                    if ($categoryKey == "0") {
                        $categoryKey = 1;
                    }
                    $text .= $category[$categoryKey - 1];
                }
                echo $text;
                ?>
            </h1>
            <div class="search-form">
                <form method="get" action="/search">
                    <select name="cat" class="normal-font-size">
                        <option selected value=""> Pilih Kategori</option>
                        <?php foreach ($category as $key => $item): ?>
                            <option value="<?= $key + 1 ?>"><?= $item ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input name="title" type="search" placeholder="Search Recipe" aria-label="Search"
                           class="normal-font-size">
                    <button type="submit" class="normal-font-size">Search</button>
                </form>
            </div>
        </div>
        <div class="search-body">
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
                            <span><?= timeAgo($recipe['createdAt']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Pagination -->
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= buildPaginationUrl($currentPage - 1) ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= buildPaginationUrl($i) ?>"
                       class="<?= ($i === $currentPage) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= buildPaginationUrl($currentPage + 1) ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Recipe Section End -->