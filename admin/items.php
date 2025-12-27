<?php
session_start();
include("../config/db.php");

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $item_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM items WHERE id=$item_id");
    header("Location: items.php");
    exit();
}

// Handle Add / Update Form Submission
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $image = $_FILES['image']['name'];

    if ($image != "") {
        $target = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    if (isset($_POST['item_id']) && $_POST['item_id'] != "") {
        $item_id = intval($_POST['item_id']);
        $query = "UPDATE items SET title='$title', description='$desc', category='$category', type='$type', location='$location'";
        if ($image != "") $query .= ", image='$image'";
        $query .= ", created_at='$date' WHERE id=$item_id";
        mysqli_query($conn, $query);
    } else {
        $query = "INSERT INTO items (title, description, category, type, location, image, created_at) 
                  VALUES ('$title', '$desc', '$category', '$type', '$location', '$image', '$date')";
        mysqli_query($conn, $query);
    }

    header("Location: items.php");
    exit();
}

// Fetch stats
$total_items = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM items"))['total'];
$lost_items = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM items WHERE type='lost'"))['total'];
$found_items = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM items WHERE type='found'"))['total'];

// Fetch all items
$items = mysqli_query($conn, "SELECT * FROM items ORDER BY created_at DESC");

// If editing
$edit_item = null;
if (isset($_GET['edit'])) {
    $item_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM items WHERE id=$item_id");
    $edit_item = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Items</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts & CSS Reset -->
     <link rel="stylesheet" href="../assets/css/admin-items.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  
</head>
<body>
<div class="dashboard">
<aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin-dashboard.php">Dashboard</a></li>
            <li><a href="users.php">Manage Users</a></li>
            <li><a href="items.php">Manage Items</a></li>
            <li><a href="../auth/logout.php" class="logout">Logout</a></li>
        </ul>
    </aside>
    <main class="content">
        <h1>Manage Items</h1>

        <!-- Stats Cards -->
        <div class="cards">
            <div class="card"><h3>Total Items</h3><p><?php echo $total_items; ?></p></div>
            <div class="card"><h3>Lost Items</h3><p><?php echo $lost_items; ?></p></div>
            <div class="card"><h3>Found Items</h3><p><?php echo $found_items; ?></p></div>
        </div>

        <!-- Add/Edit Form -->
        <div class="form-container">
            <h2><?php echo $edit_item ? "Edit Item" : "Add New Item"; ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="item_id" value="<?php echo $edit_item['id'] ?? ''; ?>">

                <label>Title</label>
                <input type="text" name="title" value="<?php echo $edit_item['title'] ?? ''; ?>" required>

                <label>Description</label>
                <textarea name="description" required><?php echo $edit_item['description'] ?? ''; ?></textarea>

                <label>Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <?php 
                    $categories = ['wallet','mobile','bag','keys','documents','others'];
                    foreach($categories as $cat){
                        $selected = (isset($edit_item) && $edit_item['category']==$cat) ? 'selected' : '';
                        echo "<option value='$cat' $selected>".ucfirst($cat)."</option>";
                    }
                    ?>
                </select>

                <label>Type</label>
                <select name="type" required>
                    <option value="">Select Type</option>
                    <option value="lost" <?php if(isset($edit_item) && $edit_item['type']=='lost') echo 'selected'; ?>>Lost</option>
                    <option value="found" <?php if(isset($edit_item) && $edit_item['type']=='found') echo 'selected'; ?>>Found</option>
                </select>

                <label>Location</label>
                <input type="text" name="location" value="<?php echo $edit_item['location'] ?? ''; ?>" required>

                <label>Date</label>
                <input type="date" name="date" value="<?php echo $edit_item['created_at'] ?? date('Y-m-d'); ?>" required>

                <label>Image</label>
                <input type="file" name="image">
                <?php if(isset($edit_item['image']) && $edit_item['image'] != ""): ?>
                    <img src="../uploads/<?php echo $edit_item['image']; ?>" width="100" style="margin-top:10px;">
                <?php endif; ?>

                <button type="submit" name="submit"><?php echo $edit_item ? "Update Item" : "Add Item"; ?></button>
            </form>
        </div>

        <!-- Items Grid -->
        <h2>All Items</h2>
        <div class="items-grid">
            <?php while($item = mysqli_fetch_assoc($items)): ?>
            <div class="item-card">
                <?php if($item['image'] != ""): ?>
                    <img src="../uploads/<?php echo $item['image']; ?>" alt="Item Image">
                <?php endif; ?>
                <h4><?php echo $item['title']; ?></h4>
                <p><strong>Category:</strong> <?php echo ucfirst($item['category']); ?></p>
                <p><strong>Type:</strong> <?php echo ucfirst($item['type']); ?></p>
                <p><strong>Location:</strong> <?php echo $item['location']; ?></p>
                <p><strong>Date:</strong> <?php echo $item['created_at']; ?></p>
                <div class="item-actions">
                    <a href="items.php?edit=<?php echo $item['id']; ?>" class="edit">Edit</a>
                    <a href="items.php?delete=<?php echo $item['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</div>
</body>
</html>
