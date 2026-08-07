<?php
header('Content-Type: application/json');
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get leadId from POST
    $leadId = isset($_POST['leadId']) ? intval($_POST['leadId']) : 0;

    // Get logged-in user
    $userId = $getuserdata->username;  // Make sure $getuserdata is properly defined
    $status = 'processing';

    // Validate
    if (empty($leadId) || empty($userId)) {
        echo json_encode(['success' => false, 'message' => 'Lead ID and User are required']);
        exit;
    }

    // Update lead status and last_action_time
    $now = date("Y-m-d H:i:s");
    $sql = "UPDATE leads 
            SET lead_status = '{$status}', last_action_time =  '$now' 
            WHERE id = $leadId  AND lead_status ='un-attempted' AND (assigned_to not null or assign_to !='')";

    $res = $boj->getConnection()->query($sql);
    $boj->insertLeadActivityLog($leadId, $getuserdata->id, 'Lead Status Changed', 'Lead Status Changed to processing on '.date('D M Y H:i:s '));
    if ($res) {
        echo json_encode(['success' => true, 'message' => 'Lead status updated to processing']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $boj->getConnection()->error]);
    }


} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
