<?php
// Perbaiki path database
include "../../auth/db_connection.php";

// Inisialisasi pesan
$message = "";

if (isset($_POST['submit'])) {
    $nama_kost = $_POST['nama_kost'];
    $alamat = $_POST['alamat'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO kost(nama_kost, alamat, deskripsi) VALUES ('$nama_kost', '$alamat', '$deskripsi')";
    mysqli_query($conn, $query);

    header("Location: /php/admin/admin_kost.php?success=Kost berhasil ditambahkan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Create Kost</title>
    <meta charset="UTF-8" />
    <style>
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
        }

        .login-container {
            max-width: 450px;
            margin: 80px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
        }

        h2 {
            text-align:center; 
            margin-bottom:20px;
            color:#333;
        }

        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 15px;
        }

        .form-group textarea {
            height: 90px;
            resize: none;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2853AF;
            box-shadow: 0 0 8px rgba(40, 83, 175, 0.3);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2853AF;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 5px;
        }

        .btn-login:hover {
            background: #1f4390;
        }

        .error-message,
        .success-message {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error-message {
            background: #ffebee;
            color: #d32f2f;
            display: <?= $message ? "block" : "none" ?>;
        }
    </style>
</head>

<body>
    <div class="login-container">

        <h2>Tambah Kost Baru</h2>

        <?php if ($message): ?>
            <div class="error-message">
                <strong>Error:</strong> <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama Kost:</label>
                <input type="text" name="nama_kost" placeholder="Masukkan nama kost" required />
            </div>

            <div class="form-group">
                <label>Alamat:</label>
                <textarea name="alamat" placeholder="Alamat lengkap" required></textarea>
            </div>

            <div class="form-group">
                <label>Deskripsi (opsional):</label>
                <textarea name="deskripsi" placeholder="Deskripsi kost"></textarea>
            </div>

            <button type="submit" name="submit" class="btn-login">Simpan</button>
        </form>

        <p style="text-align:center; margin-top:18px;">
            <a href="/php/admin/admin_kost.php">← Kembali ke daftar kost</a>
        </p>

    </div>
</body>
</html>
