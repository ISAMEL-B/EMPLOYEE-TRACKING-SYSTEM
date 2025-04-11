<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; // Include database configuration
include 'publication_score.php';
include 'post_graduate_supervision_score.php';
include 'grants_score.php';
include 'academic_score.php';
include 'community_service_score.php';
include 'innovation_score.php';
include 'membership_to_professional_bodies_score.php';
include 'other_academic_activities_score.php';
include 'service_to_the_university.php';
include 'teaching_experience.php';

class TotalScore {
    private $scholar;
    private $publicationScore;
    private $teachingExperienceScore;
    private $researchGrantsScore;
    private $postgraduateSupervisionScore;
    private $innovationScore;
    private $academicActivitiesScore;
    private $serviceToUniversityScore;
    private $communityServiceScore;
    private $membershipScore;
    private $staff_name;

    public function __construct(Scholar $scholar, PublicationScore $publicationScore, TeachingExperienceScore $teachingExperienceScore, 
                                ResearchGrantsScore $researchGrantsScore, PostgraduateSupervisionScore $postgraduateSupervisionScore,
                                InnovationScore $innovationScore, OtherAcademicActivitiesScore $academicActivitiesScore, 
                                ServiceToTheUniversityScore $serviceToUniversityScore, CommunityServiceScore $communityServiceScore,
                                MembershipToProfessionalBodiesScore $membershipScore, $staff_name) {
        $this->scholar = $scholar;
        $this->publicationScore = $publicationScore;
        $this->teachingExperienceScore = $teachingExperienceScore;
        $this->researchGrantsScore = $researchGrantsScore;
        $this->postgraduateSupervisionScore = $postgraduateSupervisionScore;
        $this->innovationScore = $innovationScore;
        $this->academicActivitiesScore = $academicActivitiesScore;
        $this->serviceToUniversityScore = $serviceToUniversityScore;
        $this->communityServiceScore = $communityServiceScore;
        $this->membershipScore = $membershipScore;
        $this->staff_name = $staff_name;
    }

    public function calculate_total_score() {
        $total_score = 0;

        // Get individual scores
        $scholar_score = $this->scholar->calculate_score();
        $publication_score = $this->publicationScore->calculate_score();
        $teaching_score = $this->teachingExperienceScore->get_score();
        $grants_score = $this->researchGrantsScore->total_score();
        $postgrad_score = $this->postgraduateSupervisionScore->calculate_score();
        $innovation_score = $this->innovationScore->calculate_score();
        $academic_score = $this->academicActivitiesScore->calculate_score();
        $service_score = $this->serviceToUniversityScore->calculate_score();
        $community_score = $this->communityServiceScore->calculate_score();
        $membership_score = $this->membershipScore->calculate_score();

        // Sum up scores
        $total_score = $scholar_score + $publication_score + $teaching_score +
                       $grants_score + $postgrad_score + $innovation_score +
                       $academic_score + $service_score + $community_score + 
                       $membership_score;

        echo "<br><strong>Total Score:</strong> $total_score<br>";

        return $total_score;
    }
    
    public function get_staff_name() {
        return $this->staff_name;
    }
}

// Fetch staff last name based on staff ID
            // just to echo on the interface
function get_staff_name($conn, $staff_id) {
    $stmt = $conn->prepare("SELECT last_name FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['last_name'];  // Fetching last name instead of full name
    } else {
        return 'Unknown';  // If no last name is found
    }
}

// Example usage
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$staff_id = 3; // Replace with dynamic staff ID
$staff_name = get_staff_name($conn, $staff_id);

$scholar = new Scholar($conn, $staff_id);
$publicationScore = new PublicationScore($conn, $staff_id);
$teachingExperienceScore = new TeachingExperienceScore($conn, $staff_id);
$researchGrantsScore = new ResearchGrantsScore($conn, $staff_id);
$postgraduateSupervisionScore = new PostgraduateSupervisionScore($conn, $staff_id);
$innovationScore = new InnovationScore($conn, $staff_id);
$academicActivitiesScore = new OtherAcademicActivitiesScore($conn, $staff_id);
$serviceToUniversityScore = new ServiceToTheUniversityScore($conn, $staff_id);
$communityServiceScore = new CommunityServiceScore($conn, $staff_id);
$membershipScore = new MembershipToProfessionalBodiesScore($conn, $staff_id);

$totalScore = new TotalScore($scholar, $publicationScore, $teachingExperienceScore, $researchGrantsScore, 
                             $postgraduateSupervisionScore, $innovationScore, $academicActivitiesScore, 
                             $serviceToUniversityScore, $communityServiceScore, $membershipScore, $staff_name);
$total_score = $totalScore->calculate_total_score();

// Output the result with the staff's last name
echo "<br><strong>Total score for " . $totalScore->get_staff_name() . " is: " . $total_score . "</strong>";

?>
