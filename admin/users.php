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
    $user_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
    header("Location: users.php");
    exit();
}

// Handle Add / Update Form Submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (isset($_POST['user_id']) && $_POST['user_id'] != "") {
        // Update existing user
        $user_id = intval($_POST['user_id']);
        $query = "UPDATE users SET name='$name', email='$email', role='$role'";
        if ($password != "") {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", password='$hashed'";
        }
        $query .= " WHERE id=$user_id";
        mysqli_query($conn, $query);
    } else {
        // Insert new user
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name','$email','$hashed','$role')";
        mysqli_query($conn, $query);
    }

    header("Location: users.php");
    exit();
}

// Fetch stats
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$total_admins = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='admin'"))['total'];
$total_normal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='user'"))['total'];

// Fetch all users
$users = mysqli_query($conn, "SELECT * FROM users where role='user' ORDER BY id DESC");

// If editing
$edit_user = null;
if (isset($_GET['edit'])) {
    $user_id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
    $edit_user = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin-users.css">
</head>
<body>
<div class="dashboard">
    <!-- Sidebar -->
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
        <h1>Manage Users</h1>

        <!-- Stats Cards -->
        <div class="cards">
            <div class="card"><h3>Total Users</h3><p><?php echo $total_users; ?></p></div>
            <div class="card"><h3>Total Admins</h3><p><?php echo $total_admins; ?></p></div>
            <div class="card"><h3>Normal Users</h3><p><?php echo $total_normal; ?></p></div>
        </div>

        <!-- Add/Edit Form -->
        <div class="form-container">
            <h2><?php echo $edit_user ? "Edit User" : "Add New User"; ?></h2>
            <form method="POST">
                <input type="hidden" name="user_id" value="<?php echo $edit_user['id'] ?? ''; ?>">

                <label>Name</label>
                <input type="text" name="name" value="<?php echo $edit_user['name'] ?? ''; ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo $edit_user['email'] ?? ''; ?>" required>

                <label>Role</label>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="admin" <?php if(isset($edit_user) && $edit_user['role']=='admin') echo 'selected'; ?>>Admin</option>
                    <option value="user" <?php if(isset($edit_user) && $edit_user['role']=='user') echo 'selected'; ?>>User</option>
                </select>

                <label>Password <?php echo $edit_user ? "(Leave blank to keep current)" : ""; ?></label>
                <input type="password" name="password" <?php echo $edit_user ? "" : "required"; ?>>

                <button type="submit" name="submit"><?php echo $edit_user ? "Update User" : "Add User"; ?></button>
            </form>
        </div>

        <!-- Users Grid -->
        <h2>All Users</h2>
        <div class="users-grid">
            <?php while($user = mysqli_fetch_assoc($users)): ?>
            <div class="user-card">
                <h4><?php echo $user['name']; ?></h4>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
                <div class="user-actions">
                    <a href="users.php?edit=<?php echo $user['id']; ?>" class="edit">Edit</a>
                    <a href="users.php?delete=<?php echo $user['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</div>
</body>
</html>
