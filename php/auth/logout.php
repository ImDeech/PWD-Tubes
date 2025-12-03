<?php
// File: php/auth/logout.php
// HANYA PROSES LOGOUT, TIDAK ADA OUTPUT HTML

session_start();

// Hapus semua session variables
$_SESSION = array();

// Hapus session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header("Location: ../../index.xhtml?success=" . urlencode("Anda telah logout"));
exit;
?>