<?php

include "processes/hrm_profile_process.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM Profile - MUST HRM</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/hrm_profile.css">

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
                                    <button type="button" class="btn must-bg-secondary text-dark" id="submitPersonalInfoBtn">
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
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">
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
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">
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
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">
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
                                            <a href="#" class="btn btn-sm must-bg-primary text-white">
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
            // Handle personal info form submission
            const submitPersonalInfoBtn = document.getElementById('submitPersonalInfoBtn');

            submitPersonalInfoBtn.addEventListener('click', function() {
                // First show password verification modal
                const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
                passwordModal.show();

                // When password is verified, submit the form
                document.getElementById('verifyPasswordForm').addEventListener('submit', function(e) {
                    // Clone all personal info fields to the password form
                    const personalInfoForm = document.getElementById('personalInfoForm');
                    const personalInfoInputs = personalInfoForm.querySelectorAll('input[name]');

                    personalInfoInputs.forEach(input => {
                        if (input.name !== 'csrf_token') {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = input.name;
                            hiddenInput.value = input.value;
                            this.appendChild(hiddenInput);
                        }
                    });

                    // The form will now submit with both password and personal info data
                    return true;
                }, {
                    once: true
                }); // Only attach this listener once
            });

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
                    'strength-very-weak',
                    'strength-weak',
                    'strength-moderate',
                    'strength-strong',
                    'strength-very-strong'
                ];

                strength = Math.min(strength, 4); // Prevents going beyond the array index

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