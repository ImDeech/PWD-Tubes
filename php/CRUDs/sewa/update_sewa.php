<?php
include "../../auth/db_connection.php";

$message = "";

// Ambil ID sewa
$id = $_GET['sewa_id'];

// Ambil data sewa
$data = mysqli_query($conn, "SELECT * FROM sewa WHERE sewa_id = $id");
$row  = mysqli_fetch_assoc($data);

// Ambil list kamar
$kamarList = mysqli_query($conn, "SELECT * FROM kamar");

// Jika submit
if (isset($_POST['submit'])) {
    $kamar_id      = $_POST['kamar_id'];
    $tanggal_sewa  = $_POST['tanggal_sewa'];
    $status_sewa   = $_POST['status_sewa'];

    mysqli_query($conn, "
        UPDATE sewa
        SET kamar_id = '$kamar_id',
            tanggal_sewa = '$tanggal_sewa',
            status_sewa = '$status_sewa'
        WHERE sewa_id = '$id'
    ");

    header("Location: /php/admin/admin_sewa.php?success=Sewa berhasil diperbarui");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Sewa</title>
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
            border:1px solid #ccc; font-size:15px;
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
    <h2>Edit Sewa</h2>

    <form method="POST">

        <div class="form-group">
            <label>Pilih Kamar</label>
            <select name="kamar_id" required>
                <?php while ($k = mysqli_fetch_assoc($kamarList)) : ?>
                    <option value="<?= $k['kamar_id'] ?>"
                        <?= ($k['kamar_id'] == $row['kamar_id']) ? 'selected' : '' ?>>
                        Kamar <?= htmlspecialchars($k['nomor_kamar']) ?> (ID: <?= $k['kamar_id'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal Sewa</label>
            <input type="date" name="tanggal_sewa" required value="<?= $row['tanggal_sewa'] ?>" />
        </div>

        <div class="form-group">
            <label>Status Sewa</label>
            <input type="text" name="status_sewa" required value="<?= htmlspecialchars($row['status_sewa']) ?>" />
        </div>

        <button type="submit" name="submit" class="btn-login">Update</button>
    </form>

    <p style="text-align:center;margin-top:15px;">
        <a href="/php/admin/admin_sewa.php">← Kembali</a>
    </p>
</div>

</body>
</html>
