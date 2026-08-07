<?php
require_once('../../config.php');
$boj->check_session();

// Enable error reporting for debugging
error_reporting(0);
// ini_set('display_errors', 1);

header('Content-Type: application/json');

// Validate and sanitize inputs
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? $boj->real_escape_string($_POST['search']['value']) : '';
$order_column = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$order_dir = in_array(strtoupper($_POST['order'][0]['dir'] ?? ''), ['ASC', 'DESC']) ? $_POST['order'][0]['dir'] : 'ASC';

// Permission checks
$view_all = $check->check_permission('visit', 'view_global');
$view_own = $_POST['view_own'] ?? 'false';
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

// Base query with improved security
$base_query = "FROM visits v 
               LEFT JOIN property_listing p ON v.property_to_visit = p.id 
               LEFT JOIN leads l ON v.lead_id = l.id 
               LEFT JOIN user u1 ON v.assign_to = u1.id 
               LEFT JOIN user u2 ON v.assign_by = u2.id";

// Where conditions
$where = [];
// Permission logic
if ($view_all === 'true') {
    // No additional filter; user can see all
} elseif ($view_own === 'true') {

    // View Own → restrict to own + subordinates
    $userId = $boj->real_escape_string($getuserdata->id);

    $subordinates = $boj->getQuery("SELECT id FROM user WHERE supervisor_id = '$userId'");
    $ids = [$userId];
    if ($subordinates) {
        foreach ($subordinates as $s) {
            $ids[] = $s->id;
        }
    }
    $idList = implode(',', array_map('intval', $ids));
    $where[] = "v.assign_to  IN ($idList)";
    // $where[] = "v.scheduled_date = CURDATE()";
} else {
    // No permission: prevent data leak
    $where[] = "1=0"; // return empty result
}

if (isset($_POST['filter'])) {

    $filter = $boj->real_escape_string($_POST['filter']);
    if ($filter == 'today') {
        $where[] = "v.scheduled_date = CURDATE()";
    } elseif ($filter == 'past') {
        $where[] = "v.scheduled_date < CURDATE()";

    } elseif ($filter == 'future') {
        $where[] = "v.scheduled_date > CURDATE()";
    } elseif ($filter == 'all') {
        // No additional filter; user can see all
    } elseif ($filter == 'open') {
        $where[] = "v.visit_status IS NULL";

    } elseif ($filter == "done") {
        $where[] = "v.visit_status = 'done'";
    }
}

if (!empty($search)) {
    $where[] = "(p.property_title LIKE '%$search%' 
                 OR l.lead_name LIKE '%$search%'
                 OR u1.username LIKE '%$search%'
                 OR u2.username LIKE '%$search%')";
}

$where_clause = empty($where) ? "" : "WHERE " . implode(" AND ", $where);

// Count queries with prepared statements
$total_records = $boj->getQuery("SELECT COUNT(*) as count FROM visits")[0]->count ?? 0;
$filtered_records = $boj->getQuery("SELECT COUNT(*) as count $base_query $where_clause")[0]->count ?? 0;

// Order handling with validation
$columns = [
    'v.id',  // Added ID column for proper ordering
    'p.property_title',
    'v.scheduled_date',
    'v.remarks',
    'u1.username',
    'u2.username',
    'v.visit_status'
];
$order_column = $columns[$order_column] ?? 'v.id';
$order_by = "ORDER BY $order_column $order_dir";

// Main query with explicit field selection
$query = "SELECT 
            v.id,
            v.property_to_visit,
            v.scheduled_date,
            v.scheduled_time,
            v.remarks,
            v.visit_status,
            v.visit_date,
            v.location,
            v.visited_selfie,
            p.property_title,
            l.lead_name as lead_name,
            l.id as lead_id,
            l.lead_contact as lead_contact,
            u1.username as assigned_to_name,
            u2.username as assigned_by_name,
            v.timestamp
          $base_query 
          $where_clause 
          $order_by 
          LIMIT $start, $length";

// echo $query; die;
$result = $boj->getQuery($query) ?? [];
$data = [];
foreach ($result as $row) {

    if (!empty($row->visited_selfie)) {
        $visited_selfie = htmlspecialchars($row->visited_selfie);
        $visited_selfie_url = dirname(BASEURL, 1) . '/uploads/' . $visited_selfie;

    }
    $data[] = [
        'id' => (int) $row->id,
        'property' => htmlspecialchars($row->property_title),
        'property_to_visit' => (int) $row->property_to_visit,
        'lead_name' => htmlspecialchars($row->lead_name),
        'lead_id' => (int) $row->lead_id,
        'lead_contact' => htmlspecialchars($row->lead_contact),
        'scheduled_date' => $boj->format_date($row->scheduled_date),
        'scheduled_time' => htmlspecialchars($row->scheduled_time),
        'remarks' => htmlspecialchars($row->remarks),
        'assigned_to' => htmlspecialchars($row->assigned_to_name),
        'assigned_by' => htmlspecialchars($row->assigned_by_name) . " " . $boj->format_date($row->timestamp),
        'visit_status' => htmlspecialchars($row->visit_status),
        'visit_date' => $row->visit_date ? $boj->format_date($row->visit_date) : null
        ,
        'action' => '',
        'location' => htmlspecialchars($row->location),
        'visited_selfie' => $visited_selfie_url ?? ''

    ];
}


// Final JSON response
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => (int) $total_records,
    "recordsFiltered" => (int) $filtered_records,
    "data" => $data
]);
exit;