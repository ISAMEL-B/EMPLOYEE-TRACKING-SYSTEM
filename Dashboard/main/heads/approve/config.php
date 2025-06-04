<?php
// Database connection variables
$dbuser = "root";
$dbpass = "";
$host = "localhost";
$db = "hrm_db";

// Create connection using 'conn'
$conn = new mysqli($host, $dbuser, $dbpass, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for full Unicode support
$conn->set_charset("utf8mb4");

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');
?>