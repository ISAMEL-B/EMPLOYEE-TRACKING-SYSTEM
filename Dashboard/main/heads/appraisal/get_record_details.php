<?php
require_once '../approve/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $table = $_POST['table'];

    $valid_tables = [
        'academicactivities', 'activity_types', 'communityservice', 
        'degrees', 'grants', 'innovations', 'professionalbodies',
        'publications', 'service', 'staff', 'supervision'
    ];

    if (!in_array($table, $valid_tables)) {
        die("Invalid table name");
    }

    $primary_key = getPrimaryKey($table);

    if ($table === 'academicactivities') {
        // Join staff twice: one for staff_name, one for verifier_name
        $query = "SELECT a.*, 
                         CONCAT(s.first_name, ' ', s.last_name) AS staff_name, 
                         CONCAT(v.first_name, ' ', v.last_name) AS verifier_full_name
                  FROM academicactivities a
                  LEFT JOIN staff s ON a.staff_id = s.staff_id
                  LEFT JOIN staff v ON a.verified_by = v.staff_id
                  WHERE a.$primary_key = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
    } else {
        $query = "SELECT * FROM $table WHERE $primary_key = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered">';

        foreach ($row as $field => $value) {
            if ($field === $primary_key) continue;

            if ($table === 'academicactivities') {
                if ($field === 'staff_id') {
                    $value = $row['staff_name'] ?? 'N/A';
                    $field = 'Staff Name';
                }
                if ($field === 'verified_by') {
                    $value = $row['verifier_full_name'] ?? 'N/A';
                    $field = 'Verified By';
                }
                if ($field === 'staff_name' || $field === 'verifier_full_name') {
                    continue; // Skip these duplicates
                }
            }

            echo '<tr>';
            echo '<th>' . htmlspecialchars(ucwords(str_replace('_', ' ', $field))) . '</th>';
            echo '<td>' . htmlspecialchars($value ?? 'NULL') . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning">Record not found</div>';
    }
}

function getPrimaryKey($table_name) {
    $primary_keys = [
        'academicactivities' => 'activity_id',
        'activity_types' => 'type_id',
        'communityservice' => 'community_service_id',
        'degrees' => 'degree_id',
        'grants' => 'grant_id',
        'innovations' => 'innovation_id',
        'professionalbodies' => 'professional_body_id',
        'publications' => 'publication_id',
        'service' => 'service_id',
        'staff' => 'staff_id',
        'supervision' => 'supervision_id'
    ];
    return $primary_keys[$table_name] ?? 'id';
}
?>
