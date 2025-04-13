<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ .'/config.php'; // Include database configuration

class CommunityServiceScore {
    private $conn;
    private $staff_id;
    private $breakdown = [
        'Community Services' => 0,
        'score' => 0,
        'activities' => []
    ];

    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT description FROM communityservice WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $services = [];
        $count = 0;

        while ($row = $result->fetch_assoc()) {
            $services[] = $row['description'];
            $count++;
        }

        $score = $count * 5; // 5 points per service

        $this->breakdown['Community Services'] = $count;
        $this->breakdown['score'] = $score;
        $this->breakdown['activities'] = $services;

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

// // Example usage
// $staff_id = 8; // Replace with actual staff ID
// $communityScore = new CommunityServiceScore($conn, $staff_id);
// $scoreDetails = $communityScore->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// // Output
// echo "<!DOCTYPE html><html><body>";
// echo "<h2>Community Service Score for $staff_name</h2>";
// echo "<p>Total Community Services: " . $scoreDetails['Community Services'] . "</p>";

// if (!empty($scoreDetails['activities'])) {
//     echo "<h3>Activities:</h3><ul>";
//     foreach ($scoreDetails['activities'] as $activity) {
//         echo "<li>$activity</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "<p>No community service records found.</p>";
// }

// echo "<p><strong>Total Score:</strong> " . $communityScore->calculate_score() . "</p>";
// echo "</body></html>";
?>
