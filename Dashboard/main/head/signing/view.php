<?php
session_start();
// require_once '../approve/config.php'; // Database connection file
require_once '../approve/configOLD.php'; // Database connection file

// Check if user is logged in
// if (!isset($_SESSION['staff_id']) || !isset($_SESSION['role'])) {
//     header("Location: login.php");
//     exit();
// }

// Function to get staff name by ID
function getStaffName($staff_id) {
    global $conn;
    if ($staff_id === null) return 'N/A';
    
    $stmt = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) AS staff_name FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['staff_name'];
    }
    return 'Unknown';
}

// Handle approval/rejection actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['record_id']) && isset($_POST['table_name'])) {
        $action = $_POST['action'];
        $record_id = $_POST['record_id'];
        $table_name = $_POST['table_name'];
        $reason = $_POST['reason'] ?? ($action === 'approve' ? 'Approved by system' : '');
        $staff_id = $_SESSION['staff_id'];
        $role = $_SESSION['user_role'];
        
        try {
            // Begin transaction
            $conn->begin_transaction();
            
            // Update the record status in the original table
            $update_stmt = $conn->prepare("UPDATE $table_name SET verification_status = ?, verification_notes = ?, verified_by = ?, verification_date = NOW() WHERE " . getPrimaryKey($table_name) . " = ?");
            
            $status = ($action === 'approve') ? 'approved' : 'rejected';
            $update_stmt->bind_param("ssii", $status, $reason, $staff_id, $record_id);
            $update_stmt->execute();
            
            // Log the action in appraisalstatus table
            $log_stmt = $conn->prepare("INSERT INTO appraisalstatus (staff_id, table_name, action, reason, approved_by, role) VALUES (?, ?, ?, ?, ?, ?)");
            
            // Get the staff_id from the record being approved/rejected
            $record_owner = getRecordOwner($table_name, $record_id);
            
            $log_stmt->bind_param("isssis", $record_owner, $table_name, $status, $reason, $staff_id, $role);
            $log_stmt->execute();
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode(['success' => true, 'message' => "Record $status successfully"]);
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            exit();
        }
    }
}

// Helper function to get primary key column name for a table
function getPrimaryKey($table_name) {
    $primary_keys = [
        'academicactivities' => 'activity_id',
        'activity_types' => 'type_id',
        'communityservice' => 'community_service_id',
        'degrees' => 'degree_id',
        'grants' => 'grant_id',
        'innovations' => 'innovation_id',
        'professionalbodies' => 'professional_body_id',
        'publications' => 'publication_id',
        'service' => 'service_id',
        'staff' => 'staff_id',
        'supervision' => 'supervision_id'
    ];
    return $primary_keys[$table_name] ?? 'id';
}

// Helper function to get the staff_id from a record
function getRecordOwner($table_name, $record_id) {
    global $conn;
    
    $primary_key = getPrimaryKey($table_name);
    $stmt = $conn->prepare("SELECT staff_id FROM $table_name WHERE $primary_key = ?");
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['staff_id'] ?? null;
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Database Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #1abc9c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border: none;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: var(--secondary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .table-responsive {
            border-radius: 0 0 10px 10px;
            overflow: hidden;
        }
        
        table.dataTable {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 20px;
            padding: 5px 15px;
            border: 1px solid #ddd;
        }
        
        .btn-action {
            border-radius: 20px;
            padding: 5px 15px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-approve {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-disapprove {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-view {
            background-color: var(--primary-color);
            color: white;
        }
        
        .badge-pending {
            background-color: var(--warning-color);
        }
        
        .badge-approved {
            background-color: var(--success-color);
        }
        
        .badge-rejected {
            background-color: var(--danger-color);
        }
        
        .nav-tabs .nav-link.active {
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }
        
        .action-buttons {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 0 0 10px 10px;
            border-top: 1px solid #eee;
        }
        
        .status-filter {
            margin-bottom: 15px;
        }
        
        .table th {
            background-color: var(--light-color);
            font-weight: 600;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .pagination .page-link {
            color: var(--secondary-color);
        }
        
        .dataTables_length select {
            border-radius: 20px;
            padding: 5px;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #2980b9;
        }
        
        /* Animation for status changes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-header {
                padding: 10px 15px;
                font-size: 16px;
            }
            
            .btn-action {
                padding: 5px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-5 fw-bold text-center text-primary">Advanced Database Management System</h1>
                <p class="text-center text-muted">Manage all database tables with approval workflows</p>
                <div class="text-end">
                    <span class="badge bg-primary">Logged in as: <?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
                    <span class="badge bg-secondary ms-2">Staff ID: <?php echo htmlspecialchars($_SESSION['staff_id']); ?></span>
                </div>
            </div>
        </div>

        <!-- Table Navigation Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-tabs" id="tableTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab">Academic Activities</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-types-tab" data-bs-toggle="tab" data-bs-target="#activity-types" type="button" role="tab">Activity Types</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="community-tab" data-bs-toggle="tab" data-bs-target="#community" type="button" role="tab">Community Service</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="degrees-tab" data-bs-toggle="tab" data-bs-target="#degrees" type="button" role="tab">Degrees</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="grants-tab" data-bs-toggle="tab" data-bs-target="#grants" type="button" role="tab">Grants</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="innovations-tab" data-bs-toggle="tab" data-bs-target="#innovations" type="button" role="tab">Innovations</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional" type="button" role="tab">Professional Bodies</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="publications-tab" data-bs-toggle="tab" data-bs-target="#publications" type="button" role="tab">Publications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="service-tab" data-bs-toggle="tab" data-bs-target="#service" type="button" role="tab">Service</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab">Staff</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="supervision-tab" data-bs-toggle="tab" data-bs-target="#supervision" type="button" role="tab">Supervision</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="tableTabsContent">
            <!-- Academic Activities Table -->
            <div class="tab-pane fade show active" id="academic" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Academic Activities</span>
                        <div class="status-filter">
                            <select class="form-select form-select-sm" id="academicStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="academicTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Staff Name</th>
                                    <th>Activity Type</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                    <th>Verification Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT a.*, CONCAT(s.first_name, ' ', s.last_name) as staff_name, 
                                          v.first_name as verifier_first, v.last_name as verifier_last
                                          FROM academicactivities a
                                          LEFT JOIN staff s ON a.staff_id = s.staff_id
                                          LEFT JOIN staff v ON a.verified_by = v.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                    $verifier_name = $row['verifier_first'] ? $row['verifier_first'] . ' ' . $row['verifier_last'] : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['activity_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['activity_type']); ?></td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['verification_status'] === 'approved') $badgeClass = 'badge-approved';
                                        if ($row['verification_status'] === 'rejected') $badgeClass = 'badge-rejected';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['verification_status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($verifier_name); ?></td>
                                    <td><?php echo $row['verification_date'] ? date('M d, Y H:i', strtotime($row['verification_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['activity_id']; ?>" data-table="academicactivities">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['activity_id']; ?>" data-table="academicactivities">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['activity_id']; ?>" data-table="academicactivities">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Activity Types Table -->
            <div class="tab-pane fade" id="activity-types" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Activity Types
                    </div>
                    <div class="table-responsive">
                        <table id="activityTypesTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Type ID</th>
                                    <th>Type Name</th>
                                    <th>Description</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM activity_types";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['type_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['type_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo $row['is_active'] ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['type_id']; ?>" data-table="activity_types">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['type_id']; ?>" data-table="activity_types">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['type_id']; ?>" data-table="activity_types">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Community Service Table -->
            <div class="tab-pane fade" id="community" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Community Service</span>
                        <div class="status-filter">
                            <select class="form-select form-select-sm" id="communityStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="communityTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Service ID</th>
                                    <th>Staff Name</th>
                                    <th>Description</th>
                                    <th>Beneficiaries</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                    <th>Verification Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT c.*, CONCAT(s.first_name, ' ', s.last_name) as staff_name, 
                                          v.first_name as verifier_first, v.last_name as verifier_last
                                          FROM communityservice c
                                          LEFT JOIN staff s ON c.staff_id = s.staff_id
                                          LEFT JOIN staff v ON c.verified_by = v.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                    $verifier_name = $row['verifier_first'] ? $row['verifier_first'] . ' ' . $row['verifier_last'] : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['community_service_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['beneficiaries']); ?></td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['verification_status'] === 'approved') $badgeClass = 'badge-approved';
                                        if ($row['verification_status'] === 'rejected') $badgeClass = 'badge-rejected';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['verification_status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($verifier_name); ?></td>
                                    <td><?php echo $row['verification_date'] ? date('M d, Y H:i', strtotime($row['verification_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['community_service_id']; ?>" data-table="communityservice">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['community_service_id']; ?>" data-table="communityservice">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['community_service_id']; ?>" data-table="communityservice">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Degrees Table -->
            <div class="tab-pane fade" id="degrees" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Degrees</span>
                        <div class="status-filter">
                            <select class="form-select form-select-sm" id="degreesStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="degreesTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Degree ID</th>
                                    <th>Staff Name</th>
                                    <th>Degree Name</th>
                                    <th>Institution</th>
                                    <th>Year Obtained</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                    <th>Verification Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT d.*, CONCAT(s.first_name, ' ', s.last_name) as staff_name, 
                                          v.first_name as verifier_first, v.last_name as verifier_last
                                          FROM degrees d
                                          LEFT JOIN staff s ON d.staff_id = s.staff_id
                                          LEFT JOIN staff v ON d.verified_by = v.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                    $verifier_name = $row['verifier_first'] ? $row['verifier_first'] . ' ' . $row['verifier_last'] : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['degree_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['degree_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['institution']); ?></td>
                                    <td><?php echo htmlspecialchars($row['year_obtained']); ?></td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['verification_status'] === 'approved') $badgeClass = 'badge-approved';
                                        if ($row['verification_status'] === 'rejected') $badgeClass = 'badge-rejected';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['verification_status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($verifier_name); ?></td>
                                    <td><?php echo $row['verification_date'] ? date('M d, Y H:i', strtotime($row['verification_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['degree_id']; ?>" data-table="degrees">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['degree_id']; ?>" data-table="degrees">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['degree_id']; ?>" data-table="degrees">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Grants Table -->
            <div class="tab-pane fade" id="grants" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Grants</span>
                        <div class="status-filter">
                            <select class="form-select form-select-sm" id="grantsStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="grantsTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Grant ID</th>
                                    <th>Staff Name</th>
                                    <th>Grant Name</th>
                                    <th>Amount</th>
                                    <th>Funding Agency</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                    <th>Verification Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT g.*, CONCAT(s.first_name, ' ', s.last_name) as staff_name, 
                                          v.first_name as verifier_first, v.last_name as verifier_last
                                          FROM grants g
                                          LEFT JOIN staff s ON g.staff_id = s.staff_id
                                          LEFT JOIN staff v ON g.verified_by = v.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                    $verifier_name = $row['verifier_first'] ? $row['verifier_first'] . ' ' . $row['verifier_last'] : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['grant_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['grant_name']); ?></td>
                                    <td><?php echo number_format($row['grant_amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['funding_agency']); ?></td>
                                    <td><?php echo htmlspecialchars($row['grant_year']); ?></td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['verification_status'] === 'approved') $badgeClass = 'badge-approved';
                                        if ($row['verification_status'] === 'rejected') $badgeClass = 'badge-rejected';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['verification_status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($verifier_name); ?></td>
                                    <td><?php echo $row['verification_date'] ? date('M d, Y H:i', strtotime($row['verification_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['grant_id']; ?>" data-table="grants">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['grant_id']; ?>" data-table="grants">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['grant_id']; ?>" data-table="grants">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Innovations Table -->
            <div class="tab-pane fade" id="innovations" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Innovations</span>
                        <div class="status-filter">
                            <select class="form-select form-select-sm" id="innovationsStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="innovationsTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Innovation ID</th>
                                    <th>Staff Name</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                    <th>Verification Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT i.*, CONCAT(s.first_name, ' ', s.last_name) as staff_name, 
                                          v.first_name as verifier_first, v.last_name as verifier_last
                                          FROM innovations i
                                          LEFT JOIN staff s ON i.staff_id = s.staff_id
                                          LEFT JOIN staff v ON i.verified_by = v.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                    $verifier_name = $row['verifier_first'] ? $row['verifier_first'] . ' ' . $row['verifier_last'] : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['innovation_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['innovation_type']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['innovation_date'])); ?></td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['verification_status'] === 'approved') $badgeClass = 'badge-approved';
                                        if ($row['verification_status'] === 'rejected') $badgeClass = 'badge-rejected';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['verification_status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($verifier_name); ?></td>
                                    <td><?php echo $row['verification_date'] ? date('M d, Y H:i', strtotime($row['verification_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['innovation_id']; ?>" data-table="innovations">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['innovation_id']; ?>" data-table="innovations">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['innovation_id']; ?>" data-table="innovations">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Professional Bodies Table -->
            <div class="tab-pane fade" id="professional" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Professional Bodies
                    </div>
                    <div class="table-responsive">
                        <table id="professionalTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Body ID</th>
                                    <th>Staff Name</th>
                                    <th>Body Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT p.*, CONCAT(s.first_name, ' ', s.last_name) as staff_name
                                          FROM professionalbodies p
                                          LEFT JOIN staff s ON p.staff_id = s.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['professional_body_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['body_name']); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['professional_body_id']; ?>" data-table="professionalbodies">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['professional_body_id']; ?>" data-table="professionalbodies">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['professional_body_id']; ?>" data-table="professionalbodies">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Publications Table -->
            <div class="tab-pane fade" id="publications" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Publications</span>
                        <div class="status-filter">
                            <select class="form-select form-select-sm" id="publicationsStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="publicationsTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Publication ID</th>
                                    <th>Staff Name</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Journal</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                    <th>Verification Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT p.*, CONCAT(s.first_name, ' ', s.last_name) as staff_name, 
                                          v.first_name as verifier_first, v.last_name as verifier_last
                                          FROM publications p
                                          LEFT JOIN staff s ON p.staff_id = s.staff_id
                                          LEFT JOIN staff v ON p.verified_by = v.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                    $verifier_name = $row['verifier_first'] ? $row['verifier_first'] . ' ' . $row['verifier_last'] : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['publication_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['publication_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['journal_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['publication_date'])); ?></td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['verification_status'] === 'approved') $badgeClass = 'badge-approved';
                                        if ($row['verification_status'] === 'rejected') $badgeClass = 'badge-rejected';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['verification_status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($verifier_name); ?></td>
                                    <td><?php echo $row['verification_date'] ? date('M d, Y H:i', strtotime($row['verification_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['publication_id']; ?>" data-table="publications">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['publication_id']; ?>" data-table="publications">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['publication_id']; ?>" data-table="publications">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Service Table -->
            <div class="tab-pane fade" id="service" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Service
                    </div>
                    <div class="table-responsive">
                        <table id="serviceTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Service ID</th>
                                    <th>Staff Name</th>
                                    <th>Service Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT s.*, CONCAT(st.first_name, ' ', st.last_name) as staff_name
                                          FROM service s
                                          LEFT JOIN staff st ON s.staff_id = st.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['service_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['service_id']; ?>" data-table="service">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['service_id']; ?>" data-table="service">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['service_id']; ?>" data-table="service">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Staff Table -->
            <div class="tab-pane fade" id="staff" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        Staff
                    </div>
                    <div class="table-responsive">
                        <table id="staffTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Staff ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Experience</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT s.*, d.department_name, r.role_name 
                                          FROM staff s
                                          LEFT JOIN departments d ON s.department_id = d.department_id
                                          LEFT JOIN roles r ON s.role_id = r.role_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['years_of_experience']); ?> years</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['staff_id']; ?>" data-table="staff">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['staff_id']; ?>" data-table="staff">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['staff_id']; ?>" data-table="staff">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>

            <!-- Supervision Table -->
            <div class="tab-pane fade" id="supervision" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Supervision</span>
                        <div class="status-filter">
                            <select class="form-select form-select-sm" id="supervisionStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="supervisionTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Supervision ID</th>
                                    <th>Staff Name</th>
                                    <th>Student Name</th>
                                    <th>Level</th>
                                    <th>Thesis Title</th>
                                    <th>Completion Year</th>
                                    <th>Status</th>
                                    <th>Verified By</th>
                                    <th>Verification Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT s.*, CONCAT(st.first_name, ' ', st.last_name) as staff_name, 
                                          v.first_name as verifier_first, v.last_name as verifier_last
                                          FROM supervision s
                                          LEFT JOIN staff st ON s.staff_id = st.staff_id
                                          LEFT JOIN staff v ON s.verified_by = v.staff_id";
                                $result = $conn->query($query);
                                
                                while ($row = $result->fetch_assoc()):
                                    $verifier_name = $row['verifier_first'] ? $row['verifier_first'] . ' ' . $row['verifier_last'] : 'N/A';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['supervision_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['student_level']); ?></td>
                                    <td><?php echo htmlspecialchars($row['thesis_title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['completion_year']); ?></td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['verification_status'] === 'approved') $badgeClass = 'badge-approved';
                                        if ($row['verification_status'] === 'rejected') $badgeClass = 'badge-rejected';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($row['verification_status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($verifier_name); ?></td>
                                    <td><?php echo $row['verification_date'] ? date('M d, Y H:i', strtotime($row['verification_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-view view-record" data-id="<?php echo $row['supervision_id']; ?>" data-table="supervision">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-approve approve-record" data-id="<?php echo $row['supervision_id']; ?>" data-table="supervision">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-disapprove disapprove-record" data-id="<?php echo $row['supervision_id']; ?>" data-table="supervision">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons text-end">
                        <button class="btn btn-disapprove me-2">Disapprove Selected</button>
                        <button class="btn btn-approve">Approve Selected</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Record Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBodyContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize all DataTables
            const academicTable = $('#academicTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const activityTypesTable = $('#activityTypesTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const communityTable = $('#communityTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const degreesTable = $('#degreesTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const grantsTable = $('#grantsTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const innovationsTable = $('#innovationsTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const professionalTable = $('#professionalTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const publicationsTable = $('#publicationsTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const serviceTable = $('#serviceTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const staffTable = $('#staffTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });
            
            const supervisionTable = $('#supervisionTable').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });

            // Status filters
            $('#academicStatusFilter').change(function() {
                academicTable.column(3).search(this.value).draw();
            });
            
            $('#communityStatusFilter').change(function() {
                communityTable.column(4).search(this.value).draw();
            });
            
            $('#degreesStatusFilter').change(function() {
                degreesTable.column(5).search(this.value).draw();
            });
            
            $('#grantsStatusFilter').change(function() {
                grantsTable.column(6).search(this.value).draw();
            });
            
            $('#innovationsStatusFilter').change(function() {
                innovationsTable.column(5).search(this.value).draw();
            });
            
            $('#publicationsStatusFilter').change(function() {
                publicationsTable.column(6).search(this.value).draw();
            });
            
            $('#supervisionStatusFilter').change(function() {
                supervisionTable.column(6).search(this.value).draw();
            });
            
            // View record details
            $(document).on('click', '.view-record', function() {
                const recordId = $(this).data('id');
                const tableName = $(this).data('table');
                
                $.ajax({
                    url: 'get_record_details.php',
                    method: 'POST',
                    data: { id: recordId, table: tableName },
                    success: function(response) {
                        $('#modalBodyContent').html(response);
                        $('#detailsModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'Could not load record details: ' + error,
                            'error'
                        );
                    }
                });
            });
            
            // Approve record
            $(document).on('click', '.approve-record', function() {
                const recordId = $(this).data('id');
                const tableName = $(this).data('table');
                
                Swal.fire({
                    title: 'Approve Record?',
                    text: "Are you sure you want to approve this record?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#e74c3c',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '',
                            method: 'POST',
                            data: { 
                                action: 'approve',
                                record_id: recordId,
                                table_name: tableName
                            },
                            success: function(response) {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    Swal.fire(
                                        'Approved!',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem approving the record: ' + error,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            
            // Disapprove record
            $(document).on('click', '.disapprove-record', function() {
                const recordId = $(this).data('id');
                const tableName = $(this).data('table');
                
                Swal.fire({
                    title: 'Reject Record',
                    input: 'textarea',
                    inputLabel: 'Reason for rejection',
                    inputPlaceholder: 'Enter the reason for rejecting this record...',
                    inputAttributes: {
                        'aria-label': 'Enter the reason for rejecting this record'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#7f8c8d',
                    confirmButtonText: 'Reject',
                    cancelButtonText: 'Cancel',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to provide a reason!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        
                        $.ajax({
                            url: '',
                            method: 'POST',
                            data: { 
                                action: 'reject',
                                record_id: recordId,
                                table_name: tableName,
                                reason: reason
                            },
                            success: function(response) {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    Swal.fire(
                                        'Rejected!',
                                        data.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem rejecting the record: ' + error,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            
            // Bulk approve/disapprove
            $('.action-buttons .btn-approve').click(function() {
                // Implement bulk approval logic
                Swal.fire(
                    'Bulk Approval',
                    'Bulk approval functionality would be implemented here',
                    'info'
                );
            });
            
            $('.action-buttons .btn-disapprove').click(function() {
                // Implement bulk disapproval logic
                Swal.fire(
                    'Bulk Rejection',
                    'Bulk rejection functionality would be implemented here',
                    'info'
                );
            });
        });
    </script>
</body>
</html>