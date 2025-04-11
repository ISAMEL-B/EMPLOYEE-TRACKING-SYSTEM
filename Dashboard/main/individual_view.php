<?php
session_start();
require_once 'approve/config.php'; // Database connection

// Fetch all staff for dropdown
$staff_query = $conn->query("SELECT s.staff_id, s.first_name, s.last_name, d.department_name
                           FROM staff s
                           JOIN departments d ON s.department_id = d.department_id
                           
                           ORDER BY s.last_name, s.first_name");
$staff_list = $staff_query->fetch_all(MYSQLI_ASSOC);

// Initialize variables
$selected_staff = null;
$staff_details = [];
$achievements = [
    'publications' => [],
    'degrees' => [],
    'grants' => [],
    'supervisions' => [],
    'innovations' => [],
    'activities' => [],
    'services' => []
];

// Handle staff selection/search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'] ?? null;

    if ($staff_id) {
        // Get staff details
        $stmt = $conn->prepare("SELECT s.*, d.department_name, r.role_name 
                              FROM staff s
                              JOIN departments d ON s.department_id = d.department_id
                              JOIN roles r ON s.role_id = r.role_id
                              WHERE s.staff_id = ?");
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $staff_details = $stmt->get_result()->fetch_assoc();

        // Get all achievements
        if ($staff_details) {
            // Publications
            $pub_query = $conn->prepare("SELECT * FROM publications WHERE staff_id = ?");
            $pub_query->bind_param("i", $staff_id);
            $pub_query->execute();
            $achievements['publications'] = $pub_query->get_result()->fetch_all(MYSQLI_ASSOC);

            // Degrees
            $deg_query = $conn->prepare("SELECT * FROM degrees WHERE staff_id = ?");
            $deg_query->bind_param("i", $staff_id);
            $deg_query->execute();
            $achievements['degrees'] = $deg_query->get_result()->fetch_all(MYSQLI_ASSOC);

            // Grants
            $grant_query = $conn->prepare("SELECT * FROM grants WHERE staff_id = ?");
            $grant_query->bind_param("i", $staff_id);
            $grant_query->execute();
            $achievements['grants'] = $grant_query->get_result()->fetch_all(MYSQLI_ASSOC);

            // Supervisions
            $sup_query = $conn->prepare("SELECT * FROM supervision WHERE staff_id = ?");
            $sup_query->bind_param("i", $staff_id);
            $sup_query->execute();
            $achievements['supervisions'] = $sup_query->get_result()->fetch_all(MYSQLI_ASSOC);

            // Innovations
            $inn_query = $conn->prepare("SELECT * FROM innovations WHERE staff_id = ?");
            $inn_query->bind_param("i", $staff_id);
            $inn_query->execute();
            $achievements['innovations'] = $inn_query->get_result()->fetch_all(MYSQLI_ASSOC);

            // Academic Activities
            $act_query = $conn->prepare("SELECT * FROM academicactivities WHERE staff_id = ?");
            $act_query->bind_param("i", $staff_id);
            $act_query->execute();
            $achievements['activities'] = $act_query->get_result()->fetch_all(MYSQLI_ASSOC);

            // Services
            $serv_query = $conn->prepare("SELECT * FROM service WHERE staff_id = ?");
            $serv_query->bind_param("i", $staff_id);
            $serv_query->execute();
            $achievements['services'] = $serv_query->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    }
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Achievement Explorer - MUST HRM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles/individual_style.css">
</head>

<body>

    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!--  sidebar -->
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
            <!-- Results Section -->
            <div class="container">
                <!-- Profile Summary -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card profile-card h-100">
                            <div class="card-body text-center">
                                <div class="profile-img-container mx-auto mb-3">
                                    <?php if (!empty($staff_details['photo_path'])): ?>
                                        <img src="<?= htmlspecialchars($staff_details['photo_path']) ?>" class="profile-img" alt="Profile Photo">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-user fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h3 class="h4"><?= htmlspecialchars($staff_details['first_name']) ?> <?= htmlspecialchars($staff_details['last_name']) ?></h3>
                                <h5 class="text-muted mb-3"><?= htmlspecialchars($staff_details['role_name']) ?></h5>
                                <p class="mb-1"><i class="fas fa-building text-muted me-2"></i> <?= htmlspecialchars($staff_details['department_name']) ?></p>
                                <p class="mb-1"><i class="fas fa-star text-muted me-2"></i> Performance Score: <?= htmlspecialchars($staff_details['performance_score'] ?? 'N/A') ?></p>
                                <p class="mb-0"><i class="fas fa-clock text-muted me-2"></i> <?= htmlspecialchars($staff_details['years_of_experience'] ?? '0') ?> years experience</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Achievement Overview</h4>

                                <!-- Achievement Stats -->
                                <div class="row text-center">
                                    <div class="col-6 col-md-3 mb-4">
                                        <div class="achievement-card p-3">
                                            <i class="fas fa-book-open achievement-icon mb-2"></i>
                                            <h3 class="mb-0"><?= count($achievements['publications']) ?></h3>
                                            <small class="text-muted">Publications</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-4">
                                        <div class="achievement-card p-3">
                                            <i class="fas fa-graduation-cap achievement-icon mb-2"></i>
                                            <h3 class="mb-0"><?= count($achievements['degrees']) ?></h3>
                                            <small class="text-muted">Degrees</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-4">
                                        <div class="achievement-card p-3">
                                            <i class="fas fa-money-bill-wave achievement-icon mb-2"></i>
                                            <h3 class="mb-0"><?= count($achievements['grants']) ?></h3>
                                            <small class="text-muted">Grants</small>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-4">
                                        <div class="achievement-card p-3">
                                            <i class="fas fa-user-graduate achievement-icon mb-2"></i>
                                            <h3 class="mb-0"><?= count($achievements['supervisions']) ?></h3>
                                            <small class="text-muted">Supervisions</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Performance Chart -->
                                <div class="chart-container mt-4">
                                    <canvas id="performanceChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($achievements['grants'] as $grant): ?>
                                                    <tr>
                                                        <td>UGX <?= number_format($grant['grant_amount'], 2) ?></td>
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
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($achievements['supervisions'] as $supervision): ?>
                                            <li class="list-group-item">
                                                <?= htmlspecialchars($supervision['student_level']) ?> supervision
                                            </li>
                                        <?php endforeach; ?>
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
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($achievements['innovations'] as $innovation): ?>
                                            <li class="list-group-item">
                                                <?= htmlspecialchars($innovation['innovation_type']) ?>
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

            <?php if ($staff_details): ?>
                // Initialize Performance Chart
                const ctx = document.getElementById('performanceChart').getContext('2d');
                const performanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Publications', 'Degrees', 'Grants', 'Supervisions', 'Innovations', 'Activities'],
                        datasets: [{
                            label: 'Achievement Count',
                            data: [
                                <?= count($achievements['publications']) ?>,
                                <?= count($achievements['degrees']) ?>,
                                <?= count($achievements['grants']) ?>,
                                <?= count($achievements['supervisions']) ?>,
                                <?= count($achievements['innovations']) ?>,
                                <?= count($achievements['activities']) ?>
                            ],
                            backgroundColor: [
                                '#4CAF50',
                                '#2e3192',
                                '#FFEB3B',
                                '#4CAF50',
                                '#2e3192',
                                '#FFEB3B'
                            ],
                            borderColor: [
                                '#388E3C',
                                '#1A237E',
                                '#FBC02D',
                                '#388E3C',
                                '#1A237E',
                                '#FBC02D'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Achievement Distribution',
                                font: {
                                    size: 16
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
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