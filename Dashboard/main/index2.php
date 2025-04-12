<?php
    session_start();
    if ($_SESSION['user_role'] !== 'hrm') {
        header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
        exit();
    }
include 'criteria/config.php';

// Fetch all data from database
$stats = [];

// 1. Academic Performance Data
$academicQuery = $conn->query("
    SELECT 
        COUNT(DISTINCT CASE WHEN d.degree_classification = 'First Class' THEN d.staff_id END) as first_class,
        COUNT(DISTINCT CASE WHEN d.degree_classification LIKE 'Second%' THEN d.staff_id END) as second_class,
        COUNT(DISTINCT s.staff_id) as total_staff
    FROM staff s
    LEFT JOIN degrees d ON s.staff_id = d.staff_id
");

if ($academicQuery) {
    $stats['academic'] = $academicQuery->fetch_assoc();
}

// 2. Research Data
$researchQuery = $conn->query("
    SELECT 
        COUNT(*) as total_publications,
        SUM(CASE WHEN p.publication_type = 'Journal Article' THEN 1 ELSE 0 END) as journal_articles,
        SUM(CASE WHEN p.publication_type = 'Conference Paper' THEN 1 ELSE 0 END) as conference_papers
    FROM publications p
");

if ($researchQuery) {
    $stats['research'] = $researchQuery->fetch_assoc();
}

// 3. Grants Data
$grantsQuery = $conn->query("
    SELECT 
        SUM(grant_amount) as total_grants,
        COUNT(*) as grant_count,
        ROUND(AVG(grant_amount), 0) as avg_grant
    FROM grants
");
if ($grantsQuery) {
    $stats['grants'] = $grantsQuery->fetch_assoc();
}

// 4. Community Service Data
$communityQuery = $conn->query("
    SELECT 
        COUNT(*) as total_services,
        COUNT(DISTINCT cs.staff_id) as staff_involved,
        COUNT(DISTINCT CASE WHEN cs.community_service_id = 'Consultancy' THEN cs.community_service_id END) as consultancies
    FROM communityservice cs
");

if ($communityQuery) {
    $stats['community'] = $communityQuery->fetch_assoc();
}

// 5. Supervision Data
$supervisionQuery = $conn->query("
    SELECT 
        COUNT(*) as total_supervisions,
        SUM(CASE WHEN student_level = 'PhD' THEN 1 ELSE 0 END) as phd_supervisions,
        SUM(CASE WHEN student_level = 'Masters' THEN 1 ELSE 0 END) as masters_supervisions,
        COUNT(DISTINCT staff_id) as supervisors
    FROM supervision
");

if ($supervisionQuery) {
    $stats['supervision'] = $supervisionQuery->fetch_assoc();
}

// 6. Innovation Data
$innovationQuery = $conn->query("
    SELECT 
        COUNT(*) as total_innovations,
        SUM(CASE WHEN innovation_type = 'Patent' THEN 1 ELSE 0 END) as patents,
        SUM(CASE WHEN innovation_type = 'Utility Model' THEN 1 ELSE 0 END) as utility_models,
        COUNT(DISTINCT staff_id) as innovators
    FROM innovations
");

if ($innovationQuery) {
    $stats['innovation'] = $innovationQuery->fetch_assoc();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Performance Dashboard</title>

    <!-- online files -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- local files -->
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.css">

    <link rel="stylesheet" href="../components/my_css/index2.css">
    <!-- <link rel="stylesheet" href="bars/nav_sidebar/nav_side_bar.css"> -->

</head>

<body>
    <?php
    // nav_bar
    include 'bars/nav_bar.php';
    // <!-- Sidebar -->
    include 'bars/side_bar.php';

    ?>
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Main content -->
            <section class="content">
                <!-- Dashboard Header -->
                <div class="dashboard-header">
                    <h3>FACAULTY PERFORMANCE DASHBOARD</h3>
                    <p class="mb-0">Comprehensive overview of academic, research, and community engagement metrics</p>
                </div>

                <!-- Key Metrics Summary -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="highlight-card text-center">
                            <div class="highlight-value"><?= $stats['academic']['total_staff'] ?? 0 ?></div>
                            <div class="highlight-label">Academic Staff</div>
                            <div class="trend-indicator trend-up mt-2">
                                <i class="fas fa-arrow-up"></i> 12% from last year
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="highlight-card text-center">
                            <div class="highlight-value"><?= $stats['research']['total_publications'] ?? 0 ?></div>
                            <div class="highlight-label">Research Publications</div>
                            <div class="trend-indicator trend-up mt-2">
                                <i class="fas fa-arrow-up"></i> 8% from last year
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="highlight-card text-center">
                            <div class="highlight-value"><?= number_format($stats['grants']['total_grants'] ?? 0) ?></div>
                            <div class="highlight-label">Research Grants (UGX)</div>
                            <div class="trend-indicator trend-up mt-2">
                                <i class="fas fa-arrow-up"></i> 15% from last year
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="highlight-card text-center">
                            <div class="highlight-value"><?= $stats['innovation']['total_innovations'] ?? 0 ?></div>
                            <div class="highlight-label">Innovations</div>
                            <div class="trend-indicator trend-up mt-2">
                                <i class="fas fa-arrow-up"></i> 5% from last year
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACADEMIC PERFORMANCE SECTION -->
                <div class="section-divider">
                    <div class="card pt-3 pb-3">
                        <h4 class="section-title">Academic Performance</h4>
                    </div>

                </div>

                <div class="row">
                    <!-- First Class Degrees -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card academic">
                            <div class="stat-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="stat-value"><?= $stats['academic']['first_class'] ?? 0 ?></div>
                            <div class="stat-label">First Class Degrees</div>
                            <div class="stat-description">
                                Staff members with first class honors degrees
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?= ($stats['academic']['first_class'] / $stats['academic']['total_staff']) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Class Degrees -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card academic">
                            <div class="stat-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-value"><?= $stats['academic']['second_class'] ?? 0 ?></div>
                            <div class="stat-label">Second Class Degrees</div>
                            <div class="stat-description">
                                Staff members with second class honors degrees
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?= ($stats['academic']['second_class'] / $stats['academic']['total_staff']) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Staff -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card academic">
                            <div class="stat-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="stat-value"><?= $stats['academic']['total_staff'] ?? 0 ?></div>
                            <div class="stat-label">Total Academic Staff</div>
                            <div class="stat-description">
                                Current teaching and research staff
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff with PhD -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card academic">
                            <div class="stat-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="stat-value"><?= $stats['supervision']['supervisors'] ?? 0 ?></div>
                            <div class="stat-label">PhD Supervisors</div>
                            <div class="stat-description">
                                Staff qualified to supervise PhD students
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?= ($stats['supervision']['supervisors'] / $stats['academic']['total_staff']) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Performance Charts -->
                <!-- <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-container">
                            <div class="chart-header d-flex justify-content-between align-items-center">
                                <h4>Academic Qualifications Over Time</h4>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="academicDropdown" data-bs-toggle="dropdown">
                                        Last 5 Years
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Last 3 Years</a></li>
                                        <li><a class="dropdown-item" href="#">Last 5 Years</a></li>
                                        <li><a class="dropdown-item" href="#">Last 10 Years</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div id="academicTrendChart" style="height: 300px;"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="chart-container">
                            <div class="chart-header">
                                <h4>Degree Class Distribution</h4>
                            </div>
                            <canvas id="degreePieChart" height="300"></canvas>
                        </div>
                    </div>
                </div> -->

                <!-- RESEARCH AND INNOVATION SECTION -->
                <div class="section-divider">
                    <div class="card pt-3 pb-3">
                        <h4 class="section-title">Research & Innovation</h4>
                    </div>
                </div>

                <div class="row">
                    <!-- Publications -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card research">
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="stat-value"><?= $stats['research']['total_publications'] ?? 0 ?></div>
                            <div class="stat-label">Total Publications</div>
                            <div class="stat-description">
                                Combined research outputs
                            </div>
                        </div>
                    </div>

                    <!-- Grants -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card research">
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-value"><?= number_format($stats['grants']['total_grants'] ?? 0) ?></div>
                            <div class="stat-label">Research Grants (UGX)</div>
                            <div class="stat-description">
                                Total funding secured
                            </div>
                        </div>
                    </div>

                    <!-- Innovations -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card innovation">
                            <div class="stat-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="stat-value"><?= $stats['innovation']['total_innovations'] ?? 0 ?></div>
                            <div class="stat-label">Innovations</div>
                            <div class="stat-description">
                                Patents and utility models
                            </div>
                        </div>
                    </div>

                    <!-- Citations -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card research">
                            <div class="stat-icon">
                                <i class="fas fa-quote-right"></i>
                            </div>
                            <div class="stat-value"><?= $stats['research']['avg_citations'] ?? 0 ?></div>
                            <div class="stat-label">Avg Citations</div>
                            <div class="stat-description">
                                Per publication
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Innovation Highlights -->
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-box-icon text-primary">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <h5>Patents Registered</h5>
                            <div class="stat-value"><?= $stats['innovation']['patents'] ?? 0 ?></div>
                            <div class="progress mt-2">
                                <div class="progress-bar" style="width: <?= ($stats['innovation']['patents'] / $stats['innovation']['total_innovations']) * 100 ?>%"></div>
                            </div>
                            <div class="stat-description mt-2">
                                <?= round(($stats['innovation']['patents'] / $stats['innovation']['total_innovations']) * 100, 1) ?>% of all innovations
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-box-icon text-success">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h5>Utility Models</h5>
                            <div class="stat-value"><?= $stats['innovation']['utility_models'] ?? 0 ?></div>
                            <div class="progress mt-2">
                                <div class="progress-bar" style="width: <?= ($stats['innovation']['utility_models'] / $stats['innovation']['total_innovations']) * 100 ?>%"></div>
                            </div>
                            <div class="stat-description mt-2">
                                <?= round(($stats['innovation']['utility_models'] / $stats['innovation']['total_innovations']) * 100, 1) ?>% of all innovations
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-box-icon text-warning">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5>Innovators</h5>
                            <div class="stat-value"><?= $stats['innovation']['innovators'] ?? 0 ?></div>
                            <div class="progress mt-2">
                                <div class="progress-bar" style="width: <?= ($stats['innovation']['innovators'] / $stats['academic']['total_staff']) * 100 ?>%"></div>
                            </div>
                            <div class="stat-description mt-2">
                                <?= round(($stats['innovation']['innovators'] / $stats['academic']['total_staff']) * 100, 1) ?>% of academic staff
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COMMUNITY ENGAGEMENT SECTION -->
                <div class="section-divider">
                    <div class="card pt-3 pb-3">
                        <h4 class="section-title">Community Engagement</h4>
                    </div>
                </div>

                <div class="row">
                    <!-- Community Services -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card community">
                            <div class="stat-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="stat-value"><?= $stats['community']['total_services'] ?? 0 ?></div>
                            <div class="stat-label">Community Services</div>
                            <div class="stat-description">
                                Outreach programs and initiatives
                            </div>
                        </div>
                    </div>

                    <!-- Staff Involved -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card community">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-value"><?= $stats['community']['staff_involved'] ?? 0 ?></div>
                            <div class="stat-label">Staff Involved</div>
                            <div class="stat-description">
                                Participating in community projects
                            </div>
                        </div>
                    </div>

                    <!-- Student Supervisions -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card community">
                            <div class="stat-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-value"><?= $stats['supervision']['total_supervisions'] ?? 0 ?></div>
                            <div class="stat-label">Help Involvement(s)</div>
                            <div class="stat-description">
                                PhD and Masters students
                            </div>
                        </div>
                    </div>

                    <!-- Consultancies -->
                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card community">
                            <div class="stat-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="stat-value"><?= $stats['community']['consultancies'] ?? 0 ?></div>
                            <div class="stat-label">Consultancies</div>
                            <div class="stat-description">
                                Professional services provided
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Community Engagement Charts -->
                <!-- <div class="row">
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="chart-header">
                                <h4>Supervision by Level</h4>
                            </div>
                            <canvas id="supervisionChart" height="300"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="chart-header">
                                <h4>Community Engagement Growth</h4>
                            </div>
                            <div id="communityTrendChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div> -->
            </section>
        </div>
    </div>

    <!-- Required JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.min.js"></script>
    <script>
        $(document).ready(function() {
            // Academic Trend Chart (ApexCharts)
            var academicTrendOptions = {
                series: [{
                    name: 'First Class',
                    data: [12, 15, 18, 20, 22]
                }, {
                    name: 'Second Class',
                    data: [45, 48, 50, 52, 55]
                }, {
                    name: 'Total Staff',
                    data: [80, 85, 90, 95, 100]
                }],
                chart: {
                    type: 'area',
                    height: '100%',
                    stacked: false,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#4361ee', '#4cc9f0', '#7209b7'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.8,
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                },
                xaxis: {
                    categories: ['2018', '2019', '2020', '2021', '2022'],
                    title: {
                        text: 'Year'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Staff'
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0) + " staff";
                            }
                            return y;
                        }
                    }
                }
            };
            var academicTrendChart = new ApexCharts(document.querySelector("#academicTrendChart"), academicTrendOptions);
            academicTrendChart.render();

            // Degree Pie Chart (Chart.js)
            var degreePieCtx = document.getElementById('degreePieChart').getContext('2d');
            var degreePieChart = new Chart(degreePieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['First Class', 'Second Class', 'Other'],
                    datasets: [{
                        data: [
                            <?= $stats['academic']['first_class'] ?? 0 ?>,
                            <?= $stats['academic']['second_class'] ?? 0 ?>,
                            <?= ($stats['academic']['total_staff'] ?? 0) - ($stats['academic']['first_class'] ?? 0) - ($stats['academic']['second_class'] ?? 0) ?>
                        ],
                        backgroundColor: [
                            '#4361ee',
                            '#4cc9f0',
                            '#adb5bd'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.raw || 0;
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Publication Type Chart (Chart.js)
            var publicationTypeCtx = document.getElementById('publicationTypeChart').getContext('2d');
            var publicationTypeChart = new Chart(publicationTypeCtx, {
                type: 'bar',
                data: {
                    labels: ['Journal Articles', 'Conference Papers'],
                    datasets: [{
                        label: 'Publications',
                        data: [
                            <?= $stats['research']['journal_articles'] ?? 0 ?>,
                            <?= $stats['research']['conference_papers'] ?? 0 ?>
                        ],
                        backgroundColor: [
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(76, 201, 240, 0.7)'
                        ],
                        borderColor: [
                            'rgba(67, 97, 238, 1)',
                            'rgba(76, 201, 240, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Publications'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw + ' publications';
                                }
                            }
                        }
                    }
                }
            });

            // Research Trend Chart (ApexCharts)
            var researchTrendOptions = {
                series: [{
                    name: 'Publications',
                    data: [45, 52, 60, 70, 75]
                }, {
                    name: 'Citations',
                    data: [120, 150, 180, 210, 240]
                }],
                chart: {
                    type: 'line',
                    height: '100%',
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#4361ee', '#f72585'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5
                },
                xaxis: {
                    categories: ['2018', '2019', '2020', '2021', '2022'],
                    title: {
                        text: 'Year'
                    }
                },
                yaxis: [{
                        title: {
                            text: 'Publications'
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Citations'
                        }
                    }
                ],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0);
                            }
                            return y;
                        }
                    }
                }
            };
            var researchTrendChart = new ApexCharts(document.querySelector("#researchTrendChart"), researchTrendOptions);
            researchTrendChart.render();

            // Supervision Chart (Chart.js)
            var supervisionCtx = document.getElementById('supervisionChart').getContext('2d');
            var supervisionChart = new Chart(supervisionCtx, {
                type: 'bar',
                data: {
                    labels: ['PhD Supervisions', 'Masters Supervisions', 'Total'],
                    datasets: [{
                        label: 'Supervisions',
                        data: [
                            <?= $stats['supervision']['phd_supervisions'] ?? 0 ?>,
                            <?= $stats['supervision']['masters_supervisions'] ?? 0 ?>,
                            <?= $stats['supervision']['total_supervisions'] ?? 0 ?>
                        ],
                        backgroundColor: [
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(76, 201, 240, 0.7)',
                            'rgba(114, 9, 183, 0.7)'
                        ],
                        borderColor: [
                            'rgba(67, 97, 238, 1)',
                            'rgba(76, 201, 240, 1)',
                            'rgba(114, 9, 183, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Supervisions'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw + ' supervisions';
                                }
                            }
                        }
                    }
                }
            });

            // Community Trend Chart (ApexCharts)
            var communityTrendOptions = {
                series: [{
                    name: 'Community Services',
                    data: [15, 18, 22, 25, 28]
                }, {
                    name: 'Staff Involved',
                    data: [30, 35, 40, 45, 50]
                }],
                chart: {
                    type: 'area',
                    height: '100%',
                    stacked: false,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#f8961e', '#f72585'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.8,
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                },
                xaxis: {
                    categories: ['2018', '2019', '2020', '2021', '2022'],
                    title: {
                        text: 'Year'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Activities/Staff'
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0);
                            }
                            return y;
                        }
                    }
                }
            };
            // Community Trend Chart (ApexCharts)
            var communityTrendOptions = {
                series: [{
                    name: 'Community Services',
                    data: [15, 18, 22, 25, 28] // Replace with actual data if available
                }, {
                    name: 'Staff Involved',
                    data: [30, 35, 40, 45, 50] // Replace with actual data if available
                }],
                chart: {
                    type: 'area',
                    height: '100%',
                    stacked: false,
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#f8961e', '#f72585'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.8,
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right'
                },
                xaxis: {
                    categories: ['2018', '2019', '2020', '2021', '2022'], // Replace with actual years if available
                    title: {
                        text: 'Year'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Activities/Staff'
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(y) {
                            if (typeof y !== "undefined") {
                                return y.toFixed(0);
                            }
                            return y;
                        }
                    }
                }
            };

            var communityTrendChart = new ApexCharts(document.querySelector("#communityTrendChart"), communityTrendOptions);
            communityTrendChart.render();
        });
    </script>
</body>

</html>