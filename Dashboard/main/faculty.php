
<?php
    session_start();
    // Check if user is NOT logged in OR not HRM
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
        header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University - Faculty Performance Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            padding: 20px;
        }

        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            color: var(--dark-color);
        }

        .faculty-selector {
            padding: 8px 15px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            background-color: white;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
        }

        .card h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
            font-size: 16px;
        }

        .card .value {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .card .subtext {
            color: #777;
            font-size: 14px;
        }

        .card.trend-up .value {
            color: var(--secondary-color);
        }

        .card.trend-down .value {
            color: var(--accent-color);
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .large-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            height: auto;
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .section-title {
            margin-bottom: 15px;
            color: var(--dark-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .department-comparison {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
        }

        .department-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .department-table th, .department-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .department-table th {
            background-color: #f8f9fa;
            color: var(--dark-color);
        }

        .department-table tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-primary {
            background-color: #d4edff;
            color: var(--primary-color);
        }

        .badge-success {
            background-color: #d4f5e0;
            color: var(--secondary-color);
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #ffc107;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: var(--accent-color);
        }

        .footer-notes {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .tab-container {
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .tab {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tab.active {
            border-bottom: 3px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .view-details {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .view-details:hover {
            background-color: rgba(52, 152, 219, 0.1);
            text-decoration: underline;
        }

        .department-table th:nth-child(7),
        .department-table td:nth-child(7) {
            text-align: center;
        }

        /* Improved Stat Cards */
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-top: 4px solid var(--primary-color);
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .stat-card .value {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--dark-color);
            margin: 10px 0 5px;
        }

        .stat-card .subtext {
            color: #666;
            font-size: 0.9rem;
        }

        /* Color variations for stat cards */
        .summary-cards .stat-card:nth-child(1) {
            border-top-color: #3498db;
        }
        .summary-cards .stat-card:nth-child(2) {
            border-top-color: #2ecc71;
        }
        .summary-cards .stat-card:nth-child(3) {
            border-top-color: #e74c3c;
        }
        .summary-cards .stat-card:nth-child(4) {
            border-top-color: #f39c12;
        }

        @media (max-width: 1200px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <link rel="stylesheet" href="styles/individual_style.css">

</head>
<body>

    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; 
    $current_ur = 'index2.php';
    $current_pag = 'index2';
    ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>
    
    <div class="content-wrapper">
        <div class="dashboard">
            <div class="explorer-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 fw-bold">Faculty of computing and informartics</h1>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <i class="fas fa-trophy fa-4x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-3" style="text-align: right;">
                <a href="index2.php" 
                style="display: inline-block; 
                        padding: 8px 16px;
                        background-color: #3498db;
                        color: white;
                        text-decoration: none;
                        border-radius: 4px;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        padding-bottom: 3px;">
                    <i class="fas fa-arrow-left me-2"></i>Back to Faculty Dashboard
                </a>
            </div>

            <div class="summary-cards">
                <div class="card">
                    <h3>Total Staff</h3>
                    <div class="value">142</div>
                    <div class="subtext">32 Professors, 65 Lecturers</div>
                </div>
                <div class="card trend-up">
                    <h3>Total publications</h3>
                    <div class="value">34</div>
                </div>
                <div class="card trend-up">
                    <h3>Research Grants</h3>
                    <div class="value">UGX 3.2B</div>
                </div>
                <div class="card trend-down">
                    <h3>Total Innovations</h3>
                    <div class="value">43</div>
                </div>
            </div>

            <div class="tab-container">
                <div class="tabs">
                    <div class="tab active" onclick="switchTab('overview')">Overview</div>
                    <div class="tab" onclick="switchTab('academic')">Academic Performance</div>
                    <div class="tab" onclick="switchTab('publications')">Publications</div>
                    <div class="tab" onclick="switchTab('research')">Research & Innovations</div>
                    <div class="tab" onclick="switchTab('community')">Community Service</div>
                </div>
            </div>

            <!-- Overview Tab Content -->
            <div class="tab-content active" id="overview">
                <div class="main-content">
                    <div class="large-card">
                        <div class="large-card">
                            <div class="section-title">
                                <h2>Faculty Overview</h2>
                            </div>
                            <div class="chart-container">
                            <canvas id="facultyOverviewChart"></canvas>
                        </div>
                        </div>
                        
                    </div>
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Key Metrics Summary</h2>
                        </div>
                        <div class="chart-container" style="height: 400px; width: 100%;">
                            <canvas id="keyMetricsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance Tab Content -->
            <div class="tab-content" id="academic">
                <div class="main-content">
                    <!-- Top Row: 4 Stat Cards -->
                    <div class="summary-cards">
                        <div class="card stat-card">
                            <h3>PhD Holders</h3>
                            <div class="value">25</div>
                            <div class="subtext">32% of faculty</div>
                        </div>
                        <div class="card stat-card">
                            <h3>Masters</h3>
                            <div class="value">45</div>
                            <div class="subtext">58% of faculty</div>
                        </div>
                        <div class="card stat-card">
                            <h3>First Class</h3>
                            <div class="value">34</div>
                            <div class="subtext">44% of faculty</div>
                        </div>
                        <div class="card stat-card">
                            <h3>Second Class</h3>
                            <div class="value">72</div>
                            <div class="subtext">93% of faculty</div>
                        </div>
                    </div>

                    <!-- Single Chart Card for Departmental Qualifications -->
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Qualifications by Department</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="qualificationsByDeptChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publications Tab Content -->
            <div class="tab-content" id="publications">
                <div class="main-content">
                <!-- status cards -->
                <div class="summary-cards"style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    <div class="card stat-card" style="width: 300px;">
                        <h3>Total peer reviewed publications</h3>
                        <div class="value">34</div>
                        
                    </div>
                    <div class="card stat-card" style="width: 300px;">
                        <h3>Total number of citations</h3>
                        <div class="value">72</div>
                        
                    </div>
                    <div class="card stat-card" style="width: 300px;">
                        <h3>Total peer reviewed publications uploaded to must repository</h3>
                        <div class="value">72</div>
                        
                    </div>

                </div>
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Publications vs Citations</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="pubCitationChart"></canvas>
                        </div>
                    </div>
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Publication Types</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="publicationTypesChart"></canvas>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Research & Innovations Tab Content -->
            <div class="tab-content" id="research">
                <div class="main-content">
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Research Grants</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="grantTrendChart"></canvas>
                        </div>
                    </div>
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Innovations</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="innovationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Service Tab Content -->
            <div class="tab-content" id="community">
                <div class="main-content">
                    <!-- status cards -->
                    <div class="summary-cards"style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                        <div class="card stat-card" style="width: 300px;">
                            <h3>Total student supervised for community placements</h3>
                            <div class="value">34</div>
                            
                        </div>
                        <div class="card stat-card" style="width: 300px;">
                            <h3>other community outreaches</h3>
                            <div class="value">72</div>
                            
                        </div>
                        <div class="card stat-card" style="width: 300px;">
                            <h3> total number of beneficiaries</h3>
                            <div class="value">72</div>
                            
                        </div>
                    </div>
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Community Engagement by department</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="communityEngagementChart"></canvas>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Department Comparison Section (Visible on all tabs) -->
            <div class="department-comparison">
                <div class="section-title">
                    <h2>Department Metrics</h2>
                </div>
                <table class="department-table">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Total Score</th>
                            <th>Publications</th>
                            <th>Grants (UGX)</th>
                            <th>Innovations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Medicine</td>
                            <td>8.2</td>
                            <td>12.4</td>
                            <td>1.8B</td>
                            <td>24</td>
                            <td><a href="#" class="view-details">View Details</a></td>
                        </tr>
                        <tr>
                            <td>Surgery</td>
                            <td>7.9</td>
                            <td>10.1</td>
                            <td>1.2B</td>
                            <td>18</td>
                            <td><a href="#" class="view-details">View Details</a></td>
                        </tr>
                        <tr>
                            <td>Pediatrics</td>
                            <td>7.5</td>
                            <td>8.7</td>
                            <td>800M</td>
                            <td>15</td>
                            <td><a href="#" class="view-details">View Details</a></td>
                        </tr>
                        <tr>
                            <td>Pathology</td>
                            <td>6.8</td>
                            <td>7.2</td>
                            <td>500M</td>
                            <td>10</td>
                            <td><a href="#" class="view-details">View Details</a></td>
                        </tr>
                        <tr>
                            <td>Public Health</td>
                            <td>6.2</td>
                            <td>5.8</td>
                            <td>300M</td>
                            <td>8</td>
                            <td><a href="#" class="view-details">View Details</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="footer-notes">
                Data updated: June 2023 | Next review: December 2023
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`.tab[onclick="switchTab('${tabId}')"]`).classList.add('active');
        }

        // Charts initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Faculty Overview Chart
            const overviewCtx = document.getElementById('facultyOverviewChart').getContext('2d');
            new Chart(overviewCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Surgery', 'Pediatrics', 'Pathology', 'Public Health'],
                    datasets: [{
                        label: 'Overall Performance',
                        data: [8.2, 7.9, 7.5, 6.8, 6.2],
                        backgroundColor: 'rgba(52, 152, 219, 0.7)'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10
                        }
                    }
                }
            });

            // Key Metrics Chart
            const keyMetricsCtx = document.getElementById('keyMetricsChart').getContext('2d');
            new Chart(keyMetricsCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Engineering', 'Science', 'Arts', 'Business', 'Law'], // Departments on x-axis
                    datasets: [
                        {
                            label: 'Publications',
                            data: [9.2, 8.1, 7.5, 6.8, 7.2, 6.5], // Scores for each department
                            backgroundColor: 'rgba(52, 152, 219, 0.8)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Grants',
                            data: [8.8, 9.2, 7.8, 6.2, 8.1, 5.8],
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Postgraduate Supervisions',
                            data: [8.5, 7.8, 8.2, 7.5, 6.9, 7.1],
                            backgroundColor: 'rgba(155, 89, 182, 0.8)',
                            borderColor: 'rgba(155, 89, 182, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Innovations',
                            data: [7.2, 8.5, 6.9, 5.8, 7.5, 6.2],
                            backgroundColor: 'rgba(255, 193, 7, 0.8)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 20,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toFixed(1);
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Department Performance by Metric',
                            font: { size: 16, weight: 'bold' },
                            padding: { bottom: 20 }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Department',
                                font: { weight: 'bold', size: 14 }
                            },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 10,
                            ticks: { stepSize: 2 },
                            title: {
                                display: true,
                                text: 'Average Score',
                                font: { weight: 'bold', size: 14 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });

            // Qualifications by Department Chart
            const qualDeptCtx = document.getElementById('qualificationsByDeptChart').getContext('2d');
            new Chart(qualDeptCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Surgery', 'Pediatrics', 'Pathology', 'Public Health'],
                    datasets: [
                        {
                            label: 'PhD',
                            data: [15, 12, 8, 5, 10],
                            backgroundColor: 'rgba(52, 152, 219, 0.8)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Masters',
                            data: [25, 18, 15, 12, 20],
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'First Class',
                            data: [18, 15, 12, 8, 10],
                            backgroundColor: 'rgba(231, 76, 60, 0.8)',
                            borderColor: 'rgba(231, 76, 60, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Second Class',
                            data: [30, 25, 20, 15, 25],
                            backgroundColor: 'rgba(241, 196, 15, 0.8)',
                            borderColor: 'rgba(241, 196, 15, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: false,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Staff'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });

            // Publications vs Citations Chart
            const pubCiteCtx = document.getElementById('pubCitationChart').getContext('2d');
            new Chart(pubCiteCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Surgery', 'Public Health', 'mental health'],
                    datasets: [
                        {
                            label: 'Publications',
                            data: [12, 10, 6, 10], // Average publications per department
                            backgroundColor: 'rgba(52, 152, 219, 0.8)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Citations',
                            data: [45, 32, 12, 34], // Average citations per department
                            backgroundColor: 'rgba(231, 76, 60, 0.8)',
                            borderColor: 'rgba(231, 76, 60, 1)',
                            borderWidth: 1,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Department',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count',
                                font: {
                                    weight: 'bold',
                                    size: 14
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                },
                                usePointStyle: true,
                                boxWidth: 12
                            }
                        },
                        title: {
                            display: true,
                            text: 'Publications and Citations by Department',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 4,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw;
                                }
                            }
                        }
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    }
                }
            });

            // Publication Types Chart
            const pubTypesCtx = document.getElementById('publicationTypesChart').getContext('2d');
            new Chart(pubTypesCtx, {
                type: 'bar',
                data: {
                    labels: ['medicine', 'pharmarcy', 'pharmacology', 'medical lab science'],
                    datasets: [
                        {
                            label: 'Journal articles',
                            data: [120, 25, 8, 30], // Sample data for Medicine department
                            backgroundColor: 'rgba(52, 152, 219, 0.8)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Book chapters',
                            data: [100, 20, 5, 43], // Sample data for Surgery department
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Books',
                            data: [85, 18, 6, 49], // Sample data for Public Health department
                            backgroundColor: 'rgba(155, 89, 182, 0.8)',
                            borderColor: 'rgba(155, 89, 182, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Department',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Publications',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                },
                                usePointStyle: true
                            }
                        },
                        title: {
                            display: true,
                            text: 'Publication Types by Department',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 4
                        }
                    },
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }
            });

            

            // Grant Trend Chart
            const grantCtx = document.getElementById('grantTrendChart').getContext('2d');
            new Chart(grantCtx, {
                type: 'line',
                data: {
                    labels: ['2018', '2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Medicine',
                            data: [500, 650, 800, 950, 1200, 1800],
                            borderColor: 'rgba(52, 152, 219, 1)',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Surgery',
                            data: [300, 400, 450, 600, 750, 1200],
                            borderColor: 'rgba(46, 204, 113, 1)',
                            backgroundColor: 'rgba(46, 204, 113, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Public Health',
                            data: [100, 150, 200, 250, 300, 500],
                            borderColor: 'rgba(231, 76, 60, 1)',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Grant Amount (Millions UGX)'
                            }
                        }
                    }
                }
            });

            // Innovations Chart
            const innovationsCtx = document.getElementById('innovationsChart').getContext('2d');
            new Chart(innovationsCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Surgery', 'Public Health'], // Departments on x-axis
                    datasets: [
                        {
                            label: 'Patents',
                            data: [3, 2, 3], // Medicine, Surgery, Public Health
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',  // Teal
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Utility Models',
                            data: [5, 3, 4],
                            backgroundColor: 'rgba(255, 159, 64, 0.8)',   // Orange
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Copyrights',
                            data: [6, 4, 5],
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',  // Blue
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Products',
                            data: [2, 1, 2],
                            backgroundColor: 'rgba(153, 102, 255, 0.8)',  // Purple
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Trademarks',
                            data: [1, 1, 1],
                            backgroundColor: 'rgba(255, 99, 132, 0.8)',  // Pink
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            title: {
                                display: true,
                                text: 'Department',
                                font: { weight: 'bold' }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count',
                                font: { weight: 'bold' }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 12 },
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        title: {
                            display: true,
                            text: 'Innovations by Department and Type',
                            font: { size: 16, weight: 'bold' },
                            padding: { bottom: 20 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 4,
                            bodyFont: { size: 12 },
                            titleFont: { size: 14 }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });

            // Community Engagement Chart
            const communityCtx = document.getElementById('communityEngagementChart').getContext('2d');
            new Chart(communityCtx, {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Pharmacy', 'Pharmacology', 'Medical Lab Science'],
                    datasets: [
                        {
                            label: 'Students Supervised',
                            data: [120, 25, 8, 30],
                            backgroundColor: 'rgba(255, 99, 132, 0.8)',  // Vibrant Pink
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Community Outreaches',
                            data: [100, 20, 5, 43],
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',  // Bright Blue
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Total Beneficiaries',
                            data: [85, 18, 6, 49],
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',  // Refreshing Teal
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            title: {
                                display: true,
                                text: 'Department',
                                font: { weight: 'bold', size: 14 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Participants',
                                font: { weight: 'bold', size: 14 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 12 },
                                usePointStyle: true,
                                padding: 20,
                                boxWidth: 12
                            }
                        },
                        title: {
                            display: true,
                            text: 'Community Engagement by Department',
                            font: { size: 18, weight: 'bold' },
                            padding: { bottom: 25 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 12,
                            cornerRadius: 6,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            footerFont: { size: 10 }
                        }
                    },
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }
            });
            

            // University Service Chart
            
        });
    </script>
</body>
</html>