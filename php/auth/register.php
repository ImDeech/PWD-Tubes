<?php
require '..db_connection.php';

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

        $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check_result = $check->get_result();
        
        // Cek apakah username atau email sudah terdaftar
        if ($check_result->num_rows > 0) {
            $message = "Username atau Email sudah terdaftar!";
        } else {

            // Insert data user
            $stmt = $conn->prepare("INSERT INTO users (username, nama, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $nama, $email, $password_hash);

            if ($stmt->execute()) {
                $message = "Registrasi berhasil! Silakan <a href='login.php'>Login</a>.";
            } else {
                $message = "Gagal registrasi: " . $conn->error;
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