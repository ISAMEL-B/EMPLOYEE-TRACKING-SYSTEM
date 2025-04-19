<?php 
session_start();
include "head/approve/config.php"; // Include your database connection

// Initialize variables
$staff = null;
$error = null;

// Get all staff members for the dropdown
$staffs = [];
$result = $conn->query("SELECT staff_id, first_name, last_name FROM staff ORDER BY last_name, first_name");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffs[] = $row;
    }
} else {
    $error = "Error fetching staff list: " . $conn->error;
}

// Get specific staff member if ID is provided
if (isset($_GET['staff_id']) && !empty($_GET['staff_id'])) {
    $staff_id = $_GET['staff_id'];
    $stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
    } else {
        $error = "Staff member not found";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'])) {
    $staff_id = $_POST['staff_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $scholar_type = $_POST['scholar_type'];
    
    $stmt = $conn->prepare("UPDATE staff SET first_name = ?, last_name = ?, scholar_type = ? WHERE staff_id = ?");
    $stmt->bind_param("sssi", $first_name, $last_name, $scholar_type, $staff_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Staff profile updated successfully";
        header("Location: ?staff_id=" . $staff_id);
        exit();
    } else {
        $error = "Error updating staff profile: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>