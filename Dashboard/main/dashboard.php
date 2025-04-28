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
    <link rel="icon" type="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <title>MUST Employee Performance Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --must-primary: #003366;
            --must-secondary: #FF9900;
            --must-accent: #6699CC;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .dashboard-header {
            background-color: var(--must-primary);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 5px solid var(--must-secondary);
        }
        
        .section-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border-top: 4px solid var(--must-secondary);
        }
        
        .section-title {
            color: var(--must-primary);
            border-bottom: 2px solid var(--must-accent);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .metric-card {
            border-left: 4px solid var(--must-accent);
            transition: transform 0.3s;
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
        }
        
        .metric-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--must-primary);
        }
        
        .metric-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }
        
        .progress {
            height: 25px;
            border-radius: 5px;
        }
        
        .progress-bar {
            background-color: var(--must-primary);
        }
        
        .badge-must {
            background-color: var(--must-secondary);
            color: #000;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--must-primary);
        }
        
        .nav-pills .nav-link {
            color: var(--must-primary);
        }
    </style>
</head>
<body>
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-university me-2"></i>Mbarara University of Science and Technology</h1>
                    <p class="mb-0">Employee Performance Tracking System</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-0">Last updated: <span id="current-date"></span></p>
                    <button class="btn btn-sm btn-outline-light mt-2"><i class="fas fa-download me-1"></i>Export Report</button>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-value">247</div>
                        <div class="metric-label">Total Employees</div>
                        <small class="text-muted">5% increase from last year</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-value">83</div>
                        <div class="metric-label">PhD Holders</div>
                        <small class="text-muted">34% of workforce</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-value">156</div>
                        <div class="metric-label">Research Publications</div>
                        <small class="text-muted">This year</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card metric-card h-100">
                    <div class="card-body text-center">
                        <div class="metric-value">42</div>
                        <div class="metric-label">Active Grants</div>
                        <small class="text-muted">UGX 3.2B total value</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Performance Section -->
        <div class="card section-card">
            <div class="card-body">
                <h2 class="section-title"><i class="fas fa-graduation-cap me-2"></i>Academic Performance</h2>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Academic Qualifications Distribution</h5>
                        <div class="chart-container">
                            <canvas id="qualificationsChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Degree Classifications</h5>
                        <div class="mb-3">
                            <label>First Class Degrees</label>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 25%">25%</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Second Class Upper</label>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 45%">45%</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Second Class Lower</label>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 20%">20%</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Other Classifications</label>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 10%">10%</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="metric-value">83</div>
                                        <div class="metric-label">PhD Holders</div>
                                    </div>
                                    <i class="fas fa-user-graduate fa-3x text-muted"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">+12%</span> <small>from last year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="metric-value">112</div>
                                        <div class="metric-label">Master's Degrees</div>
                                    </div>
                                    <i class="fas fa-user-tie fa-3x text-muted"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">+8%</span> <small>from last year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="metric-value">52</div>
                                        <div class="metric-label">First Class Degrees</div>
                                    </div>
                                    <i class="fas fa-award fa-3x text-muted"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">+15%</span> <small>from last year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Research and Publications Section -->
        <div class="card section-card">
            <div class="card-body">
                <h2 class="section-title"><i class="fas fa-flask me-2"></i>Research and Innovations</h2>
                
                <ul class="nav nav-pills mb-4" id="researchTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="publications-tab" data-bs-toggle="pill" data-bs-target="#publications" type="button" role="tab">Publications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="grants-tab" data-bs-toggle="pill" data-bs-target="#grants" type="button" role="tab">Grants</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="supervision-tab" data-bs-toggle="pill" data-bs-target="#supervision" type="button" role="tab">Supervision</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="innovations-tab" data-bs-toggle="pill" data-bs-target="#innovations" type="button" role="tab">Innovations</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="researchTabsContent">
                    <div class="tab-pane fade show active" id="publications" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Publication Types (Current Year)</h5>
                                <div class="chart-container">
                                    <canvas id="publicationsChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Publication Trends</h5>
                                <div class="chart-container">
                                    <canvas id="publicationsTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">78</div>
                                        <div class="metric-label">Journal Articles</div>
                                        <small class="text-muted">32 first authors</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">24</div>
                                        <div class="metric-label">Conference Papers</div>
                                        <small class="text-muted">12 first authors</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">15</div>
                                        <div class="metric-label">Book Chapters</div>
                                        <small class="text-muted">5 first authors</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">8</div>
                                        <div class="metric-label">Books with ISBN</div>
                                        <small class="text-muted">3 single authors</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="grants" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Grant Awards by Faculty</h5>
                                <div class="chart-container">
                                    <canvas id="grantsChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Grant Value Distribution</h5>
                                <div class="chart-container">
                                    <canvas id="grantValueChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="metric-value">42</div>
                                        <div class="metric-label">Active Grants</div>
                                        <small>Total value: UGX 3.2B</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="metric-value">18</div>
                                        <div class="metric-label">International Grants</div>
                                        <small>UGX 2.1B (66% of total)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="metric-value">24</div>
                                        <div class="metric-label">National Grants</div>
                                        <small>UGX 1.1B (34% of total)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="supervision" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Postgraduate Supervision</h5>
                                <div class="chart-container">
                                    <canvas id="supervisionChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Completion Rates</h5>
                                <div class="chart-container">
                                    <canvas id="completionChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="metric-value">56</div>
                                        <div class="metric-label">Active PhD Supervisions</div>
                                        <small>Average: 2.3 per faculty</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="metric-value">124</div>
                                        <div class="metric-label">Active Masters Supervisions</div>
                                        <small>Average: 5.1 per faculty</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="metric-value">82%</div>
                                        <div class="metric-label">Completion Rate</div>
                                        <small>5% above national average</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="innovations" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Innovation Types</h5>
                                <div class="chart-container">
                                    <canvas id="innovationsChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Innovation Commercialization</h5>
                                <div class="chart-container">
                                    <canvas id="commercializationChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">7</div>
                                        <div class="metric-label">Patents</div>
                                        <small>3 pending approval</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">12</div>
                                        <div class="metric-label">Copyrights</div>
                                        <small>5 software, 7 literary</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">9</div>
                                        <div class="metric-label">Trademarks</div>
                                        <small>4 registered</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card metric-card h-100">
                                    <div class="card-body text-center">
                                        <div class="metric-value">15</div>
                                        <div class="metric-label">Products</div>
                                        <small>3 commercialized</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Community Service Section -->
        <div class="card section-card">
            <div class="card-body">
                <h2 class="section-title"><i class="fas fa-hands-helping me-2"></i>Community Service</h2>
                
                <div class="row">
                    <div class="col-md-6">
                        <h5>Community Service Participation</h5>
                        <div class="chart-container">
                            <canvas id="communityServiceChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Service Types</h5>
                        <div class="chart-container">
                            <canvas id="serviceTypesChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="metric-value">187</div>
                                        <div class="metric-label">Employees Engaged</div>
                                    </div>
                                    <i class="fas fa-users fa-3x text-muted"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">+22%</span> <small>from last year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="metric-value">64</div>
                                        <div class="metric-label">Community Projects</div>
                                    </div>
                                    <i class="fas fa-project-diagram fa-3x text-muted"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">+18%</span> <small>from last year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card metric-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="metric-value">2,450</div>
                                        <div class="metric-label">Hours Contributed</div>
                                    </div>
                                    <i class="fas fa-clock fa-3x text-muted"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">+30%</span> <small>from last year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Top Community Service Initiatives</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Initiative</th>
                                    <th>Lead Department</th>
                                    <th>Participants</th>
                                    <th>Impact Level</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Health Camp - Western Region</td>
                                    <td>Medicine</td>
                                    <td>28</td>
                                    <td><span class="badge bg-success">High</span></td>
                                    <td>3 months</td>
                                </tr>
                                <tr>
                                    <td>STEM Education Outreach</td>
                                    <td>Science & Education</td>
                                    <td>35</td>
                                    <td><span class="badge bg-success">High</span></td>
                                    <td>Ongoing</td>
                                </tr>
                                <tr>
                                    <td>Agricultural Training</td>
                                    <td>Agriculture</td>
                                    <td>22</td>
                                    <td><span class="badge bg-warning">Medium</span></td>
                                    <td>6 weeks</td>
                                </tr>
                                <tr>
                                    <td>ICT Literacy Program</td>
                                    <td>Computing</td>
                                    <td>18</td>
                                    <td><span class="badge bg-warning">Medium</span></td>
                                    <td>2 months</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">© 2023 Mbarara University of Science and Technology - Human Resource Management System</p>
            <small class="text-muted">Data updated hourly | For official use only</small>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script>
        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            weekday: 'long'
        });
        
        // Academic Qualifications Chart
        const qualificationsCtx = document.getElementById('qualificationsChart').getContext('2d');
        const qualificationsChart = new Chart(qualificationsCtx, {
            type: 'doughnut',
            data: {
                labels: ['PhD', 'Master\'s', 'Bachelor\'s', 'Diploma', 'Other'],
                datasets: [{
                    data: [83, 112, 45, 5, 2],
                    backgroundColor: [
                        '#003366',
                        '#6699CC',
                        '#FF9900',
                        '#CCCCCC',
                        '#999999'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Publications Chart
        const publicationsCtx = document.getElementById('publicationsChart').getContext('2d');
        const publicationsChart = new Chart(publicationsCtx, {
            type: 'bar',
            data: {
                labels: ['Journal Articles', 'Conference Papers', 'Book Chapters', 'Books'],
                datasets: [{
                    label: 'First Author',
                    data: [32, 12, 5, 3],
                    backgroundColor: '#003366',
                }, {
                    label: 'Co-Author',
                    data: [46, 12, 10, 5],
                    backgroundColor: '#6699CC',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Publications Trend Chart
        const trendCtx = document.getElementById('publicationsTrendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['2018', '2019', '2020', '2021', '2022', '2023'],
                datasets: [{
                    label: 'Total Publications',
                    data: [98, 112, 105, 127, 142, 156],
                    borderColor: '#003366',
                    backgroundColor: 'rgba(0, 51, 102, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Grants Chart
        const grantsCtx = document.getElementById('grantsChart').getContext('2d');
        const grantsChart = new Chart(grantsCtx, {
            type: 'polarArea',
            data: {
                labels: ['Medicine', 'Science', 'Engineering', 'Agriculture', 'Business', 'Education'],
                datasets: [{
                    data: [18, 12, 8, 6, 5, 3],
                    backgroundColor: [
                        '#003366',
                        '#6699CC',
                        '#FF9900',
                        '#CCCCCC',
                        '#999999',
                        '#666666'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
        
        // Grant Value Chart
        const grantValueCtx = document.getElementById('grantValueChart').getContext('2d');
        const grantValueChart = new Chart(grantValueCtx, {
            type: 'pie',
            data: {
                labels: ['International', 'National', 'Local'],
                datasets: [{
                    data: [2100, 1100, 200],
                    backgroundColor: [
                        '#003366',
                        '#6699CC',
                        '#FF9900'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: UGX ${value}M (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Supervision Chart
        const supervisionCtx = document.getElementById('supervisionChart').getContext('2d');
        const supervisionChart = new Chart(supervisionCtx, {
            type: 'bar',
            data: {
                labels: ['Medicine', 'Science', 'Engineering', 'Agriculture', 'Business', 'Education'],
                datasets: [{
                    label: 'PhD',
                    data: [18, 12, 8, 6, 5, 7],
                    backgroundColor: '#003366',
                }, {
                    label: 'Masters',
                    data: [35, 28, 22, 15, 12, 12],
                    backgroundColor: '#6699CC',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: false,
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Completion Chart
        const completionCtx = document.getElementById('completionChart').getContext('2d');
        const completionChart = new Chart(completionCtx, {
            type: 'radar',
            data: {
                labels: ['Timeliness', 'Examination', 'Publication', 'Funding', 'Resources', 'Support'],
                datasets: [{
                    label: 'PhD Completion',
                    data: [75, 82, 68, 70, 78, 85],
                    backgroundColor: 'rgba(0, 51, 102, 0.2)',
                    borderColor: '#003366',
                    pointBackgroundColor: '#003366',
                    pointBorderColor: '#fff',
                }, {
                    label: 'Masters Completion',
                    data: [85, 88, 75, 80, 82, 90],
                    backgroundColor: 'rgba(102, 153, 204, 0.2)',
                    borderColor: '#6699CC',
                    pointBackgroundColor: '#6699CC',
                    pointBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 50,
                        suggestedMax: 100
                    }
                }
            }
        });
        
        // Innovations Chart
        const innovationsCtx = document.getElementById('innovationsChart').getContext('2d');
        const innovationsChart = new Chart(innovationsCtx, {
            type: 'pie',
            data: {
                labels: ['Patents', 'Copyrights', 'Trademarks', 'Products'],
                datasets: [{
                    data: [7, 12, 9, 15],
                    backgroundColor: [
                        '#003366',
                        '#6699CC',
                        '#FF9900',
                        '#CCCCCC'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
        
        // Commercialization Chart
        const commercializationCtx = document.getElementById('commercializationChart').getContext('2d');
        const commercializationChart = new Chart(commercializationCtx, {
            type: 'bar',
            data: {
                labels: ['Patents', 'Copyrights', 'Trademarks', 'Products'],
                datasets: [{
                    label: 'Commercialized',
                    data: [2, 4, 4, 3],
                    backgroundColor: '#003366',
                }, {
                    label: 'Not Commercialized',
                    data: [5, 8, 5, 12],
                    backgroundColor: '#CCCCCC',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Community Service Chart
        const communityCtx = document.getElementById('communityServiceChart').getContext('2d');
        const communityChart = new Chart(communityCtx, {
            type: 'bar',
            data: {
                labels: ['Medicine', 'Science', 'Engineering', 'Agriculture', 'Business', 'Education'],
                datasets: [{
                    label: 'Participants',
                    data: [45, 38, 32, 28, 22, 22],
                    backgroundColor: '#003366',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Service Types Chart
        const serviceTypesCtx = document.getElementById('serviceTypesChart').getContext('2d');
        const serviceTypesChart = new Chart(serviceTypesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Health', 'Education', 'Agriculture', 'Technology', 'Other'],
                datasets: [{
                    data: [45, 35, 25, 15, 10],
                    backgroundColor: [
                        '#003366',
                        '#6699CC',
                        '#FF9900',
                        '#CCCCCC',
                        '#999999'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    </script>
</body>
</html>