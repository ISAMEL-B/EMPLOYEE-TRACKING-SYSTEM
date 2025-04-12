<?php
session_start();
include '../../approve/config.php';

header('Content-Type: application/json');

$sessionId = $_GET['session_id'] ?? '';

$result = $conn->query("SELECT content, is_user, timestamp FROM chat_messages 
                       WHERE session_id = '".$conn->real_escape_string($sessionId)."'
                       ORDER BY timestamp ASC");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'content' => $row['content'],
        'is_user' => $row['is_user'],
        'timestamp' => $row['timestamp']
    ];
}

echo json_encode(['success' => true, 'messages' => $messages]);
?>