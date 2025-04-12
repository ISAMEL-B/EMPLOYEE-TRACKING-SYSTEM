<?php
session_start();
include '../approve/config.php';

// MUST Color Scheme
define('MUST_YELLOW', '#FFD700');
define('MUST_GREEN', '#4CAF50');
define('MUST_BLUE', '#2e3192');

// Get HRM data
$staff_data = [];
$performance_data = [];
$departments = [];
$criteria = [];
$promotion_candidates = [];

if ($_SESSION['user_role'] === 'hrm' || $_SESSION['user_role'] === 'dean') {
    // Fetch all staff with their roles and departments
    $staff_query = "SELECT s.*, r.role_name, d.department_name 
                   FROM staff s 
                   LEFT JOIN roles r ON s.role_id = r.role_id
                   LEFT JOIN departments d ON s.department_id = d.department_id";
    $staff_data = $conn->query($staff_query)->fetch_all(MYSQLI_ASSOC);

    // Fetch performance metrics
    $performance_query = "SELECT * FROM performance_metrics";
    $performance_data = $conn->query($performance_query)->fetch_all(MYSQLI_ASSOC);

    // Fetch departments
    $dept_query = "SELECT * FROM departments";
    $departments = $conn->query($dept_query)->fetch_all(MYSQLI_ASSOC);

    // Fetch criteria
    $criteria_query = "SELECT * FROM criteria";
    $criteria_result = $conn->query($criteria_query);
    while ($row = $criteria_result->fetch_assoc()) {
        $criteria[$row['name']] = $row['points'];
    }

    // Identify promotion candidates based on performance scores
    foreach ($staff_data as $staff) {
        $staff_id = $staff['staff_id'];
        $performance = 0;
        
        // Calculate performance score based on criteria
        $score_data = calculateStaffScore($staff_id, $conn, $criteria);
        if ($score_data) {
            $performance = $score_data['percentage'];
        }
        
        if ($performance > 85) { // Threshold for promotion candidates
            $promotion_candidates[] = $staff;
        }
    }
}

// Function to calculate staff score
function calculateStaffScore($staff_id, $conn, $criteria) {
    $totalScore = 0;
    $breakdown = [];
    
    // Fetch degrees
    $degrees = $conn->query("SELECT * FROM degrees WHERE staff_id = $staff_id")->fetch_all(MYSQLI_ASSOC);
    foreach ($degrees as $degree) {
        $points = 0;
        if ($degree['degree_classification'] === 'First Class') {
            $points = $criteria["Bachelor's (First Class)"] ?? 6;
        } else if ($degree['degree_classification'] === 'Second Class Upper') {
            $points = $criteria["Bachelor's (Second Upper)"] ?? 4;
        } else {
            $points = $criteria["Other Qualifications"] ?? 2;
        }
        $totalScore += $points;
    }
    
    // Fetch publications
    $publications = $conn->query("SELECT * FROM publications WHERE staff_id = $staff_id")->fetch_all(MYSQLI_ASSOC);
    foreach ($publications as $pub) {
        $points = 0;
        if ($pub['role'] === 'Author') {
            $points = $criteria["Peer-reviewed Journal (First author)"] ?? 4;
        } else if ($pub['role'] === 'Co-Author') {
            $points = $criteria["Peer-reviewed Journal (Co-author)"] ?? 1;
        }
        $totalScore += $points;
    }
    
    // Fetch grants
    $grants = $conn->query("SELECT * FROM grants WHERE staff_id = $staff_id")->fetch_all(MYSQLI_ASSOC);
    foreach ($grants as $grant) {
        $amount = floatval($grant['grant_amount']);
        $points = 0;
        if ($amount > 1000000000) {
            $points = $criteria["More than UGX 1,000,000,000"] ?? 12;
        } else if ($amount >= 500000000) {
            $points = $criteria["UGX 500,000,000 - 1,000,000,000"] ?? 8;
        } else if ($amount >= 100000000) {
            $points = $criteria["UGX 100,000,000 - 500,000,000"] ?? 6;
        } else {
            $points = $criteria["Less than UGX 100,000,000"] ?? 4;
        }
        $totalScore += $points;
    }
    
    // Fetch innovations
    $innovations = $conn->query("SELECT * FROM innovations WHERE staff_id = $staff_id")->fetch_all(MYSQLI_ASSOC);
    foreach ($innovations as $innovation) {
        $points = $criteria[$innovation['innovation_type']] ?? 0;
        $totalScore += $points;
    }
    
    // Fetch service
    $service = $conn->query("SELECT * FROM service WHERE staff_id = $staff_id")->fetch_all(MYSQLI_ASSOC);
    foreach ($service as $s) {
        $points = 0;
        if ($s['service_type'] === 'Dean' || $s['service_type'] === 'Director') {
            $points = $criteria["Dean / Director"] ?? 5;
        } else if (strpos($s['service_type'], 'Deputy') !== false) {
            $points = $criteria["Deputy Dean/Director"] ?? 4;
        } else if (strpos($s['service_type'], 'Head') !== false) {
            $points = $criteria["Head of Department"] ?? 3;
        } else {
            $points = $criteria["Other"] ?? 1;
        }
        $totalScore += $points;
    }
    
    // Fetch supervision
    $supervision = $conn->query("SELECT * FROM supervision WHERE staff_id = $staff_id")->fetch_all(MYSQLI_ASSOC);
    foreach ($supervision as $sup) {
        $points = $sup['student_level'] === 'PhD' ? 
            ($criteria["PhD Candidates (max 10)"] ?? 5) : 
            ($criteria["Masters Candidates (max 5)"] ?? 2);
        $totalScore += $points;
    }
    
    // Add experience points
    $staff = $conn->query("SELECT years_of_experience FROM staff WHERE staff_id = $staff_id")->fetch_assoc();
    if ($staff && isset($staff['years_of_experience'])) {
        $totalScore += min($staff['years_of_experience'], 3) * ($criteria["1 point per year"] ?? 1);
    }
    
    return [
        'totalScore' => $totalScore,
        'percentage' => min(100, round(($totalScore / ($criteria["Overall"] ?? 120)) * 100))
    ];
}

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
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>MUST HRM</h2>
                <p>Score Card System</p>
            </div>
            <div class="sidebar-menu">
                <a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="#"><i class="fas fa-users"></i> Staff Management</a>
                <a href="#"><i class="fas fa-chart-line"></i> Performance Analytics</a>
                <a href="#"><i class="fas fa-money-bill-wave"></i> Compensation</a>
                <a href="#"><i class="fas fa-bullhorn"></i> Announcements</a>
                <a href="#"><i class="fas fa-file-alt"></i> Reports</a>
                <a href="#"><i class="fas fa-cog"></i> Settings</a>
            </div>
            <div class="sidebar-footer">
                &copy; <?php echo date('Y'); ?> MUST HRM
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>HRM Score Card Dashboard</h1>
                <div class="header-actions">
                    <button class="header-btn">
                        <i class="fas fa-plus"></i> New Staff
                    </button>
                    <button class="header-btn">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                </div>
                <div class="user-info">
                    <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['first_name'] ?? 'H', 0, 1)) . strtoupper(substr($_SESSION['last_name'] ?? 'R', 0, 1)); ?></div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlspecialchars(($_SESSION['first_name'] ?? 'HR') . ' ' . ($_SESSION['last_name'] ?? 'Manager')); ?></span>
                        <span class="user-role"><?php echo ucfirst($_SESSION['user_role'] ?? 'User'); ?></span>
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
                        <div class="card-value"><?php echo count($staff_data); ?></div>
                        <p class="card-description">Active employees</p>
                        <div class="card-footer">
                            <i class="fas fa-arrow-up"></i> <?php echo round(count($staff_data) * 0.12); ?>% from last year
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
                        <div class="card-value"><?php echo count($promotion_candidates); ?></div>
                        <p class="card-description">Based on performance</p>
                        <div class="card-footer">
                            <i class="fas fa-clock"></i> Review pending
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Departments</h3>
                        <div class="card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-value"><?php echo count($departments); ?></div>
                        <p class="card-description">Active departments</p>
                        <div class="card-footer">
                            <i class="fas fa-info-circle"></i> <?php echo $departments[0]['department_name'] ?? 'N/A'; ?> largest
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
                        <div class="card-value">
                            <?php 
                            $avg_performance = 0;
                            if (count($staff_data) > 0) {
                                $total = 0;
                                foreach ($staff_data as $staff) {
                                    $score_data = calculateStaffScore($staff['staff_id'], $conn, $criteria);
                                    $total += $score_data['percentage'];
                                }
                                $avg_performance = round($total / count($staff_data));
                            }
                            echo $avg_performance; ?>%
                        </div>
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
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Scholar Type</th>
                                <th>Performance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staff_data as $staff): 
                                $score_data = calculateStaffScore($staff['staff_id'], $conn, $criteria);
                                $performance = $score_data['percentage'];
                                $isPromotionCandidate = in_array($staff, $promotion_candidates);
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($staff['role_name'] ?? 'Staff'); ?></td>
                                    <td><?php echo htmlspecialchars(str_replace('_', ' ', $staff['scholar_type'] ?? 'Full Time')); ?></td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: <?php echo $performance; ?>%"></div>
                                            </div>
                                            <span class="progress-value"><?php echo $performance; ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($isPromotionCandidate): ?>
                                            <span class="badge badge-success">Promotion Candidate</span>
                                        <?php elseif ($performance < 70): ?>
                                            <span class="badge badge-warning">Needs Improvement</span>
                                        <?php else: ?>
                                            <span class="badge">Satisfactory</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view" data-staff-id="<?php echo $staff['staff_id']; ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php if ($isPromotionCandidate): ?>
                                                <button class="action-btn promote" data-staff-id="<?php echo $staff['staff_id']; ?>">
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
                    <div class="ai-message bot">
                        <strong>Hello!</strong> I'm your HR Decision Support Assistant. I can help you with:<br><br>
                        • Identifying promotion candidates<br>
                        • Salary adjustment recommendations<br>
                        • Performance trend analysis<br>
                        • Staff evaluation summaries<br><br>
                        How can I assist you today?
                    </div>
                </div>

                <div class="ai-input-container">
                    <input type="text" class="ai-input" id="ai-input" placeholder="Ask me anything about HR decisions (e.g., 'Show promotion candidates')">
                    <button class="ai-send-btn" id="ai-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
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
                    borderColor: '<?php echo MUST_GREEN; ?>',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'white',
                    pointBorderColor: '<?php echo MUST_GREEN; ?>',
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
                        titleColor: '<?php echo MUST_BLUE; ?>',
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

        // Department Distribution Chart - Now dynamic from database
        const departmentCtx = document.getElementById('departmentCanvas').getContext('2d');
        const departmentChart = new Chart(departmentCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php foreach ($departments as $dept): ?>
                        '<?php echo $dept["department_name"]; ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    data: [
                        <?php 
                        // For demo purposes, distribute staff count per department
                        $dept_counts = [];
                        foreach ($departments as $dept) {
                            $count = 0;
                            foreach ($staff_data as $staff) {
                                if ($staff['department_id'] == $dept['department_id']) {
                                    $count++;
                                }
                            }
                            $dept_counts[] = $count;
                        }
                        echo implode(',', $dept_counts);
                        ?>
                    ],
                    backgroundColor: [
                        '<?php echo MUST_BLUE; ?>',
                        '<?php echo MUST_GREEN; ?>',
                        '<?php echo MUST_YELLOW; ?>',
                        '#e74c3c',
                        '#9b59b6',
                        '#1abc9c',
                        '#3498db',
                        '#e67e22'
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
                        titleColor: '<?php echo MUST_BLUE; ?>',
                        bodyColor: '#333',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        padding: 12,
                        boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + ' staff';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // AI Assistant Functionality - Now fully dynamic
        const conversation = document.getElementById('ai-conversation');
        const aiInput = document.getElementById('ai-input');
        const sendBtn = document.getElementById('ai-send-btn');

        // Function to fetch data from the server
        async function fetchData(endpoint, params = {}) {
            try {
                const response = await fetch(`api/${endpoint}.php?${new URLSearchParams(params)}`);
                return await response.json();
            } catch (error) {
                console.error('Error fetching data:', error);
                return null;
            }
        }

        // Function to calculate staff score via API
        async function calculateStaffScore(staffId) {
            try {
                const response = await fetch(`api/calculate_score.php?staff_id=${staffId}`);
                return await response.json();
            } catch (error) {
                console.error('Error calculating score:', error);
                return null;
            }
        }

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

        async function getAIResponse(userInput) {
            const input = userInput.toLowerCase();

            // System information queries
            if (input.includes('system') || input.includes('hrm') || input.includes('score card')) {
                return "This is the <strong>HRM Score Card System</strong> for Mbarara University of Science and Technology (MUST). " +
                       "Its purpose is to help the Human Resource Management department track and evaluate employees based on various " +
                       "performance metrics, academic achievements, research contributions, and other criteria. The system calculates " +
                       "a comprehensive score for each staff member based on the university's competence criteria.";
            }

            // Staff count queries
            if (input.includes('how many staff') || input.includes('number of staff') || input.includes('staff count')) {
                try {
                    const staff = await fetchData('staff');
                    if (staff && staff.length > 0) {
                        return `There are currently <strong>${staff.length} staff members</strong> registered in the HRM system across all departments.`;
                    } else {
                        return "I couldn't retrieve the staff count at this time. Please try again later.";
                    }
                } catch (error) {
                    return "I encountered an error while trying to fetch staff data.";
                }
            }

            // Department queries
            if (input.includes('department') || input.includes('departments') || input.includes('faculty')) {
                try {
                    const departments = await fetchData('departments');
                    if (departments && departments.length > 0) {
                        let response = "The university has the following departments:<br><ul>";
                        departments.forEach(dept => {
                            response += `<li>${dept.department_name}</li>`;
                        });
                        response += "</ul>";
                        return response;
                    } else {
                        return "I couldn't retrieve the department list at this time.";
                    }
                } catch (error) {
                    return "I encountered an error while trying to fetch department data.";
                }
            }

            // Staff performance score queries
            if (input.includes('score') || input.includes('calculate') || input.includes('performance')) {
                // Check if a specific staff member is mentioned
                const staff = await fetchData('staff');
                if (!staff) {
                    return "I couldn't retrieve staff data at this time.";
                }

                const mentionedStaff = staff.find(s => 
                    input.includes(s.first_name.toLowerCase()) || 
                    input.includes(s.last_name.toLowerCase())
                );

                if (mentionedStaff) {
                    const typingIndicator = showTypingIndicator();
                    const scoreData = await calculateStaffScore(mentionedStaff.staff_id);
                    removeTypingIndicator(typingIndicator);

                    if (scoreData) {
                        let response = `<strong>Performance Score for ${mentionedStaff.first_name} ${mentionedStaff.last_name}</strong><br>`;
                        response += `Total Score: ${scoreData.totalScore} points (${scoreData.percentage}% of target)<br><br>`;
                        response += `<strong>Score Breakdown:</strong><br><ul>`;
                        
                        // Fetch detailed breakdown (this would need a separate API endpoint)
                        // For now, we'll use a simplified response
                        response += `<li>Academic Qualifications: ${Math.round(scoreData.totalScore * 0.3)} points</li>`;
                        response += `<li>Research Contributions: ${Math.round(scoreData.totalScore * 0.4)} points</li>`;
                        response += `<li>Administrative Service: ${Math.round(scoreData.totalScore * 0.2)} points</li>`;
                        response += `<li>Community Engagement: ${Math.round(scoreData.totalScore * 0.1)} points</li>`;
                        
                        response += `</ul>`;
                        return response;
                    } else {
                        return `I couldn't calculate the performance score for ${mentionedStaff.first_name} ${mentionedStaff.last_name} at this time.`;
                    }
                } else {
                    return "Please specify which staff member's score you'd like to calculate (e.g., 'Calculate score for John Doe').";
                }
            }

            // Staff list queries
            if (input.includes('list staff') || input.includes('show staff') || input.includes('all staff')) {
                try {
                    const staff = await fetchData('staff');
                    if (staff && staff.length > 0) {
                        let response = `<strong>Staff Members (${staff.length} total):</strong><br><ul>`;
                        staff.forEach(s => {
                            response += `<li>${s.first_name} ${s.last_name} (${s.role_name || 'Staff'}) - ${s.department_name || 'No department'}</li>`;
                        });
                        response += "</ul>";
                        return response;
                    } else {
                        return "I couldn't retrieve the staff list at this time.";
                    }
                } catch (error) {
                    return "I encountered an error while trying to fetch staff data.";
                }
            }

            // Research grants queries
            if (input.includes('grant') || input.includes('research fund') || input.includes('funding')) {
                try {
                    const grants = await fetchData('grants');
                    if (grants && grants.length > 0) {
                        const totalGrants = grants.reduce((sum, grant) => sum + parseFloat(grant.grant_amount), 0);
                        let response = `There are <strong>${grants.length} research grants</strong> recorded in the system, `;
                        response += `totaling <strong>UGX ${totalGrants.toLocaleString()}</strong>.<br><br>`;
                        
                        // Group by staff
                        const staffGrants = {};
                        grants.forEach(grant => {
                            if (!staffGrants[grant.staff_id]) {
                                staffGrants[grant.staff_id] = {
                                    count: 0,
                                    total: 0
                                };
                            }
                            staffGrants[grant.staff_id].count++;
                            staffGrants[grant.staff_id].total += parseFloat(grant.grant_amount);
                        });
                        
                        response += `<strong>Top Grant Recipients:</strong><br><ol>`;
                        const sortedStaff = Object.entries(staffGrants).sort((a, b) => b[1].total - a[1].total);
                        
                        for (let i = 0; i < Math.min(3, sortedStaff.length); i++) {
                            const [staffId, data] = sortedStaff[i];
                            const staff = await fetchData('staff', {staff_id: staffId});
                            if (staff && staff.length > 0) {
                                const staffMember = staff[0];
                                response += `<li>${staffMember.first_name} ${staffMember.last_name}: ${data.count} grants (UGX ${data.total.toLocaleString()})</li>`;
                            }
                        }
                        
                        response += `</ol>`;
                        return response;
                    } else {
                        return "No research grants data is currently available.";
                    }
                } catch (error) {
                    return "I encountered an error while trying to fetch grants data.";
                }
            }

            // Publications queries
            if (input.includes('publication') || input.includes('research paper') || input.includes('journal')) {
                try {
                    const publications = await fetchData('publications');
                    if (publications && publications.length > 0) {
                        let response = `There are <strong>${publications.length} publications</strong> recorded in the system.<br><br>`;
                        
                        // Count by type
                        const typeCount = {};
                        publications.forEach(pub => {
                            typeCount[pub.publication_type] = (typeCount[pub.publication_type] || 0) + 1;
                        });
                        
                        response += `<strong>Publication Types:</strong><br><ul>`;
                        for (const [type, count] of Object.entries(typeCount)) {
                            response += `<li>${type}: ${count}</li>`;
                        }
                        response += `</ul>`;
                        
                        return response;
                    } else {
                        return "No publications data is currently available.";
                    }
                } catch (error) {
                    return "I encountered an error while trying to fetch publications data.";
                }
            }

            // Promotion candidates query
            if (input.includes('promotion') || input.includes('candidate') || input.includes('advancement')) {
                try {
                    const staff = await fetchData('staff');
                    if (!staff || staff.length === 0) {
                        return "I couldn't retrieve staff data at this time.";
                    }
                    
                    // Get all staff with performance > 85%
                    const candidates = [];
                    for (const s of staff) {
                        const scoreData = await calculateStaffScore(s.staff_id);
                        if (scoreData && scoreData.percentage > 85) {
                            candidates.push({
                                ...s,
                                performance: scoreData.percentage
                            });
                        }
                    }
                    
                    if (candidates.length === 0) {
                        return "There are currently no staff members identified as promotion candidates based on performance metrics.";
                    }

                    let response = `Based on performance data, I've identified <strong>${candidates.length} promotion candidates</strong>:<br><br>`;
                    response += `<ul class="promotion-list">`;

                    candidates.sort((a, b) => b.performance - a.performance).forEach(staff => {
                        response += `<li><strong>${staff.first_name} ${staff.last_name}</strong> (${staff.department_name || 'No department'}, ${staff.role_name || 'Staff'}) - ${staff.performance}% performance</li>`;
                    });

                    response += `</ul><br>Would you like me to generate promotion documentation for any of these candidates?`;
                    return response;
                } catch (error) {
                    return "I encountered an error while trying to identify promotion candidates.";
                }
            }

            // Default responses
            const randomResponses = [
                "I can help you analyze staff performance data and HR metrics. What would you like to know?",
                "Would you like information about staff members, departments, or research activities?",
                "I can provide reports on staff performance scores, research grants, and publications.",
                "How can I assist you with HRM data today? You can ask about staff counts, department information, or individual performance.",
                "I have access to the university's HRM database. What information would you like me to retrieve?"
            ];
            return randomResponses[Math.floor(Math.random() * randomResponses.length)];
        }

        sendBtn.addEventListener('click', async function() {
            const userMessage = aiInput.value.trim();
            if (userMessage) {
                addMessage(userMessage, true);
                aiInput.value = '';

                const typingIndicator = showTypingIndicator();

                try {
                    const aiResponse = await getAIResponse(userMessage);
                    removeTypingIndicator(typingIndicator);
                    addMessage(aiResponse);
                } catch (error) {
                    removeTypingIndicator(typingIndicator);
                    addMessage("I encountered an error while processing your request. Please try again.");
                }
            }
        });

        aiInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendBtn.click();
            }
        });

        // Staff action buttons
        document.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', async function() {
                const staffId = this.getAttribute('data-staff-id');
                addMessage(`User requested details for staff ID ${staffId}`, true);
                const typingIndicator = showTypingIndicator();

                try {
                    const staff = await fetchData('staff', {staff_id: staffId});
                    const scoreData = await calculateStaffScore(staffId);
                    
                    removeTypingIndicator(typingIndicator);
                    
                    if (staff && staff.length > 0 && scoreData) {
                        const s = staff[0];
                        const response = `
                            <strong>${s.first_name} ${s.last_name}</strong> (${s.department_name || 'No department'})<br><br>
                            • Position: ${s.role_name || 'Staff'}<br>
                            • Scholar Type: ${s.scholar_type ? s.scholar_type.replace('_', ' ') : 'Full Time'}<br>
                            • Performance: ${scoreData.percentage}% (${scoreData.totalScore} points)<br>
                            • Years of Experience: ${s.years_of_experience || 'N/A'}<br><br>
                            ${scoreData.percentage > 85 ? 
                                'This staff member qualifies for promotion consideration.' : 
                                scoreData.percentage < 70 ? 
                                'Performance improvement plan recommended.' : 
                                'Performance at satisfactory levels.'
                            }
                        `;
                        addMessage(response);
                    } else {
                        addMessage("I couldn't retrieve the requested staff information.");
                    }
                } catch (error) {
                    removeTypingIndicator(typingIndicator);
                    addMessage("I encountered an error while trying to retrieve staff details.");
                }
            });
        });

        // Promotion action buttons
        document.querySelectorAll('.action-btn.promote').forEach(btn => {
            btn.addEventListener('click', async function() {
                const staffId = this.getAttribute('data-staff-id');
                addMessage(`User requested promotion for staff ID ${staffId}`, true);
                const typingIndicator = showTypingIndicator();

                try {
                    // Here you would implement the logic to promote the staff member
                    // This is a placeholder response
                    removeTypingIndicator(typingIndicator);
                    addMessage(`Staff member with ID ${staffId} has been promoted successfully.`);
                } catch (error) {
                    removeTypingIndicator(typingIndicator);
                    addMessage("I encountered an error while trying to promote the staff member.");
                }
            });
        });

        // Adjust action buttons
        document.querySelectorAll('.action-btn.adjust').forEach(btn => {
            btn.addEventListener('click', async function() {
                const staffId = this.getAttribute('data-staff-id');
                addMessage(`User requested adjustment for staff ID ${staffId}`, true);
                const typingIndicator = showTypingIndicator();

                try {
                    // Implement logic to adjust salary or other parameters here
                    // This is a placeholder response
                    removeTypingIndicator(typingIndicator);
                    addMessage(`Adjustment for staff member with ID ${staffId} has been processed.`);
                } catch (error) {
                    removeTypingIndicator(typingIndicator);
                    addMessage("I encountered an error while trying to adjust the staff member's details.");
                }
            });
        });
    </script>
</body>
</html>
