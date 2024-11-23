<?php
include_once __DIR__ . "/../Components/navbar.php";
?>
<!-- Register Start -->
<div class="register-container normal-font-size">
    <div class="greeting-container">
        <h1 class="title-font-size">Hallo, Selamat Datang!</h1>
        <p>
            Jika sudah punya akun, bisa dilanjutkan langsung ke halaman masuk ya!
        </p>
        <a href="/login">Masuk Di Sini</a>
    </div>
    <div class="register-form">
        <div class="register-form-header">
            <h1 class="title-font-size">Daftar</h1>
            <p>Buat akun barumu dan mulai jelajahi masakan khas Italia</p>
        </div>
        <form action="/register" method="POST" class="register-form-body">
            <input
                    type="text"
                    id="name"
                    name="username"
                    placeholder="Masukkan username..."
                    class="normal-font-size"
                    pattern="[A-Za-z0-9\s]+"
                    maxlength="100"
            />
            <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Masukkan email..."
                    class="normal-font-size"
            />
            <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password..."
                    class="normal-font-size"
            />
            <input
                    type="password"
                    id="confirm-password"
                    name="confirm-password"
                    placeholder="Masukkan password kembali..."
                    class="normal-font-size"
            />
            <input type="submit" value="Daftar" class="normal-font-size"/>
        </form>
    </div>
</div>
<!-- Register End -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
    });
</script>