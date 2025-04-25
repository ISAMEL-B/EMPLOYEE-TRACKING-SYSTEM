<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('display_errors', 1);
session_start();

// $current_ur = 'department.php';
// $current_pag = 'department';

// $current_page = basename($_SERVER['PHP_SELF']);
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

//get department score
include '../../scoring_calculator/department score/department_score.php';

//get count of employees in a department
include '../../scoring_calculator/department score/department_employees.php';


$department_id = $_GET['id'] ?? null;

if ($department_id) {
    $dept_data = get_department_performance($conn, $department_id);
    $dept_name = get_department_name($conn, $department_id);
    $dept_staff_count = get_department_staff_count($conn, $department_id);
} else {
    // Fallback
    die("No department selected.");
}
//department graphs
//overview 
//grants pie   & research grants under research and innovations
$grants_pie = [
    $dept_data['Over 1B'],
    $dept_data['500M - 1B'],
    $dept_data['100M - 500M'],
    $dept_data['Below 100M'],
];

//publications
//publications vs citations
// Get department publications and citations by year

$chartData = [
    'years' => [],
    'publications' => [],
    'citations' => []
];


try {
    // Query to get yearly publication and citation counts
    $stmt = $conn->prepare("
                        SELECT 
                            YEAR(p.publication_date) AS year,
                            COUNT(*) AS publication_count,
                            SUM(p.citations) AS citation_sum
                        FROM publications p
                        JOIN staff s ON p.staff_id = s.staff_id
                        WHERE s.department_id = ?
                        GROUP BY YEAR(p.publication_date)
                        ORDER BY year ASC
                    ");

    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $chartData['years'][] = $row['year'];
        $chartData['publications'][] = $row['publication_count'];
        $chartData['citations'][] = $row['citation_sum'];
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching publication data: " . $e->getMessage());
}

//get total citations for this year.
// Get current year citations for the department
$currentYear = date('Y');
$totalCitations = 0; // Default value if no data exists

if ($department_id) {
    try {
        $stmt = $conn->prepare("
                            SELECT SUM(p.citations) AS total_citations
                            FROM publications p
                            JOIN staff s ON p.staff_id = s.staff_id
                            WHERE s.department_id = ?
                            AND YEAR(p.publication_date) = ?
                        ");

        $stmt->bind_param("ii", $department_id, $currentYear);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $totalCitations = $row['total_citations'] ?? 0;
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Error fetching current year citations: " . $e->getMessage());
    }
}


//donought
$total_peer_reviewed = $dept_data['Journal Articles (First Author)'] + $dept_data['Journal Articles (Corresponding Author)'] + $dept_data['Journal Articles (Co-author)'];
$publication_types = [
    $total_peer_reviewed,
    $dept_data['Book Chapter'],
    $dept_data['Book with ISBN']
];

//research and innovations
//post graduate supervisions
$postgraduate_supervisions = [
    $dept_data['PhD Supervised'],
    $dept_data['Masters Supervised']
];

//innovations
$dept_innovations = [
    $dept_data['Patent'],
    $dept_data['Product'],
    $dept_data['Copyright'],
    $dept_data['Utility Model'],
    $dept_data['Trademark']
];

//academic performance
//academic qualifications by rank table
// get all staff by their roles from the database
// Initialize variables safely


// Initialize empty roles array with all possible academic ranks
$roles = [
    'Professor' => [],
    'Associate Professor' => [],
    'Senior Lecturer' => [],
    'Lecturer' => [],
    'Assistant Lecturer' => [],
    'Teaching Assistant' => []
];

// Initialize role map
$roleMap = [];

try {
    // Get all roles first to map role_id to role_name
    $roleQuery = $conn->query("SELECT role_id, role_name FROM roles");
    if ($roleQuery) {
        while ($row = $roleQuery->fetch_assoc()) {
            $roleMap[$row['role_id']] = $row['role_name'];
        }
    }

    // Get all staff in this department with their roles
    $staffQuery = $conn->prepare("
                        SELECT s.staff_id, s.first_name, s.last_name, s.role_id, r.role_name 
                        FROM staff s
                        JOIN roles r ON s.role_id = r.role_id
                        WHERE s.department_id = ?
                        ORDER BY 
                            CASE r.role_name 
                                WHEN 'Professor' THEN 1
                                WHEN 'Associate Professor' THEN 2
                                WHEN 'Senior Lecturer' THEN 3
                                WHEN 'Lecturer' THEN 4
                                WHEN 'Assistant Lecturer' THEN 5
                                WHEN 'Teaching Assistant' THEN 6
                                ELSE 7
                            END,
                            s.last_name, s.first_name
                    ");

    if ($staffQuery) {
        $staffQuery->bind_param("i", $department_id);
        $staffQuery->execute();
        $staffResult = $staffQuery->get_result();

        // Organize staff by their roles
        while ($staff = $staffResult->fetch_assoc()) {
            $roleName = $staff['role_name'] ?? 'Unknown';
            if (isset($roles[$roleName])) {
                $roles[$roleName][] = $staff;
            }
        }
    }

    // Get degrees for all staff in this department
    $staffDegrees = [];
    $degreesQuery = $conn->prepare("
                        SELECT d.staff_id, d.degree_name, d.degree_classification
                        FROM degrees d
                        JOIN staff s ON d.staff_id = s.staff_id
                        WHERE s.department_id = ?
                    ");

    if ($degreesQuery) {
        $degreesQuery->bind_param("i", $department_id);
        $degreesQuery->execute();
        $degreesResult = $degreesQuery->get_result();

        // Organize degrees by staff_id and type
        while ($degree = $degreesResult->fetch_assoc()) {
            $staffId = $degree['staff_id'] ?? 0;
            if (!$staffId) continue;

            $classification = $degree['degree_classification'] ?? '';
            $degreeName = $degree['degree_name'] ?? '';

            // Normalize classifications
            if (stripos($classification, 'First') !== false || stripos($classification, 'Second Class Upper') !== false) {
                $type = 'Bachelor';
            } elseif (stripos($degreeName, 'Master') !== false) {
                $type = 'Master';
            } elseif (stripos($degreeName, 'PhD') !== false || stripos($degreeName, 'Doctor') !== false) {
                $type = 'PhD';
            } else {
                $type = 'Other';
            }

            if (!isset($staffDegrees[$staffId])) {
                $staffDegrees[$staffId] = [];
            }

            if (!isset($staffDegrees[$staffId][$type])) {
                $staffDegrees[$staffId][$type] = 0;
            }

            $staffDegrees[$staffId][$type]++;
        }
    }
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    // Continue execution with empty data rather than breaking
}

// community service
// Initialize community service metrics
$community_scores = [
    'student_supervision' => 0,
    'outreach_programs' => 0,
    'beneficiaries' => 0
];

try {
    // Query community services for this department
    $serviceQuery = $conn->prepare("
                            SELECT cs.description, cs.beneficiaries 
                            FROM communityservice cs
                            JOIN staff s ON cs.staff_id = s.staff_id
                            WHERE s.department_id = ?
                        ");

    if ($serviceQuery) {
        $serviceQuery->bind_param("i", $department_id);
        $serviceQuery->execute();
        $serviceResult = $serviceQuery->get_result();

        while ($service = $serviceResult->fetch_assoc()) {
            $description = strtolower(trim($service['description'] ?? ''));
            $beneficiaries = intval($service['beneficiaries'] ?? 0);

            // Categorize the service
            if (strpos($description, 'student') !== false && strpos($description, 'supervision') !== false) {
                $community_scores['student_supervision']++;
            } else {
                $community_scores['outreach_programs']++;
            }

            // Add beneficiaries count
            $community_scores['beneficiaries'] += $beneficiaries;
        }
    }
} catch (Exception $e) {
    error_log("Community service query error: " . $e->getMessage());
}

// community service data
$community_service_scores = [
    $community_scores['student_supervision'],
    $community_scores['outreach_programs'],
    $community_scores['beneficiaries']
];

// echo $community_scores['student_supervision'];
// echo $community_scores['outreach_programs'];
// echo $community_scores['beneficiaries'];

// top performers
// Query to get top 5 performers in the department
$topPerformers = [];
try {
    $performerQuery = $conn->prepare("
                    SELECT s.staff_id, s.first_name, s.last_name, s.performance_score, r.role_name
                    FROM staff s
                    JOIN roles r ON s.role_id = r.role_id
                    WHERE s.department_id = ?
                    ORDER BY s.performance_score DESC
                    LIMIT 5
                ");

    if ($performerQuery) {
        $performerQuery->bind_param("i", $department_id);
        $performerQuery->execute();
        $performerResult = $performerQuery->get_result();

        while ($performer = $performerResult->fetch_assoc()) {
            $topPerformers[] = [
                'staff_id' => $performer['staff_id'], // THIS WAS MISSING
                'first_name' => $performer['first_name'] ?? '',
                'last_name' => $performer['last_name'] ?? '',
                'score' => $performer['performance_score'] ?? 0,
                'role' => $performer['role_name'] ?? ''
            ];
        }
    }
} catch (Exception $e) {
    error_log("Top performers query error: " . $e->getMessage());
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Employee Tracking - Department Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <style> </style>
    <link rel="stylesheet" href="styles/department_styles.css">
    <link rel="stylesheet" href="styles/individual_style.css">

    <!-- <link rel="stylesheet" href="styles/individual_style.css">
<link rel="stylesheet" href="styles/individual_style.css"> -->
</head>

<body>
    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php';
    $current_ur = 'index3.php';
    $current_pag = 'index3';
    ?>

    <!-- sidebar -->
    <?php include 'bars/side_bar.php';
    ?>

    <div class="content-wrapper">
        <div class="container-fluid py-4">
            <div class="explorer-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 fw-bold">Department of <?= htmlspecialchars($dept_name) ?></h1>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <i class="fas fa-trophy fa-4x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-3" style="text-align: right;">
                <a href="index3.php"
                    style="display: inline-block; 
                    padding: 8px 16px;
                    background-color: #3498db;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    padding-bottom: 3px;">
                    <i class="fas fa-arrow-left me-2"></i>Back to Department Dashboard
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value"><?= $dept_staff_count ?></div>
                        <div class="stat-label" style="font-size: 20px; font-weight: bold;">Total Staff</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value"><?= $dept_data['total_publications'] ?></div>
                        <div class="stat-label" style="font-size: 20px; font-weight: bold;">Total Publications</div>
                        <div class="mt-2">
                            <span class="badge bg-success me-1">Books with ISBN : <?= $dept_data['Book with ISBN'] ?></span>
                            <span class="badge bg-success">Book chapters : <?= $dept_data['Book Chapter'] ?></span>
                            <span class="badge bg-success me-1">Journal Articles : <?= $dept_data['Journal Articles (Co-author)'] + $dept_data['Journal Articles (Corresponding Author)'] + $dept_data['Journal Articles (First Author)'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value"><?= 'UGX ' . number_format($dept_data['total_grant_amount'] / 1_000_000) . 'M' ?></div>
                        <div class="stat-label" style="font-size: 20px; font-weight: bold;">Research Grants</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-value"><?= $dept_data['total_innovations'] ?></div>
                        <div class="stat-label" style="font-size: 20px; font-weight: bold;">Total innovations</div>
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
                    <button class="nav-link" id="research-tab" data-bs-toggle="tab" data-bs-target="#research" type="button" role="tab" aria-controls="research" aria-selected="false">Research & Innovations</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="academicperformance-tab" data-bs-toggle="tab" data-bs-target="#academicperformance" type="button" role="tab" aria-controls="academicperformance" aria-selected="false">Academic Performance</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="communityservice-tab" data-bs-toggle="tab" data-bs-target="#communityservice" type="button" role="tab" aria-controls="communityservice" aria-selected="false">Community service</button>
                </li>
            </ul>

            <div class="tab-content" id="dashboardTabsContent">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <!-- Performance by Academic Rank (Full width) -->
                    <!-- <div class="row mb-4">
                        <div class="col-12">
                            <div class="large-card">
                                <div class="chart-container">
                                    <div class="chart-title">Performance by Academic Rank</div>
                                    <div class="rank-filter-container">
                                        <label for="rankFilter">Select Academic Rank:</label>
                                        <select id="rankFilter">
                                            <option value="professor" selected>professor</option>
                                            <option value="associate">AssociateProfessor</option>

                                            <option value="senior">Senior Lecturer</option>
                                            <option value="lecturer">Lecturer</option>
                                            <option value="assistant">Assistant Lecturer</option>
                                            <option value="teaching">Teaching Assistant</option>
                                        </select>
                                    </div>
                                    <div class="chart-wrapper">
                                        <canvas id="performanceChart"></canvas>
                                    </div>
                                    <div id="noDataMessage" class="no-data-message" style="display: none;">
                                        No staff members found for this rank
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Other charts in original two-column layout -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h3 class="chart-title">Trends Over Time</h3>
                                <div class="chart-wrapper">
                                    <canvas id="trendsChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h3 class="chart-title">Grant Funding Distribution</h3>
                                <div class="chart-wrapper">
                                    <canvas id="grantsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="publications" role="tabpanel" aria-labelledby="publications-tab">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $dept_data['Journal Articles (First Author)']; ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">First Author Peer reviewed Publications</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $dept_data['Journal Articles (Co-author)'] ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Co-Authored Publications in Peer reviewed Publications</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $total_peer_reviewed ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Total Number of Peer-Reviewed Publications</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $totalCitations ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">
                                    Total Citations (<?= $currentYear ?>)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="chart-container">
                                <h3 class="chart-title">Citations vs Publications</h3>
                                <div class="chart-wrapper">
                                    <canvas id="citationsChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="chart-container">
                                <h3 class="chart-title">Publication Types</h3>
                                <div class="chart-wrapper">
                                    <canvas id="publicationTypesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="research" role="tabpanel" aria-labelledby="research-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h3 class="chart-title">Research Grants</h3>
                                <div class="chart-wrapper">
                                    <canvas id="researchGrantsChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h3 class="chart-title">Supervision Completions</h3>
                                <div class="chart-wrapper">
                                    <canvas id="supervisionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <h3 class="chart-title">Innovation Types Distribution</h3>
                                <div class="chart-wrapper">
                                    <canvas id="innovationTypesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="academicperformance" role="tabpanel" aria-labelledby="academicperformance-tab">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $dept_data['PhD'] ?> </div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Phd's</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $dept_data['Masters'] ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Masters</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $dept_data['First Class'] ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">First class</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value"><?= $dept_data['Second Upper'] ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Second Class</div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Academic Qualifications Section -->
                    <div class="row">
                        <div class="col-12">
                            <?php foreach ($roles as $roleName => $staffMembers): ?>
                                <div class="rank-section">
                                    <div class="rank-header">
                                        <span><?= htmlspecialchars($roleName) ?>s</span>
                                        <span class="rank-count"><?= count($staffMembers) ?> staff</span>
                                    </div>

                                    <?php if (!empty($staffMembers)): ?>
                                        <table class="qualifications-table">
                                            <thead>
                                                <tr>
                                                    <th>Staff Member</th>
                                                    <th>Qualifications</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($staffMembers as $staff): ?>
                                                    <?php
                                                    $staffId = $staff['staff_id'] ?? 0;
                                                    $firstName = $staff['first_name'] ?? '';
                                                    $lastName = $staff['last_name'] ?? '';
                                                    ?>
                                                    <tr>
                                                        <td class="staff-name">
                                                            <?php
                                                            $prefix = '';
                                                            if ($roleName === 'Professor') {
                                                                $prefix = 'Prof. ';
                                                            } elseif ($roleName === 'Associate Professor') {
                                                                $prefix = 'Assoc. Prof. ';
                                                            } elseif (in_array($roleName, ['Senior Lecturer', 'Lecturer'])) {
                                                                $prefix = 'Dr. ';
                                                            } elseif ($roleName === 'Teaching Assistant') {
                                                                $hasPhD = isset($staffDegrees[$staffId]['PhD']);
                                                                $prefix = $hasPhD ? 'Dr. ' : '';
                                                            }
                                                            echo htmlspecialchars($prefix . $firstName . ' ' . $lastName);
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($staffDegrees[$staffId]) && !empty($staffDegrees[$staffId])): ?>
                                                                <?php foreach ($staffDegrees[$staffId] as $degreeType => $count): ?>
                                                                    <?php
                                                                    $badgeClass = strtolower($degreeType);
                                                                    $displayText = $degreeType;

                                                                    if ($degreeType === 'Bachelor') {
                                                                        $displayText = "Bachelor's";
                                                                    }
                                                                    ?>
                                                                    <span class="qualification-badge <?= $badgeClass ?> <?= $count > 1 ? 'counted' : '' ?>"
                                                                        <?= $count > 1 ? 'data-count="' . $count . '"' : '' ?>>
                                                                        <?= htmlspecialchars($displayText) ?>
                                                                    </span>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <span class="no-qualifications">No qualifications recorded</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <!-- <a href="#" class="more-link">View all <? //= count($staffMembers) 
                                                                                    ?> <? //= strtolower($roleName) 
                                                                                        ?>s →</a> -->
                                    <?php else: ?>
                                        <div class="no-staff-message">No <?= strtolower($roleName) ?>s found in this department</div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- community service -->
                <div class="tab-pane fade" id="communityservice" role="tabpanel" aria-labelledby="communityservice-tab">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value"><?= $community_scores['student_supervision'] ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Industrial Placement Supervision</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value"><?= $community_scores['outreach_programs'] ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Community Outreach Programs</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-value"><?= $community_scores['beneficiaries'] ?></div>
                                <div class="stat-label" style="font-size: 15px; font-weight: bold;">Total number of beneficiaries</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-8">
                            <div class="chart-container">
                                <h3 class="chart-title">Community Service Distribution</h3>
                                <div class="chart-wrapper">
                                    <canvas id="communityServiceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="leaderboard">
                        <h3 class="chart-title">Top Performers</h3>

                        <?php if (!empty($topPerformers)): ?>
                            <?php foreach ($topPerformers as $index => $performer): ?>
                                <?php
                                // Determine appropriate title prefix
                                $prefix = '';
                                if (stripos($performer['role'], 'professor') !== false) {
                                    $prefix = 'Prof. ';
                                } elseif (
                                    stripos($performer['role'], 'lecturer') !== false ||
                                    stripos($performer['role'], 'assistant') !== false
                                ) {
                                    $prefix = 'Dr. ';
                                }

                                // Get staff ID safely
                                $staff_id = $performer['staff_id'] ?? 0;
                                ?>
                                <div class="leaderboard-item">
                                    <div class="leaderboard-rank"><?= $index + 1 ?></div>
                                    <div class="leaderboard-name">
                                        <a href="individual_view.php?staff_id=<?= $staff_id ?>" class="staff-link">
                                            <?= htmlspecialchars($prefix . $performer['first_name'] . ' ' . $performer['last_name']) ?>
                                        </a>
                                    </div>
                                    <div class="leaderboard-score"><?= round($performer['score']) ?> pts</div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-performers">No performance data available</div>
                        <?php endif; ?>

                        <div class="text-end mt-2">
                            <a href="#" class="text-primary">View Full Ranking →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- <script> const departmentData = <?php echo json_encode($dept_data); ?>; </script> -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let performanceChart;
            const rankFilter = document.getElementById('rankFilter');
            const noDataMessage = document.getElementById('noDataMessage');

            // Load data for the DEFAULT selected rank (e.g., "Professor")
            fetchAndUpdateChart(rankFilter.value);

            // Update chart when rank changes
            rankFilter.addEventListener('change', function() {
                fetchAndUpdateChart(this.value);
            });

            function fetchAndUpdateChart(rank) {
                console.log(`Fetching data for rank: ${rank}`); // Debugging
                fetch(`get_performance_data.php?rank=${encodeURIComponent(rank)}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        if (data.error || !data.length) {
                            noDataMessage.style.display = 'block';
                            if (performanceChart) performanceChart.destroy();
                            return;
                        }
                        noDataMessage.style.display = 'none';
                        updateChart(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        noDataMessage.style.display = 'block';
                        if (performanceChart) performanceChart.destroy();
                    });
            }

            function updateChart(staffData) {
                const ctx = document.getElementById('performanceChart').getContext('2d');
                if (performanceChart) performanceChart.destroy();

                performanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: staffData.map(staff => staff.name),
                        datasets: [{
                                label: 'Experience (years)',
                                data: staffData.map(staff => staff.experience),
                                backgroundColor: 'rgba(54, 162, 235, 0.5)'
                            },
                            {
                                label: 'Publications',
                                data: staffData.map(staff => staff.publications),
                                backgroundColor: 'rgba(255, 99, 132, 0.5)'
                            },
                            {
                                label: 'Grants',
                                data: staffData.map(staff => staff.grants),
                                backgroundColor: 'rgba(75, 192, 192, 0.5)'
                            }
                        ]
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
            }
        })

        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        const trendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: ['2019', '2020', '2021', '2022', '2023'],
                datasets: [{
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
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const grantsCtx = document.getElementById('grantsChart').getContext('2d');
            const grantsChart = new Chart(grantsCtx, {
                type: 'pie',
                data: {
                    labels: ['>1B UGX', '500M-1B UGX', '100M-500M UGX', '<100M UGX'],
                    datasets: [{
                        data: <?php echo json_encode($grants_pie); ?>,
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
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} project${context.raw !== 1 ? 's' : ''}`;
                                }
                            }
                        }
                    }
                }
            });
        });

        const pubTypesCtx = document.getElementById('publicationTypesChart').getContext('2d');
        const pubTypesChart = new Chart(pubTypesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Journal Articles', 'Book Chapters', 'Books with isbn'],
                datasets: [{
                    data: <?php echo json_encode($publication_types); ?>,
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });


        // Pass PHP data to JavaScript
        const chartData = <?php echo json_encode($chartData); ?>;

        // Initialize the chart when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const citationsCtx = document.getElementById('citationsChart').getContext('2d');
            const citationsChart = new Chart(citationsCtx, {
                type: 'bar',
                data: {
                    labels: chartData.years,
                    datasets: [{
                            label: 'Publications',
                            data: chartData.publications,
                            backgroundColor: '#3498db',
                            borderColor: '#2980b9',
                            borderWidth: 1
                        },
                        {
                            label: 'Citations',
                            data: chartData.citations,
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
                        x: {
                            title: {
                                display: true,
                                text: 'Year'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}`;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Function to update chart if needed
            window.updateChartData = function(newData) {
                citationsChart.data.labels = newData.years;
                citationsChart.data.datasets[0].data = newData.publications;
                citationsChart.data.datasets[1].data = newData.citations;
                citationsChart.update();
            };
        });

        const researchGrantsCtx = document.getElementById('researchGrantsChart').getContext('2d');
        const researchGrantsChart = new Chart(researchGrantsCtx, {
            type: 'bar',
            data: {
                labels: ['Grants >1B', 'Grants 500M-1B', 'Grants 100M-500M', 'Grants <100M'],
                datasets: [{
                    label: 'Grant Amount',
                    data: <?php echo json_encode($grants_pie); ?>,
                    backgroundColor: 'rgba(46, 204, 113, 0.7)',
                    borderColor: '#2ecc71',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
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
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });

        const supervisionCtx = document.getElementById('supervisionChart').getContext('2d');
        const supervisionChart = new Chart(supervisionCtx, {
            type: 'bar',
            data: {
                labels: ['PhD Completions', 'Masters Completions'],
                datasets: [{
                    label: 'Supervision',
                    data: <?php echo json_encode($postgraduate_supervisions); ?>,
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',

                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const innovationCtx = document.getElementById('innovationTypesChart').getContext('2d');
        const innovationChart = new Chart(innovationCtx, {
            type: 'pie',
            data: {
                labels: ['Patent', 'Product', 'Copyright', 'Utility Model', 'Trademark'],
                datasets: [{
                    data: <?php echo json_encode($dept_innovations); ?>,
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
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${percentage}%`;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


        const ctx = document.getElementById('communityServiceChart').getContext('2d');
        const communityServiceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Industrial Placements', 'Outreach Programs', 'Beneficiaries'],
                datasets: [{
                    label: 'Community Service Metrics',
                    data: <?php echo json_encode($community_service_scores); ?>, // These should match your stat card values
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
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
                            text: 'Count'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Service Type'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>