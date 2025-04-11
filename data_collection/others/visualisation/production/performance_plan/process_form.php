<?php
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the name field
    $name = isset($_POST['username']) ? trim($_POST['username']) : '';

    // Retrieve the Teaching and Learning fields
    $teaching_outputs = $_POST['teaching_outputs'] ?? [];
    $teaching_indicators = $_POST['teaching_indicators'] ?? [];
    $teaching_targets = $_POST['teaching_targets'] ?? [];

    // Retrieve the Research, Innovations, and Publications fields
    $research_outputs = $_POST['research_outputs'] ?? [];
    $research_indicators = $_POST['research_indicators'] ?? [];
    $research_targets = $_POST['research_targets'] ?? [];

    // Retrieve the Community Engagement fields
    $community_outputs = $_POST['community_outputs'] ?? [];
    $community_indicators = $_POST['community_indicators'] ?? [];
    $community_targets = $_POST['community_targets'] ?? [];

    // Retrieve the Professional Development fields
    $development_outputs = $_POST['development_outputs'] ?? [];
    $development_indicators = $_POST['development_indicators'] ?? [];
    $development_targets = $_POST['development_targets'] ?? [];

    // Retrieve the Administrative Responsibilities fields
    $administrative_outputs = $_POST['administrative_outputs'] ?? [];
    $administrative_indicators = $_POST['administrative_indicators'] ?? [];
    $administrative_targets = $_POST['administrative_targets'] ?? [];

    // Validate the required fields (Name and at least 15 filled columns)
    $filled_fields = 0;
    $all_fields = array_merge(
        $teaching_outputs, $teaching_indicators, $teaching_targets,
        $research_outputs, $research_indicators, $research_targets,
        $community_outputs, $community_indicators, $community_targets,
        $development_outputs, $development_indicators, $development_targets,
        $administrative_outputs, $administrative_indicators, $administrative_targets
    );

    foreach ($all_fields as $field) {
        if (trim($field) !== '') {
            $filled_fields++;
        }
    }

    if ($name === '' || $filled_fields < 15) {
        $_SESSION['success_message'] = 'Please fill in your name and at least 15 fields before submitting.';
        header('Location: plan.php'); // Redirect back to form page
        exit();
    }

    // Prepare the data for JSON encoding
    $form_data = [
        'name' => $name,
        'teaching' => [
            'outputs' => $teaching_outputs,
            'indicators' => $teaching_indicators,
            'targets' => $teaching_targets,
        ],
        'research' => [
            'outputs' => $research_outputs,
            'indicators' => $research_indicators,
            'targets' => $research_targets,
        ],
        'community' => [
            'outputs' => $community_outputs,
            'indicators' => $community_indicators,
            'targets' => $community_targets,
        ],
        'development' => [
            'outputs' => $development_outputs,
            'indicators' => $development_indicators,
            'targets' => $development_targets,
        ],
        'administrative' => [
            'outputs' => $administrative_outputs,
            'indicators' => $administrative_indicators,
            'targets' => $administrative_targets,
        ],
    ];

    // Create the performance_plan_data directory if it doesn't exist
    $data_directory = 'performance_plan_data';
    if (!is_dir($data_directory)) {
        mkdir($data_directory, 0755, true);
    }

    // Define the JSON file path
    $file_path = $data_directory . '/' . uniqid('performance_plan_', true) . '.json';

    // Write the data to a JSON file
    if (file_put_contents($file_path, json_encode($form_data, JSON_PRETTY_PRINT))) {
        $_SESSION['success_message'] = 'Form submitted successfully. Thank you, ' . htmlspecialchars($name) . '!';
    } else {
        $_SESSION['success_message'] = 'There was an error saving your data. Please try again.';
    }

    // Redirect back to the form page
    header('Location: plan.php');
    exit();
}
?>
