<?php
session_start();
include '../head/approve/config.php';
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}
// Initialize response variable
$response = '';

// Fetch all criteria for decision making
$criteria_query = $conn->query("SELECT * FROM criteria");
$criteria = [];
while ($row = $criteria_query->fetch_assoc()) {
    $criteria[$row['category']][$row['name']] = $row;
}

// Process user request if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = strtolower(trim($_POST['query']));

    if (strpos($query, 'promotion') !== false || strpos($query, 'promote') !== false) {
        // Identify promotion candidates based on criteria
        $promotion_candidates = $conn->query("
            SELECT s.staff_id, s.first_name, s.last_name, d.department_name, r.role_name, 
                   s.performance_score, s.years_of_experience,
                   (SELECT COUNT(*) FROM publications p WHERE p.staff_id = s.staff_id) as publication_count,
                   (SELECT COUNT(*) FROM grants g WHERE g.staff_id = s.staff_id) as grant_count,
                   (SELECT COUNT(*) FROM degrees deg WHERE deg.staff_id = s.staff_id) as degree_count
            FROM staff s
            JOIN departments d ON s.department_id = d.department_id
            JOIN roles r ON s.role_id = r.role_id
            WHERE s.performance_score >= 80 AND s.years_of_experience >= 3
            ORDER BY s.performance_score DESC
        ");

        $response = "<div class='assistant-response'><h5>Promotion Candidates</h5>";
        if ($promotion_candidates->num_rows > 0) {
            $response .= "<div class='table-responsive'><table class='table table-hover'>
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Department</th>
                        <th>Current Role</th>
                        <th>Performance</th>
                        <th>Experience</th>
                        <th>Publications</th>
                        <th>Grants</th>
                        <th>Recommendation</th>
                    </tr>
                </thead>
                <tbody>";

            while ($candidate = $promotion_candidates->fetch_assoc()) {
                $recommendation = "Consider for " . getNextRole($candidate['role_name']);
                $response .= "<tr>
                    <td>{$candidate['first_name']} {$candidate['last_name']}</td>
                    <td>{$candidate['department_name']}</td>
                    <td>{$candidate['role_name']}</td>
                    <td><span class='badge bg-" . getPerformanceColor($candidate['performance_score']) . "'>{$candidate['performance_score']}</span></td>
                    <td>{$candidate['years_of_experience']} years</td>
                    <td>{$candidate['publication_count']}</td>
                    <td>{$candidate['grant_count']}</td>
                    <td>$recommendation</td>
                </tr>";
            }

            $response .= "</tbody></table></div>";
            $response .= "<p class='text-muted'>Based on performance score ≥ 80 and experience ≥ 3 years</p></div>";
        } else {
            $response .= "<div class='assistant-response'><p>No staff members currently meet the promotion criteria.</p></div>";
        }
    } elseif (strpos($query, 'salary') !== false || strpos($query, 'increase') !== false) {
        // Salary adjustment recommendations
        $salary_candidates = $conn->query("
            SELECT s.staff_id, s.first_name, s.last_name, d.department_name, r.role_name, 
                   s.performance_score,
                   (SELECT COUNT(*) FROM publications p WHERE p.staff_id = s.staff_id) as publication_count,
                   (SELECT COUNT(*) FROM grants g WHERE g.staff_id = s.staff_id) as grant_count,
                   (SELECT SUM(grant_amount) FROM grants g WHERE g.staff_id = s.staff_id) as total_grants
            FROM staff s
            JOIN departments d ON s.department_id = d.department_id
            JOIN roles r ON s.role_id = r.role_id
            WHERE s.performance_score >= 75
            ORDER BY s.performance_score DESC
        ");

        $response = "<div class='assistant-response'><h5>Salary Increase Recommendations</h5>";
        if ($salary_candidates->num_rows > 0) {
            $response .= "<div class='table-responsive'><table class='table table-hover'>
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Performance</th>
                        <th>Publications</th>
                        <th>Total Grants</th>
                        <th>Recommended Increase</th>
                    </tr>
                </thead>
                <tbody>";

            while ($candidate = $salary_candidates->fetch_assoc()) {
                $increase = calculateSalaryIncrease($candidate['performance_score'], $candidate['publication_count'], $candidate['total_grants']);
                $response .= "<tr>
                    <td>{$candidate['first_name']} {$candidate['last_name']}</td>
                    <td>{$candidate['department_name']}</td>
                    <td>{$candidate['role_name']}</td>
                    <td><span class='badge bg-" . getPerformanceColor($candidate['performance_score']) . "'>{$candidate['performance_score']}</span></td>
                    <td>{$candidate['publication_count']}</td>
                    <td>UGX " . number_format($candidate['total_grants'] ?? 0, 2) . "</td>
                    <td>$increase%</td>
                </tr>";
            }

            $response .= "</tbody></table></div>";
            $response .= "<p class='text-muted'>Based on performance and research contributions</p></div>";
        } else {
            $response .= "<div class='assistant-response'><p>No staff members currently qualify for salary increases.</p></div>";
        }
    } elseif (strpos($query, 'trend') !== false || strpos($query, 'analyze') !== false) {
        // Performance trend analysis
        $trends = $conn->query("
            SELECT d.department_name, 
                   AVG(s.performance_score) as avg_score,
                   COUNT(DISTINCT s.staff_id) as staff_count,
                   SUM(CASE WHEN s.performance_score >= 80 THEN 1 ELSE 0 END) as high_performers,
                   SUM(CASE WHEN s.performance_score < 60 THEN 1 ELSE 0 END) as low_performers
            FROM staff s
            JOIN departments d ON s.department_id = d.department_id
            GROUP BY d.department_name
            ORDER BY avg_score DESC
        ");

        $response = "<div class='assistant-response'><h5>Performance Trends by Department</h5>";
        if ($trends->num_rows > 0) {
            $response .= "<div class='table-responsive'><table class='table table-hover'>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Avg. Performance</th>
                        <th>Staff Count</th>
                        <th>High Performers</th>
                        <th>Low Performers</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>";

            while ($trend = $trends->fetch_assoc()) {
                $trend_icon = ($trend['avg_score'] >= 75) ? "fa-arrow-up text-success" : (($trend['avg_score'] <= 60) ? "fa-arrow-down text-danger" : "fa-minus text-warning");
                $response .= "<tr>
                    <td>{$trend['department_name']}</td>
                    <td><span class='badge bg-" . getPerformanceColor($trend['avg_score']) . "'>" . round($trend['avg_score'], 1) . "</span></td>
                    <td>{$trend['staff_count']}</td>
                    <td>{$trend['high_performers']}</td>
                    <td>{$trend['low_performers']}</td>
                    <td><i class='fas $trend_icon'></i></td>
                </tr>";
            }

            $response .= "</tbody></table></div>";
            $response .= "<div class='chart-container mt-4' style='height: 300px;'>
                            <canvas id='trendChart'></canvas>
                          </div></div>";
        }
    } elseif (preg_match('/about (.+)/i', $query, $matches)) {
        // Staff specific information
        $staff_name = trim($matches[1]);
        $staff_info = $conn->query("
            SELECT s.*, d.department_name, r.role_name, 
                   (SELECT COUNT(*) FROM publications p WHERE p.staff_id = s.staff_id) as publication_count,
                   (SELECT COUNT(*) FROM grants g WHERE g.staff_id = s.staff_id) as grant_count,
                   (SELECT COUNT(*) FROM degrees deg WHERE deg.staff_id = s.staff_id) as degree_count,
                   (SELECT COUNT(*) FROM supervision sup WHERE sup.staff_id = s.staff_id) as supervision_count
            FROM staff s
            JOIN departments d ON s.department_id = d.department_id
            JOIN roles r ON s.role_id = r.role_id
            WHERE CONCAT(s.first_name, ' ', s.last_name) LIKE '%$staff_name%'
               OR CONCAT(s.last_name, ' ', s.first_name) LIKE '%$staff_name%'
            LIMIT 1
        ");

        if ($staff_info->num_rows > 0) {
            $staff = $staff_info->fetch_assoc();
            $response = "<div class='assistant-response'><div class='staff-profile'>
                <h5>{$staff['first_name']} {$staff['last_name']}</h5>
                <div class='row'>
                    <div class='col-md-6'>
                        <p><strong>Department:</strong> {$staff['department_name']}</p>
                        <p><strong>Role:</strong> {$staff['role_name']}</p>
                        <p><strong>Performance Score:</strong> <span class='badge bg-" . getPerformanceColor($staff['performance_score']) . "'>{$staff['performance_score']}</span></p>
                    </div>
                    <div class='col-md-6'>
                        <p><strong>Experience:</strong> {$staff['years_of_experience']} years</p>
                        <p><strong>Publications:</strong> {$staff['publication_count']}</p>
                        <p><strong>Grants:</strong> {$staff['grant_count']}</p>
                    </div>
                </div>
                <div class='achievement-summary mt-3'>
                    <h6>Key Achievements</h6>
                    <ul>
                        <li>{$staff['degree_count']} academic degrees</li>
                        <li>{$staff['supervision_count']} student supervisions</li>
                    </ul>
                </div>";

            // Add evaluation based on criteria
            $evaluation = evaluateStaff($staff, $criteria);
            $response .= "<div class='evaluation mt-3'>
                            <h6>Evaluation Summary</h6>
                            <p>$evaluation</p>
                         </div>";

            $response .= "</div></div>";
        } else {
            $response = "<div class='assistant-response'><p>No staff member found with name matching '$staff_name'.</p></div>";
        }
    } else {
        $response = "<div class='assistant-response'><p>I didn't understand your request. Here are some examples of what you can ask:</p>
                    <ul>
                        <li>\"Show me promotion candidates\"</li>
                        <li>\"Who qualifies for salary increases?\"</li>
                        <li>\"Analyze performance trends\"</li>
                        <li>\"Tell me about [staff name]\"</li>
                    </ul></div>";
    }
}

// Helper functions
function getPerformanceColor($score)
{
    if ($score >= 80) return 'success';
    if ($score >= 60) return 'warning';
    return 'danger';
}

function getNextRole($current_role)
{
    $roles = [
        'Teaching Assistant' => 'Assistant Lecturer',
        'Assistant Lecturer' => 'Lecturer',
        'Lecturer' => 'Senior Lecturer',
        'Senior Lecturer' => 'Associate Professor',
        'Associate Professor' => 'Professor',
        'Professor' => 'Professor (no higher rank)'
    ];
    return $roles[$current_role] ?? 'next role';
}

function calculateSalaryIncrease($performance, $publications, $grants)
{
    $base = 0;
    if ($performance >= 90) $base = 15;
    elseif ($performance >= 80) $base = 10;
    elseif ($performance >= 75) $base = 5;

    $research_bonus = min(10, ($publications * 0.5) + ($grants > 500000000 ? 5 : 0));
    return min(25, $base + $research_bonus);
}

function evaluateStaff($staff, $criteria)
{
    $evaluation = [];

    // Evaluate based on academic qualifications
    if ($staff['degree_count'] > 0) {
        $evaluation[] = "Has {$staff['degree_count']} academic qualifications";
    }

    // Evaluate research output
    if ($staff['publication_count'] > 0) {
        $evaluation[] = "Authored {$staff['publication_count']} publications";
    }

    if ($staff['grant_count'] > 0) {
        $evaluation[] = "Secured {$staff['grant_count']} research grants";
    }

    // Evaluate performance
    if ($staff['performance_score'] >= 80) {
        $evaluation[] = "Consistently high performer";
    } elseif ($staff['performance_score'] >= 60) {
        $evaluation[] = "Meets performance expectations";
    } else {
        $evaluation[] = "Needs performance improvement";
    }

    // Evaluate experience
    if ($staff['years_of_experience'] >= 10) {
        $evaluation[] = "Extensive experience ({$staff['years_of_experience']} years)";
    } elseif ($staff['years_of_experience'] >= 5) {
        $evaluation[] = "Considerable experience ({$staff['years_of_experience']} years)";
    }

    return implode(". ", $evaluation) . ".";
}

// Fetch all staff for dropdown
$staff_query = $conn->query("SELECT s.staff_id, s.first_name, s.last_name, d.department_name
                           FROM staff s
                           JOIN departments d ON s.department_id = d.department_id
                           ORDER BY s.last_name, s.first_name");
$staff_list = $staff_query->fetch_all(MYSQLI_ASSOC);

// Get top performing staff (default view)
$top_performers_query = $conn->query("SELECT s.staff_id, s.first_name, s.last_name, d.department_name, 
                                     s.performance_score, s.years_of_experience, r.role_name,
                                     (SELECT COUNT(*) FROM publications p WHERE p.staff_id = s.staff_id) as publication_count,
                                     (SELECT COUNT(*) FROM grants g WHERE g.staff_id = s.staff_id) as grant_count
                                     FROM staff s
                                     JOIN departments d ON s.department_id = d.department_id
                                     JOIN roles r ON s.role_id = r.role_id
                                     ORDER BY s.performance_score DESC
                                     LIMIT 10");
$top_performing_staff = $top_performers_query->fetch_all(MYSQLI_ASSOC);

// Get department performance stats for chart
$dept_performance_query = $conn->query("SELECT d.department_name, AVG(s.performance_score) as avg_score
                                      FROM staff s
                                      JOIN departments d ON s.department_id = d.department_id
                                      GROUP BY d.department_name
                                      ORDER BY avg_score DESC");
$department_stats = $dept_performance_query->fetch_all(MYSQLI_ASSOC);

// Get recent achievements across all staff
$recent_achievements_query = $conn->query("SELECT 
                                          'Publication' as achievement_type, 
                                          CONCAT(s.first_name, ' ', s.last_name) as staff_name,
                                          d.department_name,
                                          p.publication_type as detail,
                                          NULL as date
                                          FROM publications p
                                          JOIN staff s ON p.staff_id = s.staff_id
                                          JOIN departments d ON s.department_id = d.department_id
                                          UNION ALL
                                          SELECT 
                                          'Grant' as achievement_type,
                                          CONCAT(s.first_name, ' ', s.last_name) as staff_name,
                                          d.department_name,
                                          CONCAT('UGX ', FORMAT(g.grant_amount, 2)) as detail,
                                          NULL as date
                                          FROM grants g
                                          JOIN staff s ON g.staff_id = s.staff_id
                                          JOIN departments d ON s.department_id = d.department_id
                                          UNION ALL
                                          SELECT 
                                          'Innovation' as achievement_type,
                                          CONCAT(s.first_name, ' ', s.last_name) as staff_name,
                                          d.department_name,
                                          i.innovation_type as detail,
                                          NULL as date
                                          FROM innovations i
                                          JOIN staff s ON i.staff_id = s.staff_id
                                          JOIN departments d ON s.department_id = d.department_id
                                          ORDER BY date DESC
                                          LIMIT 5");
$recent_achievements = $recent_achievements_query->fetch_all(MYSQLI_ASSOC);

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>