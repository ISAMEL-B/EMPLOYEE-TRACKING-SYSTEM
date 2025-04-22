<?php
error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors on the page


include __DIR__ .'/config.php'; // Include the database configuration file

// Enum for Innovation Types
abstract class InnovationType {
    const PATENT = 5;
    const UTILITY_MODEL = 4;
    const COPYRIGHT = 3;
    const PRODUCT = 3;
    const TRADEMARK = 1;
}

class InnovationScore {
    private $conn; // Database connection
    private $staff_id;
    private $breakdown = [
        'Patent' => 0,
        'Utility Model' => 0,
        'Copyright' => 0,
        'Product' => 0,
        'Trademark' => 0,
        'score' => 0,
        'total_innovations' => 0
    ];

    // Constructor to initialize with database connection and staff ID
    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    // Fetch and calculate breakdown and score
    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT innovation_type FROM innovations WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $type_raw = strtolower(trim($row['innovation_type']));
            $type_normalized = preg_replace('/[^a-z]/', '', $type_raw); // remove all non-letter chars

            $this->breakdown['total_innovations'] += 1;//update the total

            switch ($type_normalized) {
                case 'patent':
                    $this->breakdown['Patent'] += 1;
                    $this->breakdown['score'] += InnovationType::PATENT;
                    break;
                case 'utilitymodel':
                    $this->breakdown['Utility Model'] += 1;
                    $this->breakdown['score'] += InnovationType::UTILITY_MODEL;
                    break;
                case 'copyright':
                    $this->breakdown['Copyright'] += 1;
                    $this->breakdown['score'] += InnovationType::COPYRIGHT;
                    break;
                case 'product':
                    $this->breakdown['Product'] += 1;
                    $this->breakdown['score'] += InnovationType::PRODUCT;
                    break;
                case 'trademark':
                    $this->breakdown['Trademark'] += 1;
                    $this->breakdown['score'] += InnovationType::TRADEMARK;
                    break;
                default:
                    throw new Exception("Invalid innovation type found in database: $type_raw");
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

//
// Example usage
// $staff_id = 2;
// $innovation_score = new InnovationScore($conn, $staff_id);
// $score_details = $innovation_score->get_score_details();
// echo "Innovation Score: " . $score_details['score'];
// for

//Example usage
// $staff_id = 8;
// $innovation_score = new InnovationScore($conn, $staff_id);
// $score_details = $innovation_score->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// echo "<h2> Innovation Score for $staff_name </h2>";
// foreach ($score_details as $type => $count) {
//     if ($type !== 'score') echo "$type: $count<br>";
// }
// echo "Innovation Score: " . $score_details['score'];

?>
