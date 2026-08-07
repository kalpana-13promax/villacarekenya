<?php 
require_once('config.php');
$boj->check_session();

$leadid = $_POST['lead_id'];

$data = $boj->getQuery("SELECT * FROM leads WHERE id = $leadid LIMIT 1"); 
//echo json_encode(array("lead_id"=>$data[0]->id,"lead_name"=>$data[0]->lead_name,"lead_contact"=>$data[0]->lead_contact,"lead_mail"=>$data[0]->lead_mail,"lead_location"=>$data[0]->lead_location,"agent_pan"=>$data[0]->agent_pan));
//die;

$data = array(
		"lead_name"			=>$data[0]->lead_name,
		"lead_contact"		=>$data[0]->lead_contact,
		"lead_mail"			=>$data[0]->lead_mail,
		"lead_location"     =>$data[0]->lead_location,
		"lead_id"     =>$data[0]->id,
		"reference"=>$data[0]->reference,
		"agent_id"=>$data[0]->agent_id
		 
);
 
echo json_encode($data);
 
exit;