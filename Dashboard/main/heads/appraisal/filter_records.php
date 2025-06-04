<?php
require_once 'db_connection.php'; // your DB connection file

$status = $_POST['status'] ?? '';

$sql = "SELECT * FROM academicactivities";
$params = [];

if (!empty($status)) {
    $sql .= " WHERE LOWER(status) = ?";
    $params[] = strtolower($status);
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param("s", ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<div class="table-responsive"><table class="table table-striped table-sm">';
    echo '<thead><tr><th>Title</th><th>Date</th><th>Status</th></tr></thead><tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
        echo '<td>' . htmlspecialchars($row['date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
} else {
    echo '<div class="alert alert-warning">No records found.</div>';
}
?>
