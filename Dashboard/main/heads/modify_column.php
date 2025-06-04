<?php session_start(); 
$current_pag = basename($_SERVER['PHP_SELF']);
// Check if user is NOT logged in OR not HRM
if (!isset($_SESSION['user_role'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Columns</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../modify/style_modify_columns.css">
</head>

<body>
    <?php
    // <!-- Sidebar -->
    include '../bars/side_bar.php';
    // nav_bar
    include '../bars/nav_bar.php';
    ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h3><u>Modify CSV Receiver - Columns</u></h3>

            <?php
            // Database connection details
            include 'approve/config.php';
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
                        <select id="action" name="action" required onchange="toggleFields()">
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
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }

        // Toggle form fields based on action selection
        function toggleFields() {
            const action = document.getElementById('action').value;
            const newColumnDiv = document.getElementById('new-column-div');
            const columnSelect = document.getElementById('column');

            if (action === 'add') {
                newColumnDiv.style.display = 'block';
                columnSelect.disabled = true;
                columnSelect.value = '';
            } else {
                newColumnDiv.style.display = 'none';
                columnSelect.disabled = false;
            }
        }

        // Form validation
        function validateForm() {
            const action = document.getElementById('action').value;
            const newColumnName = document.getElementById('new_column_name');
            const errorMessage = document.getElementById('error-message');

            if (action === 'add' && (!newColumnName.value || newColumnName.value.trim() === '')) {
                errorMessage.style.display = 'block';
                return false;
            }

            errorMessage.style.display = 'none';
            return true;
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Set up hamburger menu for mobile
            const hamburger = document.getElementById('hamburger');
            if (hamburger) {
                hamburger.addEventListener('click', toggleSidebar);
            }

            // Hide hamburger on large screens
            function checkScreenSize() {
                const hamburger = document.getElementById('hamburger');
                if (window.innerWidth > 768) {
                    if (hamburger) hamburger.style.display = 'none';
                    document.querySelector('.sidebar').classList.remove('show');
                } else {
                    if (hamburger) hamburger.style.display = 'block';
                }
            }

            // Check on load and resize
            checkScreenSize();
            window.addEventListener('resize', checkScreenSize);

            // Initialize form fields
            toggleFields();
        });
    </script>
</body>

</html>