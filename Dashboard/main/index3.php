<?php
session_start();
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Database connection
require_once 'head/approve/config.php';

//get count of employees in a department
include '../../scoring_calculator/department score/department_employees.php';

//get department score
include '../../scoring_calculator/department score/department_score.php';

//get count of employees in a department
$department_counts = get_all_department_staff_counts($conn);

// Get all departments for dropdown
$departments = [];
$stmt = $conn->query("SELECT department_id, department_name FROM departments");
while ($row = $stmt->fetch_assoc()) {
    $departments[$row['department_id']] = $row['department_name'];
}

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

//INSITES ON DEPARTMENT LEVEL

// Department performance
$departmentPerformance = [];
$deptResult = $conn->query("
    SELECT 
        d.department_name,
        f.faculty_name,
        COUNT(DISTINCT p.publication_id) as publications,
        COUNT(DISTINCT g.grant_id) as grants,
        IFNULL(AVG(pm.metric_value), 0) as avg_performance,
        COUNT(DISTINCT s.staff_id) as staff_count
    FROM departments d
    JOIN faculties f ON d.faculty_id = f.faculty_id
    LEFT JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    LEFT JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    GROUP BY d.department_id
    ORDER BY publications DESC, grants DESC
");
while ($row = $deptResult->fetch_assoc()) {
    $departmentPerformance[] = $row;
}
$topResearchDept = $departmentPerformance[0] ?? null;

// Top teaching department (by student satisfaction)
$teachingDeptResult = $conn->query("
    SELECT d.department_name, f.faculty_name
    FROM departments d
    JOIN faculties f ON d.faculty_id = f.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    WHERE pm.metric_name LIKE '%Student%Satisfaction%'
    GROUP BY d.department_id
    ORDER BY AVG(pm.metric_value) DESC
    LIMIT 1
");
$topTeachingDept = $teachingDeptResult->fetch_assoc() ?? null;

// Fastest growing department
$growthDeptResult = $conn->query("
    SELECT d.department_name, f.faculty_name
    FROM departments d
    JOIN faculties f ON d.faculty_id = f.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    GROUP BY d.department_id
    ORDER BY (COUNT(DISTINCT p.publication_id) + COUNT(DISTINCT g.grant_id)) / COUNT(DISTINCT s.staff_id) DESC
    LIMIT 1
");
$growthDept = $growthDeptResult->fetch_assoc() ?? null;

// Find department with lowest performance
$lowestPerformingDept = null;
$minDeptPerformance = PHP_FLOAT_MAX;
foreach ($departmentPerformance as $dept) {
    if ($dept['staff_count'] > 0 && $dept['avg_performance'] < $minDeptPerformance) {
        $minDeptPerformance = $dept['avg_performance'];
        $lowestPerformingDept = $dept;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM - Department Analytics Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <script src="../components/Chart.js/dist/Chart.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
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
                            <?php foreach ($departments as $id => $name): ?>
                                <a class="dropdown-item" href="department.php?id=<?= $id ?>"><?= htmlspecialchars($name) ?></a>
                            <?php endforeach; ?>
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
                            <small class="text-muted">Last updated: <?= date('l, F j, Y h:i A') ?></small>
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
                                                ($dept_data['academic_score'] ?? 0) +
                                                ($dept_data['grant_score'] ?? 0) +
                                                ($dept_data['innovation_score'] ?? 0) +
                                                ($dept_data['publication_score'] ?? 0) +
                                                ($dept_data['supervision_score'] ?? 0) +
                                                ($dept_data['membership_score'] ?? 0) +
                                                ($dept_data['community_service_score'] ?? 0) +
                                                ($dept_data['other_academic_score'] ?? 0) +
                                                ($dept_data['teaching_experience_score'] ?? 0) +
                                                ($dept_data['university_service_score'] ?? 0);
                                    ?>

                                        <tr class="clickable-row" data-href="department.php?id=<?= $department_id ?>">
                                            <td>
                                                <strong>
                                                    <a href="department.php?id=<?= $department_id ?>"    
                                                    class="department-link"
                                                    title="View <?= htmlspecialchars($dept) ?> details">
                                                        <?= htmlspecialchars($dept) ?>
                                                    </a>
                                                </strong>
                                            </td>
                                            <td><?= htmlspecialchars($count) ?></td>
                                            <td><?= $dept_data['total_publications'] ?? 0 ?></td>
                                            <td><?= $dept_data['total_grant_amount'] ?? 0 ?></td>
                                            <td><?= $dept_data['total_innovations'] ?? 0 ?></td>
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
            <!-- Key Insights and Action Items -->
<div class="row">
    <div class="col-md-6">
        <div class="card highlight-yellow">
            <div class="card-header" style="font-weight: bold; font-size: 20px;">Key Performance Insights</div>
            <div class="card-body">
                <?php if ($topResearchDept): ?>
                <div class="alert alert-info">
                    <strong><?= htmlspecialchars($topResearchDept['department_name']) ?> (<?= htmlspecialchars($topResearchDept['faculty_name']) ?>):</strong>
                    Leads with <b><?= $topResearchDept['publications'] ?></b> publication(s) and <b><?= $topResearchDept['grants'] ?></b> grant(s).
                    Consider sharing best practices with other departments.
                </div>
                <?php endif; ?>

                <?php if ($topTeachingDept): ?>
                <div class="alert alert-warning">
                    <strong><?= htmlspecialchars($topTeachingDept['department_name']) ?> (<?= htmlspecialchars($topTeachingDept['faculty_name']) ?>):</strong>
                    Highest student satisfaction (<?= round($departmentPerformance[array_search($topTeachingDept['department_name'], array_column($departmentPerformance, 'department_name'))]['avg_performance'] ?? 0) ?>/100).
                    Encourage research-teaching balance.
                </div>
                <?php endif; ?>

                <?php if ($growthDept): ?>
                <div class="alert alert-success">
                    <strong><?= htmlspecialchars($growthDept['department_name']) ?> (<?= htmlspecialchars($growthDept['faculty_name']) ?>):</strong>
                    Strong growth in both research and teaching. Model for interdisciplinary collaboration.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card highlight-blue">
            <div class="card-header" style="font-weight: bold; font-size: 18px;">Strategic Action Items</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php if ($topTeachingDept): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Organize research methodology workshop for <?= htmlspecialchars($topTeachingDept['department_name']) ?>
                        <span class="badge bg-must-green rounded-pill">High Priority</span>
                    </li>
                    <?php endif; ?>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Develop interdisciplinary research grants program
                        <span class="badge bg-must-green rounded-pill">High Priority</span>
                    </li>

                    <?php if ($lowestPerformingDept): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Review teaching loads in <?= htmlspecialchars($lowestPerformingDept['department_name']) ?>
                        <span class="badge bg-warning rounded-pill">Medium Priority</span>
                    </li>
                    <?php endif; ?>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Plan department-level community engagement activities
                        <span class="badge bg-primary rounded-pill">Low Priority</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department search functionality
        const searchBox = document.getElementById('departmentSearch');
        const departmentList = document.getElementById('departmentList');
        const items = departmentList.getElementsByTagName('a');
        
        searchBox.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            Array.from(items).forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Make table rows clickable
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });
    </script>
</body>
</html>