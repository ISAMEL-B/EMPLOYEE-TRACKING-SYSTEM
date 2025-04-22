<?php
session_start();
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

//get count of employees in a department
include '../../scoring_calculator/department score/department_employees.php';

//get department score
include '../../scoring_calculator/department score/department_score.php';

//get count of employees in a department
$department_counts = get_all_department_staff_counts($conn);

// Pagination parameters
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 10;
$total_items = count($department_counts);
$total_pages = ceil($total_items / $items_per_page);

// Validate current page
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

// Get only the items for the current page
$offset = ($current_page - 1) * $items_per_page;
$paginated_departments = array_slice($department_counts, $offset, $items_per_page, true);

//total staff in each department (keeping your existing variables)
$software_engineering = $department_counts['Software Engineering'] ?? 0;
$computer_science = $department_counts['Computer Science'] ?? 0;
$information_technology = $department_counts['Information Technology'] ?? 0;
$biology = $department_counts['Biology'] ?? 0;
$chemistry = $department_counts['Chemistry'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM - Department Analytics Dashboard</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <script src="../components/Chart.js/dist/Chart.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- link it to its css -->
    <link rel="stylesheet" href="styles/index3.css">
    
</head>

<body>
    <!-- Top Navigation Bar -->
    <?php include 'bars/nav_bar.php'; ?>

    <!-- Sidebar -->
    <?php include 'bars/side_bar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="department-title">Department Analytics Dashboard</h2>
                <div class="dropdown">
                    <button class="btn btn-must dropdown-toggle" type="button" id="departmentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Department
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="departmentDropdown">
                        <input type="text" class="form-control search-box mb-2" placeholder="Search departments..." id="departmentSearch">
                        <div id="departmentList">
                            <a class="dropdown-item" href="department.php" data-dept="it">Software Engineering</a>
                            <a class="dropdown-item" href="department.php" data-dept="finance">Computer Science</a>
                            <a class="dropdown-item" href="department.php" data-dept="hr">Civil Engineering</a>
                            <a class="dropdown-item" href="department.php" data-dept="academics">Electrical Engineering</a>
                            <a class="dropdown-item" href="department.php" data-dept="research">Accounting & Finance</a>
                            <a class="dropdown-item" href="department.php" data-dept="admin">Maths</a>
                            <a class="dropdown-item" href="department.php" data-dept="facilities">Phyics</a>
                            <a class="dropdown-item" href="department.php" data-dept="marketing">Civil & Building</a>
                            <a class="dropdown-item" href="department.php" data-dept="library">Petroleum Engineering</a>
                            <a class="dropdown-item" href="department.php" data-dept="health">Information Technology</a>
                            <a class="dropdown-item" href="department.php" data-dept="health">Biology</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Comparison Section -->
            <div class="row mb-4" id="comparisonSection">
                <div class="col-12">
                    <div class="card department-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Department Comparative Overview</span>
                            <small class="text-muted">Last updated: Monday, 10:45 AM</small>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Select a department from the dropdown above to view detailed analytics. Below is a comparison of all departments across key metrics.</p>

                            <div class="table-responsive">
                                <table class="table table-hover comparison-table">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Total Employees</th>
                                            <th>Total Publications</th>
                                            <th>Total Research Grant amount</th>
                                            <th>Total innovations</th>
                                            <th>Total Score</th>
                                            <!-- <th>Budget Utilization</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($paginated_departments as $dept => $count): 
                                         // Get department ID from department name
                                        $stmt = $conn->prepare("SELECT department_id FROM departments WHERE department_name = ?");
                                        $stmt->bind_param("s", $dept);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $department_id = $result->fetch_assoc()['department_id'] ?? null;

                                        // Now call your existing function dynamically
                                        $dept_data = $department_id ? get_department_performance($conn, $department_id) : [];

                                        // get the total score for the department
                                        $total_department_score = 
                                                $dept_data['academic_score'] +
                                                $dept_data['grant_score'] +
                                                $dept_data['innovation_score'] +
                                                $dept_data['publication_score'] +
                                                $dept_data['supervision_score'] +
                                                $dept_data['membership_score'] +
                                                $dept_data['community_service_score'] +
                                                $dept_data['other_academic_score'] +
                                                $dept_data['teaching_experience_score'] +
                                                $dept_data['university_service_score'];

                                    ?>

                                        <tr class="clickable-row" data-href="department.php?name=<?= urlencode($dept) ?>">
                                            <td>
                                                <strong>
                                                    <a href="department.php?name=<?= urlencode($dept) ?>" 
                                                    class="department-link"
                                                    title="View <?= htmlspecialchars($dept) ?> details">
                                                        <?= htmlspecialchars($dept) ?>
                                                    </a>
                                                </strong>
                                            </td>
                                            <td><?= htmlspecialchars($count) ?></td>
                                            <td><?= $dept_data['total_publications'] ?></td>
                                            <td><?= $dept_data['total_grant_amount'] ?></td>
                                            <td><?= $dept_data['total_innovations'] ?></td>
                                            <td><?= $total_department_score ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Controls -->
                            <?php if ($total_pages > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center mt-4">
                                    <?php if ($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $current_page - 1 ?>" aria-label="Previous">
                                                <span aria-hidden="true">«</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $current_page + 1 ?>" aria-label="Next">
                                                <span aria-hidden="true">»</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>