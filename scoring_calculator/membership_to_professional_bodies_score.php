<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ .'/config.php'; // Include database configuration

class MembershipToProfessionalBodiesScore {
    private $conn;
    private $staff_id;
    private $breakdown = [
        'Professional Memberships' => 0,
        'score' => 0,
        'bodies' => [] // Added to store names of professional bodies
    ];

    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT body_name FROM professionalbodies WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $membership_count = 0;
        $bodies = [];

        while ($row = $result->fetch_assoc()) {
            $bodies[] = $row['body_name'];
            $membership_count++;
        }

        $score = min($membership_count, 2); // Max 2 points

        $this->breakdown['Professional Memberships'] = $membership_count;
        $this->breakdown['score'] = $score;
        $this->breakdown['bodies'] = $bodies;

        return $this->breakdown;
    }

    public function calculate_score() {
        $details = $this->get_score_details();
        return $details['score'];
    }
}

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
// $staff_id = 8; // Replace with actual staff ID
// $membershipScore = new MembershipToProfessionalBodiesScore($conn, $staff_id);
// $scoreDetails = $membershipScore->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// // Output
// echo "<!DOCTYPE html><html><body>";
// echo "<h2>Membership to Professional Bodies Score for $staff_name</h2>";
// echo "<p>Total Professional Memberships: " . $scoreDetails['Professional Memberships'] . "</p>";

// if (!empty($scoreDetails['bodies'])) {
//     echo "<h3>Professional Bodies:</h3><ul>";
//     foreach ($scoreDetails['bodies'] as $body) {
//         echo "<li>$body</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "<p>No professional bodies found.</p>";
// }

// echo "<p><strong>Total Score:</strong> " . $membershipScore->calculate_score() . "</p>";
// echo "</body></html>";
?>
