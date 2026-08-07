<?php



require_once('../../config.php');

$boj->check_session();

$view_all = $check->check_permission('properties', 'view_all');

$edit = $check->check_permission('properties', 'edit');

$delete = $check->check_permission('properties', 'delete');

$address = $check->check_permission('properties', 'view_address');

$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'];
$orderDir = $_POST['order'][0]['dir'] ?? 'DESC';

$columns = ['pl.id', 'pl.property_title', 'l.location', 'c.city', 's.sub_location', 'o.name'];
$orderBy = $columns[$orderColumn] ?? 'pl.id';

// Base FROM clause with joins
$baseSQL = "FROM property_listing pl
JOIN city c ON c.id = pl.city
JOIN locations l ON l.id = pl.location
JOIN sub_location s ON s.id = pl.sub_location
JOIN owner o ON o.id = pl.owner_id
LEFT JOIN seo_data sd ON sd.related_id = pl.id AND sd.type = 'property'
LEFT JOIN project pr on pl.project_id= pr.id
WHERE 1=1 and pl.status='4' AND reference_source = '1'";

if ($view_all !== 'true') {
    $baseSQL .= " AND user_id = '{$getuserdata->id}'";
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
        o.name LIKE '%$searchEscaped%'
    )";
}
if (!empty($_POST['user'])) {
    $filter = $boj->real_escape_string($_POST['user']);
    $searchQuery .= " And pl.uploader = '$filter'";
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
    sd.slug,
    pr.pro_name
    $baseSQL 
    $searchQuery 
ORDER BY $orderBy $orderDir 
LIMIT $start, $length";
// echo $dataSQL;
// die;

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

foreach ($rows as $row) {

    $file = $path . $row->property_image;
    if (!empty($row->property_image) && file_exists($file)) {
        $img = $file;
    } else {

        $img = DEFAULTIMG;
    }



    $available_for = !empty($row->available_for) ? $boj->contract('property', $row->available_for) : '';

    //type

    $type = $row->property_type ?? '';
    $category = $boj->getid('property_type', $row->category);
    $hr = '<hr>';
    $type .= $hr . $category[0]->type . $hr . $row->furnished_status . $hr;

    $ref = $boj->getid('source', $row->reference_source);
    $agentOwner = '';
    if ($row->reference_source == '1' && !empty($value->referance_agent)) {

        $agent = $boj->getid("agent", $value->referance_agent);

        if ($agent[0]->agent_name) {

            $agentOwner .= $agent[0]->agent_name . $hr;
        }
        ;
    } else {
        $agentOwner .= "Deleted!$hr";
    }
    $agentOwner .= "<small><i>{$ref[0]->source_name}</i></small>$hr";
    if ($getuserdata->usertype == 'root') {
        if ($row->owner_id) {

            $owner = $boj->getid('owner', $row->owner_id);



            $agentOwner .= "<a href='owner/owner-view/?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a>";

        }
    }

    $status = $boj->getid('property_status', $row->status ?? '') ?? [];
    $color = $statusColors[$row->status - 1] ?? 'default';

    $status = "<span class='label label-$color ' style='font-size:inherit'>" . $status[0]->status_name . "</span>";
    $data[] = [
        $i++,
        $row->id,
        "<img src='" . $img . "' style='width: 100px;height: 80px;'>",
        ucwords($row->property_title ?? ''),
        ucwords($row->pro_name ?? ''),
        ucwords($type ?? ''),
        ucwords($boj->price($row->property_price) ?? 0) . $hr . 'Security/Deposit: ' . $boj->price($value->deal_price ?? 0),
        $row->uploader ?? '',
        $status ?? '',

        $boj->shareEntity($row->id, 'property')
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);

