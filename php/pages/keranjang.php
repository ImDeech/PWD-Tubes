<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml?error=Silakan login dulu");
    exit;
}

$nama = $_SESSION['nama'];

$html = file_get_contents("../../xhtml/keranjang.xhtml");
$html = str_replace("{{NAMA_USER}}", htmlspecialchars($nama), $html);

echo $html;
?>
