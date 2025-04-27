<?php
session_start();
// Check if admin is logged in
if (empty($_SESSION['admin_id'])) {
    error_log("Session: admin_id is empty. Redirecting to login.php.  Session contents: " . print_r($_SESSION, true));
    header('Location: login.php');
    exit;
}
require __DIR__ . '/../config/db.php';

// Fetch current admin data for pre-filling the form
try {
    $admin_id = $_SESSION['admin_id'];
    error_log("Fetching admin data for admin_id: $admin_id"); // Log the admin ID being used in the query
    $stmt = $pdo->prepare("SELECT username, email FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin_data) {
        error_log("Database: Admin user not found with id: $admin_id.  Query: SELECT username, email FROM admins WHERE id = ?");
        echo "<script>alert('Admin user not found. Please check your session.'); window.location.href='logout.php';</script>";
        exit;
    }
    error_log("Admin data fetched successfully: " . print_r($admin_data, true)); // Log the fetched data

} catch (PDOException $e) {
    $error_message = "Error fetching admin data: " . $e->getMessage();
    error_log($error_message);
    echo "<script>alert('Failed to retrieve your profile data. Please try again.'); window.location.href='dashboard.php';</script>";
    exit;
}

// Handle Update Profile
if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    // Validate data (basic validation, you might want more)
    if (empty($new_username) || empty($new_email)) {
        echo "<script>alert('Username and Email are required.'); window.location.href='settings.php';</script>";
        exit;
    }
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href='settings.php';</script>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE admins SET username=?, email=? WHERE id=?");
        $stmt->execute([$new_username, $new_email, $admin_id]);
        echo "<script>alert('Profile updated successfully.'); window.location.href='settings.php';</script>";
        exit;
    } catch (PDOException $e) {
        $error_message = "Error updating profile: " . $e->getMessage();
        error_log($error_message);
        echo "<script>alert('Failed to update profile. Please try again.'); window.location.href='settings.php';</script>";
        exit;
    }
}

// Handle Change Password
if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate data
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('All password fields are required.'); window.location.href='settings.php';</script>";
        exit;
    }
    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match.'); window.location.href='settings.php';</script>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
        $stmt->execute([$admin_id]);
        $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$admin_data)
        {
            echo "<script>alert('User not found.'); window.location.href='settings.php';</script>";
            exit();
        }
        $hashed_password = $admin_data['password_hash'];

        if (!password_verify($old_password, $hashed_password)) {
            echo "<script>alert('Incorrect old password.'); window.location.href='settings.php';</script>";
            exit;
        }

        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET password_hash=? WHERE id=?");
        $stmt->execute([$new_hashed_password, $admin_id]);
        echo "<script>alert('Password changed successfully.'); window.location.href='settings.php';</script>";
        exit;
    } catch (PDOException $e) {
        $error_message = "Error changing password: " . $e->getMessage();
        error_log($error_message);
        echo "<script>alert('Failed to change password. Please try again.'); window.location.href='settings.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings – FitFusion</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-gray-200 font-sans">

<div class="flex h-screen">

    <aside class="w-64 bg-gray-800 p-6 flex flex-col">
        <div class="flex items-center mb-12">
            <img src="../assets/images/logo.jpg" alt="FitFusion"
                 class="h-10 w-10 mr-2 rounded-full object-cover">
            <span class="text-xl font-semibold text-white">FitFusion</span>
        </div>
        <nav class="flex-1 space-y-2 sidebar-menu">
            <a href="dashboard.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5-2h2a2 2 0 0 0 2-2v-5.586a1 1 0 0 0-.293-.707l-8-8a1 1 0 0 0-1.414 0l-8 8A1 1 0 0 0 3 12.414V19a2 0 0 0 2 2h2"/>
                </svg>
                Dashboard
            </a>

            <a href="members.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'members.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/>
                </svg>
                Members
            </a>

            <a href="trainers.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'trainers.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0zm6 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Trainers
            </a>

            <a href="packages.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'packages.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0v6l-8 4-8-4V7m16 6l-8 4-8-4"/>
                </svg>
                Packages
            </a>

            <a href="attendance.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'attendance.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 0 002-2V7a2 0 00-2-2H5a2 0 00-2 2v12a2 0 002 2z"/>
                </svg>
                Attendance
            </a>

            <a href="equipment.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'equipment.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M14.7 4.3a1 1 0 011.4 1.4l-2 2a1 1 0 01-.7.3h-2a6 6 0 100 12h.5a1 1 0 011 1v2a1 1 0 01-.3.7l-2 2a1 1 0 01-1.4-1.4l2-2a1 1 0 01.7-.3H12a4 4 0 110-8h-.5a1 0 01-1-1V7a1 1 0 01.3-.7l2-2z"/>
                </svg>
                Equipment
            </a>

            <a href="settings.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0a1.724 1.724 0 002.064 1.128 1.724 1.724 0 011.11 2.065c.921.3.921 1.602 0 1.902a1.724 1.724 0 00-1.128 2.064 1.724 1.724 0 00-2.065 1.11c-.3.921-1.602.921-1.902 0a1.724 1.724 0 00-2.064-1.128 1.724 1.724 0 00-1.11-2.065c-.921-.3-.921-1.602 0-1.902a1.724 1.724 0 001.128-2.064 1.724 1.724 0 002.065-1.11z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </nav>

        <a href="logout.php"
           class="mt-6 flex items-center gap-3 px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7"/>
            </svg>
            Logout
        </a>
    </aside>

    <div class="flex-1 flex flex-col">

        <header class="bg-gray-800 px-6 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Settings</h1>
            <div class="flex items-center gap-4">
                <input type="search" placeholder="Search..."
                       class="px-4 py-1 bg-gray-700 text-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                <button class="relative">
                    <svg class="h-6 w-6 text-gray-400 hover:text-gray-200" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-0 right-0 inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                </button>
                <img src="../assets/images/logo.jpg" alt="Admin" class="h-8 w-8 rounded-full border-2 border-blue-500">
            </div>
        </header>

        <div class="p-6 overflow-y-auto">

            <h2 class="text-xl font-semibold mb-6">Profile Settings</h2>
            <form method="POST" class="space-y-4" id="profileForm">
                <input type="hidden" name="action" value="update_profile">
                <div>
                    <label class="block mb-1">Username</label>
                    <input name="username" type="text" value="<?= htmlspecialchars($admin_data['username']) ?>" required
                           class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-1">Email</label>
                    <input name="email" type="email" value="<?= htmlspecialchars($admin_data['email']) ?>" required
                           class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Update Profile
                </button>
            </form>

            <h2 class="text-xl font-semibold mt-8 mb-6">Change Password</h2>
            <form method="POST" class="space-y-4" id="passwordForm">
                <input type="hidden" name="action" value="change_password">
                <div>
                    <label class="block mb-1">Old Password</label>
                    <input name="old_password" type="password" required
                           class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-1">New Password</label>
                    <input name="new_password" type="password" required
                           class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block mb-1">Confirm New Password</label>
                    <input name="confirm_password" type="password" required
                           class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Change Password
                </button>
            </form>

        </div>
    </div>
</div>

<script>
document.getElementById('profileForm').addEventListener('submit', function (e) {
    if (!confirm('Are you sure you want to update your profile?')) {
        e.preventDefault();
    }
});

document.getElementById('passwordForm').addEventListener('submit', function (e) {
    if (!confirm('Are you sure you want to change your password?')) {
        e.preventDefault();
    }
});
</script>
</body>
</html>
