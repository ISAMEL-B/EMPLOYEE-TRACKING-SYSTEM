<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

// Check user authorization
if ($_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Initialize variables
$user_id = null;
$user_data = [];
$staff_data = [];
$performance_data = [];
$all_staff = [];

// Fetch all staff for dropdown
$staff_query = $conn->prepare("SELECT s.staff_id, s.first_name, s.last_name, d.department_name 
                             FROM staff s
                             JOIN departments d ON s.department_id = d.department_id
                             ORDER BY s.first_name, s.last_name");
$staff_query->execute();
$staff_result = $staff_query->get_result();
while ($row = $staff_result->fetch_assoc()) {
    $all_staff[] = $row;
}

// Check if a staff member was selected
if (isset($_POST['selected_staff'])) {
    $user_id = $_POST['selected_staff'];
    
    // Get staff details if available
    if (!empty($user_id)) {
        $staff_query = $conn->prepare("SELECT s.*, d.department_name, r.role_name, f.faculty_name
                                     FROM staff s
                                     JOIN departments d ON s.department_id = d.department_id
                                     JOIN roles r ON s.role_id = r.role_id
                                     JOIN faculties f ON d.faculty_id = f.faculty_id
                                     WHERE s.staff_id = ?");
        $staff_query->bind_param("i", $user_id);
        $staff_query->execute();
        $staff_result = $staff_query->get_result();
        $staff_data = $staff_result->fetch_assoc();
        
        // Get user data
        $user_query = $conn->prepare("SELECT * FROM users WHERE staff_id = ?");
        $user_query->bind_param("i", $user_id);
        $user_query->execute();
        $user_result = $user_query->get_result();
        $user_data = $user_result->fetch_assoc();
        
        // Get performance data
        $performance_query = $conn->prepare("SELECT 
                                           (SELECT COUNT(*) FROM publications WHERE staff_id = ?) as publication_count,
                                           (SELECT COUNT(*) FROM degrees WHERE staff_id = ?) as degree_count,
                                           (SELECT COUNT(*) FROM academicactivities WHERE staff_id = ?) as activity_count,
                                           (SELECT COUNT(*) FROM supervision WHERE staff_id = ?) as supervision_count,
                                           (SELECT COUNT(*) FROM communityservice WHERE staff_id = ?) as community_service_count,
                                           (SELECT COUNT(*) FROM grants WHERE staff_id = ?) as grant_count,
                                           (SELECT COUNT(*) FROM innovations WHERE staff_id = ?) as innovation_count,
                                           (SELECT COUNT(*) FROM professionalbodies WHERE staff_id = ?) as professional_body_count");
        $performance_query->bind_param("iiiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
        $performance_query->execute();
        $performance_result = $performance_query->get_result();
        $performance_data = $performance_result->fetch_assoc();
        
        // Get grants data
        $grants_query = $conn->prepare("SELECT SUM(grant_amount) as total_grants FROM grants WHERE staff_id = ?");
        $grants_query->bind_param("i", $user_id);
        $grants_query->execute();
        $grants_result = $grants_query->get_result();
        $grants_data = $grants_result->fetch_assoc();
        
        // Get degrees data
        $degrees_query = $conn->prepare("SELECT * FROM degrees WHERE staff_id = ?");
        $degrees_query->bind_param("i", $user_id);
        $degrees_query->execute();
        $degrees_result = $degrees_query->get_result();
        
        // Get publications data
        $publications_query = $conn->prepare("SELECT * FROM publications WHERE staff_id = ?");
        $publications_query->bind_param("i", $user_id);
        $publications_query->execute();
        $publications_result = $publications_query->get_result();
        
        // Get professional bodies data
        $bodies_query = $conn->prepare("SELECT * FROM professionalbodies WHERE staff_id = ?");
        $bodies_query->bind_param("i", $user_id);
        $bodies_query->execute();
        $bodies_result = $bodies_query->get_result();
        
        // Get supervision data
        $supervision_query = $conn->prepare("SELECT * FROM supervision WHERE staff_id = ?");
        $supervision_query->bind_param("i", $user_id);
        $supervision_query->execute();
        $supervision_result = $supervision_query->get_result();
        
        // Get community service data
        $community_query = $conn->prepare("SELECT * FROM communityservice WHERE staff_id = ?");
        $community_query->bind_param("i", $user_id);
        $community_query->execute();
        $community_result = $community_query->get_result();
    }
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile - MUST HRM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --must-green: #006633;
            --must-yellow: #FFCC00;
            --must-blue: #003366;
            --must-light-green: #e6f2ec;
            --must-light-yellow: #fff9e6;
            --must-light-blue: #e6ecf2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 5%;
            padding: 20px;
            min-height: 100vh;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            font-size: 20px;
            color: var(--must-blue);
            border-radius: 10px 10px 0 0 !important;
        }

        .department-card {
            border-left: 4px solid var(--must-green);
            transition: all 0.3s ease;
        }

        .department-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--must-green);
        }

        .stat-card .stat-label {
            color: var(--must-blue);
            font-weight: 500;
        }

        .highlight-yellow {
            background-color: var(--must-light-yellow);
            border-left: 4px solid var(--must-yellow);
        }

        .highlight-blue {
            background-color: var(--must-light-blue);
            border-left: 4px solid var(--must-blue);
        }

        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .comparison-table th {
            background-color: var(--must-green);
            color: white;
        }

        .department-title {
            color: var(--must-green);
            font-weight: 700;
            border-bottom: 2px solid var(--must-yellow);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .search-box {
            border: 2px solid var(--must-green);
            border-radius: 5px;
        }

        .btn-must {
            background-color: var(--must-green);
            color: white;
        }

        .btn-must:hover {
            background-color: var(--must-blue);
            color: white;
        }

        .profile-img-container {
            width: 150px;
            height: 150px;
            margin: -75px auto 20px;
            border: 5px solid white;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .skill-badge {
            display: inline-block;
            background-color: var(--must-light-green);
            color: var(--must-green);
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
            border-left: 2px solid var(--must-green);
            padding-left: 20px;
            margin-left: -2px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: var(--must-green);
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
</head>
<body>
    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php'; ?>
    <!-- sidebar -->
    <?php include 'bars/side_bar.php'; ?>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header with Staff Selection -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="department-title">Staff Performance Profile</h2>
                <form method="POST" action="" class="d-flex">
                    <select class="form-select select2" name="selected_staff" required style="width: 300px;">
                        <option value="">-- Select Staff Member --</option>
                        <?php foreach ($all_staff as $staff): ?>
                            <option value="<?= $staff['staff_id'] ?>" <?= ($user_id == $staff['staff_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?> 
                                (<?= htmlspecialchars($staff['department_name']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-must ms-2">
                        <i class="fas fa-search me-1"></i> View Profile
                    </button>
                </form>
            </div>

            <?php if (!empty($user_id)): ?>
            <!-- Staff Profile Content -->
            <div class="row">
                <!-- Left Column - Profile Summary -->
                <div class="col-lg-4 mb-4">
                    <!-- Profile Card -->
                    <div class="card">
                        <div class="card-body text-center pt-5">
                            <div class="profile-img-container">
                                <?php if (!empty($user_data['photo_path'])): ?>
                                    <img src="<?= htmlspecialchars($user_data['photo_path']) ?>" class="profile-img" alt="Profile Photo">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                        <i class="fas fa-user fa-4x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h3 class="mt-3 mb-1"><?= htmlspecialchars($staff_data['first_name'] . ' ' . $staff_data['last_name']) ?></h3>
                            <h5 class="text-muted mb-3"><?= htmlspecialchars($staff_data['role_name']) ?></h5>
                            
                            <div class="d-flex justify-content-center mb-3">
                                <div class="mx-2">
                                    <div class="text-muted small">Faculty</div>
                                    <div class="fw-bold"><?= htmlspecialchars($staff_data['faculty_name']) ?></div>
                                </div>
                                <div class="mx-2">
                                    <div class="text-muted small">Department</div>
                                    <div class="fw-bold"><?= htmlspecialchars($staff_data['department_name']) ?></div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="text-start">
                                <p><i class="fas fa-envelope me-2 text-muted"></i> <?= htmlspecialchars($user_data['email'] ?? 'N/A') ?></p>
                                <p><i class="fas fa-id-card me-2 text-muted"></i> <?= htmlspecialchars($user_data['employee_id'] ?? 'N/A') ?></p>
                                <p><i class="fas fa-phone me-2 text-muted"></i> <?= htmlspecialchars($user_data['phone_number'] ?? 'N/A') ?></p>
                                <p><i class="fas fa-user-tie me-2 text-muted"></i> <?= htmlspecialchars($staff_data['scholar_type']) ?> Scholar</p>
                                <p><i class="fas fa-briefcase me-2 text-muted"></i> <?= htmlspecialchars($staff_data['years_of_experience']) ?> years experience</p>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-center">
                                <a href="#" class="btn btn-must me-2">
                                    <i class="fas fa-edit me-1"></i> Edit Profile
                                </a>
                                <a href="#" class="btn btn-outline-secondary">
                                    <i class="fas fa-lock me-1"></i> Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Performance Summary -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Overall Performance Score</h6>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?= ($staff_data['performance_score'] ?? 0) ?>%;" 
                                         aria-valuenow="<?= ($staff_data['performance_score'] ?? 0) ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?= ($staff_data['performance_score'] ?? 0) ?>%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-value"><?= $performance_data['publication_count'] ?? 0 ?></div>
                                        <div class="stat-label">Publications</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-value"><?= $performance_data['degree_count'] ?? 0 ?></div>
                                        <div class="stat-label">Degrees</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-value"><?= $performance_data['activity_count'] ?? 0 ?></div>
                                        <div class="stat-label">Activities</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card">
                                        <div class="stat-value"><?= $performance_data['supervision_count'] ?? 0 ?></div>
                                        <div class="stat-label">Supervisions</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card">
                                        <div class="stat-value"><?= $performance_data['community_service_count'] ?? 0 ?></div>
                                        <div class="stat-label">Community Services</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card">
                                        <div class="stat-value"><?= $performance_data['grant_count'] ?? 0 ?></div>
                                        <div class="stat-label">Grants</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Skills & Competencies -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Professional Memberships</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($bodies_result->num_rows > 0): ?>
                                <div class="mb-3">
                                    <?php while ($body = $bodies_result->fetch_assoc()): ?>
                                        <span class="skill-badge">
                                            <i class="fas fa-certificate text-success me-1"></i>
                                            <?= htmlspecialchars($body['body_name']) ?>
                                        </span>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No professional memberships recorded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Detailed Information -->
                <div class="col-lg-8">
                    <!-- Academic Qualifications -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Academic Qualifications</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($degrees_result->num_rows > 0): ?>
                                <div class="timeline">
                                    <?php while ($degree = $degrees_result->fetch_assoc()): ?>
                                        <div class="timeline-item">
                                            <h6><?= htmlspecialchars($degree['degree_name']) ?></h6>
                                            <p class="text-muted mb-1"><?= htmlspecialchars($degree['degree_classification']) ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No academic qualifications recorded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Research & Publications -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Research & Publications</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="stat-card highlight-blue">
                                        <div class="stat-value"><?= $performance_data['publication_count'] ?? 0 ?></div>
                                        <div class="stat-label">Total Publications</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="stat-card highlight-blue">
                                        <div class="stat-value"><?= $grants_data['total_grants'] ? number_format($grants_data['total_grants']) : '0' ?></div>
                                        <div class="stat-label">Total Grant Funding (UGX)</div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($publications_result->num_rows > 0): ?>
                                <h6 class="mt-4">Recent Publications</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($pub = $publications_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($pub['publication_type']) ?></td>
                                                    <td><?= htmlspecialchars($pub['role']) ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No publications recorded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Teaching & Supervision -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Teaching & Supervision</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="stat-card highlight-yellow">
                                        <div class="stat-value"><?= $performance_data['activity_count'] ?? 0 ?></div>
                                        <div class="stat-label">Academic Activities</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="stat-card highlight-yellow">
                                        <div class="stat-value"><?= $performance_data['supervision_count'] ?? 0 ?></div>
                                        <div class="stat-label">Student Supervisions</div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($supervision_result->num_rows > 0): ?>
                                <h6 class="mt-4">Current Supervisions</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Student Level</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($super = $supervision_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($super['student_level']) ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No student supervisions recorded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Community Engagement -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Community Engagement</h5>
                        </div>
                        <div class="card-body">
                            <div class="stat-card">
                                <div class="stat-value"><?= $performance_data['community_service_count'] ?? 0 ?></div>
                                <div class="stat-label">Community Service Activities</div>
                            </div>
                            
                            <?php if ($community_result->num_rows > 0): ?>
                                <h6 class="mt-4">Recent Activities</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($comm = $community_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($comm['description']) ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No community service activities recorded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Performance Charts -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Performance Metrics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="performanceChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <canvas id="activityChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Empty State -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-graduate fa-4x text-muted mb-4"></i>
                    <h4>Select a staff member to view their profile</h4>
                    <p class="text-muted">Use the dropdown above to search and select a staff member</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2 for staff dropdown
            $('.select2').select2({
                placeholder: "Search for staff member...",
                allowClear: true,
                width: '100%'
            });
            
            <?php if (!empty($user_id)): ?>
            // Initialize performance charts
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            const performanceChart = new Chart(performanceCtx, {
                type: 'radar',
                data: {
                    labels: ['Publications', 'Grants', 'Supervisions', 'Community Service', 'Activities'],
                    datasets: [{
                        label: 'Performance Metrics',
                        data: [
                            <?= $performance_data['publication_count'] ?? 0 ?>,
                            <?= $performance_data['grant_count'] ?? 0 ?>,
                            <?= $performance_data['supervision_count'] ?? 0 ?>,
                            <?= $performance_data['community_service_count'] ?? 0 ?>,
                            <?= $performance_data['activity_count'] ?? 0 ?>
                        ],
                        backgroundColor: 'rgba(0, 102, 51, 0.2)',
                        borderColor: 'rgba(0, 102, 51, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(0, 102, 51, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Performance Overview',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 0,
                            suggestedMax: Math.max(
                                <?= $performance_data['publication_count'] ?? 0 ?>,
                                <?= $performance_data['grant_count'] ?? 0 ?>,
                                <?= $performance_data['supervision_count'] ?? 0 ?>,
                                <?= $performance_data['community_service_count'] ?? 0 ?>,
                                <?= $performance_data['activity_count'] ?? 0 ?>
                            ) + 2
                        }
                    }
                }
            });
            
            const activityCtx = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(activityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Publications', 'Supervisions', 'Community Service', 'Activities'],
                    datasets: [{
                        data: [
                            <?= $performance_data['publication_count'] ?? 0 ?>,
                            <?= $performance_data['supervision_count'] ?? 0 ?>,
                            <?= $performance_data['community_service_count'] ?? 0 ?>,
                            <?= $performance_data['activity_count'] ?? 0 ?>
                        ],
                        backgroundColor: [
                            'rgba(0, 102, 51, 0.7)',
                            'rgba(0, 51, 102, 0.7)',
                            'rgba(255, 204, 0, 0.7)',
                            'rgba(102, 0, 51, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Activity Distribution',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            <?php endif; ?>
        });
    </script>
</body>
</html>