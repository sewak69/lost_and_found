<?php
include("../config/db.php");

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../dashboard/index.php");
    } else {
        echo "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <title>Login | CampusFound</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <!-- <?= $msg ?> -->
    <form method="POST">
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" name="login">Login</button>
    </form>

    <div class="text">
        Don’t have an account? <a href="register.php">Register</a>
    </div>
</div>
</div>
</body>
</html>
