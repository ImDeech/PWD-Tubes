<?php
include "../php/auth/db_connection.php";

if(isset($_POST['submit'])){
    $nama_kost = $_POST['nama_kost'];
    $alamat = $_POST['alamat'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO kost(nama_kost, alamat, deskripsi) VALUES ('$nama_kost', '$alamat', '$deskripsi')";
    mysqli_query($conn, $query);

    header("Location: read_kost.php");
}

?>

<!DOCTYPE html>
<html>
    <head><title>Create Kost</title></head>
    <body>
        <h2>Create Kost</h2>
        <?php if($message): ?> <p style="color:red;"><?= $message ?></p> <?php endif; ?>

        <form method="post" action="">
            <input type="text" name="nama_kost" placeholder="Nama Kost" required><br><br>
            <textarea name="alamat" placeholder="Alamat Kost" required></textarea><br><br>
            <textarea name="deskripsi" placeholder="Tuliskan deskripsi"></textarea><br><br>
            <button type="submit">Create</button>
            <button type="reset">Reset</button>
        </form>
    </body>
</html>