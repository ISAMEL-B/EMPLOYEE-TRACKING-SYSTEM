<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ .'/config.php'; // Include database configuration

class UniversityServiceScore {
    private $conn;
    private $staff_id;
    private $breakdown = [
        'University Services' => 0,
        'score' => 0,
        'activities' => []
    ];

    // Enum-like mapping for service types and their respective scores
    private $service_mapping = [
        'dean' => 5,
        'director' => 5,
        'deputy dean' => 3,
        'deputy director' => 3,
        'head of department' => 2,
        'committee member' => 1
    ];

    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT service_type FROM service WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $activities = [];
        $total_score = 0;
        $count = 0;

        while ($row = $result->fetch_assoc()) {
            $type = strtolower($row['service_type']);
            $score = $this->service_mapping[$type] ?? null;

            if ($score === null) {
                throw new Exception("Invalid service type found in database: " . $row['service_type']);
            }

            $activities[] = ucfirst($row['service_type']) . " ({$score} points)";
            $total_score += $score;
            $count++;
        }

        $this->breakdown['University Services'] = $count;
        $this->breakdown['score'] = $total_score;
        $this->breakdown['activities'] = $activities;

        return $this->breakdown;
    }

    public function calculate_score() {
        $details = $this->get_score_details();
        return $details['score'];
    }
}

// Utility function to get staff name
// function get_staff_name($conn, $staff_id) {
//     $stmt = $conn->prepare("SELECT first_name, last_name FROM staff WHERE staff_id = ?");
//     $stmt->bind_param("i", $staff_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($row = $result->fetch_assoc()) {
//         return $row['first_name'] . ' ' . $row['last_name'];
//     } else {
//         return "Unknown Staff";
//     }
// }

// Example usage
// $staff_id = 2; // Replace with actual staff ID
// $universityScore = new UniversityServiceScore($conn, $staff_id);
// $scoreDetails = $universityScore->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);


// // Output
// echo "<!DOCTYPE html><html><body>";
// echo "<h2>University Service Score for $staff_name</h2>";
// echo "<p>Total University Services: " . $scoreDetails['University Services'] . "</p>";

// if (!empty($scoreDetails['activities'])) {
//     echo "<h3>Activities:</h3><ul>";
//     foreach ($scoreDetails['activities'] as $activity) {
//         echo "<li>$activity</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "<p>No university service records found.</p>";
// }

// echo "<p><strong>Total Score:</strong> " . $universityScore->calculate_score() . "</p>";
// echo "</body></html>";
?>
