<?php
include_once __DIR__ . "/../Components/navbar.php";
?>
<!-- Login Start -->
<div class="login-container normal-font-size">
    <div class="greeting-container">
        <h1 class="title-font-size">Selamat Datang Kembali!</h1>
        <p>
            Jika belum punya akun, bisa dilanjutkan langsung ke halaman daftar ya!
        </p>
        <a href="/register">Daftar Di Sini</a>
    </div>
    <div class="login-form">
        <div class="login-form-header">
            <h1 class="title-font-size">Masuk</h1>
            <p>Masuk ke akunmu dan mulai berbagi resep masakan Italia</p>
        </div>
        <form action="#" method="POST" class="login-form-body">
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
            <input type="submit" value="Daftar" class="normal-font-size"/>
        </form>
    </div>
</div>
<!-- Login End -->