<?php
include("../config/db.php");

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO items 
    (user_id, title, description, type, location) 
    VALUES ('$user_id','$title','$desc','$type','$location')";

    mysqli_query($conn, $query);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Item</title>
</head>
<body>
    <h2>Post Lost/Found Item</h2>
    <form method="POST">
        <div class="input-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>

        <div class="input-group">
            <label>Description</label>
            <textarea name="description" required></textarea>
        </div>

        <div class="input-group">
            <label>Type</label>
            <select name="type" required>
                <option value="">Select Type</option>
                <option value="lost">Lost</option>
                <option value="found">Found</option>
            </select>
        </div>

        <div class="input-group">
            <label>Location</label>
            <input type="text" name="location" required>
        </div>

        <button class="btn" name="submit">Submit</button>
    </form>

    <!-- Back to Dashboard -->
    <a href="./index.php">Back to Dashboard</a>

</body>
</html>