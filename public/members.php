<?php
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/../config/db.php';

// Handle Create
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $stmt = $pdo->prepare("INSERT INTO members (name, age, gender, address, phone, email) VALUES (?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['name'], $_POST['age'], $_POST['gender'],
        $_POST['address'], $_POST['phone'], $_POST['email']
    ]);
    header('Location: members.php');
    exit;
}

// Handle Update
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $stmt = $pdo->prepare("UPDATE members SET name=?, age=?, gender=?, address=?, phone=?, email=? WHERE id=?");
    $stmt->execute([
        $_POST['name'], $_POST['age'], $_POST['gender'],
        $_POST['address'], $_POST['phone'], $_POST['email'],
        $_POST['id']
    ]);
    header('Location: members.php');
    exit;
}

// Handle Delete
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM members WHERE id=?");
    $stmt->execute([$_POST['id']]);
    header('Location: members.php');
    exit;
}

// Fetch all members
$members = $pdo->query("SELECT * FROM members ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Members – FitFusion</title>
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
                          d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5-2h2a2 2 0 0 0 2-2v-5.586a1 1 0 0 0-.293-.707l-8-8a1 1 0 0 0-1.414 0l-8 8A1 1 0 0 0 3 12.414V19a2 2 0 0 0 2 2h2"/>
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
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Attendance
            </a>

            <a href="equipment.php"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'equipment.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M14.7 4.3a1 1 0 011.4 1.4l-2 2a1 1 0 01-.7.3h-2a6 6 0 100 12h.5a1 1 0 011 1v2a1 1 0 01-.3.7l-2 2a1 1 0 01-1.4-1.4l2-2a1 1 0 01.7-.3H12a4 4 0 110-8h-.5a1 1 0 01-1-1V7a1 1 0 01.3-.7l2-2z"/>
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
            <h1 class="text-2xl font-semibold">Members</h1>
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

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold">Members</h1>
                <button onclick="openModal('createModal')"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                    + Add Member
                </button>
            </div>

            <div class="bg-gray-800 rounded-2xl shadow p-4 overflow-x-auto">
                <table class="min-w-full text-left text-gray-300">
                    <thead class="border-b border-gray-700 text-gray-400">
                    <tr>
                        <th class="py-2 px-4">Name</th>
                        <th class="py-2 px-4">Age</th>
                        <th class="py-2 px-4">Gender</th>
                        <th class="py-2 px-4">Phone</th>
                        <th class="py-2 px-4">Email</th>
                        <th class="py-2 px-4">Joined</th>
                        <th class="py-2 px-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($members as $m): ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="py-2 px-4"><?= htmlspecialchars($m['name']) ?></td>
                            <td class="py-2 px-4"><?= $m['age'] ?></td>
                            <td class="py-2 px-4"><?= ucfirst($m['gender']) ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($m['phone']) ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($m['email']) ?></td>
                            <td class="py-2 px-4"><?= date('M j, Y', strtotime($m['created_at'])) ?></td>
                            <td class="py-2 px-4 flex gap-2">
                                <button onclick="openEdit(<?= $m['id'] ?>,'<?= addslashes($m['name']) ?>',<?= $m['age'] ?>,'<?= $m['gender'] ?>','<?= addslashes($m['address']) ?>','<?= $m['phone'] ?>','<?= $m['email'] ?>')"
                                        class="text-blue-400 hover:text-blue-200">
                                    Edit
                                </button>
                                <form method="POST" class="inline"
                                      onsubmit="return confirm('Delete this member?')">
                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="text-red-500 hover:text-red-300">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="7" class="py-4 text-center text-gray-500">No members found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-gray-800 rounded-2xl p-6 w-full max-w-lg">
        <h2 class="text-xl font-semibold mb-4">Add New Member</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" value="create">
            <div>
                <label class="block mb-1">Name</label>
                <input name="name" required class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block mb-1">Age</label>
                    <input name="age" type="number" class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                </div>
                <div class="flex-1">
                    <label class="block mb-1">Gender</label>
                    <select name="gender" class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block mb-1">Address</label>
                <textarea name="address" class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200"></textarea>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block mb-1">Phone</label>
                    <input name="phone" class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                </div>
                <div class="flex-1">
                    <label class="block mb-1">Email</label>
                    <input name="email" type="email" class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 bg-gray-600 rounded-lg text-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 rounded-lg text-white">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-gray-800 rounded-2xl p-6 w-full max-w-lg">
        <h2 class="text-xl font-semibold mb-4">Edit Member</h2>
        <form method="POST" id="editForm" class="space-y-4">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <div>
                <label class="block mb-1">Name</label>
                <input name="name" id="edit_name" required class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block mb-1">Age</label>
                    <input name="age" id="edit_age" type="number"
                           class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                </div>
                <div class="flex-1">
                    <label class="block mb-1">Gender</label>
                    <select name="gender" id="edit_gender" class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block mb-1">Address</label>
                <textarea name="address" id="edit_address"
                          class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200"></textarea>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block mb-1">Phone</label>
                    <input name="phone" id="edit_phone" class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                </div>
                <div class="flex-1">
                    <label class="block mb-1">Email</label>
                    <input name="email" id="edit_email" type="email"
                           class="w-full px-3 py-2 bg-gray-700 rounded-lg text-gray-200">
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 bg-gray-600 rounded-lg text-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 rounded-lg text-white">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal helpers
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// Pre-fill edit form
function openEdit(id, name, age, gender, address, phone, email) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_age').value = age;
    document.getElementById('edit_gender').value = gender;
    document.getElementById('edit_address').value = address;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_email').value = email;
    openModal('editModal');
}
</script>
</body>
</html>
