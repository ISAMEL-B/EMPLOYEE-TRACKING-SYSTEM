<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
  header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Tracking System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>

    .format-info {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin: 15px 0;
      border-left: 4px solid #0d6efd;
    }
    .format-info p {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .format-info ul {
      margin-bottom: 0;
    }
    .error {
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 5px;
    }
  </style>
</head>

<body>

  <?php
  include '../../bars/nav_bar.php';
  include '../../bars/side_bar.php';
  ?>

  <div class="content">
    <form id="uploadForm" action="process_csv.php" method="POST" enctype="multipart/form-data">
      <h2>Upload CSV File</h2>

      <?php
      // Notification display
      if (isset($_SESSION['notification'])) {
        $alertType = $_SESSION['notification']['type'] ?? 'info';
        $message = $_SESSION['notification']['message'] ?? '';
        echo '<div class="notification-toast">
                <div class="alert alert-' . htmlspecialchars($alertType) . ' alert-dismissible fade show" role="alert">
                  ' . htmlspecialchars($message) . '
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>';
        unset($_SESSION['notification']);
      }

      $userRole = $_SESSION['user_role'] ?? '';
      $tableOptions = [
        'roles' => 'Roles',
        'faculties' => 'Faculties',
        'departments' => 'Departments',
        'staff' => 'Staff',
        'publications' => 'Publications',
        'grants' => 'Grants',
        'supervision' => 'Supervision',
        'innovations' => 'Innovations',
        'academicactivities' => 'Academic Activities',
        'service' => 'Service',
        'communityservice' => 'Community Service',
        'professionalbodies' => 'Professional Bodies',
        'degrees' => 'Degrees',
      ];

      $allowedOptions = [];

      if ($userRole === 'hrm') {
        $allowedOptions = $tableOptions;
      } elseif ($userRole === 'hod') {
        $allowedOptions = [
          'departments' => 'Departments',
          'staff' => 'Staff',
          'publications' => 'Publications',
          'grants' => 'Grants',
          'supervision' => 'Supervision'
        ];
      } elseif ($userRole === 'grants') {
        $allowedOptions = [
          'grants' => 'Grants',
          'innovations' => 'Innovations',
          'academicactivities' => 'Academic Activities'
        ];
      } elseif ($userRole === 'ar') {
        $allowedOptions = [
          'service' => 'Service',
          'communityservice' => 'Community Service'
        ];
      } elseif ($userRole === 'dean') {
        $allowedOptions = [
          'faculties' => 'Faculties',
          'departments' => 'Departments',
          'professionalbodies' => 'Professional Bodies'
        ];
      }
      ?>

      <div class="mb-3">
        <label for="table_name" class="form-label">Select Table:</label>
        <select class="form-select" name="table_name" id="table_name" required>
          <option value="">-- Select Table --</option>
          <?php foreach ($allowedOptions as $value => $label): ?>
            <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
          <?php endforeach; ?>
        </select>
        <div id="tableError" class="error"></div>
      </div>

      <div id="formatInfo" class="format-info">
        <p>CSV format requirements:</p>
        <ul id="formatRequirements">
          <!-- Will be populated by JavaScript -->
        </ul>
      </div>

      <div class="mb-3">
        <label for="csv_file" class="form-label">Select CSV File:</label>
        <input class="form-control" type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <div id="fileError" class="error"></div>
      </div>

      <button type="submit" class="btn btn-primary">Upload CSV</button>
    </form>
  </div>

  <!-- CSV Validation Modal -->
  <div class="modal fade" id="csvValidationModal" tabindex="-1" aria-labelledby="csvValidationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="csvValidationModalLabel">CSV Format Mismatch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="validationMessage"></p>
          <div class="alert alert-info">
            <strong>Note:</strong> Proceeding may result in incomplete data being imported.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel Upload</button>
          <form id="forceUploadForm" method="post" action="process_csv.php" enctype="multipart/form-data">
            <input type="hidden" name="table_name" id="forceTableName">
            <input type="hidden" name="force_upload" value="1">
            <input type="hidden" name="csv_file_path" id="csvFilePath">
            <button type="submit" class="btn btn-primary">Continue Anyway</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');

    if (hamburger) {
      hamburger.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        if (window.innerWidth <= 992) {
          sidebar.classList.toggle('show');
        }
      });
    }

    // Format requirements for each table
    const formatRequirements = {
      'roles': ['role_name (string)'],
      'faculties': ['faculty_name (string)'],
      'departments': ['department_name (string)', 'faculty_name (string) or faculty_id (integer)'],
      'staff': [
        'first_name (string)', 
        'last_name (string)', 
        'scholar_type (string)',
        'role_name (string)', 
        'department_name (string)', 
        'years_of_experience (integer)',
        'performance_score (integer)'
      ],
      'publications': ['staff_id (integer)', 'publication_type (string)', 'role (string)'],
      'grants': ['staff_id (integer)', 'grant_amount (decimal)'],
      'supervision': ['staff_id (integer)', 'student_level (string)'],
      'innovations': ['staff_id (integer)', 'innovation_type (string)'],
      'academicactivities': ['staff_id (integer)', 'activity_type (string)'],
      'service': ['staff_id (integer)', 'service_type (string)'],
      'communityservice': ['staff_id (integer)', 'description (string)', 'beneficiaries (string)'],
      'professionalbodies': ['staff_id (integer)', 'body_name (string)'],
      'degrees': ['staff_id (integer)', 'degree_name (string)', 'degree_classification (string)'],
    };

    // Update format requirements when table selection changes
    document.getElementById('table_name').addEventListener('change', function() {
      const selectedTable = this.value;
      const requirementsList = document.getElementById('formatRequirements');

      requirementsList.innerHTML = '';

      if (selectedTable && formatRequirements[selectedTable]) {
        formatRequirements[selectedTable].forEach(item => {
          const li = document.createElement('li');
          li.textContent = item;
          requirementsList.appendChild(li);
        });
        document.getElementById('formatInfo').style.display = 'block';
      } else {
        document.getElementById('formatInfo').style.display = 'none';
      }
    });

    document.getElementById('uploadForm').addEventListener('submit', function(e) {
      const csvFile = document.getElementById('csv_file').files[0];
      const tableName = document.getElementById('table_name').value;

      document.getElementById('tableError').innerText = '';
      document.getElementById('fileError').innerText = '';

      if (!tableName) {
        document.getElementById('tableError').innerText = 'Please select a table.';
        e.preventDefault();
      }

      if (!csvFile) {
        document.getElementById('fileError').innerText = 'Please select a CSV file.';
        e.preventDefault();
      }

      if (csvFile) {
        const fileExtension = csvFile.name.split('.').pop().toLowerCase();
        if (fileExtension !== 'csv') {
          document.getElementById('fileError').innerText = 'Only CSV files are allowed.';
          e.preventDefault();
        }
      }
    });

    // Handle responsive sidebar
    function handleResponsiveSidebar() {
      if (window.innerWidth <= 992) {
        sidebar?.classList.add('collapsed');
        sidebar?.classList.remove('show');
      } else {
        sidebar?.classList.remove('collapsed');
        sidebar?.classList.remove('show');
      }
    }

    window.addEventListener('resize', handleResponsiveSidebar);
    handleResponsiveSidebar();

    document.addEventListener('click', function(event) {
      if (window.innerWidth <= 992) {
        const isClickInsideSidebar = sidebar?.contains(event.target);
        const isClickOnHamburger = hamburger?.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnHamburger) {
          sidebar?.classList.remove('show');
        }
      }
    });

    // Show modal if validation failed (from session)
    document.addEventListener('DOMContentLoaded', function() {
      const validationModal = new bootstrap.Modal(document.getElementById('csvValidationModal'));
      
      <?php if (isset($_SESSION['csv_validation'])): ?>
        const validationData = <?php echo json_encode($_SESSION['csv_validation']); ?>;
        
        if (validationData) {
          const message = document.getElementById('validationMessage');
          const forceTableName = document.getElementById('forceTableName');
          const csvFilePath = document.getElementById('csvFilePath');
          
          if (validationData.received < validationData.expected) {
            message.textContent = `The CSV file for ${validationData.table} has ${validationData.received} columns, but we expected ${validationData.expected}. Some data may be missing.`;
          } else {
            message.textContent = `The CSV file for ${validationData.table} has ${validationData.received} columns, but we expected ${validationData.expected}. Extra columns will be ignored.`;
          }
          
          forceTableName.value = validationData.table;
          csvFilePath.value = validationData.file_path;
          
          validationModal.show();
          
          // Clean up session data
          fetch('clean_validation.php')
            .then(() => {
              // Remove the validation data from session
              <?php unset($_SESSION['csv_validation']); ?>
            });
        }
      <?php endif; ?>

      // Auto-dismiss notifications after 5 seconds
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        setTimeout(() => {
          const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
          bsAlert.close();
        }, 1200000);
      });
    });
  </script>
</body>
</html>