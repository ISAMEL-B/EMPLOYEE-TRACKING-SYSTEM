<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config.php'; // Include database configuration

function get_total_citations($conn, $staff_id) {
    $total_citations = 0;

    try {
        // Prepare the SQL query
        $stmt = $conn->prepare("
            SELECT SUM(citations) AS total_citations 
            FROM publications 
            WHERE staff_id = ?
        ");
        
        if ($stmt) {
            // Bind parameters and execute
            $stmt->bind_param("i", $staff_id);
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $total_citations = $row['total_citations'] ?? 0;
            }
            
            // Close statement
            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Error fetching citations: " . $e->getMessage());
    }
    
    return $total_citations;
}

// Usage
// $staff_id = 2;
// $total_citations = get_total_citations($conn, $staff_id);
// echo "Total Citations: " . $total_citations;
?>