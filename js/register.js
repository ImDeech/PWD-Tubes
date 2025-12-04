// Toggle show/hide password
function togglePasswordVisibility(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleBtn = passwordField.nextElementSibling;
            
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleBtn.innerHTML = '⌣';
    } else {
        passwordField.type = 'password';
        toggleBtn.innerHTML = '👁';
    }
}
        
// Check password strength
function checkPasswordStrength(password) {
    let strength = 0;
    const requirements = {
        length: false,
        uppercase: false,
        lowercase: false,
        number: false
    };
            
    // Check length
    if (password.length >= 8) {
        strength += 25;
        requirements.length = true;
        document.getElementById('reqLength').className = 'requirement met';
    } else {
        document.getElementById('reqLength').className = 'requirement unmet';
    }
            
    // Check uppercase
    if (/[A-Z]/.test(password)) {
        strength += 25;
        requirements.uppercase = true;
        document.getElementById('reqUppercase').className = 'requirement met';
    } else {
        document.getElementById('reqUppercase').className = 'requirement unmet';
    }
            
    // Check lowercase
    if (/[a-z]/.test(password)) {
        strength += 25;
        requirements.lowercase = true;
        document.getElementById('reqLowercase').className = 'requirement met';
    } else {
        document.getElementById('reqLowercase').className = 'requirement unmet';
    }
            
    // Check number
    if (/[0-9]/.test(password)) {
        strength += 25;
        requirements.number = true;
        document.getElementById('reqNumber').className = 'requirement met';
    } else {
        document.getElementById('reqNumber').className = 'requirement unmet';
    }
            
    // Update strength bar
    const strengthBar = document.getElementById('strengthBar');
    strengthBar.className = 'strength-bar';
            
    if (strength <= 25) {
        strengthBar.classList.add('strength-weak');
    } else if (strength <= 75) {
        strengthBar.classList.add('strength-medium');
    } else {
        strengthBar.classList.add('strength-strong');
    }
            
    return requirements;
}
        
// Check password match
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const matchDiv = document.getElementById('passwordMatch');
        
    if (confirmPassword === '') {
        matchDiv.innerHTML = '';
        matchDiv.style.color = '';
        return false;
    }
            
    if (password === confirmPassword) {
        matchDiv.innerHTML = '✓ Password cocok';
        matchDiv.style.color = '#00C851';
        return true;
    } else {
        matchDiv.innerHTML = '✗ Password tidak cocok';
        matchDiv.style.color = '#ff4444';
        return false;
    }
}
        
// Show error message
function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
            
    errorText.textContent = message;
    errorDiv.style.display = 'block';
    document.getElementById('successMessage').style.display = 'none';
    
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 5000);
}
        
// Show success message
function showSuccess(message) {
    const successDiv = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    
    successText.textContent = message;
    successDiv.style.display = 'block';
    document.getElementById('errorMessage').style.display = 'none';
}
        
// Set loading state
function setLoading(isLoading) {
    const registerBtn = document.getElementById('registerBtn');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');
            
    if (isLoading) {
        registerBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline';
    } else {
        registerBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
    }
}
        
// Validate form
function validateForm() {
    const nama = document.getElementById('nama').value.trim();
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const no_telp = document.getElementById('no_telp').value.trim();
            
    const errors = [];
            
    // Validasi nama
    if (nama === '') {
        errors.push('Nama lengkap harus diisi');
    } else if (nama.length < 3) {
        errors.push('Nama minimal 3 karakter');
    } else if (nama.length > 100) {
        errors.push('Nama maksimal 100 karakter') 
    }
            
    // Validasi username
    if (username === '') {
        errors.push('Username harus diisi');
    }
    else if (username.length < 3) {
        errors.push('Username minimal 3 karakter');
    } else if (username.length > 50) {
        errors.push('Username maksimal 50 karakter');
    } else if (!/^[a-zA-Z0-9_.]+$/.test(username)) {
        // PERBAIKI PESAN ERROR INI:
        errors.push('Username hanya boleh huruf, angka, underscore (_), dan titik (.)');
    }
            
    // Validasi email
    if (email === '') {
        errors.push('Email harus diisi');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.push('Format email tidak valid');
    } else if (email.length > 100) errors.push('Email maksimal 100 karakter');
            
    // Validasi password
    const requirements = checkPasswordStrength(password);
    if (password.length < 8) errors.push('Password minimal 8 karakter');
    if (!requirements.uppercase) errors.push('Password harus mengandung huruf besar');
    if (!requirements.lowercase) errors.push('Password harus mengandung huruf kecil');
    if (!requirements.number) errors.push('Password harus mengandung angka');
            
    // Validasi konfirmasi password
    if (!checkPasswordMatch()) errors.push('Password tidak cocok');
            
    // Validasi telepon (jika diisi)
    if (no_telp !== '') {
        const phoneRegex = /^[0-9]{10,12}$/;
        const cleanedPhone = no_telp.replace(/\D/g, '');
        if (!phoneRegex.test(cleanedPhone)) {
            errors.push('Nomor telepon harus 10-12 digit angka');
        }
    }
            
    if (errors.length > 0) {
        showError(errors.join(', '));
        return false;
    }
            
    return true;
}
        
// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Check URL for messages
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');
    const successParam = urlParams.get('success');
            
    if (errorParam) {
        showError(decodeURIComponent(errorParam));
    }
            
    if (successParam) {
        showSuccess(decodeURIComponent(successParam));
    }
            
    // Auto focus
    document.getElementById('nama').focus();
            
    // Real-time validation
    document.getElementById('password').addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });
            
    document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
            
    // Username validation (no spaces)
    document.getElementById('username').addEventListener('input', function() {
        // Hapus spasi dan karakter selain yang diizinkan
        this.value = this.value.replace(/[^a-zA-Z0-9_.]/g, '');
    });
            
    // Phone number formatting
    document.getElementById('no_telp').addEventListener('input', function() {
        // Allow only numbers and format
        this.value = this.value.replace(/\D/g, '');
    });
            
    // Form submission
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
                
        if (!validateForm()) {
            return;
        }
                
        setLoading(true);
            
        // Submit form
        this.submit();
    });
});