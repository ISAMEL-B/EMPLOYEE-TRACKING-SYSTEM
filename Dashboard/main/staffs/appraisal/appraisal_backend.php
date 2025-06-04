<?php
session_start();
include '../../head/approve/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized access',
        'message' => 'Please login to access this resource'
    ]);
    exit();
}

// Initialize response array
$response = [
    'success' => false,
    'error' => null,
    'message' => null,
    'data' => null
];

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['staff_id'];

// Main try-catch block for the entire script
try {
    // Validate user ID
    if (!filter_var($user_id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
        throw new Exception('Invalid user ID');
    }

    if ($method === 'POST') {
        // Get and validate JSON input
        $json_input = file_get_contents('php://input');
        if (empty($json_input)) {
            throw new Exception('No input data received');
        }

        $input = json_decode($json_input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input: ' . json_last_error_msg());
        }

        // Validate page number
        $page = isset($input['page']) ? (int)$input['page'] : 0;
        if ($page < 1 || $page > 5) {
            throw new Exception('Invalid page number (must be 1-5)');
        }

        $data = $input['data'] ?? [];
        $is_final = isset($input['final_submit']) ? (bool)$input['final_submit'] : false;

        // Validate data based on page
        $validation_errors = validatePageData($page, $data);
        if (!empty($validation_errors)) {
            throw new Exception('Validation failed: ' . implode(', ', $validation_errors));
        }

        // Prepare data for storage
        $json_data = json_encode($data);
        if ($json_data === false) {
            throw new Exception('Failed to encode data for storage');
        }

        $status = $is_final ? 'completed' : 'in_progress';
        $current_time = date('Y-m-d H:i:s');

        // Check if progress exists
        $check_sql = "SELECT progress_id FROM user_progress WHERE user_id = ? AND page_number = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $user_id, $page);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result && $check_result->num_rows > 0) {
            // Update existing progress
            $row = $check_result->fetch_assoc();
            $progress_id = $row['progress_id'];
            
            $update_sql = "UPDATE user_progress SET 
                          data = ?, 
                          status = ?, 
                          updated_at = ? 
                          WHERE progress_id = ?";
            
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $json_data, $status, $current_time, $progress_id);
            
            if (!$update_stmt->execute()) {
                throw new Exception('Failed to update progress: ' . $update_stmt->error);
            }
            $update_stmt->close();
        } else {
            // Create new progress record
            $insert_sql = "INSERT INTO user_progress 
                          (user_id, page_number, data, status, created_at, updated_at) 
                          VALUES 
                          (?, ?, ?, ?, ?, ?)";
            
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iissss", $user_id, $page, $json_data, $status, $current_time, $current_time);
            
            if (!$insert_stmt->execute()) {
                throw new Exception('Failed to save progress: ' . $insert_stmt->error);
            }
            $insert_stmt->close();
        }

        // Handle final submission
        if ($is_final && $page === 5) {
            processFullSubmission($user_id, $conn);
            $response['message'] = 'Appraisal submitted successfully!';
        } else {
            $response['message'] = 'Progress saved successfully';
        }

        $response['success'] = true;

    } elseif ($method === 'GET') {
        // Retrieve user progress with prepared statement
        $progress_sql = "SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number";
        $progress_stmt = $conn->prepare($progress_sql);
        $progress_stmt->bind_param("i", $user_id);
        $progress_stmt->execute();
        $progress_result = $progress_stmt->get_result();

        $progress_data = [];
        if ($progress_result && $progress_result->num_rows > 0) {
            while ($row = $progress_result->fetch_assoc()) {
                $decoded_data = json_decode($row['data'], true);
                if ($decoded_data !== null) {
                    $progress_data[$row['page_number']] = $decoded_data;
                }
            }
        }
        $progress_stmt->close();

        // Get user info with prepared statement
        $user_sql = "SELECT * FROM staff WHERE staff_id = ?";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user_data = $user_result && $user_result->num_rows > 0 ? $user_result->fetch_assoc() : null;
        $user_stmt->close();

        // Get departments
        $dept_sql = "SELECT * FROM departments";
        $dept_result = $conn->query($dept_sql);
        $departments = [];

        if ($dept_result && $dept_result->num_rows > 0) {
            while ($row = $dept_result->fetch_assoc()) {
                $departments[] = $row;
            }
        }

        $response['success'] = true;
        $response['data'] = [
            'progress' => $progress_data,
            'user' => $user_data,
            'departments' => $departments
        ];
    } else {
        throw new Exception('Method not allowed', 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    $response['error'] = $e->getMessage();
    error_log("Appraisal Error: " . $e->getMessage() . "\nStack Trace: " . $e->getTraceAsString());
}

// Send JSON response
echo json_encode($response);

/**
 * Process full submission of all appraisal data
 */
function processFullSubmission($staff_id, $conn) {
    // Begin transaction
    $conn->autocommit(false);
    $all_success = true;

    try {
        // Get all saved data
        $sql = "SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $full_data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $decoded_data = json_decode($row['data'], true);
                if ($decoded_data !== null) {
                    $full_data[$row['page_number']] = $decoded_data;
                }
            }
        }
        $stmt->close();

        // Process biodata (page 1)
        if (isset($full_data[1])) {
            $biodata = $full_data[1];
            
            // Prepare update statement
            $update_sql = "UPDATE staff SET 
                          first_name = ?, 
                          last_name = ?, 
                          email = ?, 
                          phone_number = ?, 
                          scholar_type = ?, 
                          department_id = ?, 
                          years_of_experience = ? 
                          WHERE staff_id = ?";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param(
                "sssssisi",
                $biodata['first_name'] ?? '',
                $biodata['last_name'] ?? '',
                $biodata['email'] ?? '',
                $biodata['phone_number'] ?? '',
                $biodata['scholar_type'] ?? '',
                $biodata['department_id'] ?? null,
                $biodata['years_of_experience'] ?? 0,
                $user_id
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to update staff record: " . $stmt->error);
            }
            $stmt->close();
        }

        // Process degrees (page 2)
        if (isset($full_data[2]['degrees'])) {
            // Delete existing degrees
            $delete_sql = "DELETE FROM degrees WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to clear degrees: " . $stmt->error);
            }
            $stmt->close();

            // Insert new degrees
            $degree_sql = "INSERT INTO degrees 
                          (staff_id, degree_name, degree_classification, institution, year_obtained, verification_status) 
                          VALUES (?, ?, ?, ?, ?, 'approved')";

            $stmt = $conn->prepare($degree_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($full_data[2]['degrees'] as $degree) {
                $stmt->bind_param(
                    "issss",
                    $user_id,
                    $degree['degree_name'] ?? '',
                    $degree['degree_classification'] ?? '',
                    $degree['institution'] ?? '',
                    $degree['year_obtained'] ?? ''
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert degree: " . $stmt->error);
                }
            }
            $stmt->close();
        }

        // Process publications (page 3)
        if (isset($full_data[3]['publications'])) {
            // Delete existing publications
            $delete_sql = "DELETE FROM publications WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to clear publications: " . $stmt->error);
            }
            $stmt->close();

            // Insert new publications
            $pub_sql = "INSERT INTO publications 
                       (staff_id, publication_type, role, title, journal_name, publication_date, verification_status) 
                       VALUES (?, ?, ?, ?, ?, ?, 'approved')";

            $stmt = $conn->prepare($pub_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($full_data[3]['publications'] as $pub) {
                $pub_date = !empty($pub['publication_date']) ? date('Y-m-d', strtotime($pub['publication_date'])) : null;
                
                $stmt->bind_param(
                    "isssss",
                    $user_id,
                    $pub['publication_type'] ?? '',
                    $pub['role'] ?? '',
                    $pub['title'] ?? '',
                    $pub['journal_name'] ?? '',
                    $pub_date
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert publication: " . $stmt->error);
                }
            }
            $stmt->close();
        }

        // Process grants (page 4)
        if (isset($full_data[4]['grants'])) {
            // Delete existing grants
            $delete_sql = "DELETE FROM grants WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to clear grants: " . $stmt->error);
            }
            $stmt->close();

            // Insert new grants
            $grant_sql = "INSERT INTO grants 
                         (staff_id, grant_name, funding_agency, grant_amount, grant_year, role, description, verification_status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')";

            $stmt = $conn->prepare($grant_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($full_data[4]['grants'] as $grant) {
                $stmt->bind_param(
                    "issdsss",
                    $user_id,
                    $grant['grant_name'] ?? '',
                    $grant['funding_agency'] ?? '',
                    $grant['grant_amount'] ?? 0,
                    $grant['grant_year'] ?? '',
                    $grant['role'] ?? '',
                    $grant['description'] ?? ''
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert grant: " . $stmt->error);
                }
            }
            $stmt->close();
        }

        // Process activities (page 5)
// Assuming you have a database connection $conn
function distributeUserProgressData($user_id, $conn) {
    try {
        // Begin transaction
        $conn->begin_transaction();

        // 1. Get all progress data for this user
        $progress_sql = "SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number";
        $stmt = $conn->prepare($progress_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $full_data = [];
        while ($row = $result->fetch_assoc()) {
            $full_data[$row['page_number']] = json_decode($row['data'], true);
        }
        $stmt->close();

        // 2. Process basic staff information (page 1)
        if (isset($full_data[1])) {
            $staff_data = $full_data[1];
            
            // Check if staff exists
            $check_sql = "SELECT staff_id FROM staff WHERE staff_id = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $exists = $stmt->get_result()->num_rows > 0;
            $stmt->close();

            if ($exists) {
                // Update existing staff
                $update_sql = "UPDATE staff SET 
                              first_name = ?, last_name = ?, email = ?, phone_number = ?, 
                              employee_id = ?, scholar_type = ?, department_id = ?, 
                              years_of_experience = ?
                              WHERE staff_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param(
                    "ssssssiii",
                    $staff_data['first_name'] ?? '',
                    $staff_data['last_name'] ?? '',
                    $staff_data['email'] ?? '',
                    $staff_data['phone_number'] ?? '',
                    $staff_data['employee_id'] ?? '',
                    $staff_data['scholar_type'] ?? '',
                    $staff_data['department_id'] ?? 0,
                    $staff_data['years_of_experience'] ?? 0,
                    $user_id
                );
                $stmt->execute();
                $stmt->close();
            } else {
                // Insert new staff
                $insert_sql = "INSERT INTO staff (
                              staff_id, first_name, last_name, email, phone_number, 
                              employee_id, scholar_type, department_id, years_of_experience
                              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param(
                    "issssssii",
                    $user_id,
                    $staff_data['first_name'] ?? '',
                    $staff_data['last_name'] ?? '',
                    $staff_data['email'] ?? '',
                    $staff_data['phone_number'] ?? '',
                    $staff_data['employee_id'] ?? '',
                    $staff_data['scholar_type'] ?? '',
                    $staff_data['department_id'] ?? 0,
                    $staff_data['years_of_experience'] ?? 0
                );
                $stmt->execute();
                $stmt->close();
            }
        }

        // 3. Process degrees (page 2)
        if (isset($full_data[2]['degrees'])) {
            // Delete existing degrees
            $delete_sql = "DELETE FROM degrees WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Insert new degrees
            $degree_sql = "INSERT INTO degrees (
                          staff_id, degree_name, degree_classification, 
                          institution, year_obtained, verification_status
                          ) VALUES (?, ?, ?, ?, ?, 'approved')";
            $stmt = $conn->prepare($degree_sql);

            foreach ($full_data[2]['degrees'] as $degree) {
                $stmt->bind_param(
                    "issss",
                    $user_id,
                    $degree['degree_name'] ?? '',
                    $degree['degree_classification'] ?? '',
                    $degree['institution'] ?? '',
                    $degree['year_obtained'] ?? ''
                );
                $stmt->execute();
            }
            $stmt->close();
        }

        // 4. Process publications (page 3)
        if (isset($full_data[3]['publications'])) {
            // Delete existing publications
            $delete_sql = "DELETE FROM publications WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Insert new publications
            $pub_sql = "INSERT INTO publications (
                       staff_id, publication_type, role, title, 
                       journal_name, publication_date, verification_status
                       ) VALUES (?, ?, ?, ?, ?, ?, 'approved')";
            $stmt = $conn->prepare($pub_sql);

            foreach ($full_data[3]['publications'] as $pub) {
                $stmt->bind_param(
                    "isssss",
                    $user_id,
                    $pub['publication_type'] ?? '',
                    $pub['role'] ?? '',
                    $pub['title'] ?? '',
                    $pub['journal_name'] ?? '',
                    $pub['publication_date'] ?? ''
                );
                $stmt->execute();
            }
            $stmt->close();
        }

        // 5. Process grants (page 4)
        if (isset($full_data[4]['grants'])) {
            // Delete existing grants
            $delete_sql = "DELETE FROM grants WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Insert new grants
            $grant_sql = "INSERT INTO grants (
                         staff_id, grant_name, funding_agency, grant_amount, 
                         grant_year, role, description, verification_status
                         ) VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')";
            $stmt = $conn->prepare($grant_sql);

            foreach ($full_data[4]['grants'] as $grant) {
                $stmt->bind_param(
                    "issdiss",
                    $user_id,
                    $grant['grant_name'] ?? '',
                    $grant['funding_agency'] ?? '',
                    $grant['grant_amount'] ?? 0,
                    $grant['grant_year'] ?? '',
                    $grant['role'] ?? '',
                    $grant['description'] ?? ''
                );
                $stmt->execute();
            }
            $stmt->close();
        }

        // 6. Process academic activities (page 5)
        if (isset($full_data[5]['activities'])) {
            // Delete existing activities
            $delete_sql = "DELETE FROM academicactivities WHERE staff_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Insert new activities
            $activity_sql = "INSERT INTO academicactivities (
                            staff_id, activity_type, title, date, role, 
                            location, description, verification_status
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')";
            $stmt = $conn->prepare($activity_sql);

            foreach ($full_data[5]['activities'] as $activity) {
                $stmt->bind_param(
                    "issssss",
                    $user_id,
                    $activity['activity_type'] ?? '',
                    $activity['title'] ?? '',
                    $activity['date'] ?? '',
                    $activity['role'] ?? '',
                    $activity['location'] ?? '',
                    $activity['description'] ?? ''
                );
                $stmt->execute();
            }
            $stmt->close();
        }

        // Commit transaction
        $conn->commit();
        
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        error_log("Error distributing user progress data: " . $e->getMessage());
        return false;
    }
}

// Usage example when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_details'])) {
    $user_id = $_SESSION['staff_id']; // Assuming you have user ID in session
    
    if (distributeUserProgressData($user_id, $conn)) {
        $_SESSION['success_message'] = "Your data has been successfully submitted!";
        
        // Optionally mark progress as complete
        $update_sql = "UPDATE user_progress SET status = 'complete' WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: success_page.php");
        exit();
    } else {
        $_SESSION['error_message'] = "There was an error submitting your data. Please try again.";
        header("Location: error_page.php");
        exit();
    }
}

        // Commit transaction if all queries succeeded
        $conn->commit();
        $conn->autocommit(true);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $conn->autocommit(true);
        throw $e;
    }
}

/**
 * Validate data for each page
 */
function validatePageData($page, $data) {
    $errors = [];

    switch ($page) {
        case 1: // Biodata
            if (empty($data['first_name'])) $errors[] = 'First name is required';
            if (empty($data['last_name'])) $errors[] = 'Last name is required';
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required';
            }
            if (empty($data['phone_number'])) $errors[] = 'Phone number is required';
            if (empty($data['scholar_type'])) $errors[] = 'Scholar type is required';
            if (empty($data['department_id']) || !filter_var($data['department_id'], FILTER_VALIDATE_INT)) {
                $errors[] = 'Valid department is required';
            }
            if (!isset($data['years_of_experience']) || !is_numeric($data['years_of_experience'])) {
                $errors[] = 'Years of experience must be a number';
            }
            break;

        case 2: // Degrees
            if (empty($data['degrees']) || !is_array($data['degrees'])) {
                $errors[] = 'At least one degree is required';
            } else {
                foreach ($data['degrees'] as $index => $degree) {
                    if (empty($degree['degree_name'])) {
                        $errors[] = "Degree #" . ($index + 1) . " name is required";
                    }
                    if (empty($degree['degree_classification'])) {
                        $errors[] = "Degree #" . ($index + 1) . " classification is required";
                    }
                    if (empty($degree['institution'])) {
                        $errors[] = "Degree #" . ($index + 1) . " institution is required";
                    }
                    if (empty($degree['year_obtained']) || !is_numeric($degree['year_obtained']) || 
                        $degree['year_obtained'] < 1900 || $degree['year_obtained'] > date('Y')) {
                        $errors[] = "Degree #" . ($index + 1) . " year must be between 1900 and current year";
                    }
                }
            }
            break;

        case 3: // Publications
            if (empty($data['publications']) || !is_array($data['publications'])) {
                $errors[] = 'At least one publication is required';
            } else {
                foreach ($data['publications'] as $index => $pub) {
                    if (empty($pub['publication_type'])) {
                        $errors[] = "Publication #" . ($index + 1) . " type is required";
                    }
                    if (empty($pub['role'])) {
                        $errors[] = "Publication #" . ($index + 1) . " role is required";
                    }
                    if (empty($pub['title'])) {
                        $errors[] = "Publication #" . ($index + 1) . " title is required";
                    }
                    if (empty($pub['publication_date']) || !strtotime($pub['publication_date'])) {
                        $errors[] = "Publication #" . ($index + 1) . " valid date is required";
                    }
                }
            }
            break;

        case 4: // Grants
            if (empty($data['grants']) || !is_array($data['grants'])) {
                $errors[] = 'At least one grant is required';
            } else {
                foreach ($data['grants'] as $index => $grant) {
                    if (empty($grant['grant_name'])) {
                        $errors[] = "Grant #" . ($index + 1) . " name is required";
                    }
                    if (empty($grant['funding_agency'])) {
                        $errors[] = "Grant #" . ($index + 1) . " funding agency is required";
                    }
                    if (!isset($grant['grant_amount']) || !is_numeric($grant['grant_amount'])) {
                        $errors[] = "Grant #" . ($index + 1) . " amount must be a number";
                    }
                    if (empty($grant['grant_year']) || !is_numeric($grant['grant_year']) || 
                        $grant['grant_year'] < 1900 || $grant['grant_year'] > date('Y')) {
                        $errors[] = "Grant #" . ($index + 1) . " year must be between 1900 and current year";
                    }
                    if (empty($grant['role'])) {
                        $errors[] = "Grant #" . ($index + 1) . " role is required";
                    }
                }
            }
            break;

        case 5: // Activities
            if (empty($data['activities']) || !is_array($data['activities'])) {
                $errors[] = 'At least one activity is required';
            } else {
                foreach ($data['activities'] as $index => $activity) {
                    if (empty($activity['activity_type'])) {
                        $errors[] = "Activity #" . ($index + 1) . " type is required";
                    }
                    if (empty($activity['title'])) {
                        $errors[] = "Activity #" . ($index + 1) . " title is required";
                    }
                    if (empty($activity['date']) || !strtotime($activity['date'])) {
                        $errors[] = "Activity #" . ($index + 1) . " valid date is required";
                    }
                    if (empty($activity['role'])) {
                        $errors[] = "Activity #" . ($index + 1) . " role is required";
                    }
                    if (empty($activity['location'])) {
                        $errors[] = "Activity #" . ($index + 1) . " location is required";
                    }
                }
            }
            break;
    }

    return $errors;
}