// Navigation menu
const hamburger = document.querySelector(".hamburger-menu")

hamburger.addEventListener("click", function () {
    document.querySelector(".navigation-container").classList.toggle("active");
});

// Footer year
document.querySelector("#getYear").innerHTML = new Date().getFullYear();
