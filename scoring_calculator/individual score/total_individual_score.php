<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config.php'; // Include database configuration
include __DIR__ . '/../publication_score.php';
include __DIR__ . '/../post_graduate_supervision_score.php';
include __DIR__ . '/../grants_score.php';
include __DIR__ . '/../academic_score.php';
include __DIR__ . '/../community_service_score.php';
include __DIR__ . '/../innovation_score.php';
include __DIR__ . '/../membership_to_professional_bodies_score.php';
include __DIR__ . '/../other_academic_activities_score.php';
include __DIR__ . '/../service_to_university_score.php';
include __DIR__ . '/../teaching_experience_score.php';


function get_individual_performance_breakdown($conn, $staff_id) {
    $individual_data = [
        // Academic Qualifications
        'PhD' => 0, 'Masters' => 0, 'First Class' => 0, 'Second Upper' => 0, 'Other' => 0, 'academic_score' => 0,

        // Grants
        'Over 1B' => 0, '500M - 1B' => 0, '100M - 500M' => 0, 'Below 100M' => 0, 'grant_score' => 0, 'total_grant_amount' => 0, 'grant_count' => 0,

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

    // Load the scoring classes
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

    // Academic
    $acad = $scholar->get_score_details();
    $individual_data['PhD'] = $acad['PhD'] ?? 0;
    $individual_data['Masters'] = $acad['Masters'] ?? 0;
    $individual_data['First Class'] = $acad['First Class'] ?? 0;
    $individual_data['Second Upper'] = $acad['Second Upper'] ?? 0;
    $individual_data['Other'] = $acad['Other'] ?? 0;
    $individual_data['academic_score'] = $acad['score'] ?? 0;

    // Grants
    $grants = $researchGrantsScore->get_score_details();
    foreach (['Over 1B', '500M - 1B', '100M - 500M', 'Below 100M'] as $range) {
        $individual_data[$range] = $grants[$range] ?? 0;
    }
    $individual_data['grant_score'] = $grants['score'] ?? 0;
    $individual_data['total_grant_amount'] = $grants['total_grant_amount'] ?? 0;
    $individual_data['grant_count'] = $grants['grant_count'] ?? 0; 

    // Innovations
    $inno = $innovationScore->get_score_details();
    foreach (['Patent', 'Utility Model', 'Copyright', 'Product', 'Trademark'] as $type) {
        $individual_data[$type] = $inno[$type] ?? 0;
    }
    $individual_data['innovation_score'] = $inno['score'] ?? 0;
    $individual_data['total_innovations'] = $inno['total_innovations'] ?? 0;

    // Publications
    $pubs = $publicationScore->get_score_details();
    foreach (['Journal Articles (First Author)', 'Journal Articles (Corresponding Author)', 'Journal Articles (Co-author)', 'Book with ISBN', 'Book Chapter'] as $pubtype) {
        $individual_data[$pubtype] = $pubs[$pubtype] ?? 0;
    }
    $individual_data['publication_score'] = $pubs['score'] ?? 0;
    $individual_data['total_publications'] = $pubs['total_publications'] ?? 0;

    // Supervision
    $sup = $postgraduateSupervisionScore->get_score_details();
    $individual_data['PhD Supervised'] = $sup['PhD Supervised'] ?? 0;
    $individual_data['Masters Supervised'] = $sup['Masters Supervised'] ?? 0;
    $individual_data['supervision_score'] = $sup['score'] ?? 0;

    // Memberships
    $mem = $membershipScore->get_score_details();
    $individual_data['Professional Memberships'] = $mem['Professional Memberships'] ?? 0;
    $individual_data['membership_score'] = $mem['score'] ?? 0;

    // Community Service
    $comm = $communityServiceScore->get_score_details();
    $individual_data['Community Services'] = $comm['Community Services'] ?? 0;
    $individual_data['community_service_score'] = $comm['score'] ?? 0;

    // Academic Activities
    $acad_act = $academicActivitiesScore->get_score_details();
    foreach (['External Examination', 'Internal Examination', 'Conference Presentation', 'Journal Editor'] as $activity) {
        $individual_data[$activity] = $acad_act[$activity] ?? 0;
    }
    $individual_data['other_academic_score'] = $acad_act['score'] ?? 0;

    // Teaching Experience
    $teach = $teachingExperienceScore->get_score_details();
    $individual_data['teaching_experience_years'] = $teach['Years of Experience'] ?? 0;
    $individual_data['teaching_experience_score'] = $teach['score'] ?? 0;

    // University Service
    $serv = $serviceToUniversityScore->get_score_details();
    $individual_data['University Service Roles'] = $serv['University Services'] ?? 0;
    $individual_data['university_service_score'] = $serv['score'] ?? 0;

    // Total Score
    $individual_data['total_score'] =
        $individual_data['academic_score'] +
        $individual_data['grant_score'] +
        $individual_data['innovation_score'] +
        $individual_data['publication_score'] +
        $individual_data['supervision_score'] +
        $individual_data['membership_score'] +
        $individual_data['community_service_score'] +
        $individual_data['other_academic_score'] +
        $individual_data['teaching_experience_score'] +
        $individual_data['university_service_score'];

    return $individual_data;
}

// $staff_id = 4;
// $individual_breakdown = get_individual_performance_breakdown($conn, $staff_id);
// print_r($individual_breakdown);

?>
