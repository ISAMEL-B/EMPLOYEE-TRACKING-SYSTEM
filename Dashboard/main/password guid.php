
<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Get current user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Check user authorization
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $upload_error = "Invalid CSRF token.";
    } else {
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
            $upload_error = "Sorry, your file is too large (max 5MB).";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            $upload_error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update database with new photo path
                $update_query = $conn->prepare("UPDATE users SET photo_path = ? WHERE user_id = ?");
                $update_query->bind_param("si", $target_file, $user_id);
                $update_query->execute();

                if ($update_query->affected_rows > 0) {
                    $_SESSION['upload_success'] = "Profile picture updated successfully!";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $upload_error = "Failed to update profile picture in database.";
                }
            } else {
                $upload_error = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_password'])) {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['update_error'] = "Security verification failed. Please try again.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Password Verification
    $password = $_POST['verify_password'];
    $verify_query = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $verify_query->bind_param("i", $user_id);
    $verify_query->execute();
    $user = $verify_query->get_result()->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        $_SESSION['update_error'] = "Incorrect password. Please try again.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Input Validation
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_number = preg_replace('/[^0-9]/', '', $_POST['phone_number'] ?? '');
    $personal_email = trim($_POST['personal_email'] ?? '');

    // Validate lengths
    $max_lengths = [
        'first_name' => 50,
        'last_name' => 50,
        'email' => 100,
        'phone_number' => 20,
        'personal_email' => 100
    ];

    foreach ($max_lengths as $field => $max) {
        if (strlen($$field) > $max) {
            $_SESSION['update_error'] = ucfirst(str_replace('_', ' ', $field)) . " must be less than $max characters";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    // Email validation
    if (empty($email)) {
        $_SESSION['update_error'] = "Official email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['update_error'] = "Invalid official email format";
    } elseif (!empty($personal_email) && !filter_var($personal_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['update_error'] = "Invalid personal email format";
    }

    if (isset($_SESSION['update_error'])) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Database Update with Transaction
    $conn->begin_transaction();
    try {
        $update_query = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ?, personal_email = ? WHERE user_id = ?");
        $update_query->bind_param("sssssi", $first_name, $last_name, $email, $phone_number, $personal_email, $user_id);
        $update_query->execute();
        
        if ($update_query->affected_rows > 0) {
            $_SESSION['update_success'] = "Profile updated successfully!";
        } else {
            $_SESSION['update_error'] = "No changes were made. Did you modify any fields?";
        }
        
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['update_error'] = "Database error: " . $e->getMessage();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['current_password'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['password_error'] = "Security verification failed. Please try again.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password
        $verify_query = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $verify_query->bind_param("i", $user_id);
        $verify_query->execute();
        $verify_result = $verify_query->get_result();
        $user = $verify_result->fetch_assoc();

        if (password_verify($current_password, $user['password'])) {
            // Check if new passwords match
            if ($new_password !== $confirm_password) {
                $_SESSION['password_error'] = "New passwords do not match.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            // Check password strength
            if (strlen($new_password) < 8) {
                $_SESSION['password_warning'] = "Warning: Your password is shorter than 8 characters. We recommend using a stronger password.";
            } elseif (!preg_match("#[0-9]+#", $new_password)) {
                $_SESSION['password_warning'] = "Warning: Your password doesn't contain any numbers. We recommend using a stronger password.";
            } elseif (!preg_match("#[a-zA-Z]+#", $new_password)) {
                $_SESSION['password_warning'] = "Warning: Your password doesn't contain any letters. We recommend using a stronger password.";
            }

            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password
            $update_query = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update_query->bind_param("si", $hashed_password, $user_id);
            
            if ($update_query->execute()) {
                if ($update_query->affected_rows > 0) {
                    $_SESSION['password_success'] = "Password changed successfully!";
                } else {
                    $_SESSION['password_error'] = "Failed to update password.";
                }
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['password_error'] = "Database error: " . $conn->error;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['password_error'] = "Current password is incorrect.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

// Fetch user data from database
$user_data = [];
if ($user_id) {
    $user_query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Clear flash messages after displaying them
$upload_success = $_SESSION['upload_success'] ?? null;
$upload_error = $_SESSION['upload_error'] ?? null;
$update_success = $_SESSION['update_success'] ?? null;
$update_error = $_SESSION['update_error'] ?? null;
$password_success = $_SESSION['password_success'] ?? null;
$password_error = $_SESSION['password_error'] ?? null;
$password_warning = $_SESSION['password_warning'] ?? null;

unset($_SESSION['upload_success']);
unset($_SESSION['upload_error']);
unset($_SESSION['update_success']);
unset($_SESSION['update_error']);
unset($_SESSION['password_success']);
unset($_SESSION['password_error']);
unset($_SESSION['password_warning']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM Profile - MUST HRM</title>
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/hrm_profile.css">
    <style>
        .notification-container {
            position: sticky;
            top: 20px;
            z-index: 1000;
        }
        .notification-container .alert {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            background-color: #f8f9fa;
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
            background: rgba(0,0,0,0.5);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .must-primary { color: #0056b3; }
        .must-secondary { color: #6c757d; }
        .must-accent { color: #17a2b8; }
        .must-bg-primary { background-color: #0056b3; }
        .must-bg-secondary { background-color: #6c757d; }
        .hrm-badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 0.85em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: white;
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
                <h1 class="display-4 fw-bold">HRM Profile</h1>
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
                            <?php if (!empty($user_data['photo_path'])): ?>
                                <img src="<?= htmlspecialchars($user_data['photo_path']) ?>" class="profile-img" alt="Profile Photo">
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
                                <?= htmlspecialchars($user_data['first_name'] ?? '') ?>
                                <?= htmlspecialchars($user_data['last_name'] ?? '') ?>
                            </h3>
                            <h5 class="text-muted mb-3">
                                <span class="hrm-badge must-bg-secondary">Human Resource Manager</span>
                            </h5>
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
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fas fa-lock me-1"></i> Change Password
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- HRM Summary -->
                    <div class="card mt-4">
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">HRM Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="stat-card p-3 rounded">
                                        <i class="fas fa-users fa-2x must-secondary mb-2"></i>
                                        <h4 class="must-primary">125</h4>
                                        <small class="text-muted">Employees</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card p-3 rounded">
                                        <i class="fas fa-calendar-check fa-2x must-secondary mb-2"></i>
                                        <h4 class="must-primary">42</h4>
                                        <small class="text-muted">Pending Requests</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card p-3 rounded">
                                        <i class="fas fa-chart-line fa-2x must-secondary mb-2"></i>
                                        <h4 class="must-primary">87%</h4>
                                        <small class="text-muted">Satisfaction</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card p-3 rounded">
                                        <i class="fas fa-tasks fa-2x must-secondary mb-2"></i>
                                        <h4 class="must-primary">15</h4>
                                        <small class="text-muted">Tasks Today</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Details -->
                <div class="col-lg-8">
                    <!-- Notification Container -->
                    <div class="notification-container">
                        <?php if (isset($upload_success)): ?>
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" data-auto-dismiss="20000">
                                <?= htmlspecialchars($upload_success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (isset($upload_error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" data-auto-dismiss="20000">
                                <?= htmlspecialchars($upload_error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($update_success)): ?>
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" data-auto-dismiss="20000">
                                <?= htmlspecialchars($update_success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (isset($update_error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" data-auto-dismiss="20000">
                                <?= htmlspecialchars($update_error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($password_success)): ?>
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" data-auto-dismiss="20000">
                                <?= htmlspecialchars($password_success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (isset($password_error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" data-auto-dismiss="20000">
                                <?= htmlspecialchars($password_error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($password_warning)): ?>
                            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert" data-auto-dismiss="20000">
                                <?= htmlspecialchars($password_warning) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Personal Information -->
                    <div class="card mb-4">
                        <div class="card-header must-bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Personal Information</h5>
                            <i class="fas fa-edit edit-icon" id="editPersonalInfo"></i>
                        </div>
                        <div class="card-body">
                            <form id="personalInfoForm" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" name="first_name"
                                                value="<?= htmlspecialchars($user_data['first_name'] ?? '') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                value="<?= htmlspecialchars($user_data['last_name'] ?? '') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Employee ID</label>
                                            <input type="text" class="form-control"
                                                value="<?= htmlspecialchars($user_data['employee_id'] ?? 'Not specified') ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Official Email</label>
                                            <input type="email" class="form-control" name="email"
                                                value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Personal Email</label>
                                            <input type="email" class="form-control" name="personal_email"
                                                value="<?= htmlspecialchars($user_data['personal_email'] ?? '') ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone_number"
                                                value="<?= htmlspecialchars($user_data['phone_number'] ?? '') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none" id="savePersonalInfoBtn">
                                    <button type="button" class="btn must-bg-secondary text-dark" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary ms-2" id="cancelEditPersonalInfo">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="card mb-4">
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Account Created</label>
                                        <input type="text" class="form-control"
                                            value="<?= date('F j, Y', strtotime($user_data['date_created'])) ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Last Login</label>
                                        <input type="text" class="form-control" value="Today" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">User Role</label>
                                        <input type="text" class="form-control"
                                            value="Human Resource Manager" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Account Status</label>
                                        <input type="text" class="form-control text-success" value="Active" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- HRM Tools -->
                    <div class="card mb-4">
                        <div class="card-header must-bg-primary text-white">
                            <h5 class="mb-0">HRM Tools</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Employee Management -->
                                <div class="col-md-6 mb-3">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-users-cog fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0">Manage</h4>
                                            </div>
                                            <p class="card-text">Employee Records</p>
                                            <a href="/EMPLOYEE-TRACKING-SYSTEM/hrm/employees.php" class="btn btn-sm must-bg-primary text-white">
                                                <i class="fas fa-arrow-right me-1"></i> Access
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recruitment -->
                                <div class="col-md-6 mb-3">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-user-plus fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0">Recruitment</h4>
                                            </div>
                                            <p class="card-text">Hiring & Onboarding</p>
                                            <a href="/EMPLOYEE-TRACKING-SYSTEM/hrm/recruitment.php" class="btn btn-sm must-bg-primary text-white">
                                                <i class="fas fa-arrow-right me-1"></i> Access
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Performance -->
                                <div class="col-md-6">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-chart-bar fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0">Performance</h4>
                                            </div>
                                            <p class="card-text">Reviews & Appraisals</p>
                                            <a href="/EMPLOYEE-TRACKING-SYSTEM/hrm/performance.php" class="btn btn-sm must-bg-primary text-white">
                                                <i class="fas fa-arrow-right me-1"></i> Access
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reports -->
                                <div class="col-md-6">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-file-alt fa-2x must-secondary"></i>
                                                </div>
                                                <h4 class="mb-0">Reports</h4>
                                            </div>
                                            <p class="card-text">Analytics & Insights</p>
                                            <a href="/EMPLOYEE-TRACKING-SYSTEM/hrm/reports.php" class="btn btn-sm must-bg-primary text-white">
                                                <i class="fas fa-arrow-right me-1"></i> Access
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Select new profile picture</label>
                            <input class="form-control" type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                            <small class="text-muted">Max file size: 5MB (JPG, PNG, GIF)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn must-bg-secondary text-dark">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Verification Modal -->
    <div class="modal fade password-modal" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header must-bg-primary text-white">
                    <h5 class="modal-title" id="passwordModalLabel">Verify Your Identity</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="verifyPasswordForm">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="modal-body">
                        <p>For security reasons, please enter your password to confirm changes.</p>
                        <div class="mb-3">
                            <label for="verify_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="verify_password" name="verify_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn must-bg-secondary text-dark">Verify & Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header must-bg-primary text-white">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="changePasswordForm">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="modal-body">
                        <?php if (isset($password_success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($password_success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (isset($password_error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($password_error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (isset($password_warning)): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($password_warning) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div id="passwordStrength" class="password-strength"></div>
                            <div class="form-text">Minimum 8 characters, with at least one letter and one number</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn must-bg-secondary text-dark">Change Password</button>
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

            // Auto-dismiss alerts after specified time
            document.querySelectorAll('[data-auto-dismiss]').forEach(alert => {
                const delay = parseInt(alert.getAttribute('data-auto-dismiss'));
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, delay);
            });

            // Password strength indicator
            document.getElementById('new_password')?.addEventListener('input', function() {
                var password = this.value;
                var strengthText = document.getElementById('passwordStrength');
                
                if (password.length === 0) {
                    strengthText.textContent = '';
                    strengthText.className = 'password-strength';
                    return;
                }
                
                var strength = 0;
                if (password.length >= 8) strength++;
                if (password.length >= 12) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                var strengthMessages = [
                    'Very Weak',
                    'Weak',
                    'Moderate',
                    'Strong',
                    'Very Strong'
                ];
                
                var strengthClasses = [
                    'strength-weak',
                    'strength-weak',
                    'strength-medium',
                    'strength-strong',
                    'strength-strong'
                ];
                
                strengthText.textContent = 'Strength: ' + strengthMessages[strength];
                strengthText.className = 'password-strength ' + strengthClasses[strength];
            });

            // Form submission handling for password change
            document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
                var newPassword = document.getElementById('new_password').value;
                var confirmPassword = document.getElementById('confirm_password').value;
                
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('New passwords do not match!');
                    return false;
                }
                
                return true;
            });
        });
    </script>
</body>

</html>