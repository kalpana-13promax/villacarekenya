<?php
include '../../config.php';


$projectID = $_GET['project_type_id']; // or from wherever you're getting it



// Fetch all units and their associated fields
$sql = "
    SELECT 
        unit.id AS unit_id, 
        unit.name AS unit_name, 
        utf.field_id 
    FROM pro_unit_types put
    INNER JOIN unit_types unit ON unit.name = put.name
    LEFT JOIN unit_type_fields utf ON utf.unit_type_id = put.id
    WHERE put.project_type_id = '$projectID'
    ORDER BY unit.name
";

$result = $boj->getQuery($sql) ?? [];

// Process to group by unit_id
$units = [];

foreach ($result as $row) {
    $unit_id = $row->unit_id;
    $unit_name = $row->unit_name;
    $field_id = $row->field_id;

    if (!isset($units[$unit_id])) {
        $units[$unit_id] = [
            'id' => $unit_id,
            'name' => $unit_name,
            'fields' => []
        ];
    }

    if (!empty($field_id)) {
        $units[$unit_id]['fields'][] = $field_id;
    }
}

// Re-index to get a clean array
$units = array_values($units);

echo json_encode($units);