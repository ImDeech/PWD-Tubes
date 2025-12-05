<?php
session_start();
require_once 'db_connection.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: ../../php/pages/home.php");
    exit;
}

// Hanya POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../login.xhtml?error=" . urlencode("Method tidak diizinkan"));
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header("Location: ../../login.xhtml?error=" . urlencode("Username dan Password wajib diisi"));
    exit;
}

try {
    // Cari user berdasarkan username atau email
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: ../../login.xhtml?error=" . urlencode("Username/Email tidak ditemukan"));
        exit;
    }

    $user = $result->fetch_assoc();

    // Verifikasi password
    if (!password_verify($password, $user['password'])) {
        header("Location: ../../login.xhtml?error=" . urlencode("Password salah"));
        exit;
    }

    // ---- CEK ADMIN TANPA KOLOM ROLE ----
    $checkAdmin = $conn->prepare("SELECT admin_id FROM admin WHERE user_id = ?");
    $checkAdmin->bind_param("i", $user['user_id']);
    $checkAdmin->execute();
    $isAdmin = $checkAdmin->get_result()->num_rows > 0;

    // Simpan session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['email'] = $user['email'];

    $_SESSION['is_admin'] = $isAdmin; // penting

    // Waktu login
    $_SESSION['login_time'] = time();
    $_SESSION['expiry_time'] = time() + (60 * 60 * 8);

    // Redirect sesuai role
    if ($isAdmin) {
        header("Location: ../../php/admin/admin_kost.php");
    } else {
        header("Location: ../../php/pages/home.php");
    }
    exit;

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    header("Location: ../../login.xhtml?error=" . urlencode("Kesalahan sistem"));
    exit;
}

?>
