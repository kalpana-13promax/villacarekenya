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

$measurement_unit = isset($_POST['measurement_unit']) ? $_POST['measurement_unit'] : '';
$available_for = isset($_POST['available_for']) ? $_POST['available_for'] : [];
$property_type = isset($_POST['property_type']) ? $_POST['property_type'] : [];
$category = isset($_POST['category']) ? $_POST['category'] : [];
$furnished_status = isset($_POST['furnished_status']) ? $_POST['furnished_status'] : [];
$status = isset($_POST['status']) ? $_POST['status'] : [];

$project = isset($_POST['project']) ? $_POST['project'] : [];
$city = isset($_POST['city']) ? $_POST['city'] : [];
$property_location = isset($_POST['property_location']) ? $_POST['property_location'] : [];
$sub_location = isset($_POST['sub_location']) ? $_POST['sub_location'] : [];
$quota = isset($_POST['quota']) ? $_POST['quota'] : [];
$priority = isset($_POST['priority']) ? $_POST['priority'] : [];



// Search and filter parameters (coming from DataTables)
$search_value = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';  // Search query
$order_column = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Column to order by
$order_dir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'ASC';  // Sort direction


// Mapping column index to database column
$order_columns = ['pl.unit_no', 'pl.property_title', 'owner_details', 'pl.location', 'pl.property_type', 'pri.priority', 'pl.available_for', 'pl.size', 'pl.property_price', 'premark', 'pl.status']; // Assuming columns are Property No., Owner's Name, Cost, Size

$fafa = '<span style="white-space:nowrap;"><i class="fa fa-calendar"></i>';
// Construct base SQL query with pagination and filters
$sql = "
SELECT 
    pl.*, 
    CONCAT (pl.property_type, '<br />- ', pt.type, '<br />- ', pl.furnished_status) as pType,
    pri.priority AS prinmame,
    CONCAT (pr.latest_remark, '{$fafa} ', pr.latest_date, '</span>') as premark,
    CONCAT(o.name, '<br />', o.contact, '<br />', o.alternate_contact, '<br />(', o.father, ')') AS owner_details,

    
    pr.latest_date AS pdate,
    o.id AS ownerId,
    o.name AS owner_name, 
    o.father AS father, 
    o.contact AS contact, 
    o.alternate_contact AS alt_contact
FROM 
    property_listing AS pl 
LEFT JOIN 
    owner AS o ON pl.owner_id = o.id 
JOIN 
    property_type AS pt ON pl.category = pt.id 
JOIN 
    priority AS pri ON pl.priority = pri.id 
LEFT JOIN 
    (SELECT 
        property_id, 
        remarks AS latest_remark, 
        date AS latest_date 
     FROM 
        property_remarks order by timestamp desc limit 1
    
                ) AS pr ON pl.id = pr.property_id 
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
    $sql .= " AND o.father LIKE '%$father%'";
}
// if (!empty($phone)) {
//     $sql .= " AND o.contact = $phone";
// }
if (!empty($phone)) {
    $sql .= " AND (o.contact LIKE '%$phone%') OR (o.alternate_contact LIKE '%$phone%')";
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

if (!empty($measurement_unit)) {
    $sql .= " AND pl.measurement LIKE '%$measurement_unit%'";
}
if (!empty($available_for)) {
    $sql .= " AND pl.available_for IN ('" . implode("','", $available_for) . "')";
}
if (!empty($property_type)) {
    $sql .= " AND pl.property_type IN ('" . implode("','", $property_type) . "')";
}
if (!empty($category)) {
    $sql .= " AND pl.category IN ('" . implode("','", $category) . "')";
}
if (!empty($furnished_status)) {
    $sql .= " AND pl.furnished_status IN ('" . implode("','", $furnished_status) . "')";
}
if (!empty($status)) {
    $sql .= " AND pl.status IN ('" . implode("','", $status) . "')";
}
if (!empty($project)) {
    $sql .= " AND pl.project_type IN ('" . implode("','", $project) . "')";
}
if (!empty($city)) {
    $sql .= " AND pl.city IN ('" . implode("','", $city) . "')";
}
if (!empty($property_location)) {
    $sql .= " AND pl.location IN ('" . implode("','", $location) . "')";
}
if (!empty($sub_location)) {
    $sql .= " AND pl.sub_location IN ('" . implode("','", $sub_location) . "')";
}
if (!empty($quota)) {
    $sql .= " AND pl.quota_id IN ('" . implode("','", $quota) . "')";
}
if (!empty($priority)) {
    $sql .= " AND pl.priority LIKE '%$priority%'";
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
LEFT JOIN owner AS o ON pl.owner_id = o.id
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


if (!empty($measurement_unit)) {
    $total_query .= " AND pl.measurement LIKE '%$measurement_unit%'";
}
if (!empty($available_for)) {
    $total_query .= " AND pl.available_for IN ('" . implode("','", $available_for) . "')";
}
if (!empty($property_type)) {
    $total_query .= " AND pl.property_type IN ('" . implode("','", $property_type) . "')";
}
if (!empty($category)) {
    $total_query .= " AND pl.category IN ('" . implode("','", $category) . "')";
}
if (!empty($furnished_status)) {
    $total_query .= " AND pl.furnished_status IN ('" . implode("','", $furnished_status) . "')";
}
if (!empty($status)) {
    $total_query .= " AND pl.status IN ('" . implode("','", $status) . "')";
}
if (!empty($project)) {
    $total_query .= " AND pl.project_type IN ('" . implode("','", $project) . "')";
}
if (!empty($city)) {
    $total_query .= " AND pl.city IN ('" . implode("','", $city) . "')";
}


if (!empty($property_location)) {
    $total_query .= " AND pl.location IN ('" . implode("','", $location) . "')";
}
if (!empty($sub_location)) {
    $total_query .= " AND pl.sub_location IN ('" . implode("','", $sub_location) . "')";
}
if (!empty($quota)) {
    $total_query .= " AND pl.quota_id IN ('" . implode("','", $quota) . "')";
}
if (!empty($priority)) {
    $total_query .= " AND pl.priority LIKE '%$priority%'";
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
if (!empty($results)) {
    
foreach ($results as $row) {
    $price = $boj->price($row->property_price);  // Format price using your price function

    // Check if it's priced per unit
    if ($row->is_perunit == 1 && !empty($row->measurement)) {
        $price .= ' / ' . $row->measurement;  // Append the measurement (e.g., per sqft, per unit)
    }

    if(!empty($row->owner_name) || !empty($row->contact)) {
        //CONCAT(o.name, '<br />', o.contact, '<br />', o.alternate_contact, '<br />(', o.father, ')') AS owner_details,







$owner = $row->owner_name . 
         '<br /><a href="tel:' . C_CODE . $row->contact . '">
            <i class="fa fa-phone"></i> ' . htmlspecialchars($row->contact) . '</a><br />';

// Check if alternate contact numbers are available
if (!empty($row->alt_contact)) {
    $altNumbers = explode(',', $row->alt_contact); // Split the alternate contact numbers by comma
    $altContactHtml = [];

    foreach ($altNumbers as $number) {
        $number = trim($number); // Trim whitespace for safety
        $cleanedNumber = preg_replace('/[^0-9+]/', '', $number); // Remove invalid characters
        $altContactHtml[] = '<a href="tel:' . C_CODE . $cleanedNumber . '">
                                <i class="fa fa-phone"></i> ' . htmlspecialchars($number) . '</a>';
    }

    // Join alternate contact numbers with a line break
    $owner .= implode('<br />', $altContactHtml) . '<br />';
}

// Add father's name
$owner .= '(' . htmlspecialchars($row->father) . ')';






         //$owner = $row->owner_name .'<br /><a href="tel:+'. C_CODE .$row->contact . '">' .  $row->contact .'</a><br />'. $row->alt_contact . '<br />(' . $row->father . ')';
        //$owner = $row->owner_details;
    }
   
    $status_text = '';  // Default value
    switch ($row->status) {
        case 1:
            $status_text = 'Available';  // If status is 1, show "Available"
            break;
        case 2:
            $status_text = 'Booked';  // If status is 2, show "Unavailable"
            break;
        case 3:
            $status_text = 'Hold';  // If status is 3, show "Active"
            break;
        case 4:
            $status_text = 'Pending (Un-Approved)';  // If status is 4, show "Inactive"
            break;
        case '5':
            $status_text = 'Unpublished';  // If status is 'pending', show "Pending"
            break;
        case '6':
            $status_text = 'Deactive';  // If status is 'published', show "Published"
            break;
        case '7':
            $status_text = 'Hidden';  // If status is 'unpublished', show "Unpublished"
            break;
        default:
            $status_text = 'Unknown';  // If status doesn't match any case, show "Unknown"
            break;
    }

    if(!empty($row->size)){
        $size = $row->size;
        if(!empty($row->measurement)){
         $size .=  ' ' . $row->measurement;
        }
    }else{
        $size = '';
    }
     $action = '<div class="col-sm-12">';
     $action = '<div class="col-sm-4"><a  href="property/property-info/?id='.$row->id.'"><i class="fa fa-eye"></i></a></div>';
     $action .= '<div class="col-sm-4"><a  href="property/property-edit/?edit='.$row->id.'"><i class="fa fa-pencil"></i></a></div>';
     $ph = $row->contact;
     if ($ph) {
        $action .= '<div class="col-sm-4"><span>';
        $action .= $boj->whatsapp_modal_ajax($ph, $row->id);
        $action .= '</span></div></div>';
    }

    if(!empty($row->premark)){
    $remark = $row->premark;
    $remark .= '<hr />'. $boj->remarks_modal_ajax($row->id);
    }else{
        $remark = '--';
    }

   

   

    $response['data'][] = [
        'unit_no' => $row->unit_no,
        'property_title' => $row->property_title,
        //'owner_name' => $row->owner_details,
        'owner_name' => $owner,
        'property_location' => $row->location,
        'property_type' => $row->pType,
        'priority' => $row->prinmame,
        'available_for' => $row->available_for,
        'size' => $size,
        'property_price' => $price,
        'status' => $status_text,

        'latest_remark' => $remark,
        'latest_date' => $row->pdate,
        'contact' => $row->contact,
        'alt_contact' => $row->alt_contact,
        'action' => $action
    ];
}
}else{
    $response['data'] = []; // Explicitly set an empty array for no records
    $response['recordsFiltered'] = 0; // Set filtered records count to 0
}
// Return the response as JSON
echo json_encode($response);
?>
