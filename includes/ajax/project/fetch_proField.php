<?php
require_once('../../config.php');
header('Content-Type: application/json');

$project_type_id = $_POST['project_type_id'];
$sql = "
SELECT 
    fl.*, 
    GROUP_CONCAT(fo.option_value SEPARATOR ', ') AS options 
FROM 
    pro_type_fields ptf
INNER JOIN 
    field_library fl ON fl.id = ptf.field_id
LEFT JOIN 
    field_options fo ON fo.field_id = fl.id
WHERE 
    ptf.project_type_id = $project_type_id
GROUP BY 
    fl.id
";

// $sql = "SELECT fl.* FROM pro_type_fields ptf inner join field_library fl on fl.id= ptf.field_id where ptf.project_type_id = $project_type_id";
$fields = $boj->getQuery($sql);
$unitTypes = $boj->getQuery("select * from pro_unit_types where project_type_id = $project_type_id ");
echo json_encode(
    [
        'status' => true,
        'fields' => $fields,
        'unit_types' => $unitTypes
    ]
);
?>