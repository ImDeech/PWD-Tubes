<?php
include "../php/auth/db_connection.php";

$result = mysqli_query($conn, "SELECT s.*, k.nomor_kamar, u.nama FROM sewa s
    JOIN kamar k ON s.kamar_id = k.kamar_id JOIN users u ON s.user_id = u.user_id ORDER BY s.sewa_id DESC");
?>

<!DOCTYPE html>
<html>
    <head><title>Read Sewa</title></head>
    <body>
        <h2>Read Sewa</h2>
        <a href="create_sewa.php">Tambah Sewa</a><br><br>

        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Nomor Kamar</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Sewa</th>
                <th>Status Sewa</th>
                <th>Aksi</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($result)):?>
                <tr>
                    <td><?= $row['sewa_id'];?></td>
                    <td><?= $row['nomor_kamar'];?></td>
                    <td><?= $row['nama'];?></td>
                    <td><?= $row['tanggal_sewa'];?></td>
                    <td><?= $row['status_sewa'];?></td>
                    <td>
                        <a href="update_sewa.php?id=<?= $row['sewa_id'];?>">Edit</a>
                        <a href="delete_sewa.php?id=<?= $row['sewa_id'];?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile;?>
        </table>
    </body>
</html>