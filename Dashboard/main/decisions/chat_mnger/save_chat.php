<?php
session_start();
include '../../approve/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sessionId = $data['session_id'] ?? '';
    $messages = $data['messages'] ?? [];

    // Clear previous messages for this session
    $conn->query("DELETE FROM chat_messages WHERE session_id = '".$conn->real_escape_string($sessionId)."'");

    // Save new messages
    foreach ($messages as $message) {
        $stmt = $conn->prepare("INSERT INTO chat_messages (session_id, content, is_user, timestamp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", 
            $sessionId,
            $message['content'],
            $message['is_user'],
            $message['timestamp']
        );
        $stmt->execute();
        $stmt->close();
    }

    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']);
?>