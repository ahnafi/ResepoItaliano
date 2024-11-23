<div>
    <h1>Error 404: Page Not Found</h1>
    Dalam <span id="time">10</span> detik redirect ke beranda
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