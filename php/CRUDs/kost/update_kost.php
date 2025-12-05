<?php
include "../../auth/db_connection.php";

$message = "";

// Ambil ID kost
$id = $_GET['kost_id'];

// Ambil data kost yang ingin diedit
$data = mysqli_query($conn, "SELECT * FROM kost WHERE kost_id = $id");
$row = mysqli_fetch_assoc($data);

// Jika form disubmit
if (isset($_POST['submit'])) {
    $nama_kost   = $_POST['nama_kost'];
    $alamat      = $_POST['alamat'];
    $deskripsi   = $_POST['deskripsi'];

    mysqli_query($conn, "
        UPDATE kost 
        SET nama_kost = '$nama_kost', alamat = '$alamat', deskripsi = '$deskripsi'
        WHERE kost_id = '$id'
    ");

    header("Location: /php/admin/admin_kost.php?success=Kost berhasil diperbarui");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Kost</title>
    <meta charset="UTF-8" />
    <style>
        body { background:#f0f2f5; font-family:Arial; }
        .login-container {
            max-width:450px; margin:80px auto; padding:30px;
            background:#fff; border-radius:12px;
            box-shadow:0 0 20px rgba(0,0,0,0.1);
        }
        h2 { text-align:center; margin-bottom:20px; color:#333; }
        .form-group { margin-bottom:18px; }
        .form-group label { font-weight:600; display:block; margin-bottom:6px; }
        .form-group input, .form-group textarea {
            width:100%; padding:12px; border-radius:6px;
            border:1px solid #ccc; font-size:15px; box-sizing:border-box;
        }
        .btn-login {
            width:100%; padding:12px; background:#2853AF;
            color:white; border:none; border-radius:6px;
            font-size:16px; cursor:pointer;
        }
        .btn-login:hover { background:#1f4390; }
    </style>
</head>

<body>

<div class="login-container">
    <h2>Edit Kost</h2>

    <form method="POST">

        <div class="form-group">
            <label>Nama Kost</label>
            <input type="text" name="nama_kost" required value="<?= htmlspecialchars($row['nama_kost']) ?>" />
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" required><?= htmlspecialchars($row['alamat']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
        </div>

        <button type="submit" name="submit" class="btn-login">Update</button>
    </form>

    <p style="text-align:center;margin-top:15px;">
        <a href="/php/admin/admin_kost.php">← Kembali</a>
    </p>
</div>

</body>
</html>
