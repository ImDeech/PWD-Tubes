<?php
include "../php/auth/db_connection.php";

$kostList = mysqli_query($conn, "SELECT * FROM kost");

if(isset($_POST['submit'])){
    $kost_id = $_POST['kost_id'];
    $nomor_kamar = $_POST['nomor_kamar'];
    $harga = $_POST['harga'];
    $status_kamar= $_POST['status_kamar'];

    $query = "INSERT INTO kamar(kost_id, nomor_kamar, harga, status_kamar) VALUES ('$kost_id', '$nomor_kamar', '$harga', $status_kamar')";
    mysqli_query($conn, $query);

    header("Location: read_kamar.php");
}

?>

<!DOCTYPE html>
<html>
    <head><title>Create Kamar</title></head>
    <body>
        <h2>Create Kamar</h2>
        <?php if($message): ?> <p style="color:red;"><?= $message ?></p> <?php endif; ?>

        <form method="post" action="">
            <select name="id_kost" required>
                <option value="">-- Pilih Kost --</option>

                <?php while($k = mysqli_fetch_assoc($kostList)) : ?>
                    <option value="<?= $k['kost_id'];?>">
                        <?= $k['nama_kost'];?> (ID: <?= $k['kost_id'];?>)
                    </option>
                <?php endwhile;?>
            </select><br><br>
            
            <input type="number" name="nomor_kamar" placeholder="Nomor Kamar" required><br><br>
            <input type="number" name="harga" placeholder="Harga" required><br><br>
            <input type="text" name="status_kamar" placeholder="Status Kamar" required><br><br>
            <button type="submit">Create</button>
            <button type="reset">Reset</button>
        </form>
    </body>
</html>