<?php
include("config/db.php");

if(isset($_POST['send'])) {
    $sender = $_SESSION['user_id'];
    $receiver = $_POST['receiver_id'];
    $item_id = $_POST['item_id'];
    $msg = $_POST['message'];

    mysqli_query($conn, "INSERT INTO messages 
    (sender_id, receiver_id, item_id, message) 
    VALUES ('$sender','$receiver','$item_id','$msg')");

    header("Location: dashboard/messages.php");
}
?>
