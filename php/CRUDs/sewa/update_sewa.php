<?php 
include "../php/auth/db_connection.php";

$id = $_GET['sewa_id'];

$data = mysqli_query($conn, "SELECT * FROM sewa WHERE sewaw_id = $id");
$row = mysqli_fetch_assoc($data);

if(isset($_POST['submit'])){
    $kamar_id = $_POST['kamar_id'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $status_sewa = $_POST['status_sewa'];

    mysqli_query($conn, "UPDATE sewa SET kost_id = $kost_id, kamar_id = $kamar_id, tanggal_sewa = $tanggal_sewa, status_sewa = $status_sewa WHERE sewa_id = $id");
    header("Location: read_sewa.php");
}

?>

<!DOCTYPE html>
<html>
    <head><title>Edit Sewa</title></head>
    <body>
        <h2>Edit Sewa</h2>
        <?php if($message): ?> <p style="color:red;"><?= $message ?></p> <?php endif; ?>

        <form method="post" action="">
            <select name="kamar_id">
                <?php while($k = mysqli_fetch_assoc($kamarList)) : ?>
                    <option value="<?= $k['kamar_id']; ?>">
                        <?= $k['nomor_kamar'] ?>
                    </option>
                <?php endwhile;?>
            </select><br><br>

            <input type="date" name="tanggal_sewa" placeholder="Tanggal Sewa" required><br><br>
            <input type="text" name="status_sewa" placeholder="Status Sewa" required><br><br>
            <button type="submit">Update</button>
            <button type="reset">Reset</button>
        </form>
    </body>
</html>