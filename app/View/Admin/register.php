<?php
$user = $model['user'] ?? [];
?>

<?php
include_once __DIR__ . "/../Components/navbar.php"
?>
<!-- Aside Start -->
<div class="profile-settings-container">
    <div class="profile-settings">
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
        <div class="profile-settings-content">
            <h1 class="big-normal-font-size">Buat Admin baru</h1>
            <div class="profile-settings-form">
                <form action="/admin/profile/register-admin" method="POST" class="register-form-body">
                    <div class="form-group">
                        <label for="username">Buat Username</label>
                        <input type="text" name="username" id="username"
                               placeholder="Masukan username..."
                               required/>
                    </div>
                    <div class="form-group">
                        <label for="email">Masukan Email</label>
                        <input type="email" name="email" id="email"
                               placeholder="Masukan email..."
                               required/>
                    </div>
                    <div class="form-group">
                        <label for="password">Buat Password yang aman</label>
                        <input type="password" name="password" id="password"
                               placeholder="buat pasword 8 huruf 1 huruf besar,1 kecil dan angka ..."/>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Verifikasi Password</label>
                        <input type="password" name="confirmPassword" id="confirm-password"
                               placeholder="Konfirmasi password kembali..."/>
                    </div>
                    <button type="submit" class="save-button">
                        Tambah Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Profile Settings End -->
</div>
<!-- Footer Start -->
<script>
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

    const form = document.querySelector('.register-form-body');

    form.addEventListener('submit', function (event) {
        // Mengambil nilai dari input
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        // Regex untuk memvalidasi format email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validasi email
        if (!emailPattern.test(email)) {
            alert('Format email tidak valid!');
            event.preventDefault(); // Mencegah form dari pengiriman
            return;
        }

        // Validasi password
        if (password !== confirmPassword) {
            alert('Password dan konfirmasi password harus sama!');
            event.preventDefault(); // Mencegah form dari pengiriman
            return;
        }
    });
</script>