<?php
session_start();
require_once 'approve/config.php'; // Database connection

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    // filter css for who has logged in since we use different sidebars
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 'hrm') {
        echo '<link rel="stylesheet" href="bars/nav_sidebar/nav_side_bar.css">';
    }
    ?>

    <style>
        /* Sidebar Styles */
        .main-sidebar {
            background-color: #4caf50;
            color: #ffffff;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            transition: width 0.3s;
            z-index: 1000;
        }

        .main-sidebar.collapsed {
            width: 80px;
        }

        .logo-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #388e3c;
        }

        .logo img {
            max-height: 40px;
        }

        .hamburger {
            background: none;
            border: none;
            color: #ffffff;
            cursor: pointer;
            font-size: 24px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu>li {
            position: relative;
        }

        .sidebar-menu>li>a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #ffffff;
            text-decoration: none;
            transition: background 0.3s;
        }

        .collapsed .sidebar-menu>li>a {
            justify-content: center;
        }

        .collapsed .sidebar-menu>li>a span {
            display: none;
        }

        .sidebar-menu>li>a:hover {
            background-color: #388e3c;
        }

        /* Submenu Styles */
        .treeview-menu {
            display: none;
            list-style: none;
            padding-left: 20px;
            transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
            max-height: 0;
            overflow: hidden;
            background-color: #e6e8f4;
            padding: 5px 0;
            border-left: 3px solid #3498db;
        }

        .treeview.active .treeview-menu {
            display: block;
            max-height: 300px;
            opacity: 1;
        }

        .treeview-menu li a {
            color: #2c3e50 !important;
            padding: 8px 15px 8px 35px;
            transition: all 0.2s;
        }

        .treeview-menu li a:hover {
            background-color: #d1d5e8 !important;
            padding-left: 38px;
            color: #3498db !important;
        }

        /* Active menu styling */
        .treeview.active>a {
            background-color: #FFEB3B !important;
            color: #000 !important;
            font-weight: 600;
        }

        .treeview-menu li a.active-submenu {
            background-color: #FFF59D !important;
            color: #000 !important;
            font-weight: 600;
            border-left: 3px solid #4CAF50;
            position: relative;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .treeview-menu li a.active-submenu::after {
            content: "→";
            position: absolute;
            right: 15px;
            color: #4CAF50;
            font-weight: bold;
            animation: bounce 0.5s infinite alternate;
        }

        @keyframes bounce {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(3px);
            }
        }

        /* Navigation Bar Styles */
        .ets-nav-container {
            background-color: #2e3192;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            height: 70px;
            transition: left 0.3s ease, width 0.3s ease;
        }

        .main-sidebar.collapsed~.ets-nav-container {
            left: 80px;
            width: calc(100% - 80px);
        }

        .ets-nav-tabs {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
            height: 100%;
            position: relative;
        }

        .ets-hamburger {
            position: absolute;
            left: 15px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
            transition: transform 0.3s ease;
        }

        .ets-hamburger:hover {
            color: #FFEB3B;
        }

        .ets-nav-link {
            background-color: transparent;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            font-size: 16px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ets-nav-link.active {
            background-color: #4CAF50;
            color: white !important;
            border-bottom: 3px solid #FFEB3B;
            font-weight: bold;
            transform: scale(1.05);
        }

        .ets-nav-link:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFEB3B;
        }

        .ets-nav-link.ets-logout {
            color: #e74c3c;
            font-weight: bold;
        }

        .ets-nav-link.ets-logout:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }

        .ets-role-indicator {
            position: absolute;
            top: 70px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        @keyframes ets-navLinkPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .ets-nav-link.active {
            animation: ets-navLinkPulse 0.5s ease;
        }

        /* Profile Page Styles */
        :root {
            --must-blue: #2e3192;
            --must-green: #4CAF50;
            --must-yellow: #FFEB3B;
            --must-light: #f8f9fa;
        }

        body {
            background-color: #f5f5f5;
            padding-top: 70px;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .main-sidebar.collapsed~body {
            margin-left: 80px;
        }

        .profile-completion-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .completion-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--must-green);
        }

        .completion-header h2 {
            color: var(--must-blue);
            font-weight: 700;
        }

        .completion-header p {
            color: #666;
        }

        .form-label {
            font-weight: 600;
            color: var(--must-blue);
        }

        .required-field::after {
            content: " *";
            color: red;
        }

        .profile-photo-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid var(--must-green);
            overflow: hidden;
            margin: 0 auto 20px;
            position: relative;
            background-color: #eee;
        }

        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: var(--must-green);
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .photo-upload-btn:hover {
            background-color: var(--must-blue);
        }

        .btn-must-primary {
            background-color: var(--must-blue);
            color: white;
            font-weight: 600;
            padding: 10px 25px;
        }

        .btn-must-primary:hover {
            background-color: var(--must-green);
            color: white;
        }

        .progress-container {
            margin-bottom: 30px;
        }

        .progress-bar {
            background-color: var(--must-green);
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .step {
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .step.active {
            color: var(--must-blue);
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .main-sidebar {
                width: 80px;
            }

            body {
                margin-left: 80px;
            }

            .ets-nav-container {
                left: 80px;
                width: calc(100% - 80px);
            }

            .ets-nav-tabs {
                justify-content: flex-start;
                overflow-x: auto;
                padding-left: 50px;
            }

            .ets-nav-link {
                padding: 10px 15px;
                font-size: 14px;
                white-space: nowrap;
            }

            .ets-nav-link span {
                display: none;
            }

            .ets-nav-link i {
                margin-right: 0;
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .profile-completion-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>

</head>

<body>

    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php';
    // <!-- sidebar -->
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'hrm') {
        include 'bars/side_bar.php';
    } else {
        include 'bars/nav_sidebar/side_bar.php';
    }
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