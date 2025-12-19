<?php
include("../config/db.php");
if($_SESSION['role'] != 'admin') die("Access denied");

$users = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$items = mysqli_query($conn, "SELECT COUNT(*) AS total FROM items");
?>
