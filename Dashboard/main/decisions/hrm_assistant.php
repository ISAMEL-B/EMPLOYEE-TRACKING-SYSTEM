<?php include 'hrm_assist_process.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM Decision Support System</title>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> -->
    <!-- local files -->
    <link rel="stylesheet" href="../../components/src/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="css/chatbot.css">
    <style>

    </style>
</head>

<body>
    <div class="dashboard-container">

        <!-- Nav Bar -->
        <?php include '../bars/nav_bar.php'; ?>

        <!-- Sidebar -->
        <?php //include 'resource/sidebar.php'; 
        ?>
        <?php include '../bars/side_bar.php'; ?>

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
                        <div class="card-value"><?php echo count($staff_list); ?></div>
                        <p class="card-description">Active employees</p>
                        <div class="card-footer">
                            <i class="fas fa-arrow-up"></i> Annual Performance
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
                            <i class="fas fa-exclamation-circle"></i> Waiting for Approval
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
                            <?php 
                            $staff_id_counter = 1;
                            foreach ($top_performing_staff as $staff):
                                $isPromotionCandidate = $staff['performance_score'] >= 80 && $staff['years_of_experience'] >= 3;
                            ?>
                                <tr>
                                    <td><?php echo $staff_id_counter++; ?></td>
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
                        <p class="ai-subtitle">Provides data-driven recommendations</p>
                    </div>
                    <button class="clear-chat-btn" onclick="clearChat()">
                        <i class="fas fa-trash-alt"></i> Clear Chat
                    </button>
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
                    <?php if (!empty($response)): ?>
                        <?php echo $response; ?>
                    <?php endif; ?>
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <!-- Chart.js -->
    <script src="../../components/Chart.js/dist/Chart.min.js"></script>
    <?php include 'js/chatbot.php'; ?>
</body>

</html>