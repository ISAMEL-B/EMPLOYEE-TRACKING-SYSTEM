<?php
session_start();
require_once 'approve/config.php'; // Database connection

// Get current user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Check user authorization
if ($_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Fetch user data from database
$user_data = [];
$staff_data = [];
$performance_data = [];

if ($user_id) {
    // Get basic user info
    $user_query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();
    
    // Get staff details if available
    if (!empty($user_id)) {
        $staff_query = $conn->prepare("SELECT s.*, d.department_name, r.role_name 
                                     FROM staff s
                                     JOIN departments d ON s.department_id = d.department_id
                                     JOIN roles r ON s.role_id = r.role_id
                                     JOIN users u ON u.staff_id = s.staff_id
                                     WHERE s.staff_id = ?");
        $staff_query->bind_param("i", $user_id);
        $staff_query->execute();
        $staff_result = $staff_query->get_result();
        $staff_data = $staff_result->fetch_assoc();
        
        // Get performance data
        $performance_query = $conn->prepare("SELECT 
                                           (SELECT COUNT(*) FROM publications WHERE staff_id = ?) as publication_count,
                                           (SELECT COUNT(*) FROM degrees WHERE staff_id = ?) as degree_count,
                                           (SELECT COUNT(*) FROM academicactivities WHERE staff_id = ?) as activity_count,
                                           (SELECT COUNT(*) FROM supervision WHERE staff_id = ?) as supervision_count");
        $performance_query->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
        $performance_query->execute();
        $performance_result = $performance_query->get_result();
        $performance_data = $performance_result->fetch_assoc();
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
    <title>My Profile - MUST HRM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/staff_profile.css">

</head>
<body>
         
    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php'; ?>
    <!-- sidebar -->
    <?php include 'bars/side_bar.php'; ?>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="container text-center">
                <h1 class="display-4 fw-bold">Selected Staff Profile</h1>
                <p class="lead">Mbarara University of Science and Technology</p>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="container py-4">
            <div class="row">
                <!-- Left Column - Profile Card -->
                <div class="col-lg-4 mb-4">
                    <div class="card profile-card pt-5">
                        <div class="profile-img-container">
                            <?php if (!empty($staff_data['photo_path'])): ?>
                                <img src="<?= htmlspecialchars($staff_data['photo_path']) ?>" class="profile-img" alt="Profile Photo">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-user fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="card-title must-primary">
                                <?= htmlspecialchars($staff_data['first_name'] ?? '') ?> 
                                <?= htmlspecialchars($staff_data['last_name'] ?? '') ?>
                            </h3>
                            <h5 class="text-muted mb-3">
                                <?= htmlspecialchars($staff_data['role_name'] ?? 'Staff') ?>
                            </h5>
                            <p class="card-text">
                                <i class="fas fa-building must-secondary me-2"></i>
                                <?= htmlspecialchars($staff_data['department_name'] ?? 'Not specified') ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-envelope must-secondary me-2"></i>
                                <?= htmlspecialchars($user_data['email'] ?? '') ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-id-card must-secondary me-2"></i>
                                Employee ID: <?= htmlspecialchars($user_data['employee_id'] ?? 'N/A') ?>
                            </p>
                            <hr>
                            <div class="d-flex justify-content-center">
                                <a href="#" class="btn must-bg-primary text-white me-2">
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
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">Performance Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Overall Score</h6>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= ($staff_data['performance_score'] ?? 0) ?>%;" 
                                         aria-valuenow="<?= ($staff_data['performance_score'] ?? 0) ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted"><?= ($staff_data['performance_score'] ?? 0) ?>% of target</small>
                            </div>
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <h4 class="must-primary"><?= $performance_data['publication_count'] ?? 0 ?></h4>
                                    <small class="text-muted">Publications</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <h4 class="must-primary"><?= $performance_data['degree_count'] ?? 0 ?></h4>
                                    <small class="text-muted">Degrees</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="must-primary"><?= $performance_data['activity_count'] ?? 0 ?></h4>
                                    <small class="text-muted">Activities</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="must-primary"><?= $performance_data['supervision_count'] ?? 0 ?></h4>
                                    <small class="text-muted">Supervisions</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Details -->
                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="card mb-4">
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>First Name:</strong> <?= htmlspecialchars($staff_data['first_name'] ?? 'Not specified') ?></p>
                                    <p><strong>Last Name:</strong> <?= htmlspecialchars($staff_data['last_name'] ?? 'Not specified') ?></p>
                                    <p><strong>Scholar Type:</strong> <?= htmlspecialchars($staff_data['scholar_type'] ?? 'Not specified') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Role:</strong> <?= htmlspecialchars($staff_data['role_name'] ?? 'Not specified') ?></p>
                                    <p><strong>Department:</strong> <?= htmlspecialchars($staff_data['department_name'] ?? 'Not specified') ?></p>
                                    <p><strong>Years of Experience:</strong> <?= htmlspecialchars($staff_data['years_of_experience'] ?? '0') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Qualifications -->
                    <div class="card mb-4">
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">Academic Qualifications</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($user_id)): 
                                $degree_query = $conn->prepare("SELECT * FROM degrees WHERE staff_id = ?");
                                $degree_query->bind_param("i", $user_id);
                                $degree_query->execute();
                                $degree_result = $degree_query->get_result();
                                
                                if ($degree_result->num_rows > 0): ?>
                                    <div class="timeline">
                                        <?php while ($degree = $degree_result->fetch_assoc()): ?>
                                            <div class="timeline-item">
                                                <h6><?= htmlspecialchars($degree['degree_name']) ?></h6>
                                                <p class="text-muted mb-1"><?= htmlspecialchars($degree['degree_classification']) ?></p>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No academic qualifications recorded.</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted">Staff information not available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Professional Activities -->
                    <div class="card mb-4">
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">Professional Activities</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Publications -->
                                <div class="col-md-6 mb-3">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-book fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0"><?= $performance_data['publication_count'] ?? 0 ?></h4>
                                            </div>
                                            <p class="card-text">Publications</p>
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">View All</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Academic Activities -->
                                <div class="col-md-6 mb-3">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-chalkboard-teacher fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0"><?= $performance_data['activity_count'] ?? 0 ?></h4>
                                            </div>
                                            <p class="card-text">Academic Activities</p>
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">View All</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Supervisions -->
                                <div class="col-md-6">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-user-graduate fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0"><?= $performance_data['supervision_count'] ?? 0 ?></h4>
                                            </div>
                                            <p class="card-text">Student Supervisions</p>
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">View All</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Community Service -->
                                <div class="col-md-6">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-hands-helping fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0"><?= $performance_data['community_service_count'] ?? 0 ?></h4>
                                            </div>
                                            <p class="card-text">Community Services</p>
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">View All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Skills & Competencies -->
                    <div class="card mb-4">
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">Skills & Competencies</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($user_data['employee_id'])): 
                                $skills_query = $conn->prepare("SELECT * FROM professionalbodies WHERE staff_id = ?");
                                $skills_query->bind_param("i", $user_data['employee_id']);
                                $skills_query->execute();
                                $skills_result = $skills_query->get_result();
                                
                                if ($skills_result->num_rows > 0): ?>
                                    <div class="mb-3">
                                        <h6>Professional Memberships</h6>
                                        <?php while ($skill = $skills_result->fetch_assoc()): ?>
                                            <span class="skill-badge">
                                                <i class="fas fa-certificate must-secondary me-1"></i>
                                                <?= htmlspecialchars($skill['body_name']) ?>
                                            </span>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No professional memberships recorded.</p>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <div>
                                <h6>Core Competencies</h6>
                                <span class="skill-badge">Research</span>
                                <span class="skill-badge">Teaching</span>
                                <span class="skill-badge">Mentorship</span>
                                <span class="skill-badge">Curriculum Development</span>
                                <span class="skill-badge">Academic Leadership</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Additional profile page functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Handle edit profile button
            document.querySelector('.btn-edit-profile').addEventListener('click', function() {
                // Implement your edit profile modal here
                console.log('Edit profile clicked');
            });
        });
    </script>
</body>
</html>