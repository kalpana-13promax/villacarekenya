<?php
require_once('../config.php');

// Assuming ['project_id'] contains the selected project ID
if (isset($_POST['project_id']) && !empty($_POST['project_id'])) {

    $project_id = $_POST['project_id'];

    // Fetch towers based on the selected project
    $rows = $boj->getQuery("SELECT id, project_type_name FROM project_data WHERE project_id = $project_id") ?? [];
    $tower = '';
    if (!empty($rows)) {

        $tower .= "<option value=''>Select Tower</option>";
        foreach ($rows as $row) {
            $tower .= "<option value='{$row->id}'>{$row->project_type_name}</option>";
        }
    }
    $sql = "select multi_city_loc from project where id = $project_id";
    $data = $boj->getQuery($sql) ?? [];
    if (!empty($data)) {
        if ($data[0]->multi_city_loc == 1) {
            $multi_city_loc = true;
        } else {

            $multi_city_loc = false;
        }
    }

    echo json_encode([
        'tower' => $tower,
        'multi_city' => $multi_city_loc
    ]);
}
?>