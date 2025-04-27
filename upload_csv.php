<?php
session_start();

// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hrm_db');

// MUST color scheme
define('PRIMARY_COLOR', '#0056b3'); // Blue
define('SECONDARY_COLOR', '#ffc107'); // Yellow
define('ACCENT_COLOR', '#28a745'); // Green

// Create database connection
function getDBConnection()
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Handle file upload and processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_csv'])) {
    $table_name = $_POST['table_name'];
    $csv_file = $_FILES['csv_file'];

    // Validate file
    if ($csv_file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'File upload error: ' . $csv_file['error']];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Check file extension
    $file_ext = strtolower(pathinfo($csv_file['name'], PATHINFO_EXTENSION));
    if ($file_ext !== 'csv') {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Only CSV files are allowed.'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Process the CSV file
    try {
        $conn = getDBConnection();
        $file_path = $csv_file['tmp_name'];

        // Get table columns and foreign keys
        $table_info = getTableInfo($conn, $table_name);

        // Process CSV data
        $result = processCSV($conn, $table_name, $file_path, $table_info);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "CSV processed successfully. {$result['inserted']} records inserted. " .
                ($result['skipped'] > 0 ? "{$result['skipped']} records skipped due to errors." : "")
        ];

        // Log the upload for approval if needed
        logCSVUpload($conn, $table_name, $file_path, $result['inserted']);
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error: ' . $e->getMessage()];
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

/**
 * Get table structure information including foreign keys
 */
function getTableInfo($conn, $table_name)
{
    $info = [
        'columns' => [],
        'foreign_keys' => []
    ];

    // Get column information
    $result = $conn->query("DESCRIBE `$table_name`");
    while ($row = $result->fetch_assoc()) {
        $info['columns'][$row['Field']] = $row;
    }

    // Get foreign key information (this is a simplified approach)
    // In a real application, you might query INFORMATION_SCHEMA for precise FK data
    if ($table_name === 'departments') {
        $info['foreign_keys']['faculty_id'] = [
            'reference_table' => 'faculties',
            'reference_column' => 'faculty_id',
            'display_column' => 'faculty_name'
        ];
    } elseif ($table_name === 'staff') {
        $info['foreign_keys']['role_id'] = [
            'reference_table' => 'roles',
            'reference_column' => 'role_id',
            'display_column' => 'role_name'
        ];
        $info['foreign_keys']['department_id'] = [
            'reference_table' => 'departments',
            'reference_column' => 'department_id',
            'display_column' => 'department_name'
        ];
    } elseif ($table_name === 'performance_metrics') {
        $info['foreign_keys']['staff_id'] = [
            'reference_table' => 'staff',
            'reference_column' => 'staff_id',
            'display_column' => 'CONCAT(first_name, " ", last_name)'
        ];
        $info['foreign_keys']['department_id'] = [
            'reference_table' => 'departments',
            'reference_column' => 'department_id',
            'display_column' => 'department_name'
        ];
    }
    // Add more tables as needed

    return $info;
}

/**
 * Process CSV file and insert data into database
 */
function processCSV($conn, $table_name, $file_path, $table_info)
{
    $result = [
        'inserted' => 0,
        'skipped' => 0,
        'errors' => []
    ];

    // Open CSV file
    if (($handle = fopen($file_path, "r")) === FALSE) {
        throw new Exception("Could not open CSV file.");
    }

    // Get headers
    $headers = fgetcsv($handle);
    if ($headers === FALSE) {
        fclose($handle);
        throw new Exception("Empty CSV file or invalid format.");
    }

    // Prepare column mapping (case-insensitive)
    $column_map = [];
    foreach ($headers as $index => $header) {
        $normalized_header = strtolower(trim($header));
        foreach ($table_info['columns'] as $col_name => $col_info) {
            if (strtolower($col_name) === $normalized_header) {
                $column_map[$index] = $col_name;
                break;
            }
        }
    }

    // Check if we have any valid columns
    if (empty($column_map)) {
        fclose($handle);
        throw new Exception("No matching columns found between CSV and database table.");
    }

    // Prepare data for insertion
    $conn->autocommit(FALSE); // Start transaction

    try {
        $row_num = 1; // Start at 1 because we've already read the header
        while (($data = fgetcsv($handle)) !== FALSE) {
            $row_num++;
            $row_data = [];
            $errors = [];

            // Process each column
            foreach ($column_map as $index => $col_name) {
                if (!isset($data[$index])) {
                    $row_data[$col_name] = NULL;
                    continue;
                }

                $value = trim($data[$index]);

                // Handle foreign keys
                if (isset($table_info['foreign_keys'][$col_name])) {
                    $fk_info = $table_info['foreign_keys'][$col_name];

                    // Try to find the referenced record
                    $ref_value = findReferenceValue(
                        $conn,
                        $fk_info['reference_table'],
                        $fk_info['reference_column'],
                        $fk_info['display_column'],
                        $value
                    );

                    if ($ref_value === NULL) {
                        $errors[] = "Could not find matching {$fk_info['reference_table']} for '{$value}'";
                        $row_data[$col_name] = NULL;
                    } else {
                        $row_data[$col_name] = $ref_value;
                    }
                } else {
                    // Handle regular columns
                    $row_data[$col_name] = $value === '' ? NULL : $value;
                }
            }

            // Skip row if there are errors
            if (!empty($errors)) {
                $result['skipped']++;
                $result['errors'][$row_num] = $errors;
                continue;
            }

            // Build and execute INSERT query
            $columns = implode('`, `', array_keys($row_data));
            $placeholders = implode(', ', array_fill(0, count($row_data), '?'));
            $types = str_repeat('s', count($row_data)); // All as strings for simplicity

            $stmt = $conn->prepare("INSERT INTO `$table_name` (`$columns`) VALUES ($placeholders)");
            if ($stmt === FALSE) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param($types, ...array_values($row_data));
            if (!$stmt->execute()) {
                $errors[] = "Database error: " . $stmt->error;
                $result['skipped']++;
                $result['errors'][$row_num] = $errors;
                $stmt->close();
                continue;
            }

            $stmt->close();
            $result['inserted']++;
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        fclose($handle);
        throw $e;
    }

    fclose($handle);
    return $result;
}

/**
 * Find referenced value for foreign key
 */
function findReferenceValue($conn, $table, $id_column, $display_column, $value)
{
    // First try to find by ID if the value is numeric
    if (is_numeric($value)) {
        $stmt = $conn->prepare("SELECT $id_column FROM `$table` WHERE $id_column = ?");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row[$id_column];
        }
    }

    // Then try to find by display column
    $query = "SELECT $id_column FROM `$table` WHERE $display_column = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $value);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row[$id_column];
    }

    return NULL;
}

/**
 * Log CSV upload for approval tracking
 */
function logCSVUpload($conn, $table_name, $file_path, $record_count)
{
    $temp_file = tempnam(sys_get_temp_dir(), 'hrm_csv_');
    copy($file_path, $temp_file);

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    $stmt = $conn->prepare("INSERT INTO csv_approvals 
        (table_name, file_path, submitted_by, record_count, status) 
        VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param('ssii', $table_name, $temp_file, $user_id, $record_count);
    $stmt->execute();
    $stmt->close();
}

// Get list of tables for the dropdown
$tables = [
    'faculties' => 'Faculties',
    'departments' => 'Departments',
    'roles' => 'Roles',
    'staff' => 'Staff',
    'academicactivities' => 'Academic Activities',
    'communityservice' => 'Community Service',
    'degrees' => 'Degrees',
    'grants' => 'Grants',
    'innovations' => 'Innovations',
    'performance_metrics' => 'Performance Metrics',
    'professionalbodies' => 'Professional Bodies',
    'publications' => 'Publications',
    'service' => 'Service',
    'supervision' => 'Supervision'
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM CSV Upload System | MUST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: <?php echo PRIMARY_COLOR; ?>;
            --secondary-color: <?php echo SECONDARY_COLOR; ?>;
            --accent-color: <?php echo ACCENT_COLOR; ?>;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: var(--primary-color);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            border-radius: 5px;
            padding: 10px 15px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: var(--primary-color);
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #004494;
            border-color: #004494;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: #212529;
        }

        .btn-secondary:hover {
            background-color: #e0a800;
            border-color: #e0a800;
            color: #212529;
        }

        .btn-success {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .alert {
            border-radius: 8px;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            max-width: 180px;
            height: auto;
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-info small {
            display: block;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-none d-md-block">
            <div class="logo-container">
                <img src="https://www.must.ac.ug/wp-content/uploads/2022/08/cropped-must-logo-1-192x192.png" alt="MUST Logo" class="logo">
                <h4 class="mt-3">HRM System</h4>
            </div>

            <div class="user-profile">
                <img src="https://via.placeholder.com/40" alt="User">
                <div class="user-info">
                    <strong>Admin User</strong>
                    <small>HR Manager</small>
                </div>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-file-upload"></i> CSV Upload
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-users"></i> Staff Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-line"></i> Performance Metrics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-graduation-cap"></i> Academic Records
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-book"></i> Publications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content w-100">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light mb-4">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-home"></i></a>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> Admin
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                <h2 class="mb-4">CSV Data Upload</h2>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show">
                        <?php echo $_SESSION['message']['text']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Upload CSV File</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="table_name" class="form-label">Select Table</label>
                                        <select class="form-select" id="table_name" name="table_name" required>
                                            <option value="">-- Select Table --</option>
                                            <?php foreach ($tables as $value => $label): ?>
                                                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="csv_file" class="form-label">CSV File</label>
                                        <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                                        <div class="form-text">Please upload a CSV file with headers matching the database columns.</div>
                                    </div>

                                    <button type="submit" name="upload_csv" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Upload CSV
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-success">
                                <h5 class="card-title mb-0">Upload Instructions</h5>
                            </div>
                            <div class="card-body">
                                <h6>Before Uploading:</h6>
                                <ol class="small">
                                    <li>Ensure your CSV file has headers matching the database columns</li>
                                    <li>For foreign keys, you can use either the ID or the display name</li>
                                    <li>Date fields should be in YYYY-MM-DD format</li>
                                    <li>Empty values will be treated as NULL</li>
                                </ol>

                                <h6 class="mt-3">Table Relationships:</h6>
                                <ul class="small">
                                    <li><strong>Departments</strong> require valid Faculty IDs/names</li>
                                    <li><strong>Staff</strong> require valid Role IDs/names and Department IDs/names</li>
                                    <li><strong>Performance Metrics</strong> require valid Staff IDs/names</li>
                                </ul>

                                <div class="alert alert-warning mt-3 small">
                                    <i class="fas fa-exclamation-triangle"></i> Large files may take time to process. Please be patient.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Uploads</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Table</th>
                                        <th>Uploaded At</th>
                                        <th>Records</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conn = getDBConnection();
                                    $query = "SELECT * FROM csv_approvals ORDER BY submitted_at DESC LIMIT 5";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$tables[$row['table_name']]}</td>
                                                <td>" . date('M d, Y H:i', strtotime($row['submitted_at'])) . "</td>
                                                <td>{$row['record_count']}</td>
                                                <td><span class='badge bg-" . ($row['status'] == 'approved' ? 'success' : ($row['status'] == 'rejected' ? 'danger' : 'warning')) . "'>{$row['status']}</span></td>
                                                <td>
                                                    <button class='btn btn-sm btn-outline-primary'><i class='fas fa-eye'></i></button>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No recent uploads found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('csv_file');
            const tableSelect = document.getElementById('table_name');

            if (tableSelect.value === '') {
                alert('Please select a table');
                e.preventDefault();
                return;
            }

            if (fileInput.files.length === 0) {
                alert('Please select a CSV file');
                e.preventDefault();
                return;
            }

            const file = fileInput.files[0];
            if (file.type !== 'text/csv' && !file.name.toLowerCase().endsWith('.csv')) {
                alert('Please upload a valid CSV file');
                e.preventDefault();
            }
        });
    </script>
</body>

</html>