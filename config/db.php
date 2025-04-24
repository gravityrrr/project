<?php
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = '127.0.0.1';          // use IP to avoid DNS issues
$db   = 'gym_management';
$user = 'root';
$pass = '';        // if no password, just use ''
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Connected successfully"; // uncomment this to test
} catch (PDOException $e) {
    exit("Database connection failed: " . $e->getMessage());
}
?>
