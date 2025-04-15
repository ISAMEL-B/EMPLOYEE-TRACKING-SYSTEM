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
        
        .community-service-card {
            border-left: 4px solid #27ae60;
        }
        
        .academic-performance-card {
            border-left: 4px solid #e67e22;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
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
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">25</div>
                    <div class="stat-label">Total Staff</div>
                    <div class="mt-2">
                        <span class="badge bg-primary me-1">8 Professors</span>
                        <span class="badge bg-primary me-1">10 Lecturers</span>
                        <span class="badge bg-primary">7 TAs</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">72</div>
                    <div class="stat-label">Average Score</div>
                    <div class="progress mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">+5% from last year</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">45</div>
                    <div class="stat-label">Total Publications</div>
                    <div class="mt-2">
                        <span class="badge bg-success me-1">12 First Author</span>
                        <span class="badge bg-success">8 Corresponding</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">UGX 800M</div>
                    <div class="stat-label">Research Grants</div>
                    <div class="mt-2">
                        <span class="badge bg-info me-1">2 Large Grants</span>
                        <span class="badge bg-info">4 Medium Grants</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="filter-section mb-4">
            <div class="row">
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label for="categoryFilter" class="form-label">Category</label>
                    <select id="categoryFilter" class="form-select">
                        <option selected>All Categories</option>
                        <option>Clinical</option>
                        <option>Non-Clinical</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="scoreFilter" class="form-label">Score Range</label>
                    <select id="scoreFilter" class="form-select">
                        <option selected>All Scores</option>
                        <option>Top 25%</option>
                        <option>Middle 50%</option>
                        <option>Bottom 25%</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </div>
        </div>
        
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="publications-tab" data-bs-toggle="tab" data-bs-target="#publications" type="button" role="tab" aria-controls="publications" aria-selected="false">Publications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="research-tab" data-bs-toggle="tab" data-bs-target="#research" type="button" role="tab" aria-controls="research" aria-selected="false">Research</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="teaching-tab" data-bs-toggle="tab" data-bs-target="#teaching" type="button" role="tab" aria-controls="teaching" aria-selected="false">Teaching</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab" aria-controls="academic" aria-selected="false">Academic & Community</button>
            </li>
        </ul>
        
        <div class="tab-content" id="dashboardTabsContent">
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Performance Distribution</h3>
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Trends Over Time</h3>
                            <canvas id="trendsChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Publication Impact</h3>
                            <canvas id="publicationImpactChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Grant Funding Distribution</h3>
                            <canvas id="grantsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="publications" role="tabpanel" aria-labelledby="publications-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <h3 class="chart-title">Publication Types</h3>
                            <canvas id="publicationTypesChart"></canvas>
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
            </div>
            
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
            
            <div class="tab-pane fade" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container academic-performance-card">
                            <h3 class="chart-title">Academic Performance Distribution</h3>
                            <canvas id="academicPerformanceChart"></canvas>
                        </div>
                        
                        <div class="chart-container academic-performance-card mt-4">
                            <h3 class="chart-title">Professional Memberships</h3>
                            <canvas id="membershipsChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="chart-container community-service-card">
                            <h3 class="chart-title">Community Service Activities</h3>
                            <canvas id="communityServiceChart"></canvas>
                        </div>
                        
                        <div class="chart-container community-service-card mt-4">
                            <h3 class="chart-title">University Service Roles</h3>
                            <canvas id="universityServiceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
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
                        label: 'Publications',
                        data: [12, 8, 6, 5, 4, 3, 2],
                        backgroundColor: '#3498db',
                    },
                    {
                        label: 'Grants',
                        data: [8, 6, 4, 3, 2, 1, 0],
                        backgroundColor: '#2ecc71',
                    },
                    {
                        label: 'Teaching',
                        data: [10, 9, 8, 7, 6, 5, 4],
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
                        data: [25, 28, 32, 38, 45],
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
                        label: 'Average Score',
                        data: [62, 65, 68, 70, 72],
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
                labels: ['Journal Articles', 'Book Chapters', 'Published Books', 'Conference Papers'],
                datasets: [{
                    data: [30, 8, 2, 5],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#e74c3c',
                        '#f39c12'
                    ],
                    borderWidth: 1
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

        // Citations Chart
        const citationsCtx = document.getElementById('citationsChart').getContext('2d');
        const citationsChart = new Chart(citationsCtx, {
            type: 'bar',
            data: {
                labels: ['Prof. A', 'Prof. B', 'Dr. C', 'Dr. D', 'Dr. E'],
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

        // Research Grants Chart
        const researchGrantsCtx = document.getElementById('researchGrantsChart').getContext('2d');
        const researchGrantsChart = new Chart(researchGrantsCtx, {
            type: 'radar',
            data: {
                labels: ['Grants >1B', 'Grants 500M-1B', 'Grants 100M-500M', 'Grants <100M', 'Collaborations'],
                datasets: [
                    {
                        label: 'Department Average',
                        data: [2, 3, 5, 10, 8],
                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                        borderColor: '#3498db',
                        pointBackgroundColor: '#3498db',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#3498db'
                    },
                    {
                        label: 'Faculty Average',
                        data: [1, 2, 4, 8, 6],
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        borderColor: '#2ecc71',
                        pointBackgroundColor: '#2ecc71',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#2ecc71'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0
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

        // Academic Performance Chart
        const academicPerfCtx = document.getElementById('academicPerformanceChart').getContext('2d');
        new Chart(academicPerfCtx, {
            type: 'bar',
            data: {
                labels: ['Dean/Director', 'Deputy Dean', 'HoD', 'Committee Members'],
                datasets: [{
                    label: 'Service Points',
                    data: [5, 3, 2, 1],
                    backgroundColor: '#e67e22'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // Professional Memberships Chart
        const membershipsCtx = document.getElementById('membershipsChart').getContext('2d');
        new Chart(membershipsCtx, {
            type: 'doughnut',
            data: {
                labels: ['1 Membership', '2 Memberships', '3+ Memberships'],
                datasets: [{
                    data: [15, 8, 2],
                    backgroundColor: ['#3498db', '#2ecc71', '#e74c3c']
                }]
            }
        });
        
        // Community Service Chart
        const communityCtx = document.getElementById('communityServiceChart').getContext('2d');
        new Chart(communityCtx, {
            type: 'polarArea',
            data: {
                labels: ['Health Camps', 'Education', 'Skill Training', 'Consulting', 'Other'],
                datasets: [{
                    data: [12, 8, 5, 3, 2],
                    backgroundColor: [
                        '#27ae60', '#2ecc71', '#3498db', '#e74c3c', '#f39c12'
                    ]
                }]
            }
        });
        
        // University Service Chart
        const universityCtx = document.getElementById('universityServiceChart').getContext('2d');
        new Chart(universityCtx, {
            type: 'radar',
            data: {
                labels: ['Leadership', 'Committees', 'Examinations', 'Events', 'Mentoring'],
                datasets: [{
                    label: 'Service Participation',
                    data: [8, 12, 15, 10, 7],
                    backgroundColor: 'rgba(39, 174, 96, 0.2)',
                    borderColor: '#27ae60'
                }]
            }
        });
    </script>
</body>
</html>