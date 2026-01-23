<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

/*
|--------------------------------------------------------------------------
| 1. If already logged in → redirect by role
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/admin-dashboard.php");
    } else {
        header("Location: ../dashboard/index.php");
    }
    exit();
}

/*
|--------------------------------------------------------------------------
| 2. Handle login form submission
|--------------------------------------------------------------------------
*/
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id,name, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = strtolower(trim($user['role']));
            
            if ($_SESSION['role'] === 'admin') {
                header("Location: ../admin/admin-dashboard.php");
            } else {
                header("Location: ../dashboard/index.php");
            }
            exit();
        }
    }

    // Store error in session and redirect
    $_SESSION['error'] = "Invalid email or password";
    header("Location: login.php"); // redirect to same page
    exit();
}

// Display error and clear session
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
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
    <?php if(isset($error)): ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php endif; ?>
    
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

