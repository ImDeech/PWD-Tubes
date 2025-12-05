<?php
session_start();
require_once "../auth/db_connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../xhtml/login.xhtml?error=Silakan login dulu");
    exit;
}

$user_id = $_SESSION['user_id'];

$nama = $_POST['nama'];
$username = $_POST['username'];
$email = $_POST['email'];
$no_telp = $_POST['no_telp'];

$stmt = $conn->prepare("
    UPDATE users
    SET nama = ?, username = ?, email = ?, no_telp = ?
    WHERE user_id = ?
");

$stmt->bind_param("ssssi", $nama, $username, $email, $no_telp, $user_id);

if ($stmt->execute()) {
    $_SESSION['nama'] = $nama;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['no_telp'] = $no_telp;

    header("Location: profile.php?success=Berhasil update akun");
} else {
    header("Location: update_user.php?error=Gagal update");
}
