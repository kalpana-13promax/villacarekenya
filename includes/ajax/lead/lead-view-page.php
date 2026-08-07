<?php
require_once('../../config.php');
$boj->check_session();
// error_reporting(0);
// Check permissions
$perm = $check->check_permission('leads', 'view_all');
$view_own = $check->check_permission('leads', 'view_own');

// Get request parameters from DataTables
$start = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$length = isset($_POST['length']) ? (int) $_POST['length'] : 10;
$searchValue = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'] ?? 0;
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';

// Column mapping
$columns = [
  0 => 'l.id',
  1 => 'l.lead_name',
  2 => 'l.lead_contact',
  3 => 'l.lead_call_status',

  4 => 'l.lead_status',
  5 => 'l.assign_to',
  6 => 's.source_name',

  7 => 'l.follow_up_date',
  8 => 'l.category',
  9 => 'l.contract',
  10 => 'l.lead_message',
  11 => 'l.timestamp'
];

// Build base query
$query = "SELECT 
    l.*,
    p.pro_name, pl.property_title, pl.id as property_id, p.id as project_id,
    s.source_name, 
    u.username AS assignto,
    sup.username AS sup_name,
    l.lead_status,
    l.timestamp,
    l.mark_color,
    r.remarks AS last_remark,
    CASE 
        WHEN l.reference = 1 THEN (SELECT agent_name FROM agent WHERE id = l.agent_id)
        WHEN l.reference = 2 THEN (SELECT name FROM user WHERE id = l.agent_id)
        ELSE NULL
    END AS agent_name
FROM leads l 
LEFT JOIN source s ON l.reference = s.id 
LEFT JOIN project p ON l.project = p.id 
LEFT JOIN property_listing pl ON pl.id = l.property_link_id
LEFT JOIN user u ON u.id = l.assign_to 
LEFT JOIN user sup ON sup.id = u.supervisor_id 
LEFT JOIN remarks r 
    ON r.id = (
        SELECT r2.id 
        FROM remarks r2 
        WHERE r2.lead_id = l.id 
        ORDER BY r2.id DESC 
        LIMIT 1
    )
WHERE 1=1";

// Build count query
$countQuery = "SELECT COUNT(l.id) as cnt FROM leads l 
LEFT JOIN source s ON l.reference = s.id 
LEFT JOIN project p ON l.project = p.id 
LEFT JOIN property_listing pl ON pl.id = l.property_link_id
LEFT JOIN user u ON u.id = l.assign_to AND u.usertype != 'root'
WHERE 1=1";


if ($perm == 'true') {
  // View All → no filter
} elseif ($view_own == 'true') {
  // View Own → restrict to own + subordinates
  $userId = $boj->real_escape_string($getuserdata->id);

  $subordinates = $boj->getQuery("SELECT id,username FROM user WHERE supervisor_id = '$userId'");
  $ids = [$userId];
  $userNames = [$getuserdata->username];
  if ($subordinates) {
    foreach ($subordinates as $s) {
      $ids[] = $s->id;
      $userNames[] = $s->username;
    }
  }
  $idList = implode(',', array_map('intval', $ids));
  $userNameList = implode(',', array_map(function ($e) {
    return "'$e'";
  }, $userNames));
  $query .= " AND (l.assign_to IN ($idList) OR l.lead_uploaded_by IN ($userNameList))";
  $countQuery .= " AND (l.assign_to IN ($idList) OR l.lead_uploaded_by IN ($userNameList))";
} else {
  // No permissions → block everything
  $query .= " AND 1=0";
  $countQuery .= " AND 1=0";
}


// Add search filter
if (!empty($searchValue)) {
  $escaped = $boj->real_escape_string($searchValue);
  $searchFilter = " AND (l.lead_name LIKE '%$escaped%' 
    OR l.lead_contact LIKE '%$escaped%'
    OR l.id LIKE '%$escaped%'
    OR l.project LIKE '%$escaped%'
    OR l.property_type LIKE '%$escaped%'
    OR l.lead_status LIKE '%$escaped%'
    OR l.lead_message LIKE '%$escaped%'
    
    OR l.lead_date like '%$escaped%')";
  $query .= $searchFilter;
  $countQuery .= $searchFilter;
}

if (!empty(array_filter($_POST['filter_status']))) {

  // Ensure we have an array
  $fltst_arr = is_array($_POST['filter_status']) ? $_POST['filter_status'] : [$_POST['filter_status']];

  // Escape all statuses to prevent SQL injection
  $escapedStatuses = array_map(function ($status) use ($boj) {
    return "'" . $boj->real_escape_string(trim($status)) . "'";
  }, $fltst_arr);

  // Convert array to comma-separated string for SQL IN clause
  $statusList = implode(',', $escapedStatuses);

  // Build filter condition
  $statusFilter = " AND l.lead_status IN ($statusList) ";

  // Append filter to both queries
  $query .= $statusFilter;
  $countQuery .= $statusFilter;
}


// --- Add filter for Assign To ---
if (!empty(array_filter($_POST['filter_assign']))) {

  // Handle both single and multiple inputs
  $assignArr = is_array($_POST['filter_assign']) ? $_POST['filter_assign'] : [$_POST['filter_assign']];

  if (in_array('unassigned', $assignArr)) {
    $query .= " 
      AND (
          l.assign_to IS NULL 
          OR l.assign_to = '' 
          OR l.assign_to NOT IN (SELECT id FROM user WHERE status = 'active')
      )";
    $countQuery .= " 
      AND (
          l.assign_to IS NULL 
          OR l.assign_to = '' 
          OR l.assign_to NOT IN (SELECT id FROM user WHERE status = 'active')
      )";
  } else {



    // Escape all values
    $escapedAssigns = array_map(function ($assign) use ($boj) {
      return "'" . $boj->real_escape_string(trim($assign)) . "'";
    }, $assignArr);

    // Join for SQL
    $assignList = implode(',', $escapedAssigns);

    // Build condition
    $assignFilter = " AND l.assign_to IN ($assignList) ";

    // Append to queries
    $query .= $assignFilter;
    $countQuery .= $assignFilter;
  }
}

// --- Add filter for Source ---
if (!empty(array_filter($_POST['filter_source']))) {

  // Handle both single and multiple inputs
  $sourceArr = is_array($_POST['filter_source']) ? $_POST['filter_source'] : [$_POST['filter_source']];

  // Escape all values
  $escapedSources = array_map(function ($source) use ($boj) {
    return "'" . $boj->real_escape_string(trim($source)) . "'";
  }, $sourceArr);

  // Join for SQL
  $sourceList = implode(',', $escapedSources);

  // Build condition
  $sourceFilter = " AND l.reference IN ($sourceList) ";

  // Append to queries
  $query .= $sourceFilter;
  $countQuery .= $sourceFilter;
}

// Add filter for project/property
if (!empty($_POST['filter_project'])) {
  $projectFilter = $_POST['filter_project'];
  $parts = explode('_', $projectFilter);
  if (count($parts) == 2) {
    $type = $parts[0];
    $id = $boj->real_escape_string($parts[1]);

    if ($type == 'project') {
      $projectPropertyFilter = " AND l.project = '$id'";
    } else if ($type == 'property') {
      $projectPropertyFilter = " AND l.property_link_id = '$id'";
    }

    if (isset($projectPropertyFilter)) {
      $query .= $projectPropertyFilter;
      $countQuery .= $projectPropertyFilter;
    }
  }
}

// Add filter for date range
if (!empty($_POST['filter_date_from'])) {
  $dateFrom = $boj->real_escape_string($_POST['filter_date_from']);
  $dateFilter = " AND DATE(l.timestamp) >= '$dateFrom'";
  $query .= $dateFilter;
  $countQuery .= $dateFilter;
}

if (!empty($_POST['filter_date_to'])) {
  $dateTo = $boj->real_escape_string($_POST['filter_date_to']);
  $dateFilter = " AND DATE(l.timestamp) <= '$dateTo'";
  $query .= $dateFilter;
  $countQuery .= $dateFilter;
}

// Add filter for property type
if (!empty($_POST['filter_property_type'])) {
  $propertyType = $boj->real_escape_string($_POST['filter_property_type']);
  $propertyTypeFilter = " AND l.property_type = '$propertyType'";
  $query .= $propertyTypeFilter;
  $countQuery .= $propertyTypeFilter;
}

// Add ordering and limit
$cc = $columns[$orderColumn];

if ($cc == 'l.follow_up_date') {
    // Custom logical sort for follow-up column
    $query .= "
        ORDER BY 
        CASE
            WHEN l.followup = 1 THEN 1  
            WHEN l.followup = 0 AND (l.follow_up_date IS NULL  OR l.follow_up_date = '0000-00-00') THEN 2  
            WHEN DATE(l.follow_up_date) = CURDATE() THEN 3  
            WHEN DATE(l.follow_up_date) > CURDATE() THEN 4  
            WHEN DATE(l.follow_up_date) < CURDATE() THEN 5
            ELSE 6  
        END $orderDir,
        l.follow_up_date $orderDir
    ";
} else {
    $query .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
}

$query .= " LIMIT $start, $length";

// Execute queries
$results = $boj->getQuery($query) ?? [];
$countResult = $boj->getQuery($countQuery);
$totalRecords = $countResult[0]->cnt ?? 0;

// Prepare response
$response = [
  "draw" => intval($_POST['draw'] ?? 1),
  "recordsTotal" => $totalRecords,
  "recordsFiltered" => $totalRecords,
  "data" => []
];

$count = $start + 1;

foreach ($results as $row) {


  $leadDateRaw = $row->lead_date;
  $leadDateObj = DateTime::createFromFormat('Y-m-d H:i:s', $leadDateRaw);
  if (!$leadDateObj) {
    $leadDateObj = DateTime::createFromFormat('d/m/Y H:i:s', $leadDateRaw);
  }
  $formattedDate = $leadDateObj ? $leadDateObj->format('d-m-Y h:i:s a') : '';

  $response['data'][] = [
    "id" => $row->id,
    "checkbox" => $row->id,
    "lead_name" => $row->lead_name,
    "lead_contact" => $row->lead_contact,
    "project" => $row->pro_name,
    "project_id" => $row->project_id,
    "property" => $row->property_title,
    "property_id" => $row->interest_property,
    "property_type" => $row->property_type,
    "category" => $row->category,
    "furnished_status" => $row->furnished_status,
    'message' => $row->lead_message,
    "contract" => $row->contract,
    "assign_to" => $row->assignto,
    "source_name" => $row->source_name,
    "agent_name" => $row->agent_name,
    "lead_date" => $row->timestamp ? date('d-m-Y h:i:s a', strtotime($row->timestamp)) : '',
    "lead_status" => $row->lead_status,
    "mark_color" => $row->mark_color,
    'requirement' => '',

    'alt_contact' => $row->alternate_contact,
    'lead_mail' => $row->lead_mail,
    'budget_min' => $row->client_budget_min,
    'budget_max' => $row->client_budget_max,

    'matched_properties' => $boj->matched_property_by_leadid($row->id),
    'last_remark' => $row->last_remark,
    'supervisor_name' => $row->sup_name,
    'call_status' => $row->lead_call_status,
    'follow_up_date' => $row->follow_up_date,
    'follow_up_time' => $row->follow_up_time,
    'follow_up' => $row->followup,
    "actions" => ""
  ];
}

header('Content-Type: application/json');
echo json_encode($response);
