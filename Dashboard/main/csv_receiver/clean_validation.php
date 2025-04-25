<?php
// clean_validation.php
session_start();

// Clear the CSV validation data from the session
unset($_SESSION['csv_validation']);

// Optional: You can add a response if this is being called via AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
}

// If not an AJAX request, redirect back (though this shouldn't normally happen)
header('Location: ../head/upload_csv.php');
exit;
?>