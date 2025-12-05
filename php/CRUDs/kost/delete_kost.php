<?php
include "../../auth/db_connection.php";

$id = $_GET['kost_id'];

mysqli_query($conn, "DELETE FROM kost WHERE kost_id = '$id'");
header("Location: /php/admin/admin_kost.php");
?>