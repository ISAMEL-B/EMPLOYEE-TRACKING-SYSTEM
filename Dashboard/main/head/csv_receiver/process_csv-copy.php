<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the uploads directory exists
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Database connection
include '../approve/config.php';

// Expected column counts for validation (excluding auto-increment IDs)
$expected_columns = [
    'roles' => 1,           // role_name
    'faculties' => 1,       // faculty_name
    'departments' => 2,     // department_name, faculty_id
    'staff' => 7,           // first_name, last_name, scholar_type, role_id, department_id, years_of_experience, performance_score
    'publications' => 3,    // staff_id, publication_type, role
    'grants' => 2,          // staff_id, grant_amount
    'supervision' => 2,     // staff_id, student_level
    'innovations' => 2,     // staff_id, innovation_type
    'academicactivities' => 2, // staff_id, activity_type
    'service' => 2,         // staff_id, service_type
    'communityservice' => 3, // staff_id, description, beneficiaries
    'professionalbodies' => 2, // staff_id, body_name
    'degrees' => 3          // staff_id, degree_name, degree_classification
];

// Column names for each table (excluding auto-increment IDs)
$column_names = [
    'roles' => ['role_name'],
    'faculties' => ['faculty_name'],
    'departments' => ['department_name', 'faculty_id'],
    'staff' => ['first_name', 'last_name', 'scholar_type', 'role_id', 'department_id', 'years_of_experience', 'performance_score'],
    'publications' => ['staff_id', 'publication_type', 'role'],
    'grants' => ['staff_id', 'grant_amount'],
    'supervision' => ['staff_id', 'student_level'],
    'innovations' => ['staff_id', 'innovation_type'],
    'academicactivities' => ['staff_id', 'activity_type'],
    'service' => ['staff_id', 'service_type'],
    'communityservice' => ['staff_id', 'description', 'beneficiaries'],
    'professionalbodies' => ['staff_id', 'body_name'],
    'degrees' => ['staff_id', 'degree_name', 'degree_classification']
];

// Full column names including IDs (for reference)
$full_column_names = [
    'roles' => ['role_id', 'role_name'],
    'faculties' => ['faculty_id', 'faculty_name'],
    'departments' => ['department_id', 'department_name', 'faculty_id'],
    'staff' => ['staff_id', 'first_name', 'last_name', 'scholar_type', 'role_id', 'department_id', 'years_of_experience', 'performance_score'],
    'publications' => ['publication_id', 'staff_id', 'publication_type', 'role'],
    'grants' => ['grant_id', 'staff_id', 'grant_amount'],
    'supervision' => ['supervision_id', 'staff_id', 'student_level'],
    'innovations' => ['innovation_id', 'staff_id', 'innovation_type'],
    'academicactivities' => ['activity_id', 'staff_id', 'activity_type'],
    'service' => ['service_id', 'staff_id', 'service_type'],
    'communityservice' => ['community_service_id', 'staff_id', 'description', 'beneficiaries'],
    'professionalbodies' => ['professional_body_id', 'staff_id', 'body_name'],
    'degrees' => ['degree_id', 'staff_id', 'degree_name', 'degree_classification']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table_name'] ?? '';
    $file = $_FILES['csv_file']['tmp_name'] ?? '';
    $user_decision = $_POST['user_decision'] ?? 'abort'; // Default to abort if not set

    if (is_uploaded_file($file)) {
        $file_name = $_FILES['csv_file']['name'];
        $destination = "uploads/" . $file_name;
        move_uploaded_file($file, $destination);

        if (($handle = fopen($destination, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            $received_columns = count($header);
            $expected_count = $expected_columns[$table] ?? 0;
            $required_columns = $column_names[$table] ?? [];
            $full_columns = $full_column_names[$table] ?? [];

            // Check if CSV includes ID column (first column is usually ID)
            $has_id_column = ($received_columns > $expected_count) &&
                (strpos(strtolower($header[0]), 'id') !== false);

            // Adjust expected count if CSV includes ID column
            $adjusted_expected = $has_id_column ? $expected_count + 1 : $expected_count;

            // Verify table structure match
            $structure_matches = true;
            $mismatch_details = [];

            // Get the actual columns we expect to see (with or without ID)
            $expected_actual_columns = $has_id_column ?
                array_merge([$full_columns[0]], $required_columns) :
                $required_columns;

            // Check if the header matches expected columns (case insensitive)
            for ($i = 0; $i < min(count($expected_actual_columns), $received_columns); $i++) {
                $expected_col = strtolower($expected_actual_columns[$i]);
                $actual_col = strtolower($header[$i]);

                if ($expected_col !== $actual_col) {
                    $structure_matches = false;
                    $mismatch_details[] = [
                        'position' => $i + 1,
                        'expected' => $expected_actual_columns[$i],
                        'actual' => $header[$i]
                    ];
                }
            }

            if (!$structure_matches) {
                if ($user_decision === 'abort') {
                    // First time seeing this mismatch - ask user what to do
                    $_SESSION['column_mismatch'] = [
                        'table' => $table,
                        'expected' => $expected_count,
                        'received' => $received_columns,
                        'file' => $file_name,
                        'required_columns' => $required_columns,
                        'actual_columns' => $header,
                        'has_id_column' => $has_id_column,
                        'mismatch_details' => $mismatch_details
                    ];
                    fclose($handle);
                    header('Location: upload_csv.php');
                    exit;
                } elseif ($user_decision !== 'proceed') {
                    // User chose to cancel
                    $_SESSION['notification'] = "Upload cancelled by user due to column mismatch.";
                    fclose($handle);
                    unlink($destination);
                    header('Location: upload_csv.php');
                    exit;
                }
            }

            // Validate the column count
            if ($received_columns != $adjusted_expected) {
                if ($user_decision === 'abort') {
                    // First time seeing this mismatch - ask user what to do
                    $_SESSION['column_mismatch'] = [
                        'table' => $table,
                        'expected' => $expected_count,
                        'received' => $received_columns,
                        'file' => $file_name,
                        'required_columns' => $required_columns,
                        'actual_columns' => $header,
                        'has_id_column' => $has_id_column,
                        'mismatch_details' => $mismatch_details
                    ];
                    fclose($handle);
                    header('Location: upload_csv.php');
                    exit;
                } elseif ($user_decision === 'proceed') {
                    // User chose to proceed despite mismatch
                    if ($received_columns < $adjusted_expected) {
                        $_SESSION['notification'] = "Warning: CSV has fewer columns ($received_columns) than expected ($adjusted_expected). Proceeding with available data. Missing columns will be set to NULL if possible.";
                    } else {
                        $_SESSION['notification'] = "Warning: CSV has more columns ($received_columns) than expected ($adjusted_expected). Only the first $adjusted_expected columns will be used.";
                    }
                } else {
                    // User chose to cancel
                    $_SESSION['notification'] = "Upload cancelled by user due to column mismatch.";
                    fclose($handle);
                    unlink($destination);
                    header('Location: upload_csv.php');
                    exit;
                }
            }

            // Prepare queries based on the selected table (without ID fields)
            $query_map = [
                'roles' => [
                    "INSERT INTO roles (role_name) VALUES (?)",
                    "SELECT role_id FROM roles WHERE role_name = ?"
                ],
                'faculties' => [
                    "INSERT INTO faculties (faculty_name) VALUES (?)",
                    "SELECT faculty_id FROM faculties WHERE faculty_name = ?"
                ],
                'departments' => [
                    "INSERT INTO departments (department_name, faculty_id) VALUES (?, ?)",
                    "SELECT department_id FROM departments WHERE department_name = ?"
                ],
                'staff' => [
                    "INSERT INTO staff (first_name, last_name, scholar_type, role_id, department_id, years_of_experience, performance_score) VALUES (?, ?, ?, ?, ?, ?, ?)",
                    "SELECT staff_id FROM staff WHERE first_name = ? AND last_name = ?"
                ],
                'publications' => [
                    "INSERT INTO publications (staff_id, publication_type, role) VALUES (?, ?, ?)",
                    "SELECT publication_id FROM publications WHERE staff_id = ? AND publication_type = ? AND role = ?"
                ],
                'grants' => [
                    "INSERT INTO grants (staff_id, grant_amount) VALUES (?, ?)",
                    "SELECT grant_id FROM grants WHERE staff_id = ? AND grant_amount = ?"
                ],
                'supervision' => [
                    "INSERT INTO supervision (staff_id, student_level) VALUES (?, ?)",
                    "SELECT supervision_id FROM supervision WHERE staff_id = ? AND student_level = ?"
                ],
                'innovations' => [
                    "INSERT INTO innovations (staff_id, innovation_type) VALUES (?, ?)",
                    "SELECT innovation_id FROM innovations WHERE staff_id = ? AND innovation_type = ?"
                ],
                'academicactivities' => [
                    "INSERT INTO academicactivities (staff_id, activity_type) VALUES (?, ?)",
                    "SELECT activity_id FROM academicactivities WHERE staff_id = ? AND activity_type = ?"
                ],
                'service' => [
                    "INSERT INTO service (staff_id, service_type) VALUES (?, ?)",
                    "SELECT service_id FROM service WHERE staff_id = ? AND service_type = ?"
                ],
                'communityservice' => [
                    "INSERT INTO communityservice (staff_id, description, beneficiaries) VALUES (?, ?, ?)",
                    "SELECT community_service_id FROM communityservice WHERE staff_id = ? AND description = ? AND beneficiaries = ?"
                ],
                'professionalbodies' => [
                    "INSERT INTO professionalbodies (staff_id, body_name) VALUES (?, ?)",
                    "SELECT professional_body_id FROM professionalbodies WHERE staff_id = ? AND body_name = ?"
                ],
                'degrees' => [
                    "INSERT INTO degrees (staff_id, degree_name, degree_classification) VALUES (?, ?, ?)",
                    "SELECT degree_id FROM degrees WHERE staff_id = ? AND degree_name = ? AND degree_classification = ?"
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
            $new_records = 0;
            $updated_records = 0;
            $skipped_records = 0;
            $roles = [];
            $departments = [];
            $faculties = [];

            // Fetch foreign keys if necessary
            if ($table === 'staff') {
                $role_result = $conn->query("SELECT role_id, role_name FROM roles");
                while ($row = $role_result->fetch_assoc()) {
                    $roles[$row['role_name']] = $row['role_id'];
                }
                $department_result = $conn->query("SELECT department_id, department_name FROM departments");
                while ($row = $department_result->fetch_assoc()) {
                    $departments[$row['department_name']] = $row['department_id'];
                }
            } elseif ($table === 'departments') {
                $faculty_result = $conn->query("SELECT faculty_id, faculty_name FROM faculties");
                while ($row = $faculty_result->fetch_assoc()) {
                    $faculties[$row['faculty_name']] = $row['faculty_id'];
                }
            }

            $conn->begin_transaction();

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip empty rows
                if (empty(array_filter($data))) {
                    continue;
                }

                // Handle ID column if present
                $data_offset = $has_id_column ? 1 : 0;
                $data_values = array_slice($data, $data_offset, $expected_count);

                // Handle cases where data has fewer columns than expected
                if (count($data_values) < $expected_count) {
                    // Pad the array with empty strings to match expected count
                    $data_values = array_pad($data_values, $expected_count, '');
                } elseif (count($data_values) > $expected_count) {
                    // Truncate to expected count
                    $data_values = array_slice($data_values, 0, $expected_count);
                }

                $should_insert = false;

                // Bind parameters for checking existing records
                switch ($table) {
                    case 'roles':
                    case 'faculties':
                        $check_stmt->bind_param("s", $data_values[0]);
                        break;
                    case 'departments':
                        $check_stmt->bind_param("s", $data_values[0]);
                        break;
                    case 'staff':
                        $check_stmt->bind_param("ss", $data_values[0], $data_values[1]);
                        break;
                    case 'publications':
                        $check_stmt->bind_param("iss", $data_values[0], $data_values[1], $data_values[2]);
                        break;
                    case 'grants':
                        $check_stmt->bind_param("id", $data_values[0], $data_values[1]);
                        break;
                    case 'supervision':
                        $check_stmt->bind_param("is", $data_values[0], $data_values[1]);
                        break;
                    case 'innovations':
                        $check_stmt->bind_param("is", $data_values[0], $data_values[1]);
                        break;
                    case 'academicactivities':
                        $check_stmt->bind_param("is", $data_values[0], $data_values[1]);
                        break;
                    case 'service':
                        $check_stmt->bind_param("is", $data_values[0], $data_values[1]);
                        break;
                    case 'communityservice':
                        $check_stmt->bind_param("iss", $data_values[0], $data_values[1], $data_values[2]);
                        break;
                    case 'professionalbodies':
                        $check_stmt->bind_param("is", $data_values[0], $data_values[1]);
                        break;
                    case 'degrees':
                        $check_stmt->bind_param("iss", $data_values[0], $data_values[1], $data_values[2]);
                        break;
                }

                $check_stmt->execute();
                $result = $check_stmt->get_result();

                if ($result->num_rows == 0) {
                    // New record - insert it
                    $should_insert = true;
                    $new_records++;
                } else {
                    // Existing record - check if any data has changed
                    $existing_data = $result->fetch_assoc();
                    $has_changes = false;

                    switch ($table) {
                        case 'roles':
                        case 'faculties':
                            // Names are unique, so if found, it's the same
                            break;
                        case 'departments':
                            $has_changes = ($existing_data['faculty_id'] != ($faculties[$data_values[1]] ?? null));
                            break;
                        case 'staff':
                            $has_changes = ($existing_data['scholar_type'] != $data_values[2] ||
                                $existing_data['role_id'] != ($roles[$data_values[3]] ?? null) ||
                                $existing_data['department_id'] != ($departments[$data_values[4]] ?? null) ||
                                $existing_data['years_of_experience'] != $data_values[5] ||
                                $existing_data['performance_score'] != $data_values[6]);
                            break;
                        case 'publications':
                        case 'grants':
                        case 'supervision':
                        case 'innovations':
                        case 'academicactivities':
                        case 'service':
                        case 'communityservice':
                        case 'professionalbodies':
                        case 'degrees':
                            // These are all exact matches from the check query
                            break;
                    }

                    if ($has_changes) {
                        $should_insert = true;
                        $updated_records++;
                    }
                }

                if ($should_insert) {
                    // Bind parameters for insertion
                    switch ($table) {
                        case 'roles':
                        case 'faculties':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("s", $data_values[0]);
                            break;
                        case 'departments':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $faculty_id = is_numeric($data_values[1]) ? $data_values[1] : ($faculties[$data_values[1]] ?? null);
                            if (!$faculty_id) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("si", $data_values[0], $faculty_id);
                            break;
                        case 'staff':
                            if (empty($data_values[0]) || empty($data_values[1]) || empty($data_values[3]) || empty($data_values[4])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $role_id = is_numeric($data_values[3]) ? $data_values[3] : ($roles[$data_values[3]] ?? null);
                            $department_id = is_numeric($data_values[4]) ? $data_values[4] : ($departments[$data_values[4]] ?? null);
                            if (!$role_id || !$department_id) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("sssiiii", $data_values[0], $data_values[1], $data_values[2], $role_id, $department_id, $data_values[5], $data_values[6]);
                            break;
                        case 'publications':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("iss", $data_values[0], $data_values[1], $data_values[2]);
                            break;
                        case 'grants':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("id", $data_values[0], $data_values[1]);
                            break;
                        case 'supervision':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("is", $data_values[0], $data_values[1]);
                            break;
                        case 'innovations':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("is", $data_values[0], $data_values[1]);
                            break;
                        case 'academicactivities':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("is", $data_values[0], $data_values[1]);
                            break;
                        case 'service':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("is", $data_values[0], $data_values[1]);
                            break;
                        case 'communityservice':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("iss", $data_values[0], $data_values[1], $data_values[2]);
                            break;
                        case 'professionalbodies':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("is", $data_values[0], $data_values[1]);
                            break;
                        case 'degrees':
                            if (empty($data_values[0])) {
                                $skipped_records++;
                                continue 2;
                            }
                            $stmt->bind_param("iss", $data_values[0], $data_values[1], $data_values[2]);
                            break;
                    }

                    if ($stmt->execute()) {
                        $is_updated = true;
                    } else {
                        $skipped_records++;
                    }
                }
            }

            $conn->commit();
            fclose($handle);

            if ($is_updated) {
                $message = "Data successfully processed for {$table}. ";
                if ($new_records > 0) {
                    $message .= "Added {$new_records} new records. ";
                }
                if ($updated_records > 0) {
                    $message .= "Updated {$updated_records} existing records. ";
                }
                if ($skipped_records > 0) {
                    $message .= "Skipped {$skipped_records} records due to missing required data or other issues.";
                }
                $_SESSION['notification'] = $message;
            } else {
                $_SESSION['notification'] = "No new or changed data found in the CSV file. " .
                    ($skipped_records > 0 ? "Skipped {$skipped_records} records due to missing required data." : "");
            }
        } else {
            $_SESSION['notification'] = "Failed to open the CSV file.";
        }
    } else {
        $_SESSION['notification'] = "No file uploaded or file error.";
    }
}

header('Location: upload_csv.php');
