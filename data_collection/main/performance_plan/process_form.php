<?php
session_start(); // Start the session

// Define the path to the JSON file
$jsonFilePath = 'performance_plan_data/performance_plan.json';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the current date and time
    $date = (new DateTime())->format('Y-m-d H:i:s'); // Adjust format as needed

    // Capture and sanitize teaching, research, community, and governance data
    $teachingOutputs = array_map('trim', $_POST['teaching_outputs'] ?? []);
    $teachingIndicators = array_map('trim', $_POST['teaching_indicators'] ?? []);
    $teachingTargets = array_map('trim', $_POST['teaching_targets'] ?? []);

    $researchOutputs = array_map('trim', $_POST['research_outputs'] ?? []);
    $researchIndicators = array_map('trim', $_POST['research_indicators'] ?? []);
    $researchTargets = array_map('trim', $_POST['research_targets'] ?? []);

    $communityOutputs = array_map('trim', $_POST['community_outputs'] ?? []);
    $communityIndicators = array_map('trim', $_POST['community_indicators'] ?? []);
    $communityTargets = array_map('trim', $_POST['community_targets'] ?? []);

    $governanceOutputs = array_map('trim', $_POST['governance_outputs'] ?? []);
    $governanceIndicators = array_map('trim', $_POST['governance_indicators'] ?? []);
    $governanceTargets = array_map('trim', $_POST['governance_targets'] ?? []);

    // Validate that at least five inputs are filled out
    $filledCount = 0;
    foreach ([$teachingOutputs, $teachingIndicators, $teachingTargets,
               $researchOutputs, $researchIndicators, $researchTargets,
               $communityOutputs, $communityIndicators, $communityTargets,
               $governanceOutputs, $governanceIndicators, $governanceTargets] as $array) {
        $filledCount += count(array_filter($array));
    }

    if ($filledCount < 5) {
        echo "<script>alert('Please fill in at least 5 inputs from any section.'); window.history.back();</script>";
        exit;
    }

    // Create an associative array to store the performance plan
    $performancePlan = [
        'date' => $date,
        'teaching' => [
            'outputs' => array_filter($teachingOutputs), // Only add non-empty values
            'indicators' => array_filter($teachingIndicators),
            'targets' => array_filter($teachingTargets),
        ],
        'research' => [
            'outputs' => array_filter($researchOutputs),
            'indicators' => array_filter($researchIndicators),
            'targets' => array_filter($researchTargets),
        ],
        'community' => [
            'outputs' => array_filter($communityOutputs),
            'indicators' => array_filter($communityIndicators),
            'targets' => array_filter($communityTargets),
        ],
        'governance' => [
            'outputs' => array_filter($governanceOutputs),
            'indicators' => array_filter($governanceIndicators),
            'targets' => array_filter($governanceTargets),
        ],
        'action' => $_POST['action'] ?? '',
    ];

    // Create the directory if it doesn't exist
    if (!is_dir('performance_plan_data')) {
        mkdir('performance_plan_data', 0755, true);
    }

    // Load existing data, if any
    $existingData = [];
    if (file_exists($jsonFilePath)) {
        $fileContent = file_get_contents($jsonFilePath);
        $existingData = json_decode($fileContent, true) ?: []; // Handle JSON decode errors
    }

    // Append new performance plan data to existing data
    $existingData[] = $performancePlan;

    // Save the combined data back to the JSON file
    if (file_put_contents($jsonFilePath, json_encode($existingData, JSON_PRETTY_PRINT)) === false) {
        echo "<script>alert('Failed to save data to file.'); window.history.back();</script>";
        exit;
    }

    // Store success message in a session variable
    $_SESSION['success_message'] = 'Performance plan has been saved successfully!';

    // Redirect to the plan.php page
    header('Location: plan.php'); // Use header redirection instead of JavaScript
    exit;
} else {
    echo "Invalid request method.";
}
