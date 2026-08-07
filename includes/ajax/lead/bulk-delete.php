<?php
require_once('../../config.php');
$boj->check_session();

// Verify CSRF token
if (!isset($_POST['auth_token']) || $_POST['auth_token'] !== $_SESSION['auth_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Check delete permission
if (!$check->check_permission('leads', 'delete')) {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    exit;
}

// Get lead IDs
$ids = $_POST['ids'] ?? [];
if (empty($ids)) {
    echo json_encode(['success' => false, 'message' => 'No leads selected']);
    exit;
}

// Sanitize IDs
$sanitizedIds = array_map([$boj, 'real_escape_string'], $ids);
$idList = implode(',', $sanitizedIds);

// Delete leads
$result = $boj->mysql("DELETE FROM leads WHERE id IN ($idList)");
foreach($ids as $leadId){
    $boj->insertLeadActivityLog($leadId, $getuserdata->id, 'Lead Deleted', 'Lead Deleted on '.date('D M Y H:i:s '));
}



if ($result) {
    echo json_encode(['success' => true, 'message' => 'Selected leads deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete leads']);
}