<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration file
include __DIR__ .'/../config.php';

//fetch the count of employees in each department
function get_all_department_staff_counts($conn) {
    $department_counts = [];

    // Step 1: Fetch all departments
    $dept_stmt = $conn->prepare("SELECT department_id, department_name FROM departments");
    $dept_stmt->execute();
    $departments_result = $dept_stmt->get_result();

    // Step 2: Prepare the statement to get count per department
    $count_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM staff WHERE department_id = ?");

    while ($dept = $departments_result->fetch_assoc()) {
        $department_id = $dept['department_id'];
        $department_name = $dept['department_name'];

        // Bind and execute for each department
        $count_stmt->bind_param("i", $department_id);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();

        if ($row = $count_result->fetch_assoc()) {
            $department_counts[$department_name] = $row['total'];
        } else {
            $department_counts[$department_name] = 0;
        }
    }

    return $department_counts;
}


// // //example usage
// $department_counts = get_all_department_staff_counts($conn);

// // Display the result
// echo "<h2>Department Staff Counts</h2>";
// echo "<ul>";
// foreach ($department_counts as $department => $count) {
//     echo "<li><strong>$department:</strong> $count staff member(s)</li>";
// }
// echo "</ul>";

?>