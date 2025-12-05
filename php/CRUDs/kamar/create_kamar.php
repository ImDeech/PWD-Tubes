<?php
include "../../auth/db_connection.php";

$message = "";

// Ambil list kost
$kostList = mysqli_query($conn, "SELECT * FROM kost");

// Proses submit
if (isset($_POST['submit'])) {
    $kost_id                = $_POST['kost_id'];
    $nomor_kamar            = $_POST['nomor_kamar'];
    $harga                  = $_POST['harga'];
    $status_kamar           = $_POST['status_kamar'];
    $spesifikasi_kamar      = $_POST['spesifikasi_kamar'];
    $fasilitas_kamar        = $_POST['fasilitas_kamar'];
    $fasilitas_kamar_mandi  = $_POST['fasilitas_kamar_mandi'];
    $peraturan              = $_POST['peraturan'];

    $query = "INSERT INTO kamar(kost_id, nomor_kamar, harga, status_kamar, spesifikasi_kamar, fasilitas_kamar, fasilitas_kamar_mandi, peraturan) 
              VALUES ('$kost_id', '$nomor_kamar', '$harga', '$status_kamar', '$spesifikasi_kamar', '$fasilitas_kamar', '$fasilitas_kamar_mandi', '$peraturan')";
    mysqli_query($conn, $query);

    header("Location: /php/admin/admin_kamar.php?success=Kamar berhasil ditambahkan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Create Kamar</title>
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

    <h2>Tambah Kamar Baru</h2>

    <?php if (!empty($message)): ?>
        <div class="error-message">
            <strong>Error:</strong> <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Pilih Kost</label>
            <select name="kost_id" required>
                <option value="">-- Pilih Kost --</option>

                <?php while($k = mysqli_fetch_assoc($kostList)) : ?>
                    <option value="<?= $k['kost_id']; ?>">
                        <?= htmlspecialchars($k['nama_kost']); ?> (ID: <?= $k['kost_id']; ?>)
                    </option>
                <?php endwhile; ?>

            </select>
        </div>

        <div class="form-group">
            <label>Nomor Kamar</label>
            <input type="text" name="nomor_kamar" placeholder="Masukkan nomor kamar" required />
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" placeholder="Masukkan harga" required />
        </div>

        <div class="form-group">
            <label>Status Kamar</label>
            <input type="text" name="status_kamar" placeholder="Contoh: tersedia / penuh" required />
        </div>

        <div class="form-group">
            <label>Spesifikasi Kamar</label>
            <input type="text" name="spesifikasi_kamar" placeholder="Masukkan spesifikasi kamar" required />
        </div>

        <div class="form-group">
            <label>Fasilitas Kamar</label>
            <input type="text" name="fasilitas_kamar" placeholder="Masukkan fasilitas kamar" required />
        </div>

        <div class="form-group">
            <label>Fasilitas Kamar Mandi</label>
            <input type="text" name="fasilitas_kamar_mandi" placeholder="Masukkan fasilitas kamar mandi" required />
        </div>

        <div class="form-group">
            <label>Peraturan</label>
            <input type="text" name="peraturan" placeholder="Masukkan peraturan" required />
        </div>

        <button type="submit" name="submit" class="btn-login">Simpan</button>

    </form>

    <p style="text-align:center; margin-top:15px;">
        <a href="/php/admin/admin_kamar.php">← Kembali ke daftar kamar</a>
    </p>

</div>

</body>
</html>
