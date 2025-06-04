<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Save form data
        $page = (int)$_POST['page'];
        $data = json_encode($_POST['data']);
        
        // Check if progress record exists
        $stmt = $conn->prepare("SELECT progress_id FROM user_progress WHERE user_id = ? AND page_number = ?");
        $stmt->execute([$user_id, $page]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE user_progress SET data = ?, updated_at = NOW() WHERE progress_id = ?");
            $stmt->execute([$data, $exists['progress_id']]);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO user_progress (user_id, page_number, data) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $page, $data]);
        }
        
        // If this is the final submission (page 5)
        if ($page === 5 && isset($_POST['final_submit'])) {
            // Mark all pages as completed
            $stmt = $conn->prepare("UPDATE user_progress SET status = 'completed' WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            // Process the full submission (save to respective tables)
            processFullSubmission($user_id, $conn);
            
            $_SESSION['success_message'] = "Your appraisal has been successfully submitted!";
            header("Location: appraisal_process.php?page=5");
            exit();
        }
        
        // Return success response for AJAX
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        }
        
        // Redirect to next page
        $next_page = min($page + 1, 5);
        header("Location: appraisal_process.php?page=$next_page");
        exit();
        
    } catch (PDOException $e) {
        // Handle database errors
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit();
        }
        $_SESSION['error_message'] = "Error saving your data: " . $e->getMessage();
    }
}

// Function to process full submission
function processFullSubmission($user_id, $conn) {
    // Get all saved data
    $stmt = $conn->prepare("SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number");
    $stmt->execute([$user_id]);
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $full_data = [];
    foreach ($pages as $page) {
        $full_data[$page['page_number']] = json_decode($page['data'], true);
    }
    
    // Process biodata (page 1)
    if (isset($full_data[1])) {
        $biodata = $full_data[1];
        // Update staff table
        $stmt = $conn->prepare("UPDATE staff SET 
            first_name = ?, last_name = ?, email = ?, phone_number = ?, 
            scholar_type = ?, department_id = ?, years_of_experience = ?
            WHERE staff_id = ?");
        $stmt->execute([
            $biodata['first_name'], $biodata['last_name'], $biodata['email'], 
            $biodata['phone_number'], $biodata['scholar_type'], $biodata['department_id'], 
            $biodata['years_of_experience'], $user_id
        ]);
        
        // Handle photo upload if exists
        if (!empty($biodata['photo_path'])) {
            $stmt = $conn->prepare("UPDATE staff SET photo_path = ? WHERE staff_id = ?");
            $stmt->execute([$biodata['photo_path'], $user_id]);
        }
    }
    
    // Process degrees (page 2)
    if (isset($full_data[2]['degrees'])) {
        // First delete existing degrees for this user
        $conn->prepare("DELETE FROM degrees WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new degrees
        $stmt = $conn->prepare("INSERT INTO degrees 
            (staff_id, degree_name, degree_classification, institution, year_obtained, verification_status) 
            VALUES (?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[2]['degrees'] as $degree) {
            $stmt->execute([
                $user_id, $degree['degree_name'], $degree['degree_classification'], 
                $degree['institution'], $degree['year_obtained']
            ]);
        }
    }
    
    // Process publications (page 3)
    if (isset($full_data[3]['publications'])) {
        // Delete existing publications
        $conn->prepare("DELETE FROM publications WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new publications
        $stmt = $conn->prepare("INSERT INTO publications 
            (staff_id, publication_type, role, title, journal_name, publication_date, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[3]['publications'] as $pub) {
            $pub_date = date('Y-m-d', strtotime($pub['publication_date']));
            $stmt->execute([
                $user_id, $pub['publication_type'], $pub['role'], 
                $pub['title'], $pub['journal_name'], $pub_date
            ]);
        }
    }
    
    // Process grants (page 4)
    if (isset($full_data[4]['grants'])) {
        // Delete existing grants
        $conn->prepare("DELETE FROM grants WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new grants
        $stmt = $conn->prepare("INSERT INTO grants 
            (staff_id, grant_name, funding_agency, grant_amount, grant_year, role, description, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[4]['grants'] as $grant) {
            $stmt->execute([
                $user_id, $grant['grant_name'], $grant['funding_agency'], 
                $grant['grant_amount'], $grant['grant_year'], $grant['role'], 
                $grant['description']
            ]);
        }
    }
    
    // Process activities (page 5)
    if (isset($full_data[5]['activities'])) {
        // Delete existing activities
        $conn->prepare("DELETE FROM academicactivities WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new activities
        $stmt = $conn->prepare("INSERT INTO academicactivities 
            (staff_id, activity_type, title, role, location, description, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[5]['activities'] as $activity) {
            $stmt->execute([
                $user_id, $activity['activity_type'], $activity['title'], 
                $activity['role'], $activity['location'], $activity['description']
            ]);
        }
    }
}

// Get user's current progress
$progress = [];
$stmt = $conn->prepare("SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number");
$stmt->execute([$user_id]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $progress[$row['page_number']] = json_decode($row['data'], true);
}

// Get user data from staff table
$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Get departments for dropdown
$departments = [];
$stmt = $conn->query("SELECT * FROM departments");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $departments[] = $row;
}
?>