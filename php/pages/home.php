<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml");
    exit;
}

$namaUser = $_SESSION['nama'] ?? "User";

// Ambil file home.xhtml
$html = file_get_contents("../../xhtml/home.xhtml");

// Ganti placeholder
$html = str_replace("{{NAMA_USER}}", htmlspecialchars($namaUser), $html);

// Tampilkan
echo $html;
?>
