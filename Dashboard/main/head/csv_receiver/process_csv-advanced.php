<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the uploads directory exists
if (!file_exists('uploads')) {
    if (!mkdir('uploads', 0777, true)) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => "Failed to create upload directory. Please check permissions."
        ];
        header('Location: upload_csv.php');
        exit;
    }
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

// Human-readable column names for error messages
$column_names = [
    'roles' => ['Role Name'],
    'faculties' => ['Faculty Name'],
    'departments' => ['Department Name', 'Faculty ID'],
    'staff' => ['First Name', 'Last Name', 'Scholar Type', 'Role ID', 'Department ID', 'Years of Experience', 'Performance Score'],
    'publications' => ['Staff ID', 'Publication Type', 'Role'],
    'grants' => ['Staff ID', 'Grant Amount'],
    'supervision' => ['Staff ID', 'Student Level'],
    'innovations' => ['Staff ID', 'Innovation Type'],
    'academicactivities' => ['Staff ID', 'Activity Type'],
    'service' => ['Staff ID', 'Service Type'],
    'communityservice' => ['Staff ID', 'Description', 'Beneficiaries'],
    'professionalbodies' => ['Staff ID', 'Body Name'],
    'degrees' => ['Staff ID', 'Degree Name', 'Degree Classification']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table_name'] ?? '';
    $file = $_FILES['csv_file']['tmp_name'] ?? '';
    $force_upload = isset($_POST['force_upload']) ? true : false;
    $csv_file_path = $_POST['csv_file_path'] ?? '';

    // Validate table selection
    if (!array_key_exists($table, $expected_columns)) {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => "Invalid table selection. Please select a valid table from the dropdown."
        ];
        header('Location: upload_csv.php');
        exit;
    }

    // Handle forced upload from modal
    if ($force_upload && $csv_file_path) {
        $table = $_POST['table_name'];
        $file = $csv_file_path;
    }

    if (is_uploaded_file($file)) {
        $file_name = $force_upload ? basename($csv_file_path) : $_FILES['csv_file']['name'];
        $destination = "uploads/" . $file_name;
        
        if (!$force_upload) {
            if (!move_uploaded_file($file, $destination)) {
                $_SESSION['notification'] = [
                    'type' => 'error',
                    'message' => "Failed to move uploaded file. Please check directory permissions."
                ];
                header('Location: upload_csv.php');
                exit;
            }
        } else {
            $destination = $csv_file_path;
        }

        if (($handle = fopen($destination, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            $received_columns = count($header);

            // Validate the column count if not forcing upload
            if (!$force_upload && $received_columns != $expected_columns[$table]) {
                // Store detailed file info in session for confirmation
                $_SESSION['csv_validation'] = [
                    'table' => $table,
                    'expected' => $expected_columns[$table],
                    'received' => $received_columns,
                    'file_path' => $destination,
                    'expected_columns' => $column_names[$table],
                    'received_columns' => $header
                ];
                
                fclose($handle);
                header('Location: upload_csv.php');
                exit;
            }

            // Prepare queries based on the selected table (without ID fields)
            $query_map = [
                'roles' => [
                    "INSERT INTO roles (role_name) VALUES (?)",
                    "SELECT role_id, role_name FROM roles WHERE role_name = ?"
                ],
                'faculties' => [
                    "INSERT INTO faculties (faculty_name) VALUES (?)",
                    "SELECT faculty_id, faculty_name FROM faculties WHERE faculty_name = ?"
                ],
                'departments' => [
                    "INSERT INTO departments (department_name, faculty_id) VALUES (?, ?)",
                    "SELECT department_id, department_name, faculty_id FROM departments WHERE department_name = ?"
                ],
                'staff' => [
                    "INSERT INTO staff (first_name, last_name, scholar_type, role_id, department_id, years_of_experience, performance_score) VALUES (?, ?, ?, ?, ?, ?, ?)",
                    "SELECT staff_id, first_name, last_name, scholar_type, role_id, department_id, years_of_experience, performance_score FROM staff WHERE first_name = ? AND last_name = ?"
                ],
                'publications' => [
                    "INSERT INTO publications (staff_id, publication_type, role) VALUES (?, ?, ?)",
                    "SELECT publication_id, staff_id, publication_type, role FROM publications WHERE staff_id = ? AND publication_type = ? AND role = ?"
                ],
                'grants' => [
                    "INSERT INTO grants (staff_id, grant_amount) VALUES (?, ?)",
                    "SELECT grant_id, staff_id, grant_amount FROM grants WHERE staff_id = ? AND grant_amount = ?"
                ],
                'supervision' => [
                    "INSERT INTO supervision (staff_id, student_level) VALUES (?, ?)",
                    "SELECT supervision_id, staff_id, student_level FROM supervision WHERE staff_id = ? AND student_level = ?"
                ],
                'innovations' => [
                    "INSERT INTO innovations (staff_id, innovation_type) VALUES (?, ?)",
                    "SELECT innovation_id, staff_id, innovation_type FROM innovations WHERE staff_id = ? AND innovation_type = ?"
                ],
                'academicactivities' => [
                    "INSERT INTO academicactivities (staff_id, activity_type) VALUES (?, ?)",
                    "SELECT activity_id, staff_id, activity_type FROM academicactivities WHERE staff_id = ? AND activity_type = ?"
                ],
                'service' => [
                    "INSERT INTO service (staff_id, service_type) VALUES (?, ?)",
                    "SELECT service_id, staff_id, service_type FROM service WHERE staff_id = ? AND service_type = ?"
                ],
                'communityservice' => [
                    "INSERT INTO communityservice (staff_id, description, beneficiaries) VALUES (?, ?, ?)",
                    "SELECT community_service_id, staff_id, description, beneficiaries FROM communityservice WHERE staff_id = ? AND description = ? AND beneficiaries = ?"
                ],
                'professionalbodies' => [
                    "INSERT INTO professionalbodies (staff_id, body_name) VALUES (?, ?)",
                    "SELECT professional_body_id, staff_id, body_name FROM professionalbodies WHERE staff_id = ? AND body_name = ?"
                ],
                'degrees' => [
                    "INSERT INTO degrees (staff_id, degree_name, degree_classification) VALUES (?, ?, ?)",
                    "SELECT degree_id, staff_id, degree_name, degree_classification FROM degrees WHERE staff_id = ? AND degree_name = ? AND degree_classification = ?"
                ]
            ];

            if (!isset($query_map[$table])) {
                $_SESSION['notification'] = [
                    'type' => 'error',
                    'message' => "Invalid table selection. No query mapping found for table: {$table}"
                ];
                fclose($handle);
                header('Location: upload_csv.php');
                exit;
            }

            [$insert_query, $check_query] = $query_map[$table];
            $stmt = $conn->prepare($insert_query);
            $check_stmt = $conn->prepare($check_query);

            if (!$stmt || !$check_stmt) {
                $_SESSION['notification'] = [
                    'type' => 'error',
                    'message' => "Database preparation failed: " . $conn->error
                ];
                fclose($handle);
                header('Location: upload_csv.php');
                exit;
            }

            $is_updated = false;
            $new_records = 0;
            $updated_records = 0;
            $skipped_records = 0;
            $error_records = 0;
            $error_details = [];
            $roles = [];
            $departments = [];
            $faculties = [];

            // Fetch foreign keys if necessary
            if ($table === 'staff') {
                $role_result = $conn->query("SELECT role_id, role_name FROM roles");
                if (!$role_result) {
                    $_SESSION['notification'] = [
                        'type' => 'error',
                        'message' => "Failed to fetch roles: " . $conn->error
                    ];
                    fclose($handle);
                    header('Location: upload_csv.php');
                    exit;
                }
                while ($row = $role_result->fetch_assoc()) {
                    $roles[$row['role_name']] = $row['role_id'];
                }
                
                $department_result = $conn->query("SELECT department_id, department_name FROM departments");
                if (!$department_result) {
                    $_SESSION['notification'] = [
                        'type' => 'error',
                        'message' => "Failed to fetch departments: " . $conn->error
                    ];
                    fclose($handle);
                    header('Location: upload_csv.php');
                    exit;
                }
                while ($row = $department_result->fetch_assoc()) {
                    $departments[$row['department_name']] = $row['department_id'];
                }
            } elseif ($table === 'departments') {
                $faculty_result = $conn->query("SELECT faculty_id, faculty_name FROM faculties");
                if (!$faculty_result) {
                    $_SESSION['notification'] = [
                        'type' => 'error',
                        'message' => "Failed to fetch faculties: " . $conn->error
                    ];
                    fclose($handle);
                    header('Location: upload_csv.php');
                    exit;
                }
                while ($row = $faculty_result->fetch_assoc()) {
                    $faculties[$row['faculty_name']] = $row['faculty_id'];
                }
            }

            $conn->begin_transaction();
            $line_number = 1; // Start counting from header + 1

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $line_number++;
                
                // Skip empty rows
                if (count(array_filter($data)) === 0) {
                    $skipped_records++;
                    continue;
                }

                // Handle column mismatch by truncating or padding data
                if ($received_columns != $expected_columns[$table]) {
                    if ($received_columns < $expected_columns[$table]) {
                        // Pad with nulls for missing columns
                        $data = array_pad($data, $expected_columns[$table], null);
                    } else {
                        // Truncate extra columns
                        $data = array_slice($data, 0, $expected_columns[$table]);
                    }
                }

                $should_insert = false;
                $record_errors = [];
                
                // Validate data types and foreign keys
                switch ($table) {
                    case 'departments':
                        if (!isset($faculties[$data[1]])) {
                            $record_errors[] = "Faculty '{$data[1]}' not found in database";
                        }
                        break;
                    case 'staff':
                        if (!isset($roles[$data[3]])) {
                            $record_errors[] = "Role '{$data[3]}' not found in database";
                        }
                        if (!isset($departments[$data[4]])) {
                            $record_errors[] = "Department '{$data[4]}' not found in database";
                        }
                        if (!is_numeric($data[5])) {
                            $record_errors[] = "Years of experience must be numeric";
                        }
                        if (!is_numeric($data[6])) {
                            $record_errors[] = "Performance score must be numeric";
                        }
                        break;
                    case 'grants':
                        if (!is_numeric($data[1])) {
                            $record_errors[] = "Grant amount must be numeric";
                        }
                        break;
                }

                if (!empty($record_errors)) {
                    $error_details[] = "Line {$line_number}: " . implode(", ", $record_errors);
                    $error_records++;
                    continue;
                }

                // Bind parameters for checking existing records
                switch ($table) {
                    case 'roles':
                    case 'faculties':
                        $check_stmt->bind_param("s", $data[0]);
                        break;
                    case 'departments':
                        $check_stmt->bind_param("s", $data[0]);
                        break;
                    case 'staff':
                        $check_stmt->bind_param("ss", $data[0], $data[1]);
                        break;
                    case 'publications':
                        $check_stmt->bind_param("iss", $data[0], $data[1], $data[2]);
                        break;
                    case 'grants':
                        $check_stmt->bind_param("id", $data[0], $data[1]);
                        break;
                    case 'supervision':
                        $check_stmt->bind_param("is", $data[0], $data[1]);
                        break;
                    case 'innovations':
                        $check_stmt->bind_param("is", $data[0], $data[1]);
                        break;
                    case 'academicactivities':
                        $check_stmt->bind_param("is", $data[0], $data[1]);
                        break;
                    case 'service':
                        $check_stmt->bind_param("is", $data[0], $data[1]);
                        break;
                    case 'communityservice':
                        $check_stmt->bind_param("iss", $data[0], $data[1], $data[2]);
                        break;
                    case 'professionalbodies':
                        $check_stmt->bind_param("is", $data[0], $data[1]);
                        break;
                    case 'degrees':
                        $check_stmt->bind_param("iss", $data[0], $data[1], $data[2]);
                        break;
                }

                if (!$check_stmt->execute()) {
                    $error_details[] = "Line {$line_number}: Failed to check existing record - " . $check_stmt->error;
                    $error_records++;
                    continue;
                }
                
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
                            $has_changes = ($existing_data['faculty_id'] != $faculties[$data[1]]);
                            break;
                        case 'staff':
                            $has_changes = ($existing_data['scholar_type'] != $data[2] ||
                                           $existing_data['role_id'] != $roles[$data[3]] ||
                                           $existing_data['department_id'] != $departments[$data[4]] ||
                                           $existing_data['years_of_experience'] != $data[5] ||
                                           $existing_data['performance_score'] != $data[6]);
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
                    } else {
                        $skipped_records++;
                    }
                }

                if ($should_insert) {
                    // Bind parameters for insertion
                    switch ($table) {
                        case 'roles':
                        case 'faculties':
                            $stmt->bind_param("s", $data[0]);
                            break;
                        case 'departments':
                            $faculty_id = $faculties[$data[1]] ?? null;
                            if (!$faculty_id) {
                                $error_details[] = "Line {$line_number}: Faculty '{$data[1]}' not found";
                                $error_records++;
                                continue 2;
                            }
                            $stmt->bind_param("si", $data[0], $faculty_id);
                            break;
                        case 'staff':
                            $role_id = $roles[$data[3]] ?? null;
                            $department_id = $departments[$data[4]] ?? null;
                            if (!$role_id || !$department_id) {
                                $missing = [];
                                if (!$role_id) $missing[] = "role '{$data[3]}'";
                                if (!$department_id) $missing[] = "department '{$data[4]}'";
                                $error_details[] = "Line {$line_number}: " . implode(" and ", $missing) . " not found";
                                $error_records++;
                                continue 2;
                            }
                            $stmt->bind_param("sssiiii", $data[0], $data[1], $data[2], $role_id, $department_id, $data[5], $data[6]);
                            break;
                        case 'publications':
                            $stmt->bind_param("iss", $data[0], $data[1], $data[2]);
                            break;
                        case 'grants':
                            $stmt->bind_param("id", $data[0], $data[1]);
                            break;
                        case 'supervision':
                            $stmt->bind_param("is", $data[0], $data[1]);
                            break;
                        case 'innovations':
                            $stmt->bind_param("is", $data[0], $data[1]);
                            break;
                        case 'academicactivities':
                            $stmt->bind_param("is", $data[0], $data[1]);
                            break;
                        case 'service':
                            $stmt->bind_param("is", $data[0], $data[1]);
                            break;
                        case 'communityservice':
                            $stmt->bind_param("iss", $data[0], $data[1], $data[2]);
                            break;
                        case 'professionalbodies':
                            $stmt->bind_param("is", $data[0], $data[1]);
                            break;
                        case 'degrees':
                            $stmt->bind_param("iss", $data[0], $data[1], $data[2]);
                            break;
                    }

                    if ($stmt->execute()) {
                        $is_updated = true;
                    } else {
                        $error_details[] = "Line {$line_number}: Failed to insert record - " . $stmt->error;
                        $error_records++;
                    }
                }
            }

            if ($conn->commit()) {
                $is_updated = true;
            } else {
                $error_details[] = "Commit failed: " . $conn->error;
                $error_records++;
            }
            
            fclose($handle);

            // Clear session data
            unset($_SESSION['csv_validation']);
            unset($_SESSION['pending_upload']);

            if ($is_updated) {
                $message = "Data processing completed for {$table}. ";
                $message .= "Records: {$new_records} new, {$updated_records} updated, {$skipped_records} unchanged";
                
                if ($error_records > 0) {
                    $message .= ", {$error_records} with errors";
                    $_SESSION['error_details'] = $error_details;
                }
                
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => $message
                ];
            } else {
                $message = "No new or changed data found in the CSV file.";
                
                if ($error_records > 0) {
                    $message .= " Found {$error_records} records with errors.";
                    $_SESSION['error_details'] = $error_details;
                }
                
                $_SESSION['notification'] = [
                    'type' => 'info',
                    'message' => $message
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'error',
                'message' => "Failed to open the CSV file. Please check if the file is not corrupted and is a valid CSV."
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'error',
            'message' => "No file uploaded or file upload error. Please ensure you selected a file and the file size does not exceed the limit."
        ];
    }
} else {
    $_SESSION['notification'] = [
        'type' => 'error',
        'message' => "Invalid request method. Please use the form to upload files."
    ];
}

header('Location: upload_csv.php');
exit;