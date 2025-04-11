<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php'; // Database config

class OtherAcademicActivitiesScore {
    private $conn;
    private $staff_id;
    private $breakdown = [
        'External Examination' => 0,
        'Internal Examination' => 0,
        'Conference Presentation' => 0,
        'Journal Editor' => 0,
        'score' => 0,
        'activities' => []
    ];

    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT activity_type FROM academicactivities WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $activity_type = strtolower(trim($row['activity_type'])); // Normalize

            switch ($activity_type) {
                case 'external examination':
                    $this->breakdown['External Examination']++;
                    break;
                case 'internal examination':
                    $this->breakdown['Internal Examination']++;
                    break;
                case 'conference presentation':
                    $this->breakdown['Conference Presentation']++;
                    break;
                case 'journal editor':
                    $this->breakdown['Journal Editor']++;
                    break;
                default:
                    // Skip unknown types silently
                    break;
            }

            $this->breakdown['activities'][] = ucwords($activity_type); // Store original
        }

        // Scoring logic
        $score = 0;
        $score += min($this->breakdown['External Examination'], 10) * 0.5;
        $score += min($this->breakdown['Internal Examination'], 10) * 1;
        $score += min($this->breakdown['Conference Presentation'], 2) * 0.5;
        $score += min($this->breakdown['Journal Editor'], 3) * 1;

        $this->breakdown['score'] = $score;

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
// $staff_id = 8;
// $academicScore = new OtherAcademicActivitiesScore($conn, $staff_id);
// $scoreDetails = $academicScore->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// // Output
// echo "<!DOCTYPE html><html><body>";
// echo "<h2>Other Academic Activities Score for $staff_name</h2>";

// echo "<ul>";
// echo "<li>External Examinations: <strong>" . $scoreDetails['External Examination'] . "</strong></li>";
// echo "<li>Internal Examinations: <strong>" . $scoreDetails['Internal Examination'] . "</strong></li>";
// echo "<li>Conference Presentations: <strong>" . $scoreDetails['Conference Presentation'] . "</strong></li>";
// echo "<li>Journal Editorships: <strong>" . $scoreDetails['Journal Editor'] . "</strong></li>";
// echo "</ul>";

// if (!empty($scoreDetails['activities'])) {
//     echo "<h3>All Recorded Activities:</h3><ul>";
//     foreach ($scoreDetails['activities'] as $activity) {
//         echo "<li>$activity</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "<p>No other academic activities found.</p>";
// }

// echo "<p><strong>Total Score:</strong> " . $academicScore->calculate_score() . "</p>";
// echo "</body></html>";
?>
