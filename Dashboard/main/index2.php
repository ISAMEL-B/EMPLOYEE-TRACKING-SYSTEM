<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

// Database connection and includes
include '../../scoring_calculator/faculty score/faculty_score.php'; // faculty performance
include '../../scoring_calculator/faculty score/faculty_employees.php'; // faculty employees

$faculties = [];
$sql = "SELECT faculty_id, faculty_name FROM faculties";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculties[] = $row;
    }
}

function get_faculty_performance_data($conn, $faculty_id) {
    $faculty_data = get_faculty_performance($conn, $faculty_id);
    
    $performance_data = [
        'total_employees' => getTotalEmployeesByFaculty($faculty_id, $conn),
        'total_publications' => 0,
        'total_grants' => 0,
        'total_innovations' => 0
    ];
    
    if ($faculty_data) {
        // Calculate total publications
        $performance_data['total_publications'] = 
            ($faculty_data['Journal Articles (First Author)'] ?? 0) +
            ($faculty_data['Journal Articles (Co-author)'] ?? 0) +
            ($faculty_data['Journal Articles (Corresponding Author)'] ?? 0) +
            ($faculty_data['Book with ISBN'] ?? 0) +
            ($faculty_data['Book Chapter'] ?? 0);
        
        // Calculate total research grants
        $performance_data['total_grants'] = $faculty_data['total_grant_amount'] ?? 0;
        
        // Calculate total innovations
        $performance_data['total_innovations'] = 
            ($faculty_data['Trademark'] ?? 0) +
            ($faculty_data['Patent'] ?? 0) +
            ($faculty_data['Utility Model'] ?? 0) +
            ($faculty_data['Copyright'] ?? 0) +
            ($faculty_data['Product'] ?? 0);
    }
    
    return $performance_data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM - Faculty Performance Scorecard</title>
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --must-green: #006633;
            --must-yellow: #FFCC00;
            --must-blue: #003366;
            --must-light-gray: #f8f9fa;
            --must-dark-gray: #495057;
        }
        
        .main-content { 
            margin-left: 250px; 
            margin-top: 5%; 
            padding: 20px; 
            background-color: var(--must-light-gray); 
            min-height: 100vh; 
        }
        
        .comparison-table th { 
            background-color: var(--must-green); 
            color: white; 
            position: sticky;
            top: 0;
        }
        
        .btn-must { 
            background-color: var(--must-green); 
            color: white; 
        }
        
        .btn-must:hover { 
            background-color: var(--must-blue); 
        }
        
        .dropdown-menu { 
            max-height: 300px; 
            overflow-y: auto; 
        }
        
        .faculty-id { 
            color: var(--must-dark-gray); 
            font-size: 0.9em; 
        }
        
        .number-cell { 
            text-align: right; 
            font-family: 'Courier New', monospace;
        }
        
        /* Enhanced Link Styling */
        .faculty-link {
            color: var(--must-green);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid transparent;
        }
        
        .faculty-link:hover {
            color: var(--must-blue);
            background-color: rgba(0, 102, 51, 0.08);
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 102, 51, 0.2);
        }
        
        .faculty-link:active {
            transform: translateY(0);
            box-shadow: none;
        }
        
        .faculty-link .link-icon {
            font-size: 0.85em;
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .faculty-link:hover .link-icon {
            opacity: 1;
            transform: translateX(2px);
        }
        
        /* Table enhancements */
        .comparison-table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .comparison-table tbody tr:hover {
            background-color: rgba(0, 102, 51, 0.05);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding-top: 80px;
            }
        }
    </style>
</head>
<body>
<?php include 'bars/nav_bar.php'; ?>
<?php include 'bars/side_bar.php'; ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Faculty Performance Scorecard</h2>
            <div class="dropdown">
                <button class="btn btn-must dropdown-toggle" type="button" id="facultyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-building"></i> Select Faculty
                </button>
                <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="facultyDropdown">
                    <input type="text" class="form-control search-box mb-2" placeholder="Search faculties..." id="facultySearch">
                    <div id="facultyList">
                        <?php foreach ($faculties as $faculty): ?>
                            <a class="dropdown-item d-flex justify-content-between align-items-center" href="faculty.php?faculty_id=<?= $faculty['faculty_id'] ?>">
                                <span>
                                    <span class="fw-bold"><?= htmlspecialchars($faculty['faculty_name']) ?></span>
                                </span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2"></i>Faculty Comparative Performance Overview</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover comparison-table mb-0">
                        <thead>
                            <tr>
                                <th>Faculty ID</th>
                                <th>Faculty Name</th>
                                <th class="number-cell">Employees</th>
                                <th class="number-cell">Publications</th>
                                <th class="number-cell">Research Grants</th>
                                <th class="number-cell">Innovations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faculties as $faculty): 
                                $performance_data = get_faculty_performance_data($conn, $faculty['faculty_id']);
                            ?>
                                <tr>
                                    <td class="text-muted"><?= $faculty['faculty_id'] ?></td>
                                    <td>
                                        <a href="faculty.php?faculty_id=<?= $faculty['faculty_id'] ?>" class="faculty-link">
                                            <?= htmlspecialchars($faculty['faculty_name']) ?>
                                            <i class="bi bi-box-arrow-up-right link-icon"></i>
                                        </a>
                                    </td>
                                    <td class="number-cell"><?= $performance_data['total_employees'] ?></td>
                                    <td class="number-cell"><?= $performance_data['total_publications'] ?></td>
                                    <td class="number-cell"><?= isset($performance_data['total_grants']) ? number_format($performance_data['total_grants']) . ' UGX' : '-' ?></td>
                                    <td class="number-cell"><?= $performance_data['total_innovations'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('facultySearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const items = document.querySelectorAll('#facultyList .dropdown-item');
        
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
        });
    });
    
    // Add animation to table rows
    document.querySelectorAll('.comparison-table tbody tr').forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        row.style.transition = `all 0.3s ease ${index * 0.05}s`;
        
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, 100);
    });
</script>
</body>
</html>