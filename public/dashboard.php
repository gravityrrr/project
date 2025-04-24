<?php
// public/dashboard.php
session_start();

// 1) AUTH CHECK
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// 2) LOAD DB
require __DIR__ . '/../config/db.php';

// 3) FETCH COUNTS
$tables = [
    'members'   => 'Total Members',
    'trainers'  => 'Total Trainers',
    'packages'  => 'Active Packages',
    'equipment' => 'Equipment'
];
$stats = [];
foreach ($tables as $tbl => $label) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM `$tbl`");
    $stats[$tbl] = $stmt->fetchColumn();
}

// 4) FETCH RECENT ACTIVITY
$recent = $pdo->query(
    "SELECT a.date, a.time_in, a.time_out, m.name 
       FROM attendance a
       JOIN members m ON a.user_id = m.id
      WHERE a.role = 'member'
      ORDER BY a.date DESC, a.time_in DESC
      LIMIT 5"
)->fetchAll();

// 5) FETCH GENDER DISTRIBUTION
$genders = $pdo->query(
    "SELECT gender, COUNT(*) AS cnt FROM members GROUP BY gender"
)->fetchAll(PDO::FETCH_KEY_PAIR);
$male   = $genders['male']   ?? 0;
$female = $genders['female'] ?? 0;
$other  = $genders['other']  ?? 0;

// 6) FETCH TRAINER SPECIALTIES
$specs = $pdo->query(
    "SELECT specialty, COUNT(*) AS cnt 
       FROM trainers 
   GROUP BY specialty 
      LIMIT 5"
)->fetchAll();
$labels = array_column($specs, 'specialty');
$counts = array_column($specs, 'cnt');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard – FitFusion Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^3/dist/tailwind.min.css" rel="stylesheet">
  <!-- Your custom styles -->
  <link rel="stylesheet" href="../assets/css/styles.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="app-container">

  <!-- SIDEBAR -->
  <?php include __DIR__ . '/includes/sidebar.php'; ?>

  <div class="main-content">
    <!-- TOP HEADER -->
    <?php include __DIR__ . '/includes/top-header.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="content-area">

      <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>

      <!-- STATS GRID -->
      <div class="stats-grid mb-8">
        <?php foreach ($stats as $key => $count): ?>
          <div class="stat-card">
            <div class="stat-icon <?= $key ?>-icon">
              <!-- icon SVG could go here -->
            </div>
            <div class="stat-info">
              <h3 class="stat-info"><?= $tables[$key] ?></h3>
              <p class="stat-value"><?= $count ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- RECENT ACTIVITY -->
      <div class="table-container mb-8">
        <h2 class="text-lg font-semibold mb-4">Recent Member Activity</h2>
        <table class="data-table">
          <thead>
            <tr>
              <th>Member</th><th>Date</th><th>Time In</th><th>Time Out</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($recent): ?>
              <?php foreach ($recent as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= $row['date'] ?></td>
                  <td><?= $row['time_in'] ?></td>
                  <td><?= $row['time_out'] ?? '—' ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4" class="text-center">No recent activity</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- CHARTS -->
      <div class="chart-grid">
        <div class="chart-card">
          <div class="chart-header">
            <h3>Member Gender Distribution</h3>
          </div>
          <div class="chart-body">
            <canvas id="memberChart"></canvas>
          </div>
        </div>
        <div class="chart-card">
          <div class="chart-header">
            <h3>Trainer Specialties</h3>
          </div>
          <div class="chart-body">
            <canvas id="trainerChart"></canvas>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- CHART.JS SCRIPTS -->
  <script>
    // Doughnut for Gender
    new Chart(document.getElementById('memberChart'), {
      type: 'doughnut',
      data: {
        labels: ['Male','Female','Other'],
        datasets: [{
          data: [<?= $male ?>, <?= $female ?>, <?= $other ?>],
          backgroundColor: ['#3b82f6','#ec4899','#f59e0b']
        }]
      },
      options: { responsive: true, maintainAspectRatio: false }
    });

    // Bar for Specialties
    new Chart(document.getElementById('trainerChart'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Trainers',
          data: <?= json_encode($counts) ?>,
          backgroundColor: '#6366f1',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, precision: 0 } }
      }
    });
  </script>

</body>
</html>
