<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include dependencies

$mysqli = new mysqli("localhost", "root", "", "hrm_db");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 
include __DIR__ .'/../department score/department_score.php'; // Ensure this file contains get_department_performance()

function get_faculty_performance($conn, $faculty_id) {
    $faculty_data = [
        'PhD' => 0, 'Masters' => 0, 'First Class' => 0, 'Second Upper' => 0, 'Other' => 0, 'academic_score' => 0,
        'Over 1B' => 0, '500M - 1B' => 0, '100M - 500M' => 0, 'Below 100M' => 0, 'grant_score' => 0, 'total_grant_amount' => 0,
        'Patent' => 0, 'Utility Model' => 0, 'Copyright' => 0, 'Product' => 0, 'Trademark' => 0, 'innovation_score' => 0,
        'Journal Articles (First Author)' => 0, 'Journal Articles (Corresponding Author)' => 0,
        'Journal Articles (Co-author)' => 0, 'Book with ISBN' => 0, 'Book Chapter' => 0, 'publication_score' => 0,
        'PhD Supervised' => 0, 'Masters Supervised' => 0, 'supervision_score' => 0,
        'Professional Memberships' => 0, 'membership_score' => 0,
        'Community Services' => 0, 'community_service_score' => 0,
        'External Examination' => 0, 'Internal Examination' => 0, 'Conference Presentation' => 0, 'Journal Editor' => 0, 'other_academic_score' => 0,
        'teaching_experience_years' => 0, 'teaching_experience_score' => 0,
        'University Service Roles' => 0, 'university_service_score' => 0
    ];

    // Get all departments under the faculty
    $stmt = $conn->prepare("SELECT department_id FROM departments WHERE faculty_id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $dept_id = $row['department_id'];
        $dept_data = get_department_performance($conn, $dept_id);
        foreach ($faculty_data as $key => $value) {
            $faculty_data[$key] += $dept_data[$key] ?? 0;
        }
    }

    // Calculate total score
    $faculty_data['total_score'] = 
        $faculty_data['academic_score'] +
        $faculty_data['grant_score'] +
        $faculty_data['innovation_score'] +
        $faculty_data['publication_score'] +
        $faculty_data['supervision_score'] +
        $faculty_data['membership_score'] +
        $faculty_data['community_service_score'] +
        $faculty_data['other_academic_score'] +
        $faculty_data['teaching_experience_score'] +
        $faculty_data['university_service_score'];

    return $faculty_data;
}

// Initialize university-level data
$university_data = [
    'PhD' => 0, 'Masters' => 0, 'First Class' => 0, 'Second Upper' => 0, 'Other' => 0, 'academic_score' => 0,
    'Over 1B' => 0, '500M - 1B' => 0, '100M - 500M' => 0, 'Below 100M' => 0, 'grant_score' => 0, 'total_grant_amount' => 0,
    'Patent' => 0, 'Utility Model' => 0, 'Copyright' => 0, 'Product' => 0, 'Trademark' => 0, 'innovation_score' => 0,
    'Journal Articles (First Author)' => 0, 'Journal Articles (Corresponding Author)' => 0,
    'Journal Articles (Co-author)' => 0, 'Book with ISBN' => 0, 'Book Chapter' => 0, 'publication_score' => 0,
    'PhD Supervised' => 0, 'Masters Supervised' => 0, 'supervision_score' => 0,
    'Professional Memberships' => 0, 'membership_score' => 0,
    'Community Services' => 0, 'community_service_score' => 0,
    'External Examination' => 0, 'Internal Examination' => 0, 'Conference Presentation' => 0, 'Journal Editor' => 0, 'other_academic_score' => 0,
    'teaching_experience_years' => 0, 'teaching_experience_score' => 0,
    'University Service Roles' => 0, 'university_service_score' => 0
];

// Fetch all faculties
$faculties = $conn->query("SELECT faculty_id, faculty_name FROM faculties");
while ($faculty = $faculties->fetch_assoc()) {
    $faculty_id = $faculty['faculty_id'];
    $faculty_name = $faculty['faculty_name'];

    $faculty_data = get_faculty_performance($conn, $faculty_id);

    // Aggregate faculty data into university data
    foreach ($university_data as $key => $value) {
        $university_data[$key] += $faculty_data[$key] ?? 0;
    }
}

// Compute university total score
$university_data['total_score'] = 
    $university_data['academic_score'] +
    $university_data['grant_score'] +
    $university_data['innovation_score'] +
    $university_data['publication_score'] +
    $university_data['supervision_score'] +
    $university_data['membership_score'] +
    $university_data['community_service_score'] +
    $university_data['other_academic_score'] +
    $university_data['teaching_experience_score'] +
    $university_data['university_service_score'];

?>
