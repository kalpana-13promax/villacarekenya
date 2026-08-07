<?php
require_once('../config.php');
$boj->check_session();

// Get request parameters
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$searchValue = $boj->real_escape_string($_POST['search']['value']);
$orderColumnIndex = intval($_POST['order'][0]['column']);
$orderDir = $boj->real_escape_string($_POST['order'][0]['dir']);

// Base query to get all sources
$query = "SELECT s.id, s.source_name FROM source s";

// Add search condition if search value exists
if(!empty($searchValue)) {
    $query .= " WHERE s.source_name LIKE '%$searchValue%'";
}

// Get total records count
$totalRecords = $boj->count("SELECT id FROM source");

// Execute query with pagination
$query .= " ORDER BY s.source_name $orderDir LIMIT $start, $length";
$sources = $boj->getQuery($query)??[];

// Prepare response data
$data = [];
foreach($sources as $source) {
    // Get counts for each status
    $total = $boj->count("SELECT id FROM leads WHERE reference='$source->id'");
    $fresh = $boj->count("SELECT id FROM leads WHERE reference='$source->id' AND lead_status='un-attempted'");
    $wip = $boj->count("SELECT id FROM leads WHERE reference='$source->id' AND (lead_status='Visit Planned' OR lead_status='Attempted' OR lead_status='Revisit Planned' OR lead_status='Final Negotiation' OR lead_status='Interested' OR lead_status='Meeting Done')");
    $visited = $boj->count("SELECT id FROM leads WHERE reference='$source->id' AND (lead_status='Visit Done' OR lead_status='Revisit Done')");
    $deal = $boj->count("SELECT id FROM leads WHERE reference='$source->id' AND (lead_status='Booking Done' OR lead_status='Deal Done' OR lead_status='deal-done')");
    $not_interested = $boj->count("SELECT id FROM leads WHERE reference='$source->id' AND (lead_status='Not Interested' OR lead_status='not-interested' OR lead_status='Junk' OR lead_status='Failed')");
    
    // Calculate others
    $others = $total - ($fresh + $wip + $visited + $deal + $not_interested);
    
    $data[] = [
        'source_name' => $source->source_name,
        'total' => $total,
        'fresh' => $fresh,
        'wip' => $wip,
        'visited' => $visited,
        'deal' => $deal,
        'not_interested' => $not_interested,
        'other' => $others
    ];
}

// Prepare response
$response = [
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
];

// Send response
header('Content-Type: application/json');
echo json_encode($response); 