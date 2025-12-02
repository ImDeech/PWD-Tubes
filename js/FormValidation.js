// EMAIL REGEX
function isValidEmail(email) {
    var regex = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    return regex.test(email);
}

// PASSWORD CHECK
function isStrongPassword(password) {
    var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])(?!.*\s).{6,}$/;
    return regex.test(password);
}

// VALIDASI FORM REGISTER
var registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function (e) {

        var valid = true;

        var nama = document.getElementById("nama").value.trim();
        var username = document.getElementById("username").value.trim();
        var email = document.getElementById("email").value.trim();
        var password = document.getElementById("password").value.trim();

        var namaErr = document.getElementById("namaError");
        var userErr = document.getElementById("usernameError");
        var emailErr = document.getElementById("emailError");
        var passErr = document.getElementById("passwordError");

        namaErr.innerHTML = "";
        userErr.innerHTML = "";
        emailErr.innerHTML = "";
        passErr.innerHTML = "";

        // Validasi Nama
        if (nama === "") {
            namaErr.innerHTML = "Nama tidak boleh kosong!";
            valid = false;
        }

        // Validasi Username
        if (username === "") {
            userErr.innerHTML = "Username tidak boleh kosong!";
            valid = false;
        } 
        else if (username.length < 4) {
            userErr.innerHTML = "Username minimal 4 karakter!";
            valid = false;
        }

        // Validasi Email
        if (email === "") {
            emailErr.innerHTML = "Email tidak boleh kosong!";
            valid = false;
        } 
        else if (!isValidEmail(email)) {
            emailErr.innerHTML = "Format email tidak valid!";
            valid = false;
        }

        // Validasi Password
        if (password === "") {
            passErr.innerHTML = "Password tidak boleh kosong!";
            valid = false;
        } 
        else if (password.length < 6) {
            passErr.innerHTML = "Password minimal 6 karakter!";
            valid = false;
        } 
        else if (!isStrongPassword(password)) {
            passErr.innerHTML = "Password harus mengandung huruf, angka, dan simbol!";
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
}

// VALIDASI FORM LOGIN
var loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function (e) {

        var valid = true;

        var username = document.getElementById("loginUsername").value.trim();
        var password = document.getElementById("loginPassword").value.trim();

        var userErr = document.getElementById("loginUserError");
        var passErr = document.getElementById("loginPassError");

        userErr.innerHTML = "";
        passErr.innerHTML = "";

        if (username === "") {
            userErr.innerHTML = "Username tidak boleh kosong!";
            valid = false;
        }

        if (password === "") {
            passErr.innerHTML = "Password tidak boleh kosong!";
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
}
