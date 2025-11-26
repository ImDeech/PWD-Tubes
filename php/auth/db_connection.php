<?php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "db_sistemkost";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset untuk keamanan & kompatibilitas
$conn->set_charset("utf8mb4");
?>
