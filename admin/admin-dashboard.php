<?php
session_start();
include("../config/db.php");
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}



$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"));
$items = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM items"));
$lost = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM items WHERE type='lost'"));
$found = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM items WHERE type='found'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
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
        <h1>Dashboard Overview</h1>

        <div class="cards">
            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo $users['total']; ?></p>
            </div>

            <div class="card">
                <h3>Total Items</h3>
                <p><?php echo $items['total']; ?></p>
            </div>

            <div class="card">
                <h3>Lost Items</h3>
                <p><?php echo $lost['total']; ?></p>
            </div>

            <div class="card">
                <h3>Found Items</h3>
                <p><?php echo $found['total']; ?></p>
            </div>
        </div>
    </main>
</div>

</body>
</html>
?>
