<?php
include("../config/db.php"); // Make sure db.php connects to your database

if(isset($_POST['register'])) {
    // Get POST data and escape to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Server-side password match check
    if($password !== $confirmPassword){
        $error = "Passwords do not match!";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $query = "INSERT INTO users (name, email, password) VALUES ('$name','$email','$hashedPassword')";
        if(mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">       
<head>
    <meta charset="UTF-8">
    <title>Register | Lost & found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/register.css">
    
</head>
<body>
  <div class="container">
    <div class="left-panel">
        <div class="branding">
            <h1>Lost&Found</h1>
            <p>Your gateway to college connections.</p>
        </div>
    </div>
    
    <div class="right-panel">
        <div class="form-container">
            <h1>Register</h1>

            <!-- Display PHP error if exists -->
            <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

            <form id="registerForm" method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>
    <div class="password-requirements">
        Must be at least 8 characters with uppercase, lowercase, and a number
    </div>
</div>

<div class="form-group">
    <label for="confirmPassword">Confirm Password</label>
    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
</div>
    
                
                 <button type="submit" name="register" class="btn-register">Register</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="../auth/login.php">Login</a>
            </div>
        </div>
    </div>
</div>
<script src="../assets/js/register.js"></script>


</body>
</html>
