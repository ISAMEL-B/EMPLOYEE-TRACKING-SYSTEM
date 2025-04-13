<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Tracking System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="csv_receiver/css/style.css">
  <link rel="stylesheet" href="bars/nav_sidebar/nav_side_bar.css">
</head>

<body>

  <?php
  include 'bars/nav_sidebar/nav_bar.php';
  include 'bars/nav_sidebar/side_bar.php';
  ?>

  <div class="role-indicator">
    Logged in as: <?php echo strtoupper(htmlspecialchars($_SESSION['user_role'] ?? 'guest')); ?>
  </div>

  <div class="content">
    <form id="uploadForm" action="csv_receiver/process_csv.php" method="POST" enctype="multipart/form-data">
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
        'degrees' => 'Degrees',
        'departments' => 'Departments',
        'staff' => 'Staff',
        'publications' => 'Publications',
        'grants' => 'Grants',
        'supervision' => 'Supervision',
        'innovations' => 'Innovations',
        'academicactivities' => 'Academic Activities',
        'service' => 'Service',
        'communityservice' => 'Community Service',
        'faculty' => 'faculty',
        'professionalbodies' => 'Professional Bodies'
      ];

      $allowedOptions = [];

      if ($userRole === 'hrm') {
        $allowedOptions = array_slice($tableOptions, 0, 13);
      }
      if ($userRole === 'hod') {
        $allowedOptions = array_slice($tableOptions, 3, 6);
      }
      if ($userRole === 'grants') {
        $allowedOptions = array_slice($tableOptions, 6, 9);
      }
      if ($userRole === 'ar') {
        $allowedOptions = array_slice($tableOptions, 9, 11);
      }
      if ($userRole === 'dean') {
        $allowedOptions = array_slice($tableOptions, 10, 13);
      }
      ?>

      <select name="table_name" id="table_name">
        <option value="">Select Table</option>
        <?php foreach ($allowedOptions as $value => $label): ?>
          <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
        <?php endforeach; ?>
      </select>
      <div id="tableError" class="error"></div>

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
      hamburger.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        if (window.innerWidth <= 992) {
          sidebar.classList.toggle('show');
        }
      });
    }

    document.getElementById('uploadForm').addEventListener('submit', function (e) {
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

    setTimeout(closeAlert, 5000);

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
  </script>
  <script>
    document.addEventListener('click', function (event) {
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
