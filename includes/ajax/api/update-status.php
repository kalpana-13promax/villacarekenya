

<?php

require_once '../../config.php';
try {
    $api_id = $_POST['api_id'] ?? null;
    $status = $_POST['status'] ?? null;
    $allowed = ['active', 'inactive'];
    if (!$api_id || !in_array($status, $allowed)) {
        echo "Invalid data";
        exit;
    }
    if (!is_numeric($api_id)) {
        echo "Invalid ID";
        exit;
    }
    $where= "id=$api_id";
    $csrf=$_POST['csrf_token'];
    $res = $boj->csrf_update("api_keys", ["status"=>$status], $where,$csrf);
    echo $res ? "Status updated" : "Update failed";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


