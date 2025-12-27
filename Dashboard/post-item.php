<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $imageName = $_FILES['camera']['name'];
$tmpName   = $_FILES['camera']['tmp_name'];

$uploadDir = "../uploads/";
$imagePath = $uploadDir . time() . "_" . $imageName;

// move image to folder
move_uploaded_file($tmpName, $imagePath);

// store only filename in DB
$image = time() . "_" . $imageName;
    $date = $_POST['date'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $user_id = $_SESSION['user_id'];
    print($image);
    

    $query = "INSERT INTO items 
  (user_id, title, description, category, type, location, image, created_at) 
  VALUES ('$user_id','$title','$desc','$category','$type','$location', '$image', NOW())";

    

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
    <link rel="stylesheet" href="../assets/css/post.css">
</head>
<body>
    <div class="container">
    <!-- Left Panel -->
    <div class="left-panel">
        <h1>Lost & Found</h1>
        <p>
            Help reunite lost items with their owners.  
            Share accurate details to make identification easy.
        </p>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="form-container">
            <h2>Post Item</h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <label>Category</label>
                 <select name="category" required>
                    
                 <option value="">Select Category</option>
                 <option value="wallet">Wallet</option>
                 <option value="mobile">Mobile Phone</option>
                 <option value="bag">Bag</option>
                 <option value="keys">Keys</option>
                 <option value="documents">Documents</option>
                 <option value="others">Others</option>
                </select>

                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="input-group">
                    <label for="camera">Camera</label>
                    <input type="file" id="camera" name="camera" accept="image/*">

                </div>
                <div class="input-group">
                    <label for="date">date</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="input-group">
                    <label for="type">Type</label>
                    <select id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="lost">Lost</option>
                        <option value="found">Found</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required>
                </div>

                <button type="submit" class="btn" name="submit">Submit</button>
            </form>

            <div class="back-link">
                <a href="./index.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
  

</body>
</html>