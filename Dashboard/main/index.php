<?php
include 'processes/index_process.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST Employee Performance Dashboard</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles/my_index.css">
</head>

<body>
    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>
    <?php
    // echo '<pre>';
    // print_r($grantsByFaculty);
    // echo '</pre>';
    // echo 'Total Faculties with Grants: ' . count($grantsByFaculty);

    ?>
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <h1><i class="fas fa-university me-2"></i>MBARARA UNIVERSITY OF SCIENCE AND TECHNOLOGY</h1>
                    <p>Employee Performance Tracking System</p>
                </div>
                <div class="header-actions">
                    <p class="mb-0"><i class="fas fa-calendar-alt me-1"></i><span id="current-date"></span></p>
                    <button class="btn btn-must-primary">MAIN DASHBOARD</button>
                </div>
            </div>
        </div>
    </header>

    <div class="main-wrapper">
        <div class="main-content">
            <div class="container-fluid">
                <!-- Quick Stats Row -->
                <div class="row mb-4 g-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="card metric-card h-100">
                            <div class="card-body text-center">
                                <div class="metric-value"><?= $totalStaff ?></div>
                                <div class="metric-label">Total Employees</div>
                                <i class="fas fa-users mt-3 text-muted opacity-25" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card metric-card h-100">
                            <div class="card-body text-center">
                                <div class="metric-value"><?= $phdHolders ?></div>
                                <div class="metric-label">PhD Holders</div>
                                <i class="fas fa-user-graduate mt-3 text-muted opacity-25" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card metric-card h-100">
                            <div class="card-body text-center">
                                <div class="metric-value"><?= $publicationsCount ?></div>
                                <div class="metric-label">Research Publications</div>
                                <i class="fas fa-book mt-3 text-muted opacity-25" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card metric-card h-100">
                            <div class="card-body text-center">
                                <div class="metric-value"><?= formatGrants($grantsCount) ?></div>
                                <div class="metric-label">Total Grants (UGX)</div>
                                <i class="fas fa-money-bill-wave mt-3 text-muted opacity-25" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Performance Section -->
                <div class="card section-card">
                    <div class="card-body">
                        <h2 class="section-title"><i class="fas fa-graduation-cap"></i>Academic Performance</h2>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="chart-container">
                                    <div class="chart-header">
                                        <h5 class="chart-title"><i class="fas fa-chart-pie me-1"></i>Staff Qualifications Distribution</h5>
                                    </div>
                                    <canvas id="qualificationsChart"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row h-100 g-3">
                                    <div class="col-md-6 col-lg-12">
                                        <div class="card metric-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center h-100">
                                                    <div>
                                                        <div class="metric-value"><?= $phdHolders ?></div>
                                                        <div class="metric-label">PhD Holders</div>
                                                    </div>
                                                    <i class="fas fa-user-graduate fa-3x text-muted opacity-25"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12">
                                        <div class="card metric-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center h-100">
                                                    <div>
                                                        <div class="metric-value"><?= $mastersCount ?></div>
                                                        <div class="metric-label">Master's Degrees</div>
                                                    </div>
                                                    <i class="fas fa-user-tie fa-3x text-muted opacity-25"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12">
                                        <div class="card metric-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center h-100">
                                                    <div>
                                                        <div class="metric-value"><?= isset($degreeStats['first_class']) ? $degreeStats['first_class'] : 0 ?></div>
                                                        <div class="metric-label">1st Class Degrees</div>
                                                    </div>
                                                    <i class="fas fa-award fa-3x text-muted opacity-25"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12">
                                        <div class="card metric-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center h-100">
                                                    <div>
                                                        <div class="metric-value"><?= isset($degreeStats['second_upper']) ? $degreeStats['second_upper'] : 0 ?></div>
                                                        <div class="metric-label">2nd Class Upper</div>
                                                    </div>
                                                    <i class="fas fa-medal fa-3x text-muted opacity-25"></i>
                                                </div>
                                            </div>
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
                        <h2 class="section-title"><i class="fas fa-flask"></i>Research and Innovations</h2>

                        <ul class="nav nav-pills mb-4" id="researchTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="publications-tab" data-bs-toggle="pill" data-bs-target="#publications" type="button" role="tab">
                                    <i class="fas fa-book me-1"></i>Publications
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="grants-tab" data-bs-toggle="pill" data-bs-target="#grants" type="button" role="tab">
                                    <i class="fas fa-money-bill-wave me-1"></i>Grants
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="supervision-tab" data-bs-toggle="pill" data-bs-target="#supervision" type="button" role="tab">
                                    <i class="fas fa-user-graduate me-1"></i>Supervision
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="innovations-tab" data-bs-toggle="pill" data-bs-target="#innovations" type="button" role="tab">
                                    <i class="fas fa-lightbulb me-1"></i>Innovations
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="researchTabsContent">
                            <div class="tab-pane fade show active" id="publications" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h5 class="chart-title"><i class="fas fa-chart-bar me-1"></i>Publication Types</h5>
                                            </div>
                                            <canvas id="publicationsChart" width="800" height="400"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h5 class="chart-title"><i class="fas fa-chart-line me-1"></i>Publication Trends</h5>
                                            </div>
                                            <canvas id="publicationsTrendChart" width="800" height="400"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 g-3">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($publicationTypesData['Book with ISBN']) ? $publicationTypesData['Book with ISBN'] : 0 ?></div>
                                                <div class="metric-label">Book with ISBN</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($publicationTypesData['Journal Article']) ? $publicationTypesData['Journal Article'] : 0 ?></div>
                                                <div class="metric-label">Journal Article</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($publicationTypesData['Book Chapter']) ? $publicationTypesData['Book Chapter'] : 0 ?></div>
                                                <div class="metric-label">Book Chapters</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="grants" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h5 class="chart-title"><i class="fas fa-chart-pie me-1"></i>Grants by Faculty</h5>
                                            </div>
                                            <canvas id="grantsChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                                            <?php foreach ($grantsByFaculty as $grantData): ?>
                                                <?php if ($grantData['grant_count'] > 0): ?>
                                                    <div class="col">
                                                        <div class="card metric-card h-100">
                                                            <div class="card-body">
                                                                <div class="d-flex flex-column justify-content-between h-100">
                                                                    <i class="fas fa-university mt-2 text-muted opacity-25 align-self-end"></i>
                                                                    <div>
                                                                        <div class="metric-label" style="font-weight: bold;"><?= $grantData['faculty_name'] ?></div>
                                                                        <div class="metric-amount">
                                                                            <b><?= number_format($grantData['total_amount'], 2) ?></b> <i>UGX</i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="supervision" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-lg-8">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h5 class="chart-title"><i class="fas fa-users-graduate me-1"></i>Postgraduate Supervision</h5>
                                            </div>
                                            <canvas id="supervisionChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row h-100 g-3">
                                            <div class="col-md-6 col-lg-12">
                                                <div class="card metric-card h-100">
                                                    <div class="card-body">
                                                        <div class="metric-value"><?= $supervisionData['PhD'] ?></div>
                                                        <div class="metric-label">PhD Supervisions</div>
                                                        <i class="fas fa-user-graduate mt-3 text-muted opacity-25 float-end" style="font-size: 2.5rem;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-12">
                                                <div class="card metric-card h-100">
                                                    <div class="card-body">
                                                        <div class="metric-value"><?= $supervisionData['Masters'] ?></div>
                                                        <div class="metric-label">Masters Supervisions</div>
                                                        <i class="fas fa-user-tie mt-3 text-muted opacity-25 float-end" style="font-size: 2.5rem;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="innovations" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h5 class="chart-title"><i class="fas fa-chart-pie me-1"></i>Innovation Types</h5>
                                            </div>
                                            <canvas id="innovationsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 g-3">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($innovationData['Patent']) ? $innovationData['Patent'] : 0 ?></div>
                                                <div class="metric-label">Patents</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($innovationData['Copyright']) ? $innovationData['Copyright'] : 0 ?></div>
                                                <div class="metric-label">Copyrights</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($innovationData['Utility Model']) ? $innovationData['Utility Model'] : 0 ?></div>
                                                <div class="metric-label">Utility Models</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($innovationData['Trademark']) ? $innovationData['Trademark'] : 0 ?></div>
                                                <div class="metric-label">Trademarks</div>
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
                        <h2 class="section-title"><i class="fas fa-hands-helping"></i>Community Service</h2>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="chart-container">
                                    <div class="chart-header">
                                        <h5 class="chart-title"><i class="fas fa-chart-bar me-1"></i>Community Service Participation</h5>
                                    </div>
                                    <canvas id="communityServiceChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 g-3">
                            <div class="col-md-4 col-sm-6">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="metric-value"><?= $distinctStaffCount ?></div>
                                                <div class="metric-label">Employees Engaged</div>
                                            </div>
                                            <i class="fas fa-users fa-3x text-muted opacity-25"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="metric-value"><?= $projectTypesCount ?></div>
                                                <div class="metric-label">Community Projects</div>
                                            </div>
                                            <i class="fas fa-project-diagram fa-3x text-muted opacity-25"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="card metric-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="metric-value"><?= $beneficiariesSum ?></div>
                                                <div class="metric-label">Total Beneficiaries</div>
                                            </div>
                                            <i class="fas fa-users fa-3x text-muted opacity-25"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Insights and Action Items -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card section-card highlight-yellow">
                            <div class="card-header bg-white border-bottom">
                                <h3 class="section-title mb-0"><i class="fas fa-chart-line"></i>Key Performance Insights</h3>
                            </div>
                            <div class="card-body">
                                <?php if ($topResearchFaculty || $topTeachingFaculty || $growthFaculty): ?>
                                    <?php if ($topResearchFaculty): ?>
                                        <div class="alert alert-info">
                                            <strong>Faculty of <?= htmlspecialchars($topResearchFaculty['faculty_name']) ?>:</strong>
                                            Leads with <b><?= $topResearchFaculty['publications'] ?></b> publication(s) and <b><?= $topResearchFaculty['grants'] ?></b> grant(s).

                                        </div>
                                    <?php endif; ?>

                                    <?php if ($topTeachingFaculty): ?>
                                        <div class="alert alert-warning">
                                            <strong>Faculty of <?= htmlspecialchars($topTeachingFaculty) ?>:</strong>
                                            Highest student satisfaction (<?= round($facultyPerformance[array_search($topTeachingFaculty, array_column($facultyPerformance, 'faculty_name'))]['avg_performance'] ?? 0) ?>/100)
                                            but lower research output. Encourage research-teaching balance.
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($growthFaculty): ?>
                                        <div class="alert alert-success">
                                            <strong>Faculty of <?= htmlspecialchars($growthFaculty) ?>:</strong>
                                            Strong growth in both research and teaching. Model for interdisciplinary collaboration.
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-secondary text-center">
                                        No data available for analysis at the moment.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card section-card highlight-blue">
                            <div class="card-header bg-white border-bottom">
                                <h3 class="section-title mb-0"><i class="fas fa-tasks"></i>Strategic Action Items</h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <?php if ($topTeachingFaculty): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Organize research methodology workshop for <?= htmlspecialchars($topTeachingFaculty) ?> faculty
                                            <span class="badge bg-must-green rounded-pill">High Priority</span>
                                        </li>
                                    <?php endif; ?>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Develop interdisciplinary research grants program
                                        <span class="badge bg-must-green rounded-pill">High Priority</span>
                                    </li>

                                    <?php
                                    // Find faculty with lowest performance (excluding those with 0 staff)
                                    $lowestPerforming = null;
                                    $minPerformance = PHP_FLOAT_MAX;

                                    foreach ($facultyPerformance as $faculty) {
                                        if ($faculty['staff_count'] > 0 && $faculty['avg_performance'] < $minPerformance) {
                                            $minPerformance = $faculty['avg_performance'];
                                            $lowestPerforming = $faculty;
                                        }
                                    }
                                    ?>

                                    <?php if ($lowestPerforming): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Review teaching loads in <?= htmlspecialchars($lowestPerforming['faculty_name']) ?>
                                            <span class="badge bg-warning rounded-pill">Medium Priority</span>
                                        </li>
                                    <?php endif; ?>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Plan community engagement fair for all faculties
                                        <span class="badge bg-primary rounded-pill">Low Priority</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="logo/mustlogo.png" alt="MUST Logo" style="width: 60px;" class="me-3">
                <div>
                    <p class="mb-1">© 2025 Mbarara University of Science and Technology</p>
                    <p class="mb-0 small">Human Resource Management System | Data updated hourly | For official use only</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Date Script -->
    <script>
        // Display current date
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', options);
    </script>
    <!-- Custom JS -->
    <script>
        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Academic Qualifications Chart
        const qualificationsCtx = document.getElementById('qualificationsChart').getContext('2d');
        const qualificationsChart = new Chart(qualificationsCtx, {
            type: 'bar',
            data: {
                labels: ['PhD', 'Master\'s', 'First Class', 'Second Class'],
                datasets: [{
                    data: [
                        <?= $degreeStats['phd'] ?>,
                        <?= $degreeStats['masters'] ?>,
                        <?= $degreeStats['first_class'] ?>,
                        <?= $degreeStats['second_upper'] ?>
                    ],
                    backgroundColor: [
                        '#006837', // MUST Green
                        '#005BAA', // MUST Blue
                        '#FFD700', // MUST Yellow
                        '#E5F2E9', // Light Green
                    ],
                    borderWidth: 1,
                    label: 'Academic Qualifications' // This label will show in the legend
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Staff', // <<< Vertical caption
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Qualification Level',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Publications Chart
        const publicationsCtx = document.getElementById('publicationsChart').getContext('2d');
        const publicationsChart = new Chart(publicationsCtx, {
            type: 'bar',
            data: {
                labels: ['Book with ISBN', 'Journal Articles', 'Book Chapter'],
                datasets: [{
                    label: 'Publications',
                    data: [
                        <?= isset($publicationTypesData['Book with ISBN']) ? $publicationTypesData['Book with ISBN'] : 0 ?>,
                        <?= isset($publicationTypesData['Book Chapter']) ? $publicationTypesData['Book Chapter'] : 0 ?>,
                        <?= isset($publicationTypesData['Journal Article']) ? $publicationTypesData['Journal Article'] : 0 ?>
                    ],
                    backgroundColor: [
                        '#006837', // MUST Green
                        '#005BAA', // MUST Blue
                        '#6dbfb8', // MUST
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Publication Type',
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number',
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        }
                    }
                }
            }
        });

        // Publications Trend Chart (using dummy data since we don't have date info)
        const trendCtx = document.getElementById('publicationsTrendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['2021', '2022', '2023', '2024', '2025'],
                datasets: [{
                    label: 'Total Publications',
                    data: [
                        Math.round(<?= $publicationsCount ?> * 0.3),
                        Math.round(<?= $publicationsCount ?> * 0.5),
                        Math.round(<?= $publicationsCount ?> * 0.7),
                        Math.round(<?= $publicationsCount ?> * 0.9),
                        <?= $publicationsCount ?>
                    ],
                    borderColor: '#003366',
                    backgroundColor: 'rgba(0, 51, 102, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Years',
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number',
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });


        // Grants Chart
        const grantsCtx = document.getElementById('grantsChart').getContext('2d');
        const grantsChart = new Chart(grantsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($grantsData)) ?>, // faculty names
                datasets: [{
                    label: 'Number of Grants',
                    data: <?= json_encode(array_values($grantsData)) ?>, // grant counts
                    backgroundColor: [
                        '#003366',
                        '#6699CC',
                        '#FF9900',
                        '#CCCCCC',
                        '#999999',
                        '#666666',
                        '#FF6666',
                        '#66CC66',
                        '#FFCC66'
                    ],
                    borderColor: '#fff',
                    borderWidth: 1,
                    borderRadius: 10, // Adds rounded corners to the bars
                    hoverBackgroundColor: [
                        '#005B88',
                        '#88AADD',
                        '#FFB300',
                        '#DDDDDD',
                        '#BBBBBB',
                        '#777777',
                        '#FF7777',
                        '#66DD77',
                        '#FFDD77'
                    ], // Changes the bar color on hover
                    hoverBorderWidth: 2, // Increases border width on hover
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
                                return `${context.label}: ${context.raw} grants`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Grants', // Vertical caption
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Faculty', // Horizontal caption
                            font: {
                                weight: 'bold'
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
                labels: ['PhD', 'Masters'],
                datasets: [{
                    label: 'Supervisions',
                    data: [
                        <?= $supervisionData['PhD'] ?>,
                        <?= $supervisionData['Masters'] ?>
                    ],
                    backgroundColor: [
                        '#005BAA', // MUST Blue
                        '#006837', // MUST Green
                    ],
                    borderRadius: {
                        topLeft: 5,
                        topRight: 5,
                        bottomLeft: 0,
                        bottomRight: 0
                    }
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Supervisions', // <<< Vertical caption
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Degree Level', // <<< Horizontal caption
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });


        // Innovations Chart
        const innovationsCtx = document.getElementById('innovationsChart').getContext('2d');
        const innovationsChart = new Chart(innovationsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($innovationData)) ?>,
                datasets: [{
                    label: 'Innovation Types',
                    data: <?= json_encode(array_values($innovationData)) ?>,
                    backgroundColor: [
                        '#003366', // Dark blue
                        '#6699CC', // Medium blue
                        '#FF9900', // Orange
                        '#CCCCCC', // Light gray
                        '#8dc73f', // Medium gray
                        '#666666' // Dark gray
                    ],
                    borderColor: [
                        '#001a33', // Darker blue
                        '#336699', // Darker medium blue
                        '#cc7a00', // Darker orange
                        '#aaaaaa', // Darker gray
                        '#777777', // Darker medium gray
                        '#444444' // Darker dark gray
                    ],
                    borderWidth: 1,
                    borderRadius: {
                        topLeft: 5,
                        topRight: 5,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            generateLabels: function(chart) {
                                const original = Chart.defaults.plugins.legend.labels.generateLabels(chart);
                                return original.map(label => {
                                    return {
                                        ...label,
                                        text: `${label.text} (${chart.data.datasets[0].data[label.index]})`
                                    };
                                });
                            },
                            boxWidth: 20,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#333'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Innovations', // <<< Vertical caption
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Innovation Type', // <<< Horizontal caption
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });


        // Community Service Chart with mixed colors
        const communityCtx = document.getElementById('communityServiceChart').getContext('2d');
        const communityChart = new Chart(communityCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($communityServiceData)) ?>,
                datasets: [{
                    label: 'Participants',
                    data: <?= json_encode(array_values($communityServiceData)) ?>,
                    backgroundColor: [
                        '#006837', // MUST Green
                        '#FFD700', // MUST Yellow
                        '#005BAA', // MUST Blue
                        '#17B612', // Vibrant Green
                        '#FFC72C', // Bright Yellow
                        '#1A73E8', // Google Blue
                        '#4CAF50', // Material Green
                        '#FFEB3B', // Material Yellow
                        '#2196F3' // Material Blue
                    ],
                    borderRadius: {
                        topLeft: 5,
                        topRight: 5,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderColor: '#fff',
                    borderWidth: 1,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Number of Participants',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Faculty',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>