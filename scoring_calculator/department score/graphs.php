<?php
// this has all my graphs for the department.
//1. overview section.
include __DIR__ .'/../config.php';
include __DIR__ .'/../total_individual_score.php'; // get the other scores of the staff member

// get employees by rank
function get_staff_by_rank_and_department($conn, $rank_name, $department_id) {
    $query = "
        SELECT s.staff_id, s.first_name, s.last_name
        FROM staff s
        INNER JOIN roles r ON s.role_id = r.role_id
        WHERE r.role_name = ? AND s.department_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $rank_name, $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $staff = [];
    while ($row = $result->fetch_assoc()) {
        $staff[] = $row;
    }

    $stmt->close();
    return $staff;
}

function get_performance_data_by_rank_and_department($conn, $rank_name, $department_id) {
    $staff_list = get_staff_by_rank_and_department($conn, $rank_name, $department_id);
    $performance_data = [];

    foreach ($staff_list as $staff) {
        $breakdown = get_individual_performance_breakdown($conn, $staff['staff_id']);

        $performance_data[] = [
            'name' => $staff['first_name'] . ' ' . $staff['last_name'],
            'experience' => $breakdown['teaching_experience_years'],
            'publications' => $breakdown['total_publications'],
            'grants' => $breakdown['grant_count']
        ];
    }

    return $performance_data;
}


// $performanceData = get_performance_data_by_rank($conn, 'Professor');
// echo "<pre>";
// print_r($performanceData);
// echo "</pre>";
// Call the function for a specific rank
// $professors = get_staff_by_rank($conn, 'Professor');

// // Echo the results
// echo "<h2>Professors</h2>";
// echo "<pre>";
// print_r($professors);
// echo "</pre>";

// // Or display in a more formatted way:
// echo "<h2>Professors</h2>";
// echo "<ul>";
// foreach ($professors as $professor) {
//     echo "<li>{$professor['first_name']} {$professor['last_name']} (ID: {$professor['staff_id']})</li>";
// }
// echo "</ul>";
//
?>

