<?php   
include("../config/db.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../dashboard/index.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>

    

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <title>Login | lost & found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
<form method="POST" class="login-container">
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit" name="login" class="login-btn">Login</button>

    <div class="extra-links">
        <p>Don't have an account? <a href="../auth/register.php">Sign up</a></p>
    </div>
</form>

</body>
</html>

