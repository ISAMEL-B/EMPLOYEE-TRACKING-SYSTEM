<?php
// Connect to the database
$host = 'localhost';
$db = 'hrm_db';  // Replace with your database name
$user = 'root';  // Replace with your database username
$pass = '';      // Replace with your database password
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
