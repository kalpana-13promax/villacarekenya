<?php
require_once('../../config.php');

$boj->check_session();

// Verify CSRF token
if (!$boj->verifyCsrf($_POST['csrf_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid security token']);
    exit;
}

// Validate required fields
$required_fields = ['visit_id', 'property_id', 'lead_id', 'scheduled_date', 'scheduled_time', 'assign_to'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

try {
    $visit_id = intval($_POST['visit_id']);
    $property_id = intval($_POST['property_id']);
    $lead_id = intval($_POST['lead_id']);
    $assign_to = intval($_POST['assign_to']);
    $scheduled_date = $boj->real_escape_string($_POST['scheduled_date']);
     $scheduled_time = $boj->real_escape_string($_POST['scheduled_time']);
    $remarks = isset($_POST['remarks']) ? $boj->real_escape_string($_POST['remarks']) : '';
    
    // Combine date and time
    $scheduled_on = date('Y-m-d H:i:s', strtotime($scheduled_date . ' ' . $scheduled_time));
    
    // Check if the scheduled time is in the past
    if (strtotime($scheduled_on) < time()) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot schedule visit in the past']);
        exit;
    }
    // Check for schedule conflicts
    $conflict_check = $boj->getQuery(
        "SELECT id FROM visits WHERE property_to_visit = '{$property_id}' AND scheduled_date = '{$scheduled_date}' AND scheduled_time = '{$scheduled_time}' AND id != '{$visit_id}'",
    );
    if (!empty($conflict_check[0]->id)) {
        echo json_encode(['status' => 'error', 'message' => 'This property is already scheduled for the selected time']);
        exit;
    }
    
    // Update the visit
    $update_data = [
        'property_to_visit' => $property_id,
        'lead_id' => $lead_id,
        'scheduled_date' => $scheduled_date,
        'scheduled_time' => $scheduled_time,
        'assign_to' => $assign_to,
        'remarks' => $remarks,

    
    ];
    $where = "id = '{$visit_id}'";
    $csrf_token = $_POST['csrf_token'];
    $boj->csrf_update('visits', $update_data, $where, $csrf_token);

    // Insert activity log
    $description = sprintf(
        "%s updated the visit for property %s on %s at %s%s",
        $getuserdata->username,
        $property_id,
        date("d M Y", strtotime($scheduled_date)),
        date("h:i A", strtotime($scheduled_time)),
        
        !empty($remarks) ? " — Remarks: " . $remarks : ""
    );
    $boj->insertLeadActivityLog($lead_id, $getuserdata->id, 'Visit Updated', $description);
    
    if($boj->getConnection()->affected_rows > 0){
        echo json_encode([
            'status' => 'success',
            'message' => 'Visit updated successfully'
        ]);
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Visit not updated'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error updating visit']);
} 