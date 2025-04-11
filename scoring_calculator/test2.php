<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Faculty Performance Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #1f2937;
      color: white;
      font-family: sans-serif;
      padding: 20px;
    }
    h1 {
      font-size: 2rem;
      margin-bottom: 1rem;
    }
    .grid {
      display: grid;
      gap: 1rem;
    }
    .grid-3 {
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    .card {
      background-color: #374151;
      padding: 1rem;
      border-radius: 1rem;
    }
    .tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    .tab-btn {
      background: #374151;
      border: none;
      padding: 0.5rem 1rem;
      color: white;
      border-radius: 0.5rem;
      cursor: pointer;
    }
    .tab-btn.active {
      background: #4b5563;
    }
    .tab-content {
      display: none;
    }
    .tab-content.active {
      display: block;
    }
    select {
      padding: 0.5rem;
      border-radius: 0.5rem;
      background: #374151;
      color: white;
      border: 1px solid #4b5563;
    }
  </style>
</head>
<body>
  <h1>Faculty Performance Dashboard</h1>

  <!-- Filters -->
  <div class="grid grid-3">
    <select>
      <option>Select Faculty</option>
      <option>Faculty of Science</option>
      <option>Faculty of Medicine</option>
      <option>Faculty of Computing</option>
    </select>
    <select>
      <option>Academic Year</option>
      <option>2024/2025</option>
      <option>2023/2024</option>
    </select>
    <select>
      <option>Department</option>
      <option>Biology</option>
      <option>Chemistry</option>
      <option>Computer Science</option>
    </select>
  </div>

  <!-- Tabs -->
  <div class="tabs">
    <button class="tab-btn active" onclick="showTab('overview')">Overview</button>
    <button class="tab-btn" onclick="showTab('departments')">Department Comparison</button>
    <button class="tab-btn" onclick="showTab('heatmap')">Activity Heatmap</button>
  </div>

  <!-- Tab Contents -->
  <div id="overview" class="tab-content active">
    <div class="grid grid-3">
      <div class="card">
        <h3>Category Contribution</h3>
        <canvas id="pieChart"></canvas>
      </div>
      <div class="card">
        <h3>Faculty Trend</h3>
        <canvas id="barChart"></canvas>
      </div>
      <div class="card">
        <h3>Category Weight (Radar)</h3>
        <canvas id="radarChart"></canvas>
      </div>
    </div>
  </div>

  <div id="departments" class="tab-content">
    <div class="card">
      <h3>Performance by Department</h3>
      <canvas id="departmentBarChart"></canvas>
    </div>
  </div>

  <div id="heatmap" class="tab-content">
    <div class="card">
      <h3>Monthly Faculty Activity</h3>
      <canvas id="lineChart"></canvas>
    </div>
  </div>

  <script>
    function showTab(tabId) {
      document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
      document.getElementById(tabId).classList.add('active');
      event.target.classList.add('active');
    }

    new Chart(document.getElementById('pieChart'), {
      type: 'pie',
      data: {
        labels: ['Academic', 'Research & Innovation', 'Community Service'],
        datasets: [{
          label: 'Contributions',
          data: [40, 35, 25],
          backgroundColor: ['#8b5cf6', '#3b82f6', '#10b981']
        }]
      }
    });

    new Chart(document.getElementById('barChart'), {
      type: 'bar',
      data: {
        labels: ['2021', '2022', '2023', '2024'],
        datasets: [{
          label: 'Total Score',
          data: [70, 75, 85, 90],
          backgroundColor: '#3b82f6'
        }]
      }
    });

    new Chart(document.getElementById('radarChart'), {
      type: 'radar',
      data: {
        labels: ['Academic', 'Research', 'Community'],
        datasets: [{
          label: 'Weight',
          data: [80, 60, 50],
          backgroundColor: 'rgba(16,185,129,0.2)',
          borderColor: '#10b981'
        }]
      }
    });

    new Chart(document.getElementById('departmentBarChart'), {
      type: 'bar',
      data: {
        labels: ['Biology', 'Chemistry', 'Computer Science'],
        datasets: [{
          label: 'Score',
          data: [78, 82, 95],
          backgroundColor: '#facc15'
        }]
      }
    });

    new Chart(document.getElementById('lineChart'), {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Activity Level',
          data: [20, 35, 40, 50, 45, 60],
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239,68,68,0.2)'
        }]
      }
    });
  </script>
</body>
</html>
