<?php 
include "../php/auth/db_connection.php";

$id = $_GET['kost_id'];

$data = mysqli_query($conn, "SELECT * FROM kost WHERE kost_id = $id");
$row = mysqli_fetch_assoc($data);

if(isset($_POST['submit'])){
    $nama_kost = $_POST['nama_kost'];
    $alamat = $_POST['alamat'];
    $deskripsi = $_POST['deskripsi'];

    mysqli_query($conn, "UPDATE kost SET nama_kost = $nama_kost, alamat = $alamat, deskripsi = $deskripsi WHERE kost_id = $id");
    header("Location: read_kost.php");
}

?>

<!DOCTYPE html>
<html>
    <head><title>Edit Kost</title></head>
    <body>
        <h2>Edit Kost</h2>
        <?php if($message): ?> <p style="color:red;"><?= $message ?></p> <?php endif; ?>

        <form method="post" action="">
            <input type="text" name="nama_kost" placeholder="Nama Kost" required><br><br>
            <textarea name="alamat" placeholder="Alamat Kost" required></textarea><br><br>
            <textarea name="deskripsi" placeholder="Tuliskan deskripsi"></textarea><br><br>
            <button type="submit">Update</button>
            <button type="reset">Reset</button>
        </form>
    </body>
</html>