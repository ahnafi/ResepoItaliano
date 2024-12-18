<?php
$recipes = $model['recipes'] ?? [];
$total = $model['total'] ?? 0;

// Pagination setup
$perPage = 20; // Jumlah resep per halaman
$totalPages = ceil($total / $perPage); // Hitung total halaman
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

<!-- Aside Start -->
<div class="manage-recipes-container">
    <div class="manage-recipes">
        <!-- Tombol untuk aside -->
        <button id="asideToggle" class="aside-toggle">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon">
                <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"></path>
            </svg>
        </button>
        <!-- Aside -->
        <?php
        include_once __DIR__ . "/../Components/asideMenu.php";
        ?>
        <!-- Aside End -->
        <!-- Profile Settings Start -->
        <!-- Recipe Section Start -->
        <div class="manage-recipes-content">
            <h2 class="title-font-size"><?= count($recipes) > 0 ? "Resep Saya" : "Belum Mengunggah Resep" ?></h2>
            <div class="manage-recipes-list">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="manage-recipe-item">
                        <a href="/recipe/<?= $recipe['recipeId'] ?>">
                            <img src="/images/recipes/<?= $recipe["image"] ?>" alt="<?= $recipe['name'] ?> image"/>
                        </a>
                        <div class="user-recipe">
                            <img src="/images/profiles/<?= $recipe['user']->profileImage ?? "default.jpg" ?>"
                                 alt="<?= $recipe['user']->username ?> Foto Profil"/>
                            <p><?= $recipe['user']->username ?></p>
                        </div>
                        <div class="recipe-item-content">
                            <a href="/recipe/<?= $recipe['recipeId'] ?>">
                                <h3 class="card-title-font-size"><?= $recipe['name'] ?></h3>
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
                                <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"
                                />
                            </svg>
                            <span><?= timeAgo($recipe['createdAt']) ?></span>
                        </div>
                        <div class="recipe-action">
                            <a href="/recipe/update/<?= $recipe['recipeId'] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                                    <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                                </svg>
                            </a>
                            <form action="/recipe/remove" method="post" onsubmit="return verification()">
                                <input type="hidden" name="recipeId" value="<?= $recipe['recipeId'] ?>"/>
                                <button type="submit" data-product-id="">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor">
                                        <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?php
                // Pagination
                if ($currentPage > 1): ?>
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
        <!-- Recipe Section End -->
    </div>
    <!-- Profile Settings End -->
</div>
<!-- Aside Start -->
<script>
    function verification() {
        return confirm("Resep ini akan dihapus");
    }

    const asideMenu = document.getElementById("asideMenu");
    const asideToggle = document.getElementById("asideToggle");

    asideToggle.addEventListener("click", () => {
        const isOpen = asideMenu.style.transform === "translateX(0%)";
        asideMenu.style.transform = isOpen
            ? "translateX(-100%)"
            : "translateX(0%)";
        asideToggle.style.transform = isOpen
            ? "translateX(0)"
            : "translateX(16rem)";
    });
</script>