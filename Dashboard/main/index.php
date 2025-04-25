<?php
//report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$current_ur = 'index.php';
$current_pag = 'index';

// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

//include backend calculator
// include '/EMPLOYEE-TRACKING-SYSTEM/scoring_calculator/university score/university_score.php';
include '../../scoring_calculator/university score/university_score.php';

// Verify the connection is available
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    die("Database connection not established in university_score.php");
}

// 1. PUBLICATIONS
$pubQuery = $mysqli->query("
    SELECT 
        COUNT(CASE WHEN publication_type = 'Journal Article' AND role = 'Author' THEN 1 END) as first_author,
        COUNT(CASE WHEN publication_type = 'Journal Article' AND role = 'Co-Author' THEN 1 END) as co_author,
        COUNT(*) as total
    FROM publications 
    WHERE YEAR(CURDATE()) = YEAR(CURDATE())
");
if (!$pubQuery) {
    die("Publications query failed: " . $mysqli->error);
}
$currentPublications = $pubQuery->fetch_assoc();
$pubQuery->close();

$pubQuery = $mysqli->query("
    SELECT 
        COUNT(CASE WHEN publication_type = 'Journal Article' AND role = 'Author' THEN 1 END) as first_author,
        COUNT(CASE WHEN publication_type = 'Journal Article' AND role = 'Co-Author' THEN 1 END) as co_author,
        COUNT(*) as total
    FROM publications 
    WHERE YEAR(CURDATE())-1 = YEAR(CURDATE())
");
if (!$pubQuery) {
    die("Previous publications query failed: " . $mysqli->error);
}
$previousPublications = $pubQuery->fetch_assoc();
$pubQuery->close();

// 2. GRANTS
$grantQuery = $mysqli->query("SELECT SUM(grant_amount) as total FROM grants WHERE YEAR(CURDATE()) = YEAR(CURDATE())");
if (!$grantQuery) {
    die("Grants query failed: " . $mysqli->error);
}
$currentGrants = $grantQuery->fetch_row()[0] ?? 0;
$grantQuery->close();

$grantQuery = $mysqli->query("SELECT SUM(grant_amount) as total FROM grants WHERE YEAR(CURDATE())-1 = YEAR(CURDATE())");
if (!$grantQuery) {
    die("Previous grants query failed: " . $mysqli->error);
}
$previousGrants = $grantQuery->fetch_row()[0] ?? 0;
$grantQuery->close();

// 3. PATENTS
$patentQuery = $mysqli->query("SELECT COUNT(*) FROM innovations WHERE innovation_type = 'Patent' AND YEAR(CURDATE()) = YEAR(CURDATE())");
if (!$patentQuery) {
    die("Patents query failed: " . $mysqli->error);
}
$currentPatents = $patentQuery->fetch_row()[0] ?? 0;
$patentQuery->close();

$patentQuery = $mysqli->query("SELECT COUNT(*) FROM innovations WHERE innovation_type = 'Patent' AND YEAR(CURDATE())-1 = YEAR(CURDATE())");
if (!$patentQuery) {
    die("Previous patents query failed: " . $mysqli->error);
}
$previousPatents = $patentQuery->fetch_row()[0] ?? 0;
$patentQuery->close();

// Prepare data
$currentData = [
    'publications' => $currentPublications['total'] ?? 0,
    'grants' => $currentGrants ?? 0,
    'patents' => $currentPatents ?? 0,
    'first_author' => $currentPublications['first_author'] ?? 0,
    'co_author' => $currentPublications['co_author'] ?? 0
];

$previousData = [
    'publications' => $previousPublications['total'] ?? 0,
    'grants' => $previousGrants ?? 0,
    'patents' => $previousPatents ?? 0
];

// Number formatting function
function formatNumber($number) {
    return number_format($number ?? 0, 0, '.', ',');
}

// Percentage calculation function
function calculateChange($current, $previous) {
    if ($previous > 0) {
        return round((($current - $previous) / $previous) * 100, 1);
    }
    return 0;
}

// Calculate all percentages
$percentages = [
    'publications' => calculateChange($currentData['publications'], $previousData['publications']),
    'grants' => calculateChange($currentData['grants'], $previousData['grants']),
    'patents' => calculateChange($currentData['patents'], $previousData['patents'])
];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../components/images/favicon.ico">

    <title>Dashboard</title>

    <!-- mine Style-->
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <!-- Style-->
    <link rel="stylesheet" href="../components/src/css/style.css">
    <link rel="stylesheet" href="css/stat-cards.css">';

    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">

        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            color: var(--must-green);
            border-bottom: 2px solid var(--must-yellow);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-card {
            border-left: 4px solid var(--must-blue);
            transition: all 0.3s ease;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--must-yellow);
        }

        .metric-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--must-blue);
            line-height: 1;
        }

        .metric-label {
            color: var(--must-green);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .chart-container {
            position: relative;
            margin-bottom: 20px;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            
            height: 400px;
            width: 100%;
        }

        .progress {
            height: 25px;
            border-radius: 5px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--must-green) 0%, var(--must-blue) 100%);
        }

        .badge-must {
            background-color: var(--must-yellow);
            color: #000;
            font-weight: 500;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(90deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            font-weight: 500;
        }

        .nav-pills .nav-link {
            color: var(--must-blue);
            font-weight: 500;
            border: 1px solid #dee2e6;
            margin-right: 5px;
        }

        .nav-pills .nav-link:hover {
            background-color: var(--must-light-green);
        }

        .table thead {
            background-color: var(--must-green);
            color: white;
        }

        .table-hover tbody tr:hover {
            background-color: var(--must-light-green);
        }

        footer {
            background: linear-gradient(135deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            border-top: 3px solid var(--must-yellow);
        }

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

        .bg-must-green {
            background-color: var(--must-green);
        }

        .bg-must-blue {
            background-color: var(--must-blue);
        }

        .bg-must-yellow {
            background-color: var(--must-yellow);
        }

        .text-must-green {
            color: var(--must-green);
        }

        .text-must-blue {
            color: var(--must-blue);
        }

        .text-must-yellow {
            color: var(--must-yellow);
        }

        .btn-must-primary {
            background: linear-gradient(90deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            border: none;
            font-weight: 500;
        }

        .btn-must-primary:hover {
            background: linear-gradient(90deg, var(--must-green) 0%, var(--must-blue) 80%);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .floating-action-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--must-green) 0%, var(--must-blue) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            cursor: pointer;
            transition: all 0.3s;
        }

        .floating-action-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        .large-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed sidebar-collapse">


    <!-- <div class="content-wrapper"> -->
    <div class="content-wrapper mt-5" style="width: 80%; margin-left: 20%;">
        <div class="container">
            <!-- Quick Stats Row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value">247</div>
                            <div class="metric-label">Total Employees</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value">83</div>
                            <div class="metric-label">PhD Holders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value">156</div>
                            <div class="metric-label">Research Publications</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value">42</div>
                            <div class="metric-label">Active Grants</div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- <div id="loader"></div> -->

        <!--side bar  -->
        <?php include 'bars/nav_bar.php';
        ?>
        <?php include 'bars/side_bar.php';
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-full">
                <!-- Main content -->
                <section class="content">
                    <!-- ---------------------------------------------------------------------------------------------------  


                           SECTION FOR ACADEMIC PERFORMANCE


                       ------------------------------------------------------------------------------------------------------>
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="box">
                                <div class="box-header" style="text-align : center ;">
                                    <h3 class="box-title" style="font-size: 40px; " style="text-align : center; "><b>ACADEMIC PERFORMANCE</b></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- actual dynamic data from your database / API -->
                    <?php

                    $phds = $university_data['PhD']; // Dynamic value for PhD's
                    $masters = $university_data['Masters']; // Dynamic value for Masters Degrees
                    $bachelors_first_class = $university_data['First Class']; // Dynamic value for Bachelor's Honors
                    $trainings = $university_data['Other']; // Dynamic value for Professional Trainings
                    $patents = $currentData['patents']; // Dynamic value for Patents from database
                    $publications = $currentData['publications']; // Dynamic value for publications from database
                    $journal = $currentData['first_author']; // Dynamic value for Journal Articles for First Author from database     
                    $articles = $currentData['co_author']; // Dynamic value for Journal Articles for Co-author from database
                    $grants = $currentData['grants']; // Dynamic value for Grants from database

                    ?>

                    <!-- <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-must-green"><i class="fas fa-chart-pie me-1"></i>Academic Qualifications Distribution</h5>
                            <div class="chart-container">
                                <canvas id="qualificationsChart"></canvas>
                            </div>
                        </div>
                        
                    </div> -->
                    <div class="large-card">
                        <div class="large-card">
                            <div class="large-card">
                                <div class="section-title">
                                    <h2>Faculty Overview</h2>
                                </div>
                                <div class="chart-container">
                                    <canvas id="qualificationsChart"></canvas>
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
                                        <i class="fas fa-user-graduate fa-3x text-muted opacity-25"></i>
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
                                        <i class="fas fa-user-tie fa-3x text-muted opacity-25"></i>
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
                                        <i class="fas fa-award fa-3x text-muted opacity-25"></i>
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
                    <h2 class="section-title"><i class="fas fa-flask me-2 text-must-blue"></i>Research and Innovations</h2>

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
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-must-green"><i class="fas fa-chart-bar me-1"></i>Publication Types (Current Year)</h5>
                                    <div class="chart-container">
                                        <canvas id="publicationsChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-must-green"><i class="fas fa-chart-line me-1"></i>Publication Trends</h5>
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
                                            <div class="mt-2">
                                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>15%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">24</div>
                                            <div class="metric-label">Conference Papers</div>
                                            <small class="text-muted">12 first authors</small>
                                            <div class="mt-2">
                                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>9%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">15</div>
                                            <div class="metric-label">Book Chapters</div>
                                            <small class="text-muted">5 first authors</small>
                                            <div class="mt-2">
                                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>7%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">8</div>
                                            <div class="metric-label">Books with ISBN</div>
                                            <small class="text-muted">3 single authors</small>
                                            <div class="mt-2">
                                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>5%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="grants" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-must-green"><i class="fas fa-chart-pie me-1"></i>Grant Awards by Faculty</h5>
                                    <div class="chart-container">
                                        <canvas id="grantsChart"></canvas>
                                    </div>
                                </div>
                                
                            </div>

                            <!-- <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card metric-card">
                                        <div class="card-body">
                                            <div class="metric-value">42</div>
                                            <div class="metric-label">Active Grants</div>
                                            <small>Total value: <span class="fw-bold">UGX 3.2B</span></small>
                                            <div class="mt-2">
                                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>18%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card metric-card">
                                        <div class="card-body">
                                            <div class="metric-value">18</div>
                                            <div class="metric-label">International Grants</div>
                                            <small>UGX <span class="fw-bold">2.1B</span> (66% of total)</small>
                                            <div class="mt-2">
                                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>22%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card metric-card">
                                        <div class="card-body">
                                            <div class="metric-value">24</div>
                                            <div class="metric-label">National Grants</div>
                                            <small>UGX <span class="fw-bold">1.1B</span> (34% of total)</small>
                                            <div class="mt-2">
                                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>12%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="tab-pane fade" id="supervision" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-must-green"><i class="fas fa-users-graduate me-1"></i>Postgraduate Supervision</h5>
                                    <div class="chart-container">
                                        <canvas id="supervisionChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card metric-card">
                                        <div class="card-body">
                                            <div class="metric-value">56</div>
                                            <div class="metric-label"> PhD Supervisions</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card metric-card">
                                        <div class="card-body">
                                            <div class="metric-value">124</div>
                                            <div class="metric-label"> Masters Supervisions</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-4">
                                    <div class="card metric-card">
                                        <div class="card-body">
                                            <div class="metric-value">82%</div>
                                            <div class="metric-label">Completion Rate</div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>

                        <div class="tab-pane fade" id="innovations" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-must-green"><i class="fas fa-chart-pie me-1"></i>Innovation Types</h5>
                                    <div class="chart-container">
                                        <canvas id="innovationsChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">7</div>
                                            <div class="metric-label">Copyrights</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">12</div>
                                            <div class="metric-label">Copyrights</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">9</div>
                                            <div class="metric-label">Trademarks</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">15</div>
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
                    <h2 class="section-title"><i class="fas fa-hands-helping me-2 text-must-blue"></i>Community Service</h2>

                    <div class="row">
                        <div class="col-xl-3 col-12"> </div>
                        <div class="col-xl-12 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"><b>ACADEMIC PERFORMANCE</b></h3>
                                </div>
                                <div class="box-body">
                                    <ul class="list-inline text-end">
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-info"></i>Phd's </h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text--bs-indigo"></i>Masters</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>First class</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>First class</h5>
                                        </li>
                                    </ul>
                                    <div id="morris-area-chart3" style="height: 245px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ---------------------------------------------------------------------------------------------------

                                    SECTION FOR RESEARCH AND INNOVATIONS

                    ---------------------------------------------------------------------------------------------------- -->

                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="box">
                                <div class="box-header" style="text-align: center;">
                                    <h3 class="box-title" style="font-size: 40px;"><b>RESEARCH AND INNOVATIONS</b></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h5 class="box-title">Publications by type</h5>
                                </div>                                
                                <div class="box-body" style="padding: 15px;">
                                    <div class="box-body chart-responsive" style="height: 180px;">
                                        <div class="chart" id="daily-inquery" style="height: 180px;"></div>
                                    </div>
                                    <ul class="list-inline" style="margin-bottom: 0;">
                                        <li class="flexbox mb-3 text-fade"> <!-- Reduced from mb-5 to mb-3 -->
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>First Author</span>
                                            </div>
                                            <div><?= $journal ?></div>
                                        </li>
                                        <li class="flexbox mb-3 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Co-author</span>
                                            </div>
                                            <div><?= $articles ?></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>    
                        </div>

                        <div class="col-lg-8 col-12">
                            <!-- Stat Cards Container -->
                            <div class="stat-cards-container">
                                <!-- Publications Card -->
                                <div class="stat-card">
                                    <h3>Publications</h3>
                                    <p class="stat-number formatted-number"><b><?= formatNumber($currentData['publications']) ?></b></p>
                                    <p class="stat-change <?= ($percentages['publications'] >= 0) ? 'positive' : 'negative' ?>">
                                        <?= ($percentages['publications'] >= 0) ? '+' : '' ?><?= $percentages['publications'] ?>% from last year
                                        <span class="stat-detail">(<?= formatNumber($previousData['publications']) ?>)</span>
                                    </p>
                                </div>

                                <!-- Grants Card -->
                                <div class="stat-card grants">
                                    <h3>Grants</h3>
                                    <p class="stat-number formatted-number"><b>$<?= formatNumber($currentData['grants']) ?></b></p>
                                    <p class="stat-change <?= ($percentages['grants'] >= 0) ? 'positive' : 'negative' ?>">
                                        <?= ($percentages['grants'] >= 0) ? '+' : '' ?><?= $percentages['grants'] ?>% from last year
                                        <span class="stat-detail">($<?= formatNumber($previousData['grants']) ?>)</span>
                                    </p>
                                </div>

                                <!-- Patents Card -->
                                <div class="stat-card patents">
                                    <h3>Patents</h3>
                                    <p class="stat-number formatted-number"><b><?= formatNumber($currentData['patents']) ?></b></p>
                                    <p class="stat-change <?= ($percentages['patents'] >= 0) ? 'positive' : 'negative' ?>">
                                        <?= ($percentages['patents'] >= 0) ? '+' : '' ?><?= $percentages['patents'] ?>% from last year
                                        <span class="stat-detail">(<?= formatNumber($previousData['patents']) ?>)</span>
                                    </p>
                                </div>
                            </div>                           
                        </div>
                    </div>


                    <!---------------------------------------------------------------------------------------------------


                                       SECTION FOR COMMUNITY SERVICES
                                      
                   ---------------------------------------------------------------------------------------------------->
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="box">
                                <div class="box-header" style="text-align : center ;">
                                    <h3 class="box-title" style="font-size: 40px; " style="text-align : center; "><b>COMMUNITY SERVICE</b></h3>
                                </div>

                            </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info rounded"><i class="fa fa-id-card"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b>85</b><small></small></span><br><br>
                                    <span class="info-box-text">Community Outreach Programs <br> </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success rounded"><i class="fas fa-thumbs-up"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b>600</b></span>
                                    <span class="info-box-text">Clinical Practices <br> </span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary rounded"><i class="fas fa-shopping-bag"></i></span>
                                <div class="info-box-content text-fade">
                                    <span class="info-box-number" style="font-size:40px"><b>900</b></span>
                                    <span class="info-box-text">Supervisions of students <br>in community placements</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h5 class="box-title">Community outreach programs</h5>
                                </div>
                                <div class="box-body">
                                    <div class="box-body chart-responsive">
                                        <div class="chart" id="communityoutreachprograms" style="height: 305px;"></div>
                                    </div>


                                    <!-- <div class="text-center py-20">
                                       <div class="communityoutreachprograms">
                                           data-peity='{ "fill": ["#ef5350", "#fec801", "#398bf7"], "radius": 78, "innerRadius": 58  }'
                                           9,6,5
                                       </div>
                                   </div> -->


                                    <ul class="list-inline">
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-warning"></span>
                                                <span>Awareness campaigns</span>
                                            </div>
                                            <div>300</div>
                                        </li>
                                        <li class="flexbox mb-5 text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-info"></span>
                                                <span>Embedded projects</span>
                                            </div>
                                            <div>55</div>
                                        </li>
                                        <li class="flexbox text-fade">
                                            <div>
                                                <span class="badge badge-dot badge-lg me-1 bg-danger"></span>
                                                <span>Workshops/Trainings</span>
                                            </div>
                                            <div>100</div>
                                        </li>

                                        <li class="flexbox text-fade">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-8 col-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"><b>COMMUNITY SERVICE</b></h3>
                                </div>
                                <div class="box-body">
                                    <ul class="list-inline text-center">
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-primary"></i>Supervision of Students in Community/Industrial Placements</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-success"></i>Community Outreach Programs </h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle me-5 text-danger"></i>Clinical Practices</h5>
                                        </li>
                                    </ul>
                                    <div class="chart">
                                        <div class="chart" id="communityservice" style="height: 233px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>
        </div>
        <!-- /.content-wrapper -->

        <!-- footer -->
        <?php //include 'bars/footer.php'; ?>

    </div>
    <!-- ./wrapper -->


    <!-- Page Content overlay -->
    <?php include 'bars/chatbot_overlay.php'; ?>


        // Academic Qualifications Chart
        const qualificationsCtx = document.getElementById('qualificationsChart').getContext('2d');
        const qualificationsChart = new Chart(qualificationsCtx, {
            type: 'bar',
            data: {
                labels: ['PhD', 'Master\'s', 'First Class', 'Second Class'],
                datasets: [{
                    data: [83, 112, 45, 5],
                    backgroundColor: [
                        '#006837', // MUST Green
                        '#005BAA', // MUST Blue
                        '#FFD700', // MUST Yellow
                        '#E5F2E9', // Light Green
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
                labels: ['Journal Articles', 'Conference Papers', 'Books'],
                datasets: [{
                    label: 'First Author',
                    data: [32, 12, 5],
                    backgroundColor: '#003366',
                }, {
                    label: 'Co-Author',
                    data: [46, 12, 10],
                    backgroundColor: '#6699CC',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Publication Type',  // X-axis label
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        }
                    },
                    y: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Number',  // X-axis label
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        }
                    }
                }
            }
        });

        // Publications Trend Chart
        const trendCtx = document.getElementById('publicationsTrendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['2021', '2022', '2023', '2024', '2025'],
                datasets: [{
                    label: 'Total Publications',
                    data: [112, 105, 127, 142, 156],
                    borderColor: '#003366',
                    backgroundColor: 'rgba(0, 51, 102, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x:{
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Years',  // X-axis label
                            font: {
                                weight: 'bold',
                                size: 16
                            }
                        }
                    },
                    y: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Number',  // X-axis label
                            font: {
                                weight: 'bold',
                                size: 16
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

        

        // Innovations Chart
        const innovationsCtx = document.getElementById('innovationsChart').getContext('2d');
        const innovationsChart = new Chart(innovationsCtx, {
            type: 'bar',
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