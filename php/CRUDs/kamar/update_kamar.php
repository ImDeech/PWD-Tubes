<?php 
include "../php/auth/db_connection.php";

$id = $_GET['kamar_id'];

$data = mysqli_query($conn, "SELECT * FROM kamar WHERE kamar_id = $id");
$row = mysqli_fetch_assoc($data);

if(isset($_POST['submit'])){
    $kost_id = $_POST['kost_id'];
    $nomor_kamar = $_POST['nomor_kamar'];
    $harga = $_POST['harga'];
    $status_kamar= $_POST['status_kamar'];

    mysqli_query($conn, "UPDATE kamar SET kost_id = $kost_id, nomor_kamar = $nomor_kamar, harga = $harga, status_kamar = $status_kamar WHERE kamar_id = $id");
    header("Location: read_kamar.php");
}

?>

<!DOCTYPE html>
<html>
    <head><title>Edit Kamar</title></head>
    <body>
        <h2>Edit Kamar</h2>
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
            <button type="submit">Update</button>
            <button type="reset">Reset</button>
        </form>
    </body>
</html>