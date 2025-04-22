<?php
include __DIR__ . '/../../scoring_calculator/department score/graphs.php';

header('Content-Type: application/json');

if (!isset($_GET['rank']) || !isset($_GET['department_id'])) {
    echo json_encode(['error' => 'Rank or department not specified']);
    exit;
}

$rank = $_GET['rank'];
$department_id = intval($_GET['department_id']); // Always sanitize input
$data = get_performance_data_by_rank_and_department($conn, $rank, $department_id);

echo json_encode($data);
?>
