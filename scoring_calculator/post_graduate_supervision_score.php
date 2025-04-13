<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ .'/config.php'; // Database config

class PostgraduateSupervisionScore {
    private $conn;
    private $staff_id;

    private $breakdown = [
        'PhD Supervised' => 0,
        'Masters Supervised' => 0,
        'score' => 0
    ];

    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT student_level FROM supervision WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $level = strtolower(trim($row['student_level']));

            if ($level === 'phd') {
                $this->breakdown['PhD Supervised'] += 1;
                $this->breakdown['score'] += 6;
            } elseif ($level === 'masters') {
                $this->breakdown['Masters Supervised'] += 1;
                $this->breakdown['score'] += 2;
            }
        }

        return $this->breakdown;
    }

    public function calculate_score() {
        $details = $this->get_score_details();
        return $details['score'];
    }
}

// Query the staff name from the database
// function get_staff_name($conn, $staff_id) {
//     $stmt = $conn->prepare("SELECT first_name, last_name FROM staff WHERE staff_id = ?");
//     $stmt->bind_param("i", $staff_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($row = $result->fetch_assoc()) {
//         return $row['first_name'] . ' ' . $row['last_name'];
//     } else {
//         return 'Unknown Staff';
//     }
// }


// Example usage:
// $staff_id = 2;
// $supervision = new PostgraduateSupervisionScore($conn, $staff_id);
// $details = $supervision->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// echo "<h2>Postgraduate Supervision Score for $staff_name</h2>";
// foreach ($details as $key => $value) {
//     echo "<p>$key: $value</p>";
// }
// echo "<p>Total Score: " . $details["score"] . "</p>";
// print_r($details);
?>
