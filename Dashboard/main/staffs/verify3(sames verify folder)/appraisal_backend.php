<?php
session_start();
require_once '../config/db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
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
$user_id = $conn->real_escape_string($_SESSION['user_id']);

try {
    if ($method === 'POST') {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input');
        }

        // Validate and sanitize input
        $page = isset($input['page']) ? (int)$input['page'] : 0;
        if ($page < 1 || $page > 5) {
            throw new Exception('Invalid page number');
        }

        $data = $input['data'] ?? [];
        $is_final = isset($input['final_submit']) ? (bool)$input['final_submit'] : false;

        // Prepare data for storage
        $json_data = json_encode($data);
        $json_data = $conn->real_escape_string($json_data);
        $status = $is_final ? 'completed' : 'in_progress';
        $current_time = date('Y-m-d H:i:s');

        // Check if progress exists
        $check_sql = "SELECT progress_id FROM user_progress WHERE user_id = '$user_id' AND page_number = '$page'";
        $check_result = $conn->query($check_sql);

        if ($check_result && $check_result->num_rows > 0) {
            // Update existing progress
            $row = $check_result->fetch_assoc();
            $progress_id = $row['progress_id'];
            
            $update_sql = "UPDATE user_progress SET 
                          data = '$json_data', 
                          status = '$status', 
                          updated_at = '$current_time' 
                          WHERE progress_id = '$progress_id'";
            
            if (!$conn->query($update_sql)) {
                throw new Exception('Failed to update progress: ' . $conn->error);
            }
        } else {
            // Create new progress record
            $insert_sql = "INSERT INTO user_progress 
                          (user_id, page_number, data, status, created_at, updated_at) 
                          VALUES 
                          ('$user_id', '$page', '$json_data', '$status', '$current_time', '$current_time')";
            
            if (!$conn->query($insert_sql)) {
                throw new Exception('Failed to save progress: ' . $conn->error);
            }
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
        // Retrieve user progress
        $progress_sql = "SELECT page_number, data FROM user_progress WHERE user_id = '$user_id' ORDER BY page_number";
        $progress_result = $conn->query($progress_sql);

        $progress_data = [];
        if ($progress_result && $progress_result->num_rows > 0) {
            while ($row = $progress_result->fetch_assoc()) {
                $progress_data[$row['page_number']] = json_decode($row['data'], true);
            }
        }

        // Get user info
        $user_sql = "SELECT * FROM staff WHERE staff_id = '$user_id'";
        $user_result = $conn->query($user_sql);
        $user_data = $user_result && $user_result->num_rows > 0 ? $user_result->fetch_assoc() : null;

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
        throw new Exception('Method not allowed');
    }
} catch (Exception $e) {
    error_log("Appraisal Error: " . $e->getMessage());
    $response['error'] = $e->getMessage();
    http_response_code(500);
}

header('Content-Type: application/json');
echo json_encode($response);

function processFullSubmission($user_id, $conn) {
    // Begin transaction
    $conn->autocommit(false);
    $all_success = true;

    try {
        // Get all saved data
        $sql = "SELECT page_number, data FROM user_progress WHERE user_id = '$user_id' ORDER BY page_number";
        $result = $conn->query($sql);

        $full_data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $full_data[$row['page_number']] = json_decode($row['data'], true);
            }
        }

        // Process biodata (page 1)
        if (isset($full_data[1])) {
            $biodata = $full_data[1];
            $first_name = $conn->real_escape_string($biodata['first_name'] ?? '');
            $last_name = $conn->real_escape_string($biodata['last_name'] ?? '');
            $email = $conn->real_escape_string($biodata['email'] ?? '');
            $phone = $conn->real_escape_string($biodata['phone_number'] ?? '');
            $scholar_type = $conn->real_escape_string($biodata['scholar_type'] ?? '');
            $dept_id = $conn->real_escape_string($biodata['department_id'] ?? '');
            $experience = $conn->real_escape_string($biodata['years_of_experience'] ?? 0);

            $update_sql = "UPDATE staff SET 
                          first_name = '$first_name', 
                          last_name = '$last_name', 
                          email = '$email', 
                          phone_number = '$phone', 
                          scholar_type = '$scholar_type', 
                          department_id = '$dept_id', 
                          years_of_experience = '$experience' 
                          WHERE staff_id = '$user_id'";

            if (!$conn->query($update_sql)) {
                throw new Exception("Failed to update staff record: " . $conn->error);
            }

            // Handle photo upload if exists
            if (!empty($biodata['photo_path'])) {
                $photo_path = $conn->real_escape_string($biodata['photo_path']);
                $photo_sql = "UPDATE staff SET photo_path = '$photo_path' WHERE staff_id = '$user_id'";
                if (!$conn->query($photo_sql)) {
                    throw new Exception("Failed to update photo: " . $conn->error);
                }
            }
        }

        // Process degrees (page 2)
        if (isset($full_data[2]['degrees'])) {
            // Delete existing degrees
            $delete_sql = "DELETE FROM degrees WHERE staff_id = '$user_id'";
            if (!$conn->query($delete_sql)) {
                throw new Exception("Failed to clear degrees: " . $conn->error);
            }

            // Insert new degrees
            $degree_sql = "INSERT INTO degrees 
                          (staff_id, degree_name, degree_classification, institution, year_obtained, verification_status) 
                          VALUES (?, ?, ?, ?, ?, 'approved')";

            $stmt = $conn->prepare($degree_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($full_data[2]['degrees'] as $degree) {
                $name = $conn->real_escape_string($degree['degree_name'] ?? '');
                $classification = $conn->real_escape_string($degree['degree_classification'] ?? '');
                $institution = $conn->real_escape_string($degree['institution'] ?? '');
                $year = $conn->real_escape_string($degree['year_obtained'] ?? '');

                $stmt->bind_param("issss", $user_id, $name, $classification, $institution, $year);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert degree: " . $stmt->error);
                }
            }
            $stmt->close();
        }

        // Process publications (page 3)
        if (isset($full_data[3]['publications'])) {
            // Delete existing publications
            $delete_sql = "DELETE FROM publications WHERE staff_id = '$user_id'";
            if (!$conn->query($delete_sql)) {
                throw new Exception("Failed to clear publications: " . $conn->error);
            }

            // Insert new publications
            $pub_sql = "INSERT INTO publications 
                       (staff_id, publication_type, role, title, journal_name, publication_date, verification_status) 
                       VALUES (?, ?, ?, ?, ?, ?, 'approved')";

            $stmt = $conn->prepare($pub_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($full_data[3]['publications'] as $pub) {
                $type = $conn->real_escape_string($pub['publication_type'] ?? '');
                $role = $conn->real_escape_string($pub['role'] ?? '');
                $title = $conn->real_escape_string($pub['title'] ?? '');
                $journal = $conn->real_escape_string($pub['journal_name'] ?? '');
                $pub_date = date('Y-m-d', strtotime($pub['publication_date'] ?? 'now'));

                $stmt->bind_param("isssss", $user_id, $type, $role, $title, $journal, $pub_date);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert publication: " . $stmt->error);
                }
            }
            $stmt->close();
        }

        // Process grants (page 4)
        if (isset($full_data[4]['grants'])) {
            // Delete existing grants
            $delete_sql = "DELETE FROM grants WHERE staff_id = '$user_id'";
            if (!$conn->query($delete_sql)) {
                throw new Exception("Failed to clear grants: " . $conn->error);
            }

            // Insert new grants
            $grant_sql = "INSERT INTO grants 
                         (staff_id, grant_name, funding_agency, grant_amount, grant_year, role, description, verification_status) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')";

            $stmt = $conn->prepare($grant_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($full_data[4]['grants'] as $grant) {
                $name = $conn->real_escape_string($grant['grant_name'] ?? '');
                $agency = $conn->real_escape_string($grant['funding_agency'] ?? '');
                $amount = $conn->real_escape_string($grant['grant_amount'] ?? 0);
                $year = $conn->real_escape_string($grant['grant_year'] ?? '');
                $role = $conn->real_escape_string($grant['role'] ?? '');
                $desc = $conn->real_escape_string($grant['description'] ?? '');

                $stmt->bind_param("issdsss", $user_id, $name, $agency, $amount, $year, $role, $desc);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert grant: " . $stmt->error);
                }
            }
            $stmt->close();
        }

        // Process activities (page 5)
        if (isset($full_data[5]['activities'])) {
            // Delete existing activities
            $delete_sql = "DELETE FROM academicactivities WHERE staff_id = '$user_id'";
            if (!$conn->query($delete_sql)) {
                throw new Exception("Failed to clear activities: " . $conn->error);
            }

            // Insert new activities
            $activity_sql = "INSERT INTO academicactivities 
                            (staff_id, activity_type, title, role, location, description, verification_status) 
                            VALUES (?, ?, ?, ?, ?, ?, 'approved')";

            $stmt = $conn->prepare($activity_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($full_data[5]['activities'] as $activity) {
                $type = $conn->real_escape_string($activity['activity_type'] ?? '');
                $title = $conn->real_escape_string($activity['title'] ?? '');
                $role = $conn->real_escape_string($activity['role'] ?? '');
                $location = $conn->real_escape_string($activity['location'] ?? '');
                $desc = $conn->real_escape_string($activity['description'] ?? '');

                $stmt->bind_param("isssss", $user_id, $type, $title, $role, $location, $desc);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert activity: " . $stmt->error);
                }
            }
            $stmt->close();
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
?>