<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hrm_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle signup
if (isset($_POST['signup'])) {
    $employee_id = $_POST['employee_id'];
    $email = $_POST['email'];
    $system_role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email or employee_id already exists
    $check_sql = "SELECT * FROM staff WHERE email='$email' OR employee_id='$employee_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Set session messages based on which field is taken
        $errors = [];
        while ($row = $check_result->fetch_assoc()) {
            if ($row['email'] === $email) {
                $errors[] = "Email is already taken!   ";
            }
            if ($row['employee_id'] === $employee_id) {
                $errors[] = "Employee ID is already taken!   ";
            }
            if ($row['system_role'] === $system_role) {
                $errors[] = "Role is already taken!";
            }
        }
        $_SESSION['registration_errors'] = implode(" ", $errors); // Combine errors into one message
        header("Location: register.php"); // Redirect back to the registration page
        exit();
    }

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO staff (employee_id, email, password, system_role) VALUES ('$employee_id', '$email', '$hashed_password', '$system_role')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['signup_message'] = "Account created successfully!";
            header("Location: register.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Passwords do not match!";
    }
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM staff WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['staff_id'] = $row['staff_id'];
            $_SESSION['employee_id'] = $row['employee_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_role'] = $row['system_role'];
            $_SESSION['phone_number'] = $row['phone_number'];

            // Redirect based on role
            if ($_SESSION['user_role'] === 'hrm') {
                header("Location: ../Dashboard/main/index.php");
            } elseif ($_SESSION['user_role'] === 'staff') {
                // Redirect based on role to upload.php with role as a query parameter
                header("Location: ../Dashboard/main/staff/for_staff_profile.php");
            } else {
                // Redirect based on role to upload.php with role as a query parameter
                header("Location: ../Dashboard/main/head/csv_receiver/upload_csv.php?role=" . urlencode($_SESSION['user_role']));
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid password!"; // Set login error
            header("Location: register.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "No user found with that email!"; // Set email error
        header("Location: register.php");
        exit();
    }
}

$conn->close();
