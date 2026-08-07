<?php
require_once('../../config.php');

$boj->check_session();
error_reporting(0);

if (!isset($_POST['visit_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Visit ID is required']);
    exit;
}

$visit_id = intval($_POST['visit_id']);

try {
    $visit = $boj->getQuery("SELECT * FROM visits WHERE id ='{$visit_id}'")[0]??[];
    
    if (!$visit) {
        echo json_encode(['status' => 'error', 'message' => 'Visit not found']);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'id' => $visit->id,
            'property_id' => $visit->property_to_visit,
            'lead_id' => $visit->lead_id,
            'scheduled_date' => date('Y-m-d', strtotime($visit->scheduled_date)),
            'scheduled_time' => date('H:i', strtotime($visit->scheduled_time)),
            'assign_to' => $visit->assign_to,
            'remarks' => $visit->remarks
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching visit details']);
} 