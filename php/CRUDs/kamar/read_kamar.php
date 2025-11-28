<?php
include "../php/auth/db_connection.php";

$result = mysqli_query($conn, "SELECT kamar.*, kost.nama_kost AS nama_kost FROM kamar JOIN kost ON kamar.kost_id = kost.kost_id
                        ORDER BY kamar_id DESC");
?>

<!DOCTYPE html>
<html>
    <head><title>Read Kamar</title></head>
    <body>
        <h2>Read Kamar</h2>
        <a href="create_kamar.php">Tambah Kamar</a><br><br>

        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Nama Kost</th>
                <th>Nomor Kamar</th>
                <th>Harga</th>
                <th>Status Kamar</th>
                <th>Aksi</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($result)):?>
                <tr>
                    <td><?= $row['kamar_id'];?></td>
                    <td><?= $row['nama_kost'];?></td>
                    <td><?= $row['nomor_kamar'];?></td>
                    <td><?= $row['harga'];?></td>
                    <td><?= $row['status_kamar'];?></td>
                    <td>
                        <a href="update_kamar.php?id=<?= $row['kost_id'];?>">Edit</a>
                        <a href="delete_kamar.php?id=<?= $row['kost_id'];?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile;?>
        </table>
    </body>
</html>