<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_role'])) {
    header("Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php");
    exit();
}
?>