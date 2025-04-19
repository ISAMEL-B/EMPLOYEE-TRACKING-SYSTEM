<?php
session_start();
require_once 'head/approve/config.php'; // Database connection

// Fetch all staff for dropdown
$staff_query = $conn->query("SELECT s.staff_id, s.first_name, s.last_name, d.department_name
                           FROM staff s
                           JOIN departments d ON s.department_id = d.department_id
                           ORDER BY s.last_name, s.first_name");
$staff_list = $staff_query->fetch_all(MYSQLI_ASSOC);

// Get top performing staff (default view)
$top_performers_query = $conn->query("SELECT s.staff_id, s.first_name, s.last_name, d.department_name, 
                                     s.performance_score, s.years_of_experience, r.role_name,
                                     (SELECT COUNT(*) FROM publications p WHERE p.staff_id = s.staff_id) as publication_count,
                                     (SELECT COUNT(*) FROM grants g WHERE g.staff_id = s.staff_id) as grant_count
                                     FROM staff s
                                     JOIN departments d ON s.department_id = d.department_id
                                     JOIN roles r ON s.role_id = r.role_id
                                     ORDER BY s.performance_score DESC
                                     LIMIT 10");
$top_performing_staff = $top_performers_query->fetch_all(MYSQLI_ASSOC);

// Get department performance stats for chart
$dept_performance_query = $conn->query("SELECT d.department_name, AVG(s.performance_score) as avg_score
                                      FROM staff s
                                      JOIN departments d ON s.department_id = d.department_id
                                      GROUP BY d.department_name
                                      ORDER BY avg_score DESC");
$department_stats = $dept_performance_query->fetch_all(MYSQLI_ASSOC);

// Get recent achievements across all staff
$recent_achievements_query = $conn->query("SELECT 
                                          'Publication' as achievement_type, 
                                          CONCAT(s.first_name, ' ', s.last_name) as staff_name,
                                          d.department_name,
                                          p.publication_type as detail,
                                          NULL as date
                                          FROM publications p
                                          JOIN staff s ON p.staff_id = s.staff_id
                                          JOIN departments d ON s.department_id = d.department_id
                                          UNION ALL
                                          SELECT 
                                          'Grant' as achievement_type,
                                          CONCAT(s.first_name, ' ', s.last_name) as staff_name,
                                          d.department_name,
                                          CONCAT('UGX ', FORMAT(g.grant_amount, 2)) as detail,
                                          NULL as date
                                          FROM grants g
                                          JOIN staff s ON g.staff_id = s.staff_id
                                          JOIN departments d ON s.department_id = d.department_id
                                          UNION ALL
                                          SELECT 
                                          'Innovation' as achievement_type,
                                          CONCAT(s.first_name, ' ', s.last_name) as staff_name,
                                          d.department_name,
                                          i.innovation_type as detail,
                                          NULL as date
                                          FROM innovations i
                                          JOIN staff s ON i.staff_id = s.staff_id
                                          JOIN departments d ON s.department_id = d.department_id
                                          ORDER BY date DESC
                                          LIMIT 5");
$recent_achievements = $recent_achievements_query->fetch_all(MYSQLI_ASSOC);

// Initialize variables
$selected_staff = null;
$staff_details = [];
$achievements = [
    'publications' => [],
    'degrees' => [],
    'grants' => [],
    'supervisions' => [],
    'innovations' => [],
    'activities' => [],
    'services' => []
];

// Handle staff selection from either POST or GET (URL parameter)
$staff_id = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'])) {
    $staff_id = (int)$_POST['staff_id'];
} elseif (isset($_GET['staff_id'])) {
    $staff_id = (int)$_GET['staff_id'];
}

if ($staff_id) {
    // Get staff details
    // $stmt = $conn->prepare("SELECT s.*, d.department_name, r.role_name, u.photo_path 
    //                       FROM staff s
    //                       JOIN departments d ON s.department_id = d.department_id
    //                       JOIN roles r ON s.role_id = r.role_id
    //                       WHERE s.staff_id = ?");
    // $stmt->bind_param("i", $staff_id);
    // $stmt->execute();
    // $staff_details = $stmt->get_result()->fetch_assoc();
    $stmt = $conn->prepare("SELECT s.*, d.department_name, r.role_name 
                        FROM staff s
                        JOIN departments d ON s.department_id = d.department_id
                        JOIN roles r ON s.role_id = r.role_id
                        WHERE s.staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $staff_details = $stmt->get_result()->fetch_assoc();


    // Get all achievements
    if ($staff_details) {
        // Publications
        $pub_query = $conn->prepare("SELECT * FROM publications WHERE staff_id = ?");
        $pub_query->bind_param("i", $staff_id);
        $pub_query->execute();
        $achievements['publications'] = $pub_query->get_result()->fetch_all(MYSQLI_ASSOC);

        // Degrees
        $deg_query = $conn->prepare("SELECT * FROM degrees WHERE staff_id = ?");
        $deg_query->bind_param("i", $staff_id);
        $deg_query->execute();
        $achievements['degrees'] = $deg_query->get_result()->fetch_all(MYSQLI_ASSOC);

        // Grants
        $grant_query = $conn->prepare("SELECT * FROM grants WHERE staff_id = ?");
        $grant_query->bind_param("i", $staff_id);
        $grant_query->execute();
        $achievements['grants'] = $grant_query->get_result()->fetch_all(MYSQLI_ASSOC);

        // Supervisions
        $sup_query = $conn->prepare("SELECT * FROM supervision WHERE staff_id = ?");
        $sup_query->bind_param("i", $staff_id);
        $sup_query->execute();
        $achievements['supervisions'] = $sup_query->get_result()->fetch_all(MYSQLI_ASSOC);

        // Innovations
        $inn_query = $conn->prepare("SELECT * FROM innovations WHERE staff_id = ?");
        $inn_query->bind_param("i", $staff_id);
        $inn_query->execute();
        $achievements['innovations'] = $inn_query->get_result()->fetch_all(MYSQLI_ASSOC);

        // Academic Activities
        $act_query = $conn->prepare("SELECT * FROM academicactivities WHERE staff_id = ?");
        $act_query->bind_param("i", $staff_id);
        $act_query->execute();
        $achievements['activities'] = $act_query->get_result()->fetch_all(MYSQLI_ASSOC);

        // Services
        $serv_query = $conn->prepare("SELECT * FROM service WHERE staff_id = ?");
        $serv_query->bind_param("i", $staff_id);
        $serv_query->execute();
        $achievements['services'] = $serv_query->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
