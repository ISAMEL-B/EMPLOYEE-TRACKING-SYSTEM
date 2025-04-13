<?php
error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors on the page


include __DIR__ .'/config.php'; // Include the database configuration file



class Scholar {
    private $scholar_type;
    private $role;
    private $qualifications = [];
    private $breakdown = [
        'PhD' => 0,
        'Masters' => 0,
        'First Class' => 0,
        'Second Upper' => 0,
        'Other' => 0,
        'score' => 0
    ];

    public function __construct($conn, $id) {
        // Get scholar_type and role
        $stmt = $conn->prepare("SELECT staff.scholar_type, roles.role_name
                                FROM staff 
                                JOIN roles ON staff.role_id = roles.role_id 
                                WHERE staff.staff_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $this->scholar_type = strtolower($row['scholar_type']);
            $this->role = $row['role_name'];
        }

        // Get qualifications
        $stmt = $conn->prepare("SELECT degree_classification FROM degrees WHERE staff_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $this->qualifications[] = strtolower($row['degree_classification']);
        }
    }

    public function get_score_details() {
        $score = 0;
    
        foreach ($this->qualifications as $qualification) {
            // Normalize input
            $normalized = strtolower(trim($qualification));
            $normalized = preg_replace('/[^a-z]/', '', $normalized); // remove all non-letter chars (spaces, dashes, etc.)
    
            // Map to standard types
            $map = [
                'phd' => 'PhD',
                'doctorate' => 'PhD',
                'masters' => 'Masters',
                'msc' => 'Masters',
                'firstclass' => 'First Class',
                'secondupper' => 'Second Upper',
                'secondclassupper' => 'Second Upper'
            ];
    
            // Default to 'Other' if not matched
            $key = $map[$normalized] ?? 'Other';
    
            $this->breakdown[$key] += 1;
    
            // Score logic
            switch ($key) {
                case 'PhD':
                    $score += ($this->scholar_type === 'non clinical') ? 12 : 6;
                    break;
                case 'Masters':
                    $score += ($this->scholar_type === 'non clinical') ? 8 : 12;
                    break;
                case 'First Class':
                    $score += 6;
                    break;
                case 'Second Upper':
                    $score += 4;
                    break;
                case 'Other':
                    $score += 2;
                    break;
            }
        }
    
        $this->breakdown['score'] = $score;
        return $this->breakdown;
    }
    

    public function calculate_score() {
        // Maintain backward compatibility
        $details = $this->get_score_details();
        return $details['score'];
    }
}

//query the staff name from the database
// this is defined once, provided the include of this file will be the first. because when another include of the file comes and also has it, 
// it creates a conflict. 
// function get_staff_name($conn, $staff_id) {
//     $stmt = $conn->prepare("SELECT first_name, last_name FROM staff WHERE staff_id = ?");
//     $stmt->bind_param("i", $staff_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($row = $result->fetch_assoc()) {
//         return $row['first_name'] . ' ' . $row['last_name'];
//     }
//     else {
//         return 'Unknown Staff';
//     }

// }

//example usage
// $staff_id = 2; // Example staff ID
// $staff  = new Scholar($conn, $staff_id);
// $staffscores = $staff->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// echo "<h2> Academic Score for $staff_name </h2>";
// foreach ($staffscores as $key => $value) {
//     echo "<p>$key: $value</p>";
// }
// echo "<p>Total Score: " . $staff->calculate_score() . "</p>";


?> 
 
