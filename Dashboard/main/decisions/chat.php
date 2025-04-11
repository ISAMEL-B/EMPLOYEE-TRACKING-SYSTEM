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
if ($_SESSION['user_role'] === 'hrm') {
    $staff_query = "SELECT * FROM users";
    $performance_query = "SELECT * FROM performance_metrics"; // Assuming this table exists
    $staff_data = $conn->query($staff_query)->fetch_all(MYSQLI_ASSOC);
    $performance_data = $conn->query($performance_query)->fetch_all(MYSQLI_ASSOC);

    // Generate promotion candidates data
    $promotion_candidates = array_filter($staff_data, function ($staff) {
        return rand(0, 100) > 70; // 30% chance to be promotion candidate for demo
    });
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
        <?php include 'resource/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>HR Decision Support Dashboard</h1>
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
                        <div class="card-value"><?php echo count($staff_data); ?></div>
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
                        <div class="card-value"><?php echo count($promotion_candidates ?? []); ?></div>
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
                        <div class="card-value">8</div>
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
                        <div class="card-value">84%</div>
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
                            <?php
                            // echo '<pre>';
                            // print_r($staff_data);
                            // echo '</pre>';

                            foreach ($staff_data as $staff):
                                $performance = rand(60, 95);
                                $isPromotionCandidate = in_array($staff['user_id'], array_column($promotion_candidates ?? [], 'user_id'));

                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($staff['employee_id'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['department'] ?? 'General'); ?></td>
                                    <td><?php echo htmlspecialchars($staff['position'] ?? 'Staff'); ?></td>
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
                                            <button class="action-btn view" data-user-id="<?php echo $staff['user_id']; ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php if ($isPromotionCandidate): ?>
                                                <button class="action-btn promote" data-user-id="<?php echo $staff['user_id']; ?>">
                                                    <i class="fas fa-arrow-up"></i> Promote
                                                </button>
                                            <?php endif; ?>
                                            <button class="action-btn adjust" data-staff-id="<?php echo $staff['user_id']; ?>">
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
        const aiInput = document.getElementById('ai-input');
        const sendBtn = document.getElementById('ai-send-btn');

        // Sample staff data for the AI to reference - USING user_id
        const staffData = [
            <?php foreach ($staff_data as $staff):
                $performance = rand(60, 95);
                $isPromotionCandidate = in_array($staff['user_id'], array_column($promotion_candidates ?? [], 'user_id'));
            ?> {
                    user_id: '<?php echo $staff['user_id']; ?>',
                    first_name: '<?php echo $staff['first_name']; ?>',
                    last_name: '<?php echo $staff['last_name']; ?>',
                    name: '<?php echo $staff['first_name'] . ' ' . $staff['last_name']; ?>',
                    department: '<?php echo $staff['department'] ?? 'General'; ?>',
                    position: '<?php echo $staff['position'] ?? 'Staff'; ?>',
                    performance: <?php echo $performance; ?>,
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
                    response += `<li><strong>${staff.name}</strong> (${staff.department}, ${staff.position}) - ${staff.performance}% performance, last evaluated ${staff.lastEvaluation}</li>`;
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
                response += `• University-wide average: 84%<br>`;
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
                input.includes(staff.last_name.toLowerCase())
            );

            if (mentionedStaff) {
                let response = `<strong>Staff Profile: ${mentionedStaff.name}</strong><br><br>`;
                response += `• Department: ${mentionedStaff.department}<br>`;
                response += `• Position: ${mentionedStaff.position}<br>`;
                response += `• Current Performance: ${mentionedStaff.performance}%<br>`;
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

        sendBtn.addEventListener('click', function() {
            const userMessage = aiInput.value.trim();
            if (userMessage) {
                addMessage(userMessage, true);
                aiInput.value = '';

                const typingIndicator = showTypingIndicator();

                setTimeout(() => {
                    removeTypingIndicator(typingIndicator);
                    const aiResponse = getAIResponse(userMessage);
                    addMessage(aiResponse);
                }, 1500);
            }
        });

        aiInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendBtn.click();
            }
        });

        // Staff action buttons - UPDATED TO USE data-user-id
        document.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const staff = staffData.find(s => s.user_id === userId);

                if (staff) {
                    addMessage(`User requested details for ${staff.name}`, true);
                    const typingIndicator = showTypingIndicator();

                    setTimeout(() => {
                        removeTypingIndicator(typingIndicator);
                        const response = `
                        <strong>${staff.name}</strong> (${staff.department})<br><br>
                        • Position: ${staff.position}<br>
                        • Performance: ${staff.performance}%<br>
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
                const userId = this.getAttribute('data-user-id');
                const staff = staffData.find(s => s.user_id === userId);

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
                const userId = this.getAttribute('data-user-id');
                const staff = staffData.find(s => s.user_id === userId);

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