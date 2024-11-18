<!-- navbar -->
<?php
include_once __DIR__ . "/../Components/navbar.php";
?>


<!-- Add Recipe Start -->
<div class="add-recipe-container">
    <div class="add-recipe">
        <div class="add-recipe-content">
            <div class="add-recipe-head">
                <h1 class="title-font-size">Tambah Resep</h1>
            </div>
            <div class="add-recipe-form">
                <form id="recipeForm">
                    <div class="form-group">
                        <label class="normal-font-size" for="name">Judul Resep:</label>
                        <input
                                type="text"
                                id="name"
                                required
                                class="normal-font-size"
                        />
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="category">Kategori:</label>
                        <select id="category" required class="normal-font-size">
                            <option value="Pizza">Pizza</option>
                            <option value="Pasta">Pasta</option>
                            <option value="Risotto">Risotto</option>
                            <option value="Gelato">Gelato</option>
                            <option value="Tiramisu">Tiramisu</option>
                            <option value="Burrata">Burrata</option>
                            <option value="Bruschetta">Bruschetta</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="ingredients"
                        >Bahan-bahan:</label
                        >
                        <div id="ingredientsList">
                            <div class="ingredient-item">
                                <span class="ingredient-number">1.</span>
                                <input class="normal-font-size" type="text" required/>
                            </div>
                        </div>
                        <button
                                class="normal-font-size"
                                type="button"
                                onclick="addIngredient()"
                        >
                            Tambah Bahan
                        </button>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="steps"
                        >Langkah-langkah:</label
                        >
                        <div id="stepsList">
                            <div class="step-item">
                                <span class="step-number">1.</span>
                                <input type="text" class="step normal-font-size" required/>
                            </div>
                        </div>
                        <button
                                class="normal-font-size"
                                type="button"
                                onclick="addStep()"
                        >
                            Tambah Langkah
                        </button>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="note">Catatan:</label>
                        <textarea id="note" rows="6"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="normal-font-size" for="image"
                        >Unggah Gambar:</label
                        >
                        <div class="custom-file-upload">
                            <input type="file" id="image" accept="image/*" multiple/>
                            <span>Pilih Gambar</span>
                        </div>
                        <div
                                id="image-preview-container"
                                class="image-preview-container"
                        ></div>
                    </div>
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
                    <button type="submit" id="submitAside" name="upload">
                        Terbitkan
                    </button>
                    <button type="button" name="clear">Bersihkan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Recipe End -->