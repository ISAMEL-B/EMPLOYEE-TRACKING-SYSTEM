<?php
session_start();
require_once '../approve/config.php';

// Fetch all criteria for decision making
$criteria_query = $conn->query("SELECT * FROM criteria");
$criteria = [];
while ($row = $criteria_query->fetch_assoc()) {
    $criteria[$row['category']][$row['name']] = $row;
}

// Process user request if submitted
$response = '';
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
    }
    elseif (strpos($query, 'salary') !== false || strpos($query, 'increase') !== false) {
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
    }
    elseif (strpos($query, 'trend') !== false || strpos($query, 'analyze') !== false) {
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
                $trend_icon = ($trend['avg_score'] >= 75) ? "fa-arrow-up text-success" : 
                              (($trend['avg_score'] <= 60) ? "fa-arrow-down text-danger" : "fa-minus text-warning");
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
    }
    elseif (preg_match('/about (.+)/i', $query, $matches)) {
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
    }
    else {
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
function getPerformanceColor($score) {
    if ($score >= 80) return 'success';
    if ($score >= 60) return 'warning';
    return 'danger';
}

function getNextRole($current_role) {
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

function calculateSalaryIncrease($performance, $publications, $grants) {
    $base = 0;
    if ($performance >= 90) $base = 15;
    elseif ($performance >= 80) $base = 10;
    elseif ($performance >= 75) $base = 5;
    
    $research_bonus = min(10, ($publications * 0.5) + ($grants > 500000000 ? 5 : 0));
    return min(25, $base + $research_bonus);
}

function evaluateStaff($staff, $criteria) {
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Decision Support - MUST HRM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --must-yellow: #FFD700; 
            --must-green: #4CAF50;
            --must-blue: #2e3192;
            --must-blue-light: #4a5bdf;
            --must-blue-dark: #1a237e;
            --light-gray: #f5f5f5;
            --dark-gray: #333;
            --medium-gray: #777;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .decision-card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .decision-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .assistant-header {
            background-color: var(--must-blue);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .chat-container {
            max-height: 400px;
            overflow-y: auto;
            padding: 15px;
            background-color: #fafafa;
            border-radius: var(--border-radius);
        }

        .user-query {
            background-color: var(--light-gray);
            border-radius: 18px;
            padding: 8px 15px;
            margin: 5px 0;
            max-width: 80%;
            float: right;
            clear: both;
        }

        .assistant-response {
            background-color: #e9f7fe;
            border-radius: 18px;
            padding: 15px;
            margin: 5px 0;
            max-width: 90%;
            float: left;
            clear: both;
        }

        .performance-badge {
            font-size: 0.9rem;
            padding: 0.35rem 0.6rem;
            border-radius: 10px;
        }

        .badge-success {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--must-green);
        }

        .badge-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .badge-danger {
            background-color: rgba(244, 67, 54, 0.1);
            color: #f44336;
        }

        .department-chart-container {
            position: relative;
            height: 250px;
        }

        .search-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
        }

        .search-btn {
            background-color: var(--must-blue);
            color: white;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .search-btn:hover {
            background-color: var(--must-blue-dark);
            transform: translateY(-2px);
        }

        .profile-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
        }

        .profile-img-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--must-blue-light);
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .achievement-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            text-align: center;
            margin-bottom: 15px;
        }

        .achievement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .achievement-icon {
            color: var(--must-blue);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .badge-must {
            background-color: var(--must-blue);
            color: white;
        }

        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: var(--must-blue-light);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 15px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--must-blue);
            border: 2px solid white;
        }
    </style>
</head>

<body>
    <!-- navigation bar -->
    <?php //include '../bars/nav_bar.php'; ?>

    <!-- sidebar -->
    <?php //include '../bars/side_bar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="card decision-card mb-4">
                        <div class="card-header assistant-header">
                            <h4 class="mb-0">HR Decision Support Assistant</h4>
                        </div>
                        <div class="card-body">
                            <div class="chat-container mb-3">
                                <div class="assistant-response">
                                    <p class="mb-1"><strong>Assistant:</strong> Hello! I'm your HR Decision Support Assistant. I can help you with:</p>
                                    <ul class="mb-1">
                                        <li>Identifying promotion candidates</li>
                                        <li>Salary adjustment recommendations</li>
                                        <li>Performance trend analysis</li>
                                        <li>Staff evaluation summaries</li>
                                    </ul>
                                    <p class="mb-0">Try asking things like: "Show me promotion candidates" or "Tell me about [staff name]"</p>
                                </div>
                                
                                <?php if (!empty($_POST['query'])): ?>
                                    <div class="user-query">
                                        <strong>You:</strong> <?= htmlspecialchars($_POST['query']) ?>
                                    </div>
                                    <?= $response ?>
                                <?php endif; ?>
                            </div>
                            
                            <form method="POST" class="mt-3">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control" placeholder="Ask me anything about staff performance..." required>
                                    <button class="btn search-btn" type="submit">
                                        <i class="fas fa-search me-2"></i> Ask
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Staff Performance Overview -->
                    <div class="card decision-card mb-4">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">Staff Performance Overview</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Staff Member</th>
                                            <th>Department</th>
                                            <th>Performance</th>
                                            <th>Experience</th>
                                            <th>Publications</th>
                                            <th>Grants</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($top_performing_staff as $index => $staff): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($staff['last_name']) ?>, <?= htmlspecialchars($staff['first_name']) ?></td>
                                                <td><?= htmlspecialchars($staff['department_name']) ?></td>
                                                <td><span class="badge bg-<?= getPerformanceColor($staff['performance_score']) ?>"><?= $staff['performance_score'] ?></span></td>
                                                <td><?= $staff['years_of_experience'] ?> years</td>
                                                <td><?= $staff['publication_count'] ?? 0 ?></td>
                                                <td><?= $staff['grant_count'] ?? 0 ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Quick Actions -->
                    <div class="card decision-card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="mb-3">
                                <input type="hidden" name="query" value="Show me promotion candidates">
                                <button type="submit" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="fas fa-user-graduate me-2"></i> View Promotion Candidates
                                </button>
                            </form>
                            <form method="POST" class="mb-3">
                                <input type="hidden" name="query" value="Who qualifies for salary increases?">
                                <button type="submit" class="btn btn-outline-success w-100 mb-2">
                                    <i class="fas fa-money-bill-wave me-2"></i> Salary Increase Qualifiers
                                </button>
                            </form>
                            <form method="POST" class="mb-3">
                                <input type="hidden" name="query" value="Analyze performance trends">
                                <button type="submit" class="btn btn-outline-info w-100 mb-2">
                                    <i class="fas fa-chart-line me-2"></i> Performance Trends
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Department Performance -->
                    <div class="card decision-card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Department Performance</h5>
                        </div>
                        <div class="card-body">
                            <div class="department-chart-container">
                                <canvas id="departmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Achievements -->
                    <div class="card decision-card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Recent Achievements</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php foreach ($recent_achievements as $achievement): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($achievement['achievement_type']) ?></h6>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($achievement['date'] ?? 'now')) ?></small>
                                        </div>
                                        <p class="mb-1"><?= htmlspecialchars($achievement['staff_name']) ?></p>
                                        <small class="text-muted"><?= htmlspecialchars($achievement['department_name']) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize Department Performance Chart
        $(document).ready(function() {
            const deptCtx = document.getElementById('departmentChart').getContext('2d');
            const departmentChart = new Chart(deptCtx, {
                type: 'bar',
                data: {
                    labels: [<?= implode(',', array_map(function($dept) { return "'" . htmlspecialchars($dept['department_name']) . "'"; }, $department_stats)) ?>],
                    datasets: [{
                        label: 'Average Performance Score',
                        data: [<?= implode(',', array_map(function($dept) { return $dept['avg_score']; }, $department_stats)) ?>],
                        backgroundColor: '#2e3192',
                        borderColor: '#1A237E',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Department Performance',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    }
                }
            });

            <?php if (isset($response) && strpos($response, 'trendChart') !== false): ?>
                // Initialize Trend Chart if it was requested
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                const trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Average Performance',
                            data: [75, 78, 82, 80, 85, 83, 87, 85, 88, 90, 89, 91],
                            fill: false,
                            borderColor: '#2e3192',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Monthly Performance Trend',
                                font: {
                                    size: 16
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                min: 70,
                                max: 100
                            }
                        }
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>