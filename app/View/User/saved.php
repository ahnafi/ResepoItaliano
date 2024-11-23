<?php
$saved = $model['savedRecipes'] ?? [];
?>

<?php
include_once __DIR__ . "/../Components/navbar.php";
?>

<!-- Aside Start -->
<div class="saved-recipes-container">
    <div class="saved-recipes">
        <!-- Tombol untuk aside -->
        <button id="asideToggle" class="aside-toggle">
            <svg fill="currentColor"
                 xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 512 512"
                 class="icon">
                <path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z">
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
        <div class="saved-recipes-content">
            <h2 class="title-font-size"><?= count($saved) > 0 ? "Daftar Resep yang disimpan" : "Tidak ada resep yang disimpan" ?></h2>
            <div class="saved-recipes-list">
                <?php foreach ($saved as $save): ?>
                    <div class="saved-recipe-item">
                        <a href="/recipe/<?= $save['recipe_id'] ?>">
                            <img src="/images/recipes/<?= $save["image"] ?>" alt="Spaghetti Carbonara"/>
                        </a>
                        <div class="user-recipe">
                            <img src="/images/profiles/<?= $save['creator_profile'] ?? "default.jpg" ?>"
                                 alt="<?= $save['creator'] ?> Foto Profil"/>
                            <p><?= $save['creator'] ?></p>
                        </div>
                        <div class="recipe-item-content">
                            <a href="/recipe/<?= $save['recipe_id'] ?>">
                                <h3 class="subtitle-font-size"><?= $save['title'] ?></h3>
                            </a>
                            <p>
                                <?php
                                $ingredients = [];
                                foreach (explode("###", $save['ingredients']) as $ingredient) {
                                    $ingredients[] = trim($ingredient);
                                }
                                echo truncateText(implode(" | ", $ingredients), 50);
                                ?>
                            </p>
                        </div>
                        <div class="recipe-time-past small-font-size">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/>
                            </svg>
                            <span><?= timeAgo($save['created_at']) ?></span>
                        </div>
                        <div class="recipe-action">
                            <form action="/recipe/save/remove" method="post" onsubmit="return verification()">
                                <input type="hidden" name="savedId" value="<?= $save['saved_id'] ?>"/>
                                <button type="submit" data-product-id="<?= $save['saved_id'] ?>">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Recipe Section End -->
    </div>
    <!-- Profile Settings End -->
</div>
<!-- Aside Start -->
<script>
    function verification() {
        return confirm("Resep ini akan dihapus dari daftar tersimpan");
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