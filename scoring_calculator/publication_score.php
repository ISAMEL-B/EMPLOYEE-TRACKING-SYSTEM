<?php
error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors on the page

include __DIR__ .'/config.php'; // Include the database configuration file

// Enum for Publication Types
abstract class PublicationType {
    const JOURNAL_ARTICLE = 'journal article';
    const BOOK_WITH_ISBN = 'book with isbn';
    const BOOK_CHAPTER = 'book chapter';
}

// Enum for Author Roles
abstract class AuthorRole {
    const FIRST_AUTHOR = 'first-author';
    const CORRESPONDING_AUTHOR = 'corresponding-author';
    const CO_AUTHOR = 'co-author';
}

class PublicationScore {
    private $conn;
    private $staff_id;
    private $breakdown = [
        'Journal Articles (First Author)' => 0,
        'Journal Articles (Corresponding Author)' => 0,
        'Journal Articles (Co-author)' => 0,
        'Book with ISBN' => 0,
        'Book Chapter' => 0,
        'score' => 0,
        'total_publications' => 0
    ];

    // Constructor to initialize with DB connection and staff ID
    public function __construct($conn, $staff_id) {
        $this->conn = $conn;
        $this->staff_id = $staff_id;
    }

    public function get_score_details() {
        $stmt = $this->conn->prepare("SELECT publication_type, role FROM publications WHERE staff_id = ?");
        $stmt->bind_param("i", $this->staff_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $pub_type = strtolower(trim($row['publication_type']));
            $author_role = strtolower(trim($row['role']));

            // Count every publication towards total
            $this->breakdown['total_publications'] += 1;

            if ($pub_type == PublicationType::JOURNAL_ARTICLE) {
                switch ($author_role) {
                    case AuthorRole::FIRST_AUTHOR:
                        $this->breakdown['Journal Articles (First Author)'] += 1;
                        $this->breakdown['score'] += 4;
                        break;
                    case AuthorRole::CORRESPONDING_AUTHOR:
                        $this->breakdown['Journal Articles (Corresponding Author)'] += 1;
                        $this->breakdown['score'] += 2;
                        break;
                    case AuthorRole::CO_AUTHOR:
                        $this->breakdown['Journal Articles (Co-author)'] += 1;
                        $this->breakdown['score'] += 1;
                        break;
                }
            } elseif ($pub_type == PublicationType::BOOK_WITH_ISBN) {
                $this->breakdown['Book with ISBN'] += 1;
                $this->breakdown['score'] += 12;
            } elseif ($pub_type == PublicationType::BOOK_CHAPTER) {
                $this->breakdown['Book Chapter'] += 1;
                $this->breakdown['score'] += 4;
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

// Example usage
// $staff_id = 8;
// $pub = new PublicationScore($conn, $staff_id);
// $details = $pub->get_score_details();
// $staff_name = get_staff_name($conn, $staff_id);

// echo "<h2>Publication Score for $staff_name</h2>";
// foreach ($details as $key => $value) {
//     echo "$key: $value<br>";
// }

// echo "Publication Score: " . $details['score'];
?>
