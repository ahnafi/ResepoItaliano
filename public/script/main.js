let ingredientCount = 1; // Untuk menghitung jumlah bahan
let stepCount = 1; // Untuk menghitung jumlah langkah

// Navigation menu
document
    .querySelector(".hamburger-menu")
    .addEventListener("click", function () {
        document.querySelector(".navigation-container").classList.toggle("active");
    });

// Footer year
document.querySelector("#getYear").innerHTML = new Date().getFullYear();

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

function removeIngredient() {
    const ingredientsList = document.getElementById("ingredientsList");
    if (ingredientsList.children.length > 1) { // Pastikan ada lebih dari satu bahan
        ingredientsList.removeChild(ingredientsList.lastElementChild); // Hapus elemen terakhir
        ingredientCount--; // Decrement count
        updateIngredientNumbers(); // Perbarui nomor bahan
    }
}

function removeStep() {
    const stepsList = document.getElementById("stepsList");
    if (stepsList.children.length > 1) { // Pastikan ada lebih dari satu langkah
        stepsList.removeChild(stepsList.lastElementChild); // Hapus elemen terakhir
        stepCount--; // Decrement count
        updateStepNumbers(); // Perbarui nomor langkah
    }
}

function updateIngredientNumbers() {
    const ingredientsList = document.getElementById("ingredientsList");
    const items = ingredientsList.getElementsByClassName("ingredient-item");
    for (let i = 0; i < items.length; i++) {
        items[i].getElementsByClassName("ingredient-number")[0].innerText = (i + 1) + ".";
    }
}

function updateStepNumbers() {
    const stepsList = document.getElementById("stepsList");
    const items = stepsList.getElementsByClassName("step-item");
    for (let i = 0; i < items.length; i++) {
        items[i].getElementsByClassName("step-number")[0].innerText = (i + 1) + ".";
    }
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
    const ingredientsString = ingredients.join("###");
    const stepsString = steps.join("###");

    const recipeData = {
        title: document.getElementById("name").value,
        category: document.getElementById("category").value,
        ingredients: ingredientsString,
        steps: stepsString,
        note: document.getElementById("note").value,
    };

    console.log(recipeData)

    document.getElementById("valIngredients").value = ingredientsString;
    document.getElementById("valSteps").value = stepsString;

}

// Menambahkan event listener pada tombol "Terbitkan"
const btnSubmit = document
    .getElementById("submitAside");
btnSubmit.addEventListener("click", function (event) {
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
