<?php
session_start();
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']))  {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Database connection
require_once 'head/approve/pdo.php';

// Function to count records in a table
function countRecords($pdo, $table) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
    return $stmt->fetchColumn();
}

// Function to count records with conditions
function countRecordsWhere($pdo, $table, $condition) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM $table WHERE $condition");
    return $stmt->fetchColumn();
}

// Get counts for dashboard metrics
$totalStaff = countRecords($pdo, 'staff');
$phdHolders = countRecordsWhere($pdo, 'degrees', "degree_name LIKE '%PhD%'");
$publicationsCount = countRecords($pdo, 'publications');
$grantsCount = countRecords($pdo, 'grants');
$innovationsCount = countRecords($pdo, 'innovations');
$communityServiceCount = countRecords($pdo, 'community_service_activities');

// Get degree distribution data
$degreeStats = $pdo->query("
    SELECT 
        SUM(CASE WHEN degree_classification = 'First Class' THEN 1 ELSE 0 END) as first_class,
        SUM(CASE WHEN degree_classification = 'Second Class Upper' THEN 1 ELSE 0 END) as second_upper,
        SUM(CASE WHEN degree_classification = 'Second Class Lower' THEN 1 ELSE 0 END) as second_lower,
        SUM(CASE WHEN degree_name LIKE '%PhD%' THEN 1 ELSE 0 END) as phd,
        SUM(CASE WHEN degree_name LIKE '%Master%' THEN 1 ELSE 0 END) as masters
    FROM degrees
")->fetch(PDO::FETCH_ASSOC);

// Get publication types data
$publicationTypes = $pdo->query("
    SELECT 
        publication_type,
        COUNT(*) as count
    FROM publications
    GROUP BY publication_type
")->fetchAll(PDO::FETCH_ASSOC);

// Get grants data by department
$grantsByDept = $pdo->query("
    SELECT 
        d.department_name,
        COUNT(g.grant_id) as grant_count
    FROM grants g
    JOIN staff s ON g.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    GROUP BY d.department_name
")->fetchAll(PDO::FETCH_ASSOC);

// Get supervision data
$supervisionStats = $pdo->query("
    SELECT 
        student_level,
        COUNT(*) as count
    FROM supervision
    GROUP BY student_level
")->fetchAll(PDO::FETCH_ASSOC);

// Get innovation types
$innovationTypes = $pdo->query("
    SELECT 
        innovation_type,
        COUNT(*) as count
    FROM innovations
    GROUP BY innovation_type
")->fetchAll(PDO::FETCH_ASSOC);

// Get community service data by department
$communityServiceByDept = $pdo->query("
    SELECT 
        d.department_name,
        COUNT(cs.activity_id) as service_count
    FROM community_service_activities cs
    JOIN staff s ON cs.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    GROUP BY d.department_name
")->fetchAll(PDO::FETCH_ASSOC);

// Get community service logs for the table
$communityServiceLogs = $pdo->query("
    SELECT 
        cs.activity_id,
        CONCAT(s.first_name, ' ', s.last_name) AS staff_name,
        s.staff_id,
        cs.activity_name,
        cs.location,
        cs.activity_date,
        cs.beneficiaries,
        cs.points_earned,
        cs.verification_status,
        cs.proof_document_path
    FROM community_service_activities cs
    JOIN staff s ON cs.staff_id = s.staff_id
    ORDER BY cs.activity_date DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for charts
$publicationTypesData = [];
foreach ($publicationTypes as $type) {
    $publicationTypesData[$type['publication_type']] = $type['count'];
}

$grantsData = [];
foreach ($grantsByDept as $grant) {
    $grantsData[$grant['department_name']] = $grant['grant_count'];
}

$supervisionData = [
    'PhD' => 0,
    'Masters' => 0
];
foreach ($supervisionStats as $stat) {
    if ($stat['student_level'] === 'PhD') {
        $supervisionData['PhD'] = $stat['count'];
    } else {
        $supervisionData['Masters'] = $stat['count'];
    }
}

$innovationData = [];
foreach ($innovationTypes as $innovation) {
    $innovationData[$innovation['innovation_type']] = $innovation['count'];
}

$communityServiceData = [];
foreach ($communityServiceByDept as $service) {
    $communityServiceData[$service['department_name']] = $service['service_count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST Employee Performance Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --must-blue: rgb(16, 19, 111);
            --must-green: rgb(17, 123, 21);
            --must-yellow: rgb(251, 250, 249);
            --must-light: #f8f9fa;
            --must-light-green: #E5F2E9;
            --must-light-blue: #E6F0FA;
        }

        /* [Previous CSS styles remain unchanged...] */

        /* Community Service Table Styles */
        #communityServiceTable {
            font-size: 0.9rem;
        }

        #communityServiceTable th {
            white-space: nowrap;
        }

        #communityServiceTable .badge {
            font-size: 0.7em;
            margin-left: 5px;
        }

        /* Filter Controls */
        #filterStaff, #filterDate, #filterVerification {
            margin-bottom: 10px;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            #communityServiceTable td:nth-child(1)::before { content: "Staff: "; font-weight: bold; }
            #communityServiceTable td:nth-child(2)::before { content: "Activity: "; font-weight: bold; }
            #communityServiceTable td:nth-child(3)::before { content: "Location: "; font-weight: bold; }
            #communityServiceTable td:nth-child(4)::before { content: "Date: "; font-weight: bold; }
            #communityServiceTable td:nth-child(5)::before { content: "Beneficiaries: "; font-weight: bold; }
            #communityServiceTable td:nth-child(6)::before { content: "Points: "; font-weight: bold; }
            #communityServiceTable td:nth-child(7)::before { content: "Verified?: "; font-weight: bold; }
            
            #communityServiceTable td {
                display: block;
                border-bottom: 1px solid #eee;
            }
            
            #communityServiceTable td:last-child {
                border-bottom: 2px solid #dee2e6;
            }
            
            #communityServiceTable thead {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>
    
    <!-- Dashboard Header -->
    <header class="dashboard-header mt-5" style="width: 80%; margin-left: 20%;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-university me-2"></i>Mbarara University of Science and Technology</h1>
                    <p class="mb-0 fs-5">Employee Performance Tracking Dashboard</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-0"><i class="fas fa-calendar-alt me-1"></i><span id="current-date"></span></p>
                    <button class="btn btn-must-primary btn-sm mt-2"><i class="fas fa-download me-1"></i>Export Report</button>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper mt-5" style="width: 80%; margin-left: 20%;">
        <div class="container">
            <!-- Quick Stats Row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= $totalStaff ?></div>
                            <div class="metric-label">Total Employees</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= $phdHolders ?></div>
                            <div class="metric-label">PhD Holders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= $publicationsCount ?></div>
                            <div class="metric-label">Research Publications</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-value"><?= $grantsCount ?></div>
                            <div class="metric-label">Active Grants</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- [Previous sections remain unchanged...] -->

            <!-- Community Service Section -->
            <div class="card section-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title"><i class="fas fa-hands-helping me-2 text-must-blue"></i>Community Service</h2>
                        <div>
                            <button class="btn btn-must-primary me-2" id="exportCommunityService">
                                <i class="fas fa-file-csv me-1"></i>Export to CSV
                            </button>
                            <button class="btn btn-must-primary" data-bs-toggle="modal" data-bs-target="#addCommunityServiceModal">
                                <i class="fas fa-plus me-1"></i>Add Activity
                            </button>
                        </div>
                    </div>

                    <!-- Community Service Log Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="communityServiceTable">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Activity</th>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>Beneficiaries</th>
                                    <th>Points</th>
                                    <th>Verified?</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($communityServiceLogs as $log): ?>
                                <tr>
                                    <td>
                                        <?= $log['staff_name'] ?> 
                                        <span class="badge bg-primary"><?= $log['staff_id'] ?></span>
                                        <?= $log['verification_status'] === 'verified' ? '<i class="fas fa-check-circle text-success"></i>' : 
                                           ($log['verification_status'] === 'pending' ? '<i class="fas fa-clock text-warning"></i>' : 
                                           '<i class="fas fa-times-circle text-danger"></i>') ?>
                                    </td>
                                    <td><?= $log['activity_name'] ?></td>
                                    <td><?= $log['location'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($log['activity_date'])) ?></td>
                                    <td><?= $log['beneficiaries'] ?? 'N/A' ?></td>
                                    <td><?= $log['points_earned'] ?></td>
                                    <td>
                                        <?php if ($log['verification_status'] === 'verified'): ?>
                                            <span class="badge bg-success">✅ Verified</span>
                                        <?php elseif ($log['verification_status'] === 'pending'): ?>
                                            <span class="badge bg-warning text-dark">⚠️ Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">❌ Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($log['proof_document_path']): ?>
                                            <a href="<?= $log['proof_document_path'] ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-secondary edit-activity" data-id="<?= $log['activity_id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Filter Row -->
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <select class="form-select" id="filterStaff">
                                <option value="">Filter by Staff</option>
                                <?php 
                                $staffList = $pdo->query("SELECT staff_id, CONCAT(first_name, ' ', last_name) AS name FROM staff ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($staffList as $staff): 
                                ?>
                                <option value="<?= $staff['staff_id'] ?>"><?= $staff['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterDate">
                                <option value="">Filter by Date</option>
                                <option value="this_year">This Year</option>
                                <option value="last_year">Last Year</option>
                                <option value="older">Older</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterVerification">
                                <option value="">Filter by Verification</option>
                                <option value="verified">Verified</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary w-100" id="resetFilters">
                                <i class="fas fa-sync-alt me-1"></i>Reset Filters
                            </button>
                        </div>
                    </div>

                    <!-- Community Service Metrics -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-value"><?= $communityServiceCount ?></div>
                                            <div class="metric-label">Employees Engaged</div>
                                        </div>
                                        <i class="fas fa-users fa-3x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="metric-value"><?= $communityServiceCount ?></div>
                                            <div class="metric-label">Community Projects</div>
                                        </div>
                                        <i class="fas fa-project-diagram fa-3x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card metric-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php 
                                            $totalBeneficiaries = array_sum(array_column($communityServiceLogs, 'beneficiaries'));
                                            ?>
                                            <div class="metric-value"><?= $totalBeneficiaries ?></div>
                                            <div class="metric-label">Total Beneficiaries</div>
                                        </div>
                                        <i class="fas fa-users fa-3x text-muted opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Community Service Modal -->
    <div class="modal fade" id="addCommunityServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-must-blue text-white">
                    <h5 class="modal-title">Add Community Service Activity</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="communityServiceForm" enctype="multipart/form-data">
                        <input type="hidden" name="activity_id" id="activity_id">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="staff_id" class="form-label">Staff Member</label>
                                <select class="form-select" name="staff_id" id="staff_id" required>
                                    <option value="">Select Staff</option>
                                    <?php foreach ($staffList as $staff): ?>
                                    <option value="<?= $staff['staff_id'] ?>"><?= $staff['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="activity_date" class="form-label">Activity Date</label>
                                <input type="date" class="form-control" name="activity_date" id="activity_date" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="activity_name" class="form-label">Activity Name</label>
                                <input type="text" class="form-control" name="activity_name" id="activity_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" id="location" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="beneficiaries" class="form-label">Number of Beneficiaries</label>
                                <input type="number" class="form-control" name="beneficiaries" id="beneficiaries">
                            </div>
                            <div class="col-md-6">
                                <label for="proof_document" class="form-label">Proof Document</label>
                                <input type="file" class="form-control" name="proof_document" id="proof_document" accept=".pdf,.jpg,.png">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-must-primary" id="saveCommunityService">Save Activity</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <img src="https://via.placeholder.com/150x50?text=MUST+Logo" alt="MUST Logo" class="mb-3">
            <p class="mb-1">© 2023 Mbarara University of Science and Technology</p>
            <p class="mb-0">Human Resource Management System | <small>Data updated hourly | For official use only</small></p>
        </div>
    </footer>

    <!-- Floating Action Button -->
    <div class="floating-action-btn" data-bs-toggle="tooltip" data-bs-placement="left" title="Quick Actions">
        <i class="fas fa-bolt"></i>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // [Previous chart initializations remain unchanged...]

        // Community Service Table Functionality
        $(document).ready(function() {
            // Initialize DataTable
            $('#communityServiceTable').DataTable({
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                pageLength: 10,
                responsive: true
            });

            // Filter functionality
            $('#filterStaff, #filterDate, #filterVerification').change(function() {
                const staff = $('#filterStaff').val();
                const date = $('#filterDate').val();
                const verification = $('#filterVerification').val();
                
                const table = $('#communityServiceTable').DataTable();
                
                table.column(0).search(staff, false, false);
                
                if (date === 'this_year') {
                    const currentYear = new Date().getFullYear();
                    table.column(3).search(currentYear.toString(), true, false);
                } else if (date === 'last_year') {
                    const lastYear = new Date().getFullYear() - 1;
                    table.column(3).search(lastYear.toString(), true, false);
                } else if (date === 'older') {
                    const currentYear = new Date().getFullYear();
                    const lastYear = currentYear - 1;
                    table.column(3).search('^(?!.*' + currentYear + ')(?!.*' + lastYear + ')', true, false);
                } else {
                    table.column(3).search('');
                }
                
                table.column(6).search(verification, false, false);
                
                table.draw();
            });

            // Reset filters
            $('#resetFilters').click(function() {
                $('#filterStaff, #filterDate, #filterVerification').val('');
                $('#communityServiceTable').DataTable().search('').columns().search('').draw();
            });

            // Export to CSV
            $('#exportCommunityService').click(function() {
                const table = $('#communityServiceTable').DataTable();
                const data = table.rows({ search: 'applied' }).data();
                let csvContent = "data:text/csv;charset=utf-8,";
                
                // Add headers
                csvContent += "Staff,Activity,Location,Date,Beneficiaries,Points,Verified?\r\n";
                
                // Add data
                data.each(function(row) {
                    csvContent += `"${row[0]}","${row[1]}","${row[2]}","${row[3]}","${row[4]}","${row[5]}","${row[6]}"\r\n`;
                });
                
                // Download
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "community_service_activities.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            // Save community service activity
            $('#saveCommunityService').click(function() {
                const formData = new FormData($('#communityServiceForm')[0]);
                
                $.ajax({
                    url: 'save_community_service.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            alert('Activity saved successfully!');
                            $('#addCommunityServiceModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Error: ' + result.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while saving the activity.');
                    }
                });
            });

            // Edit activity
            $('.edit-activity').click(function() {
                const activityId = $(this).data('id');
                
                $.ajax({
                    url: 'get_community_service.php',
                    type: 'GET',
                    data: { id: activityId },
                    success: function(response) {
                        const activity = JSON.parse(response);
                        $('#activity_id').val(activity.activity_id);
                        $('#staff_id').val(activity.staff_id);
                        $('#activity_date').val(activity.activity_date);
                        $('#activity_name').val(activity.activity_name);
                        $('#location').val(activity.location);
                        $('#beneficiaries').val(activity.beneficiaries);
                        $('#notes').val(activity.notes);
                        
                        $('#addCommunityServiceModal').modal('show');
                    },
                    error: function() {
                        alert('An error occurred while fetching activity details.');
                    }
                });
            });
        });
    </script>
</body>
</html>