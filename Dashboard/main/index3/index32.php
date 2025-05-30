<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Employee Tracking - Department Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--primary-color);
        }
        
        .dashboard-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
        }
        
        .stat-card .stat-label {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .chart-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .leaderboard {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .leaderboard-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .leaderboard-item:last-child {
            border-bottom: none;
        }
        
        .leaderboard-rank {
            font-weight: bold;
            color: var(--secondary-color);
            width: 30px;
        }
        
        .leaderboard-name {
            flex-grow: 1;
        }
        
        .leaderboard-score {
            font-weight: bold;
        }
        
        .badge-custom {
            background-color: var(--secondary-color);
        }
        
        .filter-section {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .tab-content {
            padding: 20px 0;
        }
        
        .nav-tabs .nav-link.active {
            font-weight: bold;
            color: var(--secondary-color);
            border-bottom: 2px solid var(--secondary-color);
        }
        
        .compare-card {
            border-left: 4px solid var(--secondary-color);
        }
        
        .progress {
            height: 10px;
            margin-bottom: 5px;
        }
        
        .progress-bar {
            background-color: var(--secondary-color);
        }
        
        
    </style>
    
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-university me-2"></i> Department of Computer Science</h1>
                    <p class="mb-0">Employee Performance Dashboard</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="timePeriodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Academic Year 2023-2024
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="timePeriodDropdown">
                            <li><a class="dropdown-item" href="#">2023-2024</a></li>
                            <li><a class="dropdown-item" href="#">2022-2023</a></li>
                            <li><a class="dropdown-item" href="#">2021-2022</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats Section -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">25</div>
                    <div class="stat-label">Total Staff</div>
                </div>
            </div>            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">45</div>
                    <div class="stat-label">Total Publications</div>
                    <div class="mt-2">
                        <span class="badge bg-success me-1">Books with ISBN : 14</span><br>
                        <span class="badge bg-success">Book chapters : 7</span><br>
                        <span class="badge bg-success me-1">Journal Articles : 50</span><br>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">UGX 800M</div>
                    <div class="stat-label">Research Grants</div>
                    <div class="mt-2">
                        <!-- <span class="badge bg-info me-1">2 Large Grants</span> -->
                        <!-- <span class="badge bg-info">4 more than 1 Billion</span> -->
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">72</div>
                    <div class="stat-label">Total innovations</div>
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="rankFilter" class="form-label">Academic Rank</label>
                    <select id="rankFilter" class="form-select">
                        <option selected>All Ranks</option>
                        <option>Professor</option>
                        <option>Associate Professor</option>
                        <option>Senior Lecturer</option>
                        <option>Lecturer</option>
                        <option>Assistant Lecturer</option>
                        <option>Teaching Assistant</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="categoryFilter" class="form-label">Category</label>
                    <select id="categoryFilter" class="form-select">
                        <option selected>All Categories</option>
                        <option>Clinical</option>
                        <option>Non-Clinical</option>
                    </select>
                </div>
                <!-- <div class="col-md-3">
                    <label for="scoreFilter" class="form-label">Score Range</label>
                    <select id="scoreFilter" class="form-select">
                        <option selected>All Scores</option>
                        <option>Top 25%</option>
                        <option>Middle 50%</option>
                        <option>Bottom 25%</option>
                    </select>
                </div> -->
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </div>
        </div>
        
        <!-- Main Content Tabs -->
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="publications-tab" data-bs-toggle="tab" data-bs-target="#publications" type="button" role="tab" aria-controls="publications" aria-selected="false">Publications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="research-tab" data-bs-toggle="tab" data-bs-target="#research" type="button" role="tab" aria-controls="research" aria-selected="false">Research & Innovations</button>
            </li>
            <li class="nav-item" role="presentation">
                <!-- <button class="nav-link" id="teaching-tab" data-bs-toggle="tab" data-bs-target="#teaching" type="button" role="tab" aria-controls="teaching" aria-selected="false">Teaching</button> -->
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="academicperformance-tab" data-bs-toggle="tab" data-bs-target="#academicperformance" type="button" role="tab" aria-controls="academicperformance" aria-selected="false">Academic Performance</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="communityservice-tab" data-bs-toggle="tab" data-bs-target="#communityservice" type="button" role="tab" aria-controls="communityservice" aria-selected="false">Community service</button>
            </li>
        </ul>
        
        <div class="tab-content" id="dashboardTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row">
                    <!-- Performance Distribution -->
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Performance Distribution</h3>
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Trends Over Time -->
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Trends Over Time</h3>
                            <canvas id="trendsChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Publication Impact -->
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Publication Impact</h3>
                            <canvas id="publicationImpactChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Grant Funding -->
                    <div class="col-lg-6">
                        <div class="chart-container" style="position: relative; width: 700px; height: 380px; ">
                            <h3 class="chart-title">Grant Funding Distribution</h3>
                            <canvas id="grantsChart" height="100px" ></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Publications Tab -->
            <div class="tab-pane fade" id="publications" role="tabpanel" aria-labelledby="publications-tab">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary shadow">
                            <div class="card-body">
                                <h5 class="card-title">First Author Peer reviewed Publications</h5><br>
                                <p class="card-text" id="firstAuthorCount">20</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success shadow">
                            <div class="card-body">
                                <h5 class="card-title">Co-Authored Publications in Peer reviewed Publications</h5>
                                <p class="card-text" id="coAuthorCount">20</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning shadow">
                            <div class="card-body">
                                <h5 class="card-title">Total Number of Peer-Reviewed Publications</h5><br>
                                <p class="card-text" id="peerReviewedCount">20</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger shadow">
                            <div class="card-body">
                                <h5 class="card-title">Total Number of Citations</h5><br><br>
                                <p class="card-text" id="totalCitations">20</p>
                                
                            </div>
                        </div>
                    </div>
                </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-lg-6" style="display: flex; flex-direction: column; height: 100%;">
            <div class="chart-container" style="flex: 1; display: flex; flex-direction: column; height: 100%;">
                <h3 class="chart-title">Publication Types</h3>
                <div style="flex: 1; min-height: 0; width: 100%;"> <!-- Container for canvas -->
                    <canvas id="publicationTypesChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-container">
                <h3 class="chart-title">Citations vs Publications</h3>
                <canvas id="citationsChart"></canvas>
            </div>
        </div>
    </div>
</div>

            
            <!-- Research Tab -->
            <div class="tab-pane fade" id="research" role="tabpanel" aria-labelledby="research-tab">
                <div class="row">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h3 class="chart-title">Research Grants</h3>
                        <canvas id="researchGrantsChart"></canvas>
                    </div>
                </div>
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Supervision Completions</h3>
                            <canvas id="supervisionChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container" height="100px">
                        <h3 class="chart-title">Innovation Types Distribution</h3>
                        <div style="height: 300px;"> <!-- Adjust height here (e.g., 250px, 300px) -->
                            <canvas id="innovationTypesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Teaching Tab -->
            <div class="tab-pane fade" id="teaching" role="tabpanel" aria-labelledby="teaching-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Teaching Experience</h3>
                            <canvas id="teachingExperienceChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Thesis Examinations</h3>
                            <canvas id="examinationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Performance tab -->
        <div class="tab-pane fade" id="academicperformance" role="tabpanel" aria-labelledby="academicperformance-tab">
        <!-- Quick Stats Section -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">25</div>
                    <div class="stat-label">Phd's</div>
                </div>
            </div>            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">45</div>
                    <div class="stat-label">Masters</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">34</div>
                    <div class="stat-label">First class</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">72</div>
                    <div class="stat-label">Second Class</div>
                </div>
            </div>
        </div>

        <!-- <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Teaching Experience</h3>
                            <canvas id="teachingExperienceChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Thesis Examinations</h3>
                            <canvas id="examinationsChart"></canvas>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Community service tab -->
        <div class="tab-pane fade" id="communityservice" role="tabpanel" aria-labelledby="communityservice-tab">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value">25</div>
                        <div class="stat-label">Industrial Placement Supervision</div>
                    </div>
                </div>            
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value">45</div>
                        <div class="stat-label">Community Outreach Programs</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-value">34</div>
                        <div class="stat-label"> Clinical Practices</div>
                    </div>
                </div>
                
            </div>
        
        <!-- <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Teaching Experience</h3>
                            <canvas id="teachingExperienceChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Thesis Examinations</h3>
                            <canvas id="examinationsChart"></canvas>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        
        <!-- Leaderboard and Comparison Section -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="leaderboard">
                    <h3 class="chart-title">Top Performers</h3>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">1</div>
                        <div class="leaderboard-name">Dr. Jane Smith</div>
                        <div class="leaderboard-score">92 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">2</div>
                        <div class="leaderboard-name">Prof. John Doe</div>
                        <div class="leaderboard-score">88 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">3</div>
                        <div class="leaderboard-name">Dr. Alice Johnson</div>
                        <div class="leaderboard-score">85 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">4</div>
                        <div class="leaderboard-name">Dr. Robert Brown</div>
                        <div class="leaderboard-score">80 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">5</div>
                        <div class="leaderboard-name">Dr. Emily Wilson</div>
                        <div class="leaderboard-score">78 pts</div>
                    </div>
                    <div class="text-end mt-2">
                        <a href="#" class="text-primary">View Full Ranking →</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="compare-card chart-container">
                    <h3 class="chart-title">Department Comparison</h3>
                    <div class="mb-3">
                        <label class="form-label">Compare with:</label>
                        <select class="form-select">
                            <option>Faculty Average</option>
                            <option>University Average</option>
                            <option>Department of Mathematics</option>
                            <option>Department of Engineering</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Publications</span>
                            <span>45 vs 38</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Research Grants</span>
                            <span>UGX 800M vs UGX 650M</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Teaching Score</span>
                            <span>7.2 vs 6.8</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Performance Distribution Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(performanceCtx, {
            type: 'bar',
            data: {
                labels: ['Prof. A', 'Prof. B', 'Dr. C', 'Dr. D', 'Dr. E', 'Dr. F', 'Lect. G'],
                datasets: [
                    {
                        label: 'years of experience',
                        data: [12, 8, 6, 5, 4, 3, 2],
                        backgroundColor: '#3498db',
                    },
                    {
                        label: 'publications',
                        data: [10, 9, 8, 7, 6, 5, 4],
                        backgroundColor: '#2ecc71',
                    },
                    {
                        label: 'grants won',
                        data: [8, 6, 4, 3, 2, 1, 0],
                        backgroundColor: '#e74c3c',
                    }
                ]
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

        // Trends Over Time Chart
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        const trendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: ['2019', '2020', '2021', '2022', '2023'],
                datasets: [
                    {
                        label: 'Publications',
                        data: [25, 50, 70, 100, 200],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Research Grants (M UGX)',
                        data: [300, 350, 450, 600, 800],
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'innovations',
                        data: [62, 80, 100, 190, 250],
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        // Publication Impact Chart
        const impactCtx = document.getElementById('publicationImpactChart').getContext('2d');
        const impactChart = new Chart(impactCtx, {
            type: 'scatter',
            data: {
                datasets: [
                    {
                        label: 'Faculty Members',
                        data: [
                            {x: 12, y: 120},
                            {x: 8, y: 85},
                            {x: 6, y: 45},
                            {x: 5, y: 30},
                            {x: 4, y: 25},
                            {x: 3, y: 15},
                            {x: 2, y: 8}
                        ],
                        backgroundColor: '#3498db',
                        pointRadius: 8,
                        pointHoverRadius: 10
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Number of Publications'
                        },
                        beginAtZero: true
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Citations'
                        },
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Publications: ${context.parsed.x}, Citations: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            }
        });

        // Grants Chart
        const grantsCtx = document.getElementById('grantsChart').getContext('2d');
        const grantsChart = new Chart(grantsCtx, {
            type: 'pie',
            data: {
                labels: ['>1B UGX', '500M-1B UGX', '100M-500M UGX', '<100M UGX'],
                datasets: [{
                    data: [2, 3, 5, 10],
                    backgroundColor: [
                        '#2ecc71',
                        '#3498db',
                        '#f39c12',
                        '#e74c3c'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Disables aspect ratio enforcement
                plugins: {
                    legend: {
                        position: 'right',
                        
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} projects`;
                            }
                        }
                    }
                }
            }
        });

        // Publication Types Chart
        const pubTypesCtx = document.getElementById('publicationTypesChart').getContext('2d');
        const pubTypesChart = new Chart(pubTypesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Journal Articles', 'Book Chapters', 'Books with isbn'],
                datasets: [{
                    data: [30, 48, 62],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#e74c3c',
                        
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Disables aspect ratio enforcement
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });

        // Citations Chart
        const citationsCtx = document.getElementById('citationsChart').getContext('2d');
        const citationsChart = new Chart(citationsCtx, {
            type: 'bar',
            data: {
                labels: ['2021', '2022', '2023', '2024', '2025'],
                datasets: [
                    {
                        label: 'Publications',
                        data: [12, 8, 6, 5, 4],
                        backgroundColor: '#3498db',
                    },
                    {
                        label: 'Citations',
                        data: [120, 85, 45, 30, 25],
                        backgroundColor: '#2ecc71',
                    }
                ]
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

        //research grants by amount
        const researchGrantsCtx = document.getElementById('researchGrantsChart').getContext('2d');
        const researchGrantsChart = new Chart(researchGrantsCtx, {
            type: 'bar',
            data: {
                labels: ['Grants >1B', 'Grants 500M-1B', 'Grants 100M-500M', 'Grants <100M', 'Collaborations'],
                datasets: [
                    
                    {
                        label: 'Grant Amount',
                        data: [1, 2, 4, 8, 6],
                        backgroundColor: 'rgba(46, 204, 113, 0.7)', // Slightly more opaque for bars
                        borderColor: '#2ecc71',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Makes bars horizontal
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Grants'
                        }
                    },
                    y: {
                        grid: {
                            display: false // Cleaner layout
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top', // Adjust as needed
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });

        // Supervision Chart
        const supervisionCtx = document.getElementById('supervisionChart').getContext('2d');
        const supervisionChart = new Chart(supervisionCtx, {
            type: 'bar',
            data: {
                labels: ['PhD Completions', 'Masters Completions', 'Ongoing PhD', 'Ongoing Masters'],
                datasets: [{
                    label: 'Supervision',
                    data: [5, 15, 8, 20],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#f39c12',
                        '#e74c3c'
                    ],
                    borderWidth: 1
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
        // Innovation Types Pie Chart
        const innovationCtx = document.getElementById('innovationTypesChart').getContext('2d');
        const innovationChart = new Chart(innovationCtx, {
            type: 'pie',
            data: {
                labels: ['Patent', 'Product', 'Copyright', 'Utility Model', 'Trademark'],
                datasets: [{
                    data: [35, 25, 20, 10, 10],
                    backgroundColor: [
                        '#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    // Configure datalabels
                    datalabels: {
                        color: '#fff', // White text for visibility
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${percentage}%`; // Show percentage inside slices
                            // Or return `${value}`; // To show raw values instead
                        }
                    }
                }
            },
            plugins: [ChartDataLabels] // Activate the plugin
        });

        // Teaching Experience Chart
        const teachingExpCtx = document.getElementById('teachingExperienceChart').getContext('2d');
        const teachingExpChart = new Chart(teachingExpCtx, {
            type: 'horizontalBar',
            data: {
                labels: ['Prof. A', 'Prof. B', 'Dr. C', 'Dr. D', 'Dr. E', 'Lect. F', 'Lect. G'],
                datasets: [{
                    label: 'Years of Experience',
                    data: [15, 12, 8, 7, 5, 3, 2],
                    backgroundColor: '#3498db',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Examinations Chart
        const examsCtx = document.getElementById('examinationsChart').getContext('2d');
        const examsChart = new Chart(examsCtx, {
            type: 'polarArea',
            data: {
                labels: ['External Examiner', 'Internal Examiner', 'Conference Presentations', 'Journal Editor'],
                datasets: [{
                    label: 'Academic Activities',
                    data: [15, 25, 10, 5],
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(46, 204, 113, 0.7)',
                        'rgba(243, 156, 18, 0.7)',
                        'rgba(231, 76, 60, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        suggestedMin: 0
                    }
                }
            }
        });
    </script>
</body>
</html>