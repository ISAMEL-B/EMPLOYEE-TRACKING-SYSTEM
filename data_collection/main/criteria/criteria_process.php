<?php
session_start(); // Start the session to handle session-based messages

// Path to the JSON file
$jsonFile = 'criteria_data.json'; // Change this to your actual path

// Function to load and return the stored criteria data from the JSON file
function loadCriteriaData() {
    global $jsonFile;

    if (file_exists($jsonFile)) {
        // Read the data from the JSON file
        $jsonData = file_get_contents($jsonFile);
        return json_decode($jsonData, true);
    } else {
        // Return an empty array if the file doesn't exist
        return [];
    }
}

// Function to save the criteria data to the JSON file
function saveCriteriaData($criteriaData) {
    global $jsonFile;

    // Convert the criteria data to JSON format and save it
    $jsonData = json_encode($criteriaData, JSON_PRETTY_PRINT);
    file_put_contents($jsonFile, $jsonData);
}

// Handle the "Update Criteria" button submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Ensure you're checking for the correct request method
    // Get the submitted criteria data
    $submittedCriteria = $_POST['criteria'];

    // Validate the submitted criteria data
    if (empty($submittedCriteria)) {
        // Set an error message if the criteria field is empty
        $_SESSION['error_message'] = 'Criteria field cannot be empty.';
        header('Location: criteria_upload.php'); // Change to your form page
        exit();
    }

    // Load the existing criteria data from the JSON file
    $existingCriteria = loadCriteriaData();

    // Update the existing criteria with the submitted values
    foreach ($submittedCriteria as $key => $value) {
        // Ensure values are integers, default to 0 if empty
        if ($key === 'PhD_Lecturer') {
            $existingCriteria['PhD track [Lecturer]'] = (int)$value; // Convert and save with the original JSON key
        } else {
            $existingCriteria[$key] = (int)$value; // Directly cast to integer for other fields
        }
    }

    // Save the updated criteria to the JSON file
    saveCriteriaData($existingCriteria);

    // Set a success message in the session
    $_SESSION['success_message'] = 'Criteria have been successfully updated!';

    // Redirect back to the form after saving
    header('Location: criteria_upload.php'); // Change to your form page
    exit();
}

// Handle the "Load Data" button submission (if you have a button for this)
if (isset($_POST['load_data'])) {
    // Load the existing criteria data
    $criteriaData = loadCriteriaData();

    // Optionally: Store the data in the session for later use or debugging
    $_SESSION['loaded_data'] = $criteriaData;

    // Redirect back to the form after loading
    header('Location: criteria_upload.php'); // Change to your form page
    exit();
}
?>
