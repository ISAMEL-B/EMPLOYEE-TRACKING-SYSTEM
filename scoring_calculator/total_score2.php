<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/config.php'; // Include database configuration
include __DIR__ . '/publication_score.php';
include __DIR__ . '/post_graduate_supervision_score.php';
include __DIR__ . '/grants_score.php';
include __DIR__ . '/academic_score.php';
include __DIR__ . '/community_service_score.php';
include __DIR__ . '/innovation_score.php';
include __DIR__ . '/membership_to_professional_bodies_score.php';
include __DIR__ . '/other_academic_activities_score.php';
include __DIR__ . '/service_to_university_score.php';
include __DIR__ . '/teaching_experience_score.php';

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
                                ResearchGrants $researchGrantsScore, PostgraduateSupervisionScore $postgraduateSupervisionScore,
                                InnovationScore $innovationScore, OtherAcademicActivitiesScore $academicActivitiesScore, 
                                UniversityServiceScore $serviceToUniversityScore, CommunityServiceScore $communityServiceScore,
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

    public function calculate_total_score_with_breakdown() {
        $total_score = 0;
        $breakdown = [];

        // Scholar (Academic Qualifications)
        $scholar_details = $this->scholar->get_score_details();
        $total_score += $scholar_details['score'];
        $breakdown['Academic Qualifications'] = $scholar_details;

        // Publications
        $pub_details = method_exists($this->publicationScore, 'get_score_details') 
                        ? $this->publicationScore->get_score_details() 
                        : ['score' => $this->publicationScore->calculate_score()];
        $total_score += $pub_details['score'];
        $breakdown['Publications'] = $pub_details;

        // Teaching Experience
        $teach_details = method_exists($this->teachingExperienceScore, 'get_score_details') 
                        ? $this->teachingExperienceScore->get_score_details() 
                        : ['score' => $this->teachingExperienceScore->calculate_score()];
        $total_score += $teach_details['score'];
        $breakdown['Teaching Experience'] = $teach_details;

        // Grants
        $grants_details = method_exists($this->researchGrantsScore, 'get_score_details') 
                        ? $this->researchGrantsScore->get_score_details() 
                        : ['score' => $this->researchGrantsScore->calculate_score()];
        $total_score += $grants_details['score'];
        $breakdown['Research Grants'] = $grants_details;

        // Postgraduate Supervision
        $pg_details = method_exists($this->postgraduateSupervisionScore, 'get_score_details') 
                    ? $this->postgraduateSupervisionScore->get_score_details() 
                    : ['score' => $this->postgraduateSupervisionScore->calculate_score()];
        $total_score += $pg_details['score'];
        $breakdown['Postgraduate Supervision'] = $pg_details;

        // Innovation
        $innovation_details = method_exists($this->innovationScore, 'get_score_details') 
                            ? $this->innovationScore->get_score_details() 
                            : ['score' => $this->innovationScore->calculate_score()];
        $total_score += $innovation_details['score'];
        $breakdown['Innovation'] = $innovation_details;

        // Other Academic Activities
        $other_activities_details = method_exists($this->academicActivitiesScore, 'get_score_details') 
                                  ? $this->academicActivitiesScore->get_score_details() 
                                  : ['score' => $this->academicActivitiesScore->calculate_score()];
        $total_score += $other_activities_details['score'];
        $breakdown['Other Academic Activities'] = $other_activities_details;

        // Service to University
        $service_details = method_exists($this->serviceToUniversityScore, 'get_score_details') 
                         ? $this->serviceToUniversityScore->get_score_details() 
                         : ['score' => $this->serviceToUniversityScore->calculate_score()];
        $total_score += $service_details['score'];
        $breakdown['Service to University'] = $service_details;

        // Community Service
        $community_details = method_exists($this->communityServiceScore, 'get_score_details') 
                           ? $this->communityServiceScore->get_score_details() 
                           : ['score' => $this->communityServiceScore->calculate_score()];
        $total_score += $community_details['score'];
        $breakdown['Community Service'] = $community_details;

        // Membership to Professional Bodies
        $membership_details = method_exists($this->membershipScore, 'get_score_details') 
                            ? $this->membershipScore->get_score_details() 
                            : ['score' => $this->membershipScore->calculate_score()];
        $total_score += $membership_details['score'];
        $breakdown['Professional Membership'] = $membership_details;

        // Append total score
        $breakdown['Total Score'] = $total_score;

        return $breakdown;
    }

    public function get_staff_name() {
        return $this->staff_name;
    }
}
// Fetch staff name
function get_staff_name($conn, $staff_id) {
    $stmt = $conn->prepare("SELECT last_name FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['last_name'];
    } else {
        return 'Unknown';
    }
}

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$staff_id = 17;
$staff_name = get_staff_name($conn, $staff_id);

$scholar = new Scholar($conn, $staff_id);
$publicationScore = new PublicationScore($conn, $staff_id);
$teachingExperienceScore = new TeachingExperienceScore($conn, $staff_id);
$researchGrantsScore = new ResearchGrants($conn, $staff_id);
$postgraduateSupervisionScore = new PostgraduateSupervisionScore($conn, $staff_id);
$innovationScore = new InnovationScore($conn, $staff_id);
$academicActivitiesScore = new OtherAcademicActivitiesScore($conn, $staff_id);
$serviceToUniversityScore = new UniversityServiceScore($conn, $staff_id);
$communityServiceScore = new CommunityServiceScore($conn, $staff_id);
$membershipScore = new MembershipToProfessionalBodiesScore($conn, $staff_id);

$totalScore = new TotalScore(
    $scholar, $publicationScore, $teachingExperienceScore, $researchGrantsScore, 
    $postgraduateSupervisionScore, $innovationScore, $academicActivitiesScore, 
    $serviceToUniversityScore, $communityServiceScore, $membershipScore, $staff_name
);

$breakdown = $totalScore->calculate_total_score_with_breakdown();

echo "<h2>Total Score Breakdown for {$totalScore->get_staff_name()}</h2>";
foreach ($breakdown as $kpi => $details) {
    if ($kpi === 'Total Score') {
        echo "<h3><strong>$kpi: {$details}</strong></h3>";
    } else {
        echo "<h4>$kpi</h4><ul>";
        if (is_array($details)) {
            foreach ($details as $key => $value) {
                echo "<li>$key: $value</li>";
            }
        } else {
            echo "<li>$details</li>"; // fallback if it's not an array
        }
        echo "</ul>";
    }
}


