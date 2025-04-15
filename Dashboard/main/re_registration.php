<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Check if user is HRM
$is_hrm = ($_SESSION['user_role'] === 'hrm');

// Get current page name
$current_uri = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($current_uri, PHP_URL_PATH));

// Get all employees for HRM dropdown
$employees = [];
if ($is_hrm) {
    $query = $conn->query("SELECT user_id, employee_id, first_name, last_name FROM users ORDER BY first_name");
    $employees = $query->fetch_all(MYSQLI_ASSOC);
}

// Get selected user data
$selected_user_id = $_GET['user_id'] ?? $_SESSION['user_id'];
$user = null;
$query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$query->bind_param("i", $selected_user_id);
$query->execute();
$user = $query->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);
    $personal_email = trim($_POST['personal_email']);

    // Handle file upload
    $photo_path = $user['photo_path'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profile_photos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $file_name = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
        $target_file = $upload_dir . $file_name;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_ext), $allowed_types)) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                if ($photo_path && file_exists($photo_path)) {
                    unlink($photo_path);
                }
                $photo_path = $target_file;
            }
        }
    }

    // Update database
    $update = $conn->prepare("UPDATE users SET 
                            first_name = ?, 
                            last_name = ?, 
                            phone_number = ?, 
                            personal_email = ?, 
                            photo_path = ? 
                            WHERE user_id = ?");
    $update->bind_param("sssssi", $first_name, $last_name, $phone_number, $personal_email, $photo_path, $user_id);

    if ($update->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: ?user_id=" . $user_id);
        exit();
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_hrm ? 'Employee Profile Management' : 'Complete Your Profile' ?> - MUST HRM</title>
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="styles/re_registration.css">
</head>

<body>
    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php'; ?>
    <!-- sidebar -->
    <?php include 'bars/side_bar.php'; ?>

    <!-- Main Content -->
    <div class="container">
        <div class="profile-completion-container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Header -->
            <div class="completion-header">
                <h2><?= $is_hrm ? 'Employee Profile Management' : 'Update Your Profile' ?></h2>
                <p><?= $is_hrm ? 'Manage employee profiles' : 'Manage your personal information' ?></p>
            </div>

            <?php if ($is_hrm): ?>
                <!-- Employee Selector Dropdown -->
                <div class="employee-select-container">
                    <label for="employeeSelect" class="form-label">Select Employee</label>
                    <select id="employeeSelect" class="form-select">
                        <option value=""> Search for an employee </option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['user_id'] ?>" 
                                >
                                <span class="employee-id"><?= htmlspecialchars($employee['employee_id']) ?></span>
                                <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (!$user && $is_hrm): ?>
                <!-- Message when no employee is selected -->
                <div class="no-selection-message">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4>No employee selected</h4>
                    <p class="text-muted">Please select an employee from the dropdown above to view and edit their profile</p>
                </div>
            <?php elseif ($user): ?>
                <!-- Profile Form -->
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <!-- Profile Photo -->
                            <div class="profile-photo-container">
                                <?php if (!empty($user['photo_path'])): ?>
                                    <img src="<?= htmlspecialchars($user['photo_path']) ?>" class="profile-photo" id="profilePhotoPreview">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user fa-4x text-muted"></i>
                                    </div>
                                    <img src="" class="profile-photo d-none" id="profilePhotoPreview">
                                <?php endif; ?>
                                <div class="photo-upload-btn" onclick="document.getElementById('photoInput').click()">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none" onchange="previewPhoto(event)">
                            </div>
                            <small class="text-muted">Upload a clear passport photo</small>
                        </div>

                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Institutional Email</label>
                                <input type="email" class="form-control" id="email"
                                    value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label required-field">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label required-field">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label required-field">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="required format 0701234567" pattern="^07[0-9]{8}$"
                                    value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="personal_email" class="form-label">Personal Email</label>
                                <input type="email" class="form-control" id="personal_email" name="personal_email"
                                    value="<?= htmlspecialchars($user['personal_email'] ?? '') ?>">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-must-primary">
                                    <i class="fas fa-save ms-2"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="../components/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Initialize Select2 for employee dropdown
        $(document).ready(function() {
            $('#employeeSelect').select2({
                placeholder: "Search for an employee",
                allowClear: true,
                templateResult: formatEmployee,
                templateSelection: formatEmployeeSelection
            });

            $('#employeeSelect').on('change', function() {
                const userId = $(this).val();
                if (userId) {
                    window.location.href = '?user_id=' + userId;
                }
            });

            function formatEmployee(employee) {
                if (!employee.id) return employee.text;
                
                const $container = $(
                    '<div><span class="employee-id">' + $(employee.element).find('span').text() + '</span>' + 
                    employee.text + '</div>'
                );
                return $container;
            }

            function formatEmployeeSelection(employee) {
                if (!employee.id) return employee.text;
                
                const $container = $(
                    '<div><span class="employee-id">' + $(employee.element).find('span').text() + '</span>' + 
                    employee.text + '</div>'
                );
                return $container;
            }
        });

        // Toggle sidebar
        document.getElementById('hamburger').addEventListener('click', function() {
            const sidebar = document.querySelector('.main-sidebar');
            sidebar.classList.toggle('collapsed');
        });

        // Preview uploaded photo
        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('profilePhotoPreview');
                preview.src = reader.result;
                preview.classList.remove('d-none');
                preview.parentElement.querySelector('.fa-user').classList.add('d-none');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Form validation
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    valid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</body>
</html>