<?php
require_once('../../config.php');
$boj->check_session();

// Check permissions
$perm = $check->check_permission('leads', 'view_all');
$view_own = $check->check_permission('leads', 'view_own');

// Get request parameters from DataTables
$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$searchValue = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';

// Column mapping
$columns = [
    0 => 'l.id',
    1 => 'l.id',
    2 => 'l.lead_name',
    3 => 'l.lead_contact',
    4 => 'l.project',
    5 => 'l.property_type',
    6 => 'l.contract',
    7 => 's.source_name',
    8 => 'l.lead_date'
];

// Build base query
$query = "SELECT 
    SQL_CALC_FOUND_ROWS 
    l.*, p.pro_name,pl.property_title,pl.id as property_id,p.id as project_id,
    s.source_name, 
    u.username AS assignto,
    CASE 
        WHEN l.reference = 1 THEN (SELECT agent_name FROM agent WHERE id = l.agent_id)
        WHEN l.reference = 2 THEN (SELECT name FROM user WHERE id = l.agent_id)
        ELSE NULL
    END AS agent_name
FROM leads l 
LEFT JOIN source s ON l.reference = s.id 
LEFT JOIN project p ON l.project = p.id 
LEFT JOIN  property_listing pl on pl.id= l.property_link_id
LEFT JOIN user u ON u.id = l.assign_to AND u.usertype != 'root'
WHERE l.lead_status = 'un-attempted' 
 ";



if ($getuserdata->usertype != 'root' && $perm != 'true' && $view_own == 'true') {
   
    $query .= " AND l.assign_to = 'public'";
} else {
   
    $query .= " AND (l.assign_to = 'public' OR (l.cold = '1' OR l.hot = '0'))";
}



// Add search filter
if (!empty($searchValue)) {
    $query .= " AND (l.lead_name LIKE '%" . $boj->real_escape_string($searchValue) . "%' 
              OR l.lead_contact LIKE '%" . $boj->real_escape_string($searchValue) . "%'
              OR l.id LIKE '%" . $boj->real_escape_string($searchValue) . "%'
              OR l.project LIKE '%" . $boj->real_escape_string($searchValue) . "%'
              OR l.property_type LIKE '%" . $boj->real_escape_string($searchValue) . "%')";
}

// Add ordering
$query .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
$query .= " LIMIT " . $boj->real_escape_string($start) . ", " . $boj->real_escape_string($length);

// Execute query
$results = $boj->getQuery($query) ?? [];
$totalRecords = $boj->count("SELECT FOUND_ROWS()");

// Prepare response
$response = [
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => []
];

foreach ($results as $row) {
    $response['data'][] = [
        "id" => $row->id,
        "checkbox" => $row->id,
        "lead_name" => $row->lead_name,
        "lead_contact" => $row->lead_contact,
        "project" => $row->project,
        "project_id" => $row->project_id,
        "property" => $row->property_title,
        "property_id" => $row->property_id,
        "property_type" => $row->property_type,
        "category" => $row->category,
        "furnished_status" => $row->furnished_status,
        "contract" => $row->contract,
        "assign_to" => $row->assignto,
        "source_name" => $row->source_name,
        "agent_name" => $row->agent_name,
        "lead_date" => date('d-m-Y', strtotime($row->lead_date)),
        "actions" => ""
    ];
}

header('Content-Type: application/json');
echo json_encode($response);