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
// if (!isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'hrm') {
//     header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
//     exit();
// }

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