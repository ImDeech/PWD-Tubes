<?php
session_start();

// Cek jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml?error=Silakan login dulu");
    exit;
}

// Ambil nama user
$nama = $_SESSION['nama'] ?? "";

// Load halaman XHTML
$html = file_get_contents("../../xhtml/pembayaran.xhtml");

// Replace placeholder (kalau kamu ingin menambahkannya)
$html = str_replace("{{NAMA_USER}}", htmlspecialchars($nama), $html);

// Tampilkan halaman final
echo $html;
