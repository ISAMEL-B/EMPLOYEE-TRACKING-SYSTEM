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
  <link rel="stylesheet" href="../csv_receiver/css/style.css">
  <style>
    /* Notification alert styles - centered */
    .alert-container {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1050;
      width: 80%;
      max-width: 600px;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      cursor: pointer;
      color: #6c757d;
    }

    .close-btn:hover {
      color: #343a40;
    }

    /* Modal styles */
    .modal {
      margin-left: 30%;
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      overflow: auto;
      /* Allow scrolling if modal itself becomes too tall */
      padding: 20px;
      /* Optional padding around modal content */
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 8px;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      max-height: 90vh;
      /* Important: limit the height */
      overflow-y: auto;
      /* Make content inside scrollable if needed */
    }

    .modal-buttons {
      margin-top: 20px;
      text-align: right;
    }

    .modal-buttons button {
      margin-left: 10px;
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 500;
    }

    .proceed-btn {
      background-color: #4CAF50;
      color: white;
    }

    .proceed-btn:hover {
      background-color: #45a049;
    }

    .cancel-btn {
      background-color: #f44336;
      color: white;
    }

    .cancel-btn:hover {
      background-color: #d32f2f;
    }

    /* Table styles */
    .column-mismatch-table {
      width: 100%;
      border-collapse: collapse;
      margin: 10px 0;
      font-size: 14px;
    }

    .column-mismatch-table th,
    .column-mismatch-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .column-mismatch-table th {
      background-color: #f2f2f2;
      font-weight: 600;
    }

    .match {
      background-color: #e6ffe6;
    }

    .mismatch {
      background-color: #ffe6e6;
    }

    .id-column {
      background-color: #f0f0f0;
      font-style: italic;
      color: #6c757d;
    }

    /* Content styles */
    .content {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Form styles */
    #uploadForm {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    #uploadForm select,
    #uploadForm input[type="file"] {
      padding: 8px;
      border: 1px solid #ced4da;
      border-radius: 4px;
    }

    #uploadForm input[type="submit"] {
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }

    #uploadForm input[type="submit"]:hover {
      background-color: #0069d9;
    }

    .error {
      color: #dc3545;
      font-size: 14px;
      margin-top: -10px;
    }

    .format-info {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      border: 1px solid #e9ecef;
    }

    .format-info ul {
      margin: 10px 0 0 20px;
      padding: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .content {
        margin: 10px;
        padding: 15px;
      }

      .modal-content {
        width: 90%;
        margin: 20% auto;
      }

      .alert-container {
        width: 90%;
      }
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
      if (isset($_SESSION['notification'])) {
        $notification = is_array($_SESSION['notification'])
          ? implode('<br>', array_map('htmlspecialchars', $_SESSION['notification']))
          : htmlspecialchars($_SESSION['notification']);

        echo '<div class="alert-container"><div class="alert">' .
          $notification .
          '<span class="close-btn" onclick="closeAlert()">&times;</span></div></div>';
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

      <select name="table_name" id="table_name" required>
        <option value="">Select Table</option>
        <?php foreach ($allowedOptions as $value => $label): ?>
          <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
        <?php endforeach; ?>
      </select>
      <div id="tableError" class="error"></div>

      <div id="formatInfo" class="format-info">
        <p>CSV format requirements (ID columns are optional and will be auto-generated if omitted):</p>
        <ul id="formatRequirements">
          <!-- Will be populated by JavaScript -->
        </ul>
      </div>

      <label for="csv_file">Select CSV File:</label>
      <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
      <div id="fileError" class="error"></div>

      <input type="hidden" name="user_decision" id="user_decision" value="abort">
      <input type="submit" value="Upload CSV">
    </form>
  </div>

  <!-- Column Mismatch Modal -->
  <div id="columnMismatchModal" class="modal">
    <div class="modal-content">
      <h2>Column Mismatch Detected</h2>
      <div id="mismatchDetails"></div>
      <div class="modal-buttons">
        <button class="proceed-btn" onclick="proceedWithUpload()">Proceed Anyway</button>
        <button class="cancel-btn" onclick="cancelUpload()">Cancel Upload</button>
      </div>
    </div>
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

    // Format requirements for each table (excluding auto-increment IDs)
    const formatRequirements = {
      'roles': ['role_name (string)'],
      'faculties': ['faculty_name (string)'],
      'departments': ['department_name (string)', 'faculty_id (integer) or faculty_name (string)'],
      'staff': ['first_name (string)', 'last_name (string)', 'scholar_type (string)',
        'role_id (integer) or role_name (string)', 'department_id (integer) or department_name (string)',
        'years_of_experience (integer)', 'performance_score (integer)'
      ],
      'publications': ['staff_id (integer)', 'publication_type (string)', 'role (string)'],
      'grants': ['staff_id (integer)', 'grant_amount (decimal)'],
      'supervision': ['staff_id (integer)', 'student_level (string)'],
      'innovations': ['staff_id (integer)', 'innovation_type (string)'],
      'academicactivities': ['staff_id (integer)', 'activity_type (string)'],
      'service': ['staff_id (integer)', 'service_type (string)'],
      'communityservice': ['staff_id (integer)', 'description (string)', 'beneficiaries (string)'],
      'professionalbodies': ['staff_id (integer)', 'body_name (string)'],
      'degrees': ['staff_id (integer)', 'degree_name (string)', 'degree_classification (string)']
    };

    // Full column names including IDs (for reference)
    const fullColumnNames = {
      'roles': ['role_id (auto)', 'role_name (string)'],
      'faculties': ['faculty_id (auto)', 'faculty_name (string)'],
      'departments': ['department_id (auto)', 'department_name (string)', 'faculty_id (integer)'],
      'staff': ['staff_id (auto)', 'first_name (string)', 'last_name (string)', 'scholar_type (string)',
        'role_id (integer)', 'department_id (integer)', 'years_of_experience (integer)', 'performance_score (integer)'
      ],
      'publications': ['publication_id (auto)', 'staff_id (integer)', 'publication_type (string)', 'role (string)'],
      'grants': ['grant_id (auto)', 'staff_id (integer)', 'grant_amount (decimal)'],
      'supervision': ['supervision_id (auto)', 'staff_id (integer)', 'student_level (string)'],
      'innovations': ['innovation_id (auto)', 'staff_id (integer)', 'innovation_type (string)'],
      'academicactivities': ['activity_id (auto)', 'staff_id (integer)', 'activity_type (string)'],
      'service': ['service_id (auto)', 'staff_id (integer)', 'service_type (string)'],
      'communityservice': ['community_service_id (auto)', 'staff_id (integer)', 'description (string)', 'beneficiaries (string)'],
      'professionalbodies': ['professional_body_id (auto)', 'staff_id (integer)', 'body_name (string)'],
      'degrees': ['degree_id (auto)', 'staff_id (integer)', 'degree_name (string)', 'degree_classification (string)']
    };

    // Update format requirements when table selection changes
    document.getElementById('table_name').addEventListener('change', function() {
      const selectedTable = this.value;
      const requirementsList = document.getElementById('formatRequirements');

      requirementsList.innerHTML = '';

      if (selectedTable && formatRequirements[selectedTable]) {
        // Add note about optional ID column
        const noteItem = document.createElement('li');
        noteItem.textContent = "Note: ID column is optional and will be auto-generated if omitted";
        noteItem.style.fontStyle = 'italic';
        noteItem.style.color = '#666';
        requirementsList.appendChild(noteItem);

        // Add actual requirements
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

    // Check for column mismatch on page load
    document.addEventListener('DOMContentLoaded', function() {
      <?php if (isset($_SESSION['column_mismatch'])): ?>
        showColumnMismatchModal(
          '<?php echo $_SESSION["column_mismatch"]["table"]; ?>',
          <?php echo $_SESSION["column_mismatch"]["expected"]; ?>,
          <?php echo $_SESSION["column_mismatch"]["received"]; ?>,
          <?php echo json_encode($_SESSION["column_mismatch"]["required_columns"]); ?>,
          <?php echo json_encode($_SESSION["column_mismatch"]["actual_columns"]); ?>,
          <?php echo $_SESSION["column_mismatch"]["has_id_column"] ? 'true' : 'false'; ?>
        );
        <?php unset($_SESSION['column_mismatch']); ?>
      <?php endif; ?>
    });

    function showColumnMismatchModal(table, expected, received, requiredColumns, actualColumns, hasIdColumn) {
      const modal = document.getElementById('columnMismatchModal');
      const details = document.getElementById('mismatchDetails');

      // Adjust expected count if CSV includes ID column
      const adjustedExpected = hasIdColumn ? expected + 1 : expected;

      let html = `
        <p>The CSV file you uploaded for table "${table}" doesn't match the expected structure.</p>
        <p><strong>Expected columns:</strong></p>
        <ul>
          <li style="font-style: italic; color: #666;">ID column is optional (will be auto-generated)</li>
      `;

      requiredColumns.forEach(col => {
        html += `<li>${col}</li>`;
      });

      html += `</ul>`;

      if (received < adjustedExpected) {
        html += `<p class="warning"><strong>Warning:</strong> Your file has fewer columns than expected. Some data might be missing.</p>`;
      } else if (received > adjustedExpected) {
        html += `<p class="warning"><strong>Note:</strong> Your file has more columns than expected. Only the first ${adjustedExpected} columns will be used.</p>`;
      }

      // Add comparison table
      html += `
        <p><strong>Column Comparison:</strong></p>
        <table class="column-mismatch-table">
          <tr>
            <th>Position</th>
            <th>Expected Column</th>
            <th>Your CSV Column</th>
            <th>Status</th>
          </tr>
      `;

      // Check if first column is an ID column
      const isFirstColumnId = actualColumns.length > 0 && actualColumns[0].toLowerCase().includes('id');

      for (let i = 0; i < Math.max(adjustedExpected, received); i++) {
        // Determine expected column (accounting for optional ID)
        let expectedCol = '';
        let expectedColIndex = i;
        let isIdColumn = false;

        if (hasIdColumn && i === 0) {
          expectedCol = 'id (auto)';
          isIdColumn = true;
        } else {
          expectedColIndex = hasIdColumn ? i - 1 : i;
          if (expectedColIndex < requiredColumns.length) {
            expectedCol = requiredColumns[expectedColIndex];
          }
        }

        const actualCol = i < received ? actualColumns[i] : '';
        let statusClass = '';
        let statusText = '';

        if (isIdColumn) {
          statusClass = 'id-column';
          statusText = 'Optional ID';
        } else if (i === 0 && isFirstColumnId) {
          statusClass = 'id-column';
          statusText = 'Optional ID';
        } else if (expectedCol.toLowerCase() === actualCol.toLowerCase()) {
          statusClass = 'match';
          statusText = 'Match';
        } else if (expectedColIndex < requiredColumns.length) {
          statusClass = 'mismatch';
          statusText = 'Mismatch';
        } else {
          statusClass = '';
          statusText = 'Extra';
        }

        html += `
          <tr>
            <td>${i + 1}</td>
            <td>${expectedCol || ''}</td>
            <td>${actualCol || ''}</td>
            <td class="${statusClass}">${statusText}</td>
          </tr>
        `;
      }

      html += `</table>`;

      details.innerHTML = html;
      modal.style.display = 'block';
    }

    function proceedWithUpload() {
      document.getElementById('user_decision').value = 'proceed';
      document.getElementById('uploadForm').submit();
    }

    function cancelUpload() {
      document.getElementById('user_decision').value = 'cancel';
      document.getElementById('uploadForm').submit();
    }

    function closeAlert() {
      const alertContainer = document.querySelector('.alert-container');
      if (alertContainer) {
        alertContainer.style.display = 'none';
      }
    }

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