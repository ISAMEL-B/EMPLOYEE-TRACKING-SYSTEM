<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}
// Get current page name (handles URLs with parameters)
$current_uri = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($current_uri, PHP_URL_PATH));

// Get current user data
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$user = $query->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
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

        // Validate image file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_ext), $allowed_types)) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                // Delete old photo if exists
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
        // Success message
        $_SESSION['success'] = "Profile updated successfully!";

        // Refresh user data
        $query->execute();
        $user = $query->get_result()->fetch_assoc();
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}

$user_role = $_SESSION['user_role'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - MUST HRM</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="styles/re_registration.css">
</head>

<body>

    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php';
    // <!-- sidebar -->
    include 'bars/side_bar.php';
    ?>

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
                <h2>Update Your Profile</h2>
                <p>Manage your personal information</p>
            </div>

            <!-- Profile Form -->
            <form method="POST" enctype="multipart/form-data">
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
                            <input type="tel" class="form-control" id="phone_number" name="phone_number"
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
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('hamburger').addEventListener('click', function() {
            const sidebar = document.querySelector('.main-sidebar');
            sidebar.classList.toggle('collapsed');
        });

        document.getElementById('ets-hamburger').addEventListener('click', function() {
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
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = document.querySelectorAll('[required]');
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