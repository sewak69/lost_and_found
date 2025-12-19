<?php
include("../config/db.php");
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampusFound | Lost & Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<header class="navbar">
    <div class="logo">CampusFound</div>
    <nav>
        <a href="#">Home</a>
        <a href="#">Lost Items</a>
        <a href="#">Found Items</a>
        <a href="../auth/login.php">Login</a>
        <a href="../auth/register.php" class="btn">Register</a>
    </nav>
</header>

<!-- HERO SECTION -->
<section class="hero">
    <h1>Lost Something? Found Something? <br> We're Here to Help</h1>
    <div class="search-box">
        <input type="text" placeholder="Search for items...">
    </div>

    <div class="hero-buttons">
        <button class="primary-btn">+ Report Lost Item</button>
        <button class="secondary-btn">+ Report Found Item</button>
    </div>
</section>

<!-- RECENT ITEMS -->
<section class="items">
    <h2>Recently Posted Items</h2>



      

    </div>
</section>

<script src="./js/script.js"></script>
</body>
</html>

<!-- <h2>User Dashboard</h2>
<a href="post-item.php">Post Lost / Found</a>
<a href="my-items.php">My Items</a>
<a href="messages.php">Messages</a>
<a href="../auth/logout.php">Logout</a> -->
