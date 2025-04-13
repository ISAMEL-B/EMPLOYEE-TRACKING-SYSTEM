<?php
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors on the page

include __DIR__ .'/config.php'; // Include the database configuration file

class TeachingExperienceScore {
    private $conn;
    private $staff_id;
    private $breakdown = [
        'Years of Experience' => 0,
        'score' => 0,
        'activities' => []
    ];

    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT years_of_experience FROM staff WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $years = 0;
        if ($row = $result->fetch_assoc()) {
            $years = (int)$row['years_of_experience'];
        }

        $score = min($years, 15); // Max 15 points

        $this->breakdown['Years of Experience'] = $years;
        $this->breakdown['score'] = $score;
        $this->breakdown['activities'][] = "{$years} year(s) of teaching (Scored {$score} points)";

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
// try {
//     $staff_id = 4; // Replace with actual staff ID
//     $teachingScore = new TeachingExperienceScore($conn, $staff_id);
//     $scoreDetails = $teachingScore->get_score_details();
//     $staff_name = get_staff_name($conn, $staff_id); // You can reuse the get_staff_name() function

//     // Output
//     echo "<!DOCTYPE html><html><body>";
//     echo "<h2>Teaching Experience Score for $staff_name</h2>";
//     echo "<p>Years of Experience: " . $scoreDetails['Years of Experience'] . "</p>";

//     if (!empty($scoreDetails['activities'])) {
//         echo "<h3>Activity:</h3><ul>";
//         foreach ($scoreDetails['activities'] as $activity) {
//             echo "<li>$activity</li>";
//         }
//         echo "</ul>";
//     } else {
//         echo "<p>No teaching experience data found.</p>";
//     }

//     echo "<p><strong>Total Score:</strong> " . $teachingScore->calculate_score() . "</p>";
//     echo "</body></html>";

// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }
?>
