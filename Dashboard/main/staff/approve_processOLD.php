<?php
session_start();
// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['staff_id'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit;
}

// Database connection
include '../head/approve/config.php';

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Fetch all staff for the dropdown
$staff_query = "SELECT staff_id, first_name, last_name FROM staff ORDER BY first_name";
$staff_result = $conn->query($staff_query);
$staff_members = [];
while ($row = $staff_result->fetch_assoc()) {
    $staff_members[$row['staff_id']] = $row['first_name'] . ' ' . $row['last_name'];
}

// Get selected staff ID from form
$selected_staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : null;

// Initialize data arrays
$staff_data = [];
$degrees_data = [];
$publications_data = [];
$grants_data = [];
$innovations_data = [];
$community_service_data = [];
$supervision_data = [];

if ($selected_staff_id) {
    // Fetch staff basic info
    $staff_query = "SELECT * FROM staff WHERE staff_id = $selected_staff_id";
    $staff_result = $conn->query($staff_query);
    $staff_data = $staff_result->fetch_assoc();

    // Fetch degrees
    $degrees_query = "SELECT * FROM degrees WHERE staff_id = $selected_staff_id";
    $degrees_result = $conn->query($degrees_query);
    while ($row = $degrees_result->fetch_assoc()) {
        $degrees_data[] = $row;
    }

    // Fetch publications
    $publications_query = "SELECT * FROM publications WHERE staff_id = $selected_staff_id";
    $publications_result = $conn->query($publications_query);
    while ($row = $publications_result->fetch_assoc()) {
        $publications_data[] = $row;
    }

    // Fetch grants
    $grants_query = "SELECT * FROM grants WHERE staff_id = $selected_staff_id";
    $grants_result = $conn->query($grants_query);
    while ($row = $grants_result->fetch_assoc()) {
        $grants_data[] = $row;
    }

    // Fetch innovations
    $innovations_query = "SELECT * FROM innovations WHERE staff_id = $selected_staff_id";
    $innovations_result = $conn->query($innovations_query);
    while ($row = $innovations_result->fetch_assoc()) {
        $innovations_data[] = $row;
    }

    // Fetch community service
    $community_service_query = "SELECT * FROM communityservice WHERE staff_id = $selected_staff_id";
    $community_service_result = $conn->query($community_service_query);
    while ($row = $community_service_result->fetch_assoc()) {
        $community_service_data[] = $row;
    }

    // Fetch supervision
    $supervision_query = "SELECT * FROM supervision WHERE staff_id = $selected_staff_id";
    $supervision_result = $conn->query($supervision_query);
    while ($row = $supervision_result->fetch_assoc()) {
        $supervision_data[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Staff Data</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../../components/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../../components/src/fontawesome/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../../../components/datatables/datatables.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            /* MUST Color Scheme */
            --must-green: #006633;
            --must-yellow: #FFCC00;
            --must-blue: #003366;
            --must-light-green: #e6f2ec;
            --must-light-yellow: #fff9e6;
            --must-light-blue: #e6ecf2;
            --must-red: #cc0000;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .wrapper {
            margin-left: 280px;
            padding: 20px 30px;
            transition: all 0.3s;
            min-height: calc(100vh - 56px);
        }

        #sidebar {
            width: 280px;
            transition: all 0.3s;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .verification-container {
            background-color: white;
            border-top: 4px solid var(--must-green);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .staff-selector {
            background-color: var(--must-light-green);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .data-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-header {
            background-color: var(--must-blue);
            color: white;
            padding: 12px 15px;
            font-weight: 600;
        }

        .table-container {
            padding: 15px;
        }

        .table th {
            background-color: var(--must-light-blue);
        }

        .badge-success {
            background-color: var(--must-green);
        }

        .badge-warning {
            background-color: var(--must-yellow);
        }

        .badge-danger {
            background-color: var(--must-red);
        }

        .action-buttons .btn {
            margin-right: 5px;
        }

        .empty-data {
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1050;
                height: 100vh;
                top: 0;
                left: 0;
            }

            #sidebar.active {
                transform: translateX(0);
            }

            .wrapper {
                margin-left: 0 !important;
                padding: 15px !important;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>

<body class="d-flex">
    <?php
    // nav_bar
    include '../../bars/nav_bar.php';
    // -- Sidebar --
    include '../../bars/side_bar.php';
    ?>

    <!-- Overlay for mobile -->
    <div class="sidebar-overlay"></div>

    <div class="wrapper d-flex flex-column flex-grow-1">

        <!-- Main Content -->
        <main class="container-fluid py-4 flex-grow-1">
            <div class="verification-container bg-white rounded-3 shadow-sm p-4">
                <h2 class="mb-4"><i class="fas fa-user-check text-primary me-2"></i> Staff Data Verification</h2>

                <!-- Notification Area -->
                <?php if (isset($_SESSION['notification'])): ?>
                    <div class="alert alert-dismissible alert-<?php echo $_SESSION['notification_type'] ?? 'success'; ?>">
                        <?php echo htmlspecialchars($_SESSION['notification']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['notification'], $_SESSION['notification_type']); ?>
                <?php endif; ?>

                <!-- Staff Selector Form -->
                <div class="staff-selector">
                    <form method="POST" action="">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-8">
                                <label for="staff_id" class="form-label">Select Staff Member:</label>
                                <select class="form-select" id="staff_id" name="staff_id" required>
                                    <option value="">-- Select Staff Member --</option>
                                    <?php foreach ($staff_members as $id => $name): ?>
                                        <option value="<?php echo $id; ?>" <?php echo ($selected_staff_id == $id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>View Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if ($selected_staff_id): ?>
                    <!-- Staff Basic Information -->
                    <div class="data-section mb-4">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user me-2"></i>Basic Information</span>
                            <span class="badge bg-success">Verified</span>
                        </div>
                        <div class="table-container">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="20%">Full Name</th>
                                        <td><?php echo htmlspecialchars($staff_data['first_name'] . ' ' . $staff_data['last_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Scholar Type</th>
                                        <td><?php echo htmlspecialchars($staff_data['scholar_type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Department</th>
                                        <td>
                                            <?php 
                                            if ($staff_data['department_id']) {
                                                $dept_query = "SELECT department_name FROM departments WHERE department_id = " . $staff_data['department_id'];
                                                $dept_result = $conn->query($dept_query);
                                                echo htmlspecialchars($dept_result->fetch_assoc()['department_name']);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Years of Experience</th>
                                        <td><?php echo htmlspecialchars($staff_data['years_of_experience']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Performance Score</th>
                                        <td><?php echo htmlspecialchars($staff_data['performance_score']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Academic Degrees -->
                    <div class="data-section mb-4">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-graduation-cap me-2"></i>Academic Degrees</span>
                            <div>
                                <span class="badge bg-warning text-dark">Pending Verification</span>
                                <button class="btn btn-sm btn-success ms-2" onclick="approveSection('degrees')">
                                    <i class="fas fa-check me-1"></i>Approve All
                                </button>
                            </div>
                        </div>
                        <div class="table-container">
                            <?php if (!empty($degrees_data)): ?>
                                <table class="table table-striped table-hover" id="degrees-table">
                                    <thead>
                                        <tr>
                                            <th>Degree Name</th>
                                            <th>Classification</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($degrees_data as $degree): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($degree['degree_name']); ?></td>
                                                <td><?php echo htmlspecialchars($degree['degree_classification']); ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-success" onclick="approveRow('degrees', <?php echo $degree['degree_id']; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectRow('degrees', <?php echo $degree['degree_id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewDetails('degrees', <?php echo $degree['degree_id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-data">
                                    <i class="fas fa-info-circle me-2"></i>No degree information found for this staff member.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Publications -->
                    <div class="data-section mb-4">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-book me-2"></i>Publications</span>
                            <div>
                                <span class="badge bg-warning text-dark">Pending Verification</span>
                                <button class="btn btn-sm btn-success ms-2" onclick="approveSection('publications')">
                                    <i class="fas fa-check me-1"></i>Approve All
                                </button>
                            </div>
                        </div>
                        <div class="table-container">
                            <?php if (!empty($publications_data)): ?>
                                <table class="table table-striped table-hover" id="publications-table">
                                    <thead>
                                        <tr>
                                            <th>Publication Type</th>
                                            <th>Role</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($publications_data as $pub): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($pub['publication_type']); ?></td>
                                                <td><?php echo htmlspecialchars($pub['role']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($pub['publication_date'])); ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-success" onclick="approveRow('publications', <?php echo $pub['publication_id']; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectRow('publications', <?php echo $pub['publication_id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewDetails('publications', <?php echo $pub['publication_id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-data">
                                    <i class="fas fa-info-circle me-2"></i>No publication information found for this staff member.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Research Grants -->
                    <div class="data-section mb-4">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-money-bill-wave me-2"></i>Research Grants</span>
                            <div>
                                <span class="badge bg-warning text-dark">Pending Verification</span>
                                <button class="btn btn-sm btn-success ms-2" onclick="approveSection('grants')">
                                    <i class="fas fa-check me-1"></i>Approve All
                                </button>
                            </div>
                        </div>
                        <div class="table-container">
                            <?php if (!empty($grants_data)): ?>
                                <table class="table table-striped table-hover" id="grants-table">
                                    <thead>
                                        <tr>
                                            <th>Grant Amount</th>
                                            <th>Year</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($grants_data as $grant): ?>
                                            <tr>
                                                <td>UGX <?php echo number_format($grant['grant_amount'], 2); ?></td>
                                                <td><?php echo date('Y', strtotime($grant['grant_year'])); ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-success" onclick="approveRow('grants', <?php echo $grant['grant_id']; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectRow('grants', <?php echo $grant['grant_id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewDetails('grants', <?php echo $grant['grant_id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-data">
                                    <i class="fas fa-info-circle me-2"></i>No grant information found for this staff member.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Innovations -->
                    <div class="data-section mb-4">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-lightbulb me-2"></i>Innovations</span>
                            <div>
                                <span class="badge bg-warning text-dark">Pending Verification</span>
                                <button class="btn btn-sm btn-success ms-2" onclick="approveSection('innovations')">
                                    <i class="fas fa-check me-1"></i>Approve All
                                </button>
                            </div>
                        </div>
                        <div class="table-container">
                            <?php if (!empty($innovations_data)): ?>
                                <table class="table table-striped table-hover" id="innovations-table">
                                    <thead>
                                        <tr>
                                            <th>Innovation Type</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($innovations_data as $innovation): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($innovation['innovation_type']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($innovation['innovation_date'])); ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-success" onclick="approveRow('innovations', <?php echo $innovation['innovation_id']; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectRow('innovations', <?php echo $innovation['innovation_id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewDetails('innovations', <?php echo $innovation['innovation_id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-data">
                                    <i class="fas fa-info-circle me-2"></i>No innovation information found for this staff member.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Community Service -->
                    <div class="data-section mb-4">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-hands-helping me-2"></i>Community Service</span>
                            <div>
                                <span class="badge bg-warning text-dark">Pending Verification</span>
                                <button class="btn btn-sm btn-success ms-2" onclick="approveSection('communityservice')">
                                    <i class="fas fa-check me-1"></i>Approve All
                                </button>
                            </div>
                        </div>
                        <div class="table-container">
                            <?php if (!empty($community_service_data)): ?>
                                <table class="table table-striped table-hover" id="communityservice-table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Beneficiaries</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($community_service_data as $service): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($service['description']); ?></td>
                                                <td><?php echo htmlspecialchars($service['beneficiaries']); ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-success" onclick="approveRow('communityservice', <?php echo $service['community_service_id']; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectRow('communityservice', <?php echo $service['community_service_id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewDetails('communityservice', <?php echo $service['community_service_id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-data">
                                    <i class="fas fa-info-circle me-2"></i>No community service information found for this staff member.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Student Supervision -->
                    <div class="data-section mb-4">
                        <div class="section-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-graduate me-2"></i>Student Supervision</span>
                            <div>
                                <span class="badge bg-warning text-dark">Pending Verification</span>
                                <button class="btn btn-sm btn-success ms-2" onclick="approveSection('supervision')">
                                    <i class="fas fa-check me-1"></i>Approve All
                                </button>
                            </div>
                        </div>
                        <div class="table-container">
                            <?php if (!empty($supervision_data)): ?>
                                <table class="table table-striped table-hover" id="supervision-table">
                                    <thead>
                                        <tr>
                                            <th>Student Level</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($supervision_data as $supervision): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($supervision['student_level']); ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                </td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-success" onclick="approveRow('supervision', <?php echo $supervision['supervision_id']; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="rejectRow('supervision', <?php echo $supervision['supervision_id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick="viewDetails('supervision', <?php echo $supervision['supervision_id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="empty-data">
                                    <i class="fas fa-info-circle me-2"></i>No student supervision information found for this staff member.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Verification Actions -->
                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-secondary" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh Data
                        </button>
                        <div>
                            <button class="btn btn-danger me-2" onclick="rejectAll()">
                                <i class="fas fa-times-circle me-2"></i>Reject All
                            </button>
                            <button class="btn btn-success" onclick="approveAll()">
                                <i class="fas fa-check-circle me-2"></i>Approve All
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center py-4">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>Select a staff member to view and verify their data</h4>
                        <p class="mb-0">Use the dropdown above to select a staff member whose data you want to verify.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- jQuery, Bootstrap Bundle with Popper, DataTables -->
    <script src="../../../components/jquery/jquery.min.js"></script>
    <script src="../../../components/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../components/datatables/datatables.min.js"></script>
    <script src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/bars/nav_sidebar/nav_side_bar.js"></script>
    
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#degrees-table, #publications-table, #grants-table, #innovations-table, #communityservice-table, #supervision-table').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50],
                dom: '<"top"f>rt<"bottom"lip><"clear">'
            });
        });

        // Sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            if (hamburger && sidebar && overlay) {
                hamburger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });

        // Approval functions
        function approveRow(table, id) {
            if (confirm('Are you sure you want to approve this record?')) {
                fetch('process_verification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=approve&table=${table}&id=${id}&staff_id=<?php echo $selected_staff_id; ?>`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
            }
        }

        function rejectRow(table, id) {
            const reason = prompt('Please enter reason for rejection:');
            if (reason !== null) {
                fetch('process_verification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=reject&table=${table}&id=${id}&reason=${encodeURIComponent(reason)}&staff_id=<?php echo $selected_staff_id; ?>`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
            }
        }

        function approveSection(table) {
            if (confirm(`Are you sure you want to approve ALL ${table} records?`)) {
                fetch('process_verification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=approve_section&table=${table}&staff_id=<?php echo $selected_staff_id; ?>`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    alert(data.message);
                    location.reload();
                    alert('All records in this section have been approved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }
    }

    function approveAll() {
        if (confirm('Are you sure you want to approve ALL records for this staff member?')) {
            fetch('process_verification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=approve_all&staff_id=<?php echo $selected_staff_id; ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('All records have been approved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }
    }

    function rejectAll() {
        const reason = prompt('Please enter reason for rejecting all records:');
        if (reason !== null) {
            fetch('process_verification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=reject_all&reason=${encodeURIComponent(reason)}&staff_id=<?php echo $selected_staff_id; ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('All records have been rejected successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }
    }

    function viewDetails(table, id) {
        // You can implement a modal or redirect to a detailed view page
        alert(`Viewing details for ${table} record ID: ${id}`);
        // window.location.href = `view_details.php?table=${table}&id=${id}`;
    }
</script>
</body>
</html>
```

And here's the corresponding `process_verification.php` file that handles the approval/rejection actions:

```php
<?php
session_start();
include 'config.php';

// Check if user is logged in and has permission
if (!isset($_SESSION['staff_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get POST data
$action = $_POST['action'] ?? '';
$table = $_POST['table'] ?? '';
$id = $_POST['id'] ?? 0;
$staff_id = $_POST['staff_id'] ?? 0;
$reason = $_POST['reason'] ?? '';

// Validate inputs
if (empty($action) {
    echo json_encode(['success' => false, 'message' => 'No action specified']);
    exit;
}

// Process different actions
switch ($action) {
    case 'approve':
        if (empty($table) {
            echo json_encode(['success' => false, 'message' => 'No table specified']);
            exit;
        }
        
        // For simplicity, we'll just update a status field if it exists
        // In a real implementation, you might have a verification table
        try {
            // Check if table exists and has a verification column
            $check_query = "SHOW COLUMNS FROM $table LIKE 'verification_status'";
            $check_result = $conn->query($check_query);
            
            if ($check_result->num_rows > 0) {
                $update_query = "UPDATE $table SET verification_status = 'verified' WHERE " . getPrimaryKey($table) . " = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                
                if ($stmt->affected_rows > 0) {
                    logVerification($staff_id, $table, $id, 'approved');
                    echo json_encode(['success' => true, 'message' => 'Record approved successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No record updated']);
                }
            } else {
                // If no verification column, just log the approval
                logVerification($staff_id, $table, $id, 'approved');
                echo json_encode(['success' => true, 'message' => 'Approval logged']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    case 'reject':
        if (empty($table) || empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }
        
        try {
            // Check if table exists and has a verification column
            $check_query = "SHOW COLUMNS FROM $table LIKE 'verification_status'";
            $check_result = $conn->query($check_query);
            
            if ($check_result->num_rows > 0) {
                $update_query = "UPDATE $table SET verification_status = 'rejected', rejection_reason = ? WHERE " . getPrimaryKey($table) . " = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("si", $reason, $id);
                $stmt->execute();
                
                if ($stmt->affected_rows > 0) {
                    logVerification($staff_id, $table, $id, 'rejected', $reason);
                    echo json_encode(['success' => true, 'message' => 'Record rejected successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No record updated']);
                }
            } else {
                // If no verification column, just log the rejection
                logVerification($staff_id, $table, $id, 'rejected', $reason);
                echo json_encode(['success' => true, 'message' => 'Rejection logged']);
            }