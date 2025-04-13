<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM - Department Analytics Dashboard</title>
    <!-- Bootstrap CSS -->
    <!-- Chart.js -->
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <script src="../components/Chart.js/dist/Chart.min.js"></script>

    <style>
        :root {
            --must-green: #006633;
            --must-yellow: #FFCC00;
            --must-blue: #003366;
            --must-light-green: #e6f2ec;
            --must-light-yellow: #fff9e6;
            --must-light-blue: #e6ecf2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--must-green);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1rem;
            /* Smaller text */
            padding: 5px 10px;
            /* Less padding */
            white-space: nowrap;
            /* Prevents text wrap */
        }

        .navbar-brand img {
            height: 30px;
            /* Reduce logo height */
            width: auto;
            margin-right: 8px;
            /* Space between image and text */
        }


        .sidebar {
            background-color: var(--must-light-green);
            height: 100vh;
            position: fixed;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar .nav-link {
            color: var(--must-blue);
            font-weight: 500;
            border-radius: 5px;
            margin-bottom: 5px;
            padding: 10px 15px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--must-yellow);
            color: var(--must-blue);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            color: var(--must-green);
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            color: var(--must-blue);
            border-radius: 10px 10px 0 0 !important;
        }

        .department-card {
            border-left: 4px solid var(--must-green);
            transition: all 0.3s ease;
        }

        .department-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--must-green);
        }

        .stat-card .stat-label {
            color: var(--must-blue);
            font-weight: 500;
        }

        .highlight-yellow {
            background-color: var(--must-light-yellow);
            border-left: 4px solid var(--must-yellow);
        }

        .highlight-blue {
            background-color: var(--must-light-blue);
            border-left: 4px solid var(--must-blue);
        }

        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .comparison-table th {
            background-color: var(--must-green);
            color: white;
        }

        .department-title {
            color: var(--must-green);
            font-weight: 700;
            border-bottom: 2px solid var(--must-yellow);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .search-box {
            border: 2px solid var(--must-green);
            border-radius: 5px;
        }

        .btn-must {
            background-color: var(--must-green);
            color: white;
        }

        .btn-must:hover {
            background-color: var(--must-blue);
            color: white;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 0.9rem;
            }

            .navbar-brand img {
                height: 25px;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png" alt="MUST Logo" class="me-2">
                MUST HRM Analytics Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Analytics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar col-md-3 col-lg-2 d-md-block">
        <div class="position-sticky pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-people"></i> Employee Directory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-building"></i> Departments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-graph-up"></i> Performance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-cash-stack"></i> Payroll
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-calendar-event"></i> Leave Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-file-earmark-text"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            </ul>

            <div class="mt-4 p-3 highlight-yellow">
                <h6 class="fw-bold">Quick Actions</h6>
                <button class="btn btn-sm btn-must w-100 mb-2">Add New Employee</button>
                <button class="btn btn-sm btn-outline-must w-100 mb-2">Generate Report</button>
                <button class="btn btn-sm btn-outline-must w-100">Schedule Meeting</button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="department-title">Department Analytics Dashboard</h2>
                <div class="dropdown">
                    <button class="btn btn-must dropdown-toggle" type="button" id="departmentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Department
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="departmentDropdown">
                        <input type="text" class="form-control search-box mb-2" placeholder="Search departments..." id="departmentSearch">
                        <div id="departmentList">
                            <a class="dropdown-item" href="#" data-dept="it">Information Technology</a>
                            <a class="dropdown-item" href="#" data-dept="finance">Finance</a>
                            <a class="dropdown-item" href="#" data-dept="hr">Human Resources</a>
                            <a class="dropdown-item" href="#" data-dept="academics">Academics</a>
                            <a class="dropdown-item" href="#" data-dept="research">Research & Development</a>
                            <a class="dropdown-item" href="#" data-dept="admin">Administration</a>
                            <a class="dropdown-item" href="#" data-dept="facilities">Facilities Management</a>
                            <a class="dropdown-item" href="#" data-dept="marketing">Marketing & Communications</a>
                            <a class="dropdown-item" href="#" data-dept="library">Library Services</a>
                            <a class="dropdown-item" href="#" data-dept="health">Health Services</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Comparison Section -->
            <div class="row mb-4" id="comparisonSection">
                <div class="col-12">
                    <div class="card department-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Department Comparative Overview</span>
                            <small class="text-muted">Last updated: Today, 10:45 AM</small>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Select a department from the dropdown above to view detailed analytics. Below is a comparison of all departments across key metrics.</p>

                            <div class="table-responsive">
                                <table class="table table-hover comparison-table">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Employees</th>
                                            <th>Avg. Tenure</th>
                                            <th>Avg. Rating</th>
                                            <th>Training Hours</th>
                                            <th>Vacancy Rate</th>
                                            <th>Budget Utilization</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Information Technology</strong></td>
                                            <td>42</td>
                                            <td>3.2 years</td>
                                            <td>4.1</td>
                                            <td>35</td>
                                            <td>8%</td>
                                            <td>78%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Finance</strong></td>
                                            <td>28</td>
                                            <td>5.1 years</td>
                                            <td>4.3</td>
                                            <td>28</td>
                                            <td>5%</td>
                                            <td>82%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Human Resources</strong></td>
                                            <td>18</td>
                                            <td>4.7 years</td>
                                            <td>4.5</td>
                                            <td>42</td>
                                            <td>3%</td>
                                            <td>75%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Academics</strong></td>
                                            <td>156</td>
                                            <td>6.3 years</td>
                                            <td>4.2</td>
                                            <td>25</td>
                                            <td>12%</td>
                                            <td>88%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Research & Development</strong></td>
                                            <td>37</td>
                                            <td>4.2 years</td>
                                            <td>4.4</td>
                                            <td>48</td>
                                            <td>15%</td>
                                            <td>92%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="deptSizeChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="deptRatingChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Detail Section (Hidden by default) -->
            <div class="row mb-4 d-none" id="detailSection">
                <div class="col-12">
                    <div class="card department-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span id="deptDetailTitle">Department Details</span>
                            <button class="btn btn-sm btn-outline-secondary" id="backToComparison">
                                <i class="bi bi-arrow-left"></i> Back to Comparison
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-value" id="deptEmployees">--</div>
                                        <div class="stat-label">Total Employees</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card highlight-yellow">
                                        <div class="stat-value" id="deptAvgTenure">--</div>
                                        <div class="stat-label">Avg. Tenure</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card highlight-blue">
                                        <div class="stat-value" id="deptAvgRating">--</div>
                                        <div class="stat-label">Avg. Performance</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-card">
                                        <div class="stat-value" id="deptVacancy">--</div>
                                        <div class="stat-label">Vacancy Rate</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">Employee Distribution</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="deptPositionChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">Training Completion</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="deptTrainingChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header">Gender Diversity</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="deptGenderChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">Budget Allocation vs Utilization</div>
                                        <div class="card-body">
                                            <div class="chart-container">
                                                <canvas id="deptBudgetChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">Recent Department Activity</div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush" id="deptActivity">
                                                <!-- Activity items will be added here -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Insights Section -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card highlight-yellow">
                        <div class="card-header">Key Insights</div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>IT Department:</strong> High training hours with excellent performance ratings. Consider expanding team to reduce workload.
                            </div>
                            <div class="alert alert-warning">
                                <strong>Finance Department:</strong> Lowest vacancy rate indicates good retention. Budget utilization could be improved.
                            </div>
                            <div class="alert alert-success">
                                <strong>HR Department:</strong> Highest performance ratings. Model department for others.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card highlight-blue">
                        <div class="card-header">Action Items</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Review Research Department vacancies
                                    <span class="badge bg-must-green rounded-pill">High Priority</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Plan Finance Department training
                                    <span class="badge bg-warning rounded-pill">Medium Priority</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Analyze Academics Department budget
                                    <span class="badge bg-must-green rounded-pill">High Priority</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Schedule HR best practices session
                                    <span class="badge bg-primary rounded-pill">Low Priority</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <script>
        // Department data
        const departments = {
            it: {
                name: "Information Technology",
                employees: 42,
                avgTenure: "3.2 years",
                avgRating: 4.1,
                vacancy: "8%",
                positions: {
                    labels: ["Developers", "Sys Admins", "Support", "Managers", "Analysts"],
                    data: [18, 8, 10, 3, 3]
                },
                training: {
                    completed: 78,
                    inProgress: 15,
                    notStarted: 7
                },
                gender: {
                    male: 65,
                    female: 35
                },
                budget: {
                    allocated: 1200000,
                    utilized: 936000
                },
                activity: [
                    "New help desk system implemented (May 2023)",
                    "3 new developers hired",
                    "Quarterly security training completed",
                    "Server upgrade scheduled for next month"
                ]
            },
            finance: {
                name: "Finance",
                employees: 28,
                avgTenure: "5.1 years",
                avgRating: 4.3,
                vacancy: "5%",
                positions: {
                    labels: ["Accountants", "Analysts", "Clerks", "Managers", "Auditors"],
                    data: [10, 6, 8, 2, 2]
                },
                training: {
                    completed: 65,
                    inProgress: 20,
                    notStarted: 15
                },
                gender: {
                    male: 45,
                    female: 55
                },
                budget: {
                    allocated: 850000,
                    utilized: 697000
                },
                activity: [
                    "Annual budget review completed",
                    "New accounting software training",
                    "2 analysts promoted",
                    "Quarterly audit scheduled"
                ]
            },
            hr: {
                name: "Human Resources",
                employees: 18,
                avgTenure: "4.7 years",
                avgRating: 4.5,
                vacancy: "3%",
                positions: {
                    labels: ["Generalists", "Recruiters", "Compensation", "Managers", "Training"],
                    data: [6, 5, 3, 2, 2]
                },
                training: {
                    completed: 92,
                    inProgress: 5,
                    notStarted: 3
                },
                gender: {
                    male: 30,
                    female: 70
                },
                budget: {
                    allocated: 600000,
                    utilized: 450000
                },
                activity: [
                    "New employee onboarding program launched",
                    "Annual benefits review completed",
                    "Diversity training scheduled",
                    "Recruitment drive for tech positions"
                ]
            },
            academics: {
                name: "Academics",
                employees: 156,
                avgTenure: "6.3 years",
                avgRating: 4.2,
                vacancy: "12%",
                positions: {
                    labels: ["Professors", "Lecturers", "Assistants", "Deans", "Researchers"],
                    data: [45, 70, 25, 10, 6]
                },
                training: {
                    completed: 60,
                    inProgress: 25,
                    notStarted: 15
                },
                gender: {
                    male: 55,
                    female: 45
                },
                budget: {
                    allocated: 3500000,
                    utilized: 3080000
                },
                activity: [
                    "New curriculum development underway",
                    "10 new faculty positions approved",
                    "Research grant applications up 15%",
                    "Student evaluation process updated"
                ]
            },
            research: {
                name: "Research & Development",
                employees: 37,
                avgTenure: "4.2 years",
                avgRating: 4.4,
                vacancy: "15%",
                positions: {
                    labels: ["Scientists", "Engineers", "Technicians", "Managers", "Analysts"],
                    data: [15, 10, 8, 2, 2]
                },
                training: {
                    completed: 85,
                    inProgress: 10,
                    notStarted: 5
                },
                gender: {
                    male: 70,
                    female: 30
                },
                budget: {
                    allocated: 2500000,
                    utilized: 2300000
                },
                activity: [
                    "3 new patents filed this quarter",
                    "Collaboration with industry partners",
                    "New lab equipment installed",
                    "5 research papers published"
                ]
            }
        };

        // Initialize comparison charts
        const deptSizeCtx = document.getElementById('deptSizeChart').getContext('2d');
        const deptSizeChart = new Chart(deptSizeCtx, {
            type: 'bar',
            data: {
                labels: ['IT', 'Finance', 'HR', 'Academics', 'Research'],
                datasets: [{
                    label: 'Number of Employees',
                    data: [42, 28, 18, 156, 37],
                    backgroundColor: [
                        'rgba(0, 102, 51, 0.7)',
                        'rgba(0, 51, 102, 0.7)',
                        'rgba(255, 204, 0, 0.7)',
                        'rgba(0, 102, 51, 0.5)',
                        'rgba(0, 51, 102, 0.5)'
                    ],
                    borderColor: [
                        'rgba(0, 102, 51, 1)',
                        'rgba(0, 51, 102, 1)',
                        'rgba(255, 204, 0, 1)',
                        'rgba(0, 102, 51, 1)',
                        'rgba(0, 51, 102, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Department Size Comparison',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Employees'
                        }
                    }
                }
            }
        });

        const deptRatingCtx = document.getElementById('deptRatingChart').getContext('2d');
        const deptRatingChart = new Chart(deptRatingCtx, {
            type: 'radar',
            data: {
                labels: ['Performance', 'Productivity', 'Collaboration', 'Innovation', 'Satisfaction'],
                datasets: [{
                        label: 'IT Department',
                        data: [4.1, 4.3, 3.9, 4.5, 3.8],
                        backgroundColor: 'rgba(0, 102, 51, 0.2)',
                        borderColor: 'rgba(0, 102, 51, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(0, 102, 51, 1)'
                    },
                    {
                        label: 'Finance Department',
                        data: [4.3, 4.5, 4.2, 3.8, 4.1],
                        backgroundColor: 'rgba(0, 51, 102, 0.2)',
                        borderColor: 'rgba(0, 51, 102, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(0, 51, 102, 1)'
                    },
                    {
                        label: 'HR Department',
                        data: [4.5, 4.4, 4.7, 4.2, 4.6],
                        backgroundColor: 'rgba(255, 204, 0, 0.2)',
                        borderColor: 'rgba(255, 204, 0, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(255, 204, 0, 1)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Department Performance Metrics',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 5
                    }
                }
            }
        });

        // Department detail charts (will be initialized when department is selected)
        let deptPositionChart, deptTrainingChart, deptGenderChart, deptBudgetChart;

        // Search functionality for department dropdown
        document.getElementById('departmentSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('#departmentList .dropdown-item');

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Department selection
        document.querySelectorAll('#departmentList .dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const deptId = this.getAttribute('data-dept');
                showDepartmentDetails(deptId);
            });
        });

        // Back to comparison button
        document.getElementById('backToComparison').addEventListener('click', function() {
            document.getElementById('comparisonSection').classList.remove('d-none');
            document.getElementById('detailSection').classList.add('d-none');
        });

        // Show department details
        function showDepartmentDetails(deptId) {
            const dept = departments[deptId];

            // Update basic info
            document.getElementById('deptDetailTitle').textContent = dept.name + " Department Analytics";
            document.getElementById('deptEmployees').textContent = dept.employees;
            document.getElementById('deptAvgTenure').textContent = dept.avgTenure;
            document.getElementById('deptAvgRating').textContent = dept.avgRating;
            document.getElementById('deptVacancy').textContent = dept.vacancy;

            // Update activity list
            const activityList = document.getElementById('deptActivity');
            activityList.innerHTML = '';
            dept.activity.forEach(activity => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = activity;
                activityList.appendChild(li);
            });

            // Initialize or update charts
            updateDepartmentCharts(dept);

            // Show detail section and hide comparison
            document.getElementById('comparisonSection').classList.add('d-none');
            document.getElementById('detailSection').classList.remove('d-none');
        }

        // Update department detail charts
        function updateDepartmentCharts(dept) {
            // Position distribution chart
            if (deptPositionChart) {
                deptPositionChart.data.labels = dept.positions.labels;
                deptPositionChart.data.datasets[0].data = dept.positions.data;
                deptPositionChart.update();
            } else {
                const deptPositionCtx = document.getElementById('deptPositionChart').getContext('2d');
                deptPositionChart = new Chart(deptPositionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: dept.positions.labels,
                        datasets: [{
                            data: dept.positions.data,
                            backgroundColor: [
                                'rgba(0, 102, 51, 0.7)',
                                'rgba(0, 51, 102, 0.7)',
                                'rgba(255, 204, 0, 0.7)',
                                'rgba(0, 102, 51, 0.5)',
                                'rgba(0, 51, 102, 0.5)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Position Distribution',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Training completion chart
            if (deptTrainingChart) {
                deptTrainingChart.data.datasets[0].data = [
                    dept.training.completed,
                    dept.training.inProgress,
                    dept.training.notStarted
                ];
                deptTrainingChart.update();
            } else {
                const deptTrainingCtx = document.getElementById('deptTrainingChart').getContext('2d');
                deptTrainingChart = new Chart(deptTrainingCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Completed', 'In Progress', 'Not Started'],
                        datasets: [{
                            label: '% of Employees',
                            data: [
                                dept.training.completed,
                                dept.training.inProgress,
                                dept.training.notStarted
                            ],
                            backgroundColor: [
                                'rgba(0, 102, 51, 0.7)',
                                'rgba(255, 204, 0, 0.7)',
                                'rgba(204, 0, 0, 0.7)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Training Completion Status',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: '% of Employees'
                                }
                            }
                        }
                    }
                });
            }

            // Gender diversity chart
            if (deptGenderChart) {
                deptGenderChart.data.datasets[0].data = [
                    dept.gender.male,
                    dept.gender.female
                ];
                deptGenderChart.update();
            } else {
                const deptGenderCtx = document.getElementById('deptGenderChart').getContext('2d');
                deptGenderChart = new Chart(deptGenderCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Male', 'Female'],
                        datasets: [{
                            data: [
                                dept.gender.male,
                                dept.gender.female
                            ],
                            backgroundColor: [
                                'rgba(0, 51, 102, 0.7)',
                                'rgba(255, 204, 0, 0.7)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Gender Distribution',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Budget chart
            if (deptBudgetChart) {
                deptBudgetChart.data.datasets[0].data = [dept.budget.allocated];
                deptBudgetChart.data.datasets[1].data = [dept.budget.utilized];
                deptBudgetChart.update();
            } else {
                const deptBudgetCtx = document.getElementById('deptBudgetChart').getContext('2d');
                deptBudgetChart = new Chart(deptBudgetCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Budget'],
                        datasets: [{
                                label: 'Allocated',
                                data: [dept.budget.allocated],
                                backgroundColor: 'rgba(0, 102, 51, 0.7)',
                                borderWidth: 1
                            },
                            {
                                label: 'Utilized',
                                data: [dept.budget.utilized],
                                backgroundColor: 'rgba(0, 51, 102, 0.7)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Budget Allocation vs Utilization',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount (UGX)'
                                }
                            }
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>