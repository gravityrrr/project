<?php
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/../config/db.php';

// Stats
$tables = [
    'members'   => 'Total Members',
    'trainers'  => 'Trainers',
    'packages'  => 'Active Packages',
    'equipment' => 'Equipment'
];
$stats = [];
foreach ($tables as $tbl => $label) {
    $stats[$tbl] = $pdo->query("SELECT COUNT(*) FROM `$tbl`")->fetchColumn();
}

// Recent activity
$recent = $pdo->query("
    SELECT a.date, a.time_in, a.time_out, m.name
      FROM attendance a
      JOIN members m ON a.user_id = m.id
     WHERE a.role = 'member'
     ORDER BY a.date DESC, a.time_in DESC
     LIMIT 5
")->fetchAll();

// Gender distribution
$genders = $pdo->query("SELECT gender, COUNT(*) AS cnt FROM members GROUP BY gender")
               ->fetchAll(PDO::FETCH_KEY_PAIR);
$male   = $genders['male']   ?? 0;
$female = $genders['female'] ?? 0;
$other  = $genders['other']  ?? 0;

// Trainer specialties
$specs  = $pdo->query("SELECT specialty, COUNT(*) AS cnt FROM trainers GROUP BY specialty LIMIT 5")
               ->fetchAll();
$labels = array_column($specs, 'specialty');
$counts = array_column($specs, 'cnt');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FitFusion Admin Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Your custom CSS (optional overrides) -->
  <link rel="stylesheet" href="../assets/css/styles.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-900 text-gray-200 font-sans">

<div class="flex h-screen">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-gray-800 p-6 flex flex-col">
    <div class="flex items-center mb-12">
      <img src="../assets/images/logo.jpg" alt="FitFusion" class="h-10 w-10 mr-2 rounded-full object-cover">
      <span class="text-xl font-semibold text-white">FitFusion</span>
    </div>
    <nav class="flex-1 space-y-2 sidebar-menu">
  <a href="dashboard.php"
     class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
    <!-- Home icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5-2h2a2 2 0 0 0 2-2v-5.586a1 1 0 0 0-.293-.707l-8-8a1 1 0 0 0-1.414 0l-8 8A1 1 0 0 0 3 12.414V19a2 2 0 0 0 2 2h2"/>
    </svg>
    Dashboard
  </a>

  <a href="members.php"
     class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'members.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
    <!-- User icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z"/>
    </svg>
    Members
  </a>

  <a href="trainers.php"
     class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'trainers.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
    <!-- Users icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0zm6 7a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    Trainers
  </a>

  <a href="packages.php"
     class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'packages.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
    <!-- Cube icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0v6l-8 4-8-4V7m16 6l-8 4-8-4"/>
    </svg>
    Packages
  </a>

  <a href="attendance.php"
     class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'attendance.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
    <!-- Calendar icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    Attendance
  </a>

  <a href="equipment.php"
     class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'equipment.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
    <!-- Wrench icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M14.7 4.3a1 1 0 011.4 1.4l-2 2a1 1 0 01-.7.3h-2a6 6 0 100 12h.5a1 1 0 011 1v2a1 1 0 01-.3.7l-2 2a1 1 0 01-1.4-1.4l2-2a1 1 0 01.7-.3H12a4 4 0 110-8h-.5a1 1 0 01-1-1V7a1 1 0 01.3-.7l2-2z"/>
    </svg>
    Equipment
  </a>

  <a href="settings.php"
     class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors <?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'bg-gray-700 text-white' : 'text-gray-400' ?>">
    <!-- Cog icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sidebar-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0a1.724 1.724 0 002.064 1.128 1.724 1.724 0 011.11 2.065c.921.3.921 1.602 0 1.902a1.724 1.724 0 00-1.128 2.064 1.724 1.724 0 00-2.065 1.11c-.3.921-1.602.921-1.902 0a1.724 1.724 0 00-2.064-1.128 1.724 1.724 0 00-1.11-2.065c-.921-.3-.921-1.602 0-1.902a1.724 1.724 0 001.128-2.064 1.724 1.724 0 002.065-1.11z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    Settings
  </a>
</nav>

    <a href="logout.php" class="mt-6 flex items-center gap-3 px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M17 16l4-4m0 0l-4-4m4 4H7"/>
      </svg>
      Logout
    </a>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="flex-1 flex flex-col">

    <!-- HEADER -->
    <header class="bg-gray-800 px-6 py-4 flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Dashboard</h1>
      <div class="flex items-center gap-4">
        <input type="search" placeholder="Search..."
               class="px-4 py-1 bg-gray-700 text-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <button class="relative">
          <svg class="h-6 w-6 text-gray-400 hover:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 1 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/>
          </svg>
          <span class="absolute top-0 right-0 inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
        </button>
        <img src="../assets/images/logo.jpg" alt="Admin" class="h-8 w-8 rounded-full border-2 border-blue-500">
      </div>
    </header>

    <!-- PAGE -->
    <div class="p-6 overflow-y-auto">

      <!-- STATS -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php foreach ($stats as $key => $count): ?>
        <div class="bg-gray-800 p-6 rounded-2xl shadow hover:shadow-2xl transition-colors border-l-4 border-blue-500">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-400"><?= $tables[$key] ?></p>
            <!-- icon placeholder -->
          </div>
          <p class="text-4xl font-bold text-white"><?= $count ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      


      <!-- RECENT ACTIVITY -->
      <div class="bg-gray-800 rounded-2xl p-6 mb-8 shadow">
        <h2 class="text-xl font-semibold mb-4">Recent Member Activity</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-left text-gray-300">
            <thead class="border-b border-gray-700 text-gray-400">
              <tr>
                <th class="py-2 px-4">Member</th>
                <th class="py-2 px-4">Date</th>
                <th class="py-2 px-4">Time In</th>
                <th class="py-2 px-4">Time Out</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($recent): foreach ($recent as $r): ?>
              <tr class="border-b border-gray-700 hover:bg-gray-700">
                <td class="py-2 px-4"><?= htmlspecialchars($r['name']) ?></td>
                <td class="py-2 px-4"><?= $r['date'] ?></td>
                <td class="py-2 px-4"><?= $r['time_in'] ?></td>
                <td class="py-2 px-4"><?= $r['time_out'] ?? '—' ?></td>
              </tr>
              <?php endforeach; else: ?>
              <tr><td colspan="4" class="py-4 text-center text-gray-500">No recent activity</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      

<script>
// Gender Doughnut
new Chart(document.getElementById('memberChart'), {
  type: 'doughnut',
  data: {
    labels: ['Male','Female','Other'],
    datasets: [{
      data: [<?= $male ?>,<?= $female ?>,<?= $other ?>],
      backgroundColor: ['#3b82f6','#60a5fa','#93c5fd']
    }]
  },
  options: { maintainAspectRatio:false, responsive:true }
});
// Specialties Bar
new Chart(document.getElementById('trainerChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Trainers',
      data: <?= json_encode($counts) ?>,
      backgroundColor: '#3b82f6',
      borderRadius: 4
    }]
  },
  options: {
    scales: { y: { beginAtZero:true, ticks:{precision:0} } },
    maintainAspectRatio:false, responsive:true
  }
});
</script>

</body>
</html>
