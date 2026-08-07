<?php
require_once('../../config.php');
header('Content-Type: application/json');

try {
    $unit_type_id = (int) $_POST['unit_type_id'];

    $sql = "SELECT 
    fl.*, 
    GROUP_CONCAT(fo.option_value SEPARATOR ', ') AS options  
FROM 
    unit_type_fields ut  
INNER JOIN 
    field_library fl ON fl.id = ut.field_id  
LEFT JOIN 
    field_options fo ON fo.field_id = fl.id  
WHERE 
    ut.unit_type_id = $unit_type_id  
GROUP BY 
    fl.id"
    ;
    $fields = $boj->getQuery($sql);
    // $unitTypes = $boj->getQuery("select * from pro_unit_types where project_type_id = $project_type_id ");
    echo json_encode(
        [
            'status' => true,
            'fields' => $fields,
            // 'unit_types' => $unitTypes
        ]
    );
    exit;
} catch (\Throwable $th) {
    echo json_encode(
        [
            'status' => true,
            'message' => $th->getMessage()
        ]
    );
    exit;
}

?>