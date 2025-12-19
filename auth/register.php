<?php
include("../config/db.php");

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (name, email, password) 
              VALUES ('$name','$email','$password')";

    if(mysqli_query($conn, $query)) {
        header("Location: login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">       
<head>
    <meta charset="UTF-8">
    <title>Register | CampusFound</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
<div class="register-container">
    <h2>Register</h2>
    <form method="POST">
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" name="register">Register</button>
    </form>

    <div class="text">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>
</body>
</html>
