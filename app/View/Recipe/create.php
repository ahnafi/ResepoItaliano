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
                <h1 class="title-font-size">Tambahkan Resep</h1>
            </div>
            <div class="add-recipe-form">
                <form id="recipeForm" method="post" action="/recipe/add" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="normal-font-size" for="name">Judul Resep:</label>
                        <input pattern="[A-Za-z0-9\s]+" maxlength="50" type="text" id="name" required class="normal-font-size" name="title"/>
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
                            <div class="ingredient-item">
                                <span class="ingredient-number">1.</span>
                                <input pattern="[A-Za-z0-9\s,./]+" maxlength="200" class="normal-font-size" type="text" id="ingredients" required/>
                            </div>
                        </div>
                        <button class="normal-font-size add-ingredients" type="button" onclick="addIngredient()">
                            Tambah Bahan
                        </button>
                        <button class="normal-font-size delete-ingredients" type="button" onclick="removeIngredient()">
                            Hapus Bahan
                        </button>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="steps">
                            Langkah-langkah:
                        </label>
                        <div id="stepsList">
                            <div class="step-item">
                                <span class="step-number">1.</span>
                                <input pattern="[A-Za-z0-9\s,./]+" maxlength="200" type="text" class="step normal-font-size" id="steps" required/>
                            </div>
                        </div>
                        <button class="normal-font-size add-steps" type="button" onclick="addStep()">
                            Tambah Langkah
                        </button>
                          <button class="normal-font-size delete-steps" type="button" onclick="removeStep()">
                            Hapus Langkah
                        </button>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="note">Catatan (opsional):</label>
                        <textarea id="note" rows="6" name="note" class="normal-font-size"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="image">
                            Unggah Gambar:
                        </label>
                        <div class="custom-file-upload">
                            <input type="file" id="image-recipe-upload" name="image" accept=".jpg, .jpeg, .png" required>
                            <span>Pilih Gambar</span>
                        </div>
                        <div id="image-preview-container" class="image-preview-container">
                        </div>
                    </div>
                    <input type="hidden" name="ingredients" id="valIngredients" >
                    <input type="hidden" name="steps" id="valSteps" >
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
                <form id="asideForm" action="">
                    <button class="normal-font-size" type="submit" id="submitAside" name="upload">
                        Terbitkan
                    </button>
                    <button id="clearButton" class="normal-font-size" type="button" name="clear">Bersihkan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Recipe End -->