<?php

require_once('../../config.php');





if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if auth token is valid
    if (!isset($_POST['auth_token'])) {
        echo "invalid_token";
        exit;
    }

    if (!isset($_POST['lead_ids']) || empty($_POST['lead_ids'])) {
        echo "no_ids";
        exit;
    }


    // Check delete permission
if (!$check->check_permission('leads', 'delete')) {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    exit;
}

    $lead_ids = $_POST['lead_ids'];
    $lead_ids = array_map('intval', $lead_ids);
    $placeholders = implode(',', $lead_ids);

    $data = $boj->delQuery("delete FROM leads where id in ($placeholders) ");
    foreach($lead_ids as $leadId){
        $boj->insertLeadActivityLog($leadId, $getuserdata->id, 'Lead Deleted', 'Lead Deleted on '.date('D M Y H:i:s '));
    }

    if ($data) {

        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error!"]);
    }

}

?>