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
    $query = $conn->query("SELECT user_id, employee_id, first_name, last_name FROM users WHERE role != 'hrm' ORDER BY first_name");
    $employees = $query->fetch_all(MYSQLI_ASSOC);
}

// Get selected user data
$selected_user_id = $_GET['user_id'] ?? null;
$user = null;

if ($selected_user_id) {
    $query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $query->bind_param("i", $selected_user_id);
    $query->execute();
    $user = $query->get_result()->fetch_assoc();
}

// For non-HRM users, always show their own profile
if (!$is_hrm) {
    $query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $query->bind_param("i", $_SESSION['user_id']);
    $query->execute();
    $user = $query->get_result()->fetch_assoc();
}

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