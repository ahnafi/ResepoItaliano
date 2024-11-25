<?php
$updateUser = $model['updateUser'] ?? [];
?>

<?php
include_once __DIR__ . "/../Components/navbar.php"
?>

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
            <h1 class="big-normal-font-size">Perbarui Akun <?= $updateUser['username'] ?></h1>
            <div class="profile-settings-form">
                <form action="/admin/profile/manage-users/update/<?= $updateUser['id'] ?>" method="post"
                      enctype="multipart/form-data">
                    <div id="profileSettingsPhoto" class="form-group profile-settings-photo">
                        <img src="/images/profiles/<?= $updateUser["profileImage"] ?? "default.jpg" ?>"
                             alt="Profile photo"
                             class="profile-photo"
                             id="profilePreview"/>
                        <div class="profile-image-upload">
                            <label for="profilePhoto" class="font-semibold cursor-pointer text-light-base">
                                Ganti Foto Profil
                            </label>
                            <input type="file" name="profile" id="profilePhoto" accept=".jpg, .jpeg, .png"
                                   onchange="previewProfilePhoto()"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="username" id="name" placeholder="Nama..." required
                               value="<?= $updateUser["username"] ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email..." readonly
                               value="<?= $updateUser["email"] ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Password Baru (opsional)</label>
                        <input type="password" name="newPassword" id="newPassword"
                               placeholder="Buat Password baru ..."/>
                    </div>
                    <div class="form-group">
                        <label for="verifyPassword">Verifikasi Password baru</label>
                        <input type="password" name="" id="verifyPassword"
                               placeholder="Verifikasi password ..."/>
                    </div>
                    <button type="submit" class="save-button">
                        Simpan Perubahan
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

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector(".profile-settings-form form");
        const newPasswordInput = document.getElementById("newPassword");
        const verifyPasswordInput = document.getElementById("verifyPassword");

        form.addEventListener("submit", (event) => {
            const newPassword = newPasswordInput.value;
            const verifyPassword = verifyPasswordInput.value;

            if (newPassword !== verifyPassword) {
                event.preventDefault(); // Mencegah form dikirim
                alert("Password baru dan verifikasi password tidak cocok.");
            }
        });
    });

</script>