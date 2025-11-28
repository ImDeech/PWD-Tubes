<?php
session_start();
require 'db_connection.php';

// Jika sudah login, redirect ke index
if (isset($_SESSION['user_id'])) {
    header("Location: ../../index.xhtml");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // 1. Cari user berdasarkan username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); 
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();


        // 2. Verifikasi Password
        // password_verify mencocokkan password inputan dengan hash di database
        if ($user && password_verify($password, $user['password'])) {
            
            // 3. Set Session (Login Berhasil)
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            
            header("Location: ../../index.xhtml");
            exit;
        } else {
            $message = "Username atau Password salah.";
        }
    } else {
        $message = "Isi username dan password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login Kost</title></head>
<body>
    <h2>Login</h2>
    <?php if($message): ?> <p style="color:red;"><?= $message ?></p> <?php endif; ?>
    
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Masuk</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
</body>
</html>
