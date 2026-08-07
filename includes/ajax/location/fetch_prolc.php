<?php

include_once '../../config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pro_id']) && isset($_POST['csrf_token'])) {
    $obj = new itways(); // Assuming your class is itways
    $pro_id = intval($_POST['pro_id']);
    $csrf = $_POST['csrf_token'];

    // Validate CSRF token
    if (strcasecmp($csrf, $_SESSION['csrf']) !== 0) {
        echo json_encode(["status" => false, "message" => "Invalid CSRF token"]);
        exit;
    }

    $sql = "SELECT city, location, sub_location FROM project WHERE id = $pro_id";
    $result = $obj->getQuery($sql);

    if (!empty($result)) {
        echo json_encode([
            "status" => true,
            "city" => $result[0]->city,
            "location" => $result[0]->location,
            "subLocation" => $result[0]->sub_location,

        ]);
    } else {
        echo json_encode(["status" => false, "message" => "Project not found"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Invalid request"]);
}

?>