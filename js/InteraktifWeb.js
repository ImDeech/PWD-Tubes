// 1. MENU BURGER (opsional, hanya kalau elemen ada)
var menuBtn = document.getElementById("menuBtn");
var navMenu = document.getElementById("navMenu");

if (menuBtn && navMenu) {
    menuBtn.addEventListener("click", function () {
        navMenu.style.display = (navMenu.style.display === "block") ? "none" : "block";
    });
}

// 2. SHOW/HIDE PASSWORD (dengan id toggle-password dari login.xhtml)
var passToggle = document.querySelector(".toggle-password");
var passField = document.getElementById("password");

if (passToggle && passField) {
    passToggle.addEventListener("click", function () {
        if (passField.type === "password") {
            passField.type = "text";
            passToggle.innerHTML = "⌣";
        } else {
            passField.type = "password";
            passToggle.innerHTML = "👁";
        }
    });
}

// 3. INPUT HIGHLIGHT
var inputs = document.querySelectorAll("input");

for (var i = 0; i < inputs.length; i++) {

    inputs[i].addEventListener("focus", function () {
        this.style.borderColor = "#4f8dff";
        this.style.backgroundColor = "#eef4ff";
    });

    inputs[i].addEventListener("blur", function () {
        this.style.borderColor = "#ccc";
        this.style.backgroundColor = "white";
    });
}

// 4. BUTTON HOVER
var buttons = document.querySelectorAll("button");

for (var j = 0; j < buttons.length; j++) {

    buttons[j].addEventListener("mouseover", function () {
        this.style.opacity = "0.8";
        this.style.transform = "scale(1.03)";
    });

    buttons[j].addEventListener("mouseout", function () {
        this.style.opacity = "1";
        this.style.transform = "scale(1)";
    });
}
