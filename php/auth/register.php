<?php
require 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi sederhana
    if (!empty($username) && !empty($nama) && !empty($email) && !empty($password)) {
        
        // 1. Enkripsi Password (Sangat Penting!)
        // PASSWORD_DEFAULT menggunakan algoritma bcrypt yang kuat
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // 2. Query Insert dengan Prepared Statement (Mencegah SQL Injection)
            $sql = "INSERT INTO users (username, nama, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $nama, $email, $password_hash]);
            
            $message = "Registrasi berhasil! Silakan <a href='login.php'>Login</a>.";
        } catch (PDOException $e) {
            // Cek jika error karena duplikat (username/email sudah ada)
            if ($e->getCode() == 23000) {
                $message = "Username atau Email sudah terdaftar.";
            } else {
                $message = "Terjadi kesalahan: " . $e->getMessage();
            }
        }
    } else {
        $message = "Semua kolom harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register Kost</title></head>
<body>
    <h2>Daftar Akun</h2>
    <?php if($message): ?> <p style="color:red;"><?= $message ?></p> <?php endif; ?>
    
    <form method="POST" action="">
        <input type="text" name="nama" placeholder="Nama Lengkap" required><br><br>
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
</body>
</html>