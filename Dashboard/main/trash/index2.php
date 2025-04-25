<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hrm') {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}

//include faculty performance 
include '../../scoring_calculator/faculty score/faculty_score.php';

$faculties = [];
$sql = "SELECT faculty_id, faculty_name FROM faculties";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculties[] = $row;
    }
}

function getTotalEmployees($conn, $faculty_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM employees WHERE faculty_id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}

function getTotalPublications($conn, $faculty_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM publications WHERE faculty_id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}

function getTotalGrants($conn, $faculty_id) {
    $stmt = $conn->prepare("SELECT SUM(amount) AS total FROM grants WHERE faculty_id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return number_format($result['total'] ?? 0) . ' UGX';
}

function getTotalInnovations($conn, $faculty_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM innovations WHERE faculty_id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}
?>

<!-- HTML Starts -->
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
        }
        .main-content { margin-left: 250px; margin-top: 5%; padding: 20px; background-color: #f8f9fa; min-height: 100vh; }
        .comparison-table th { background-color: var(--must-green); color: white; }
        .btn-must { background-color: var(--must-green); color: white; }
        .btn-must:hover { background-color: var(--must-blue); }
        .dropdown-menu { max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
<?php include 'bars/nav_bar.php'; ?>
<?php include 'bars/side_bar.php'; ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Faculty Performance Scorecard</h2>
            <div class="dropdown">
                <button class="btn btn-must dropdown-toggle" type="button" id="facultyDropdown" data-bs-toggle="dropdown">
                    Select Faculty
                </button>
                <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="facultyDropdown">
                    <input type="text" class="form-control search-box mb-2" placeholder="Search faculties..." id="facultySearch">
                    <div id="facultyList">
                        <?php foreach ($faculties as $faculty): ?>
                            <a class="dropdown-item" href="faculty.php?faculty_id=<?= $faculty['faculty_id'] ?>">
                                <?= htmlspecialchars($faculty['faculty_name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Faculty Comparative Performance Overview</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover comparison-table">
                        <thead>
                            <tr>
                                <th>Faculty</th>
                                <th>Total Employees</th>
                                <th>Total Publications</th>
                                <th>Total Research Grant Amount</th>
                                <th>Total Innovations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faculties as $faculty): ?>
                                <tr>
                                    <td>
                                        <a href="faculty.php?faculty_id=<?= $faculty['faculty_id'] ?>">
                                            <strong><?= htmlspecialchars($faculty['faculty_name']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= getTotalEmployees($conn, $faculty['faculty_id']) ?></td>
                                    <td><?= getTotalPublications($conn, $faculty['faculty_id']) ?></td>
                                    <td><?= getTotalGrants($conn, $faculty['faculty_id']) ?></td>
                                    <td><?= getTotalInnovations($conn, $faculty['faculty_id']) ?></td>
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
            item.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });
</script>
</body>
</html>
