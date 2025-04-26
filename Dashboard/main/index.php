<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

require_once 'head/approve/config.php'; // Use conn instead of pdo
// require_once 'head/approve/pdo.php'; 

function countRecords($conn, $table)
{
    $result = $conn->query("SELECT COUNT(*) AS count FROM $table");
    $row = $result->fetch_assoc();
    return $row['count'];
}

function countRecordsWhere($conn, $table, $condition)
{
    $result = $conn->query("SELECT COUNT(*) AS count FROM $table WHERE $condition");
    $row = $result->fetch_assoc();
    return $row['count'];
}

$totalStaff = countRecords($conn, 'staff');
$mastersCount = countRecordsWhere($conn, 'degrees', "degree_classification LIKE '%Master%' OR degree_name LIKE '%Mast%' OR degree_name LIKE '%MSc%'");
$phdHolders = countRecordsWhere($conn, 'degrees', "degree_classification LIKE '%PhD%'");

//not yet used
$publicationsCount = countRecords($conn, 'publications');
//not used yet
$grantsCount = countRecords($conn, 'grants');
$innovationsCount = countRecords($conn, 'innovations');
// Not used yet
$communityServiceCount = countRecords($conn, 'communityservice');

// Sum of beneficiaries (assuming there's a 'beneficiaries' column in communityservice table)
$beneficiariesSum = 0;
$beneficiariesQuery = $conn->query("SELECT SUM(beneficiaries) as total FROM communityservice");
if ($beneficiariesResult = $beneficiariesQuery->fetch_assoc()) {
    $beneficiariesSum = $beneficiariesResult['total'] ?? 0;
}

// Sum of grants 
$grantsCount = 0;
$grantsCountQuery = $conn->query("SELECT SUM(grant_amount) as total FROM grants");
if ($grantsCountResult = $grantsCountQuery->fetch_assoc()) {
    $grantsCount = $grantsCountResult['total'] ?? 0;
}

// Function to format grant amounts
function formatGrants($amount)
{
    if ($amount >= 1_000_000_000) {
        // Billions (remove extra zeros and add 'B')
        return round($amount / 1_000_000_000, 1) . 'B';
    } elseif ($amount >= 1_000_000) {
        // Millions (remove extra zeros and add 'M')
        return round($amount / 1_000_000, 1) . 'M';
    } else {
        // Less than a million, show full amount with thousands separator
        return number_format($amount);
    }
}

// Count of DISTINCT project types (using the 'description' column as project type)
$projectTypesCount = 0;
$projectTypesQuery = $conn->query("SELECT COUNT(DISTINCT description) as count FROM communityservice WHERE description IS NOT NULL AND description != ''");
if ($projectTypesResult = $projectTypesQuery->fetch_assoc()) {
    $projectTypesCount = (int)$projectTypesResult['count'] ?? 0;
}

// Count of DISTINCT staff members involved in community service
$distinctStaffCount = 0;
$staffCountQuery = $conn->query("SELECT COUNT(DISTINCT staff_id) as count FROM communityservice WHERE staff_id IS NOT NULL");
if ($staffCountResult = $staffCountQuery->fetch_assoc()) {
    $distinctStaffCount = (int)$staffCountResult['count'] ?? 0;
}

// Count of DISTINCT staff members involved in community service
$publicationsCount = 0;
$publicationsCountQuery = $conn->query("SELECT COUNT(DISTINCT publication_type) as count FROM publications WHERE staff_id IS NOT NULL");
if ($publicationsCountResult = $publicationsCountQuery->fetch_assoc()) {
    $publicationsCount = (int)$publicationsCountResult['count'] ?? 0;
}

$degreeStatsResult = $conn->query("
    SELECT 
        SUM(CASE WHEN degree_classification = 'First Class' THEN 1 ELSE 0 END) as first_class,
        SUM(CASE WHEN degree_classification = 'Second Class Upper' THEN 1 ELSE 0 END) as second_upper,
        SUM(CASE WHEN degree_classification = 'Second Class Lower' THEN 1 ELSE 0 END) as second_lower,
        SUM(CASE WHEN degree_classification LIKE '%PhD%' THEN 1 ELSE 0 END) as phd,
        SUM(CASE WHEN degree_classification LIKE '%Master%' THEN 1 ELSE 0 END) as masters
    FROM degrees
");
$degreeStats = $degreeStatsResult->fetch_assoc();

$publicationTypes = [];
$pubResult = $conn->query("
    SELECT publication_type, COUNT(*) as count
    FROM publications
    GROUP BY publication_type
");
while ($row = $pubResult->fetch_assoc()) {
    $publicationTypes[] = $row;
}

$grantsByDept = [];
$grantResult = $conn->query("
    SELECT d.department_name, COUNT(g.grant_id) as grant_count
    FROM grants g
    JOIN staff s ON g.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    GROUP BY d.department_name
");
while ($row = $grantResult->fetch_assoc()) {
    $grantsByDept[] = $row;
}

$supervisionStats = [];
$supervisionResult = $conn->query("
    SELECT student_level, COUNT(*) as count
    FROM supervision
    GROUP BY student_level
");
while ($row = $supervisionResult->fetch_assoc()) {
    $supervisionStats[] = $row;
}

$innovationTypes = [];
$innovationResult = $conn->query("
    SELECT innovation_type, COUNT(*) as count
    FROM innovations
    GROUP BY innovation_type
");
while ($row = $innovationResult->fetch_assoc()) {
    $innovationTypes[] = $row;
}

$communityServiceByDept = [];
$communityResult = $conn->query("
    SELECT d.department_name, COUNT(cs.community_service_id) as service_count
    FROM communityservice cs
    JOIN staff s ON cs.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    GROUP BY d.department_name
");
while ($row = $communityResult->fetch_assoc()) {
    $communityServiceByDept[] = $row;
}

// Charts
$publicationTypesData = [];
foreach ($publicationTypes as $type) {
    $publicationTypesData[$type['publication_type']] = $type['count'];
}

$grantsData = [];
foreach ($grantsByDept as $grant) {
    $grantsData[$grant['department_name']] = $grant['grant_count'];
}

$supervisionData = ['PhD' => 0, 'Masters' => 0, 'First Class' => 0];
foreach ($supervisionStats as $stat) {
    if ($stat['student_level'] === 'PhD') {
        $supervisionData['PhD'] = $stat['count'];
    } else if ($stat['student_level'] === 'First Class') {
        $supervisionData['First Class'] = $stat['count'];
    } else if ($stat['student_level'] === 'Second Class') {
        $supervisionData['Second Class'] = $stat['count'];
    } else {
        $supervisionData['Masters'] = $stat['count'];
    }
}

$innovationData = [];
foreach ($innovationTypes as $innovation) {
    $innovationData[$innovation['innovation_type']] = $innovation['count'];
}

$communityServiceData = [];
foreach ($communityServiceByDept as $service) {
    $communityServiceData[$service['department_name']] = $service['service_count'];
}

// Faculty performance
$facultyPerformance = [];
$facultyResult = $conn->query("
    SELECT 
        f.faculty_name,
        COUNT(DISTINCT p.publication_id) as publications,
        COUNT(DISTINCT g.grant_id) as grants,
        IFNULL(AVG(pm.metric_value), 0) as avg_performance,
        COUNT(DISTINCT s.staff_id) as staff_count
    FROM faculties f
    LEFT JOIN departments d ON f.faculty_id = d.faculty_id
    LEFT JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    LEFT JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    GROUP BY f.faculty_id
    ORDER BY publications DESC, grants DESC
");
while ($row = $facultyResult->fetch_assoc()) {
    $facultyPerformance[] = $row;
}
$topResearchFaculty = $facultyPerformance[0] ?? null;

$teachingResult = $conn->query("
    SELECT f.faculty_name
    FROM faculties f
    JOIN departments d ON f.faculty_id = d.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    WHERE pm.metric_name LIKE '%Student%Satisfaction%'
    GROUP BY f.faculty_id
    ORDER BY AVG(pm.metric_value) DESC
    LIMIT 1
");
$topTeachingFaculty = $teachingResult->fetch_assoc()['faculty_name'] ?? null;

$growthResult = $conn->query("
    SELECT f.faculty_name
    FROM faculties f
    JOIN departments d ON f.faculty_id = d.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    GROUP BY f.faculty_id
    ORDER BY (COUNT(DISTINCT p.publication_id) + COUNT(DISTINCT g.grant_id)) / COUNT(DISTINCT s.staff_id) DESC
    LIMIT 1
");
$growthFaculty = $growthResult->fetch_assoc()['faculty_name'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
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
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .dashboard-header {
        background: linear-gradient(135deg, var(--must-green) 0%, var(--must-blue) 100%);
        color: white;
        padding: 25px 0;
        margin-bottom: 30px;
        border-bottom: 5px solid var(--must-yellow);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .section-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border-top: 4px solid var(--must-yellow);
        background-color: white;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .section-title {
        color: #000;
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
        padding: 2px;
        box-shadow: var(--box-shadow);
        height: auto;
        margin-bottom: 20px;
    }
</style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>

    <!-- Dashboard Header -->
    <header class="dashboard-header mt-5" style="width: 80%; margin-left: 20%;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-university me-2"></i>Mbarara University of Science and Technology</h1>
                    <p class="mb-0 fs-5">Employee Performance Tracking Dashboard</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-0"><i class="fas fa-calendar-alt me-1"></i><span id="current-date"></span></p>
                    <button class="btn btn-must-primary btn-sm mt-2"><i class="fas fa-download me-1"></i>Export Report</button>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper mt-5" style="width: 80%; margin-left: 20%;">
        <div class="container">
            <!-- Quick Stats Row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= $totalStaff ?></div>
                            <div class="metric-label">Total Employees</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= $phdHolders ?></div>
                            <div class="metric-label">PhD Holders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= $publicationsCount ?></div>
                            <div class="metric-label">Research Publications</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= formatGrants($grantsCount) ?></div>
                            <div class="metric-label">Total Grants (UGX)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance Section -->
            <div class="card .">
                <div class="card-body">
                    <h2 class="section-title"><i class="fas fa-graduation-cap me-2 text-must-blue"></i>Academic Performance</h2>
                    <div class="large-card">
                        <div class="large-card">
                            <div class="large-card">
                                <div class="chart-container">
                                    <canvas id="qualificationsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-value"><?= $phdHolders ?></div>
                                            <div class="metric-label">PhD Holders</div>
                                        </div>
                                        <i class="fas fa-user-graduate fa-3x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-value"><?= $mastersCount ?></div>
                                            <div class="metric-label">Master's Degrees</div>
                                        </div>
                                        <i class="fas fa-user-tie fa-3x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-value"><?= isset($degreeStats['first_class']) ? $degreeStats['first_class'] : 0 ?></div>
                                            <div class="metric-label">1st Class Degrees</div>
                                        </div>
                                        <i class="fas fa-award fa-3x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-value"><?= isset($degreeStats['second_upper']) ? $degreeStats['second_upper'] : 0 ?></div>
                                            <div class="metric-label">2nd Class Upper Degrees</div>
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
                                    <h5 class="text-must-green"><i class="fas fa-chart-bar me-1"></i>Publication Types</h5>
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
                                            <div class="metric-value"><?= isset($publicationTypesData['Journal Article']) ? $publicationTypesData['Journal Article'] : 0 ?></div>
                                            <div class="metric-label">Journal Articles</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value"><?= isset($publicationTypesData['Conference Paper']) ? $publicationTypesData['Conference Paper'] : 0 ?></div>
                                            <div class="metric-label">Conference Papers</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">0</div>
                                            <div class="metric-label">Book Chapters</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                    <h5 class="text-must-green"><i class="fas fa-chart-pie me-1"></i>Grant Awards by Department</h5>
                                    <div class="chart-container">
                                        <canvas id="grantsChart"></canvas>
                                    </div>
                                </div>
                            </div>
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
                                            <div class="metric-value"><?= $supervisionData['PhD'] ?></div>
                                            <div class="metric-label">PhD Supervisions</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card metric-card">
                                        <div class="card-body">
                                            <div class="metric-value"><?= $supervisionData['Masters'] ?></div>
                                            <div class="metric-label">Masters Supervisions</div>
                                        </div>
                                    </div>
                                </div>

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
                                            <div class="metric-value"><?= isset($innovationData['Patent']) ? $innovationData['Patent'] : 0 ?></div>
                                            <div class="metric-label">Patents</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value"><?= isset($innovationData['Copyright']) ? $innovationData['Copyright'] : 0 ?></div>
                                            <div class="metric-label">Copyrights</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card metric-card h-100">
                                        <div class="card-body text-center">
                                            <div class="metric-value">0</div>
                                            <div class="metric-label">Trademarks</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                    <h2 class="section-title"><i class="fas fa-hands-helping me-2 text-must-blue"></i>Community Service</h2>

                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-must-green"><i class="fas fa-chart-bar me-1"></i>Community Service Participation</h5>
                            <div class="chart-container">
                                <canvas id="communityServiceChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-value"><?= $beneficiariesSum  ?></div>
                                            <div class="metric-label">Total Beneficiaries</div>
                                        </div>
                                        <i class="fas fa-clock fa-3x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Insights and Action Items -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card highlight-yellow">
                        <div class="card-header " style="font-weight: bold; font-size: 20px;">Key Performance Insights</div>
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

                <div class="col-md-6">
                    <div class="card highlight-blue">
                        <div class="card-header" style="font-weight: bold; font-size: 18px;">Strategic Action Items</div>
                        <div class="card-body">
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
    <footer class="py-4 mt-5" style="margin-left: 250px;"> <!-- Match sidebar width -->
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="logo/mustlogo.png" alt="MUST Logo" style="width: 80px;" class="me-3">
                <div>
                    <p class="mb-1">© 2025 Mbarara University of Science and Technology</p>
                    <p class="mb-0">Human Resource Management System | <small>Data updated hourly | For official use only</small></p>
                </div>
            </div>
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