<?php
// File: php/auth/register_process.php
// SESUAI STRUKTUR TABEL: user_id, username, nama, email, password, no_telp, created_at

session_start();
require_once 'db_connection.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../dashboard.php");
    exit;
}

// Hanya proses POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../../register.xhtml?error=" . urlencode("Method tidak diizinkan"));
    exit;
}

// Ambil data dari form
$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$no_telp = trim($_POST['no_telp'] ?? '');

// Validasi input
$errors = [];

// Validasi nama (sesuai database: VARCHAR(100))
if (empty($nama)) {
    $errors[] = "Nama lengkap harus diisi";
} elseif (strlen($nama) < 3) {
    $errors[] = "Nama minimal 3 karakter";
} elseif (strlen($nama) > 100) {
    $errors[] = "Nama maksimal 100 karakter";
}

// Validasi username (sesuai database: VARCHAR(50))
if (empty($username)) {
    $errors[] = "Username harus diisi";
} elseif (strlen($username) < 3) {
    $errors[] = "Username minimal 3 karakter";
} elseif (strlen($username) > 50) {
    $errors[] = "Username maksimal 50 karakter";
} elseif (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
    $errors[] = "Username hanya boleh huruf, angka, dan underscore (_)";
}

// Validasi email (sesuai database: VARCHAR(100))
if (empty($email)) {
    $errors[] = "Email harus diisi";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid";
} elseif (strlen($email) > 100) {
    $errors[] = "Email maksimal 100 karakter";
}

// Validasi password (sesuai database: VARCHAR(255))
if (empty($password)) {
    $errors[] = "Password harus diisi";
} elseif (strlen($password) < 8) {
    $errors[] = "Password minimal 8 karakter";
} elseif (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password harus mengandung minimal 1 huruf besar";
} elseif (!preg_match('/[a-z]/', $password)) {
    $errors[] = "Password harus mengandung minimal 1 huruf kecil";
} elseif (!preg_match('/[0-9]/', $password)) {
    $errors[] = "Password harus mengandung minimal 1 angka";
}

// Validasi konfirmasi password
if ($password !== $confirm_password) {
    $errors[] = "Password tidak cocok";
}

// Validasi no_telp (sesuai database: VARCHAR(20))
if (!empty($no_telp)) {
    // Hapus semua non-digit
    $no_telp = preg_replace('/\D/', '', $no_telp);
    
    if (strlen($no_telp) < 10 || strlen($no_telp) > 13) {
        $errors[] = "Nomor telepon harus 10-13 digit angka";
    } elseif (strlen($no_telp) > 20) {
        $errors[] = "Nomor telepon maksimal 20 karakter";
    }
} else {
    $no_telp = null; // Set null jika kosong
}

// Jika ada error, redirect kembali
if (!empty($errors)) {
    $errorMessage = implode(", ", $errors);
    header("Location: ../../register.xhtml?error=" . urlencode($errorMessage));
    exit;
}

try {
    // Cek apakah username sudah terdaftar (UNIQUE constraint)
    $checkUsername = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    
    if ($checkUsername->get_result()->num_rows > 0) {
        header("Location: ../../register.xhtml?error=" . urlencode("Username sudah terdaftar"));
        exit;
    }
    
    // Cek apakah email sudah terdaftar (UNIQUE constraint)
    $checkEmail = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    
    if ($checkEmail->get_result()->num_rows > 0) {
        header("Location: ../../register.xhtml?error=" . urlencode("Email sudah terdaftar"));
        exit;
    }
    
    // Hash password dengan PASSWORD_DEFAULT (bcrypt)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert ke database sesuai struktur tabel
    $stmt = $conn->prepare("INSERT INTO users (username, nama, email, password, no_telp, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    
    if ($no_telp === null || $no_telp === '') {
        // Jika no_telp kosong, insert NULL
        $stmt->bind_param("sssss", $username, $nama, $email, $password_hash, $no_telp);
    } else {
        $stmt->bind_param("sssss", $username, $nama, $email, $password_hash, $no_telp);
    }
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        
        // Auto login setelah registrasi (opsional)
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'user'; // Default role karena tabel tidak ada kolom role
        
        // Log aktivitas
        error_log("New registration - user_id: $user_id, username: $username, email: $email");
        
        // Redirect ke dashboard
        header("Location: ../../dashboard.php?success=" . urlencode("Registrasi berhasil! Selamat datang $nama"));
        exit;
    } else {
        // Tangkap error MySQL
        if ($conn->errno == 1062) { // Duplicate entry error
            if (strpos($conn->error, 'username') !== false) {
                header("Location: ../../register.xhtml?error=" . urlencode("Username sudah terdaftar"));
            } elseif (strpos($conn->error, 'email') !== false) {
                header("Location: ../../register.xhtml?error=" . urlencode("Email sudah terdaftar"));
            } else {
                header("Location: ../../register.xhtml?error=" . urlencode("Data sudah terdaftar"));
            }
        } else {
            throw new Exception("Database error: " . $conn->error);
        }
        exit;
    }
    
} catch (Exception $e) {
    // Log error
    error_log("Registration error: " . $e->getMessage());
    
    // Redirect dengan error umum
    header("Location: ../../register.xhtml?error=" . urlencode("Terjadi kesalahan sistem. Silakan coba lagi."));
    exit;
}

$conn->close();
?>