<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

if (!isset($_SESSION['staff_id'])) {
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
    $query = $conn->query("SELECT staff_id, employee_id, first_name, last_name FROM staff ORDER BY first_name");
    $employees = $query->fetch_all(MYSQLI_ASSOC);
}

// Get selected staff data
$selected_staff_id = $_GET['staff_id'] ?? null;
$staff = null;

if ($selected_staff_id) {
    $query = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $query->bind_param("i", $selected_staff_id);
    $query->execute();
    $staff = $query->get_result()->fetch_assoc();
}

// For non-HRM users, always show their own profile
if (!$is_hrm) {
    $query = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $query->bind_param("i", $_SESSION['staff_id']);
    $query->execute();
    $staff = $query->get_result()->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'])) {
    $staff_id = $_POST['staff_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);
    $personal_email = trim($_POST['personal_email']);

    // Handle file upload
    $photo_path = $staff['photo_path'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profile_photos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $file_name = 'staff_' . $staff_id . '_' . time() . '.' . $file_ext;
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
    $update = $conn->prepare("UPDATE staff SET 
                            first_name = ?, 
                            last_name = ?, 
                            phone_number = ?, 
                            personal_email = ?, 
                            photo_path = ? 
                            WHERE staff_id = ?");
    $update->bind_param("sssssi", $first_name, $last_name, $phone_number, $personal_email, $photo_path, $staff_id);

    if ($update->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: ?staff_id=" . $staff_id);
        exit();
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}
