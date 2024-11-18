let ingredientCount = 1; // Untuk menghitung jumlah bahan
let stepCount = 1; // Untuk menghitung jumlah langkah

// Navigation menu
document
    .querySelector(".hamburger-menu")
    .addEventListener("click", function () {
        document.querySelector(".navigation-container").classList.toggle("active");
        console.log("oke");
    });

// Footer year
document.querySelector("#getYear").innerHTML = new Date().getFullYear();

// Add Recipe
function addIngredient() {
    ingredientCount++; // Increment count
    const ingredientsList = document.getElementById("ingredientsList");
    const newIngredientItem = document.createElement("div");
    newIngredientItem.className = "ingredient-item";
    newIngredientItem.innerHTML = `
        <span class="ingredient-number">${ingredientCount}.</span>
        <input class="normal-font-size" type="text" required />
    `;
    ingredientsList.appendChild(newIngredientItem);
}

function addStep() {
    stepCount++; // Increment count
    const stepsList = document.getElementById("stepsList");
    const newStepItem = document.createElement("div");
    newStepItem.className = "step-item";
    newStepItem.innerHTML = `
        <span class="step-number">${stepCount}.</span>
        <input type="text" class="step normal-font-size" required />
    `;
    stepsList.appendChild(newStepItem);
}

// Fungsi untuk mengumpulkan bahan dan langkah
function gatherRecipeData() {
    const ingredients = Array.from(
        document.querySelectorAll(".ingredient-item input")
    )
        .map((input) => input.value)
        .filter((value) => value.trim() !== ""); // Filter untuk menghapus input kosong

    const steps = Array.from(document.querySelectorAll(".step-item input"))
        .map((input) => input.value)
        .filter((value) => value.trim() !== ""); // Filter untuk menghapus input kosong

    // Menggabungkan semua bahan dan langkah dengan simbol pemisah, misalnya "; "
    const ingredientsString = ingredients.join("; ");
    const stepsString = steps.join("; ");

    // Jika Anda ingin menggabungkan semua dalam satu string
    const recipeData = {
        title: document.getElementById("name").value,
        category: document.getElementById("category").value,
        ingredients: ingredientsString,
        steps: stepsString,
        note: document.getElementById("note").value,
    };

    console.log(recipeData); // Anda bisa mengirimkan ini ke database
}

// Menambahkan event listener pada tombol "Terbitkan"
document
    .getElementById("submitAside")
    .addEventListener("click", function (event) {
        event.preventDefault(); // Mencegah form kedua dari pengiriman default

        // Mengumpulkan data dari form pertama
        gatherRecipeData(); // Panggil fungsi yang mengumpulkan data dari form pertama

        // Submit form pertama
        document.getElementById("recipeForm").submit();
    });

// Profile photo upload
function previewProfilePhoto() {
    const fileInput = document.getElementById("profilePhoto");
    const previewImage = document.getElementById("profilePreview");

    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            previewImage.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}
