<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Columns</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style_modify_columns.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo"></div>
        <h2>Dashboard</h2>
        <ul>
            <li class="active">
                <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/upload_csv.php">
                    <i class="fas fa-upload"></i>
                    <span class="text">Upload</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-check-circle"></i>
                    <span class="text">Verify</span>
                </a>
            </li>
            <li>
                <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/modify_column.php">
                    <i class="fas fa-edit"></i>
                    <span class="text">Edit</span>
                </a>
            </li>
            <li>
                <a href="/EMPLOYEE-TRACKING-SYSTEM/register/logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-container">
        <div class="nav-tabs" id="nav-tab" role="tablist">
            <div class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </div>
            <!-- filtering home link and who to access which home hrm or other users -->
            <?php
            $homeUrl = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'hrm')
                ? '/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index.php'
                : '/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/upload_csv.php';
            ?>
            <button class="nav-link active" id="nav-home-tab" type="button" aria-controls="nav-home" aria-selected="true" onclick="goToPage('<?php echo $homeUrl; ?>')">Home</button>
            <button class="nav-link" id="nav-profile-tab" type="button" aria-controls="nav-profile" aria-selected="false" onclick="goToPage('/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/modify_column.php')">Modify CSV Receiver</button>
            <button class="nav-link" id="nav-contact-tab" type="button" aria-controls="nav-contact" aria-selected="false" onclick="goToPage('/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/view_criteria.php')">Modify Criteria</button>
            <button class="nav-link" id="nav-about-tab" type="button" aria-controls="nav-about" aria-selected="false" onclick="goToPage('#')">About</button>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'hrm'): ?>
              <button class="nav-link" id="signUp" type="button" onclick="goToPage('/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/index.php')">HRM- ScoreCard</button>
            <?php endif; ?>
            <button class="nav-link" id="nav-services-tab" type="button" aria-controls="nav-services" aria-selected="false" onclick="goToPage('#')">Services</button>
            <button class="nav-link" id="nav-blog-tab" type="button" style="color:red; border-radius:10px;" aria-controls="nav-blog" aria-selected="false" onclick="goToPage('/EMPLOYEE-TRACKING-SYSTEM/registration/logout.php')">Logout</button>
        </div>
    </div>

    <!-- Role Indicator -->
    <div class="role-indicator">
        Logged in as: <?php echo strtoupper(htmlspecialchars($_SESSION['user_role'] ?? 'guest')); ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h3><u>Modify CSV Receiver - Columns</u></h3>

            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hrm_db";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Determine user role
            $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

            // Initialize table options based on user role
            $tableOptions = [
                'roles' => 'Roles',
                'degrees' => 'Degrees',
                'departments' => 'Departments',
                'staff' => 'Staff',
                'publications' => 'Publications',
                'grants' => 'Grants',
                'supervision' => 'Supervision',
                'innovations' => 'Innovations',
                'academic_activities' => 'Academic Activities',
                'service' => 'Service',
                'community_service' => 'Community Service',
                'professional_bodies' => 'Professional Bodies'
            ];

            // Filter options based on user role by name
            $allowedOptions = [];
            switch ($userRole) {
                case 'hrm':
                    $allowedOptions = ['roles', 'degrees', 'departments'];
                    break;
                case 'hod':
                    $allowedOptions = ['staff', 'publications', 'grants'];
                    break;
                case 'grants':
                    $allowedOptions = ['supervision', 'innovations', 'academic_activities'];
                    break;
                case 'ar':
                    $allowedOptions = ['service', 'community_service', 'professional_bodies'];
                    break;
                case 'pub':
                    $allowedOptions = ['publications'];
                    break;
                default:
                    echo "<p style='color:red'>Your role is not recognized. No tables to display </br> [LOGIN FIRST!!].</p>";
                    break;
            }

            // SQL query to get table names
            $tables = [];
            $sql = "SHOW TABLES";
            $result = $conn->query($sql);

            // Populate the $tables array with table names
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_row()) {
                    $tables[] = $row[0];
                }
            }

            // Filter the tables by the allowed options
            $filteredTables = array_intersect($tables, $allowedOptions);

            // Initialize columns array
            $columns = [];

            // Display available tables
            echo "<form id='table-select-form' method='POST' action=''>";
            echo "<label for='table'>Select CSV Name:</label>";
            echo "<select id='table' name='table_name' required onchange='this.form.submit()'>";
            echo "<option value='' disabled selected>Select CSV here</option>";
            foreach ($filteredTables as $table) {
                $selected = (isset($_POST['table_name']) && $_POST['table_name'] == $table) ? 'selected' : '';
                echo "<option value='$table' $selected>$table</option>";
            }
            echo "</select>";
            echo "</form>";

            // Get selected table from POST request
            if (isset($_POST['table_name'])) {
                $selected_table = $_POST['table_name'];

                // SQL query to get column names from the selected table
                $sql = "SHOW COLUMNS FROM $selected_table";
                $result = $conn->query($sql);

                // Display available columns, excluding specific columns
                if ($result->num_rows > 0) {
                    echo "<div class='list_div'>";
                    echo "<h4>Current Expected Columns for '$selected_table':</h4>";
                    echo "<ul class='columns-list'>";
                    while ($row = $result->fetch_assoc()) {
                        // Skip columns like 'id' and those containing '_id'
                        if (!preg_match('/(_id|id|id_|_id_)/', $row['Field'])) {
                            $columns[] = $row['Field'];
                            echo "<li>" . $row['Field'] . "</li>";
                        }
                    }
                    echo "</ul>";
                    echo "</div>";
                } else {
                    echo "<p>No columns found in the table '$selected_table'.</p><br>";
                }
            }

            // Close the database connection
            $conn->close();
            ?>

            <!-- Display feedback message if it exists -->
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='message' id='feedback-message'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            ?>

            <form id="modify-form" action="modify_process.php" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <div>
                        <label for="column">Column Name:</label>
                        <select id="column" name="column_name">
                            <option value="" disabled selected>Select Column</option>
                            <?php foreach ($columns as $column): ?>
                                <option value="<?php echo $column; ?>"><?php echo $column; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="action">Action:</label>
                        <select id="action" name="action" required onchange="toggleDataTypeField()">
                            <option value="" disabled selected>Select Action</option>
                            <option value="add">Add Column</option>
                            <option value="drop">Delete Column</option>
                        </select>
                    </div>
                </div>

                <!-- Input field for new column name if 'add' is selected -->
                <div id="new-column-div" style="display: none; margin-top: 10px;">
                    <label for="new_column_name">New Column Name:</label>
                    <input type="text" id="new_column_name" name="new_column_name" placeholder="Enter new column name">
                    <p id="error-message" class="error" style="display: none;">Please enter a valid column name.</p>
                </div>

                <input type="hidden" id="data_type" name="data_type" value="VARCHAR(255)">
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function goToPage(pageUrl) {
            window.location.href = pageUrl;
        }

        function toggleDataTypeField() {
            var action = document.getElementById('action').value;
            var newColumnDiv = document.getElementById('new-column-div');

            if (action === 'drop') {
                newColumnDiv.style.display = 'none';
            } else {
                newColumnDiv.style.display = 'block';
            }
        }

        function validateForm() {
            var action = document.getElementById('action').value;
            var newColumnName = document.getElementById('new_column_name').value;
            var errorMessage = document.getElementById('error-message');

            if (action === 'add' && newColumnName.trim() === '') {
                errorMessage.style.display = 'block';
                return false;
            } else {
                errorMessage.style.display = 'none';
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const body = document.body;

            // Check if sidebar state is saved in localStorage
            const sidebarState = localStorage.getItem('sidebarCollapsed');
            if (sidebarState === 'true') {
                body.classList.add('collapsed-sidebar');
            }

            hamburger.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    body.classList.toggle('show-sidebar');
                } else {
                    body.classList.toggle('collapsed-sidebar');
                }

                // Save state to localStorage
                const isCollapsed = body.classList.contains('collapsed-sidebar');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // For mobile view
            if (window.innerWidth <= 768) {
                body.classList.add('collapsed-sidebar');
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    body.classList.remove('show-sidebar');
                } else {
                    if (!body.classList.contains('collapsed-sidebar')) {
                        body.classList.add('collapsed-sidebar');
                    }
                }
            });

            // Initialize form fields
            toggleDataTypeField();
        });
    </script>
    
</body>

</html>