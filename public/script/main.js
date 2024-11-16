// Navigation menu
document
    .querySelector(".hamburger-menu")
    .addEventListener("click", function () {
        document.querySelector(".navigation-container").classList.toggle("active");
        console.log("oke");
    });

// Footer year
document.querySelector("#getYear").innerHTML = new Date().getFullYear();
