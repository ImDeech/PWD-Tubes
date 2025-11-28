<?php
include "../php/auth/db_connection.php";

$id = $_GET['kamar_id'];

mysqli_query($conn, "DELETE FROM kamar WHERE kamar_id = $id");
header("Location: read_kamar.php");
?>