<?php
// start session 
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../register/logout.php"); // Redirect to the login page
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Navigation Bar Styles */
        nav {
            background-color: rgba(0, 0, 0, 0.7);
            /* Light black background */
            padding: 10px;
            width: 100%;
            /* Full width */
            position: fixed;
            /* Fixed position */
            top: 0;
            /* Align to top */
            left: 0;
            /* Align to left */
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            /* Ensure it's above other content */
        }

        nav h1 {
            color: white;
            /* White text for the title */
            margin: 0;
            position: absolute;
            /* Position it absolutely */
            left: 40%;
            /* Move to the center */
            transform: translateX(-50%);
            /* Center it */
        }

        nav .nav-button {
            background-color: #4CAF50;
            /* Green background */
            color: white;
            /* White text */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            /* Remove underline */
            transition: background-color 0.3s;
            /* Smooth transition */
        }

        nav .nav-button:hover {
            background-color: #45a049;
            /* Darker green on hover */
        }

        /* Centered and Animated Heading */
        .centered-heading {
            text-align: center;
            overflow: hidden;
            /* Hide overflow */
            white-space: nowrap;
            /* Prevent line breaks */
            animation: move 15s linear infinite;
            /* Animation for moving text */
        }

        @keyframes move {
            0% {
                transform: translateX(100%);
                /* Start off-screen right */
            }

            50% {
                transform: translateX(-100%);
                /* Move off-screen left */
            }

            100% {
                transform: translateX(100%);
                /* Return to start */
            }
        }


        /* Additional styles for the rest of the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 60px 20px;
            /* Add padding to avoid content being hidden under the nav */
        }

        .content {
            margin-top: 20px;
            /* Margin for content below the nav */
        }
    </style>
</head>

<body>

    <nav>
        <h1 class="centered-heading">CSV Upload System</h1>
        <a href="../../register/logout.php" class="nav-button">Logout</a>
    </nav>

    <form id="uploadForm" action="process_csv.php" method="POST" enctype="multipart/form-data">
        <h2>Upload CSV File</h2>
        <?php
        if (isset($_SESSION['notification'])) {
            echo '<div id="notification" class="alert">' . htmlspecialchars($_SESSION['notification']) .
                '<span class="close-btn" onclick="closeAlert()">&times;</span></div>';
            unset($_SESSION['notification']); // Clear the notification after displaying it
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
            'academicactivities' => 'Academic Activities',
            'service' => 'Service',
            'communityservice' => 'Community Service',
            'professionalbodies' => 'Professional Bodies'
        ];

        // Filter options based on user role
        if ($userRole === 'hrm') {
            $allowedOptions = array_slice($tableOptions, 0, 13); // First 3 options for HRM
        }
        if ($userRole === 'hod') {
            $allowedOptions = array_slice($tableOptions, 3, 6); // Next 3 options for HOD
        }
        if ($userRole === 'grants') {
            $allowedOptions = array_slice($tableOptions, 6, 9); // Next 3 options for Grants
        }
        if ($userRole === 'ar') {
            $allowedOptions = array_slice($tableOptions, 9, 12); // Next 3 options for AR
        }
        if ($userRole === 'pub') {
            $allowedOptions = array_slice($tableOptions, 11, 13); // Last option for Pub
        }
        ?>
        <br>
        <select name="table_name" id="table_name">
            <option value="">Select Table</option>
            <?php foreach ($allowedOptions as $value => $label): ?>
                <option value="<?php echo htmlspecialchars($value); ?>"><?php echo htmlspecialchars($label); ?></option>
            <?php endforeach; ?>
        </select>
        <div id="tableError" class="error"></div> <!-- Error message for table selection -->

        <label for="csv_file">Select CSV File:</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" style="width: 370px;">
        <div id="fileError" class="error"></div> <!-- Error message for file selection -->

        <input type="submit" value="Upload CSV">
    </form>

    <script>
        // Hide the notification after 30 seconds
        setTimeout(function() {
            var notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 30000);

        // Close the alert when clicking "X"
        function closeAlert() {
            var notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const csvFile = document.getElementById('csv_file').files[0];
            const tableName = document.getElementById('table_name').value;

            // Clear previous error messages
            document.getElementById('tableError').innerText = '';
            document.getElementById('fileError').innerText = '';

            let valid = true; // Flag for form validity

            // Validate table selection first
            if (!tableName) {
                document.getElementById('tableError').innerText = 'Please select a table.';
                valid = false;
                e.preventDefault(); // Stop form submission if invalid
                return; // Exit the function to prevent further checks
            }

            // Validate CSV file second
            if (!csvFile) {
                document.getElementById('fileError').innerText = 'Please select a CSV file.';
                valid = false;
                e.preventDefault(); // Stop form submission if invalid
                return; // Exit the function to prevent further checks
            }

            // Validate file type
            if (csvFile) {
                const fileExtension = csvFile.name.split('.').pop().toLowerCase();
                if (fileExtension !== 'csv') {
                    document.getElementById('fileError').innerText = 'Only CSV files are allowed.';
                    valid = false;
                    e.preventDefault(); // Stop form submission if invalid
                }
            }
        });

        // Display alert notification from PHP
        window.onload = function() {
            const notification =
                "<?php echo isset($_SESSION['notification']) ? htmlspecialchars($_SESSION['notification']) : ''; ?>";
            if (notification) {
                alert(notification); // Show the alert box with the notification content
            }
        };
    </script>
</body>

</html>