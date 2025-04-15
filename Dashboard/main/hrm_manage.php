<?php
session_start();
require_once 'head/approve/config.php';

// Pagination settings
$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $records_per_page;

// Search functionality
$search_term = '';
$search_condition = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $search_condition = "WHERE s.first_name LIKE '%$search_term%' OR s.last_name LIKE '%$search_term%' OR d.department_name LIKE '%$search_term%'";
}

// Get total number of staff for pagination
$total_staff_query = $conn->query("SELECT COUNT(*) as total FROM staff s JOIN departments d ON s.department_id = d.department_id $search_condition");
$total_staff = $total_staff_query->fetch_assoc()['total'];
$total_pages = ceil($total_staff / $records_per_page);

// Get staff data with pagination
$staff_query = $conn->query("
    SELECT s.staff_id, s.first_name, s.last_name, s.performance_score, s.years_of_experience,
           d.department_name, r.role_name, u.photo_path
    FROM staff s
    JOIN departments d ON s.department_id = d.department_id
    JOIN roles r ON s.role_id = r.role_id
    LEFT JOIN users u ON s.staff_id = u.staff_id
    $search_condition
    ORDER BY s.last_name, s.first_name
    LIMIT $offset, $records_per_page
");
$staff_list = $staff_query->fetch_all(MYSQLI_ASSOC);

// Get current page for active menu highlighting
$current_page_name = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - MUST HRM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .staff-management-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            transition: var(--transition);
        }

        .staff-management-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .staff-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--must-blue-light);
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

        .search-box {
            position: relative;
            max-width: 400px;
        }

        .search-box .form-control {
            padding-left: 40px;
            border-radius: 20px;
            border: 1px solid #ddd;
        }

        .search-box .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--must-blue);
            border-color: var(--must-blue);
        }

        .pagination .page-link {
            color: var(--must-blue);
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 3px;
            transition: var(--transition);
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .view-btn {
            background-color: var(--must-blue);
            color: white;
        }

        .edit-btn {
            background-color: var(--must-green);
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .must-footer {
            background-color: var(--must-blue-dark);
            color: white;
            padding: 30px 0;
            margin-top: 40px;
        }

        .footer-logo {
            max-width: 180px;
            margin-bottom: 20px;
        }

        .footer-links h5 {
            color: var(--must-yellow);
            margin-bottom: 15px;
            font-size: 18px;
        }

        .footer-links ul {
            list-style: none;
            padding-left: 0;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .staff-table th {
            background-color: var(--must-blue);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .staff-table tr:hover {
            background-color: rgba(46, 49, 146, 0.03);
        }

        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
        }
    </style>
</head>

<body>
    <!-- navigation bar -->
    <?php //include 'bars/nav_bar.php'; ?>

    <!-- sidebar -->
    <?php //include 'bars/side_bar.php'; ?>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="mb-0">Staff Management</h2>
                    <p class="text-muted">Manage and view all staff members</p>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center">
                    <div class="search-box me-3">
                        <i class="fas fa-search search-icon"></i>
                        <form method="GET" class="d-inline">
                            <input type="text" name="search" class="form-control" placeholder="Search staff..." 
                                   value="<?= htmlspecialchars($search_term) ?>">
                        </form>
                    </div>
                    <a href="add_staff.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add Staff
                    </a>
                </div>
            </div>

            <div class="card staff-management-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover staff-table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Staff Member</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Performance</th>
                                    <th>Experience</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($staff_list) > 0): ?>
                                    <?php foreach ($staff_list as $staff): ?>
                                        <tr>
                                            <td><?= $staff['staff_id'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($staff['photo_path'])): ?>
                                                        <img src="<?= htmlspecialchars($staff['photo_path']) ?>" 
                                                             class="staff-avatar me-3" alt="Staff Photo">
                                                    <?php else: ?>
                                                        <div class="staff-avatar me-3 bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-user text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($staff['first_name']) ?> <?= htmlspecialchars($staff['last_name']) ?></h6>
                                                        <small class="text-muted">ID: <?= $staff['staff_id'] ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($staff['department_name']) ?></td>
                                            <td><?= htmlspecialchars($staff['role_name']) ?></td>
                                            <td>
                                                <span class="badge performance-badge bg-<?= getPerformanceColor($staff['performance_score']) ?>">
                                                    <?= $staff['performance_score'] ?>
                                                </span>
                                            </td>
                                            <td><?= $staff['years_of_experience'] ?> years</td>
                                            <td>
                                                <a href="view_staff.php?id=<?= $staff['staff_id'] ?>" 
                                                   class="action-btn view-btn" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit_staff.php?id=<?= $staff['staff_id'] ?>" 
                                                   class="action-btn edit-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete_staff.php?id=<?= $staff['staff_id'] ?>" 
                                                   class="action-btn delete-btn" title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this staff member?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                            <h5>No staff members found</h5>
                                            <p class="text-muted">Try adjusting your search or add a new staff member</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Staff pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search_term) ?>" 
                                   aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php 
                        // Show page numbers
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        if ($start_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=1&search='.urlencode($search_term).'">1</a></li>';
                            if ($start_page > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }
                        
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            $active = ($i == $current_page) ? 'active' : '';
                            echo '<li class="page-item '.$active.'"><a class="page-link" href="?page='.$i.'&search='.urlencode($search_term).'">'.$i.'</a></li>';
                        }
                        
                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?page='.$total_pages.'&search='.urlencode($search_term).'">'.$total_pages.'</a></li>';
                        }
                        ?>

                        <?php if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search_term) ?>" 
                                   aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <footer class="must-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 text-center text-md-start">
                        <img src="../assets/images/must-logo-white.png" alt="MUST Logo" class="footer-logo">
                        <p class="mt-2">Mbarara University of Science and Technology</p>
                        <p>Expert Scorecard System for Human Resource Management</p>
                    </div>
                    <div class="col-md-2">
                        <div class="footer-links">
                            <h5>Quick Links</h5>
                            <ul>
                                <li><a href="../dashboard.php">Dashboard</a></li>
                                <li><a href="../staff/">Staff Management</a></li>
                                <li><a href="../reports/">Reports</a></li>
                                <li><a href="../settings/">Settings</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-links">
                            <h5>Departments</h5>
                            <ul>
                                <li><a href="#">Computer Science</a></li>
                                <li><a href="#">Information Technology</a></li>
                                <li><a href="#">Engineering</a></li>
                                <li><a href="#">Business</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-links">
                            <h5>Contact Us</h5>
                            <ul>
                                <li><i class="fas fa-map-marker-alt me-2"></i> Mbarara, Uganda</li>
                                <li><i class="fas fa-phone me-2"></i> +256 123 456 789</li>
                                <li><i class="fas fa-envelope me-2"></i> hrm@must.ac.ug</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="copyright">
                    <p>&copy; <?= date('Y') ?> Mbarara University of Science and Technology. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Auto-submit search form when typing stops
        $(document).ready(function() {
            var timer;
            $('input[name="search"]').on('keyup', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    $('form').submit();
                }, 800);
            });
        });

        // Helper function for performance badge color
        function getPerformanceColor(score) {
            if (score >= 80) return 'success';
            if (score >= 60) return 'warning';
            return 'danger';
        }
    </script>
</body>
</html>