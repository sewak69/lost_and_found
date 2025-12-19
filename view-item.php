<?php
include("config/db.php");

$item_id = $_GET['id'];
$item = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT items.*, users.name 
    FROM items JOIN users ON items.user_id = users.id 
    WHERE items.id='$item_id'")
);
?>

<h3><?= $item['title'] ?></h3>
<p><?= $item['description'] ?></p>

<form method="POST" action="send-message.php">
    <textarea name="message"></textarea>
    <input type="hidden" name="receiver_id" value="<?= $item['user_id'] ?>">
    <input type="hidden" name="item_id" value="<?= $item_id ?>">
    <button name="send">Send Message</button>
</form>
