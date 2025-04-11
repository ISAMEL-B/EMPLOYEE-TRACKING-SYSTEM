<?php 
session_start();
//include 'inbox_process.php';
if (!isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) == 0) {
    header('Location: ../register/logout.php');
    exit();
} 
?>