<?php
session_start();
include 'config.php';

// Check if user is admin (uncomment when ready)
// if ($_SESSION['user_role'] !== 'admin') {
//     $_SESSION['error_message'] = 'Unauthorized access';
//     header('Location: ../unauthorized.php');
//     exit();
// }

// Function to sanitize input
function sanitizeInput($data) {
    // First decode any existing entities to prevent double encoding
    $data = htmlspecialchars_decode(trim($data), ENT_QUOTES);
    // Then properly encode just once
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Function to generate a unique criteria key
function generateCriteriaKey($name) {
    return 'crit_' . md5(strtolower($name));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->begin_transaction();
        
        // Get all existing criteria first
        $existingCriteria = [];
        $result = $conn->query("SELECT name, category FROM criteria");
        while ($row = $result->fetch_assoc()) {
            $existingCriteria[$row['name']] = $row['category'];
        }
        
        // Process submitted criteria
        $submittedCriteria = $_POST['criteria_names'] ?? [];
        $submittedPoints = $_POST['criteria_values'] ?? [];
        $submittedCategories = $_POST['criteria_categories'] ?? [];
        
        $updatedCriteria = [];
        $newCriteria = [];
        
        // Separate updates and new entries
        foreach ($submittedCriteria as $key => $displayName) {
            $points = $submittedPoints[$key] ?? 0;
            $category = $submittedCategories[$key] ?? 'General';
            
            // Sanitize the display name (fixes the Bachelor's issue)
            $cleanDisplayName = sanitizeInput($displayName);
            
            // Determine if this is a new criteria (starts with 'new_')
            if (strpos($key, 'new_') === 0) {
                $newCriteria[$key] = [
                    'display_name' => $cleanDisplayName,
                    'points' => floatval($points),
                    'category' => sanitizeInput($category)
                ];
            } elseif (isset($existingCriteria[$key])) {
                $updatedCriteria[$key] = [
                    'display_name' => $cleanDisplayName,
                    'points' => floatval($points),
                    'category' => $existingCriteria[$key]
                ];
            }
        }
        
        // Process updates
        foreach ($updatedCriteria as $key => $data) {
            $stmt = $conn->prepare("UPDATE criteria SET display_name = ?, points = ?, updated_at = NOW() WHERE name = ?");
            $stmt->bind_param("sds", $data['display_name'], $data['points'], $key);
            $stmt->execute();
            $stmt->close();
        }
        
        // Process additions
        foreach ($newCriteria as $key => $data) {
            // Generate a proper key name
            $name = generateCriteriaKey($data['display_name']);
            
            $stmt = $conn->prepare("INSERT INTO criteria (name, display_name, category, points, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->bind_param("sssd", $name, $data['display_name'], $data['category'], $data['points']);
            $stmt->execute();
            $stmt->close();
        }
        
        // Determine deleted criteria (exist in DB but not in submission)
        $deletedCriteria = array_diff_key($existingCriteria, $submittedCriteria);
        
        // Process deletions
        foreach ($deletedCriteria as $key => $category) {
            $stmt = $conn->prepare("DELETE FROM criteria WHERE name = ?");
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $stmt->close();
        }
        
        $conn->commit();
        
        $_SESSION['success_message'] = 'Criteria updated successfully!';
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = 'Error updating criteria: ' . $e->getMessage();
    }
    
    // Redirect back to the criteria page
    header('Location: ../view_criteria.php');
    exit();
}

// If not a POST request, redirect
header('Location: ../view_criteria.php');
exit();
?>