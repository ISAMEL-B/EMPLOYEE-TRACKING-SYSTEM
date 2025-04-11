<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify CSV Receiver - Columns</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            width: 60%;
            margin: 0 auto;
            text-align: center;
        }

        .list_div {
            background-color: #e0f7fa;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            text-align: left;
            margin: 20px 0;
        }

        ul.columns-list {
            list-style-type: decimal;
            padding-left: 0;
            margin: 0;
        }

        ul.columns-list li {
            padding: 8px;
            font-size: 16px;
        }

        h4 {
            text-align: center;
        }

        .message {
            color: green;
            margin-bottom: 20px;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <h3> <u>Modify CSV Receiver - Columns</u></h3>

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
                $allowedOptions = ['roles', 'degrees', 'departments']; // Specific tables for HRM
                break;
            case 'hod':
                $allowedOptions = ['staff', 'publications', 'grants']; // Specific tables for HOD
                break;
            case 'grants':
                $allowedOptions = ['supervision', 'innovations', 'academic_activities']; // Specific tables for Grants
                break;
            case 'ar':
                $allowedOptions = ['service', 'community_service', 'professional_bodies']; // Specific tables for AR
                break;
            case 'pub':
                $allowedOptions = ['publications']; // Specific table for Pub
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
        echo "<form id='table-select-form' method='POST' action=''> <br> "; 
        echo "<label for='table'>Select CSV Name:</label>";
        echo "<select id='table' name='table_name' required onchange='this.form.submit()' style='width: 100%;'>";
        echo "<option value='' disabled selected>Select CSV here</option>"; // Placeholder option
        foreach ($filteredTables as $table) {
            echo "<option value='$table'>$table</option>";
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
            unset($_SESSION['message']); // Clear the message after displaying
        }
        ?>

        <form id="modify-form" action="modify_table.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                <div style="flex: 1; margin-right: 10px;">
                    <label for="column">Column Name:</label>
                    <select id="column" name="column_name" style="width: 100%;">
                        <option value="" disabled selected>Select Column</option>
                        <?php foreach ($columns as $column): ?>
                            <option value="<?php echo $column; ?>"><?php echo $column; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex: 1; margin-left: 10px;">
                    <label for="action">Action:</label>
                    <select id="action" name="action" required onchange="toggleDataTypeField()" style="width: 100%;">
                        <option value="" disabled selected>Select Action</option>
                        <option value="add">Add Column</option>
                        <option value="drop">Delete Column</option>
                    </select>
                </div>
            </div>

            <!-- Input field for new column name if 'add' is selected -->
            <div id="new-column-div" style="display: none; margin-top: 10px;">
                <label for="new_column_name">New Column Name:</label>
                <input type="text" id="new_column_name" name="new_column_name" placeholder="Enter new column name if needed" style="width: 100%;">
                <p id="error-message" class="error" style="display: none;">Please enter a valid column name.</p>
            </div>

            <input type="hidden" id="data_type" name="data_type" value="VARCHAR(255)">
            <button type="submit" style="margin-top: 10px;">Submit</button>

            <a href="../criteria/criteria_upload.php">GoToCriteria</a> ||
            <a href="../upload.php">GoToModifyCsvReceiver</a>
            
        </form>
    </div>

    <script>
        function toggleDataTypeField() {
            var action = document.getElementById('action').value;
            var newColumnDiv = document.getElementById('new-column-div');

            if (action === 'drop') {
                newColumnDiv.style.display = 'none'; // Hide new column input for 'drop'
            } else {
                newColumnDiv.style.display = 'block'; // Show new column input for 'add'
            }
        }

        // Form validation to ensure new column name is provided for 'add' action
        function validateForm() {
            var action = document.getElementById('action').value;
            var newColumnName = document.getElementById('new_column_name').value;
            var errorMessage = document.getElementById('error-message');
            
            if (action === 'add' && newColumnName.trim() === '') {
                errorMessage.style.display = 'block'; // Show error if new column name is empty
                return false; // Prevent form submission
            } else {
                errorMessage.style.display = 'none'; // Hide error message
            }
            return true; // Proceed with form submission
        }

        window.onload = function() {
            toggleDataTypeField();

            var messageElement = document.getElementById('feedback-message');
            if (messageElement) {
                setTimeout(function() {
                    messageElement.style.display = 'none';
                }, 10000); // Hide feedback message after 10 seconds
            }
        };
    </script>
</body>
</html>
