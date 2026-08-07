<?php
require_once('../../config.php');
$boj->check_session();

header('Content-Type: application/json');

$property_id = $_POST['property_id'] ?? null;

if (!$property_id) {
    echo json_encode(['success' => false, 'message' => 'Property ID required']);
    exit;
}

$result = $boj->matched_leads_by_property_id($property_id);

echo json_encode([
    'success' => true,
    'matched' => $result['matched'],
    'leads' => $result['leads'],
    'query' => $result['q'],
    'error' => $result['error'],
]);