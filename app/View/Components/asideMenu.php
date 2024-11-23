<?php
$user = $model['user'] ?? [];
?>
<aside id="asideMenu" class="aside-menu">
    <?php if ($user['role'] == 'admin'): ?>
        <div class="menu-content">
            <h2>Settings</h2>
            <a href="/admin/profile" class="aside-link">Pengaturan Akun</a>
            <a href="/admin/profile/password" class="aside-link">Ganti Password</a>
            <a href="/admin/profile/manage-recipes" class="aside-link">
                Kelola Resep
            </a>
            <a href="/admin/profile/manage-users" class="aside-link">
                Kelola User
            </a>
            <a href="/admin/profile/register-admin" class="aside-link">
                Tambah Admin
            </a>
            <a href="/logout" class="aside-link exit">Keluar</a>
        </div>
    <?php else: ?>
        <div class="menu-content">
            <h2>Settings</h2>
            <a href="/user/profile" class="aside-link">Pengaturan Akun</a>
            <a href="/user/profile/password" class="aside-link">Ganti Password</a>
            <a href="/user/profile/saved-recipe" class="aside-link">
                Resep Tersimpan
            </a>
            <a href="/user/profile/manage-recipes" class="aside-link">
                Kelola Resep
            </a>
            <a href="/logout" class="aside-link exit">Keluar</a>
        </div>
    <?php endif; ?>
</aside>