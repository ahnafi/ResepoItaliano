<?php
$users = $model['data']['users'] ?? [];
$total = $model['data']['total'] ?? 0;

// Pagination setup
$perPage = 50; // Jumlah resep per halaman
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
<div class="admin-manage-users-container">
    <div class="admin-manage-users">
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
        <div class="admin-manage-users-content">
            <h2 class="subtitle-font-size"><?= count($users) > 0 ? "Kelola Semua User" : "Belum Ada User" ?></h2>
            <div class="search-form">
                <form method="get" action="">
                    <select name="role" class="normal-font-size">
                        <option selected value="">Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <input name="username" type="search" placeholder="Search Username" aria-label="Search"
                           class="normal-font-size">
                    <input name="email" type="search" placeholder="Search Email" aria-label="Search"
                           class="normal-font-size">
                    <button type="submit" class="normal-font-size">Search</button>
                </form>
            </div>
            <div class="admin-manage-users-list">
                <table>
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?= $user['id'] ?>
                            </td>
                            <td>
                                <?= $user['username'] ?>
                            </td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['role'] == 'admin' ? 'Admin' : 'Pengguna' ?></td>
                            <td><img src="/images/profiles/<?= $user['profileImage'] ?? "default.jpg" ?>"
                                     alt="Foto profil <?= $user['username'] ?>" class="profile-photos">
                            </td>
                            <td class="action">
                                <a href="/admin/profile/manage-users/update/<?= $user['id'] ?>" class="update-recipe">Ubah</a>
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