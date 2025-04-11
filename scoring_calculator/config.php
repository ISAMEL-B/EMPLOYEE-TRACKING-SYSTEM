<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hrm_db2"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
