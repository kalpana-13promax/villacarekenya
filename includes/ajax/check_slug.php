<?php
header("Content-Type: application/json");

require_once '../config.php'; // Include DB connection file


if (isset($_POST['slug']) && !empty($_POST['slug']) && $_POST['type']) {


    $obj = new itways();
    $type = $_POST['type'];
    $slug = $_POST['slug'];
    $id = @$_POST['id'];
    $condition = "";
    // $condition = ($type == 'project') ? "AND type= 'project'" : "AND type= 'property'";
    if (!empty($id) && $id > 0) {
        $condition = " AND related_id != $id";
    }

    $query = "SELECT id FROM seo_data WHERE slug = '$slug' $condition LIMIT 1";
    $result = $obj->getQuery($query);

    if (!empty($result)) {
        echo json_encode(['success' => false, 'message' => 'This slug already exists.']);
    } else {

        echo json_encode(['success' => true, 'message' => 'done']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Slug is Required']);
}
?>