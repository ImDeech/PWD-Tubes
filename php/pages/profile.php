<?php
session_start();
require_once "../auth/db_connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml?error=Silakan login dulu");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ambil data user dari db_sistemkost */
$stmt = $conn->prepare("SELECT username, nama, email, no_telp FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();

if (!$data) {
    die("Data user tidak ditemukan.");
}


$template = file_get_contents("../../xhtml/profile.xhtml");

$template = str_replace("{{NAMA_USER}}", htmlspecialchars($data['nama']), $template);
$template = str_replace("{{USERNAME}}", htmlspecialchars($data['username']), $template);
$template = str_replace("{{EMAIL}}", htmlspecialchars($data['email']), $template);
$template = str_replace("{{NO_TELP}}", htmlspecialchars($data['no_telp']), $template);

echo $template;
?>
