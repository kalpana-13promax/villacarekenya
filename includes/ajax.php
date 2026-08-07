<?php 
require_once('config.php');
$boj->check_session();
$action = $_POST['action'];

if($action == 'get_property'){
	 $clientid = $_POST['client_id'];
	 $data = $boj->getQuery("SELECT property_id FROM property_booking WHERE client_id = $clientid");
	 $ids = array();
	 foreach ($data as $value) {
	 	$ids[] = $value->property_id;
	 }
	 $ids = implode(',', $ids);
	$pdata = $boj->getQuery("SELECT * FROM property_listing WHERE id IN ($ids)");
	$html = '';
	$html .= '<option>---- Select Property ----</option>';
	foreach ($pdata as $pvalue) {
		$html .='<option value="'.$pvalue->id.'"> '.$pvalue->property_title.'</option>';
	}
	$datas = array("property_name"	=>$html	);
	echo json_encode($datas);
	exit;
}



if($action == 'get_property_payment'){
	$property_id 	= $_POST['property_id'];
	$client_id 	= $_POST['client_id'];
	$baseAmount = $boj->getQuery("SELECT property_price FROM property_listing WHERE id = $property_id");
	$baseAmount = $baseAmount[0]->property_price;
	$dealAmount = $boj->getQuery("SELECT deal_amount FROM property_booking WHERE client_id = $client_id AND property_id = $property_id");
	$dealAmount = $dealAmount[0]->deal_amount;
	$rm = $boj->getQuery("SELECT amount FROM payment_details WHERE property_id = $property_id AND client_id = $client_id");
	if($rm){
		foreach ($rm as $value) {
			$amount += $value->amount;
		}
		$remaingA = $dealAmount - $amount;
		$amount =  $remaingA;
	}else{
		   $amount = $dealAmount;
	}
	$reamining_amount = $amount;
	$datas = array("deal_amount"=>$dealAmount, "base_amount"=>$baseAmount,"reamining_amount"=>$reamining_amount);
	echo json_encode($datas);
	exit;
}








 





