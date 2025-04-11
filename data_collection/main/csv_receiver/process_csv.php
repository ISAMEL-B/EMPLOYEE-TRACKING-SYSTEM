<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the uploads directory exists
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Database connection
include 'config.php';

// Expected column counts for validation
$expected_columns = [
    'roles' => 2,
    'departments' => 2,
    'staff' => 8, // Includes scholar_type
    'publications' => 4, // Includes publication_id, staff_id, publication_type, role
    'grants' => 3, // Includes grant_id, staff_id, grant_amount
    'supervision' => 3, // Includes supervision_id, staff_id, student_level
    'innovations' => 3, // Includes innovation_id, staff_id, innovation_type
    'academicactivities' => 3, // Includes activity_id, staff_id, activity_type
    'service' => 3, // Includes community_service_id, staff_id, service_type
    'communityservice' => 3, // Includes community_service_id, staff_id, description
    'professionalbodies' => 3, // Includes professional_body_id, staff_id, body_name
    'degrees' => 4, // Includes degree_id, staff_id, degree_name, degree_classification
    'users' => 4 // Includes user_id, employee_id, email, passkey
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table_name'];
    $file = $_FILES['csv_file']['tmp_name'];

    if (is_uploaded_file($file)) {
        $file_name = $_FILES['csv_file']['name'];
        $destination = "uploads/" . $file_name;
        move_uploaded_file($file, $destination);

        if (($handle = fopen($destination, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            $received_columns = count($header); // Get the number of columns received

            // Validate the column count
            if ($received_columns != $expected_columns[$table]) {
                $_SESSION['notification'] = "Invalid CSV format: Expected {$expected_columns[$table]} columns, but received {$received_columns} columns. Received header: " . implode(", ", $header);
                fclose($handle);
                header('Location: upload_csv.php');
                exit;
            }

            // Prepare queries based on the selected table
            $query_map = [
                'roles' => [
                    "INSERT INTO roles (role_id, role_name) VALUES (?, ?)",
                    "SELECT * FROM roles WHERE role_id = ?"
                ],
                'departments' => [
                    "INSERT INTO departments (department_id, department_name) VALUES (?, ?)",
                    "SELECT * FROM departments WHERE department_id = ?"
                ],
                'staff' => [
                    "INSERT INTO staff (staff_id, first_name, last_name, scholar_type, role_id, department_id, years_of_experience, performance_score) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    "SELECT * FROM staff WHERE staff_id = ?"
                ],
                'publications' => [
                    "INSERT INTO publications (publication_id, staff_id, publication_type, role) VALUES (?, ?, ?, ?)",
                    "SELECT * FROM publications WHERE publication_id = ?"
                ],
                'grants' => [
                    "INSERT INTO grants (grant_id, staff_id, grant_amount) VALUES (?, ?, ?)",
                    "SELECT * FROM grants WHERE grant_id = ?"
                ],
                'supervision' => [
                    "INSERT INTO supervision (supervision_id, staff_id, student_level) VALUES (?, ?, ?)",
                    "SELECT * FROM supervision WHERE supervision_id = ?"
                ],
                'innovations' => [
                    "INSERT INTO innovations (innovation_id, staff_id, innovation_type) VALUES (?, ?, ?)",
                    "SELECT * FROM innovations WHERE innovation_id = ?"
                ],
                'academicactivities' => [
                    "INSERT INTO academicactivities (activity_id, staff_id, activity_type) VALUES (?, ?, ?)",
                    "SELECT * FROM academicactivities WHERE activity_id = ?"
                ],
                'service' => [
                    "INSERT INTO service (community_service_id, staff_id, service_type) VALUES (?, ?, ?)",
                    "SELECT * FROM service WHERE community_service_id = ?"
                ],
                'communityservice' => [
                    "INSERT INTO communityservice (community_service_id, staff_id, description) VALUES (?, ?, ?)",
                    "SELECT * FROM communityservice WHERE community_service_id = ?"
                ],
                'professionalbodies' => [
                    "INSERT INTO professionalbodies (professional_body_id, staff_id, body_name) VALUES (?, ?, ?)",
                    "SELECT * FROM professionalbodies WHERE professional_body_id = ?"
                ],
                'degrees' => [
                    "INSERT INTO degrees (degree_id, staff_id, degree_name, degree_classification) VALUES (?, ?, ?, ?)",
                    "SELECT * FROM degrees WHERE degree_id = ?"
                ],
                'users' => [
                    "INSERT INTO users (user_id, employee_id, email, passkey) VALUES (?, ?, ?, ?)",
                    "SELECT * FROM users WHERE user_id = ?"
                ]
            ];

            if (!isset($query_map[$table])) {
                $_SESSION['notification'] = "Invalid table selection.";
                fclose($handle);
                header('Location: upload_csv.php');
                exit;
            }

            [$insert_query, $check_query] = $query_map[$table];
            $stmt = $conn->prepare($insert_query);
            $check_stmt = $conn->prepare($check_query);

            $is_updated = false;
            $roles = [];
            $departments = [];

            // Fetch foreign keys if necessary (roles and departments for 'staff')
            if ($table === 'staff') {
                $role_result = $conn->query("SELECT role_id, role_name FROM roles");
                while ($row = $role_result->fetch_assoc()) {
                    $roles[$row['role_name']] = $row['role_id'];
                }
                $department_result = $conn->query("SELECT department_id, department_name FROM departments");
                while ($row = $department_result->fetch_assoc()) {
                    $departments[$row['department_name']] = $row['department_id'];
                }
            }

            $conn->begin_transaction();

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) != $expected_columns[$table]) {
                    continue; // Skip rows with incorrect column count
                }

                switch ($table) {
                    case 'roles':
                    case 'departments':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("is", $data[0], $data[1]);
                        break;
                    case 'staff':
                        $role_id = $roles[$data[4]] ?? null;
                        $department_id = $departments[$data[5]] ?? null;
                        if (!$role_id || !$department_id) continue 2; // Skip row if role/department not found
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("isssiiii", $data[0], $data[1], $data[2], $data[3], $role_id, $department_id, $data[6], $data[7]);
                        break;
                    case 'publications':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iiss", $data[0], $data[1], $data[2], $data[3]);
                        break;
                    case 'grants':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iid", $data[0], $data[1], $data[2]);
                        break;
                    case 'supervision':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'innovations':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'academicactivities':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'service':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'communityservice':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'professionalbodies':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'degrees':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iiss", $data[0], $data[1], $data[2], $data[3]);
                        break;
                    case 'users':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("isss", $data[0], $data[1], $data[2], $data[3]);
                        break;
                    default:
                        continue 2;
                }

                $check_stmt->execute();
                $check_stmt->store_result();
                if ($check_stmt->num_rows == 0) {
                    $stmt->execute();
                    $is_updated = true;
                }
            }

            $conn->commit();
            fclose($handle);
            $_SESSION['notification'] = $is_updated ? "Data successfully inserted into {$table}." : "No new data added.";
        } else {
            $_SESSION['notification'] = "Failed to open the CSV file.";
        }
    } else {
        $_SESSION['notification'] = "No file uploaded or file error.";
    }
}

header('Location: upload_csv.php');
