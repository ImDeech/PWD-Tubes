<?php
include "../../auth/db_connection.php";

$message = "";

// Ambil ID kamar
$id = $_GET['kamar_id'];

// Ambil data kamar
$data  = mysqli_query($conn, "SELECT * FROM kamar WHERE kamar_id = $id");
$row   = mysqli_fetch_assoc($data);

// Ambil list kost untuk dropdown
$kostList = mysqli_query($conn, "SELECT * FROM kost");

// Jika form disubmit
if (isset($_POST['submit'])) {
    $kost_id                = $_POST['kost_id'];
    $nomor_kamar            = $_POST['nomor_kamar'];
    $harga                  = $_POST['harga'];
    $status_kamar           = $_POST['status_kamar'];
    $spesifikasi_kamar      = $_POST['spesifikasi_kamar'];
    $fasilitas_kamar        = $_POST['fasilitas_kamar'];
    $fasilitas_kamar_mandi  = $_POST['fasilitas_kamar_mandi'];
    $peraturan              = $_POST['peraturan'];

    mysqli_query($conn, "
        UPDATE kamar 
        SET kost_id = '$kost_id',
            nomor_kamar = '$nomor_kamar',
            harga = '$harga',
            status_kamar = '$status_kamar',
            spesifikasi_kamar = '$spesifikasi_kamar',
            fasilitas_kamar = '$fasilitas_kamar',
            fasilitas_kamar_mandi = '$fasilitas_kamar_mandi',
            peraturan = '$peraturan'
        WHERE kamar_id = '$id'
    ");

    header("Location: /php/admin/admin_kamar.php?success=Kamar berhasil diperbarui");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Kamar</title>
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
        .form-group input, .form-group select {
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
    <h2>Edit Kamar</h2>

    <form method="POST">

        <div class="form-group">
            <label>Pilih Kost</label>
            <select name="kost_id" required>
                <?php while ($k = mysqli_fetch_assoc($kostList)) : ?>
                    <option value="<?= $k['kost_id'] ?>"
                        <?= ($k['kost_id'] == $row['kost_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kost']) ?> (ID: <?= $k['kost_id'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nomor Kamar</label>
            <input type="text" name="nomor_kamar" required value="<?= htmlspecialchars($row['nomor_kamar']) ?>" />
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" required value="<?= $row['harga'] ?>" />
        </div>

        <div class="form-group">
            <label>Status Kamar</label>
            <input type="text" name="status_kamar" required value="<?= htmlspecialchars($row['status_kamar']) ?>" />
        </div>

        <div class="form-group">
            <label>Spesifikasi Kamar</label>
            <input type="text" name="spesifikasi_kamar" required value="<?= htmlspecialchars($row['spesifikasi_kamar']) ?>" />
        </div>

        <div class="form-group">
            <label>Fasilitas Kamar</label>
            <input type="text" name="fasilitas_kamar" required value="<?= htmlspecialchars($row['fasilitas_kamar']) ?>" />
        </div>

        <div class="form-group">
            <label>Fasilitas Kamar Mandi</label>
            <input type="text" name="fasilitas_kamar_mandi" required value="<?= htmlspecialchars($row['fasilitas_kamar_mandi']) ?>" />
        </div>

        <div class="form-group">
            <label>Peraturan</label>
            <input type="text" name="peraturan" required value="<?= htmlspecialchars($row['peraturan']) ?>" />
        </div>
        
        <button type="submit" name="submit" class="btn-login">Update</button>
    </form>

    <p style="text-align:center;margin-top:15px;">
        <a href="/php/admin/admin_kamar.php">← Kembali</a>
    </p>
</div>

</body>
</html>
