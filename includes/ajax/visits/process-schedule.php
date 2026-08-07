<?php
require_once('../../config.php');
$boj->check_session();

try {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || !$boj->verifyCsrf($_POST['csrf_token'])) {
        throw new Exception('Invalid CSRF token. Please refresh the page and try again.');
    }

    // Validate required fields
    $required_fields = ['property_id', 'lead_id', 'scheduled_date', 'scheduled_time'];
    $errors = [];

    foreach($required_fields as $field) {
        if(empty($_POST[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
        }
    }

    if(!empty($errors)) {
        throw new Exception(implode('<br>', $errors));
    }

    // Sanitize inputs
    $property_id = intval($_POST['property_id']);
    $lead_id = intval($_POST['lead_id']);
    $scheduled_date = $boj->real_escape_string($_POST['scheduled_date']);
    $scheduled_time = $boj->real_escape_string($_POST['scheduled_time']);
    $assign_to = intval($_POST['assign_to']??0);
    $remarks = $boj->real_escape_string($_POST['remarks']);

    // Validate date and time
    if(strtotime($scheduled_date) < strtotime(date('Y-m-d'))) {
        throw new Exception('Schedule date cannot be in the past');
    }

    // Check for existing schedule conflicts
    $conflict_check = "SELECT COUNT(*) as count FROM visits 
                      WHERE property_to_visit = $property_id 
                      AND scheduled_date = '$scheduled_date' 
                      AND scheduled_time = '$scheduled_time'";
    $conflict_result = $boj->getQuery($conflict_check);

    if($conflict_result[0]->count > 0) {
        throw new Exception('This time slot is already booked for the selected property');
    }

    // Insert new schedule
    $data = [
        'property_to_visit' => $property_id,
        'lead_id' => $lead_id,
        'scheduled_date' => $scheduled_date,
        'scheduled_time' => $scheduled_time,
        'assign_to' => $assign_to,
        'assign_by' => $getuserdata->id,
        'remarks' => $remarks,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    $csrf=$_POST['csrf_token'];
    $result = $boj->csrf_insert('visits', $data,$csrf);


    if(!$result) {
        throw new Exception('Failed to schedule tour. Please try again.');
    }

    // Insert activity log
  $description = sprintf(
    "%s scheduled a property tour for %s at %s%s",
    $getuserdata->username,
    date("d M Y", strtotime($scheduled_date)),
    date("h:i A", strtotime($scheduled_time)),
    !empty($remarks) ? " — Remarks: " . $remarks : ""
);

    $boj->insertLeadActivityLog($lead_id, $getuserdata->id, 'Tour Scheduled', $description);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Tour scheduled successfully'
    ]);

} catch (Exception $e) {
    // Log the error
    error_log("Schedule Tour Error: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (Error $e) {
    // Log the error
    error_log("Schedule Tour Fatal Error: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'message' => 'An unexpected error occurred. Please try again later.'
    ]);
} 