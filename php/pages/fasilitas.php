<?php
session_start();
require_once "../auth/db_connection.php";

// Cek apakah user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml?error=Silakan login dulu");
    exit;
}

$nama_user = $_SESSION['nama'];

// Cek apakah ada ID kost
$kost_id = $_GET['id'] ?? null;

if (!$kost_id) {
    die("ID kost tidak ditemukan.");
}

// Ambil data kost dari DB
$stmt = $conn->prepare("SELECT * FROM kost WHERE kost_id = ?");
$stmt->bind_param("i", $kost_id);
$stmt->execute();
$result = $stmt->get_result();
$kost = $result->fetch_assoc();

// Load file XHTML
$html = file_get_contents("../../xhtml/fasilitas.xhtml");

// Replace placeholder
$html = str_replace("{{NAMA_USER}}", htmlspecialchars($nama_user), $html);
$html = str_replace("{{NAMA_KOST}}", htmlspecialchars($kost['nama_kost']), $html);
$html = str_replace("{{HARGA}}", number_format($kost['harga'], 0, ',', '.'), $html);

// Echo final HTML
echo $html;
