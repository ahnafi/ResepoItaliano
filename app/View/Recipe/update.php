<?php
$foodItems = [
    'Pizza',
    'Pasta',
    'Risotto',
    'Gelato',
    'Tiramisu',
    'Burrata',
    'Bruschetta'
];
$recipe = $model['recipe'] ?? [];
$ingredients = explode("###", $recipe['ingredients']);
$steps = explode("###", $recipe['steps']);
?>

<!-- navbar -->
<?php
include_once __DIR__ . "/../Components/navbar.php";
?>

<!-- Add Recipe Start -->
<div class="add-recipe-container">
    <div class="add-recipe">
        <div class="add-recipe-content">
            <div class="add-recipe-head">
                <h1 class="title-font-size">Ubah Resep</h1>
            </div>
            <div class="add-recipe-form">
                <form id="recipeForm" method="post" action="/recipe/update/<?= $recipe['recipeId'] ?>"
                      enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="normal-font-size" for="name">Judul Resep:</label>
                        <input type="text" id="name" required class="normal-font-size" name="title"
                               value="<?= $recipe['name'] ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="category">Kategori:</label>
                        <select id="category" required class="normal-font-size" name="categoryId">
                            <?php foreach ($foodItems as $key => $category): ?>
                                <option value="<?= $key + 1 ?>"><?= $category ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="ingredients">
                            Bahan-bahan:
                        </label>
                        <div id="ingredientsList">
                            <?php foreach ($ingredients as $key => $ingredient): ?>
                                <div class="ingredient-item">
                                    <span class="ingredient-number"><?= $key + 1 ?></span>
                                    <input class="normal-font-size" type="text" id="ingredients" required
                                           value="<?= $ingredient ?>"/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="normal-font-size" type="button" onclick="addIngredient()">
                            Tambah Bahan
                        </button>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="steps">
                            Langkah-langkah:
                        </label>
                        <div id="stepsList">
                            <?php foreach ($steps as $key => $step): ?>
                                <div class="step-item">
                                    <span class="step-number"><?= $key + 1 ?></span>
                                    <input type="text" class="step normal-font-size" id="steps" required
                                           value="<?= $step ?>"/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="normal-font-size" type="button" onclick="addStep()">
                            Tambah Langkah
                        </button>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="note">Catatan:</label>
                        <textarea id="note" rows="6" name="note"><?= $recipe['note']?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="image">
                            Unggah Gambar:
                        </label>
                        <div class="custom-file-upload">
                            <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png" required>
                            <span>Pilih Gambar</span>
                        </div>
                        <div id="image-preview-container" class="image-preview-container">
                            <img src="/images/recipes/<?= $recipe['image'] ?>" alt="photo recipe" class="image-preview">
                        </div>
                    </div>
                    <input type="hidden" name="ingredients" id="valIngredients">
                    <input type="hidden" name="steps" id="valSteps">
                </form>
            </div>
        </div>
        <div class="add-recipe-aside">
            <div class="add-recipe-aside-head">
                <h2 class="subtitle-font-size">Petunjuk</h2>
            </div>
            <div class="add-recipe-aside-content">
                <p class="normal-font-size">
                    Isi form di samping dengan lengkap dan benar. Pastikan semua
                    informasi yang kamu masukkan benar agar resep yang kamu bagikan
                    dapat dinikmati oleh semua orang.
                </p>
            </div>
            <div class="add-recipe-aside-action">
                <form id="asideForm" action="/">
                    <button type="submit" id="submitAside" name="upload">
                        Perbarui
                    </button>
                    <button type="button" name="clear">Bersihkan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Recipe End -->