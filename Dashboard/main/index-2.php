<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST Employee Tracking Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --must-green: #006633;
            --must-yellow: #FFCC00;
            --must-blue: #003366;
            --must-light: #F5F5F5;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--must-light);
        }
        
        .sidebar {
            background-color: var(--must-blue);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding-top: 20px;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu {
            padding: 0;
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .sidebar-menu li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu li.active {
            background-color: var(--must-green);
        }
        
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
        }
        
        .dashboard-header {
            background-color: var(--must-green);
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .section-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            padding: 20px;
            border-top: 4px solid var(--must-yellow);
        }
        
        .section-title {
            color: var(--must-blue);
            border-bottom: 2px solid var(--must-yellow);
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .metric-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            border-left: 4px solid var(--must-green);
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--must-blue);
        }
        
        .metric-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .badge-must {
            background-color: var(--must-yellow);
            color: var(--must-blue);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .gauge-container {
            height: 150px;
            width: 100%;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        .progress-bar {
            background-color: var(--must-green);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .main-content.active {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i data-feather="award"></i> MUST HR System</h4>
        </div>
        <ul class="sidebar-menu">
            <li class="active">
                <a href="#"><i data-feather="home"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i data-feather="users"></i> Staff Directory</a>
            </li>
            <li>
                <a href="#"><i data-feather="book"></i> Academic Records</a>
            </li>
            <li>
                <a href="#"><i data-feather="file-text"></i> Research & Publications</a>
            </li>
            <li>
                <a href="#"><i data-feather="dollar-sign"></i> Grants & Funding</a>
            </li>
            <li>
                <a href="#"><i data-feather="heart"></i> Community Service</a>
            </li>
            <li>
                <a href="#"><i data-feather="settings"></i> Settings</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="container-fluid">
                <button class="btn btn-sm d-md-none" id="sidebarToggle">
                    <i data-feather="menu"></i>
                </button>
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i data-feather="user"></i> Admin User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i data-feather="user"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i data-feather="settings"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i data-feather="log-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i data-feather="users"></i> Employee Performance Dashboard</h2>
                    <p class="mb-0">Comprehensive tracking of academic staff metrics and achievements</p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-light text-dark me-2"><i data-feather="calendar"></i> June 2023</span>
                    <button class="btn btn-sm btn-outline-light"><i data-feather="download"></i> Export</button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="metric-card text-center">
                    <div class="metric-value">248</div>
                    <div class="metric-label">Total Academic Staff</div>
                    <div class="gauge-container mt-3">
                        <canvas id="staffGauge"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card text-center">
                    <div class="metric-value">73</div>
                    <div class="metric-label">PhD Holders</div>
                    <div class="gauge-container mt-3">
                        <canvas id="phdGauge"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card text-center">
                    <div class="metric-value">156</div>
                    <div class="metric-label">Master's Holders</div>
                    <div class="gauge-container mt-3">
                        <canvas id="mastersGauge"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Performance Section -->
        <div class="section-card">
            <h3 class="section-title"><i data-feather="graduation-cap"></i> Academic Performance</h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5>Staff Qualifications</h5>
                        <canvas id="qualificationChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5>Degree Classifications</h5>
                        <canvas id="degreeChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="metric-value">42</div>
                                <div class="metric-label">First Class Degrees</div>
                            </div>
                            <div class="text-success">
                                <i data-feather="arrow-up"></i>
                                <div>+5% from 2022</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="metric-value">167</div>
                                <div class="metric-label">Second Class Degrees</div>
                            </div>
                            <div class="text-warning">
                                <i data-feather="minus"></i>
                                <div>No change</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="metric-value">39</div>
                                <div class="metric-label">Staff Pursuing PhDs</div>
                            </div>
                            <div class="text-success">
                                <i data-feather="arrow-up"></i>
                                <div>+12% from 2022</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Research & Innovations Section -->
        <div class="section-card">
            <h3 class="section-title"><i data-feather="book"></i> Research & Innovations</h3>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="chart-container">
                        <h5>Publications Over Time</h5>
                        <canvas id="publicationsChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card h-100">
                        <h5>Research Highlights</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Grants Won</span>
                                <span>24</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 65%"></div>
                            </div>
                            <small class="text-muted">UGX 1.2B total value</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Postgraduate Supervision</span>
                                <span>89</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 80%"></div>
                            </div>
                            <small class="text-muted">62 Masters, 27 PhDs</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Innovations</span>
                                <span>15</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 45%"></div>
                            </div>
                            <small class="text-muted">3 patents, 5 copyrights</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5>Grants by Department</h5>
                        <canvas id="grantsChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5>Student Supervision</h5>
                        <canvas id="supervisionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Community Service Section -->
        <div class="section-card">
            <h3 class="section-title"><i data-feather="heart"></i> Community Service</h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <h5>Service Hours by Department</h5>
                        <canvas id="serviceHoursChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="metric-card h-100">
                        <h5>Community Engagement</h5>
                        <div class="mb-3">
                            <span class="badge badge-must me-1">128</span>
                            <span>Total community projects</span>
                        </div>
                        <div class="mb-3">
                            <span class="badge badge-must me-1">42</span>
                            <span>Staff involved in outreach</span>
                        </div>
                        <div class="mb-3">
                            <span class="badge badge-must me-1">1,240</span>
                            <span>Total service hours</span>
                        </div>
                        <div class="mb-3">
                            <span class="badge badge-must me-1">18</span>
                            <span>Partnerships with local orgs</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="chart-container">
                        <h5>Types of Service Activities</h5>
                        <canvas id="serviceTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
            
            // Sidebar Toggle for Mobile
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
                document.querySelector('.main-content').classList.toggle('active');
            });
            
            // Create all charts after DOM is fully loaded
            createCharts();
        });

        function createGauge(chartId, value, max, label, color) {
            const ctx = document.getElementById(chartId).getContext('2d');
            const data = {
                datasets: [{
                    data: [value, max - value],
                    backgroundColor: [color, '#f5f5f5'],
                    borderWidth: 0,
                    circumference: 180,
                    rotation: -90,
                    cutout: '80%'
                }]
            };
            
            return new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false },
                        title: {
                            display: true,
                            text: label,
                            position: 'bottom',
                            font: { size: 14 }
                        }
                    },
                    animation: { animateScale: true }
                }
            });
        }

        function createCharts() {
            // Gauges
            createGauge('staffGauge', 248, 300, 'Total Staff', '#006633');
            createGauge('phdGauge', 73, 100, 'PhD Holders', '#003366');
            createGauge('mastersGauge', 156, 200, "Master's Holders", '#FFCC00');
            
            // Qualification Chart (Doughnut)
            new Chart(document.getElementById('qualificationChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['PhD Holders', "Master's Degree", "Bachelor's Degree", 'Other'],
                    datasets: [{
                        data: [73, 156, 15, 4],
                        backgroundColor: ['#006633', '#003366', '#FFCC00', '#CCCCCC'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Staff Qualifications',
                            font: { size: 16 }
                        },
                        legend: { position: 'right' }
                    }
                }
            });
            
            // Degree Chart (Bar)
            new Chart(document.getElementById('degreeChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['First Class', 'Second Upper', 'Second Lower', 'Third Class', 'Pass'],
                    datasets: [{
                        label: 'Number of Staff',
                        data: [42, 98, 69, 12, 5],
                        backgroundColor: '#006633',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: "Bachelor's Degree Classification",
                            font: { size: 16 }
                        },
                        legend: { display: false }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Publications Chart (Line)
            new Chart(document.getElementById('publicationsChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['2018', '2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Journal Articles',
                            data: [45, 52, 58, 67, 72, 65],
                            borderColor: '#006633',
                            backgroundColor: 'rgba(0, 102, 51, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Conference Papers',
                            data: [28, 31, 35, 42, 38, 45],
                            borderColor: '#003366',
                            backgroundColor: 'rgba(0, 51, 102, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Book Chapters',
                            data: [12, 15, 18, 22, 25, 28],
                            borderColor: '#FFCC00',
                            backgroundColor: 'rgba(255, 204, 0, 0.1)',
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Research Publications (2018-2023)',
                            font: { size: 16 }
                        }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Grants Chart (Radar)
            new Chart(document.getElementById('grantsChart').getContext('2d'), {
                type: 'radar',
                data: {
                    labels: ['Health Sciences', 'Engineering', 'Agriculture', 'Education', 'Business', 'Computing'],
                    datasets: [{
                        label: 'Grants Won',
                        data: [8, 5, 4, 3, 2, 2],
                        backgroundColor: 'rgba(0, 102, 51, 0.2)',
                        borderColor: '#006633',
                        pointBackgroundColor: '#006633',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#006633'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Grants by Department',
                            font: { size: 16 }
                        }
                    },
                    scales: {
                        r: { angleLines: { display: true }, suggestedMin: 0 }
                    }
                }
            });
            
            // Supervision Chart (Pie)
            new Chart(document.getElementById('supervisionChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Masters Completed', 'Masters Ongoing', 'PhDs Completed', 'PhDs Ongoing'],
                    datasets: [{
                        data: [42, 20, 15, 12],
                        backgroundColor: ['#006633', '#003366', '#FFCC00', '#669966'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Postgraduate Student Supervision',
                            font: { size: 16 }
                        }
                    }
                }
            });
            
            // Service Hours Chart (Bar)
            new Chart(document.getElementById('serviceHoursChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Medicine', 'Engineering', 'Agriculture', 'Education', 'Business', 'Computing'],
                    datasets: [{
                        label: 'Service Hours',
                        data: [320, 280, 210, 180, 150, 100],
                        backgroundColor: '#006633'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Community Service Hours by Department',
                            font: { size: 16 }
                        },
                        legend: { display: false }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Service Types Chart (Horizontal Bar)
            new Chart(document.getElementById('serviceTypesChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Health Camps', 'School Outreach', 'Skills Training', 'Consultations', 'Public Lectures', 'Other'],
                    datasets: [{
                        label: 'Number of Activities',
                        data: [32, 28, 24, 18, 15, 11],
                        backgroundColor: '#FFCC00'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Types of Service Activities',
                            font: { size: 16 }
                        },
                        legend: { display: false }
                    },
                    scales: { x: { beginAtZero: true } }
                }
            });
        }
    </script>
</body>
</html>