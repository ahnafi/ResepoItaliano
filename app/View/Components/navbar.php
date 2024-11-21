<!-- Navigation Start -->
<div class="navigation-container">
    <nav class="navigation-bar">
        <a href="/" class="logo">
            <img src="/images/logo.png" alt="ResepoItaliano Logo"/>
            <span class="big-normal-font-size">ResepoItaliano</span>
        </a>
        <div class="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="navigation-links-container">
            <ul class="navigation-links">
                <li class="navigation-link"><a href="/">Beranda</a></li>
                <li class="navigation-link">
                    <a href="/about">Tentang Kami</a>
                </li>
                <li class="navigation-link">
                    <a href="/recipe/add">Posting Resep</a>
                </li>
                <li class="navigation-link">
                    <a href="/search">Cari Resep</a>
                </li>
                <?php if (isset($model['user'])) : ?>
                    <li class="navigation-link">
                        <a href="/user/profile" class="profile-photo">
                            <img src="/images/profiles/<?= $model['user']['profileImage'] ?? "default.jpg" ?>"
                                 alt="Profile photo"/>
                            <span><?= $model['user']['username'] ?></span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="navigation-link">
                        <a href="/login" class="login-button">Masuk</a>
                    </li>
                    <li class="navigation-link">
                        <a href="/register" class="register-button">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>
<!-- Navigation End -->