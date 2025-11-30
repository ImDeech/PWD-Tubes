// 1. Toggle Menu
var menuBtn = document.getElementById("menuBtn");
var navMenu = document.getElementById("navMenu");

if (menuBtn && navMenu) {
    menuBtn.addEventListener("click", function () {
        if (navMenu.style.display === "block") {
            navMenu.style.display = "none";
        } else {
            navMenu.style.display = "block";
        }
    });
}

// 2. Show / Hide Password
var passField = document.getElementById("password");

if (passToggle && passField) {
    passToggle.addEventListener("click", function () {
        if (passField.type === "password") {
            passField.type = "text";
        } else {
            passField.type = "password";
        }
    });
}

// 3. Highlight Input Saat Focus
var inputs = document.querySelectorAll("input");

inputs.forEach(function (input) {
    input.addEventListener("focus", function () {
        input.style.borderColor = "#4f8dff";
        input.style.backgroundColor = "#eef4ff";
    });

    input.addEventListener("blur", function () {
        input.style.borderColor = "#ccc";
        input.style.backgroundColor = "white";
    });
});

// 4. Button Hover Effect
var buttons = document.querySelectorAll("button");

buttons.forEach(function (btn) {
    btn.addEventListener("mouseover", function () {
        btn.style.opacity = "0.8";
        btn.style.transform = "scale(1.03)";
    });

    btn.addEventListener("mouseout", function () {
        btn.style.opacity = "1";
        btn.style.transform = "scale(1)";
    });
});