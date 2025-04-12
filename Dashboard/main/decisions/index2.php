<?php
session_start();
include '../approve/config.php';

// Initialize conversation in session if it doesn't exist
if (!isset($_SESSION['chat_conversation'])) {
    $_SESSION['chat_conversation'] = [
        [
            'text' => '<strong>Hello!</strong> I\'m your HR Decision Support Assistant. I can help you with:<br><br>
                        • Identifying promotion candidates<br>
                        • Salary adjustment recommendations<br>
                        • Performance trend analysis<br>
                        • Staff evaluation summaries<br><br>
                        How can I assist you today?',
            'isUser' => false
        ]
    ];
}

// Initialize response variable
$response = '';

// Fetch all criteria for decision making
$criteria_query = $conn->query("SELECT * FROM criteria");
$criteria = [];
while ($row = $criteria_query->fetch_assoc()) {
    $criteria[$row['category']][$row['name']] = $row;
}

// Handle AJAX request for chat history
if (isset($_GET['get_chat_history'])) {
    header('Content-Type: application/json');
    echo json_encode($_SESSION['chat_conversation']);
    exit;
}

// Handle clearing chat
if (isset($_GET['clear_chat'])) {
    $_SESSION['chat_conversation'] = [
        [
            'text' => '<strong>Hello!</strong> I\'m your HR Decision Support Assistant. I can help you with:<br><br>
                        • Identifying promotion candidates<br>
                        • Salary adjustment recommendations<br>
                        • Performance trend analysis<br>
                        • Staff evaluation summaries<br><br>
                        How can I assist you today?',
            'isUser' => false
        ]
    ];
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}

// Process user request if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $query = strtolower(trim($_POST['query']));
    
    // Add user message to conversation
    $_SESSION['chat_conversation'][] = [
        'text' => htmlspecialchars($query),
        'isUser' => true
    ];
    
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
    
    // Add bot response to conversation
    $_SESSION['chat_conversation'][] = [
        'text' => $response,
        'isUser' => false
    ];
    
    // For AJAX requests, return the updated conversation
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($_SESSION['chat_conversation']);
        exit;
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

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM Decision Support System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/chatbot.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'resource/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>HR Decision Support Dashboard</h1>
                <div class="header-actions">
                    <button class="header-btn">
                        <i class="fas fa-plus"></i> New Staff
                    </button>
                    <button class="header-btn" id="clear-chat-btn">
                        <i class="fas fa-trash"></i> Clear Chat
                    </button>
                    <button class="header-btn">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
                <div class="user-info">
                    <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['first_name'] ?? 'H', 0, 1)) . strtoupper(substr($_SESSION['last_name'] ?? 'R', 0, 1)); ?></div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars(($_SESSION['first_name'] ?? 'HR') . ' ' . ($_SESSION['last_name'] ?? 'Manager')); ?></span>
                        <span class="user-role"><?php echo $_SESSION['user_role'] === 'hrm' ? 'HR Manager' : 'Administrator'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Staff</h3>
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-value"><?php echo count($staff_list); ?></div>
                        <p class="card-description">Active employees</p>
                        <div class="card-footer">
                            <i class="fas fa-arrow-up"></i> 12% from last year
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Promotion Candidates</h3>
                        <div class="card-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-value"><?php
                                                $promotion_count = $conn->query("SELECT COUNT(*) as count FROM staff WHERE performance_score >= 80 AND years_of_experience >= 3")->fetch_assoc()['count'];
                                                echo $promotion_count;
                                                ?></div>
                        <p class="card-description">Based on performance</p>
                        <div class="card-footer">
                            <i class="fas fa-clock"></i> Review pending
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Salary Adjustments</h3>
                        <div class="card-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-value"><?php
                                                $salary_adjustments = $conn->query("SELECT COUNT(*) as count FROM staff WHERE performance_score >= 75")->fetch_assoc()['count'];
                                                echo $salary_adjustments;
                                                ?></div>
                        <p class="card-description">Pending reviews</p>
                        <div class="card-footer">
                            <i class="fas fa-exclamation-circle"></i> 3 overdue
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Avg. Performance</h3>
                        <div class="card-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-value"><?php
                                                $avg_performance = $conn->query("SELECT AVG(performance_score) as avg FROM staff")->fetch_assoc()['avg'];
                                                echo round($avg_performance, 1) . '%';
                                                ?></div>
                        <p class="card-description">University-wide</p>
                        <div class="card-footer">
                            <i class="fas fa-arrow-up"></i> 3% improvement
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Visualization Section -->
            <div class="data-section">
                <div class="chart-container">
                    <h3>Performance Metrics</h3>
                    <div id="performance-chart" style="height: 300px;">
                        <canvas id="performanceCanvas"></canvas>
                    </div>
                </div>

                <div class="chart-container">
                    <h3>Department Distribution</h3>
                    <div id="department-chart" style="height: 300px;">
                        <canvas id="departmentCanvas"></canvas>
                    </div>
                </div>
            </div>

            <!-- Staff Performance Table -->
            <div class="table-container">
                <div class="table-header">
                    <h3>Staff Performance Overview</h3>
                    <div class="table-actions">
                        <button class="table-btn secondary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="table-btn primary">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="table-body">
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Performance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_performing_staff as $staff):
                                $isPromotionCandidate = $staff['performance_score'] >= 80 && $staff['years_of_experience'] >= 3;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($staff['staff_id']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['department_name']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['role_name']); ?></td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: <?php echo $staff['performance_score']; ?>%"></div>
                                            </div>
                                            <span class="progress-value"><?php echo $staff['performance_score']; ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($isPromotionCandidate): ?>
                                            <span class="badge badge-success">Promotion Candidate</span>
                                        <?php elseif ($staff['performance_score'] < 70): ?>
                                            <span class="badge badge-warning">Needs Improvement</span>
                                        <?php else: ?>
                                            <span class="badge">Satisfactory</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view" data-user-id="<?php echo $staff['staff_id']; ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php if ($isPromotionCandidate): ?>
                                                <button class="action-btn promote" data-user-id="<?php echo $staff['staff_id']; ?>">
                                                    <i class="fas fa-arrow-up"></i> Promote
                                                </button>
                                            <?php endif; ?>
                                            <button class="action-btn adjust" data-staff-id="<?php echo $staff['staff_id']; ?>">
                                                <i class="fas fa-dollar-sign"></i> Adjust
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- AI Decision Support Assistant -->
            <div class="ai-assistant">
                <div class="ai-header">
                    <div class="ai-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h3 class="ai-title">HR Decision Support Assistant</h3>
                        <p class="ai-subtitle">Powered by AI - Provides data-driven recommendations</p>
                    </div>
                </div>

                <div class="ai-conversation" id="ai-conversation">
                    <?php foreach ($_SESSION['chat_conversation'] as $message): ?>
                        <div class="ai-message <?php echo $message['isUser'] ? 'user' : 'bot'; ?>">
                            <?php echo $message['text']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="ai-input-container">
                    <form method="POST" action="" id="ai-form">
                        <input type="text" class="ai-input" id="ai-input" name="query" placeholder="Ask me anything about HR decisions (e.g., 'Show promotion candidates')">
                        <button type="submit" class="ai-send-btn" id="ai-send-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Performance Chart
        const performanceCtx = document.getElementById('performanceCanvas').getContext('2d');
        const performanceChart = new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'University Performance',
                    data: [75, 78, 82, 80, 85, 83, 87, 85, 88, 86, 89, 91],
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'white',
                    pointBorderColor: '#4CAF50',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'white',
                        titleColor: '#4CAF50',
                        bodyColor: '#333',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        padding: 12,
                        boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 70,
                        max: 100,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Department Distribution Chart
        const departmentCtx = document.getElementById('departmentCanvas').getContext('2d');
        const departmentChart = new Chart(departmentCtx, {
            type: 'pie',
            data: {
                labels: ['Academic', 'Administrative', 'Support Staff', 'Management', 'Research'],
                datasets: [{
                    data: [45, 20, 15, 10, 10],
                    backgroundColor: [
                        '#4CAF50',
                        '#4CAF50',
                        '#fdd835',
                        '#e74c3c',
                        '#9b59b6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#4CAF50',
                        bodyColor: '#333',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        padding: 12,
                        boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // AI Assistant Functionality
        const conversation = document.getElementById('ai-conversation');
        const aiForm = document.getElementById('ai-form');
        const aiInput = document.getElementById('ai-input');
        const sendBtn = document.getElementById('ai-send-btn');
        const clearChatBtn = document.getElementById('clear-chat-btn');

        // Staff data for the AI to reference
        const staffData = [
            <?php foreach ($top_performing_staff as $staff):
                $isPromotionCandidate = $staff['performance_score'] >= 80 && $staff['years_of_experience'] >= 3;
            ?> {
                    staff_id: '<?php echo $staff['staff_id']; ?>',
                    first_name: '<?php echo $staff['first_name']; ?>',
                    last_name: '<?php echo $staff['last_name']; ?>',
                    name: '<?php echo $staff['first_name'] . ' ' . $staff['last_name']; ?>',
                    department: '<?php echo $staff['department_name']; ?>',
                    position: '<?php echo $staff['role_name']; ?>',
                    performance: <?php echo $staff['performance_score']; ?>,
                    experience: <?php echo $staff['years_of_experience']; ?>,
                    publications: <?php echo $staff['publication_count']; ?>,
                    grants: <?php echo $staff['grant_count']; ?>,
                    isPromotionCandidate: <?php echo $isPromotionCandidate ? 'true' : 'false'; ?>,
                    lastEvaluation: '<?php echo date('M Y', strtotime('-' . rand(1, 12) . ' months')); ?>'
                },
            <?php endforeach; ?>
        ];

        function addMessage(text, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `ai-message ${isUser ? 'user' : 'bot'}`;
            messageDiv.innerHTML = text;
            conversation.appendChild(messageDiv);
            conversation.scrollTop = conversation.scrollHeight;
            return messageDiv;
        }

        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'ai-message bot typing';
            typingDiv.innerHTML = '<div class="ai-typing-indicator"><span></span><span></span><span></span></div>';
            conversation.appendChild(typingDiv);
            conversation.scrollTop = conversation.scrollHeight;
            return typingDiv;
        }

        function removeTypingIndicator(typingDiv) {
            typingDiv.remove();
        }

        function getAIResponse(userInput) {
            const input = userInput.toLowerCase();

            // Promotion queries
            if (input.includes('promot') || input.includes('advance') || input.includes('candidate')) {
                const candidates = staffData.filter(staff => staff.isPromotionCandidate);

                if (candidates.length === 0) {
                    return "There are currently no staff members identified as promotion candidates based on performance metrics.";
                }

                let response = `Based on performance data, I've identified <strong>${candidates.length} promotion candidates</strong>:<br><br>`;
                response += `<ul class="promotion-list">`;

                candidates.sort((a, b) => b.performance - a.performance).forEach(staff => {
                    response += `<li><strong>${staff.name}</strong> (${staff.department}, ${staff.position}) - ${staff.performance}% performance, ${staff.experience} years experience</li>`;
                });

                response += `</ul><br>Would you like me to generate promotion documentation for any of these candidates?`;
                return response;
            }

            // Salary adjustment queries
            if (input.includes('salary') || input.includes('adjust') || input.includes('compensation')) {
                const eligibleStaff = staffData.filter(staff => staff.performance > 85);

                if (eligibleStaff.length === 0) {
                    return "Currently, no staff members meet the criteria for salary adjustments (consistent performance above 85%).";
                }

                let response = `The salary adjustment algorithm suggests considering increases for <strong>${eligibleStaff.length} staff members</strong> with consistent high performance:<br><br>`;
                response += `<ul class="promotion-list">`;

                eligibleStaff.sort((a, b) => b.performance - a.performance).forEach(staff => {
                    const increaseRange = staff.performance > 90 ? '10-15%' : '8-12%';
                    response += `<li><strong>${staff.name}</strong>: Current performance ${staff.performance}%, recommended ${increaseRange} increase</li>`;
                });

                response += `</ul><br>Would you like me to prepare the adjustment proposals?`;
                return response;
            }

            // Performance queries
            if (input.includes('performance') || input.includes('metric') || input.includes('evaluation')) {
                const deptPerformance = {
                    'Academic': 91,
                    'Administrative': 78,
                    'Support Staff': 82,
                    'Management': 88,
                    'Research': 85
                };

                let response = `<strong>Performance Metrics Overview:</strong><br><br>`;
                response += `• University-wide average: <?php echo round($avg_performance, 1); ?>%<br>`;
                response += `• Highest performing department: Academic (91%)<br>`;
                response += `• Lowest performing department: Administrative (78%)<br><br>`;

                if (input.includes('trend')) {
                    response += `Performance trends show a 3% improvement overall compared to last quarter.<br>`;
                    response += `The most improved department is Research (+5% from last quarter).`;
                } else {
                    response += `Would you like me to analyze specific performance trends or comparisons?`;
                }

                return response;
            }

            // Staff-specific queries
            const mentionedStaff = staffData.find(staff =>
                input.includes(staff.first_name.toLowerCase()) ||
                input.includes(staff.last_name.toLowerCase()) ||
                input.includes(staff.name.toLowerCase())
            );

            if (mentionedStaff) {
                let response = `<strong>Staff Profile: ${mentionedStaff.name}</strong><br><br>`;
                response += `• Department: ${mentionedStaff.department}<br>`;
                response += `• Position: ${mentionedStaff.position}<br>`;
                response += `• Current Performance: ${mentionedStaff.performance}%<br>`;
                response += `• Experience: ${mentionedStaff.experience} years<br>`;
                response += `• Publications: ${mentionedStaff.publications}<br>`;
                response += `• Grants: ${mentionedStaff.grants}<br>`;
                response += `• Last Evaluation: ${mentionedStaff.lastEvaluation}<br><br>`;

                if (mentionedStaff.isPromotionCandidate) {
                    response += `This staff member is identified as a <strong>promotion candidate</strong> based on consistent high performance.<br>`;
                } else if (mentionedStaff.performance < 70) {
                    response += `This staff member <strong>requires performance improvement</strong> (below 70% threshold).<br>`;
                } else {
                    response += `Performance is at satisfactory levels.<br>`;
                }

                response += `Would you like more details or specific recommendations for ${mentionedStaff.first_name}?`;
                return response;
            }

            // Default responses
            const randomResponses = [
                "I can analyze staff performance data to identify trends and make recommendations. What specific information would you like?",
                "Would you like me to generate a report on promotion candidates or salary adjustments?",
                "I've identified several patterns in recent performance evaluations that might interest you.",
                "The decision support system can predict promotion success with 92% accuracy based on historical data.",
                "I can compare individual performance against department averages if that would be helpful."
            ];
            return randomResponses[Math.floor(Math.random() * randomResponses.length)];
        }

        // Handle form submission with AJAX
        aiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const userMessage = aiInput.value.trim();
            if (userMessage) {
                // Add user message immediately
                addMessage(userMessage, true);
                aiInput.value = '';

                const typingIndicator = showTypingIndicator();

                // Send to server via AJAX
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `query=${encodeURIComponent(userMessage)}`
                })
                .then(response => response.json())
                .then(data => {
                    removeTypingIndicator(typingIndicator);
                    // Clear and rebuild conversation from server data
                    conversation.innerHTML = '';
                    data.forEach(msg => {
                        addMessage(msg.text, msg.isUser);
                    });
                })
                .catch(error => {
                    removeTypingIndicator(typingIndicator);
                    addMessage("Sorry, there was an error processing your request.");
                    console.error('Error:', error);
                });
            }
        });

        // Clear chat button
        clearChatBtn.addEventListener('click', function() {
            fetch('?clear_chat=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Reload the conversation from server
                    conversation.innerHTML = '';
                    fetch('?get_chat_history=1')
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(msg => addMessage(msg.text, msg.isUser));
                        });
                }
            });
        });

        // Staff action buttons
        document.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', function() {
                const staffId = this.getAttribute('data-user-id');
                const staff = staffData.find(s => s.staff_id === staffId);

                if (staff) {
                    addMessage(`User requested details for ${staff.name}`, true);
                    const typingIndicator = showTypingIndicator();

                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);
                        const response = `
                        <strong>${staff.name}</strong> (${staff.department})<br><br>
                        • Position: ${staff.position}<br>
                        • Performance: ${staff.performance}%<br>
                        • Experience: ${staff.experience} years<br>
                        • Last Evaluation: ${staff.lastEvaluation}<br>
                        • Status: ${staff.isPromotionCandidate ? 'Promotion Candidate' : 'Regular'}<br><br>
                        ${staff.isPromotionCandidate ? 
                            'This staff member qualifies for promotion consideration.' : 
                            staff.performance < 70 ? 
                            'Performance improvement plan recommended.' : 
                            'Performance at satisfactory levels.'
                        }
                    `;
                        addMessage(response);
                    }, 1500);
                }
            });
        });

        document.querySelectorAll('.action-btn.promote').forEach(btn => {
            btn.addEventListener('click', function() {
                const staffId = this.getAttribute('data-user-id');
                const staff = staffData.find(s => s.staff_id === staffId);

                if (staff) {
                    addMessage(`User initiated promotion process for ${staff.name}`, true);
                    const typingIndicator = showTypingIndicator();

                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);
                        const response = `
                        <strong>Promotion Recommendation for ${staff.name}</strong><br><br>
                        • Current Position: ${staff.position}<br>
                        • Recommended Position: Senior ${staff.position}<br>
                        • Performance Justification: ${staff.performance}% average<br>
                        • Experience: ${staff.experience} years<br>
                        • Department Rank: Top ${staff.performance > 90 ? '5%' : '15%'}<br><br>
                        Would you like me to generate the promotion documentation?
                    `;
                        addMessage(response);
                    }, 2000);
                }
            });
        });

        document.querySelectorAll('.action-btn.adjust').forEach(btn => {
            btn.addEventListener('click', function() {
                const staffId = this.getAttribute('data-staff-id');
                const staff = staffData.find(s => s.staff_id === staffId);

                if (staff) {
                    addMessage(`User requested salary adjustment analysis for ${staff.name}`, true);
                    const typingIndicator = showTypingIndicator();

                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);
                        const increaseRange = staff.performance > 90 ? '10-15%' :
                            staff.performance > 85 ? '8-12%' :
                            staff.performance > 80 ? '5-8%' : '0-3%';

                        const response = `
                        <strong>Salary Adjustment for ${staff.name}</strong><br><br>
                        • Current Performance: ${staff.performance}%<br>
                        • Department Average: ${staff.department === 'Academic' ? '91%' : 
                                            staff.department === 'Administrative' ? '78%' : 
                                            staff.department === 'Support Staff' ? '82%' : 
                                            staff.department === 'Management' ? '88%' : '85%'}<br>
                        • Market Benchmark: ${staff.position.includes('Senior') ? '15% above' : '5% above'} industry standard<br>
                        • Recommended Adjustment: ${increaseRange} increase<br><br>
                        Shall I prepare the adjustment proposal?
                    `;
                        addMessage(response);
                    }, 2000);
                }
            });
        });

        // Initial assistant suggestions
        setTimeout(() => {
            const typingIndicator = showTypingIndicator();

            setTimeout(() => {
                removeTypingIndicator(typingIndicator);
                addMessage("You can ask me things like:<br><br>" +
                    "• \"Show me promotion candidates\"<br>" +
                    "• \"Who qualifies for salary increases?\"<br>" +
                    "• \"Analyze performance trends\"<br>" +
                    "• \"Tell me about [staff name]\"");
            }, 1500);
        }, 3000);
    </script>
</body>
</html>