<?php

class message extends db
{
	
	function wa_send(){
	    
		@session_start();
		$_SESSION['search'] = $_POST['search'];
		$_SESSION['lead'] = $_POST['LeadID'];
		$l= $_POST['LeadID'];
		$mob = $this->int($_POST['mob']);
		$content = $_POST['msg'];
	
	if(isset($_POST['link'])){
	$link = $_POST['link'];
	}else{
	$link = "";
	}
	
	$data = $this->mysqli->query("SELECT * FROM api WHERE name ='whatsapp' ");
	$row[] = $data->fetch_object(); 
	$msg = $content . $link;
		
	if($row[0]->api_status == '1'){
	 
	 
	echo  $url = $row[0]->endpoint . "?username=".$row[0]->username. "&number=91". $mob."&message=". urlencode($msg). "&token=" . $row[0]->api_key;
//die;

	  $curl = curl_init();
	  
	  curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	  ));
	  
	  $response = curl_exec($curl);
	  
	  curl_close($curl);
	  echo $response;
	  $status = $response->status;
//die;	  



}else{
$error_msg = 'WhatsApp Api is disabled!';
}
session_start();
			
	if( $status = 'success')
	{
		$_SESSION['suc'] = 'Message Sent Succesfully!';
	}
	else
	{
		$_SESSION['fal'] = 'Failed! Message not sent. '. $error_msg;
	}
	$location = "?sent&edit=".$l;
		header("location: $location");
		die;
}

	function wa_send_test(){
	
		@session_start();
		// $_SESSION['search'] = $_POST['search'];
		$_SESSION['lead'] = $_POST['LeadID'];
		$l= $_POST['LeadID'];
		$mob = $this->int($_POST['mob']);
		$content = $_POST['msg'];	
		// if(isset($_POST['link'])){
		// $link = $_POST['link'];
		// }else{
		// $link = "";
		// }				
		$data = $this->mysqli->query("SELECT * FROM apitest WHERE name ='whatsapp' ");
		$row[] = $data->fetch_object(); 
		$array=json_decode($row[0]->key_value);		
		$url = '';
    	foreach ($array as $obj) {
    	if ($obj->key === 'url') {       
		$url .= $obj->kv . '?'; 
    	} else {
       	 $url .= $obj->key . '=' . $obj->kv . '&'; 
    	}
    	}		
		$msg = $content;		
		$message =  $msg;				
		$url .= 'number=91' . $mob . '&';
		$url .= 'message=' . $message; 
		// $url = rtrim($url, '&');		
		$u="'".$url."'";	
		$headers = array(
			'Content-Type: application/json', // Change content type based on your request
			// Add more headers if required
		);	
		if($row[0]->api_status == '1'){		
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	   
   			$response = curl_exec($ch);
   			curl_close($ch);
			echo $response;
			if(curl_errno($ch)) {
				echo 'Curl error: ' . curl_error($ch);
			} else {
				// Successful response
				echo 'Response: ' . $response;
			}
			
			

		// $curl = curl_init();	

		// curl_setopt_array($curl, array(
		//   CURLOPT_URL => $u,
		//   CURLOPT_RETURNTRANSFER => true,
		//   CURLOPT_ENCODING => '',
		//   CURLOPT_MAXREDIRS => 10,
		//   CURLOPT_TIMEOUT => 0,
		//   CURLOPT_FOLLOWLOCATION => true,
		//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		//   CURLOPT_CUSTOMREQUEST => 'POST',
		//   CURLOPT_HTTPHEADER => array(
		// 	'Cookie: TUkzSWI0YXBOaUp5TklYZ2J6Nmg4QT09=V3JLaTRNZ3lpeVEzUEE5NEV4enVwVWJjMk5LV0lSZUZLRmF2VGh1R2xGUlhvclRQRXFuUW0rUFlCcHhjK0dGRWVaOWtLd08vbVNsSHAva1E1dGFvSHc9PQ%3D%3D'
		//   ),
		// ));				
		// $response = curl_exec($curl);
		
		// curl_close($curl);
		// print_r($response);
		
die;
			


} else{
$error_msg = 'WhatsApp Api is disabled!';
}
session_start();
			
	if( $status = 'success')
	{
		$_SESSION['suc'] = 'Message Sent Succesfully!';
	}
	else
	{
		$_SESSION['fal'] = 'Failed! Message not sent. '. $error_msg;
	}
	$location = "?sent&edit=".$l;
		header("location: $location");
		die;
}



function wa_balance_check(){
   
	$data = $this->mysqli->query("SELECT * FROM api WHERE name ='whatsapp' ");
	$row[] = $data->fetch_object(); 
	@$msg = $content . $link;
		
	if($row[0]->api_status == '1'){
	 
	 
	  $balance = "http://wapi.itways.in/api/credits?username=".$row[0]->username. "&token=" . $row[0]->password;
//die;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $balance,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: TUkzSWI0YXBOaUp5TklYZ2J6Nmg4QT09=cDJHaHlmZ3d2VmZSQURldXN1V1V2Q0hnZzUvbUlneEp0cENQQk5OVXgydlhROXJlVTFjMXYyczhOQjRrcEJYcG1RazVkQ1ZNaXZ1a0NDMUZsSncrOXc9PQ%3D%3D'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$res = json_decode($response);
return $creditsBal = @$res->credits[0]->credits_bal;

}

	
}




}

$obj = new message();
$whatsapp_balance =	$obj->wa_balance_check ();

if(isset($_POST['wa-send']))
{
	$obj = new message();
	$obj->wa_send ();
}
if(isset($_POST['wa-send-test']))
{
	$obj = new message();
	$obj->wa_send_test();
}

?>