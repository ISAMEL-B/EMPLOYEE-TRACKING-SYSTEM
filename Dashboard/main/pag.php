<?php
session_start();
require_once 'head/approve/config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper function for performance badge color
function getPerformanceColor($score) {
    if ($score >= 80) return 'success';
    if ($score >= 60) return 'warning';
    return 'danger';
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    header('Content-Type: application/json');
    
    $delete_id = (int)$_POST['delete_id'];
    $password = $_POST['password'] ?? '';
    $current_user_id = $_SESSION['user_id'] ?? 0;
    
    try {
        // Verify password first
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $current_user_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit();
        }
        
        if (!password_verify($password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Incorrect password']);
            exit();
        }
        
        if ($delete_id > 0) {
            // Start transaction
            $conn->begin_transaction();
            
            // Delete from users table
            $delete_users = $conn->prepare("DELETE FROM users WHERE staff_id = ?");
            if (!$delete_users) {
                throw new Exception("Prepare users delete failed: " . $conn->error);
            }
            $delete_users->bind_param("i", $delete_id);
            if (!$delete_users->execute()) {
                throw new Exception("Users delete failed: " . $delete_users->error);
            }
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode(['success' => true]);
            exit();
        }
        
        echo json_encode(['success' => false, 'message' => 'Invalid staff ID']);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
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
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_term = $conn->real_escape_string(trim($_GET['search']));
    $search_condition = "WHERE (s.first_name LIKE '%$search_term%' OR s.last_name LIKE '%$search_term%' OR d.department_name LIKE '%$search_term%' OR r.role_name LIKE '%$search_term%')";
}

// Get total number of staff for pagination
$total_staff_query = $conn->query("SELECT COUNT(*) as total FROM staff s 
                                  JOIN departments d ON s.department_id = d.department_id 
                                  JOIN roles r ON s.role_id = r.role_id 
                                  $search_condition");
$total_staff = $total_staff_query->fetch_assoc()['total'];
$total_pages = ceil($total_staff / $records_per_page);

// Adjust current page if it's beyond total pages
if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
    $offset = ($current_page - 1) * $records_per_page;
}

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
$staff_list = $staff_query ? $staff_query->fetch_all(MYSQLI_ASSOC) : [];

// Get current page for active menu highlighting
$current_page_name = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/manage_staff.css">

</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Staff List</h2>

    <?php if (!empty($staff_list)) : ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Experience</th>
                    <th>Performance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff_list as $index => $staff): ?>
                    <tr>
                        <td><?= $offset + $index + 1 ?></td>
                        <td><img src="<?= htmlspecialchars($staff['photo_path']) ?>" width="50" height="50" class="rounded-circle"></td>
                        <td><?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?></td>
                        <td><?= htmlspecialchars($staff['department_name']) ?></td>
                        <td><?= htmlspecialchars($staff['role_name']) ?></td>
                        <td><?= htmlspecialchars($staff['years_of_experience']) ?> yrs</td>
                        <td>
                            <span class="badge bg-<?= getPerformanceColor($staff['performance_score']) ?>">
                                <?= htmlspecialchars($staff['performance_score']) ?>%
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
    <nav aria-label="Staff pagination" class="mt-3">
        <ul class="pagination justify-content-center">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?><?= !empty($search_term) ? '&search=' . urlencode($search_term) : '' ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);

            if ($start_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=1' . (!empty($search_term) ? '&search=' . urlencode($search_term) : '') . '">1</a></li>';
                if ($start_page > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            for ($i = $start_page; $i <= $end_page; $i++):
                $active = ($i == $current_page) ? 'active' : '';
                ?>
                <li class="page-item <?= $active ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= !empty($search_term) ? '&search=' . urlencode($search_term) : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($end_page < $total_pages): ?>
                <?php if ($end_page < $total_pages - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $total_pages ?><?= !empty($search_term) ? '&search=' . urlencode($search_term) : '' ?>"><?= $total_pages ?></a></li>
            <?php endif; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?><?= !empty($search_term) ? '&search=' . urlencode($search_term) : '' ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>


    <?php else: ?>
        <div class="alert alert-warning">No staff found.</div>
    <?php endif; ?>
</div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>