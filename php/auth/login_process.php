<?php
session_start();
require_once 'db_connection.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../php/pages/home.php");
    exit;
}

// Hanya proses POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../../login.xhtml?error=" . urlencode("Method tidak diizinkan"));
    exit;
}

// Ambil data dari form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input
$errors = [];

if (empty($username)) {
    $errors[] = "Username/Email harus diisi";
}

if (empty($password)) {
    $errors[] = "Password harus diisi";
}

// Jika ada error, redirect kembali dengan pesan error
if (!empty($errors)) {
    $errorMessage = implode(", ", $errors);
    header("Location: ../../login.xhtml?error=" . urlencode($errorMessage));
    exit;
}

// Cari user di database
try {
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cek apakah user ditemukan
    if ($result->num_rows === 0) {
        header("Location: ../../login.xhtml?error=" . urlencode("Username/Email tidak ditemukan"));
        exit;
    }
    
    // Ambil data user
    $user = $result->fetch_assoc();
    
    // Verifikasi password (dengan password_hash)
    if (!password_verify($password, $user['password'])) {
        // Log failed attempt (opsional)
        error_log("Failed login attempt for username: $username from IP: " . $_SERVER['REMOTE_ADDR']);
        
        header("Location: ../../login.xhtml?error=" . urlencode("Password salah"));
        exit;
    }
    
    // Cek jika akun aktif (jika ada kolom status)
    if (isset($user['status']) && $user['status'] != 'active') {
        header("Location: ../../login.xhtml?error=" . urlencode("Akun tidak aktif. Hubungi administrator."));
        exit;
    }
    
    // LOGIN BERHASIL
    
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['email'] = $user['email'];
    
    // Set login time
    $_SESSION['login_time'] = time();
    
    // Set session expiry (8 jam)
    $_SESSION['expiry_time'] = time() + (8 * 60 * 60);
    
    // Log successful login (opsional)
    error_log("Successful login for user_id: " . $user['user_id'] . " from IP: " . $_SERVER['REMOTE_ADDR']);
    
    // Redirect ke dashboard berdasarkan role
    if (($user['role'] ?? 'user') === 'admin') {
        header("Location: ../../admin/dashboard.php");
    } else {
        header("Location: ../../xhtml/home.xhtml");
    }
    exit;
    
} catch (Exception $e) {
    // Log error
    error_log("Login error: " . $e->getMessage());
    
    // Redirect dengan error umum (jangan tampilkan detail error ke user)
    header("Location: ../../xhtml/login.xhtml?error=" . urlencode("Terjadi kesalahan sistem. Silakan coba lagi."));
    exit;
}

// Tutup koneksi
$conn->close();
?>