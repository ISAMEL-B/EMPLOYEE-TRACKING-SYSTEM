<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

// Check user authorization
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Initialize variables
$user_id = null;
$user_data = [];
$staff_data = [];
$performance_data = [];
$all_staff = [];

// Fetch all staff for dropdown
$staff_query = $conn->prepare("SELECT s.staff_id, s.first_name, s.last_name, d.department_name 
                             FROM staff s
                             JOIN departments d ON s.department_id = d.department_id
                             ORDER BY s.first_name, s.last_name");
$staff_query->execute();
$staff_result = $staff_query->get_result();
while ($row = $staff_result->fetch_assoc()) {
    $all_staff[] = $row;
}

// Check if a staff member was selected
if (isset($_POST['selected_staff'])) {
    $user_id = $_POST['selected_staff'];
    
    // Get staff details if available
    if (!empty($user_id)) {
        $staff_query = $conn->prepare("SELECT s.*, d.department_name, r.role_name, f.faculty_name
                                     FROM staff s
                                     JOIN departments d ON s.department_id = d.department_id
                                     JOIN roles r ON s.role_id = r.role_id
                                     JOIN faculties f ON d.faculty_id = f.faculty_id
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
                                           (SELECT COUNT(*) FROM supervision WHERE staff_id = ?) as supervision_count,
                                           (SELECT COUNT(*) FROM communityservice WHERE staff_id = ?) as community_service_count,
                                           (SELECT COUNT(*) FROM grants WHERE staff_id = ?) as grant_count,
                                           (SELECT COUNT(*) FROM innovations WHERE staff_id = ?) as innovation_count,
                                           (SELECT COUNT(*) FROM professionalbodies WHERE staff_id = ?) as professional_body_count");
        $performance_query->bind_param("iiiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
        $performance_query->execute();
        $performance_result = $performance_query->get_result();
        $performance_data = $performance_result->fetch_assoc();
        
        // Get grants data
        $grants_query = $conn->prepare("SELECT SUM(grant_amount) as total_grants FROM grants WHERE staff_id = ?");
        $grants_query->bind_param("i", $user_id);
        $grants_query->execute();
        $grants_result = $grants_query->get_result();
        $grants_data = $grants_result->fetch_assoc();
        
        // Get degrees data
        $degrees_query = $conn->prepare("SELECT * FROM degrees WHERE staff_id = ?");
        $degrees_query->bind_param("i", $user_id);
        $degrees_query->execute();
        $degrees_result = $degrees_query->get_result();
        
        // Get publications data
        $publications_query = $conn->prepare("SELECT * FROM publications WHERE staff_id = ?");
        $publications_query->bind_param("i", $user_id);
        $publications_query->execute();
        $publications_result = $publications_query->get_result();
        
        // Get professional bodies data
        $bodies_query = $conn->prepare("SELECT * FROM professionalbodies WHERE staff_id = ?");
        $bodies_query->bind_param("i", $user_id);
        $bodies_query->execute();
        $bodies_result = $bodies_query->get_result();
        
        // Get supervision data
        $supervision_query = $conn->prepare("SELECT * FROM supervision WHERE staff_id = ?");
        $supervision_query->bind_param("i", $user_id);
        $supervision_query->execute();
        $supervision_result = $supervision_query->get_result();
        
        // Get community service data
        $community_query = $conn->prepare("SELECT * FROM communityservice WHERE staff_id = ?");
        $community_query->bind_param("i", $user_id);
        $community_query->execute();
        $community_result = $community_query->get_result();
    }
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>