<?php
include "../../auth/db_connection.php";

$result = mysqli_query($conn, "SELECT * FROM kost ORDER BY kost_id DESC");
?>

<!DOCTYPE html>
<html>
    <head><title>Read Kost</title></head>
    <body>
        <h2>Read Kost</h2>
        <a href="create_kost.php">Tambah Kost</a><br><br>

        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($result)):?>
                <tr>
                    <td><?= $row['kost_id'];?></td>
                    <td><?= $row['nama_kost'];?></td>
                    <td><?= $row['alamat'];?></td>
                    <td><?= $row['deskripsi'];?></td>
                    <td>
                        <a href="update_kost.php?id=<?= $row['kost_id'];?>">Edit</a>
                        <a href="delete_kost.php?id=<?= $row['kost_id'];?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile;?>
        </table>
    </body>
</html>