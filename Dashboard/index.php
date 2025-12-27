<?php
session_start();
include("../config/db.php");

// check login
$isLoggedIn = isset($_SESSION['user_id']);

// Search logic
$search = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM items 
              WHERE title LIKE '%$search%' 
              OR description LIKE '%$search%' 
              OR type LIKE '%$search%'
              ORDER BY created_at DESC";
} else {
    $query = "SELECT * FROM items 
              ORDER BY created_at DESC 
              LIMIT 5";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lost & Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<!-- NAVBAR -->
<header class="navbar">
    <div class="logo">
        <a href="../dashboard/index.php">Lost & Found</a>
    </div>

    <!-- NAV SEARCH -->
    <form method="GET" action="" class="nav-search">
        <input
            type="text"
            name="search"
            placeholder="Search items..."
            value="<?php echo htmlspecialchars($search); ?>"
        >
        <button type="submit">
            <i class="fa fa-search"></i>
        </button>
    </form>

    <nav>
        <a href="../dashboard/index.php">Home</a>
        <a href="../dashboard/post-item.php">Lost Items</a>
        <a href="../dashboard/post-item.php">Found Items</a>

        <?php if($isLoggedIn): ?>
            <a href="../dashboard/messages.php">Messages</a>
            <a href="../auth/logout.php" class="btn logout-btn">Logout</a>
        <?php else: ?>
            <a href="../auth/login.php">Login</a>
            <a href="../auth/register.php" class="btn">Register</a>
        <?php endif; ?>
    </nav>
</header>


<div class="main-content">

<!-- HERO -->
<section class="hero">
    <h1>Lost Something? Found Something?<br>We're Here to Help</h1>


    <div class="hero-buttons">
        <?php if($isLoggedIn): ?>
            <a href="../dashboard/post-item.php" class="primary-btn">+ Report Lost Item</a>
            <a href="../dashboard/post-item.php" class="secondary-btn">+ Report Found Item</a>
        <?php else: ?>
            <a href="../auth/login.php" class="primary-btn">+ Report Lost Item</a>
            <a href="../auth/login.php" class="secondary-btn">+ Report Found Item</a>
        <?php endif; ?>
    </div>
</section>

<!-- ITEMS -->
<section class="items">
    <h2>Recently Posted Items</h2>

    <?php if(!empty($search)): ?>
        <p><strong>Search results for:</strong> "<?php echo htmlspecialchars($search); ?>"</p>
    <?php endif; ?>

    <div class="items-container">
        <?php
        if(mysqli_num_rows($result) > 0) {
            while($item = mysqli_fetch_assoc($result)) {
                echo '<div class="item-card">';

                if(!empty($item['image'])) {
                    echo '<img src="../uploads/' . htmlspecialchars($item['image']) . '" alt="Item Image">';
                }

                echo '<h3>' . htmlspecialchars($item['title']) . '</h3>';
                echo '<p>' . htmlspecialchars($item['description']) . '</p>';
                echo '<span class="posted-at">' . date("d M Y", strtotime($item['created_at'])) . '</span>';
                echo'<div class="item-meta">';
                echo '<span class="item-category">' . ucfirst($item['category']) . '</span>';
                echo '<span class="item-type ' . htmlspecialchars($item['type']) . '">' 
                  . ucfirst($item['type']) . 
                   '</span>';
                "</div>";

                echo '</div>';
            }
        } else {
            echo "<p>No items found.</p>";
        }
        ?>
    </div>
</section>

</div>

<!-- FOOTER -->
<footer class="footer">
    <p>&copy; 2025 Lost & Found. All rights reserved.</p>
</footer>

</body>
</html>
