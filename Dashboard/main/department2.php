<?php
// department.php
    //get department score
    include '../../scoring_calculator/department score/department_score.php';
// Assuming you have a valid connection and you got the department name or details if needed
$department_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Department Performance</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .dropdown {
      text-align: center;
      margin-bottom: 30px;
    }

    select {
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    canvas {
      margin-top: 30px;
    }

    #noDataMessage {
      display: none;
      text-align: center;
      color: red;
      font-weight: bold;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Department Staff Performance</h2>

    <div class="dropdown">
      <label for="rankFilter">Select Rank:</label>
      <select id="rankFilter">
        <option value="Professor">Professor</option>
        <option value="Associate Professor">Associate Professor</option>
        <option value="Lecturer">Lecturer</option>
      </select>
    </div>

    <canvas id="performanceChart" width="900" height="400"></canvas>
    <div id="noDataMessage">No data available for the selected rank and department.</div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const rankSelect = document.getElementById('rankFilter');
      const noDataMessage = document.getElementById('noDataMessage');
      const ctx = document.getElementById('performanceChart').getContext('2d');
      let chartInstance;

      // 🔍 Get department_id from URL
      const urlParams = new URLSearchParams(window.location.search);
      const departmentId = urlParams.get('id');

      function fetchAndRenderChart(rank) {
        fetch(`graphs/get_performance_data.php?rank=${encodeURIComponent(rank)}&department_id=${encodeURIComponent(departmentId)}`)
          .then(response => response.json())
          .then(data => {
            if (!Array.isArray(data) || data.length === 0) {
              noDataMessage.style.display = 'block';
              if (chartInstance) chartInstance.destroy();
              return;
            }

            noDataMessage.style.display = 'none';

            const labels = data.map(staff => staff.name);
            const experience = data.map(staff => staff.experience);
            const publications = data.map(staff => staff.publications);
            const grants = data.map(staff => staff.grants);

            if (chartInstance) {
              chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [
                  {
                    label: 'Teaching Experience (years)',
                    data: experience,
                    backgroundColor: '#3498db'
                  },
                  {
                    label: 'Total Publications',
                    data: publications,
                    backgroundColor: '#2ecc71'
                  },
                  {
                    label: 'Total Grants',
                    data: grants,
                    backgroundColor: '#e74c3c'
                  }
                ]
              },
              options: {
                responsive: true,
                plugins: {
                  title: {
                    display: true,
                    text: 'Staff Performance'
                  }
                },
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          })
          .catch(error => {
            console.error('Error fetching performance data:', error);
          });
      }

      // Load chart on page load
      fetchAndRenderChart(rankSelect.value);

      // Update chart on rank change
      rankSelect.addEventListener('change', function () {
        fetchAndRenderChart(this.value);
      });
    });
  </script>
</body>
</html>
