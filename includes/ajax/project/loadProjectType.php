<?php
require_once '../../config.php'; // DB connection
// error_reporting(0);
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$searchValue = $_POST['search']['value'];
$orderDir = $_POST['order'][0]['dir'] ?? 'DESC';


$columns = ['pd.id', 'p.pro_name', 'pt.name'];
$orderBy = $columns[$columnIndex] ?? 'pd.id';


$searchQuery = "";
if (!empty($searchValue)) {
    $searchQuery = " AND (pd.field_name LIKE '%$searchValue%') ";
}

## Total records
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM project_type_data pd WHERE 1 $searchQuery";
$totalRecords = $boj->getQuery($totalRecordsQuery)[0]->total;

## Fetch filtered records
$sql = "SELECT pd.*,p.pro_name,pt.name  FROM project_type_data pd inner join project p on p.id=pd.project_id inner join project_types pt on pt.id=pd.project_type_id WHERE 1 $searchQuery 
            ORDER BY $orderBy  $orderDir 
            LIMIT $row, $rowperpage";
// echo $sql;
// die;
$data = $boj->getQuery($sql) ?? [];
$response = [];
$i = $row + 1;

foreach ($data as $rowData) {
    $fields = html_entity_decode($rowData->fields);
    $units = html_entity_decode($rowData->units);
    $fields = json_decode($fields, true);
    $units = json_decode($units, true);

    // Format fields
    $fieldHtml = "";
    if (!empty($fields)) {
        $fieldHtml .= "<ul>";
        foreach ($fields as $key => $val) {
            $fieldHtml .= "<li><b>$key:</b> $val</li>";
        }
        $fieldHtml .= "</ul>";
    } else {
        $fieldHtml = "<em>No Fields</em>";
    }

    // Format units
    $unitHtml = "";
    if (!empty($units)) {
        foreach ($units as $unit) {
            $unitHtml .= "<table class='table table-bordered table-sm mb-1' style='overflow-x:auto'>";
            foreach ($unit as $uk => $uv) {
                $unitHtml .= "<tr><td><b>$uk</b></td><td>$uv</td></tr>";
            }
            $unitHtml .= "</table>";
        }
    } else {
        $unitHtml = "<em>No Units</em>";
    }

    $response[] = [
        "sno" => $i++,
        "project_name" => $rowData->pro_name,
        "project_type" => htmlspecialchars($rowData->name),
        "fields" => $fieldHtml,
        "units" => $unitHtml,
        "action" => '<a href="project/add-project-type/?edit=' . $rowData->id . '" class="btn btn-sm btn-primary">Edit</a>' . '|' . '<a href="project/add-project-type/?delete=' . $rowData->id . '&value' . $rowData->pro_name . '/' . $rowData->name . '" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>'
    ];
}

echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $response
]);
