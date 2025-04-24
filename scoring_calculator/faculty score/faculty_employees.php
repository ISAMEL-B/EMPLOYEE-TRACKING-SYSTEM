<?php

include __DIR__ .'/../config.php'; 


function getTotalEmployeesByFaculty($faculty_id, $conn) {
    $totalEmployees = 0;

    // Step 1: Get department IDs for this faculty
    $deptQuery = "SELECT department_id FROM departments WHERE faculty_id = ?";
    $stmt = $conn->prepare($deptQuery);
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Collect department IDs
    $departmentIds = [];
    while ($row = $result->fetch_assoc()) {
        $departmentIds[] = $row['department_id'];
    }

    // If no departments found, return 0
    if (empty($departmentIds)) {
        return 0;
    }

    // Step 2: Count total employees in those departments
    // Create placeholders for IN clause
    $placeholders = implode(',', array_fill(0, count($departmentIds), '?'));
    $types = str_repeat('i', count($departmentIds)); // All integers
    $countQuery = "SELECT COUNT(*) AS total FROM staff WHERE department_id IN ($placeholders)";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param($types, ...$departmentIds);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $totalEmployees = $row['total'];
    }

    return $totalEmployees;
}


// // Usage
// include 'db_connection.php'; // make sure $conn is defined here

// $facultyId = 1; // example ID
// $total = getTotalEmployeesByFaculty($facultyId, $conn);
// echo "Total employees in faculty ID $facultyId: $total";

?>