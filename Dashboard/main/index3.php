<?php
session_start();
if ($_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}
// Database connection
$host = '127.0.0.1';
$db = 'hrm_db';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Pagination settings
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Fetch total number of staff
$totalStaff = $pdo->query("SELECT COUNT(*) FROM staff")->fetchColumn();
$totalPages = ceil($totalStaff / $perPage);

// Fetch paginated staff data with roles and departments
function fetchStaffData($pdo, $offset, $perPage) {
    $stmt = $pdo->prepare("
        SELECT s.staff_id, s.first_name, s.last_name, r.role_name, d.department_name
        FROM staff s
        LEFT JOIN roles r ON s.role_id = r.role_id
        LEFT JOIN departments d ON s.department_id = d.department_id
        ORDER BY d.department_name, r.role_name, s.last_name
        LIMIT :offset, :perPage
    ");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

$staffList = fetchStaffData($pdo, $offset, $perPage);

// Function to generate a consistent color from department name
function getDepartmentColor($deptName) {
    $colors = [
        'HR' => '#FFB703',
        'IT' => '#4CAF50',
        'Finance' => '#FFD166',
        'Marketing' => '#06D6A0',
        'Sales' => '#118AB2',
        'Operations' => '#073B4C',
        'Civil Engineering' => '#3A86FF',
        'Default' => '#6A4C93'
    ];
    
    return $colors[$deptName] ?? $colors['Default'];
}

// Generate secure incremental IDs
$secureIds = [];
$counter = 1;
foreach ($staffList as $staff) {
    $secureIds[$staff['staff_id']] = 'EMP' . str_pad($counter++, 5, '0', STR_PAD_LEFT);
}

// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
$user_role = $_SESSION['user_role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard | HRM System</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    
    <!-- local files -->
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="../components/my_css/index3.css">
    <!-- <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css"> -->


</head>
<body>
        <!--side bar  -->
        <?php include 'bars/nav_bar.php'; ?>
        <?php include 'bars/side_bar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="staff-dashboard">
            <div class="dashboard-header">
                <h1><i class="fas fa-users"></i> Staff Directory</h1>
                <p class="subtitle">Manage and view all staff members in MUST organization</p>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-label">Total Staff</div>
                    <div class="stat-value"><?= $totalStaff ?></div>
                    <div class="stat-change"><i class="fas fa-arrow-up text-success"></i> 12% from last month</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">Departments</div>
                    <div class="stat-value">
                        <?php 
                        $deptCount = $pdo->query("SELECT COUNT(DISTINCT department_id) FROM staff")->fetchColumn();
                        echo $deptCount;
                        ?>
                    </div>
                    <div class="stat-change">Active divisions</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-label">Roles</div>
                    <div class="stat-value">
                        <?php 
                        $roleCount = $pdo->query("SELECT COUNT(DISTINCT role_id) FROM staff")->fetchColumn();
                        echo $roleCount;
                        ?>
                    </div>
                    <div class="stat-change">Different positions</div>
                </div>
            </div>
            
            <div class="staff-table-container">
                <div class="search-filter">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search staff by name, ID, department or role...">
                    </div>
                    <select class="filter-dropdown" id="departmentFilter">
                        <option value="">All Departments</option>
                        <?php 
                        $depts = $pdo->query("SELECT DISTINCT department_name FROM departments ORDER BY department_name")->fetchAll();
                        foreach ($depts as $dept): 
                        ?>
                            <option value="<?= htmlspecialchars($dept['department_name']) ?>"
                                <?= (isset($_GET['dept']) && $_GET['dept'] == $dept['department_name']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['department_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Staff Member</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staffList as $staff): 
                            $deptColor = getDepartmentColor($staff['department_name']);
                        ?>
                            <tr>
                                <td><?= $secureIds[$staff['staff_id']] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($staff['first_name']) ?> <?= htmlspecialchars($staff['last_name']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($staff['role_name']) ?></td>
                                <td>
                                    <span class="department-badge" style="background-color: <?= $deptColor ?>">
                                        <?= htmlspecialchars($staff['department_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button style="background: none; border: none; color: #4CAF50; cursor: pointer;" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button style="background: none; border: none; color: #FFB703; cursor: pointer;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page-1 ?><?= isset($_GET['dept']) ? '&dept='.urlencode($_GET['dept']) : '' ?>">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    <?php else: ?>
                        <span class="disabled"><i class="fas fa-angle-left"></i></span>
                    <?php endif; ?>
                    
                    <?php 
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    
                    if ($startPage > 1) {
                        echo '<a href="?page=1'.(isset($_GET['dept']) ? '&dept='.urlencode($_GET['dept']) : '').'">1</a>';
                        if ($startPage > 2) echo '<span>...</span>';
                    }
                    
                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="?page=<?= $i ?><?= isset($_GET['dept']) ? '&dept='.urlencode($_GET['dept']) : '' ?>"
                           class="<?= $i == $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor;
                    
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) echo '<span>...</span>';
                        echo '<a href="?page='.$totalPages.(isset($_GET['dept']) ? '&dept='.urlencode($_GET['dept']) : '').'">'.$totalPages.'</a>';
                    }
                    ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page+1 ?><?= isset($_GET['dept']) ? '&dept='.urlencode($_GET['dept']) : '' ?>">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="disabled"><i class="fas fa-angle-right"></i></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle sidebar collapse
        document.getElementById('hamburger').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });
        
        // Enhanced search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const id = row.cells[0].textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();
                const role = row.cells[2].textContent.toLowerCase();
                const dept = row.cells[3].textContent.toLowerCase();
                
                const matches = id.includes(searchTerm) || 
                               name.includes(searchTerm) || 
                               role.includes(searchTerm) || 
                               dept.includes(searchTerm);
                
                row.style.display = matches ? '' : 'none';
            });
        });
        
        // Department filter functionality with page reload
        document.getElementById('departmentFilter').addEventListener('change', function(e) {
            const selectedDept = encodeURIComponent(e.target.value);
            if (selectedDept) {
                window.location.href = '?dept=' + selectedDept;
            } else {
                window.location.href = window.location.pathname;
            }
        });
    </script>
</body>
</html>