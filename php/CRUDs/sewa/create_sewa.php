<?php
session_start();
include "../../auth/db_connection.php";

$message = "";

// Ambil daftar kamar
$kamarList = mysqli_query($conn, "SELECT kamar.*, kost.nama_kost 
                                  FROM kamar 
                                  JOIN kost ON kamar.kost_id = kost.kost_id
                                  ORDER BY kamar_id DESC");

// Ambil user ID (admin)
$user_id = $_SESSION['user_id'] ?? null;

// Handle submit
if (isset($_POST['submit'])) {
    $kamar_id = $_POST['kamar_id'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $status_sewa = $_POST['status_sewa'];

    $query = "INSERT INTO sewa (kamar_id, user_id, tanggal_sewa, status_sewa) 
              VALUES ('$kamar_id', '$user_id', '$tanggal_sewa', '$status_sewa')";

    mysqli_query($conn, $query);

    header("Location: /php/admin/admin_sewa.php?success=Data sewa berhasil ditambahkan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Create Sewa</title>

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
            display:block;
            font-weight:600;
            margin-bottom:6px;
            color:#333;
        }

        .form-group select,
        .form-group input {
            width:100%;
            padding:12px;
            border-radius:6px;
            border:1px solid #ccc;
            font-size:15px;
            box-sizing:border-box;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline:none;
            border-color:#2853AF;
            box-shadow:0 0 8px rgba(40,83,175,0.3);
        }

        .btn-login {
            width:100%;
            padding:12px;
            background:#2853AF;
            color:white;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
        }

        .btn-login:hover {
            background:#1f4390;
        }

        a {
            color:#2853AF;
            text-decoration:none;
        }

        .error-message {
            background:#ffebee;
            color:#d32f2f;
            padding:10px;
            border-radius:6px;
            margin-bottom:15px;
            display:block;
        }
    </style>
</head>

<body>

<div class="login-container">

    <h2>Tambah Data Sewa</h2>

    <?php if (!empty($message)): ?>
        <div class="error-message">
            <strong>Error:</strong> <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Pilih Kamar</label>
            <select name="kamar_id" required>
                <option value="">-- Pilih Kamar --</option>

                <?php while ($k = mysqli_fetch_assoc($kamarList)) : ?>
                <option value="<?= $k['kamar_id']; ?>">
                    Kamar <?= $k['nomor_kamar']; ?> - <?= $k['nama_kost']; ?>
                </option>
                <?php endwhile; ?>

            </select>
        </div>

        <div class="form-group">
            <label>Tanggal Sewa</label>
            <input type="date" name="tanggal_sewa" required>
        </div>

        <div class="form-group">
            <label>Status Sewa</label>
            <input type="text" name="status_sewa" placeholder="contoh: aktif, selesai" required>
        </div>

        <button type="submit" name="submit" class="btn-login">Simpan</button>

    </form>

    <p style="text-align:center; margin-top:15px;">
        <a href="/php/admin/admin_sewa.php">← Kembali ke daftar sewa</a>
    </p>

</div>

</body>
</html>
