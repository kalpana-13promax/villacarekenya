<?php
require_once('includes/config.php');


// <th>ID</th>
// <th>Lead Name</th>
// <th>Contact</th>
// <th>Project</th>
// <th>Requirement</th>
// <th>For</th>
// <th>Source/Employee</th>
// <th>Remarks</th>
// <th>Lead Date</th>
// <th>Action</th>


$sql = "SELECT id as id,lead_name as Name,lead_contact as contact, project as project, category as requirement, contract as for, reference as source, remarks as remarks, lead_date as lead_date FROM leads LIMIT 20";
$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
$data = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
	$data[] = $rows;
}

$results = array(
	"sEcho" => 1,
"iTotalRecords" => count($data),
"iTotalDisplayRecords" => count($data),
  "aaData"=>$data);
echo json_encode($results);
exit;
?>