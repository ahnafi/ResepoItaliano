<?php
$recipe = $model["recipe"] ?? [];
$images = $recipe["recipe_images"];
$ingredients = explode("###", $recipe["ingredients"]);
$steps = explode("###", $recipe["steps"]);
?>

<!--navbar-->
<?php
include_once __DIR__ . "/../Components/navbar.php";
?>

<!-- Detail Start -->
<div class="detail-container">
    <div class="detail">
        <div class="detail-content">
            <div class="detail-head">
                <div class="detail-image">
                    <img src="/images/recipes/<?= $recipe["banner"] ?>" alt="photo <?= $recipe["title"] ?>"/>
                </div>
                <h2 class="title-font-size"><?= $recipe["title"] ?></h2>
                <p class="category"><?= $recipe["category_name"] ?></p>
            </div>
            <div class="detail-profile">
                <img src="/images/profiles/<?= $recipe["creator_image"] ?? 'default.jpg' ?>"
                     alt="<?= $recipe["creator_name"] ?> Foto Profile"/>
                <div class="detail-profile-credential">
                    <p class="detail-profile-username"><?= $recipe["creator_name"] ?></p>
                    <p class="detail-profile-email"><?= $recipe["creator_email"] ?></p>
                </div>
            </div>
            <div class="detail-body">
                <div class="detail-description">
                    <p>
                        <?= $recipe["note"] ?>
                    </p>
                    <div class="detail-ingredients">
                        <h2 class="subtitle-font-size">Bahan-bahan:</h2>
                        <ol>
                            <?php foreach ($ingredients as $ingredient): ?>
                                <li><?= $ingredient ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                    <div class="detail-instructions">
                        <h2 class="subtitle-font-size">Cara membuat:</h2>
                        <ol>
                            <?php foreach ($steps as $step): ?>
                                <li><?= $step ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="detail-action">
                <form method="post" action="/recipe/save/<?= $recipe['recipe_id'] ?>">
                    <button type="submit" class="normal-font-size">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"/>
                        </svg>
                        Simpan Resep
                    </button>
                    <button type="button" id="share" class="normal-font-size">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M307 34.8c-11.5 5.1-19 16.6-19 29.2l0 64-112 0C78.8 128 0 206.8 0 304C0 417.3 81.5 467.9 100.2 478.1c2.5 1.4 5.3 1.9 8.1 1.9c10.9 0 19.7-8.9 19.7-19.7c0-7.5-4.3-14.4-9.8-19.5C108.8 431.9 96 414.4 96 384c0-53 43-96 96-96l96 0 0 64c0 12.6 7.4 24.1 19 29.2s25 3 34.4-5.4l160-144c6.7-6.1 10.6-14.7 10.6-23.8s-3.8-17.7-10.6-23.8l-160-144c-9.4-8.5-22.9-10.6-34.4-5.4z"/>
                        </svg>
                        Bagikan
                    </button>
                    <button type="button" id="printPage" class="normal-font-size">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                        </svg>
                        Cetak Resep
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Detail End -->
<script>
    document.getElementById("printPage").addEventListener("click", () => {
        window.print();
    })

    document.getElementById("share").addEventListener("click", () => {
        const shareUrl = window.location.href
        const shareText = "Lihat resep ini!";
        const whatsappUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(shareText + " " + shareUrl)}`;
        window.open(whatsappUrl, '_blank');
    })
</script>