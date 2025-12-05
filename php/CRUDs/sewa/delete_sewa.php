<?php
include "../../auth/db_connection.php";

$id = $_GET['sewa_id'];

mysqli_query($conn, "DELETE FROM sewa WHERE sewa_id = $id");
header("Location: /php/admin/admin_sewa.php");
?>