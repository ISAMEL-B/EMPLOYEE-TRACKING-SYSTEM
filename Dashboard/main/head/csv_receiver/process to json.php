<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create uploads directory if it doesn't exist
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Connect to the database
include '../../db/config.php';

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$notification = ''; // Variable to store message for the user
$errorFields = [];  // To store any field errors

// Define the expected column count for each table
$expected_columns = [
    'roles' => 3,
    'degrees' => 4,
    'departments' => 2,
    'staff' => 8,
    'publications' => 4,
    'grants' => 4,
    'supervision' => 4,
    'innovations' => 4,
    'academic_activities' => 4,
    'service' => 4,
    'community_service' => 4,
    'professional_bodies' => 4,
];

// Handle CSV file upload and data insertion into the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table_name'];
    $file = $_FILES['csv_file']['tmp_name'];

    if (is_uploaded_file($file)) {
        $file_name = $_FILES['csv_file']['name'];
        $destination = "uploads/" . $file_name;

        move_uploaded_file($file, $destination);

        if (($handle = fopen($destination, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            $header_column_count = count($header);

            if ($header_column_count != $expected_columns[$table]) {
                $_SESSION['notification'] = 'CSV has incorrect number of fields. Expected ' . $expected_columns[$table] . ', but got ' . $header_column_count . '.';
                fclose($handle);
                header('Location: upload.php');
                exit;
            } else {
                // Prepare the insert and check statement based on the selected table
                switch ($table) {
                    case 'roles':
                        $query = "INSERT INTO Roles (role_name, max_points) VALUES (?, ?)";
                        $check_query = "SELECT * FROM Roles WHERE role_name = ? AND max_points = ?";
                        break;
                    case 'degrees':
                        $query = "INSERT INTO Degrees (degree_name, degree_classification, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM Degrees WHERE degree_name = ? AND degree_classification = ? AND points = ?";
                        break;
                    case 'departments':
                        $query = "INSERT INTO Departments (department_name) VALUES (?)";
                        $check_query = "SELECT * FROM Departments WHERE department_name = ?";
                        break;
                    case 'staff':
                        $query = "INSERT INTO Staff (first_name, last_name, role_id, degree_id, department_id, years_of_experience, performance_score) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $check_query = "SELECT * FROM Staff WHERE first_name = ? AND last_name = ? AND role_id = ? AND degree_id = ? AND department_id = ? AND years_of_experience = ? AND performance_score = ?";
                        break;
                    case 'publications':
                        $query = "INSERT INTO Publications (staff_id, publication_type, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM Publications WHERE staff_id = ? AND publication_type = ?";
                        break;
                    case 'grants':
                        $query = "INSERT INTO Grants (staff_id, grant_amount, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM Grants WHERE staff_id = ? AND grant_amount = ?";
                        break;
                    case 'supervision':
                        $query = "INSERT INTO Supervision (staff_id, student_level, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM Supervision WHERE staff_id = ? AND student_level = ?";
                        break;
                    case 'innovations':
                        $query = "INSERT INTO Innovations (staff_id, innovation_type, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM Innovations WHERE staff_id = ? AND innovation_type = ?";
                        break;
                    case 'academic_activities':
                        $query = "INSERT INTO AcademicActivities (staff_id, activity_type, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM AcademicActivities WHERE staff_id = ? AND activity_type = ?";
                        break;
                    case 'service':
                        $query = "INSERT INTO Service (staff_id, service_type, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM Service WHERE staff_id = ? AND service_type = ?";
                        break;
                    case 'community_service':
                        $query = "INSERT INTO CommunityService (staff_id, description, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM CommunityService WHERE staff_id = ? AND description = ?";
                        break;
                    case 'professional_bodies':
                        $query = "INSERT INTO ProfessionalBodies (staff_id, body_name, points) VALUES (?, ?, ?)";
                        $check_query = "SELECT * FROM ProfessionalBodies WHERE staff_id = ? AND body_name = ?";
                        break;
                    default:
                        echo 'Invalid table selected.';
                        exit;
                }

                // Prepare the SQL statements
                $stmt = $conn->prepare($query);
                $check_stmt = $conn->prepare($check_query);

                $is_updated = false;

                // Fetch foreign keys for roles, degrees, and departments once
                $roles = [];
                $degrees = [];
                $departments = [];

                $role_result = $conn->query("SELECT role_id, role_name FROM Roles");
                while ($row = $role_result->fetch_assoc()) {
                    $roles[$row['role_name']] = $row['role_id'];
                }

                $degree_result = $conn->query("SELECT degree_id, degree_name FROM Degrees");
                while ($row = $degree_result->fetch_assoc()) {
                    $degrees[$row['degree_name']] = $row['degree_id'];
                }

                $department_result = $conn->query("SELECT department_id, department_name FROM Departments");
                while ($row = $department_result->fetch_assoc()) {
                    $departments[$row['department_name']] = $row['department_id'];
                }

                // Loop through each row in the CSV file
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // Check for missing fields in each row
                    if (count($data) != $expected_columns[$table]) {
                        $errorFields[] = 'Missing or extra fields in row: ' . json_encode($data);
                        continue;  // Skip this row if incorrect number of fields
                    }

                    // Bind parameters for each row based on the table
                    switch ($table) {
                        case 'roles':
                            $check_stmt->bind_param("si", $data[1], $data[2]);
                            break;
                        case 'degrees':
                            $check_stmt->bind_param("ssi", $data[1], $data[2], $data[3]);
                            break;
                        case 'departments':
                            $check_stmt->bind_param("s", $data[1]);
                            break;
                        case 'staff':
                            // Fetch foreign keys for role_id, degree_id, and department_id
                            $role_id = $roles[$data[3]] ?? null;
                            $degree_id = $degrees[$data[4]] ?? null;
                            $department_id = $departments[$data[5]] ?? null;

                            if ($role_id === null || $degree_id === null || $department_id === null) {
                                $errorFields[] = 'Invalid foreign key data in row: ' . json_encode($data);
                                continue;  // Skip this row if any foreign key is invalid
                            }

                            $check_stmt->bind_param("ssiiiii", $data[1], $data[2], $role_id, $degree_id, $department_id, $data[6], $data[7]);
                            break;
                        case 'publications':
                            $check_stmt->bind_param("is", $data[1], $data[2]);
                            break;
                        case 'grants':
                            $check_stmt->bind_param("id", $data[1], $data[2]);
                            break;
                        case 'supervision':
                            $check_stmt->bind_param("is", $data[1], $data[2]);
                            break;
                        case 'innovations':
                            $check_stmt->bind_param("is", $data[1], $data[2]);
                            break;
                        case 'academic_activities':
                            $check_stmt->bind_param("is", $data[1], $data[2]);
                            break;
                        case 'service':
                            $check_stmt->bind_param("is", $data[1], $data[2]);
                            break;
                        case 'community_service':
                            $check_stmt->bind_param("is", $data[1], $data[2]);
                            break;
                        case 'professional_bodies':
                            $check_stmt->bind_param("is", $data[1], $data[2]);
                            break;
                    }

                    // Execute the check query to see if the record already exists
                    $check_stmt->execute();
                    $check_stmt->store_result();

                    // If the record doesn't exist, insert it
                    if ($check_stmt->num_rows === 0) {
                        // Bind the parameters for insertion
                        switch ($table) {
                            case 'roles':
                                $stmt->bind_param("si", $data[1], $data[2]);
                                break;
                            case 'degrees':
                                $stmt->bind_param("ssi", $data[1], $data[2], $data[3]);
                                break;
                            case 'departments':
                                $stmt->bind_param("s", $data[1]);
                                break;
                            case 'staff':
                                $stmt->bind_param("ssiiiii", $data[1], $data[2], $role_id, $degree_id, $department_id, $data[6], $data[7]);
                                break;
                            case 'publications':
                                $stmt->bind_param("isi", $data[1], $data[2], $data[3]);
                                break;
                            case 'grants':
                                $stmt->bind_param("idi", $data[1], $data[2], $data[3]);
                                break;
                            case 'supervision':
                                $stmt->bind_param("isi", $data[1], $data[2], $data[3]);
                                break;
                            case 'innovations':
                                $stmt->bind_param("isi", $data[1], $data[2], $data[3]);
                                break;
                            case 'academic_activities':
                                $stmt->bind_param("isi", $data[1], $data[2], $data[3]);
                                break;
                            case 'service':
                                $stmt->bind_param("isi", $data[1], $data[2], $data[3]);
                                break;
                            case 'community_service':
                                $stmt->bind_param("isi", $data[1], $data[2], $data[3]);
                                break;
                            case 'professional_bodies':
                                $stmt->bind_param("isi", $data[1], $data[2], $data[3]);
                                break;
                        }

                        // Insert the data
                        $stmt->execute();
                        $is_updated = true;
                    }
                }

                // Close the file and statement
                fclose($handle);
                $stmt->close();
                $check_stmt->close();

                if ($is_updated) {
                    $_SESSION['notification'] = 'CSV received successfully!';
                } else {
                    $_SESSION['notification'] = 'CSV contains no new data.';
                }

                // Display any errors if fields are missing
                if (!empty($errorFields)) {
                    $_SESSION['notification'] .= ' Some rows had missing or extra fields: ' . implode(", ", $errorFields);
                }
            }
        } else {
            $_SESSION['notification'] = 'Failed to open the uploaded file.';
        }
    } else {
        $_SESSION['notification'] = 'Failed to upload file.';
    }

    header('Location: upload.php');
    exit;
}
?>
