<?php
session_start();
require_once '../head/approve/config.php';

// Map table names to their correct ID columns
$table_id_columns = [
    'degrees' => 'degree_id',
    'publications' => 'publication_id',
    'innovations' => 'innovation_id',
    'communityservice' => 'community_service_id',
    'supervision' => 'supervision_id',
    'grants' => 'grant_id'
];

// Check if user is logged in and has verification privileges
if (!isset($_SESSION['staff_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if staff_id is provided
if (!isset($_POST['staff_id'])) {
    echo json_encode(['success' => false, 'message' => 'Staff ID not provided']);
    exit;
}

$staff_id = intval($_POST['staff_id']);
$action = isset($_POST['action']) ? $_POST['action'] : '';
$table = isset($_POST['table']) ? $_POST['table'] : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$reason = isset($_POST['reason']) ? $conn->real_escape_string($_POST['reason']) : '';

$response = ['success' => false, 'message' => 'Invalid action'];

try {
    // Validate table name and get correct ID column
    if ($table && isset($table_id_columns[$table])) {
        $id_column = $table_id_columns[$table];
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid table name']);
        exit;
    }
    
    switch ($action) {
        case 'approve':
            // Approve single record
            if ($id) {
                $sql = "UPDATE $table SET verification_status = 'approved', verified_by = ?, verification_date = NOW() 
                        WHERE $id_column = ? AND staff_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $_SESSION['staff_id'], $id, $staff_id);

                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Record approved successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to approve record'];
                }
                $stmt->close();
            }
            break;

        case 'reject':
            // Reject single record
            if ($id && $reason) {
                $sql = "UPDATE $table SET verification_status = 'rejected', verification_notes = ?, 
                        verified_by = ?, verification_date = NOW() 
                        WHERE $id_column = ? AND staff_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("siii", $reason, $_SESSION['staff_id'], $id, $staff_id);

                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Record rejected successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to reject record'];
                }
                $stmt->close();
            }
            break;

        case 'approve_section':
            // Approve all records in a section
            $sql = "UPDATE $table SET verification_status = 'approved', verified_by = ?, verification_date = NOW() 
                    WHERE staff_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $_SESSION['staff_id'], $staff_id);

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'All records in section approved successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to approve section records'];
            }
            $stmt->close();
            break;

        case 'reject_section':
            // Reject all records in a section
            if ($reason) {
                $sql = "UPDATE $table SET verification_status = 'rejected', verification_notes = ?, 
                        verified_by = ?, verification_date = NOW() 
                        WHERE staff_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sii", $reason, $_SESSION['staff_id'], $staff_id);

                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'All records in section rejected successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to reject section records'];
                }
                $stmt->close();
            }
            break;

        case 'approve_all':
            // Approve all records across all tables for this staff member
            $tables = array_keys($table_id_columns);
            $success = true;

            foreach ($tables as $t) {
                $sql = "UPDATE $t SET verification_status = 'approved', verified_by = ?, verification_date = NOW() 
                        WHERE staff_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $_SESSION['staff_id'], $staff_id);

                if (!$stmt->execute()) {
                    $success = false;
                }
                $stmt->close();
            }

            if ($success) {
                $response = ['success' => true, 'message' => 'All records approved successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to approve some records'];
            }
            break;

        case 'reject_all':
            // Reject all records across all tables for this staff member
            if ($reason) {
                $tables = array_keys($table_id_columns);
                $success = true;

                foreach ($tables as $t) {
                    $sql = "UPDATE $t SET verification_status = 'rejected', verification_notes = ?, 
                            verified_by = ?, verification_date = NOW() 
                            WHERE staff_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sii", $reason, $_SESSION['staff_id'], $staff_id);

                    if (!$stmt->execute()) {
                        $success = false;
                    }
                    $stmt->close();
                }

                if ($success) {
                    $response = ['success' => true, 'message' => 'All records rejected successfully'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to reject some records'];
                }
            }
            break;

        default:
            $response = ['success' => false, 'message' => 'Invalid action specified'];
    }
} catch (Exception $e) {
    $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close connection
$conn->close();
?>
