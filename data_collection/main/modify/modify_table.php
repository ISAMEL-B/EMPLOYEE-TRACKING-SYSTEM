<?php
session_start(); // Start the session

// Capture form input
$column = trim($_POST['column_name']); // Get the selected column name from the dropdown
$action = $_POST['action']; // Get the action (add or drop)
$table = $_POST['table_name']; // Get the selected table name

// Data type is hardcoded to 'VARCHAR(255)' since it's hidden in the form for non-technical users
$data_type = "VARCHAR(255)";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrm_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement based on user action
$sql = "";
if ($action == "add") {
    // Set a default column name if the user didn't provide one
    if (empty($column)) {
        $column = "new_column"; // Default column name
    }
    
    // Make sure the column name is valid and safe to use
    $column = mysqli_real_escape_string($conn, $column);
    $sql = "ALTER TABLE `$table` ADD `$column` $data_type";
} elseif ($action == "drop") {
    // Check if column name is valid and not empty before trying to drop
    if (!empty($column)) {
        $column = mysqli_real_escape_string($conn, $column);
        $sql = "ALTER TABLE `$table` DROP COLUMN `$column`";
    } else {
        $_SESSION['message'] = "Column name cannot be empty for drop action.";
        header("Location: column.php"); // Redirect to the column selection page
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid action.";
    header("Location: column.php"); // Redirect to the column selection page
    exit();
}

// Execute SQL query
if ($conn->query($sql) === TRUE) {
    $_SESSION['message'] = "Modification has been successful!";
} else {
    $_SESSION['message'] = "Error modifying table: " . $conn->error; // Include error for debugging
}

// Close connection
$conn->close();

// Redirect to another page to display the message
header("Location: column.php"); 
exit();
?>
