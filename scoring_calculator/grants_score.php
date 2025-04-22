<?php
error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors on the page

include __DIR__ .'/config.php'; // Include the database configuration file

class ResearchGrants {
    private $staff_id;
    private $grants = []; // Stores all grant amounts
    private $breakdown = [
        'Over 1B' => 0,
        '500M - 1B' => 0,
        '100M - 500M' => 0,
        'Below 100M' => 0,
        'score' => 0,
        'total_grant_amount' => 0,
        'grant_count' => 0
    ];

    public function __construct($conn, $staff_id) {
        $this->staff_id = $staff_id;
        $stmt = $conn->prepare("SELECT grant_amount FROM grants WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $amount = (int)$row['grant_amount'];
            $this->grants[] = $amount;
            $this->breakdown['total_grant_amount'] += $amount;
        }

        // Count total number of grants
        $this->breakdown['grant_count'] = count($this->grants);
    }

    public function get_score_details() {
        $score = 0;

        foreach ($this->grants as $grant_amount) {
            if ($grant_amount > 1000000000) {
                $this->breakdown['Over 1B'] += 1;
                $score += 12;
            } elseif ($grant_amount >= 500000000 && $grant_amount <= 1000000000) {
                $this->breakdown['500M - 1B'] += 1;
                $score += 8;
            } elseif ($grant_amount >= 100000000 && $grant_amount < 500000000) {
                $this->breakdown['100M - 500M'] += 1;
                $score += 6;
            } elseif ($grant_amount < 100000000) {
                $this->breakdown['Below 100M'] += 1;
                $score += 4;
            }
        }

        $this->breakdown['score'] = $score;
        return $this->breakdown;
    }

    public function calculate_score() {
        $details = $this->get_score_details();
        return $details['score'];
    }

    public function total_grant_amount() {
        return $this->breakdown['total_grant_amount'];
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

// Example usage
// $staff_id = 8; // Example staff ID
// $grants = new ResearchGrants($conn, $staff_id);
// $grant_scores = $grants->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// echo "<h2>Research Grants Score for $staff_name</h2>";
// foreach ($grant_scores as $key => $value) {
//     if ($key === 'total_grant_amount') {
//         echo "<p>Total Grant Amount: " . number_format($value, 0, '.', ',') . " UGX</p>";
//     } elseif ($key === 'score') {
//         echo "<p>Total Score: $value</p>";
//     } else {
//         echo "<p>$key: $value</p>";
//     }
// }
?>
