<?php
require_once('../../config.php');
$boj->check_session();

// Get request parameters
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$searchValue = $boj->real_escape_string($_POST['search']['value']);
$orderColumnIndex = intval($_POST['order'][0]['column']);
$orderDir = $boj->real_escape_string($_POST['order'][0]['dir']);

// Base query
$query = "SELECT 
            l.id, 
            l.lead_name AS name,
            l.lead_contact AS contact,
            ls.name AS status,
            l.lead_call_status AS call_status,
            s.source_name AS source,
            l.lead_date AS created_at
          FROM leads l
          LEFT JOIN lead_status ls ON l.lead_status = ls.name
          LEFT JOIN source s ON l.reference = s.id
          WHERE 1=1";

// Add filters
if(!empty($_POST['source'])) {
    $source = $boj->real_escape_string($_POST['source']);
    $query .= " AND s.id = '$source'";
}

if(!empty($_POST['status'])) {
    $status = $boj->real_escape_string($_POST['status']);
    $query .= " AND ls.name = '$status'";
}

if(!empty($_POST['call_status'])) {
    $callStatus = $boj->real_escape_string($_POST['call_status']);
    $query .= " AND l.lead_call_status = '$callStatus'";
}

// Handle global search
if(!empty($searchValue)) {
    $query .= " AND (l.lead_name LIKE '%$searchValue%' 
                OR l.lead_contact LIKE '%$searchValue%'
                OR s.source_name LIKE '%$searchValue%')";
}

// Add ordering
$columns = ['l.id', 'l.lead_name', 'l.lead_contact', 'ls.name', 'l.lead_call_status', 's.source_name', 'l.lead_date'];
$orderColumn = $columns[$orderColumnIndex] ?? 'l.id';
$query .= " ORDER BY $orderColumn $orderDir";

// Add pagination
$query .= " LIMIT $start, $length";

// Execute query
$leads = $boj->getQuery($query);

// Get total records count
$totalRecords = $boj->getQuery("SELECT COUNT(*) AS total FROM leads")[0]->total;

// Prepare response
$response = [
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords, // Simplified for example
    "data" => []
];

foreach($leads as $lead) {
    $response['data'][] = [
        'id' => $lead->id,
        'name' => $lead->name,
        'contact' => $lead->contact,
        'status' => $lead->status,
        'call_status' => $lead->call_status,
        'source' => $lead->source,
        'created_at' => $lead->created_at
    ];
}

header('Content-Type: application/json');
echo json_encode($response);