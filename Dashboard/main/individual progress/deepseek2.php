<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Tracking System - Individual Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --warning-color: #f39c12;
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
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Search Section */
        .search-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .search-btn {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Profile Header */
        .profile-header {
            display: flex;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-color);
            margin-right: 20px;
        }
        
        .profile-info h1 {
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .profile-info p {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .score-card {
            margin-left: auto;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
        }
        
        .score-card .score {
            font-size: 2.5rem;
            font-weight: bold;
        }
        
        .score-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Overview Cards */
        .overview-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .overview-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        
        .overview-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .overview-card h3 {
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .overview-card p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        /* Main Content Cards */
        .dashboard-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .card-header h2 {
            color: var(--dark-color);
            font-size: 1.2rem;
        }
        
        .badge-must {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Charts */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-pic {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .score-card {
                margin: 15px 0 0 0;
            }
            
            .overview-cards {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .overview-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Search Section -->
    <div class="card search-card">
        <div class="card-body">
            <form id="searchForm" class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label for="staffSelect" class="form-label">Select Staff Member</label>
                    <select class="form-select" id="staffSelect" name="staff_id" required>
                        <option value="">-- Search for staff --</option>
                        <option value="1">Kato, John (Computer Science)</option>
                        <option value="2">Smith, Sarah (Electrical Engineering)</option>
                        <option value="3">Johnson, Michael (Mathematics)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn search-btn w-100 py-2">
                        <i class="fas fa-search me-2"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="profile-header">
        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile Picture" class="profile-pic">
        <div class="profile-info">
            <h1>Dr. John M. Kato</h1>
            <p>Associate Professor</p>
            <p>Department of Computer Science • Faculty of Computing & IT</p>
            <p>Years at current rank: 5 • Years of service: 12</p>
        </div>
        <div class="score-card">
            <div class="score">78</div>
            <div class="label">Total Score</div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="overview-cards">
        <div class="overview-card">
            <i class="fas fa-book-open"></i>
            <h3>24</h3>
            <p>Publications</p>
        </div>
        <div class="overview-card">
            <i class="fas fa-graduation-cap"></i>
            <h3>3</h3>
            <p>Qualifications</p>
        </div>
        <div class="overview-card">
            <i class="fas fa-money-bill-wave"></i>
            <h3>5</h3>
            <p>Research Grants</p>
        </div>
        <div class="overview-card">
            <i class="fas fa-user-graduate"></i>
            <h3>8</h3>
            <p>Supervisions</p>
        </div>
    </div>

    <!-- Publications Chart -->
    <div class="dashboard-card">
        <div class="card-header">
            <h2>Publications vs Citations</h2>
            <span class="badge badge-must">24 publications</span>
        </div>
        <div class="chart-container">
            <canvas id="publicationsChart"></canvas>
        </div>
    </div>

    <!-- Embedded Detailed Sections -->
    <div class="row">
        <!-- Publications -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Publications</h5>
                    <span class="badge badge-must">5</span>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <h6 class="mb-1">Journal Article</h6>
                            <p class="text-muted small mb-1">Role: First Author</p>
                        </div>
                        <div class="timeline-item mb-3">
                            <h6 class="mb-1">Conference Paper</h6>
                            <p class="text-muted small mb-1">Role: Corresponding Author</p>
                        </div>
                        <div class="timeline-item mb-3">
                            <h6 class="mb-1">Book Chapter</h6>
                            <p class="text-muted small mb-1">Role: Co-author</p>
                        </div>
                        <div class="timeline-item mb-3">
                            <h6 class="mb-1">Journal Article</h6>
                            <p class="text-muted small mb-1">Role: First Author</p>
                        </div>
                        <div class="timeline-item mb-3">
                            <h6 class="mb-1">Conference Paper</h6>
                            <p class="text-muted small mb-1">Role: Corresponding Author</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Degrees -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Academic Qualifications</h5>
                    <span class="badge badge-must">3</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>PhD in Computer Science</strong>
                            <span class="badge bg-info float-end">PhD</span>
                        </li>
                        <li class="list-group-item">
                            <strong>MSc in Software Engineering</strong>
                            <span class="badge bg-info float-end">Masters</span>
                        </li>
                        <li class="list-group-item">
                            <strong>BSc in Computer Science</strong>
                            <span class="badge bg-info float-end">First Class</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Grants -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Research Grants</h5>
                    <span class="badge badge-must">4</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>UGX 1,200,000,000.00</td>
                                </tr>
                                <tr>
                                    <td>UGX 750,000,000.00</td>
                                </tr>
                                <tr>
                                    <td>UGX 350,000,000.00</td>
                                </tr>
                                <tr>
                                    <td>UGX 80,000,000.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supervisions -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Student Supervisions</h5>
                    <span class="badge badge-must">6</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            PhD supervision
                        </li>
                        <li class="list-group-item">
                            PhD supervision
                        </li>
                        <li class="list-group-item">
                            Masters supervision
                        </li>
                        <li class="list-group-item">
                            Masters supervision
                        </li>
                        <li class="list-group-item">
                            Masters supervision
                        </li>
                        <li class="list-group-item">
                            Masters supervision
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Innovations -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Innovations</h5>
                    <span class="badge badge-must">2</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Patent: AI-based Learning System
                        </li>
                        <li class="list-group-item">
                            Copyright: Educational Software
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Activities & Services -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Activities & Services</h5>
                    <span class="badge badge-must">5</span>
                </div>
                <div class="card-body">
                    <h6>Academic Activities</h6>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            Conference presentation at IEEE 2023
                        </li>
                        <li class="list-group-item">
                            Journal editor for Computing Journal
                        </li>
                        <li class="list-group-item">
                            Thesis examination for PhD candidate
                        </li>
                    </ul>

                    <h6>Administrative Services</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Head of Department (2021-present)
                        </li>
                        <li class="list-group-item">
                            Faculty Research Committee member
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-must {
        background-color: #2e3192;
        color: white;
    }
    
    .timeline {
        position: relative;
        padding-left: 20px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 15px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -4px;
        top: 10px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #2e3192;
        border: 2px solid white;
    }
    
    .list-group-item {
        padding: 0.75rem 1.25rem;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .search-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    
    .search-btn {
        background-color: #3498db;
        color: white;
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    
    .profile-pic {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #3498db;
        margin-right: 20px;
    }
    
    .profile-info h1 {
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .profile-info p {
        color: #7f8c8d;
        margin-bottom: 5px;
    }
    
    .score-card {
        margin-left: auto;
        text-align: center;
        background: linear-gradient(135deg, #3498db, #2ecc71);
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
    }
    
    .score-card .score {
        font-size: 2.5rem;
        font-weight: bold;
    }
    
    .score-card .label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .overview-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
    }
    
    .overview-card i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #3498db;
    }
    
    .overview-card h3 {
        margin-bottom: 5px;
        color: #2c3e50;
    }
    
    .overview-card p {
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .dashboard-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Charts
            
            // Publications Chart
            const pubCtx = document.getElementById('publicationsChart').getContext('2d');
            new Chart(pubCtx, {
                type: 'bar',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Publications',
                            data: [3, 5, 7, 6, 8],
                            backgroundColor: '#3498db',
                            borderColor: '#2980b9',
                            borderWidth: 1
                        },
                        {
                            label: 'Citations',
                            data: [15, 22, 30, 28, 35],
                            backgroundColor: '#2ecc71',
                            borderColor: '#27ae60',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Publications vs Citations (Last 5 Years)',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });

            // Qualifications Chart
            const qualCtx = document.getElementById('qualificationsChart').getContext('2d');
            new Chart(qualCtx, {
                type: 'doughnut',
                data: {
                    labels: ['PhD (12 pts)', 'Masters (8 pts)', 'Bachelor (6 pts)'],
                    datasets: [{
                        data: [12, 8, 6],
                        backgroundColor: [
                            '#2ecc71',
                            '#3498db',
                            '#e74c3c'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Qualification Points Breakdown',
                            font: {
                                size: 14
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // Grants Chart
            const grantsCtx = document.getElementById('grantsChart').getContext('2d');
            new Chart(grantsCtx, {
                type: 'pie',
                data: {
                    labels: ['> UGX 1B (12 pts)', 'UGX 500M-1B (8 pts)', 'UGX 100M-500M (6 pts)'],
                    datasets: [{
                        data: [1, 2, 2],
                        backgroundColor: [
                            '#2ecc71',
                            '#3498db',
                            '#f39c12'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Grant Funding Tiers',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            });

            // Teaching Chart
            const teachCtx = document.getElementById('teachingChart').getContext('2d');
            new Chart(teachCtx, {
                type: 'radar',
                data: {
                    labels: ['Teaching Exp.', 'PhD Supervisions', 'Masters Supervisions'],
                    datasets: [{
                        label: 'Current Points',
                        data: [5, 12, 10],
                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(52, 152, 219, 1)'
                    }, {
                        label: 'Max Possible',
                        data: [15, 18, 15],
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(46, 204, 113, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 0,
                            suggestedMax: 20
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Teaching & Supervision Performance',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            });

            // Service Chart
            const serviceCtx = document.getElementById('serviceChart').getContext('2d');
            new Chart(serviceCtx, {
                type: 'horizontalBar',
                data: {
                    labels: ['University Service', 'Community Service', 'Professional Memberships'],
                    datasets: [{
                        label: 'Points',
                        data: [3, 5, 2],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ],
                        borderColor: [
                            'rgba(52, 152, 219, 1)',
                            'rgba(46, 204, 113, 1)',
                            'rgba(155, 89, 182, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 10
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Service & Leadership Points',
                            font: {
                                size: 14
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Progress Chart
            const progressCtx = document.getElementById('progressChart').getContext('2d');
            new Chart(progressCtx, {
                type: 'line',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Research',
                            data: [18, 22, 25, 28, 32],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Teaching',
                            data: [12, 14, 15, 16, 17],
                            borderColor: '#2ecc71',
                            backgroundColor: 'rgba(46, 204, 113, 0.1)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Service',
                            data: [5, 6, 7, 8, 10],
                            borderColor: '#9b59b6',
                            backgroundColor: 'rgba(155, 89, 182, 0.1)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Annual Performance Trends',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            });

            // Form submission handler
            document.getElementById('searchForm').addEventListener('submit', function(e) {
                e.preventDefault();
                // In a real app, this would fetch data for the selected staff member
                alert('In a real application, this would load data for the selected staff member');
            });
        });
    </script>
</body>
</html>