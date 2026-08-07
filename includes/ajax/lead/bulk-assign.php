<?php
require_once('../../config.php');
$boj->check_session();
// Verify CSRF token
if (!isset($_POST['auth_token']) || $_POST['auth_token'] !== $_SESSION['auth_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Check assign permission
if (!$check->check_permission('leads', 'bulk_assign')) {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    exit;
}

// Get input data
$assign_to = $boj->real_escape_string($_POST['assign_to'] ?? '');
$selected_ids = explode(',', $_POST['selected_ids'] ?? '');

if (empty($assign_to) || empty($selected_ids)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Sanitize IDs
$sanitizedIds = array_map([$boj, 'real_escape_string'], $selected_ids);
$idList = implode(',', $sanitizedIds);

// Update leads
$result = $boj->mysql("UPDATE leads SET assign_to = '$assign_to' ,assign_date=NOW() WHERE id IN ($idList)");


try{
    
$assign_name=$boj->getQuery("select name from user where id='{$assign_to}'");
$admin=$boj->getQuery("select id from user where usertype='root'");
foreach($selected_ids as $leadId){
    $adminUserIds=(array) $admin;
    $vars=[];
    
    $payload = json_encode([
        'lead_id'    => $leadId,
        'assign_to'  => $assign_to,
        'admin_ids'  => $adminUserIds,
        'vars'=>['company_name'=>$company->name,'agent_name'=>$assign_name[0]->name,'crm_link'=>BASEURL,'assigned_time'=>date('D M Y H:i:s a')]
    ]);
    $boj->mysql("INSERT INTO jobs (type, payload) VALUES ('lead_assigned', '$payload')");
    $boj->insertLeadActivityLog($leadId, $getuserdata->id, 'Lead Assigned', 'Lead Assigned to ' . $assign_name[0]->name.' on '.date('D M Y H:i:s '));
}
  
}catch(Exception $e){
    echo json_encode(['success' => false, 'message' => "AutomationService: Failed to send: " . $e->getMessage()]);
}

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Leads assigned successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to assign leads']);
}