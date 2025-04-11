<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the uploads directory exists
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Database connection
include 'config.php';

// Expected column counts for validation
$expected_columns = [
    'academicactivities' => 3, // Includes activity_id, staff_id, activity_type
    'communityservice' => 3, // Includes community_service_id, staff_id, description
    'professionalbodies' => 3 // Includes professional_body_id, staff_id, body_name
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table_name'];
    $file = $_FILES['csv_file']['tmp_name'];

    if (is_uploaded_file($file)) {
        $file_name = $_FILES['csv_file']['name'];
        $destination = "uploads/" . $file_name;
        move_uploaded_file($file, $destination);

        if (($handle = fopen($destination, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            $received_columns = count($header); // Get the number of columns received

            // // Validate the column count
            // if ($received_columns != $expected_columns[$table]) {
            //     $_SESSION['notification'] = "Invalid CSV format: Expected {$expected_columns[$table]} columns, but received {$received_columns} columns. Received header: " . implode(", ", $header);
            //     fclose($handle);
            //     header('Location: upload_csv.php');
            //     exit;
            // }

            // Prepare queries based on the selected table
            $query_map = [
                'academicactivities' => [
                    "INSERT INTO academicactivities (activity_id, staff_id, activity_type) VALUES (?, ?, ?)",
                    "SELECT * FROM academicactivities WHERE activity_id = ?"
                ],
                'communityservice' => [
                    "INSERT INTO communityservice (community_service_id, staff_id, description) VALUES (?, ?, ?)",
                    "SELECT * FROM communityservice WHERE community_service_id = ?"
                ],
                'professionalbodies' => [
                    "INSERT INTO professionalbodies (professional_body_id, staff_id, body_name) VALUES (?, ?, ?)",
                    "SELECT * FROM professionalbodies WHERE professional_body_id = ?"
                ]
            ];

            // if (!isset($query_map[$table])) {
            //     $_SESSION['notification'] = "Invalid table selection.";
            //     fclose($handle);
            //     header('Location: upload_csv.php');
            //     exit;
            // }

            [$insert_query, $check_query] = $query_map[$table];
            $stmt = $conn->prepare($insert_query);
            $check_stmt = $conn->prepare($check_query);

            $is_updated = false;

            $conn->begin_transaction();

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) != $expected_columns[$table]) {
                    continue; // Skip rows with incorrect column count
                }

                switch ($table) {
                    case 'academicactivities':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'communityservice':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    case 'professionalbodies':
                        $check_stmt->bind_param("i", $data[0]);
                        $stmt->bind_param("iis", $data[0], $data[1], $data[2]);
                        break;
                    default:
                        continue 2;
                }

                $check_stmt->execute();
                $check_stmt->store_result();
                if ($check_stmt->num_rows == 0) {
                    $stmt->execute();
                    $is_updated = true;
                }
            }

            $conn->commit();
            fclose($handle);
            $_SESSION['notification'] = $is_updated ? "Data successfully inserted into {$table}." : "No new data added.";
        } else {
            $_SESSION['notification'] = "Failed to open the CSV file.";
        }
    } else {
        $_SESSION['notification'] = "No file uploaded or file error.";
    }
}

header('Location: upload_csv.php');