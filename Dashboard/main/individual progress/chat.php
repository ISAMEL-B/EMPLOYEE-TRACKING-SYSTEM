<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Individual Performance Scorecard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .profile-header {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .score-card {
      margin-top: 20px;
    }
    .card-title span {
      font-size: 0.9rem;
      color: #6c757d;
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <!-- Header Section -->
    <div class="profile-header row align-items-center">
      <div class="col-md-2 text-center">
        <img src="https://via.placeholder.com/100" class="rounded-circle" alt="Profile Picture">
      </div>
      <div class="col-md-10">
        <h3>Dr. Jane Doe</h3>
        <p>Senior Lecturer | Department of Computer Science | Faculty of Science and Technology</p>
        <p><strong>Total Score:</strong> 74/100</p>
      </div>
    </div>

    <!-- Radar Chart Section -->
    <div class="score-card row mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Performance Overview</div>
          <div class="card-body">
            <canvas id="radarChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Score Breakdown -->
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Academic Qualifications <span>(18 pts)</span></h5>
            <div class="progress">
              <div class="progress-bar" style="width: 90%">90%</div>
            </div>
          </div>
        </div>
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Publications <span>(22 pts)</span></h5>
            <canvas id="pubCiteChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Teaching Experience <span>(10 pts)</span></h5>
            <canvas id="teachingLineChart"></canvas>
          </div>
        </div>
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Research Grants <span>(6 pts)</span></h5>
            <div class="progress">
              <div class="progress-bar bg-info" style="width: 50%">50%</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Insights -->
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Insights</div>
          <div class="card-body">
            <ul>
              <li>Strongest area: Publications (22 points)</li>
              <li>Area for improvement: Research Grants</li>
              <li>No recent innovations recorded</li>
              <li>Consistent teaching record over last 5 years</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const radarChart = new Chart(document.getElementById('radarChart'), {
      type: 'radar',
      data: {
        labels: ['Qualifications', 'Publications', 'Teaching', 'Grants', 'Supervision', 'Innovations', 'Activities', 'University Service', 'Community Service', 'Memberships', 'Conduct'],
        datasets: [{
          label: 'Performance Score',
          data: [18, 22, 10, 6, 8, 0, 3, 5, 5, 2, 3],
          fill: true,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          pointBackgroundColor: 'rgba(54, 162, 235, 1)'
        }]
      },
      options: {
        responsive: true,
        scales: {
          r: {
            suggestedMin: 0,
            suggestedMax: 25
          }
        }
      }
    });

    const pubCiteChart = new Chart(document.getElementById('pubCiteChart'), {
      type: 'bar',
      data: {
        labels: ['Article A', 'Article B', 'Article C'],
        datasets: [
          {
            label: 'Publications',
            data: [4, 4, 4],
            backgroundColor: '#007bff'
          },
          {
            label: 'Citations',
            data: [10, 2, 5],
            backgroundColor: '#ffc107'
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          }
        }
      }
    });

    const teachingLineChart = new Chart(document.getElementById('teachingLineChart'), {
      type: 'line',
      data: {
        labels: ['2019', '2020', '2021', '2022', '2023'],
        datasets: [{
          label: 'Years of Experience',
          data: [1, 2, 3, 4, 5],
          fill: false,
          borderColor: '#28a745',
          tension: 0.1
        }]
      },
      options: {
        responsive: true
      }
    });
  </script>
</body>
</html>
