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

//Top teaching for insites on the downer cards
// Faculty performance
$facultyPerformance = [];
$facultyResult = $conn->query("
    SELECT 
        f.faculty_name,
        COUNT(DISTINCT p.publication_id) as publications,
        COUNT(DISTINCT g.grant_id) as grants,
        IFNULL(AVG(pm.metric_value), 0) as avg_performance,
        COUNT(DISTINCT s.staff_id) as staff_count
    FROM faculties f
    LEFT JOIN departments d ON f.faculty_id = d.faculty_id
    LEFT JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    LEFT JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    GROUP BY f.faculty_id
    ORDER BY publications DESC, grants DESC
");
while ($row = $facultyResult->fetch_assoc()) {
    $facultyPerformance[] = $row;
}
$topResearchFaculty = $facultyPerformance[0] ?? null;

$teachingResult = $conn->query("
    SELECT f.faculty_name
    FROM faculties f
    JOIN departments d ON f.faculty_id = d.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    JOIN performance_metrics pm ON s.staff_id = pm.staff_id
    WHERE pm.metric_name LIKE '%Student%Satisfaction%'
    GROUP BY f.faculty_id
    ORDER BY AVG(pm.metric_value) DESC
    LIMIT 1
");
$topTeachingFaculty = $teachingResult->fetch_assoc()['faculty_name'] ?? null;

$growthResult = $conn->query("
    SELECT f.faculty_name
    FROM faculties f
    JOIN departments d ON f.faculty_id = d.faculty_id
    JOIN staff s ON d.department_id = s.department_id
    LEFT JOIN publications p ON s.staff_id = p.staff_id
    LEFT JOIN grants g ON s.staff_id = g.staff_id
    GROUP BY f.faculty_id
    ORDER BY (COUNT(DISTINCT p.publication_id) + COUNT(DISTINCT g.grant_id)) / COUNT(DISTINCT s.staff_id) DESC
    LIMIT 1
");
$growthFaculty = $growthResult->fetch_assoc()['faculty_name'] ?? null;

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
            <!-- Key Insights and Action Items -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card highlight-yellow">
                        <div class="card-header " style="font-weight: bold; font-size: 20px;">Key Performance Insights</div>
                        <div class="card-body">
                            <?php if ($topResearchFaculty): ?>
                                <div class="alert alert-info">
                                    <strong> <?= htmlspecialchars($topResearchFaculty['faculty_name']) ?>:</strong>
                                    Leads with <b><?= $topResearchFaculty['publications'] ?></b> publication(s) and <b><?= $topResearchFaculty['grants'] ?></b> grant(s).
                                </div>
                            <?php endif; ?>

                            <?php if ($topTeachingFaculty): ?>
                                <div class="alert alert-warning">
                                    <strong> <?= htmlspecialchars($topTeachingFaculty) ?>:</strong>
                                    Highest student satisfaction (<?= round($facultyPerformance[array_search($topTeachingFaculty, array_column($facultyPerformance, 'faculty_name'))]['avg_performance'] ?? 0) ?>/100)
                                    but lower research output. Encourage research-teaching balance.
                                </div>
                            <?php endif; ?>

                            <?php if ($growthFaculty): ?>
                                <div class="alert alert-success">
                                    <strong> <?= htmlspecialchars($growthFaculty) ?>:</strong>
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
                                <?php if ($topTeachingFaculty): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Organize research methodology workshop for <?= htmlspecialchars($topTeachingFaculty) ?> faculty
                                        <span class="badge bg-must-green rounded-pill">High Priority</span>
                                    </li>
                                <?php endif; ?>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Develop interdisciplinary research grants program
                                    <span class="badge bg-must-green rounded-pill">High Priority</span>
                                </li>

                                <?php
                                // Find faculty with lowest performance (excluding those with 0 staff)
                                $lowestPerforming = null;
                                $minPerformance = PHP_FLOAT_MAX;

                                foreach ($facultyPerformance as $faculty) {
                                    if ($faculty['staff_count'] > 0 && $faculty['avg_performance'] < $minPerformance) {
                                        $minPerformance = $faculty['avg_performance'];
                                        $lowestPerforming = $faculty;
                                    }
                                }
                                ?>

                                <?php if ($lowestPerforming): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Review teaching loads in <?= htmlspecialchars($lowestPerforming['faculty_name']) ?>
                                        <span class="badge bg-warning rounded-pill">Medium Priority</span>
                                    </li>
                                <?php endif; ?>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Plan community engagement fair for all faculties
                                    <span class="badge bg-primary rounded-pill">Low Priority</span>
                                </li>
                            </ul>
                        </div>
                    </div>
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