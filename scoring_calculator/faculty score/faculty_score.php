<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include dependencies
include __DIR__ .'/../config.php'; 
include __DIR__ .'/../department score/department_score.php'; // Ensure this file contains get_department_performance()

function get_faculty_performance($conn, $faculty_id) {
    // Initialize faculty data structure
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

        // Aggregate department data into faculty data
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

function get_faculty_name($conn, $faculty_id) {
    $stmt = $conn->prepare("SELECT faculty_name FROM faculties WHERE faculty_id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($row = $result->fetch_assoc()) ? $row['faculty_name'] : 'Unknown Faculty';
}

// // Usage
$faculty_id = 1;
$faculty_data = get_faculty_performance($conn, $faculty_id);
$faculty_name = get_faculty_name($conn, $faculty_id);

// Output
// echo "<h2>Performance Summary for Faculty of $faculty_name</h2>";

// echo "<h3>Academic Qualifications</h3>";
// foreach (['PhD', 'Masters', 'First Class', 'Second Upper', 'Other'] as $key) {
//     echo "$key: <strong>{$faculty_data[$key]}</strong><br>";
// }
// echo "Total Academic Score: <strong>{$faculty_data['academic_score']}</strong><br><br>";

// echo "<h3>Research Grants</h3>";
// foreach (['Over 1B', '500M - 1B', '100M - 500M', 'Below 100M'] as $key) {
//     echo "$key Grants: <strong>{$faculty_data[$key]}</strong><br>";
// }
// echo "Total Grant Score: <strong>{$faculty_data['grant_score']}</strong><br>";
// echo "Total Grant Amount: <strong>" . number_format($faculty_data['total_grant_amount'], 0, '.', ',') . " UGX</strong><br><br>";

// echo "<h3>Innovations</h3>";
// foreach (['Patent', 'Utility Model', 'Copyright', 'Product', 'Trademark'] as $type) {
//     echo "$type: <strong>{$faculty_data[$type]}</strong><br>";
// }
// echo "Total Innovation Score: <strong>{$faculty_data['innovation_score']}</strong><br><br>";

// echo "<h3>Publications</h3>";
// foreach ([
//     'Journal Articles (First Author)',
//     'Journal Articles (Corresponding Author)',
//     'Journal Articles (Co-author)',
//     'Book with ISBN',
//     'Book Chapter'
// ] as $pub_type) {
//     echo "$pub_type: <strong>{$faculty_data[$pub_type]}</strong><br>";
// }
// echo "Total Publication Score: <strong>{$faculty_data['publication_score']}</strong><br><br>";

// echo "<h3>Postgraduate Supervision</h3>";
// echo "PhD Supervised: <strong>{$faculty_data['PhD Supervised']}</strong><br>";
// echo "Masters Supervised: <strong>{$faculty_data['Masters Supervised']}</strong><br>";
// echo "Total Supervision Score: <strong>{$faculty_data['supervision_score']}</strong><br><br>";

// echo "<h3>Professional Memberships</h3>";
// echo "Total Memberships: <strong>{$faculty_data['Professional Memberships']}</strong><br>";
// echo "Total Membership Score: <strong>{$faculty_data['membership_score']}</strong><br><br>";

// echo "<h3>Community Service</h3>";
// echo "Total Community Services: <strong>{$faculty_data['Community Services']}</strong><br>";
// echo "Total Community Service Score: <strong>{$faculty_data['community_service_score']}</strong><br><br>";

// echo "<h3>Other Academic Activities</h3>";
// foreach (['External Examination', 'Internal Examination', 'Conference Presentation', 'Journal Editor'] as $type) {
//     echo "$type: <strong>{$faculty_data[$type]}</strong><br>";
// }
// echo "Total Other Academic Activities Score: <strong>{$faculty_data['other_academic_score']}</strong><br><br>";

// echo "<h3>Teaching Experience</h3>";
// echo "Years of Teaching: <strong>{$faculty_data['teaching_experience_years']}</strong><br>";
// echo "Total Teaching Experience Score: <strong>{$faculty_data['teaching_experience_score']}</strong><br><br>";

// echo "<h3>Service to University</h3>";
// echo "Service Roles Held: <strong>{$faculty_data['University Service Roles']}</strong><br>";
// echo "Total University Service Score: <strong>{$faculty_data['university_service_score']}</strong><br>";

// echo "<h3 style='color: darkgreen;'>Overall Total Score</h3>";
// echo "<strong>{$faculty_data['total_score']}</strong><br>";

?>
<!-- <h2>Performance Summary for Faculty of <?php //echo $faculty_name; ?></h2>
<pre><?php// print_r($faculty_data); ?></pre> -->
