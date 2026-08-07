<?php



require_once('../../config.php');

$boj->check_session();
error_reporting(0);

$view_all = $check->check_permission('properties', 'view_all');

$edit = $check->check_permission('properties', 'edit');

$delete = $check->check_permission('properties', 'delete');

$view_own = $check->check_permission('properties', 'view_own');



$owner_all = $check->check_permission('owners', 'view_all');

$owner_own = $check->check_permission('owners', 'view_own');

$view_assign_property = $check->check_permission('properties', 'view_assign_property');



$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'];
$orderDir = $_POST['order'][0]['dir'] ?? 'DESC';
$filters = $_POST['filters'] ?? [];


$columns = ['pl.id', 'pl.property_title', 'l.location', 'c.city', 's.sub_location', 'o.name'];
$orderBy = $columns[$orderColumn] ?? 'pl.id';

// Base FROM clause with joins
$baseSQL = "FROM property_listing pl
left JOIN city c ON c.id = pl.city
left JOIN locations l ON l.id = pl.location
left JOIN sub_location s ON s.id = pl.sub_location
left JOIN owner o ON o.id = pl.owner_id
LEFT JOIN seo_data sd ON sd.related_id = pl.id AND sd.type = 'property'
left join project pr on pl.project_id= pr.id
left join property_status ps on pl.status =ps.id and is_active='1'
WHERE 1=1 ";



// Apply filters
if (!empty($filters)) {
  foreach ($filters as $filterType => $filterValue) {
    switch ($filterType) {
      case 'type':
        $baseSQL .= " AND pl.property_type = '" . $boj->real_escape_string($filterValue) . "'";
        break;
      case 'available_for':
        $baseSQL .= " AND pl.available_for = '" . $boj->real_escape_string($filterValue) . "'";
        break;
      case 'staff':
        $baseSQL .= " AND pl.user_id = " . intval($filterValue);
        break;
      case 'status':
        $baseSQL .= " AND ps.id = '" . $boj->real_escape_string($filterValue) . "'";
        break;
    }
  }
}

// Search query
$searchQuery = "";
if (!empty($search)) {
  $searchEscaped = $boj->real_escape_string($search);
  $searchQuery .= " AND (
        pl.property_title LIKE '%$searchEscaped%' OR 
        l.location LIKE '%$searchEscaped%' OR 
        c.city LIKE '%$searchEscaped%' OR 
        s.sub_location LIKE '%$searchEscaped%' OR
        o.name LIKE '%$searchEscaped%' or
        pl.property_type LIKE '%$searchEscaped%' or
        ps.status_name LIKE '%$searchEscaped%' or
        pl.available_for LIKE '%$searchEscaped%' or
        pr.pro_name LIKE '%$searchEscaped%' 
    )";
}

// Total records count
$totalRecordsRes = $boj->getQuery("SELECT COUNT(DISTINCT pl.id) as count $baseSQL");
$totalRecords = $totalRecordsRes[0]->count ?? 0;

// Filtered records count
$totalFilteredRes = $boj->getQuery("SELECT COUNT(DISTINCT pl.id) as count $baseSQL $searchQuery");
$totalFiltered = $totalFilteredRes[0]->count ?? 0;

// Main data query
$dataSQL = "SELECT 
    pl.*,
    c.city,
    l.location,
    s.sub_location,
    o.name AS owner_name,
    pr.pro_name,
    sd.slug,
    ps.status_name
    
    $baseSQL 
    $searchQuery 
ORDER BY $orderBy $orderDir 
LIMIT $start, $length";

$rows = $boj->getQuery($dataSQL) ?? [];
$path = IMGPATH;
$data = [];
$i = $start + 1;

$statusColors = [
  'success',
  'primary',
  'warning',
  'info',
  'danger',
  'default',

];

function tag($color, $data)
{
  return (!empty($data) ? "<span class='label label-$color ' style='font-size:11px;display:inline-flex'>" . $data . "</span>" : '');
}

foreach ($rows as $row) {



  $file = $path . $row->property_image;
  if (!empty($row->property_image) && file_exists($file)) {
    $img = $file;
  } else {

    $img = DEFAULTIMG;
  }



  $available_for = !empty($row->available_for) ? tag('primary', $boj->contract('property', $row->available_for)) : '';

  //typ
  $category = $boj->getid('property_type', $row->category);
  $hr = '<hr>';
  $type = [$row->property_type, $category[0]->type, $row->furnished_status];
  $type = array_map('tag', $statusColors, $type);

  // $ref = $boj->getid('source', $row->reference_source);
  // $agentOwner = '';
  // if ($row->reference_source == '1' && !empty($value->referance_agent)) {

  //   $agent = $boj->getid("agent", $value->referance_agent);

  //   if ($agent[0]->agent_name) {

  //     $agentOwner .= $agent[0]->agent_name . $hr;
  //   }
  //   ;
  // } else {
  //   $agentOwner .= "Deleted!$hr";
  // }
  // $agentOwner .= "<small><i>{$ref[0]->source_name}</i></small>$hr";
  // if ($owner_all == 'true' or $owner_own == 'true') {
  //   if ($row->owner_id) {

  //     $owner = $boj->getid('owner', $row->owner_id);



  //     $agentOwner .= "<a href='owner/owner-view/?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a>";

  //   }
  // }

  $address = [$row->address, $row->sub_location, $row->location, $row->city];
  $addre = implode(' ,', array_filter($address));

  $color = $statusColors[$row->status - 1] ?? 'default';

  $status = "<span class='label label-$color ' style='font-size:inherit'>" . $row->status_name . "</span>";
  $data[] = [
    'sno' => $i++,
    'property_title' => $row->property_title ?? '',
    'address' => ucwords($addre ?? ''),

    'property_remark' => ucwords($row->remark ?? ''),
    'available_for' => $available_for,
    'type' => implode(' ', array_filter($type)),

    'price' => ucwords($boj->price($row->property_price) ?? 0),
    'status' => $status ?? '',

    'action' => $boj->shareEntity($row->id, 'property'),
    'project_name' => $row->pro_name,
    'image' => $img,
    'id' => $row->id


  ];

}

echo json_encode([
  "draw" => $draw,
  "recordsTotal" => $totalRecords,
  "recordsFiltered" => $totalFiltered,
  "data" => $data,
]);
