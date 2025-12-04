<?php
include "../php/auth/db_connection.php";

session_start();
$user_id = $_SESSION['user_id'];

$kamarList = mysqli_query($conn, "SELECT * FROM kamar");

if(isset($_POST['submit'])){
    $kamar_id = $_POST['kamar_id'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $status_sewa = $_POST['status_sewa'];

    $query = "INSERT INTO sewa(kamar_id, user_id, tanggal_sewa, status_sewa) VALUES ('$kamar_id', '$user_id', '$tanggal_sewa', $status_sewa')";
    mysqli_query($conn, $query);

    header("Location: read_sewa.php");
}

?>

<!DOCTYPE html>
<html>
    <head><title>Create Sewa</title></head>
    <body>
        <h2>Create Sewa</h2>
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
            <button type="submit">Create</button>
            <button type="reset">Reset</button>
        </form>
    </body>
</html>