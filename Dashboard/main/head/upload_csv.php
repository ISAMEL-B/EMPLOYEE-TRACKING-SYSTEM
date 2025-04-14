<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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
  <link rel="stylesheet" href="../csv_receiver/css/style.css">
</head>

<body>

  <?php
  include '../bars/nav_bar.php';
  include '../bars/side_bar.php';
  ?>

  <div class="content">
    <form id="uploadForm" action="../csv_receiver/process_csv.php" method="POST" enctype="multipart/form-data">
      <h2>Upload CSV File</h2>

      <?php
      if (isset($_SESSION['notification'])) {
        echo '<div id="notification" class="alert">' . htmlspecialchars($_SESSION['notification']) .
          '<span class="close-btn" onclick="closeAlert()">&times;</span></div>';
        unset($_SESSION['notification']);
      }

      $userRole = $_SESSION['user_role'] ?? '';
      $tableOptions = [
        'roles' => 'Roles',
        'faculties' => 'Faculties', // Changed from 'faculty' to 'faculties' to match backend
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
        // 'users' => 'Users'
      ];

      $allowedOptions = [];

      if ($userRole === 'hrm') {
        $allowedOptions = $tableOptions; // HRM should have access to all tables
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

      <select name="table_name" id="table_name">
        <option value="">Select Table</option>
        <?php foreach ($allowedOptions as $value => $label): ?>
          <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
        <?php endforeach; ?>
      </select>
      <div id="tableError" class="error"></div>

      <div id="formatInfo" class="format-info">
        <p>CSV format requirements:</p>
        <ul id="formatRequirements">
          <!-- Will be populated by JavaScript -->
        </ul>
      </div>

      <label for="csv_file">Select CSV File:</label>
      <input type="file" name="csv_file" id="csv_file" accept=".csv">
      <div id="fileError" class="error"></div>

      <input type="submit" value="Upload CSV">
    </form>
  </div>

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
      'roles': ['role_id (integer)', 'role_name (string)'],
      'faculties': ['faculty_id (integer)', 'faculty_name (string)'],
      'departments': ['department_id (integer)', 'department_name (string)', 'faculty_name (string) or faculty_id (integer)'],
      'staff': ['staff_id (integer)', 'first_name (string)', 'last_name (string)', 'scholar_type (string)',
        'role_name (string)', 'department_name (string)', 'years_of_experience (integer)',
        'performance_score (integer)'
      ],
      'publications': ['publication_id (integer)', 'staff_id (integer)', 'publication_type (string)', 'role (string)'],
      'grants': ['grant_id (integer)', 'staff_id (integer)', 'grant_amount (decimal)'],
      'supervision': ['supervision_id (integer)', 'staff_id (integer)', 'student_level (string)'],
      'innovations': ['innovation_id (integer)', 'staff_id (integer)', 'innovation_type (string)'],
      'academicactivities': ['activity_id (integer)', 'staff_id (integer)', 'activity_type (string)'],
      'service': ['community_service_id (integer)', 'staff_id (integer)', 'service_type (string)'],
      'communityservice': ['community_service_id (integer)', 'staff_id (integer)', 'description (string)'],
      'professionalbodies': ['professional_body_id (integer)', 'staff_id (integer)', 'body_name (string)'],
      'degrees': ['degree_id (integer)', 'staff_id (integer)', 'degree_name (string)', 'degree_classification (string)'],
      // 'users': ['user_id (integer)', 'employee_id (string)', 'email (string)', 'passkey (string)']
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

    function closeAlert() {
      const notification = document.getElementById('notification');
      if (notification) {
        notification.style.display = 'none';
      }
    }

    setTimeout(closeAlert, 20000);

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
      // Check if screen is small
      if (window.innerWidth <= 992) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnHamburger = hamburger.contains(event.target);

        if (!isClickInsideSidebar && !isClickOnHamburger) {
          sidebar.classList.remove('show');
        }
      }
    });
  </script>
</body>

</html>