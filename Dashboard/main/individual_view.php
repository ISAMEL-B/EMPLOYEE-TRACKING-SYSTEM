<?php  
    include 'processes/individual_view_process.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Achievement Explorer - MUST HRM</title>
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
         /* New styles for the profile header */
         .profile-header {
            display: flex;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3498db;
            margin-right: 20px;
        }
        
        .profile-info h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .profile-info p {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .score-card {
            margin-left: auto;
            text-align: center;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
        }
        
        .score-card .score {
            font-size: 2.5rem;
            font-weight: bold;
        }
        
        .score-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Overview cards */
        .overview-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .overview-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        
        .overview-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #3498db;
        }
        
        .overview-card h3 {
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .overview-card p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        /* Publications chart */
        .publications-chart-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Existing card styles */
        .badge-must {
            background-color: #2e3192;
            color: white;
        }
        
        .timeline {
            position: relative;
            padding-left: 20px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e9ecef;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 15px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 10px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #2e3192;
            border: 2px solid white;
        }
        
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-pic {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .score-card {
                margin: 15px 0 0 0;
            }
        }
    </style>
    <link rel="stylesheet" href="styles/individual_style.css">
</head>

<body>
    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- sidebar -->
    <?php include 'bars/side_bar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Header -->
        <div class="explorer-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold">Staff Achievement Explorer</h1>
                        <p class="lead">Discover and analyze faculty accomplishments across MUST</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <i class="fas fa-trophy fa-4x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="container mb-5">
            <div class="card search-card">
                <div class="card-body">
                    <form method="POST" class="row g-3 align-items-end">
                        <div class="col-md-9">
                            <label for="staffSelect" class="form-label">Select Staff Member</label>
                            <select class="form-select select2" id="staffSelect" name="staff_id" required>
                                <option value="">-- Search for staff --</option>
                                <?php foreach ($staff_list as $staff): ?>
                                    <option value="<?= $staff['staff_id'] ?>"
                                        <?= ($staff_details && $staff['staff_id'] == $staff_details['staff_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($staff['last_name']) ?>, <?= htmlspecialchars($staff['first_name']) ?>
                                        (<?= htmlspecialchars($staff['department_name']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn search-btn w-100 py-2">
                                <i class="fas fa-search me-2"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($staff_details): ?>
            <!-- Results Section for Selected Staff -->
            <div class="container">
                <!-- Profile Header -->
                <div class="profile-header">
                    <?php if (!empty($staff_details['photo_path'])): ?>
                        <img src="<?= htmlspecialchars($staff_details['photo_path']) ?>" alt="Profile Picture" class="profile-pic">
                    <?php else: ?>
                        <div class="profile-pic" style="background-color: #eee; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="profile-info">
                        <h1><?= htmlspecialchars($staff_details['first_name']) ?> <?= htmlspecialchars($staff_details['last_name']) ?></h1>
                        <p><?= htmlspecialchars($staff_details['role_name']) ?></p>
                        <p><?= htmlspecialchars($staff_details['department_name']) ?></p>
                        <p>Years at current rank: <?= htmlspecialchars($staff_details['years_of_experience'] ?? '0') ?> • Years of service: <?= htmlspecialchars($staff_details['years_of_service'] ?? '0') ?></p>
                    </div>
                    <div class="score-card">
                        <div class="score"><?= htmlspecialchars($staff_details['performance_score'] ?? '0') ?></div>
                        <div class="label">Total Score</div>
                    </div>
                </div>

                <!-- Overview Cards -->
                <div class="overview-cards">
                    <div class="overview-card">
                        <i class="fas fa-book-open"></i>
                        <h3><?= count($achievements['publications']) ?></h3>
                        <p>Publications</p>
                    </div>
                    <div class="overview-card">
                        <i class="fas fa-graduation-cap"></i>
                        <h3><?= count($achievements['degrees']) ?></h3>
                        <p>Qualifications</p>
                    </div>
                    <div class="overview-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <h3><?= count($achievements['grants']) ?></h3>
                        <p>Research Grants</p>
                    </div>
                    <div class="overview-card">
                        <i class="fas fa-user-graduate"></i>
                        <h3><?= count($achievements['supervisions']) ?></h3>
                        <p>Supervisions</p>
                    </div>
                </div>

                <!-- Publications vs Citations Chart -->
                <div class="publications-chart-container">
                    <div class="card-header">
                        <h2>Publications vs Citations</h2>
                        <span class="badge badge-must"><?= count($achievements['publications']) ?> publications</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="publicationsChart"></canvas>
                    </div>
                </div>

                <!-- Detailed Achievements -->
                <div class="row">
                    <!-- Publications -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Publications</h5>
                                <span class="badge badge-must"><?= count($achievements['publications']) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($achievements['publications'])): ?>
                                    <div class="timeline">
                                        <?php foreach ($achievements['publications'] as $pub): ?>
                                            <div class="timeline-item mb-3">
                                                <h6 class="mb-1"><?= htmlspecialchars($pub['publication_type']) ?></h6>
                                                <p class="text-muted small mb-1">Role: <?= htmlspecialchars($pub['role']) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No publications recorded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Degrees -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Academic Qualifications</h5>
                                <span class="badge badge-must"><?= count($achievements['degrees']) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($achievements['degrees'])): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($achievements['degrees'] as $degree): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlspecialchars($degree['degree_name']) ?></strong>
                                                <span class="badge bg-info float-end"><?= htmlspecialchars($degree['degree_classification']) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No degrees recorded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Grants -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Research Grants</h5>
                                <span class="badge badge-must"><?= count($achievements['grants']) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($achievements['grants'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Amount</th>
                                                    <th>Year Awarded</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($achievements['grants'] as $grant): ?>
                                                    <tr>
                                                        <td>UGX <?= number_format($grant['grant_amount'], 2) ?></td>
                                                        <td>
                                                            <?php if (!empty($grant['year_awarded'])): ?>
                                                                <?= htmlspecialchars($grant['year_awarded']) ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No grants recorded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Supervisions -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Student Supervisions</h5>
                                <span class="badge badge-must"><?= count($achievements['supervisions']) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($achievements['supervisions'])): ?>
                                    <?php
                                    // Count PhD and Masters supervisions
                                    $phdCount = 0;
                                    $mastersCount = 0;
                                    
                                    foreach ($achievements['supervisions'] as $supervision) {
                                        $level = strtolower($supervision['student_level']);
                                        if (strpos($level, 'phd') !== false) {
                                            $phdCount++;
                                        } elseif (strpos($level, 'master') !== false || strpos($level, 'msc') !== false) {
                                            $mastersCount++;
                                        }
                                    }
                                    ?>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            PhD Supervisions
                                            <span class="text-muted"><?= $phdCount ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Masters Supervisions
                                            <span class="text-muted"><?= $mastersCount ?></span>
                                        </li>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No supervisions recorded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Innovations -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Innovations</h5>
                                <span class="badge badge-must"><?= count($achievements['innovations']) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($achievements['innovations'])): ?>
                                    <?php
                                    // Count different innovation types
                                    $innovationCounts = [];
                                    foreach ($achievements['innovations'] as $innovation) {
                                        $type = $innovation['innovation_type'];
                                        if (!isset($innovationCounts[$type])) {
                                            $innovationCounts[$type] = 0;
                                        }
                                        $innovationCounts[$type]++;
                                    }
                                    ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($innovationCounts as $type => $count): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?= htmlspecialchars($type) ?>
                                                <span class="text-muted"><?= $count ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">No innovations recorded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Activities & Services -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Activities & Services</h5>
                                <span class="badge badge-must"><?= count($achievements['activities']) + count($achievements['services']) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($achievements['activities']) || !empty($achievements['services'])): ?>
                                    <h6>Academic Activities</h6>
                                    <?php if (!empty($achievements['activities'])): ?>
                                        <ul class="list-group list-group-flush mb-3">
                                            <?php foreach ($achievements['activities'] as $activity): ?>
                                                <li class="list-group-item">
                                                    <?= htmlspecialchars($activity['activity_type']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted small">No academic activities recorded</p>
                                    <?php endif; ?>

                                    <h6>Administrative Services</h6>
                                    <?php if (!empty($achievements['services'])): ?>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($achievements['services'] as $service): ?>
                                                <li class="list-group-item">
                                                    <?= htmlspecialchars($service['service_type']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted small">No administrative services recorded</p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="text-muted">No activities or services recorded</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Default View - Top Performing Staff -->
            <div class="container">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Top Performing Staff</h4>
                        <p class="text-muted mb-0">Ranked by performance score</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Staff Member</th>
                                        <th>Department</th>
                                        <th>Performance Score</th>
                                        <th>Publications</th>
                                        <th>Grants</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_performing_staff as $index => $staff): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <?= htmlspecialchars($staff['last_name']) ?>, <?= htmlspecialchars($staff['first_name']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($staff['department_name']) ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success"
                                                        role="progressbar"
                                                        style="width: <?= min(100, $staff['performance_score']) ?>%"
                                                        aria-valuenow="<?= $staff['performance_score'] ?>"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <?= round($staff['performance_score'], 1) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $staff['publication_count'] ?? 0 ?></td>
                                            <td><?= $staff['grant_count'] ?? 0 ?></td>
                                            <td>
                                                <a href="?staff_id=<?= $staff['staff_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Department-wise Performance -->
                <!-- <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Department Performance Comparison</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 400px;">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div> -->
            </div>
        <?php endif; ?>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Search for staff member",
                allowClear: true
            });

            // If a staff is selected via URL, ensure Select2 shows the correct value
            <?php if (isset($staff_id) && $staff_id): ?>
                $('.select2').val(<?= $staff_id ?>).trigger('change');
            <?php endif; ?>

            <?php if ($staff_details): ?>
                // Initialize Publications vs Citations Chart
                const pubCtx = document.getElementById('publicationsChart').getContext('2d');
                new Chart(pubCtx, {
                    type: 'bar',
                    data: {
                        labels: ['2019', '2020', '2021', '2022', '2023'],
                        datasets: [
                            {
                                label: 'Publications',
                                data: [3, 5, 7, 6, 8],
                                backgroundColor: '#3498db',
                                borderColor: '#2980b9',
                                borderWidth: 1
                            },
                            {
                                label: 'Citations',
                                data: [15, 22, 30, 28, 35],
                                backgroundColor: '#2ecc71',
                                borderColor: '#27ae60',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Publications vs Citations (Last 5 Years)',
                                font: {
                                    size: 16
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });
            <?php else: ?>
                // Initialize Department Performance Chart for default view
                const deptCtx = document.getElementById('departmentChart').getContext('2d');
                const departmentChart = new Chart(deptCtx, {
                    type: 'bar',
                    data: {
                        labels: [<?= implode(',', array_map(function ($dept) {
                                        return "'" . htmlspecialchars($dept['department_name']) . "'";
                                    }, $department_stats)) ?>],
                        datasets: [{
                            label: 'Average Performance Score',
                            data: [<?= implode(',', array_map(function ($dept) {
                                        return $dept['avg_score'];
                                    }, $department_stats)) ?>],
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
                                text: 'Department Performance Comparison',
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
            <?php endif; ?>
        });
    </script>
</body>
</html>