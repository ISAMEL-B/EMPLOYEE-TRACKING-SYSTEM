<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

// Get current user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Check user authorization
// if ($_SESSION['user_role'] !== 'hrm') {
//     header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
//     exit();
// }

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/profile_pictures/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $new_filename = "user_" . $user_id . "_" . time() . "." . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    $uploadOk = 1;
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        $upload_error = "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check file size (5MB max)
    if ($_FILES["profile_picture"]["size"] > 5000000) {
        $upload_error = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $upload_error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update database with new photo path
            $update_query = $conn->prepare("UPDATE staff SET photo_path = ? WHERE staff_id = ?");
            $update_query->bind_param("si", $target_file, $user_id);
            $update_query->execute();
            
            if ($update_query->affected_rows > 0) {
                $upload_success = "Profile picture updated successfully!";
                // Refresh the page to show the new image
                header("Refresh:0");
            } else {
                $upload_error = "Failed to update profile picture in database.";
            }
        } else {
            $upload_error = "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_password'])) {
    $password = $_POST['verify_password'];
    
    // Verify password
    $verify_query = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $verify_query->bind_param("i", $user_id);
    $verify_query->execute();
    $verify_result = $verify_query->get_result();
    $user = $verify_result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Password is correct, process updates
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        
        $update_query = $conn->prepare("UPDATE staff SET first_name = ?, last_name = ? WHERE staff_id = ?");
        $update_query->bind_param("ssi", $first_name, $last_name, $user_id);
        $update_query->execute();
        
        $update_user_query = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
        $update_user_query->bind_param("si", $email, $user_id);
        $update_user_query->execute();
        
        $update_success = "Profile updated successfully!";
        header("Refresh:2");
    } else {
        $update_error = "Incorrect password. Please try again.";
    }
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
        $staff_query = $conn->prepare("SELECT s.*, d.department_name, r.role_name, u.*
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
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <style>
        /* Content Wrapper */
        .content-wrapper {
            margin-left: 250px;
            padding-top: 70px;
            min-height: 100vh;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }

        .main-sidebar.collapsed~.content-wrapper {
            margin-left: 80px;
        }

        /* MUST Brand Colors */
        .must-primary {
            color: #2e3192 !important;
        }

        .must-secondary {
            color: #FFC107 !important;
        }

        .must-accent {
            color: #4CAF50 !important;
        }

        .must-bg-primary {
            background-color: #2e3192 !important;
        }

        .must-bg-secondary {
            background-color: #FFC107 !important;
        }

        .must-bg-accent {
            background-color: #4CAF50 !important;
        }

        /* Profile Page Specific Styles */
        .profile-header {
            background: linear-gradient(135deg, #2e3192 0%, #4CAF50 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .profile-card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            border: none;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .profile-img-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            margin: -75px auto 20px;
            background-color: #f8f9fa;
            position: relative;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-img-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #2e3192;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .profile-img-upload:hover {
            background: #4CAF50;
            transform: scale(1.1);
        }

        .stat-card {
            border-left: 4px solid #4CAF50;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .progress {
            height: 10px;
        }

        .progress-bar {
            background-color: #4CAF50;
        }

        .skill-badge {
            background-color: #e9f5e9;
            color: #2e3192;
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
            font-size: 0.85rem;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #4CAF50;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -25px;
            top: 5px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #2e3192;
            border: 2px solid #4CAF50;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #f8f9fa;
            opacity: 1;
            border-color: #e9ecef;
        }

        .edit-icon {
            color: #FFC107;
            cursor: pointer;
            transition: all 0.3s;
        }

        .edit-icon:hover {
            color: #2e3192;
            transform: scale(1.1);
        }

        .password-modal .modal-header {
            background-color: #2e3192;
            color: white;
        }

        .password-modal .modal-footer .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .password-modal .modal-footer .btn-primary:hover {
            background-color: #3e8e41;
            border-color: #3e8e41;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0;
            }

            .profile-header h1 {
                font-size: 2rem;
            }

            .profile-img-container {
                width: 120px;
                height: 120px;
                margin-top: -60px;
            }
        }
    </style>
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
                <h1 class="display-4 fw-bold">My Profile</h1>
                <p class="lead">Mbarara University of Science and Technology</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container py-4">
            <?php if (isset($upload_success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $upload_success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($upload_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $upload_error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($update_success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $update_success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($update_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $update_error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

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
                            <div class="profile-img-upload" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                                <i class="fas fa-camera"></i>
                            </div>
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
                                <i class="fas fa-building must-accent me-2"></i>
                                <?= htmlspecialchars($staff_data['department_name'] ?? 'Not specified') ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-envelope must-accent me-2"></i>
                                <?= htmlspecialchars($user_data['email'] ?? '') ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-id-card must-accent me-2"></i>
                                Employee ID: <?= htmlspecialchars($user_data['employee_id'] ?? 'N/A') ?>
                            </p>
                            <hr>
                            <div class="d-flex justify-content-center">
                                <button id="editProfileBtn" class="btn must-bg-primary text-white me-2">
                                    <i class="fas fa-edit me-1"></i> Edit Profile
                                </button>
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
                        <div class="card-header must-bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Personal Information</h5>
                            <i class="fas fa-edit edit-icon" id="editPersonalInfo"></i>
                        </div>
                        <div class="card-body">
                            <form id="personalInfoForm" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" name="first_name" 
                                                   value="<?= htmlspecialchars($staff_data['first_name'] ?? '') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" 
                                                   value="<?= htmlspecialchars($staff_data['last_name'] ?? '') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Scholar Type</label>
                                            <input type="text" class="form-control" 
                                                   value="<?= htmlspecialchars($staff_data['scholar_type'] ?? 'Not specified') ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" 
                                                   value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" 
                                                   value="<?= htmlspecialchars($staff_data['role_name'] ?? 'Not specified') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Department</label>
                                            <input type="text" class="form-control" 
                                                   value="<?= htmlspecialchars($staff_data['department_name'] ?? 'Not specified') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none" id="savePersonalInfoBtn">
                                    <button type="button" class="btn must-bg-accent text-white" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                        Save Changes
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary ms-2" id="cancelEditPersonalInfo">
                                        Cancel
                                    </button>
                                </div>
                            </form>
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
                                                    <i class="fas fa-book fa-2x must-accent"></i>
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
                                                    <i class="fas fa-chalkboard-teacher fa-2x must-accent"></i>
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
                                                    <i class="fas fa-user-graduate fa-2x must-accent"></i>
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
                                                    <i class="fas fa-hands-helping fa-2x must-accent"></i>
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
                                                <i class="fas fa-certificate must-accent me-1"></i>
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

    <!-- Upload Photo Modal -->
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header must-bg-primary text-white">
                    <h5 class="modal-title" id="uploadPhotoModalLabel">Update Profile Picture</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Select new profile picture</label>
                            <input class="form-control" type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                            <small class="text-muted">Max file size: 5MB (JPG, PNG, GIF)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn must-bg-accent text-white">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Verification Modal -->
    <div class="modal fade password-modal" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Verify Your Identity</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="verifyPasswordForm">
                    <div class="modal-body">
                        <p>For security reasons, please enter your password to confirm changes.</p>
                        <div class="mb-3">
                            <label for="verify_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="verify_password" name="verify_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn must-bg-accent text-white">Verify & Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../components/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit Profile Button
            const editProfileBtn = document.getElementById('editProfileBtn');
            const editPersonalInfoBtn = document.getElementById('editPersonalInfo');
            const personalInfoForm = document.getElementById('personalInfoForm');
            const savePersonalInfoBtn = document.getElementById('savePersonalInfoBtn');
            const cancelEditPersonalInfo = document.getElementById('cancelEditPersonalInfo');
            
            // Toggle edit mode for personal info
            function toggleEditMode(enable) {
                const inputs = personalInfoForm.querySelectorAll('input[readonly]');
                inputs.forEach(input => {
                    input.readOnly = !enable;
                    if (enable) {
                        input.classList.remove('form-control:disabled');
                        input.classList.add('form-control');
                    } else {
                        input.classList.add('form-control:disabled');
                        input.classList.remove('form-control');
                    }
                });
                
                if (enable) {
                    savePersonalInfoBtn.classList.remove('d-none');
                    editPersonalInfoBtn.classList.add('d-none');
                } else {
                    savePersonalInfoBtn.classList.add('d-none');
                    editPersonalInfoBtn.classList.remove('d-none');
                }
            }
            
            editProfileBtn.addEventListener('click', function() {
                toggleEditMode(true);
            });
            
            editPersonalInfoBtn.addEventListener('click', function() {
                toggleEditMode(true);
            });
            
            cancelEditPersonalInfo.addEventListener('click', function() {
                toggleEditMode(false);
                // Reset form to original values
                personalInfoForm.reset();
            });
            
            // When password is verified and form is submitted
            document.getElementById('verifyPasswordForm').addEventListener('submit', function() {
                // The form will submit normally, PHP will handle the verification
                // We just need to make sure the personal info form is submitted too
                document.getElementById('personalInfoForm').submit();
            });
            
            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>