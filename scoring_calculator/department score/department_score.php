<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('display_errors', 1);

// Include database connection and required classes
include __DIR__ .'/../config.php'; 
include __DIR__ .'/../academic_score.php';
include __DIR__ .'/../grants_score.php';
include __DIR__ .'/../innovation_score.php';
include __DIR__ .'/../publication_score.php';
include __DIR__ .'/../post_graduate_supervision_score.php';
include __DIR__ .'/../membership_to_professional_bodies_score.php';
include __DIR__ .'/../community_service_score.php';
include __DIR__ .'/../other_academic_activities_score.php';
include __DIR__ .'/../teaching_experience_score.php';
include __DIR__ .'/../service_to_university_score.php';

function get_department_performance($conn, $department_id) {
    $department_data = [
        // Academic
        'PhD' => 0, 'Masters' => 0, 'First Class' => 0, 'Second Upper' => 0, 'Other' => 0, 'academic_score' => 0,

        // Grants
        'Over 1B' => 0, '500M - 1B' => 0, '100M - 500M' => 0, 'Below 100M' => 0, 'grant_score' => 0, 'total_grant_amount' => 0, 

        // Innovations
        'Patent' => 0, 'Utility Model' => 0, 'Copyright' => 0, 'Product' => 0, 'Trademark' => 0, 'innovation_score' => 0, 'total_innovations' => 0,

        // Publications    
        'Journal Articles (First Author)' => 0,
        'Journal Articles (Corresponding Author)' => 0,
        'Journal Articles (Co-author)' => 0,
        'Book with ISBN' => 0,
        'Book Chapter' => 0,
        'publication_score' => 0,
        'total_publications' => 0,

        // Supervision
        'PhD Supervised' => 0,
        'Masters Supervised' => 0,
        'supervision_score' => 0,
        
        // Professional Memberships
        'Professional Memberships' => 0,
        'membership_score' => 0,

        // Community Service
        'Community Services' => 0,
        'community_service_score' => 0,

        // Other Academic Activities
        'External Examination' => 0,
        'Internal Examination' => 0,
        'Conference Presentation' => 0,
        'Journal Editor' => 0,
        'other_academic_score' => 0,

        // Teaching Experience
        'teaching_experience_years' => 0,
        'teaching_experience_score' => 0,

        // Service to University
        'University Service Roles' => 0,
        'university_service_score' => 0, 

        // Total Score
        'total_score' => 0
    ];

    $stmt = $conn->prepare("SELECT staff_id FROM staff WHERE department_id = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $staff_id = $row['staff_id'];

        // Academic
        $scholar = new Scholar($conn, $staff_id);
        $academic_details = $scholar->get_score_details();
        foreach (['PhD', 'Masters', 'First Class', 'Second Upper', 'Other'] as $key) {
            $department_data[$key] += $academic_details[$key] ?? 0;
        }
        $department_data['academic_score'] += $academic_details['score'] ?? 0;

        // Grants
        $grants = new ResearchGrants($conn, $staff_id);
        $grant_details = $grants->get_score_details();
        foreach (['Over 1B', '500M - 1B', '100M - 500M', 'Below 100M'] as $key) {
            $department_data[$key] += $grant_details[$key] ?? 0;
        }
        $department_data['grant_score'] += $grant_details['score'] ?? 0;
        $department_data['total_grant_amount'] += $grant_details['total_grant_amount'] ?? 0;

        // Innovation
        $innovation = new InnovationScore($conn, $staff_id);
        try {
            $innovation_details = $innovation->get_score_details();
            foreach (['Patent', 'Utility Model', 'Copyright', 'Product', 'Trademark'] as $type) {
                $count = $innovation_details[$type] ?? 0;
                $department_data[$type] += $innovation_details[$type] ?? 0;
                $department_data['total_innovations'] += $count; // add total innovation score to the count of the innovations
                
            }
            $department_data['innovation_score'] += $innovation_details['score'] ?? 0;
        } catch (Exception $e) {
            error_log("Innovation error for staff $staff_id: " . $e->getMessage());
        }

        // Publications
        $publication = new PublicationScore($conn, $staff_id);
        $publication_details = $publication->get_score_details();
        foreach ([
            'Journal Articles (First Author)',
            'Journal Articles (Corresponding Author)',
            'Journal Articles (Co-author)',
            'Book with ISBN',
            'Book Chapter'
        ] as $pub_type) {
            $count = $publication_details[$pub_type] ?? 0;
            $department_data[$pub_type] += $count;
            $department_data['total_publications'] += $count;
        }
        $department_data['publication_score'] += $publication_details['score'] ?? 0;

        // Supervision
        $supervision = new PostgraduateSupervisionScore($conn, $staff_id);
        $supervision_details = $supervision->get_score_details();
        foreach (['PhD Supervised', 'Masters Supervised'] as $type) {
            $department_data[$type] += $supervision_details[$type] ?? 0;
        }
        $department_data['supervision_score'] += $supervision_details['score'] ?? 0;

        // Memberships
        $membership = new MembershipToProfessionalBodiesScore($conn, $staff_id);
        $membership_details = $membership->get_score_details();
        $department_data['Professional Memberships'] += $membership_details['Professional Memberships'] ?? 0;
        $department_data['membership_score'] += $membership_details['score'] ?? 0;

        // Community Service
        $community = new CommunityServiceScore($conn, $staff_id);
        $community_details = $community->get_score_details();
        $department_data['Community Services'] += $community_details['Community Services'] ?? 0;
        $department_data['community_service_score'] += $community_details['score'] ?? 0;

        // Other Academic Activities
        $other = new OtherAcademicActivitiesScore($conn, $staff_id);
        $other_details = $other->get_score_details();
        foreach (['External Examination', 'Internal Examination', 'Conference Presentation', 'Journal Editor'] as $type) {
            $department_data[$type] += $other_details[$type] ?? 0;
        }
        $department_data['other_academic_score'] += $other_details['score'] ?? 0;

        // Teaching Experience
        $teaching = new TeachingExperienceScore($conn, $staff_id);
        $teaching_details = $teaching->get_score_details();
        $department_data['teaching_experience_years'] += $teaching_details['years'] ?? 0;
        $department_data['teaching_experience_score'] += $teaching_details['score'] ?? 0;

        // Service to University
        $university_service = new UniversityServiceScore($conn, $staff_id);
        $service_details = $university_service->get_score_details();
        $department_data['University Service Roles'] += $service_details['University Service Roles'] ?? 0;
        $department_data['university_service_score'] += $service_details['score'] ?? 0;
    }

    return $department_data;
}


//get the department name
function get_department_name($conn, $department_id) {
    $stmt = $conn->prepare("SELECT department_name FROM departments WHERE department_id = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($row = $result->fetch_assoc()) ? $row['department_name'] : 'Unknown Department';
}

//get the count of employees in the department.
function get_department_staff_count($conn, $department_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM staff WHERE department_id = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['total'];
    }
    return 0;
}





// Usage
$department_id = 17;
$dept_data = get_department_performance($conn, $department_id);
$department_name = get_department_name($conn, $department_id);

echo $dept_data['total_publications'] . "<br>";
// Output
echo "<h2>Performance Summary for Department of $department_name</h2>";

echo "<h3>Academic Qualifications</h3>";
foreach (['PhD', 'Masters', 'First Class', 'Second Upper', 'Other'] as $key) {
    echo "$key: <strong>{$dept_data[$key]}</strong><br>";
}
echo "Total Academic Score: <strong>{$dept_data['academic_score']}</strong><br><br>";

echo "<h3>Research Grants</h3>";
foreach (['Over 1B', '500M - 1B', '100M - 500M', 'Below 100M'] as $key) {
    echo "$key Grants: <strong>{$dept_data[$key]}</strong><br>";
}
echo "Total Grant Score: <strong>{$dept_data['grant_score']}</strong><br>";
echo "Total Grant Amount: <strong>" . number_format($dept_data['total_grant_amount'], 0, '.', ',') . " UGX</strong><br><br>";

echo "<h3>Innovations</h3>";
foreach (['Patent', 'Utility Model', 'Copyright', 'Product', 'Trademark'] as $type) {
    echo "$type: <strong>{$dept_data[$type]}</strong><br>";
}
echo "Total Innovation Score: <strong>{$dept_data['innovation_score']}</strong><br><br>";

echo "<h3>Publications</h3>";
foreach ([
    'Journal Articles (First Author)',
    'Journal Articles (Corresponding Author)',
    'Journal Articles (Co-author)',
    'Book with ISBN',
    'Book Chapter'
] as $pub_type) {
    echo "$pub_type: <strong>{$dept_data[$pub_type]}</strong><br>";
}
echo "Total Publication Score: <strong>{$dept_data['publication_score']}</strong><br><br>";

echo "<h3>Postgraduate Supervision</h3>";
echo "PhD Supervised: <strong>{$dept_data['PhD Supervised']}</strong><br>";
echo "Masters Supervised: <strong>{$dept_data['Masters Supervised']}</strong><br>";
echo "Total Supervision Score: <strong>{$dept_data['supervision_score']}</strong><br><br>";

echo "<h3>Professional Memberships</h3>";
echo "Total Memberships: <strong>{$dept_data['Professional Memberships']}</strong><br>";
echo "Total Membership Score: <strong>{$dept_data['membership_score']}</strong><br><br>";

echo "<h3>Community Service</h3>";
echo "Total Community Services: <strong>{$dept_data['Community Services']}</strong><br>";
echo "Total Community Service Score: <strong>{$dept_data['community_service_score']}</strong><br><br>";

echo "<h3>Other Academic Activities</h3>";
foreach (['External Examination', 'Internal Examination', 'Conference Presentation', 'Journal Editor'] as $type) {
    echo "$type: <strong>{$dept_data[$type]}</strong><br>";
}
echo "Total Other Academic Activities Score: <strong>{$dept_data['other_academic_score']}</strong><br><br>";

echo "<h3>Teaching Experience</h3>";
echo "Years of Teaching: <strong>{$dept_data['teaching_experience_years']}</strong><br>";
echo "Total Teaching Experience Score: <strong>{$dept_data['teaching_experience_score']}</strong><br><br>";

echo "<h3>Service to University</h3>";
echo "Service Roles Held: <strong>{$dept_data['University Service Roles']}</strong><br>";
echo "Total University Service Score: <strong>{$dept_data['university_service_score']}</strong><br>";


// Calculate overall total score
$overall_total_score = 
    $dept_data['academic_score'] +
    $dept_data['grant_score'] +
    $dept_data['innovation_score'] +
    $dept_data['publication_score'] +
    $dept_data['supervision_score'] +
    $dept_data['membership_score'] +
    $dept_data['community_service_score'] +
    $dept_data['other_academic_score'] +
    $dept_data['teaching_experience_score'] +
    $dept_data['university_service_score'];

echo "<hr>";
echo "<h2 style='color: darkgreen;'>Overall Total Score: <strong>$overall_total_score</strong></h2>";


?>
