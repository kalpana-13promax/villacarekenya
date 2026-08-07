<?php
// Database connection
require_once('../../config.php');

// Check user session
$boj->check_session();





// Check if the DataTables server-side parameters are present
$records_per_page = isset($_POST['length']) ? (int)$_POST['length'] : 10;  // Records per page
$current_page = isset($_POST['start']) ? (int)$_POST['start'] / $records_per_page + 1 : 1; // Current page
$offset = ($current_page - 1) * $records_per_page; // Calculate offset

// Get the filter form data (these fields should be part of the form)
 $unit_no = isset($_POST['unit_no']) ? $_POST['unit_no'] : '';
$owner_name = isset($_POST['owner_name']) ? $_POST['owner_name'] : '';
$father = isset($_POST['father']) ? $_POST['father'] : '';
$phone = isset($_POST['phone_no']) ? $_POST['phone_no'] : '';
$min_price = isset($_POST['min_price']) ? $_POST['min_price'] : '';
$max_price = isset($_POST['max_price']) ? $_POST['max_price'] : '';
$min_size = isset($_POST['min_size']) ? $_POST['min_size'] : '';
$max_size = isset($_POST['max_size']) ? $_POST['max_size'] : '';

// Search and filter parameters (coming from DataTables)
$search_value = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';  // Search query
$order_column = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Column to order by
$order_dir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'ASC';  // Sort direction

// Mapping column index to database column
$order_columns = ['pl.unit_no', 'o.name', 'pl.property_price', 'pl.size']; // Assuming columns are Property No., Owner's Name, Cost, Size

// Construct base SQL query with pagination and filters
$sql = "
SELECT 
    pl.*, 
    pri.priority AS prinmame,
    pr.latest_remark AS premark,
    pr.latest_date AS pdate,
    o.name AS owner_name, 
    o.father AS father, 
    o.contact AS contact, 
    o.alternate_contact AS alt_contact 
FROM 
    property_listing AS pl 
JOIN 
    owner AS o ON pl.owner_id = o.id 
JOIN 
    priority AS pri ON pl.priority = pri.id 
LEFT JOIN 
    (SELECT 
        property_id, 
        remarks AS latest_remark, 
        date AS latest_date 
     FROM 
        property_remarks 
     WHERE 
        (property_id, date) IN 
            (SELECT 
                property_id, 
                MAX(date) 
             FROM 
                property_remarks 
             GROUP BY 
                property_id)) AS pr ON pl.unit_no = pr.property_id 
WHERE 
    pl.status != '7'";

// Apply custom filter conditions
if (!empty($unit_no)) {
    $sql .= " AND pl.unit_no = $unit_no";
}
if (!empty($owner_name)) {
    $sql .= " AND o.name LIKE '%$owner_name%'";
}
if (!empty($father)) {
    $total_query .= " AND o.father LIKE '%$father%'";
}
if (!empty($phone)) {
    $total_query .= " AND o.contact = $phone";
}
if (!empty($min_price)) {
    $sql .= " AND pl.property_price >= $min_price";
}
if (!empty($max_price)) {
    $sql .= " AND pl.property_price <= $max_price";
}
if (!empty($min_size)) {
    $sql .= " AND pl.size >= $min_size";
}
if (!empty($max_size)) {
    $sql .= " AND pl.size <= $max_size";
}

// Add search condition if search query exists
if (!empty($search_value)) {
    $sql .= " AND (pl.unit_no LIKE '%$search_value%' OR o.name LIKE '%$search_value%' OR o.father LIKE '%$search_value%')";
}

// Add ordering condition
$sql .= " ORDER BY " . $order_columns[$order_column] . " $order_dir";

// Add limit and offset for pagination
$sql .= " LIMIT $records_per_page OFFSET $offset";

// Execute the query
$results = $boj->getQuery($sql);

// Fetch total records with filters applied
$total_query = "
SELECT COUNT(*) AS total 
FROM property_listing AS pl
JOIN owner AS o ON pl.owner_id = o.id
WHERE pl.status != '7'";

// Apply custom filter conditions for total count
if (!empty($unit_no)) {
    $total_query .= " AND pl.unit_no = $unit_no";
}
if (!empty($owner_name)) {
    $total_query .= " AND o.name LIKE '%$owner_name%'";
}
if (!empty($father)) {
    $total_query .= " AND o.father LIKE '%$father%'";
}
if (!empty($phone)) {
    $total_query .= " AND o.contact = $phone";
}
if (!empty($min_price)) {
    $total_query .= " AND pl.property_price >= $min_price";
}
if (!empty($max_price)) {
    $total_query .= " AND pl.property_price <= $max_price";
}
if (!empty($min_size)) {
    $total_query .= " AND pl.size >= $min_size";
}
if (!empty($max_size)) {
    $total_query .= " AND pl.size <= $max_size";
}

$total_result = $boj->getQuery($total_query);
$total_records = $total_result[0]->total;

// Prepare DataTables response
$response = [
    "draw" => isset($_POST['draw']) ? $_POST['draw'] : 1,  // Draw counter from DataTables
    "recordsTotal" => $total_records,  // Total number of records without filtering
    "recordsFiltered" => $total_records,  // Total number of records after filtering
    "data" => []  // This will hold the data for the table
];

// Map the results to DataTables columns
foreach ($results as $row) {
    $response['data'][] = [
        'unit_no' => $row->unit_no,
        'owner_name' => $row->owner_name,
        'property_price' => $row->property_price,
        'size' => $row->size,
        'priority' => $row->prinmame,
        'latest_remark' => $row->premark,
        'latest_date' => $row->pdate,
        'contact' => $row->contact,
        'alt_contact' => $row->alt_contact
    ];
}

// Return the response as JSON
echo json_encode($response);
?>
