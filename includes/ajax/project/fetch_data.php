<?php
include_once '../../config.php';
error_reporting(0);
$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'];
$orderColumn = $_POST['order'][0]['column'];
$orderDir = $_POST['order'][0]['dir'] ?? 'DESC';

$columns = ['p.id', 'p.pro_name', 'l.location', 'c.city', 'matched_properties', 'action'];
$orderBy = $columns[$orderColumn] ?? 'p.id';
$filters = $_POST['filters'] ?? [];

// Base query
$baseSQL = "FROM project p
LEFT JOIN city c ON p.city = c.id
LEFT JOIN locations l ON p.location = l.id
LEFT JOIN sub_location s ON p.sub_location = s.id
LEFT JOIN property_listing pl ON p.id = pl.project_id
LEFT JOIN project_status ps ON ps.id = p.status

WHERE p.for_ploting = '0' ";



// Apply filters
if (!empty($filters)) {
    foreach ($filters as $filterType => $filterValue) {
        switch ($filterType) {

            case 'location':
                $baseSQL .= " AND p.location = " . intval($filterValue);
                break;
            case 'status':
                $baseSQL .= " AND p.status = '" . intval($filterValue) . "'";
                break;
        }
    }
}

// Count total records
$totalRecordsRes = $boj->getQuery("SELECT COUNT(DISTINCT p.id) as count $baseSQL");
$totalRecords = $totalRecordsRes[0]->count;

// Search filter
$searchQuery = "";
if (!empty($search)) {
    $searchQuery = " AND (p.pro_name LIKE '%$search%' OR l.location LIKE '%$search%' OR c.city LIKE '%$search%')";
}

// Count after filtering
$filteredSQL = "SELECT COUNT(DISTINCT p.id) as count $baseSQL $searchQuery";
$totalFiltered = $boj->getQuery($filteredSQL);
$totalFiltered = $totalFiltered[0]->count;

// Fetch filtered, ordered, paginated data
$dataSQL = "SELECT p.*,
    p.id AS project_id, 
    p.pro_name AS project_name, 
    c.city, 
    ps.status as project_status, 
    l.location, 
    COUNT(pl.id) AS matched_properties
    $baseSQL
    $searchQuery
GROUP BY p.id 
ORDER BY $orderBy $orderDir 
LIMIT $start, $length";
// echo $dataSQL;
// die;
$result = $boj->getQuery($dataSQL) ?? [];


$data = [];
$i = $start + 1;
foreach ($result as $key => $value) {
    $project_id = $value->project_id;


    $file = IMGPATH. $value->pro_image;
    if (!empty($value->pro_image)) {
       $img = $file;
  
    } else {
        $img = DEFAULTIMG;
    }

    $loc = array_filter(array_map('ucwords', [$value->location, $value->city]));
    // $actions = $boj->shareEntity($project_id, 'project');
  

    $data[] = [
        'sno' => $i++,
        'pro_name' => ucwords($value->project_name ?? ''),
        'address' => implode(',', $loc),
        'properties' => "<a href='{$dir}project-properties/?filter=$project_id'>{$value->matched_properties}</a>",
        'action' => $boj->shareEntity($value->project_id, 'project'),
        'image' => $img,
        'id' => $value->project_id,
        'status' => $value->project_status,

    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
