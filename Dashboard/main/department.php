<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ini_set('display_errors', 1);
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
        $dept_staff_count = get_department_staff_count($conn, $department_id );


        }else {
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
    <style>  </style>
<link rel="stylesheet" href="styles/department_styles.css">
<link rel="stylesheet" href="styles/individual_style.css">

<!-- <link rel="stylesheet" href="styles/individual_style.css">
<link rel="stylesheet" href="styles/individual_style.css"> -->
</head>
<body>
    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- sidebar -->
    <?php //include 'bars/side_bar.php'; ?>
    
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
                        <span class="badge bg-success me-1">Books with ISBN : <?= $dept_data['Book with ISBN']?></span>
                        <span class="badge bg-success">Book chapters : <?= $dept_data['Book Chapter']?></span>
                        <span class="badge bg-success me-1">Journal Articles : <?= $dept_data['Journal Articles (Co-author)'] + $dept_data['Journal Articles (Corresponding Author)'] + $dept_data['Journal Articles (First Author)']?></span>
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
                <div class="row mb-4">
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
                </div>
                
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
                            <div class="stat-value">72</div>
                            <div class="stat-label" style="font-size: 15px; font-weight: bold;">Total Number of Citations</div>
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
                            <div class="stat-value">25</div>
                            <div class="stat-label" style="font-size: 15px; font-weight: bold;">Phd's</div>
                        </div>
                    </div>            
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-value">45</div>
                            <div class="stat-label" style="font-size: 15px; font-weight: bold;">Masters</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-value">34</div>
                            <div class="stat-label" style="font-size: 15px; font-weight: bold;">First class</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-value">72</div>
                            <div class="stat-label" style="font-size: 15px; font-weight: bold;">Second Class</div>
                        </div>
                    </div>
                </div>

                <!-- Staff Academic Qualifications Section -->
                <div class="row">
                    <div class="col-12">
                        <!-- Professors -->
                        <div class="rank-section">
                            <div class="rank-header">
                                <span>Professors</span>
                                <span class="rank-count">8 staff</span>
                            </div>
                            <table class="qualifications-table">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Qualifications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="staff-name">Prof. John Smith</td>
                                        <td>
                                            <span class="qualification-badge phd counted" data-count="2">PhD</span>
                                            <span class="qualification-badge first-class">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Prof. Alice Johnson</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge masters counted" data-count="2">Masters</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Prof. Robert Brown</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge first-class">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Prof. Emily Wilson</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge second-class">Second Class</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="#" class="more-link">View all 8 professors →</a>
                        </div>
                        
                        <!-- Associate Professors -->
                        <div class="rank-section">
                            <div class="rank-header">
                                <span>Associate Professors</span>
                                <span class="rank-count">5 staff</span>
                            </div>
                            <table class="qualifications-table">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Qualifications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="staff-name">Assoc. Prof. David Lee</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge first-class counted" data-count="2">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Assoc. Prof. Sarah Miller</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge masters">Masters</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Assoc. Prof. James Taylor</td>
                                        <td>
                                            <span class="qualification-badge phd counted" data-count="3">PhD</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="#" class="more-link">View all 5 associate professors →</a>
                        </div>
                        
                        <!-- Senior Lecturers -->
                        <div class="rank-section">
                            <div class="rank-header">
                                <span>Senior Lecturers</span>
                                <span class="rank-count">10 staff</span>
                            </div>
                            <table class="qualifications-table">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Qualifications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="staff-name">Dr. Michael Clark</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge first-class">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Dr. Patricia Adams</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge masters counted" data-count="2">Masters</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Dr. Richard Evans</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                            <span class="qualification-badge second-class">Second Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Dr. Lisa Wong</td>
                                        <td>
                                            <span class="qualification-badge first-class counted" data-count="3">First Class</span>
                                            <span class="qualification-badge second-class">Second Class</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="#" class="more-link">View all 10 senior lecturers →</a>
                        </div>
                        
                        <!-- Lecturers -->
                        <div class="rank-section">
                            <div class="rank-header">
                                <span>Lecturers</span>
                                <span class="rank-count">12 staff</span>
                            </div>
                            <table class="qualifications-table">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Qualifications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="staff-name">Dr. Kevin White</td>
                                        <td>
                                            <span class="qualification-badge phd">PhD</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Dr. Lisa Green</td>
                                        <td>
                                            <span class="qualification-badge first-class counted" data-count="2">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Mr. Daniel King</td>
                                        <td>
                                            <span class="qualification-badge masters">Masters</span>
                                            <span class="qualification-badge first-class">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Ms. Rachel Young</td>
                                        <td>
                                            <span class="qualification-badge masters counted" data-count="2">Masters</span>
                                            <span class="qualification-badge second-class">Second Class</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="#" class="more-link">View all 12 lecturers →</a>
                        </div>
                        
                        <!-- Assistant Lecturers -->
                        <div class="rank-section">
                            <div class="rank-header">
                                <span>Assistant Lecturers</span>
                                <span class="rank-count">5 staff</span>
                            </div>
                            <table class="qualifications-table">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Qualifications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="staff-name">Ms. Angela Scott</td>
                                        <td>
                                            <span class="qualification-badge first-class">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Mr. Brian Hill</td>
                                        <td>
                                            <span class="qualification-badge first-class">First Class</span>
                                            <span class="qualification-badge second-class">Second Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Ms. Rachel Young</td>
                                        <td>
                                            <span class="qualification-badge masters">Masters</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="#" class="more-link">View all 5 assistant lecturers →</a>
                        </div>
                        
                        <!-- Teaching Assistants -->
                        <div class="rank-section">
                            <div class="rank-header">
                                <span>Teaching Assistants</span>
                                <span class="rank-count">8 staff</span>
                            </div>
                            <table class="qualifications-table">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Qualifications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="staff-name">Mr. Jason Wright</td>
                                        <td>
                                            <span class="qualification-badge first-class">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Ms. Olivia Carter</td>
                                        <td>
                                            <span class="qualification-badge masters">Masters</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Mr. Ethan Walker</td>
                                        <td>
                                            <span class="qualification-badge first-class counted" data-count="2">First Class</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="staff-name">Ms. Sophia Chen</td>
                                        <td>
                                            <span class="qualification-badge second-class counted" data-count="2">Second Class</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="#" class="more-link">View all 8 teaching assistants →</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- community service -->
            <div class="tab-pane fade" id="communityservice" role="tabpanel" aria-labelledby="communityservice-tab">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-value">25</div>
                            <div class="stat-label" style="font-size: 15px; font-weight: bold;">Industrial Placement Supervision</div>
                        </div>
                    </div>            
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-value">45</div>
                            <div class="stat-label" style="font-size: 15px; font-weight: bold;">Community Outreach Programs</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-value">34</div>
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
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">1</div>
                        <div class="leaderboard-name">Dr. Jane Smith</div>
                        <div class="leaderboard-score">92 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">2</div>
                        <div class="leaderboard-name">Prof. John Doe</div>
                        <div class="leaderboard-score">88 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">3</div>
                        <div class="leaderboard-name">Dr. Alice Johnson</div>
                        <div class="leaderboard-score">85 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">4</div>
                        <div class="leaderboard-name">Dr. Robert Brown</div>
                        <div class="leaderboard-score">80 pts</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="leaderboard-rank">5</div>
                        <div class="leaderboard-name">Dr. Emily Wilson</div>
                        <div class="leaderboard-score">78 pts</div>
                    </div>
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
        // Pass PHP data to JavaScript
        // const departmentData = <?php// echo json_encode($dept_data); ?>;


         // Academic staff data
        // const academicStaff = [
        //     { name: 'Prof. A', rank: 'professor', experience: 12, publications: 10, grants: 8 },
        //     { name: 'Prof. B', rank: 'professor', experience: 8, publications: 9, grants: 6 },
        //     { name: 'Dr. C', rank: 'associate', experience: 6, publications: 8, grants: 4 },
        //     { name: 'Dr. C', rank: 'associate', experience: 6, publications: 8, grants: 4 },
        //     { name: 'Dr. D', rank: 'senior', experience: 5, publications: 7, grants: 3 },
        //     { name: 'Dr. E', rank: 'lecturer', experience: 4, publications: 6, grants: 2 },
        //     { name: 'Dr. F', rank: 'assistant', experience: 3, publications: 5, grants: 1 },
        //     { name: 'Lect. G', rank: 'teaching', experience: 2, publications: 4, grants: 0 }
        // ];

        // // Filter staff by rank
        // function filterDataByRank(rank) {
        //     return academicStaff.filter(staff => staff.rank === rank);
        // }

        // // Chart setup
        // const chartCanvas = document.getElementById('performanceChart');
        // const noDataMessage = document.getElementById('noDataMessage');
        // let performanceChart;

        // function initializeChart(data) {
        //     if (performanceChart) {
        //         performanceChart.destroy();
        //     }

        //     if (data.length === 0) {
        //         chartCanvas.style.display = 'none';
        //         noDataMessage.style.display = 'block';
        //         return;
        //     }

        //     chartCanvas.style.display = 'block';
        //     noDataMessage.style.display = 'none';

        //     const ctx = chartCanvas.getContext('2d');
        //     performanceChart = new Chart(ctx, {
        //         type: 'bar',
        //         data: {
        //             labels: data.map(staff => staff.name),
        //             datasets: [
        //                 {
        //                     label: 'Years of Experience',
        //                     data: data.map(staff => staff.experience),
        //                     backgroundColor: '#3498db',
        //                     barPercentage: 0.6
        //                 },
        //                 {
        //                     label: 'Publications',
        //                     data: data.map(staff => staff.publications),
        //                     backgroundColor: '#2ecc71',
        //                     barPercentage: 0.6
        //                 },
        //                 {
        //                     label: 'Grants Won',
        //                     data: data.map(staff => staff.grants),
        //                     backgroundColor: '#e74c3c',
        //                     barPercentage: 0.6
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: true,
        //             maintainAspectRatio: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'top',
        //                 }
        //             },
        //             scales: {
        //                 x: {
        //                     stacked: false,
        //                     grid: {
        //                         display: false
        //                     }
        //                 },
        //                 y: {
        //                     beginAtZero: true,
        //                     grid: {
        //                         color: '#f5f5f5'
        //                     }
        //                 }
        //             }
        //         }
        //     });
        // }

        // // Initial chart load
        // initializeChart(filterDataByRank('professor'));

        // // Update chart on rank selection
        // document.getElementById('rankFilter').addEventListener('change', function () {
        //     const selectedRank = this.value;
        //     const filteredData = filterDataByRank(selectedRank);
        //     initializeChart(filteredData);
        // });

        

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
                    datasets: [
                        {
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
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    })

        //DRAFT                     DRAFT
    //     document.addEventListener('DOMContentLoaded', function () {
    //     const rankSelect = document.getElementById('rankFilter');
    //     const noDataMessage = document.getElementById('noDataMessage');
    //     const ctx = document.getElementById('performanceChart').getContext('2d');
    //     let chartInstance;

    //     // 🔍 Get department_id from URL
    //     const urlParams = new URLSearchParams(window.location.search);
    //     const departmentId = urlParams.get('id');

    //     function fetchAndRenderChart(rank) {
    //         fetch(`get_performance_data.php?rank=${encodeURIComponent(rank)}&department_id=${encodeURIComponent(departmentId)}`)
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (!Array.isArray(data) || data.length === 0) {
    //                     noDataMessage.style.display = 'block';
    //                     if (chartInstance) chartInstance.destroy();
    //                     return;
    //                 }

    //                 noDataMessage.style.display = 'none';

    //                 const labels = data.map(staff => staff.name);
    //                 const experience = data.map(staff => staff.experience);
    //                 const publications = data.map(staff => staff.publications);
    //                 const grants = data.map(staff => staff.grants);

    //                 if (chartInstance) {
    //                     chartInstance.destroy();
    //                 }

    //                 chartInstance = new Chart(ctx, {
    //                     type: 'bar',
    //                     data: {
    //                         labels: labels,
    //                         datasets: [
    //                             {
    //                                 label: 'Experience',
    //                                 data: experience,
    //                                 backgroundColor: '#3498db'
    //                             },
    //                             {
    //                                 label: 'Publications',
    //                                 data: publications,
    //                                 backgroundColor: '#2ecc71'
    //                             },
    //                             {
    //                                 label: 'Grants',
    //                                 data: grants,
    //                                 backgroundColor: '#e74c3c'
    //                             }
    //                         ]
    //                     },
    //                     options: {
    //                         responsive: true,
    //                         plugins: {
    //                             title: {
    //                                 display: true,
    //                                 text: 'Staff Performance by Rank & Department'
    //                             }
    //                         },
    //                         scales: {
    //                             y: {
    //                                 beginAtZero: true
    //                             }
    //                         }
    //                     }
    //                 });
    //             })
    //             .catch(error => {
    //                 console.error('Error fetching performance data:', error);
    //             });
    //     }

    //     // Load chart on page load
    //     fetchAndRenderChart(rankSelect.value);

    //     // Update chart on rank change
    //     rankSelect.addEventListener('change', function () {
    //         fetchAndRenderChart(this.value);
    //     });
    // });


        


    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        const trendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: ['2019', '2020', '2021', '2022', '2023'],
                datasets: [
                    {
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

        const citationsCtx = document.getElementById('citationsChart').getContext('2d');
        const citationsChart = new Chart(citationsCtx, {
            type: 'bar',
            data: {
                labels: ['2021', '2022', '2023', '2024', '2025'],
                datasets: [
                    {
                        label: 'Publications',
                        data: [12, 8, 6, 5, 4],
                        backgroundColor: '#3498db',
                    },
                    {
                        label: 'Citations',
                        data: [120, 85, 45, 30, 25],
                        backgroundColor: '#2ecc71',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        const researchGrantsCtx = document.getElementById('researchGrantsChart').getContext('2d');
        const researchGrantsChart = new Chart(researchGrantsCtx, {
            type: 'bar',
            data: {
                labels: ['Grants >1B', 'Grants 500M-1B', 'Grants 100M-500M', 'Grants <100M'],
                datasets: [
                    {
                        label: 'Grant Amount',
                        data: <?php echo json_encode($grants_pie); ?>,
                        backgroundColor: 'rgba(46, 204, 113, 0.7)',
                        borderColor: '#2ecc71',
                        borderWidth: 1
                    }
                ]
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
                    data: [25, 45, 34], // These should match your stat card values
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