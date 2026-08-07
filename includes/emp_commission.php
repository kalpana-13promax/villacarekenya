<?php 
require_once('config.php');
$boj->check_session();

$leadid = $_POST['lead_id'];

$data = $boj->getQuery("SELECT * FROM user order by name asc"); 

$html = '<select class="form-group" name="employee_id[]" >'; 

foreach ($data as $value) {
	
	$html .= '<option class="form-control" value="'. $value->id .'" >'.$value->name.'</option>';

}

$html .= '</select>';
$result = array($html);
echo json_encode($result);

exit;