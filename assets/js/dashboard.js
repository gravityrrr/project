document.addEventListener('DOMContentLoaded', async () => {
  if (!checkAuth()) return; // From auth.js
  
  // Fetch dashboard statistics
  fetchStats();
  
  // Fetch attendance data for the chart
  fetchAttendanceData();
  
  // Fetch distribution data for pie chart
  fetchDistributionData();
});

async function fetchStats() {
  try {
    const stats = await apiRequest('/api/stats'); // From auth.js
    
    if (stats) {
      // Update stat cards
      document.getElementById('memberCount').textContent = stats.members;
      document.getElementById('trainerCount').textContent = stats.trainers;
      document.getElementById('equipmentCount').textContent = stats.equipment;
      document.getElementById('packageCount').textContent = stats.packages;
    }
  } catch (error) {
    console.error('Error fetching stats:', error);
  }
}

async function fetchAttendanceData() {
  try {
    const attendanceData = await apiRequest('/api/attendance/last-seven-days'); // From auth.js
    
    if (attendanceData) {
      renderAttendanceChart(attendanceData);
    }
  } catch (error) {
    console.error('Error fetching attendance data:', error);
  }
}

function renderAttendanceChart(data) {
  const ctx = document.getElementById('attendanceChart').getContext('2d');
  
  // Format dates for display
  const labels = data.map(item => {
    const date = new Date(item.date);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
  });
  
  const memberData = data.map(item => item.member);
  const trainerData = data.map(item => item.trainer);
  
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Members',
          data: memberData,
          backgroundColor: '#f59e0b',
          borderColor: '#f59e0b',
          borderWidth: 1
        },
        {
          label: 'Trainers',
          data: trainerData,
          backgroundColor: '#4f46e5',
          borderColor: '#4f46e5',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0
          }
        }
      },
      plugins: {
        legend: {
          position: 'top'
        }
      }
    }
  });
}

async function fetchDistributionData() {
  try {
    const distributionData = await apiRequest('/api/stats/total-distribution'); // From auth.js
    
    if (distributionData) {
      renderDistributionChart(distributionData);
    }
  } catch (error) {
    console.error('Error fetching distribution data:', error);
  }
}

function renderDistributionChart(data) {
  const ctx = document.getElementById('distributionChart').getContext('2d');
  
  const labels = data.map(item => item.label);
  const counts = data.map(item => item.count);
  
  // Colors for each category
  const colors = [
    '#ef4444', // Members - Red
    '#4f46e5', // Trainers - Primary
    '#f59e0b', // Equipment - Yellow
    '#10b981'  // Packages - Green
  ];
  
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: counts,
        backgroundColor: colors,
        borderColor: colors,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right'
        }
      }
    }
  });
}