<?php
session_start();
require '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get admin details
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($admin['username']) ?></h1>
    <nav>
        <a href="members.php">Members</a>
        <a href="trainers.php">Trainers</a>
        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>