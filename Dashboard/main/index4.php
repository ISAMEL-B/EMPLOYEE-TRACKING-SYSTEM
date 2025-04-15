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
    <title>MUST Faculty Performance Dashboard</title>

    <!-- online Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- online fontawesome icons -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

    <!-- local files -->
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">

    <!-- <link rel="stylesheet" href="bars/nav_sidebar//nav_side_bar.css"> -->
    <link rel="stylesheet" href="../components/my_css/index4.css">

    <!-- Chart Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>
    <?php
    // nav_bar
    include 'bars/nav_bar.php';

    // <!-- Sidebar -->
    include 'bars/side_bar.php';
    ?>

    <!-- Main Content -->
    <div class="content mt-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">MUST Faculty Performance Dashboard</h1>
            <p class="dashboard-subtitle">Tracking Excellence in Teaching, Research & Community Service</p>
        </div>

        <!-- Faculty Selector -->
        <div class="faculty-selector">
            <select id="facultyFilter">
                <option value="all">All Faculties</option>
                <option value="science">Faculty of Science</option>
                <option value="engineering">Faculty of Engineering</option>
                <option value="medicine">Faculty of Medicine</option>
                <option value="computing">Faculty of Computing</option>
                <option value="business">Faculty of Business</option>
                <option value="applied">Faculty of Applied Sciences</option>
            </select>
        </div>

        <!-- KPI Cards -->
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon">👨‍🏫</div>
                    <div>
                        <div class="kpi-value" id="totalStaff">450</div>
                        <div class="kpi-label">Academic Staff</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon">🎓</div>
                    <div>
                        <div class="kpi-value" id="totalStudents">12,000</div>
                        <div class="kpi-label">Students</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon">📚</div>
                    <div>
                        <div class="kpi-value" id="totalPublications">850</div>
                        <div class="kpi-label">Publications</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon">💰</div>
                    <div>
                        <div class="kpi-value" id="totalGrants">UGX 3.2B</div>
                        <div class="kpi-label">Research Grants</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Chart 1: Student Enrollment Trend -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Student Enrollment Trend (2019-2023)</div>
                    <div class="chart-container">
                        <canvas id="enrollmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Staff Distribution -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Staff Distribution by Faculty</div>
                    <div class="chart-container">
                        <canvas id="staffChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Chart 3: Research Output -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Research Output (2023)</div>
                    <div class="chart-container">
                        <canvas id="researchChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 4: Community Engagement -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Community Engagement</div>
                    <div class="chart-container">
                        <canvas id="communityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 5: Faculty Comparison -->
        <div class="card">
            <div class="card-header">Faculty Performance Comparison</div>
            <div class="chart-container">
                <canvas id="comparisonChart" height="400"></canvas>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for Charts -->
    <script>
        // Data for MUST Faculties
        const facultyData = {
            science: {
                students: [1800, 1900, 2100, 2300, 2500],
                staff: 85,
                publications: 210,
                grants: 750
            },
            engineering: {
                students: [1500, 1600, 1800, 2000, 2200],
                staff: 75,
                publications: 180,
                grants: 1200
            },
            medicine: {
                students: [2200, 2400, 2600, 2800, 3000],
                staff: 120,
                publications: 240,
                grants: 900
            },
            computing: {
                students: [1200, 1400, 1600, 1800, 2000],
                staff: 65,
                publications: 125,
                grants: 450
            },
            business: {
                students: [800, 900, 1000, 1100, 1200],
                staff: 50,
                publications: 90,
                grants: 300
            },
            applied: {
                students: [600, 700, 800, 900, 1000],
                staff: 55,
                publications: 80,
                grants: 250
            }
        };

        // Initialize Charts
        function renderCharts(faculty = 'all') {
            // Chart 1: Student Enrollment Trend
            new Chart(document.getElementById('enrollmentChart'), {
                type: 'line',
                data: {
                    labels: ['2019', '2020', '2021', '2022', '2023'],
                    datasets: [{
                            label: 'Science',
                            data: facultyData.science.students,
                            borderColor: '#4E79A7'
                        },
                        {
                            label: 'Engineering',
                            data: facultyData.engineering.students,
                            borderColor: '#F28E2B'
                        },
                        {
                            label: 'Medicine',
                            data: facultyData.medicine.students,
                            borderColor: '#E15759'
                        },
                        {
                            label: 'Computing',
                            data: facultyData.computing.students,
                            borderColor: '#76B7B2'
                        }
                    ]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 2: Staff Distribution
            new Chart(document.getElementById('staffChart'), {
                type: 'pie',
                data: {
                    labels: ['Science', 'Engineering', 'Medicine', 'Computing', 'Business', 'Applied'],
                    datasets: [{
                        data: [85, 75, 120, 65, 50, 55],
                        backgroundColor: ['#4E79A7', '#F28E2B', '#E15759', '#76B7B2', '#59A14F', '#EDC948']
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 3: Research Output
            new Chart(document.getElementById('researchChart'), {
                type: 'bar',
                data: {
                    labels: ['Publications', 'Citations', 'Grants (UGX M)'],
                    datasets: [{
                            label: 'Science',
                            data: [210, 1200, 750],
                            backgroundColor: '#4E79A7'
                        },
                        {
                            label: 'Engineering',
                            data: [180, 950, 1200],
                            backgroundColor: '#F28E2B'
                        }
                    ]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 4: Community Engagement
            new Chart(document.getElementById('communityChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Outreach', 'Clinical', 'Training'],
                    datasets: [{
                        data: [45, 35, 40],
                        backgroundColor: ['#003366', '#FFD700', '#2E8B57']
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Chart 5: Faculty Comparison
            new Chart(document.getElementById('comparisonChart'), {
                type: 'bar',
                data: {
                    labels: ['Science', 'Engineering', 'Medicine', 'Computing'],
                    datasets: [{
                            label: 'Publications',
                            data: [210, 180, 240, 125],
                            backgroundColor: '#4E79A7'
                        },
                        {
                            label: 'Students',
                            data: [2500, 2200, 3000, 2000],
                            backgroundColor: '#F28E2B'
                        },
                        {
                            label: 'Staff',
                            data: [85, 75, 120, 65],
                            backgroundColor: '#E15759'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        }

        // Load charts on page load
        document.addEventListener('DOMContentLoaded', renderCharts);

        // Update dashboard when faculty is selected
        document.getElementById('facultyFilter').addEventListener('change', (e) => {
            const faculty = e.target.value;
            renderCharts(faculty);
        });
    </script>
</body>

</html>