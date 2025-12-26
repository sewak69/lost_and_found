<?php
$conn = mysqli_connect("localhost", "root", "Sewak@2061", "lost_and_found");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!$conn) {
    die("Database Connection Failed");
}

?>
