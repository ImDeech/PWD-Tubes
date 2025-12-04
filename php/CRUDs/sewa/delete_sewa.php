<?php
include "../php/auth/db_connection.php";

$id = $_GET['sewa_id'];

mysqli_query($conn, "DELETE FROM sewa WHERE sewa_id = $id");
header("Location: read_sewa.php");
?>