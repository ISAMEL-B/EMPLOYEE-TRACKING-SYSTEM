
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();
    // Check if user is NOT logged in OR not HRM
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
        header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
        exit();
    }

    //department performance
    // include '../../scoring_calculator/department score/department_score.php';

    //get faculty score
    include '../../scoring_calculator/faculty score/faculty_score.php';

    

    //get count of employees in a faculty
    include '../../scoring_calculator/faculty score/faculty_employees.php';

    $faculty_id = $_GET['faculty_id'] ?? null;

    if ($faculty_id) {
        $faculty_data = get_faculty_performance($conn, $faculty_id);
        $faculty_name = get_faculty_name($conn, $faculty_id);
    } else {
        // Fallback
        die("No faculty selected.");
    }

    // total publications
    $totalPublications = $faculty_data['Journal Articles (First Author)'] + 
                         $faculty_data['Journal Articles (Co-author)'] + 
                         $faculty_data['Journal Articles (Corresponding Author)'] +
                         $faculty_data['Book Chapter'] +
                         $faculty_data['Book with ISBN'];

    // total grants
    $totalGrants = $faculty_data['total_grant_amount'];

    // total innovations
    $totalInnovations = $faculty_data['Patent'] + 
    $faculty_data['Utility Model'] +
    $faculty_data['Copyright'] +
    $faculty_data['Product'] +
    $faculty_data['Trademark'];

    //total employees
    $total_employees = getTotalEmployeesByFaculty($faculty_id, $conn);


    /// graphs
        //overview section
        //departmental scores graph
        $facOverview_chart_data = [];

        $stmt = $conn->prepare("SELECT department_id FROM departments WHERE faculty_id = ?");
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $facOverview_dept_id = $row['department_id'];
            $facOverview_dept_name = get_department_name($conn, $facOverview_dept_id);
            $facOverview_scores = get_department_performance($conn, $facOverview_dept_id);

            $facOverview_chart_data[] = [
                'department' => $facOverview_dept_name,
                'total_score' => round($facOverview_scores['total_score'], 2)
            ];
        }


        // academic performance (academic performance tab)
        //quick stats
        $phdHolders = $faculty_data['PhD'];
        $mastersHolders = $faculty_data['Masters'];
        $firstClass = $faculty_data['First Class'];
        $secondUpper = $faculty_data['Second Upper'];

        //academic performance comparison
        $academicData = [];

        $stmt = $conn->prepare("SELECT department_id FROM departments WHERE faculty_id = ?");
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $department_id = $row['department_id'];
            $department_name = get_department_name($conn, $department_id); // make sure this is $department_name
            $scores = get_department_performance($conn, $department_id); // also rename for clarity
        
            $academicData[] = [
                'department' => $department_name,
                'PhD' => $scores['PhD'],
                'Masters' => $scores['Masters'], 
                'First Class' => $scores['First Class'],
                'Second Upper' => $scores['Second Upper'],
            ];
        }
    
        //comparison
        $keyMetricsData = [];

        $stmt = $conn->prepare("SELECT department_id FROM departments WHERE faculty_id = ?");
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $department_id = $row['department_id'];
            $department_name = get_department_name($conn, $department_id);

            // Get detailed metrics from department performance function
            $scores = get_department_performance($conn, $department_id);

            $publications = isset($scores['total_publications']) ? $scores['total_publications'] : 0;
            $grants = isset($scores['total_grant_amount']) ? round($scores['total_grant_amount'] / 1000000000, 1) : 0;
            $supervisions = (isset($scores['Masters Supervised']) ? $scores['Masters Supervised'] : 0)
                        + (isset($scores['PhD Supervised']) ? $scores['PhD Supervised'] : 0);
            $innovations = isset($scores['total_innovations']) ? $scores['total_innovations'] : 0;

            $keyMetricsData[] = [
                'department' => $department_name,
                'publications' => $publications,
                'grants' => $grants,
                'supervisions' => $supervisions,
                'innovations' => $innovations,
            ];
        }

        // publications
        $publicationTypeData = [];

        $stmt = $conn->prepare("SELECT department_id FROM departments WHERE faculty_id = ?");
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $dept_id = $row['department_id'];
            $dept_name = get_department_name($conn, $dept_id);
            $pub_scores = get_department_performance($conn, $dept_id);

            $publicationTypeData[] = [
                'department' => $dept_name,
                'journal_articles' => 
                    (isset($pub_scores['Journal Articles (First Author)']) ? $pub_scores['Journal Articles (First Author)'] : 0) +
                    (isset($pub_scores['Journal Articles (Corresponding Author)']) ? $pub_scores['Journal Articles (Corresponding Author)'] : 0) +
                    (isset($pub_scores['Journal Articles (Co-author)']) ? $pub_scores['Journal Articles (Co-author)'] : 0),
                
                'book_chapters' => isset($pub_scores['Book Chapter']) ? $pub_scores['Book Chapter'] : 0,
                
                'books' => isset($pub_scores['Book with ISBN']) ? $pub_scores['Book with ISBN'] : 0
            ];
            
        }

        //innovations
        $innovationData = [];

        $stmt = $conn->prepare("SELECT department_id FROM departments WHERE faculty_id = ?");
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $dept_id = $row['department_id'];
            $dept_name = get_department_name($conn, $dept_id);
            $innovation_scores = get_department_performance($conn, $dept_id);

            $innovationData[] = [
                'department' => $dept_name,
                'patents' => isset($innovation_scores['Patent']) ? $innovation_scores['Patent'] : 0,
                'utility_models' => isset($innovation_scores['Utility Model']) ? $innovation_scores['Utility Model'] : 0,
                'copyrights' => isset($innovation_scores['Copyright']) ? $innovation_scores['Copyright'] : 0,
                'products' => isset($innovation_scores['Product']) ? $innovation_scores['Product'] : 0,
                'trademarks' => isset($innovation_scores['Trademark']) ? $innovation_scores['Trademark'] : 0
            ];
        }

        // community service
        $communityData = [];

        $stmt = $conn->prepare("SELECT department_id FROM departments WHERE faculty_id = ?");
        $stmt->bind_param("i", $faculty_id);
        $stmt->execute();
        $deptResult = $stmt->get_result();

        while ($deptRow = $deptResult->fetch_assoc()) {
            $dept_id = $deptRow['department_id'];
            $dept_name = get_department_name($conn, $dept_id);

            $studentsSupervised = 0;
            $communityOutreaches = 0;
            $totalBeneficiaries = 0;

            // Get all staff in this department
            $staffStmt = $conn->prepare("SELECT staff_id FROM staff WHERE department_id = ?");
            $staffStmt->bind_param("i", $dept_id);
            $staffStmt->execute();
            $staffResult = $staffStmt->get_result();

            while ($staffRow = $staffResult->fetch_assoc()) {
                $staff_id = $staffRow['staff_id'];

                // Get community services for this staff
                $commStmt = $conn->prepare("SELECT description, beneficiaries FROM communityservice WHERE staff_id = ?");
                $commStmt->bind_param("i", $staff_id);
                $commStmt->execute();
                $commResult = $commStmt->get_result();

                while ($commRow = $commResult->fetch_assoc()) {
                    $serviceType = strtolower(trim($commRow['description']));
                    $beneficiaries = intval($commRow['beneficiaries']);

                    if ($serviceType === 'student supervision') {
                        $studentsSupervised++;
                    } else {
                        $communityOutreaches++;
                    }

                    $totalBeneficiaries += $beneficiaries;
                }
                $commStmt->close();
            }
            $staffStmt->close();

            $communityData[] = [
                'department' => $dept_name,
                'students_supervised' => $studentsSupervised,
                'community_outreaches' => $communityOutreaches,
                'total_beneficiaries' => $totalBeneficiaries
            ];

            //get to total community performance for the whole faculty
            $totalStudentsSupervised = 0;
            $totalCommunityOutreaches = 0;
            $totalBeneficiaries = 0;

            foreach ($communityData as $entry) {
                $totalStudentsSupervised += $entry['students_supervised'];
                $totalCommunityOutreaches += $entry['community_outreaches'];
                $totalBeneficiaries += $entry['total_beneficiaries'];
            }

        }



    ?>

    
            


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University - Faculty Performance Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            padding: 20px;
        }

        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            color: var(--dark-color);
        }

        .faculty-selector {
            padding: 8px 15px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            background-color: white;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
        }

        .card h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
            font-size: 16px;
        }

        .card .value {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .card .subtext {
            color: #777;
            font-size: 14px;
        }

        .card.trend-up .value {
            color: var(--secondary-color);
        }

        .card.trend-down .value {
            color: var(--accent-color);
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .large-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            height: auto;
            margin-bottom: 20px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .section-title {
            margin-bottom: 15px;
            color: var(--dark-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .department-comparison {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
        }

        .department-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .department-table th, .department-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .department-table th {
            background-color: #f8f9fa;
            color: var(--dark-color);
        }

        .department-table tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-primary {
            background-color: #d4edff;
            color: var(--primary-color);
        }

        .badge-success {
            background-color: #d4f5e0;
            color: var(--secondary-color);
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #ffc107;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: var(--accent-color);
        }

        .footer-notes {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .tab-container {
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .tab {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tab.active {
            border-bottom: 3px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .view-details {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .view-details:hover {
            background-color: rgba(52, 152, 219, 0.1);
            text-decoration: underline;
        }

        .department-table th:nth-child(7),
        .department-table td:nth-child(7) {
            text-align: center;
        }

        /* Improved Stat Cards */
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-top: 4px solid var(--primary-color);
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .stat-card .value {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--dark-color);
            margin: 10px 0 5px;
        }

        .stat-card .subtext {
            color: #666;
            font-size: 0.9rem;
        }

        /* Color variations for stat cards */
        .summary-cards .stat-card:nth-child(1) {
            border-top-color: #3498db;
        }
        .summary-cards .stat-card:nth-child(2) {
            border-top-color: #2ecc71;
        }
        .summary-cards .stat-card:nth-child(3) {
            border-top-color: #e74c3c;
        }
        .summary-cards .stat-card:nth-child(4) {
            border-top-color: #f39c12;
        }

        @media (max-width: 1200px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <link rel="stylesheet" href="styles/individual_style.css">

</head>
<body>

    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; 
    $current_ur = 'index2.php';
    $current_pag = 'index2';
    ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>
    
    <div class="content-wrapper">
        <div class="dashboard">
            <div class="explorer-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 fw-bold">Faculty of <?= htmlspecialchars($faculty_name) ?></h1>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <i class="fas fa-trophy fa-4x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-3" style="text-align: right;">
                <a href="index2.php" 
                style="display: inline-block; 
                        padding: 8px 16px;
                        background-color: #3498db;
                        color: white;
                        text-decoration: none;
                        border-radius: 4px;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        padding-bottom: 3px;">
                    <i class="fas fa-arrow-left me-2"></i>Back to Faculty Dashboard
                </a>
            </div>

            <div class="summary-cards">
                <div class="card">
                    <h3>Total Staff</h3>
                    <div class="value"><?= $total_employees ?></div>
                </div>
                <div class="card trend-up">
                    <h3>Total publications</h3>
                    <div class="value"><?= $totalPublications; ?></div>
                </div>
                <div class="card trend-up">
                    <h3>Research Grants</h3>
                    <div class="value"><?= 'UGX ' . number_format($faculty_data['total_grant_amount'] / 1_000_000_000) . 'B' ?></div>
                </div>
                <div class="card trend-down">
                    <h3>Total Innovations</h3>
                    <div class="value"><?= $totalInnovations?></div>
                </div>
            </div>

            <div class="tab-container">
                <div class="tabs">
                    <div class="tab active" onclick="switchTab('overview')">Overview</div>
                    <div class="tab" onclick="switchTab('academic')">Academic Performance</div>
                    <div class="tab" onclick="switchTab('publications')">Publications</div>
                    <div class="tab" onclick="switchTab('research')">Research & Innovations</div>
                    <div class="tab" onclick="switchTab('community')">Community Service</div>
                </div>
            </div>

            <!-- Overview Tab Content -->
            <div class="tab-content active" id="overview">
                <div class="main-content">
                    <div class="large-card">
                        <div class="large-card">
                            <div class="section-title">
                                <h2>Faculty Overview</h2>
                            </div>
                            <div class="chart-container">
                            <canvas id="facultyOverviewChart"></canvas>
                        </div>
                        </div>
                        
                    </div>
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Key Metrics Summary</h2>
                        </div>
                        <div class="chart-container" style="height: 400px; width: 100%;">
                            <canvas id="keyMetricsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance Tab Content -->
            <div class="tab-content" id="academic">
                <div class="main-content">
                    <!-- Top Row: 4 Stat Cards -->
                    <div class="summary-cards">
                        <div class="card stat-card">
                            <h3>PhD Holders</h3>
                            <div class="value"><?= $phdHolders?></div>
                        </div>
                        <div class="card stat-card">
                            <h3>Masters</h3>
                            <div class="value"><?= $mastersHolders ?></div>
                        </div>
                        <div class="card stat-card">
                            <h3>First Class</h3>
                            <div class="value"><?= $firstClass ?></div>
                        </div>
                        <div class="card stat-card">
                            <h3>Second Class</h3>
                            <div class="value"><?php echo $secondUpper ?></div>
                        </div>
                    </div>

                    <!-- Single Chart Card for Departmental Qualifications -->
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Qualifications by Department</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="qualificationsByDeptChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publications Tab Content -->
            <div class="tab-content" id="publications">
                <div class="main-content">
                <!-- status cards -->
                <!-- <div class="summary-cards"style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    <div class="card stat-card" style="width: 300px;">
                        <h3>Total peer reviewed publications</h3>
                        <div class="value">34</div>
                        
                    </div>
                    <div class="card stat-card" style="width: 300px;">
                        <h3>Total number of citations</h3>
                        <div class="value">72</div>
                        
                    </div>
                    <div class="card stat-card" style="width: 300px;">
                        <h3>Total peer reviewed publications uploaded to must repository</h3>
                        <div class="value">72</div>
                        
                    </div>

                </div> -->
                    <!-- <div class="large-card">
                        <div class="section-title">
                            <h2>Publications vs Citations</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="pubCitationChart"></canvas>
                        </div>
                    </div> -->
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Publication Types By Department</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="publicationTypesChart"></canvas>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Research & Innovations Tab Content -->
            <div class="tab-content" id="research">
                <div class="main-content">
                    <!-- <div class="large-card">
                        <div class="section-title">
                            <h2>Research Grants</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="grantTrendChart"></canvas>
                        </div>
                    </div> -->
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Innovations</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="innovationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Service Tab Content -->
            <div class="tab-content" id="community">
                <div class="main-content">
                    <!-- status cards -->
                    <div class="summary-cards"style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                        <div class="card stat-card" style="width: 300px;">
                            <h3>Total student supervised for community placements</h3>
                            <div class="value"><?= $totalStudentsSupervised?></div>
                            
                        </div>
                        <div class="card stat-card" style="width: 300px;">
                            <h3>other community outreaches</h3>
                            <div class="value"><?= $totalCommunityOutreaches ?></div>
                            
                        </div>
                        <div class="card stat-card" style="width: 300px;">
                            <h3> total number of beneficiaries</h3>
                            <div class="value"><?= $totalBeneficiaries ?></div>
                            
                        </div>
                    </div>
                    <div class="large-card">
                        <div class="section-title">
                            <h2>Community Engagement by department</h2>
                        </div>
                        <div class="chart-container">
                            <canvas id="communityEngagementChart"></canvas>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Department Comparison Section (Visible on all tabs) -->
            <div class="department-comparison">
                <div class="section-title">
                    <h2>Department Metrics</h2>
                </div>
                <table class="department-table">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Total Score</th>
                            <th>Publications</th>
                            <th>Grants (UGX Billions)</th>
                            <th>Innovations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Faculty name row -->
                        <tr>
                            <td colspan="6" style="font-weight: bold; background-color: #f0f0f0;">
                                <?php echo htmlspecialchars($faculty_name); ?>
                            </td>
                        </tr>

                        <?php
                        // Fetch departments under this faculty
                        $dept_stmt = $conn->prepare("SELECT department_id, department_name FROM departments WHERE faculty_id = ?");
                        $dept_stmt->bind_param("i", $faculty_id);
                        $dept_stmt->execute();
                        $dept_result = $dept_stmt->get_result();

                        while ($dept = $dept_result->fetch_assoc()) {
                            $department_id = $dept['department_id'];
                            $department_name = $dept['department_name'];

                            // Get performance data for this department
                            $dept_perf = get_department_performance($conn, $department_id);

                            // Extract values
                            $total_score = $dept_perf['total_score'] ?? 0;
                            $total_publications = $dept_perf['total_publications'] ?? 0;
                            $total_grant_amount = $dept_perf['total_grant_amount'] ?? 0;
                            $grant_billions = number_format($total_grant_amount / 1_000_000_000, 1);
                            $total_innovations = $dept_perf['total_innovations'] ?? 0;

                            echo "<tr>
                                    <td>" . htmlspecialchars($department_name) . "</td>
                                    <td>" . $total_score . "</td>
                                    <td>" . $total_publications . "</td>
                                    <td>" . $grant_billions . "</td>
                                    <td>" . $total_innovations . "</td>
                                    <td><a href='department.php?id=" . urlencode($department_id) . "' class='view-details'>View Details</a></td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>




            </div>

            <div class="footer-notes">
                Data updated: June 2023 | Next review: December 2023
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`.tab[onclick="switchTab('${tabId}')"]`).classList.add('active');
        }

        // Charts initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Faculty Overview Chart
            const facOverviewData = <?php echo json_encode($facOverview_chart_data); ?>;

            const facOverviewLabels = facOverviewData.map(item => item.department);
            const facOverviewScores = facOverviewData.map(item => item.total_score);

            const facOverviewCtx = document.getElementById('facultyOverviewChart').getContext('2d');

            new Chart(facOverviewCtx, {
                type: 'bar',
                data: {
                    labels: facOverviewLabels,
                    datasets: [{
                        label: 'Overall Performance',
                        data: facOverviewScores,
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: 'rgba(41, 128, 185, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Departments',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Score',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        title: {
                            display: true,
                            text: 'Faculty Performance Overview by Department',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        }
                    }
                }
            });



            // Key Metrics Chart
            const keyMetricsChartData = <?php echo json_encode($keyMetricsData); ?>;
            const keyMetricsLabels = keyMetricsChartData.map(item => item.department);
            const keyMetricsPublications = keyMetricsChartData.map(item => item.publications);
            const keyMetricsGrants = keyMetricsChartData.map(item => item.grants);
            const keyMetricsSupervisions = keyMetricsChartData.map(item => item.supervisions);
            const keyMetricsInnovations = keyMetricsChartData.map(item => item.innovations);

            const keyMetricsCtx = document.getElementById('keyMetricsChart').getContext('2d');
            new Chart(keyMetricsCtx, {
                type: 'bar',
                data: {
                    labels: keyMetricsLabels,
                    datasets: [
                        {
                            label: 'Publications',
                            data: keyMetricsPublications,
                            backgroundColor: 'rgba(52, 152, 219, 0.8)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Grants',
                            data: keyMetricsGrants,
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Postgraduate Supervisions',
                            data: keyMetricsSupervisions,
                            backgroundColor: 'rgba(155, 89, 182, 0.8)',
                            borderColor: 'rgba(155, 89, 182, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Innovations',
                            data: keyMetricsInnovations,
                            backgroundColor: 'rgba(255, 193, 7, 0.8)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 20,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toFixed(1);
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Department Performance by Metric',
                            font: { size: 16, weight: 'bold' },
                            padding: { bottom: 20 }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Department',
                                font: { weight: 'bold', size: 16 }
                            },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 10,
                            ticks: { stepSize: 2 },
                            title: {
                                display: true,
                                text: 'Score',
                                font: { weight: 'bold', size: 16 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });

            //Qualifications by Department Chart
            const academicData = <?php echo json_encode($academicData); ?>;
            const labels = academicData.map(item => item.department);
            const phdData = academicData.map(item => item.PhD);
            const mastersData = academicData.map(item => item.Masters);
            const firstClassData = academicData.map(item => item['First Class']);
            const secondUpperData = academicData.map(item => item['Second Upper']);

            const qualDeptCtx = document.getElementById('qualificationsByDeptChart').getContext('2d');
            new Chart(qualDeptCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'PhD',
                            data: phdData,
                            backgroundColor: 'rgba(52, 152, 219, 0.8)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Masters',
                            data: mastersData,
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'First Class',
                            data: firstClassData,
                            backgroundColor: 'rgba(231, 76, 60, 0.8)',
                            borderColor: 'rgba(231, 76, 60, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Second Upper',
                            data: secondUpperData,
                            backgroundColor: 'rgba(241, 196, 15, 0.8)',
                            borderColor: 'rgba(241, 196, 15, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: false,
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Department', 
                                font: { weight: 'bold', size: 16 }
                            }
                            
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number', 
                                font: { weight: 'bold', size: 16 }
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


            // Publications vs Citations Chart
            // const pubCiteCtx = document.getElementById('pubCitationChart').getContext('2d');
            // new Chart(pubCiteCtx, {
            //     type: 'bar',
            //     data: {
            //         labels: ['Medicine', 'Surgery', 'Public Health', 'mental health'],
            //         datasets: [
            //             {
            //                 label: 'Publications',
            //                 data: [12, 10, 6, 10], // Average publications per department
            //                 backgroundColor: 'rgba(52, 152, 219, 0.8)',
            //                 borderColor: 'rgba(52, 152, 219, 1)',
            //                 borderWidth: 1,
            //                 barPercentage: 0.6
            //             },
            //             {
            //                 label: 'Citations',
            //                 data: [45, 32, 12, 34], // Average citations per department
            //                 backgroundColor: 'rgba(231, 76, 60, 0.8)',
            //                 borderColor: 'rgba(231, 76, 60, 1)',
            //                 borderWidth: 1,
            //                 barPercentage: 0.6
            //             }
            //         ]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         scales: {
            //             x: {
            //                 grid: {
            //                     display: false
            //                 },
            //                 title: {
            //                     display: true,
            //                     text: 'Department',
            //                     font: {
            //                         weight: 'bold',
            //                         size: 14
            //                     }
            //                 }
            //             },
            //             y: {
            //                 beginAtZero: true,
            //                 title: {
            //                     display: true,
            //                     text: 'Count',
            //                     font: {
            //                         weight: 'bold',
            //                         size: 14
            //                     }
            //                 },
            //                 grid: {
            //                     color: 'rgba(0, 0, 0, 0.05)'
            //                 }
            //             }
            //         },
            //         plugins: {
            //             legend: {
            //                 position: 'top',
            //                 labels: {
            //                     font: {
            //                         size: 12
            //                     },
            //                     usePointStyle: true,
            //                     boxWidth: 12
            //                 }
            //             },
            //             title: {
            //                 display: true,
            //                 text: 'Publications and Citations by Department',
            //                 font: {
            //                     size: 16,
            //                     weight: 'bold'
            //                 },
            //                 padding: {
            //                     top: 10,
            //                     bottom: 20
            //                 }
            //             },
            //             tooltip: {
            //                 backgroundColor: 'rgba(0, 0, 0, 0.8)',
            //                 titleFont: {
            //                     size: 14,
            //                     weight: 'bold'
            //                 },
            //                 bodyFont: {
            //                     size: 12
            //                 },
            //                 padding: 12,
            //                 cornerRadius: 4,
            //                 displayColors: true,
            //                 callbacks: {
            //                     label: function(context) {
            //                         return context.dataset.label + ': ' + context.raw;
            //                     }
            //                 }
            //             }
            //         },
            //         layout: {
            //             padding: {
            //                 left: 10,
            //                 right: 10,
            //                 top: 10,
            //                 bottom: 10
            //             }
            //         }
            //     }
            // });

            // Publication Types Chart
            const pubTypeChartData = <?php echo json_encode($publicationTypeData); ?>;

            const pubTypeLabels = pubTypeChartData.map(item => item.department);
            const journalArticlesData = pubTypeChartData.map(item => item.journal_articles);
            const bookChaptersData = pubTypeChartData.map(item => item.book_chapters);
            const booksData = pubTypeChartData.map(item => item.books);

            const pubTypesCtx = document.getElementById('publicationTypesChart').getContext('2d');
            new Chart(pubTypesCtx, {
                type: 'bar',
                data: {
                    labels: pubTypeLabels,
                    datasets: [
                        {
                            label: 'Journal Articles',
                            data: journalArticlesData,
                            backgroundColor: 'rgba(52, 152, 219, 0.8)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Book Chapters',
                            data: bookChaptersData,
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Books',
                            data: booksData,
                            backgroundColor: 'rgba(155, 89, 182, 0.8)',
                            borderColor: 'rgba(155, 89, 182, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            title: {
                                display: true,
                                text: 'Department',
                                font: { weight: 'bold', size: 16 },
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number',
                                font: { weight: 'bold', size: 16 },
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 12 },
                                usePointStyle: true
                            }
                        },
                        title: {
                            display: true,
                            font: { size: 16, weight: 'bold' },
                            padding: { bottom: 20 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 4
                        }
                    },
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }
            });

            

            // Grant Trend Chart
            // const grantCtx = document.getElementById('grantTrendChart').getContext('2d');
            // new Chart(grantCtx, {
            //     type: 'line',
            //     data: {
            //         labels: ['2018', '2019', '2020', '2021', '2022', '2023'],
            //         datasets: [
            //             {
            //                 label: 'Medicine',
            //                 data: [500, 650, 800, 950, 1200, 1800],
            //                 borderColor: 'rgba(52, 152, 219, 1)',
            //                 backgroundColor: 'rgba(52, 152, 219, 0.1)',
            //                 borderWidth: 2,
            //                 tension: 0.3
            //             },
            //             {
            //                 label: 'Surgery',
            //                 data: [300, 400, 450, 600, 750, 1200],
            //                 borderColor: 'rgba(46, 204, 113, 1)',
            //                 backgroundColor: 'rgba(46, 204, 113, 0.1)',
            //                 borderWidth: 2,
            //                 tension: 0.3
            //             },
            //             {
            //                 label: 'Public Health',
            //                 data: [100, 150, 200, 250, 300, 500],
            //                 borderColor: 'rgba(231, 76, 60, 1)',
            //                 backgroundColor: 'rgba(231, 76, 60, 0.1)',
            //                 borderWidth: 2,
            //                 tension: 0.3
            //             }
            //         ]
            //     },
            //     options: {
            //         scales: {
            //             y: {
            //                 title: {
            //                     display: true,
            //                     text: 'Grant Amount (Millions UGX)'
            //                 }
            //             }
            //         }
            //     }
            // });

            // Innovations Chart
            const innovationsData = <?php echo json_encode($innovationData); ?>;
            const departmentsLabels_innov = innovationsData.map(item => item.department);

            const patentsData = innovationsData.map(item => item.patents);
            const utilityModelsData = innovationsData.map(item => item.utility_models);
            const copyrightsData = innovationsData.map(item => item.copyrights);
            const productsData = innovationsData.map(item => item.products);
            const trademarksData = innovationsData.map(item => item.trademarks);

            const innovationsCtxChart = document.getElementById('innovationsChart').getContext('2d');

            new Chart(innovationsCtxChart, {
                type: 'bar',
                data: {
                    labels: departmentsLabels_innov,
                    datasets: [
                        {
                            label: 'Patents',
                            data: patentsData,
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Utility Models',
                            data: utilityModelsData,
                            backgroundColor: 'rgba(255, 159, 64, 0.8)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Copyrights',
                            data: copyrightsData,
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Products',
                            data: productsData,
                            backgroundColor: 'rgba(153, 102, 255, 0.8)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Trademarks',
                            data: trademarksData,
                            backgroundColor: 'rgba(255, 99, 132, 0.8)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            title: {
                                display: true,
                                text: 'Department',
                                font: { weight: 'bold', size: 16 },
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number',
                                font: { weight: 'bold', size: 16 },
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 12 },
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        title: {
                            display: true,
                            text: 'Innovations Type by Department',
                            font: { size: 16, weight: 'bold' },
                            padding: { bottom: 20 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            cornerRadius: 4,
                            bodyFont: { size: 12 },
                            titleFont: { size: 14 }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });

            //community service chart
            const communityData = <?php echo json_encode($communityData); ?>;

            const departmentLabelsComm = communityData.map(item => item.department);
            const studentsSupervisedData = communityData.map(item => item.students_supervised);
            const communityOutreachesData = communityData.map(item => item.community_outreaches);
            const totalBeneficiariesData = communityData.map(item => item.total_beneficiaries);

            const ctx = document.getElementById('communityEngagementChart').getContext('2d');
            const communityServiceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: departmentLabelsComm,
                    datasets: [
                        {
                            label: 'Students Supervised',
                            data: studentsSupervisedData,
                            backgroundColor: 'rgba(255, 99, 132, 0.8)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Community Outreaches',
                            data: communityOutreachesData,
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Total Beneficiaries',
                            data: totalBeneficiariesData,
                            backgroundColor: 'rgba(75, 192, 192, 0.8)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            title: {
                                display: true,
                                text: 'Department',
                                font: { weight: 'bold', size: 14 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number',
                                font: { weight: 'bold', size: 14 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 12 },
                                usePointStyle: true,
                                padding: 20,
                                boxWidth: 12
                            }
                        },
                        title: {
                            display: true,
                            text: 'Community Engagement by Department',
                            font: { size: 18, weight: 'bold' },
                            padding: { bottom: 25 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 12,
                            cornerRadius: 6,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            footerFont: { size: 10 }
                        }
                    },
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }
            });
            

            // University Service Chart
            
        });
    </script>
</body>
</html>