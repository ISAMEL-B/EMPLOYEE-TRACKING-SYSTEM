<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$db = 'hrm_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle Login
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Query to check the user's email
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['passkey'])) {
                // Set session variables
                $_SESSION['email'] = $user['email'];
                $_SESSION['employee_id'] = $user['employee_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['message'] = 'Login successful!';

                // Redirect to dashboard or the upload page
                header('Location: ../csv/csv_receiver/upload.php');
                exit();
            } else {
                $_SESSION['message'] = 'Invalid password!';
                header('Location: register.php');
                exit();
            }
        } else {
            $_SESSION['message'] = 'No user found with the provided email!';
            header('Location: register.php');
            exit();
        }
    }

    // Handle Signup
    elseif (isset($_POST['signup'])) {
        $employee_id = trim($_POST['employee_id']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validate inputs
        if (empty($employee_id) || empty($username) || empty($email) || empty($password) || empty($role) || empty($confirm_password)) {
            $_SESSION['message'] = 'All fields are required!';
            header('Location: register.php');
            exit();
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['message'] = 'Passwords do not match!';
            header('Location: register.php');
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (employee_id, username, email, passkey, role) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("sssss", $employee_id, $username, $email, $hashed_password, $role);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Account created successfully!';
            header('Location: register.php');
            exit();
        } else {
            $_SESSION['message'] = 'Error creating account: ' . $stmt->error;
            header('Location: register.php');
            exit();
        }
    }
}
$conn->close();
?>
