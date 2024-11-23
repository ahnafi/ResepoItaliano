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
            <svg fill="currentColor"
                 xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 512 512"
                 class="icon">
                <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s. 6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z">
                </path>
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
            <h2 class="title-font-size"><?= count($recipes) > 0 ? "Kelola Semua Resep" : "Belum ada resep" ?></h2>
            <div class="manage-recipes-list">
                <table border="1">
                    <thead>
                    <tr>
                        <th>Judul</th>
                        <th>kategori</th>
                        <th>Pembuat</th>
                        <th>email</th>
                        <th>Waktu Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recipes as $recipe): ?>
                        <tr>
                            <td>
                                <a href="/recipe/<?= $recipe['recipeId'] ?>">
                                    <?= truncateText($recipe['name'], 20) ?>
                                </a>
                            </td>
                            <td>
                                <?= $recipe['category']->category_name ?>
                            </td>
                            <td><?= $recipe['user']->username ?></td>
                            <td><?= $recipe['user']->email ?></td>
                            <td><?= timeAgo($recipe['createdAt']) ?></td>
                            <td>
                                <a href="/recipe/update/<?= $recipe['recipeId'] ?>">edit</a>
                                <form action="/recipe/remove" method="post" onsubmit="return verification()">
                                    <input type="hidden" name="recipeId" value="<?= $recipe['recipeId'] ?>">
                                    <input type="submit" value="hapus">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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
</script>