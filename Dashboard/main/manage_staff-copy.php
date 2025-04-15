<?php
session_start();
require_once 'head/approve/config.php';

// Helper function for performance badge color
function getPerformanceColor($score)
{
    if ($score >= 80) return 'success';
    if ($score >= 60) return 'warning';
    return 'danger';
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if ($delete_id > 0) {
        // First delete from users table (if exists)
        $conn->query("DELETE FROM users WHERE staff_id = $delete_id");

        // Then delete from staff table
        $conn->query("DELETE FROM staff WHERE staff_id = $delete_id");

        $_SESSION['message'] = "Staff member deleted successfully";
        header("Location: staff_management.php");
        exit();
    }
}

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
    $search_condition = "WHERE s.first_name LIKE '%$search_term%' OR s.last_name LIKE '%$search_term%' OR d.department_name LIKE '%$search_term%' OR r.role_name LIKE '%$search_term%'";
}

// Get total number of staff for pagination
$total_staff_query = $conn->query("SELECT COUNT(*) as total FROM staff s JOIN departments d ON s.department_id = d.department_id JOIN roles r ON s.role_id = r.role_id $search_condition");
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
    <link rel="stylesheet" href="styles/manage_staff.css">
</head>

<body>
    <!-- navigation bar -->
    <?php include 'bars/nav_bar.php';
    ?>

    <!-- sidebar -->
    <?php include 'bars/side_bar.php';
    ?>

    <!-- Main Content -->
    <!-- <div class="content-wrapper w-75 mt-5"> -->
    <div class="content-wrapper mt-5" style="width: 80%; margin-left: 20%;">

        <div class="container-fluid py-4">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-message">
                    <?= $_SESSION['message'] ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

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
                    <a href="/EMPLOYEE-TRACKING-SYSTEM/registration/register.php" class="btn btn-primary">
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
                                    <th>Rank</th>
                                    <th>Performance</th>
                                    <th>Experience</th>
                                    <th style="width: 200px; min-width: 200px">Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // 1. Sort members: those with photo_path come first
                                usort($staff_list, function ($a, $b) {
                                    return !empty($b['photo_path']) <=> !empty($a['photo_path']);
                                });
                                
                                // 2. Initialize custom auto-increment counter
                                $i = 1;
                                ?>

                                <?php if (count($staff_list) > 0): ?>
                                    <?php foreach ($staff_list as $staff): ?>
                                        <tr>
                                            <td><?= $i++ ?></td> <!-- Show custom counter instead of staff_id -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($staff['photo_path'])): ?>
                                                        <img src="<?= htmlspecialchars($staff['photo_path']) ?>" class="staff-avatar me-3" alt="Staff Photo">
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
                                                <a href="individual_view2.php?staff_id=<?= $staff['staff_id'] ?>" class="action-btn view-btn" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="re_registration.php?user_id=<?= $staff['staff_id'] ?>" class="action-btn edit-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="staff_management.php?delete_id=<?= $staff['staff_id'] ?>" class="action-btn delete-btn" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.')">
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
                            echo '<li class="page-item"><a class="page-link" href="?page=1&search=' . urlencode($search_term) . '">1</a></li>';
                            if ($start_page > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++) {
                            $active = ($i == $current_page) ? 'active' : '';
                            echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '&search=' . urlencode($search_term) . '">' . $i . '</a></li>';
                        }

                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&search=' . urlencode($search_term) . '">' . $total_pages . '</a></li>';
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
                        <img src="logo/mustlogo.png" alt="MUST Logo" class="footer-logo">
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
    </script>
</body>

</html>