<?php
$conn = mysqli_connect("localhost", "root", "Sewak@2061", "lost_and_found");

if (!$conn) {
    die("Database Connection Failed");
}
session_start();
?>
