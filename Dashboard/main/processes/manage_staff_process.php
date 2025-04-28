<?php
session_start();
require_once 'head/approve/config.php';
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper function for performance badge color
function getPerformanceColor($score)
{
    if ($score >= 80) return 'success';
    if ($score >= 60) return 'warning';
    return 'danger';
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    header('Content-Type: application/json');

    $delete_id = (int)$_POST['delete_id'];
    $password = $_POST['password'] ?? '';
    $current_user_id = $_SESSION['staff_id'] ?? 0;

    try {
        // Verify password first
        $stmt = $conn->prepare("SELECT password FROM staff WHERE staff_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $current_user_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit();
        }

        if (!password_verify($password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Incorrect password']);
            exit();
        }

        if ($delete_id > 0) {
            // Start transaction
            $conn->begin_transaction();

            // Delete from staff table
            $delete_users = $conn->prepare("DELETE FROM staff WHERE staff_id = ?");
            if (!$delete_users) {
                throw new Exception("Prepare users delete failed: " . $conn->error);
            }
            $delete_users->bind_param("i", $delete_id);
            if (!$delete_users->execute()) {
                throw new Exception("Users delete failed: " . $delete_users->error);
            }

            // Commit transaction
            $conn->commit();

            echo json_encode(['success' => true]);
            exit();
        }

        echo json_encode(['success' => false, 'message' => 'Invalid staff ID']);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}

// Pagination settings
$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $records_per_page;

// Search functionality
$search_term = '';
$search_condition = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = $conn->real_escape_string(trim($_GET['search']));
    $search_condition = "WHERE (s.first_name LIKE '%$search_term%' OR s.last_name LIKE '%$search_term%' OR d.department_name LIKE '%$search_term%' OR r.role_name LIKE '%$search_term%')";
}

// Get total number of staff for pagination
// $total_staff_query = $conn->query("SELECT COUNT(*) as total FROM staff s 
//                                   JOIN departments d ON s.department_id = d.department_id 
//                                   JOIN roles r ON s.role_id = r.role_id 
//                                   $search_condition");
// $total_staff = $total_staff_query->fetch_assoc()['total'];
// $total_pages = ceil($total_staff / $records_per_page);

// // Adjust current page if it's beyond total pages
// if ($current_page > $total_pages && $total_pages > 0) {
//     $current_page = $total_pages;
//     $offset = ($current_page - 1) * $records_per_page;
// }

// Get total number of staff for pagination
$total_staff_query = $conn->query("SELECT COUNT(*) as total 
                                   FROM staff s 
                                   LEFT JOIN departments d ON s.department_id = d.department_id 
                                   LEFT JOIN roles r ON s.role_id = r.role_id 
                                   $search_condition");

$total_staff = 0;
if ($total_staff_query && $total_staff_query->num_rows > 0) {
    $total_staff = $total_staff_query->fetch_assoc()['total'];
}

// Calculate total number of pages
$total_pages = ceil($total_staff / $records_per_page);

// Adjust current page if it's beyond total pages
if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
    $offset = ($current_page - 1) * $records_per_page;
}

// Get staff data with pagination BUT those who have no null in any of their  fields
// $staff_query = $conn->query("
//     SELECT s.staff_id, s.first_name, s.last_name,s.photo_path, s.performance_score, s.years_of_experience,
//            d.department_name, r.role_name
//     FROM staff s
//     JOIN departments d ON s.department_id = d.department_id
//     JOIN roles r ON s.role_id = r.role_id
//     $search_condition
//     ORDER BY s.last_name, s.first_name
//     LIMIT $offset, $records_per_page
// ");

// Get staff data with pagination even those who have null in any of their  fields
$staff_query = $conn->query("
    SELECT s.staff_id, s.first_name, s.last_name, s.photo_path, s.performance_score, s.years_of_experience,
           d.department_name, r.role_name
    FROM staff s
    LEFT JOIN departments d ON s.department_id = d.department_id
    LEFT JOIN roles r ON s.role_id = r.role_id
    $search_condition
    ORDER BY s.last_name, s.first_name
    LIMIT $offset, $records_per_page
");


$staff_list = $staff_query ? $staff_query->fetch_all(MYSQLI_ASSOC) : [];

// Get current page for active menu highlighting
$current_page_name = basename($_SERVER['PHP_SELF']);
?>