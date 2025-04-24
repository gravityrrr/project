<?php
session_start();
require '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
   // print($password . "\n");
   // print($admin['password_hash']. "\n");
      
    $pass_check = password_verify($password, $admin['password_hash']);
   // echo("\n" . $pass_check. "\n");

    if ($admin && $pass_check) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gym Management System - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="login-body">
  <div class="login-container">
    <div class="login-left">
      <div class="login-intro">
        <h1>Gym Management System</h1>
        <p>Welcome back! Please login to your account.</p>
      </div>
    </div>
    <div class="login-right">
      <div class="login-form-container">
        <div class="login-logo">
          <img src="../assets/images/logo.jpg" alt="Gym Management System Logo">
        </div>
        <h2>Admin Login</h2>

        <!-- Form submission handled by PHP (same page) -->
        <form method="POST" id="loginForm">
          <div class="form-group">
            <label for="username" style="color: #cbd5e1;">Username</label>
            <input type="text" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password" style="color: #cbd5e1;">Password</label>
            <input type="password" id="password" name="password" required>
          </div>
          <?php if (!empty($error)) echo "<div class='login-error' style='color:red;'>$error</div>"; ?>
          <button type="submit" class="btn-primary">Log In</button>
        </form>

      </div>
    </div>
  </div>
</body>
</html>
