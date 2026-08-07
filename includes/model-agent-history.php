<?php 
require_once('config.php');
$boj->check_session();

$leadid = $_POST['lead_id'];

$data = $boj->getQuery("SELECT * FROM leads WHERE id = $leadid LIMIT 1"); 


$data = array(
		"lead_name"			=>$data[0]->lead_name,
		"lead_contact"		=>$data[0]->lead_contact,
		"lead_mail"			=>$data[0]->lead_mail,
		"lead_location"     =>$data[0]->lead_location,
		"lead_id"     =>$data[0]->id,
		"agent_pan"=>$data[0]->agent_pan,
);
echo json_encode($data);

exit;