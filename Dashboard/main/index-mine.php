<?php
    include 'processes/index_process.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST Employee Performance Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --must-blue: rgb(16, 19, 111);
            --must-green: rgb(17, 123, 21);
            --must-yellow: rgb(251, 250, 249);
            --must-light: #f8f9fa;
            --must-light-green: #E5F2E9;
            --must-light-blue: #E6F0FA;
            --sidebar-width: 280px;
            --header-height: 80px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden;
            padding-top: var(--header-height);
        }

        /* Sidebar and Main Content Layout */
        .main-wrapper {
            display: flex;
            min-height: calc(100vh - var(--header-height));
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left var(--transition-speed);
        }

        /* Dashboard Header */
        .dashboard-header {
            /* margin-top: 20%; */
            background: linear-gradient(135deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 100;
            height: var(--header-height);
            border-bottom: 4px solid var(--must-yellow);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .header-title p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Cards and Sections */
        .section-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            border-top: 4px solid var(--must-yellow);
            background-color: white;
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            color: var(--must-blue);
            border-bottom: 2px solid rgba(16, 19, 111, 0.1);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            font-size: 1.1em;
        }

        .metric-card {
            border-left: 4px solid var(--must-blue);
            transition: all 0.3s ease;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--must-green);
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--must-blue);
            line-height: 1;
            margin-bottom: 5px;
        }

        .metric-label {
            color: var(--must-green);
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Charts */
        .chart-container {
            position: relative;
            margin-bottom: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: 350px;
            width: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-title {
            color: var(--must-green);
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 0;
        }

        /* Navigation Tabs */
        .nav-pills .nav-link.active {
            background: linear-gradient(90deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            font-weight: 500;
            border-radius: 6px;
        }

        .nav-pills .nav-link {
            color: var(--must-blue);
            font-weight: 500;
            border: 1px solid #dee2e6;
            margin-right: 5px;
            border-radius: 6px;
            padding: 8px 15px;
            font-size: 0.85rem;
        }

        .nav-pills .nav-link:hover {
            background-color: var(--must-light-green);
        }

        /* Tables */
        .table thead {
            background-color: var(--must-green);
            color: white;
        }

        .table-hover tbody tr:hover {
            background-color: var(--must-light-green);
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            border-top: 3px solid var(--must-yellow);
            padding: 20px 0;
            margin-left: var(--sidebar-width);
        }

        /* Impact Indicators */
        .impact-high {
            background-color: rgba(0, 104, 55, 0.1);
            color: var(--must-green);
            font-weight: 500;
        }

        .impact-medium {
            background-color: rgba(255, 215, 0, 0.1);
            color: #b38f00;
            font-weight: 500;
        }

        .impact-low {
            background-color: rgba(0, 91, 170, 0.1);
            color: var(--must-blue);
            font-weight: 500;
        }

        /* Buttons */
        .btn-must-primary {
            background: linear-gradient(90deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            border: none;
            font-weight: 500;
            border-radius: 6px;
            padding: 8px 20px;
            font-size: 0.85rem;
        }

        .btn-must-primary:hover {
            background: linear-gradient(90deg, var(--must-green) 0%, var(--must-blue) 80%);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Insights Cards */
        .highlight-yellow {
            border-top: 4px solid var(--must-yellow);
        }

        .highlight-blue {
            border-top: 4px solid var(--must-blue);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .dashboard-header, footer {
                left: 0;
            }
            
            .header-title h1 {
                font-size: 1.3rem;
            }
            
            .metric-value {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .section-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>

    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <h1><i class="fas fa-university me-2"></i>MUST Performance Dashboard</h1>
                    <p>Employee Performance Tracking System</p>
                </div>
                <div class="header-actions">
                    <p class="mb-0"><i class="fas fa-calendar-alt me-1"></i><span id="current-date"></span></p>
                    <button class="btn btn-must-primary"><i class="fas fa-download me-1"></i>Export Report</button>
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
                                            <canvas id="publicationsChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h5 class="chart-title"><i class="fas fa-chart-line me-1"></i>Publication Trends</h5>
                                            </div>
                                            <canvas id="publicationsTrendChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 g-3">
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($publicationTypesData['Journal Article']) ? $publicationTypesData['Journal Article'] : 0 ?></div>
                                                <div class="metric-label">Journal Articles</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value"><?= isset($publicationTypesData['Conference Paper']) ? $publicationTypesData['Conference Paper'] : 0 ?></div>
                                                <div class="metric-label">Conference Papers</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value">0</div>
                                                <div class="metric-label">Book Chapters</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value">0</div>
                                                <div class="metric-label">Books with ISBN</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="grants" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="chart-container">
                                            <div class="chart-header">
                                                <h5 class="chart-title"><i class="fas fa-chart-pie me-1"></i>Grant Awards by Department</h5>
                                            </div>
                                            <canvas id="grantsChart"></canvas>
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
                                                <div class="metric-value">0</div>
                                                <div class="metric-label">Trademarks</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card metric-card h-100">
                                            <div class="card-body text-center">
                                                <div class="metric-value">0</div>
                                                <div class="metric-label">Products</div>
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
                                            Consider sharing best practices with other faculties.
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
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
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
                        <?= $degreeStats['second_upper'] + $degreeStats['second_lower'] ?>
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
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 20, // Adjust the size of the key box
                            padding: 15, // Adjust the padding between key and label
                            font: {
                                weight: 'bold'
                            }
                        }
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
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 20,
                        ticks: {
                            stepSize: 2, // Shows ticks at 0, 2, 4, ..., 20
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Academic Performance',
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
                            text: 'Student Level',
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


        // Publications Chart
        const publicationsCtx = document.getElementById('publicationsChart').getContext('2d');
        const publicationsChart = new Chart(publicationsCtx, {
            type: 'bar',
            data: {
                labels: ['Journal Articles', 'Conference Papers'],
                datasets: [{
                    label: 'Publications',
                    data: [
                        <?= isset($publicationTypesData['Journal Article']) ? $publicationTypesData['Journal Article'] : 0 ?>,
                        <?= isset($publicationTypesData['Conference Paper']) ? $publicationTypesData['Conference Paper'] : 0 ?>
                    ],
                    backgroundColor: '#003366',
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
                        }
                    }
                }
            }
        });

        // Grants Chart
        const grantsCtx = document.getElementById('grantsChart').getContext('2d');
        const grantsChart = new Chart(grantsCtx, {
            type: 'polarArea',
            data: {
                labels: <?= json_encode(array_keys($grantsData)) ?>,
                datasets: [{
                    data: <?= json_encode(array_values($grantsData)) ?>,
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
                    backgroundColor: '#003366',
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
                        min: 0,
                        max: 20,
                        ticks: {
                            stepSize: 2, // Shows ticks at 0, 2, 4, ..., 20
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Number of Supervisions',
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
                            text: 'Student Level',
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
                        '#999999', // Medium gray
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
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 0.5,
                            precision: 1, // Shows one decimal place
                            callback: function(value) {
                                // Show all ticks (0, 0.5, 1, 1.5, etc.)
                                return value % 1 === 0 ? value : value.toFixed(1);
                            }
                        },
                        title: {
                            display: true,
                            text: 'Number of Innovations',
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            lineWidth: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Innovation Types',
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        },
                        grid: {
                            display: false
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
                        min: 0,
                        max: 20,
                        ticks: {
                            stepSize: 2,
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
                            text: 'Department',
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