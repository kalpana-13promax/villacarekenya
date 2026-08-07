<?php
require_once('../../config.php');
header('Content-Type: application/json');

$id = $_POST['project_id'];
$sql = "select pro_type from project where id= $id";

$type = $boj->getQuery($sql);

if (!empty($type)) {
    $type = $type[0]->pro_type;
    $typeArray = array_filter(array_map('intval', explode(',', $type)));
    $typeList = implode(',', $typeArray); // safe comma-separated integers
    $data = $boj->getQuery("SELECT id,name FROM project_types WHERE id IN ($typeList)");

    // print_r($data);

    echo json_encode(
        [
            'status' => true,
            'types' => $data,
        ]
    );
} else {
    echo json_encode(
        [
            'status' => false,

        ]
    );
}
?>