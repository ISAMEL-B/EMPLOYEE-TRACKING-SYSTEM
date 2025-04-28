<?php
session_start();
require_once 'config.php';

// Check if user is logged in and has permission
if (!isset($_SESSION['staff_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get input data
$action = $_POST['action'] ?? '';
$approval_id = intval($_POST['id'] ?? 0);
$reason = $_POST['reason'] ?? '';

// Validate action
if (!in_array($action, ['approve', 'reject']) || $approval_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Start transaction
$conn->begin_transaction();

try {
    // 1. Update approval status
    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE csv_approvals SET 
            status = 'approved',
            reviewed_by = ?,
            reviewed_at = NOW()
            WHERE id = ? AND status = 'pending'");
        $stmt->bind_param("ii", $_SESSION['user_id'], $approval_id);
    } else {
        $stmt = $conn->prepare("UPDATE csv_approvals SET 
            status = 'rejected',
            reviewed_by = ?,
            reviewed_at = NOW(),
            rejection_reason = ?
            WHERE id = ? AND status = 'pending'");
        $stmt->bind_param("isi", $_SESSION['user_id'], $reason, $approval_id);
    }

    $stmt->execute();

    // Check if any row was affected
    if ($stmt->affected_rows === 0) {
        throw new Exception("Approval not found or already processed");
    }

    // 2. If approving, process the data (example for staff table)
    if ($action === 'approve') {
        // Get approval details
        $approval = $conn->query("SELECT * FROM csv_approvals WHERE id = $approval_id")->fetch_assoc();
        
        // Process based on table_name (example for staff table)
        if ($approval['table_name'] === 'staff') {
            $file_path = $approval['file_path'];
            
            if (($handle = fopen($file_path, "r")) !== FALSE) {
                $header = fgetcsv($handle);
                
                // Prepare insert statement
                $insert_stmt = $conn->prepare("INSERT INTO staff 
                    (staff_id, first_name, last_name, scholar_type, role_id, department_id, years_of_experience, performance_score) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                
                while (($data = fgetcsv($handle)) !== FALSE) {
                    // Convert role_name to role_id and department_name to department_id
                    $role_id = getRoleId($conn, $data[4]); // Assuming role_name is at index 4
                    $dept_id = getDeptId($conn, $data[5]); // Assuming department_name is at index 5
                    
                    $insert_stmt->bind_param("isssiiii", 
                        $data[0], $data[1], $data[2], $data[3], 
                        $role_id, $dept_id, $data[6], $data[7]);
                    $insert_stmt->execute();
                }
                fclose($handle);
            }
        }
        // Add similar processing for other tables
    }

    $conn->commit();
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Helper functions
function getRoleId($conn, $role_name) {
    $stmt = $conn->prepare("SELECT role_id FROM roles WHERE role_name = ?");
    $stmt->bind_param("s", $role_name);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc()['role_id'] : null;
}

function getDeptId($conn, $dept_name) {
    $stmt = $conn->prepare("SELECT department_id FROM departments WHERE department_name = ?");
    $stmt->bind_param("s", $dept_name);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc()['department_id'] : null;
}