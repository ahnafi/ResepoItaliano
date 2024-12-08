<?php include_once __DIR__ . '/Components/navbar.php'; ?>

<style>
    .error {
        min-height: calc(100vh - 3rem);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .error img {
        max-width: 376px;
        margin-top: 4rem;
    }

</style>

<div class="error">
    <img src="/images/elements/error.svg" alt="error 404">
    <h1 class="title-font-size">Halaman Tidak Ditemukan</h1>
    <p class="small-font-size">
        Dalam <span id="time">10</span> detik akan diarahkan ke beranda
    </p>
</div>

<script>
    let i = 10;
    const countdownElement = document.getElementById('time');

    const intervalId = setInterval(() => {
        i--;
        countdownElement.innerHTML = i;

        if (i <= 0) {
            clearInterval(intervalId); // Hentikan interval
            window.location.href = '/'; // Ganti 'beranda.html' dengan URL beranda yang sesuai
        }
    }, 1000);
</script>