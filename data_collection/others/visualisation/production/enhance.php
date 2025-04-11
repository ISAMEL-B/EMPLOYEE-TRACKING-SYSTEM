<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General styling for the page and containers */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
            font-weight: 700;
        }

        .container {
            margin-top: 40px;
        }

        /* Styling for each scorecard */
        .scorecard {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        /* Add hover effect for scorecards */
        .scorecard:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Styling for progress bars */
        .progress {
            height: 20px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background-color: #4CAF50;
            border-radius: 10px;
        }

        /* Individual chart container styling */
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
        }

        /* Styling for headings within cards */
        .scorecard h2 {
            font-size: 1.25rem;
            color: #555;
            font-weight: 600;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Dashboard</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="scorecard">
                    <h2>App Versions</h2>
                    <div class="chart-container">
                        <canvas id="appVersionsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="scorecard">
                    <h2>Device Usage</h2>
                    <div class="chart-container">
                        <canvas id="deviceUsageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="scorecard">
                    <h2>Campaign Performance</h2>
                    <div class="chart-container">
                        <canvas id="campaignChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="scorecard">
                    <h2>Profile Completion</h2>
                    <div id="profileCompletion" class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>



        // Fetch data from JSON file
        fetch('data.json')
            .then(response => response.json())
            .then(data => {
                // App Versions Chart
                const appVersionsCtx = document.getElementById('appVersionsChart').getContext('2d');
                const appVersionsChart = new Chart(appVersionsCtx, {
                    type: 'bar',
                    data: {
                        labels: data.appVersions.map(v => v.version),
                        datasets: [{
                            label: 'Usage',
                            data: data.appVersions.map(v => v.usage),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Device Usage Chart
                const deviceUsageCtx = document.getElementById('deviceUsageChart').getContext('2d');
                const deviceUsageChart = new Chart(deviceUsageCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(data.deviceUsage),
                        datasets: [{
                            label: 'Device Usage',
                            data: Object.values(data.deviceUsage),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    }
                });

                // Campaign Performance Chart
                const campaignCtx = document.getElementById('campaignChart').getContext('2d');
                const campaignChart = new Chart(campaignCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(data.campaignPerformance),
                        datasets: [{
                            label: 'Performance',
                            data: Object.values(data.campaignPerformance),
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Profile Completion
                const profileCompletion = document.getElementById('profileCompletion').querySelector('.progress-bar');
                profileCompletion.style.width = `${data.profileCompletion}%`;
                profileCompletion.setAttribute('aria-valuenow', data.profileCompletion);
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
</body>
</html>
