<?php
session_start();
include '../../approve/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sessionId = $data['session_id'] ?? '';

    $conn->query("DELETE FROM chat_messages WHERE session_id = '".$conn->real_escape_string($sessionId)."'");

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']);
?>