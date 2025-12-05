<?php
session_start();
require_once 'db_connection.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../php/pages/home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../register.xhtml?error=" . urlencode("Method tidak diizinkan"));
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$no_telp = trim($_POST['no_telp'] ?? '');

$errors = [];

// --- VALIDASI ---
if (empty($nama) || strlen($nama) < 3) $errors[] = "Nama minimal 3 karakter";
if (empty($username) || strlen($username) < 3) $errors[] = "Username minimal 3 karakter";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid";
if (strlen($password) < 8) $errors[] = "Password minimal 8 karakter";
if ($password !== $confirm_password) $errors[] = "Konfirmasi password tidak cocok";

if (!empty($no_telp)) {
    $no_telp = preg_replace('/\D/', '', $no_telp);
    if (strlen($no_telp) < 10 || strlen($no_telp) > 13)
        $errors[] = "Nomor telepon harus 10–12 digit";
} else {
    $no_telp = null;
}

if (!empty($errors)) {
    header("Location: ../../register.xhtml?error=" . urlencode(implode(", ", $errors)));
    exit;
}

try {
    // Cek username
    $checkUsername = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    if ($checkUsername->get_result()->num_rows > 0) {
        header("Location: ../../register.xhtml?error=" . urlencode("Username sudah digunakan"));
        exit;
    }

    // Cek email
    $checkEmail = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    if ($checkEmail->get_result()->num_rows > 0) {
        header("Location: ../../register.xhtml?error=" . urlencode("Email sudah digunakan"));
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, nama, email, password, no_telp, created_at)
                            VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $username, $nama, $email, $password_hash, $no_telp);
    $stmt->execute();

    $user_id = $stmt->insert_id;

    // ------------------------------
    // AUTO REGISTER ADMIN
    // ------------------------------
    $isNameAdmin = (strtolower($nama) === "admin");
    $isUsernameAdmin = (strpos(strtolower($username), "admin") !== false);

    if ($isNameAdmin && $isUsernameAdmin) {
        $insertAdmin = $conn->prepare("INSERT INTO admin (user_id, created_at) VALUES (?, NOW())");
        $insertAdmin->bind_param("i", $user_id);
        $insertAdmin->execute();
        $_SESSION['is_admin'] = true;
    } else {
        $_SESSION['is_admin'] = false;
    }

    // Auto-login
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['nama'] = $nama;
    $_SESSION['email'] = $email;

    // Redirect
    if ($_SESSION['is_admin']) {
        header("Location: ../admin/admin_kost.php?success=" . urlencode("Admin berhasil dibuat"));
    } else {
        header("Location: ../../php/pages/home.php?success=" . urlencode("Registrasi berhasil!"));
    }
    exit;

} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    header("Location: ../../register.xhtml?error=" . urlencode("Kesalahan sistem"));
    exit;
}

?>
