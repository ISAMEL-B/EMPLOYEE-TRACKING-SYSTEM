<?php
session_start();
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

require_once 'head/approve/config.php'; // Use conn instead of pdo
// require_once 'head/approve/pdo.php'; 

function countRecords($conn, $table)
{
    $result = $conn->query("SELECT COUNT(*) AS count FROM $table");
    $row = $result->fetch_assoc();
    return $row['count'];
}

function countRecordsWhere($conn, $table, $condition)
{
    $result = $conn->query("SELECT COUNT(*) AS count FROM $table WHERE $condition");
    $row = $result->fetch_assoc();
    return $row['count'];
}

$totalStaff = countRecords($conn, 'staff');
$mastersCount = countRecordsWhere($conn, 'degrees', "degree_classification LIKE '%Master%' OR degree_name LIKE '%Mast%' OR degree_name LIKE '%MSc%'");
$phdHolders = countRecordsWhere($conn, 'degrees', "degree_classification LIKE '%PhD%'");

//not yet used
$publicationsCount = countRecords($conn, 'publications');
//not used yet
$grantsCount = countRecords($conn, 'grants');
$innovationsCount = countRecords($conn, 'innovations');
// Not used yet
$communityServiceCount = countRecords($conn, 'communityservice');

// Sum of beneficiaries (assuming there's a 'beneficiaries' column in communityservice table)
$beneficiariesSum = 0;
$beneficiariesQuery = $conn->query("SELECT SUM(beneficiaries) as total FROM communityservice");
if ($beneficiariesResult = $beneficiariesQuery->fetch_assoc()) {
    $beneficiariesSum = $beneficiariesResult['total'] ?? 0;
}

// Sum of grants 
$grantsCount = 0;
$grantsCountQuery = $conn->query("SELECT SUM(grant_amount) as total FROM grants");
if ($grantsCountResult = $grantsCountQuery->fetch_assoc()) {
    $grantsCount = $grantsCountResult['total'] ?? 0;
}

// Function to format grant amounts
function formatGrants($amount)
{
    if ($amount >= 1_000_000_000) {
        // Billions (remove extra zeros and add 'B')
        return round($amount / 1_000_000_000, 1) . 'B';
    } elseif ($amount >= 1_000_000) {
        // Millions (remove extra zeros and add 'M')
        return round($amount / 1_000_000, 1) . 'M';
    } else {
        // Less than a million, show full amount with thousands separator
        return number_format($amount);
    }
}

// Count of DISTINCT project types (using the 'description' column as project type)
$projectTypesCount = 0;
$projectTypesQuery = $conn->query("SELECT COUNT(DISTINCT description) as count FROM communityservice WHERE description IS NOT NULL AND description != ''");
if ($projectTypesResult = $projectTypesQuery->fetch_assoc()) {
    $projectTypesCount = (int)$projectTypesResult['count'] ?? 0;
}

// Count of DISTINCT staff members involved in community service
$distinctStaffCount = 0;
$staffCountQuery = $conn->query("SELECT COUNT(DISTINCT staff_id) as count FROM communityservice WHERE staff_id IS NOT NULL");
if ($staffCountResult = $staffCountQuery->fetch_assoc()) {
    $distinctStaffCount = (int)$staffCountResult['count'] ?? 0;
}

// Count of ALL publications
$publicationsCount = 0;
$publicationsCountQuery = $conn->query("SELECT COUNT(*) as count FROM publications WHERE staff_id IS NOT NULL");
if ($publicationsCountResult = $publicationsCountQuery->fetch_assoc()) {
    $publicationsCount = (int)$publicationsCountResult['count'] ?? 0;
}

$degreeStatsResult = $conn->query("
    SELECT 
        SUM(CASE WHEN degree_classification = 'First Class' THEN 1 ELSE 0 END) as first_class,
        SUM(CASE WHEN degree_classification = 'Second Upper' THEN 1 ELSE 0 END) as second_upper,
        SUM(CASE WHEN degree_classification LIKE '%PhD%' THEN 1 ELSE 0 END) as phd,
        SUM(CASE WHEN degree_classification LIKE '%Master%' THEN 1 ELSE 0 END) as masters
    FROM degrees
");
$degreeStats = $degreeStatsResult->fetch_assoc();

$publicationTypes = [];
$pubResult = $conn->query("
    SELECT publication_type, COUNT(*) as count
    FROM publications
    GROUP BY publication_type
");
while ($row = $pubResult->fetch_assoc()) {
    $publicationTypes[] = $row;
}

// Query to get grants by faculty (through staff and department relationships)
$grantsByFaculty = [];
$grantResult = $conn->query("
    SELECT 
        f.faculty_name,
        COUNT(g.grant_id) AS grant_count,
        SUM(g.grant_amount) AS total_amount
    FROM grants g
    JOIN staff s ON g.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    JOIN faculties f ON d.faculty_id = f.faculty_id
    GROUP BY f.faculty_name
    ORDER BY total_amount DESC
");

while ($row = $grantResult->fetch_assoc()) {
    $grantsByFaculty[] = $row;
}


$supervisionStats = [];
$supervisionResult = $conn->query("
    SELECT student_level, COUNT(*) AS count
    FROM supervision
    GROUP BY student_level
");
while ($row = $supervisionResult->fetch_assoc()) {
    $supervisionStats[] = $row;
}

$innovationTypes = [];
$innovationResult = $conn->query("
    SELECT innovation_type, COUNT(*) AS count
    FROM innovations
    GROUP BY innovation_type
");
while ($row = $innovationResult->fetch_assoc()) {
    $innovationTypes[] = $row;
}

$communityServiceByFac = [];
$communityResult = $conn->query("
    SELECT f.faculty_name, COUNT(cs.community_service_id) AS service_count
    FROM communityservice cs
    JOIN staff s ON cs.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    JOIN faculties f ON d.faculty_id = f.faculty_id
    GROUP BY f.faculty_name
");

while ($row = $communityResult->fetch_assoc()) {
    $communityServiceByFac[] = $row;
}

// Charts data preparation (assuming $publicationTypes and $grantsByDept are defined elsewhere)
$publicationTypesData = [];
foreach ($publicationTypes as $type) {
    $publicationTypesData[$type['publication_type']] = $type['count'];
}

$supervisionStats = [];
$supervisionResult = $conn->query("
    SELECT student_level, COUNT(*) as count
    FROM supervision
    GROUP BY student_level
");
while ($row = $supervisionResult->fetch_assoc()) {
    $supervisionStats[] = $row;
}

$innovationTypes = [];
$innovationResult = $conn->query("
    SELECT innovation_type, COUNT(*) as count
    FROM innovations
    GROUP BY innovation_type
");
while ($row = $innovationResult->fetch_assoc()) {
    $innovationTypes[] = $row;
}

$communityServiceByFac = [];
$communityResult = $conn->query("
    SELECT f.faculty_name, COUNT(cs.community_service_id) AS service_count
    FROM communityservice cs
    JOIN staff s ON cs.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    JOIN faculties f ON d.faculty_id = f.faculty_id
    GROUP BY f.faculty_name
");

while ($row = $communityResult->fetch_assoc()) {
    $communityServiceByFac[] = $row;
}

// Charts
$publicationTypesData = [];
foreach ($publicationTypes as $type) {
    $publicationTypesData[$type['publication_type']] = $type['count'];
}

$grantsData = [];
foreach ($grantsByFaculty as $grant) {
    $grantsData[$grant['faculty_name']] = $grant['total_amount'];
}

$supervisionData = ['PhD' => 0, 'Masters' => 0, 'First Class' => 0];
foreach ($supervisionStats as $stat) {
    if ($stat['student_level'] === 'PhD') {
        $supervisionData['PhD'] = $stat['count'];
    } else if ($stat['student_level'] === 'First Class') {
        $supervisionData['First Class'] = $stat['count'];
    } else if ($stat['student_level'] === 'Second Class') {
        $supervisionData['Second Class'] = $stat['count'];
    } else {
        $supervisionData['Masters'] = $stat['count'];
    }
}

$innovationData = [];
foreach ($innovationTypes as $innovation) {
    $innovationData[$innovation['innovation_type']] = $innovation['count'];
}

$communityServiceData = [];
foreach ($communityServiceByFac as $service) {
    $communityServiceData[$service['faculty_name']] = $service['service_count'];
}

// Faculty performance
$facultyPerformance = [];
$facultyResult = $conn->query("
    SELECT 
        f.faculty_name,
        COUNT(DISTINCT p.publication_id) as publications,
        COUNT(DISTINCT g.grant_id) as grants,
        IFNULL(AVG(pm.metric_value), 0) as avg_performance,
        COUNT(DISTINCT s.staff_id) as staff_count
    FROM faculties f
    LEFT JOIN departments d ON f.faculty_id = d.faculty_id
    LEFT JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    LEFT JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    GROUP BY f.faculty_id
    ORDER BY publications DESC, grants DESC
");
while ($row = $facultyResult->fetch_assoc()) {
    $facultyPerformance[] = $row;
}
$topResearchFaculty = $facultyPerformance[0] ?? null;

$teachingResult = $conn->query("
    SELECT f.faculty_name
    FROM faculties f
    JOIN departments d ON f.faculty_id = d.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    WHERE pm.metric_name LIKE '%Student%Satisfaction%'
    GROUP BY f.faculty_id
    ORDER BY AVG(pm.metric_value) DESC
    LIMIT 1
");
$topTeachingFaculty = $teachingResult->fetch_assoc()['faculty_name'] ?? null;

$growthResult = $conn->query("
    SELECT f.faculty_name
    FROM faculties f
    JOIN departments d ON f.faculty_id = d.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    GROUP BY f.faculty_id
    ORDER BY (COUNT(DISTINCT p.publication_id) + COUNT(DISTINCT g.grant_id)) / COUNT(DISTINCT s.staff_id) DESC
    LIMIT 1
");
$growthFaculty = $growthResult->fetch_assoc()['faculty_name'] ?? null;
?>