<?php

class api extends db
{
    
    
        // ######################## --------- magicbricks --------- ########################

	
	function magicbricks ()
	{
	    $qry = $this->getQuery("select * from api where name = 'magicbricks'");
	    if($qry[0]->api_status=='1'){
	    
	    echo $date = date('Ymd');
echo "<br />";
echo $sdate = date('Ymd', strtotime("-4 day", strtotime($date)));

// Set the url you're making an api call to
$endpoint = $qry[0]->endpoint . '?key='. $qry[0]->api_key . '&startDate='.$sdate.'&endDate='.$date;


$curl = curl_init();


curl_setopt_array($curl, [
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $xml,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/xml"
    ]
]);

$response = curl_exec($curl);
$error = curl_error($curl);

curl_close($curl);

if ($error) {
  echo "cURL Error #:" . $error;
} else {

$xml = simplexml_load_string($response);
$json = json_encode($xml);
$arr = json_decode($json,true);
echo "<pre>";
//print_r($arr);

echo '<table width="50%"><tr>';
if(is_array($arr)){
foreach($arr as $var){
   echo $var . "<br />";
   if(is_array($var)){
    foreach($var as $n){
        
        $id = $n['id'];
        $names = $n['name'];
         $isd = $n['isd'];
         $mobile = $n['mobile'];
         $mail = $n['email'];
        
         $location = $n['locality'];
         $city = $n['city'];
        
         $project = $n['project'];
         $pid = $n['pid'];
          $vdate = $n['vdate'];
         $vtime = $n['VTime'];
        
         $tranType = $n['tranType'];
         
         $msg = $n['msg'];
        
         $this->sanitize($tranType);
        
    
        
        
        
        if($tran='b'){
            $contract == 'Buy';
        }
        elseif($tran=='r'){
             $contract = 'Rent';
        }
        else{
            $contract ="";
        }
        
         $dt = $this->datetime();
        
      
        if($names){
            $name= $names;
        }else{
            $name = 'Unknown';
        }
       
         if (strlen($mobile >= 8)){
        
        $array = array(
            'lead_name' => $name,
            'c_code' => $isd,
            'lead_contact'  => $mobile,
            'lead_mail'  => $mail,
            'required_property_location'  => $location,
            'project' => $project,
            'lead_status'  => $qry[0]->lead_status,
            'lead_uploaded_by'  => 'Cron',
            'lead_date'  => $dt,
            'contract'  => $contract,
            'reference'  => $qry[0]->source,
            'property_link_id' => "MagicBricks Property ID: ". $pid,
            'remarks'  => $msg,
            'hot' =>'1',
            'assign_to' => $qry[0]->assign_to
            );
           
        $duplicate = $this->getQuery("select cron_id, lead_contact, remarks from leads where lead_contact = '$mobile'");
$cron_id = implode(" ",$duplicate[0]->cron_id);

      if ($duplicate[0]->lead_contact AND in_array($id, $cron_id)){
          echo "<br />Duplicate Record!" . $id;
          echo "<br />Old Requirement: ".$duplicate[0]->remarks;
          echo "<br />New Requirements: ". $msg;
      }else{
          echo "<br /><b>Fresh</b><br />";
           $cron_ids = (implode(",",$cron_arr));
            if($cron_ids){
          echo $updated_cron_id = $cron_ids .",".$id;
            }else{
                $updated_cron_id = $id;
            }
          
          
          $up_cron = array(
          'cron_id' => $updated_cron_id, 
          'lead_status' => 'un-attempted'
          );
          $data_update = $this->update_id('leads', $up_cron, $duplicate[0]->id);
          
          $dup_rem = "MB ID:". $id . " - ". $msg ;
          $insert_cron = array(
              'lead_id' => $duplicate[0]->id,
              'remarks' => $dup_rem,
              'remarks_by' => 'Cron',
              'remarks_date' => $dt
              );
              
        $data_insert = $this->insert_qry('remarks', $insert_cron);
         // echo "insert into remarks (lead_id, remarks, remarks_by, remarks_date)
                  //  VALUES ('". $duplicate[0]->id."', '". $dup_rem ."', 'Cron', '". $dt ."')";
      }
      
        $data = $this->insert_ignore('leads', $array);
        
        
            }
  
  
  if($data){
      echo $name . " ". $this->mask($mobile) ." - ". $contract. " : Inserted!";
   
  }else{
      echo $error = $bo->mysqli->error;
  }
  
  
    foreach($n as $yourArray){
        $id = $yourArray['id'];
         $names = $yourArray['name'];
         $isd = $yourArray['isd'];
         $mobile = $yourArray['mobile'];
         $mail = $yourArray['email'];
        
         $location = $yourArray['locality'];
         $city = $yourArray['city'];
        
         $project = $yourArray['project'];
         $pid = $yourArray['pid'];
          $vdate = $yourArray['vdate'];
         $vtime = $yourArray['VTime'];
        
         $tranType = $yourArray['tranType'];
         
         $msg = $yourArray['msg'];
        
         $tran = $this->sanitize($tranType);
        
    
        
        
        
        if($tran=='b'){
            $contract = 'Buy';
        }
        elseif($tran=='r'){
             $contract = 'Rent';
        }
        else{
            $contract ="";
        }
         $dt = $this->datetime();
        
      
        if($names){
            $name= $names;
        }else{
            $name = 'Unknown';
        }
       
        
          if (strlen($mobile >= 8)){
        $array = array(
            'lead_name' => $name,
            'c_code' => $isd,
            'lead_contact'  => $mobile,
            'lead_mail'  => $mail,
            'required_property_location'  => $location,
            'project' => $project,
            'lead_status'  => $qry[0]->lead_status,
            'lead_uploaded_by'  => 'Cron',
            'lead_date'  => $dt,
            'contract'  => $contract,
            'reference'  => $qry[0]->source,
            'property_link_id' => "MagicBricks Property ID: ". $pid,
            'remarks'  => $msg,
            'hot' =>'1',
             'assign_to' =>''
            );
           
          $duplicate = $this->getQuery("select id, cron_id, lead_contact, remarks from leads where lead_contact = '$mobile'");
$cron_arr = explode(",",$duplicate[0]->cron_id);
//$cron_arr = array('1','2','3','4','985769747');
      if ($duplicate[0]->lead_contact AND in_array($id, $cron_arr)){
          echo "<br />Duplicate Record!" . $id . " - ". $vdate. ", ". $vtime;
          echo "<br />Old Requirement: ".$duplicate[0]->remarks;
          echo "<br />New Requirements: ". $msg;
      }else{
          echo "<br /><b>Fresh</b><br />";
           $cron_ids = (implode(",",$cron_arr));
            if($cron_ids){
          echo $updated_cron_id = $cron_ids .",".$id;
            }else{
                $updated_cron_id = $id;
            }
          
          
          $up_cron = array(
          'cron_id' => $updated_cron_id, 
          'lead_status' => 'un-attempted'
          );
          $data_update = $this->update_id('leads', $up_cron, $duplicate[0]->id);
          
          $dup_rem = "MB ID:". $id . " - ". $msg ;
          $insert_cron = array(
              'lead_id' => $duplicate[0]->id,
              'remarks' => $dup_rem,
              'remarks_by' => 'Cron',
              'remarks_date' => $dt
              );
              
        $data_insert = $this->insert_qry('remarks', $insert_cron);
         // echo "insert into remarks (lead_id, remarks, remarks_by, remarks_date)
                  //  VALUES ('". $duplicate[0]->id."', '". $dup_rem ."', 'Cron', '". $dt ."')";
      }
      
        $data = $this->insert_ignore('leads', $array);
             }
  
  
  if($data){
      echo $name . " ". $this->mask($mobile) ." - ". $contract. " : Inserted! ID: ". $id;
        
  }else{
      echo $error = $bo->mysqli->error;
  }
   echo "<hr />";
    }
    }}

}}
echo "</tr></table>";
}

	}
}
    
    
    
    
    
        // ######################## ------------ Housing ------------ ###############################
    function housing ()
{
    $qry = $this->getQuery("select * from api where name = 'housing'");
	    if($qry[0]->api_status=='1'){
	        
    $currentDate =  time();
    
    $start_date = strtotime(date("Y-m-d", $currentDate) . " -1 day");
    
    $key = $qry[0]->api_key;

$hash = hash_hmac('sha256', $currentDate, $key);

$id = $qry[0]->username;


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://leads.housing.com/api/v0/get-broker-leads?id='.$id.'&start_date='.$start_date.'&end_date='.$currentDate.'&current_time='.$currentDate.'&hash='.$hash,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));


$response = curl_exec($curl);
$error = curl_error($curl);

curl_close($curl);

if ($error) {
  echo "cURL Error #:" . $error;
} else {

$response_data = json_decode($response);
echo "<pre>";
print_r($response_data);



//echo "<hr/>";
foreach ($response_data as $data) {

		  if (strlen($data->lead_phone >= 8)){
		      
		      if($data->service_type == 'rent'){
		        $contract = 'Rent';  
		      }else{
		          $contract = 'Buy';  
		      }
		      
		      if($data->category_type == 'residential')
		      {
		          $property_type='Resedential';
		      }
        
        
$lead_date =  date('d-m-Y h:i:s A', $data->lead_date);

$country_code = $this->int($data->country_code);
$c_code =  preg_replace('/[^\p{L}\p{N}\s]/u', '', $country_code);
        $array = array(
            'lead_name' => $data->lead_name,
            'c_code' => $c_code,
            'lead_contact'  => $data->lead_phone,
            'lead_mail'  => $data->lead_email,
            'required_property_location'  => $data->locality_name,
            'lead_status'  => $qry[0]->lead_status,
            //'lead_status'  => 'un-attempted',
            'lead_uploaded_by'  => 'Cron',
            'lead_date'  => $lead_date,
            'contract'  => $contract,
            'property_type'  => $property_type,
            
            'client_budget_min'  => $data->min_price,
            'client_budget_max'  => $data->max_price,
            'property_size_min' => $data->min_area,
            'property_size_max' => $data->max_area,
            
            'reference'  => $qry[0]->source,
            'assign_to' => $qry[0]->assign_to,
            //'property_link_id' => "Housing Project ID: ". $data->project_id,
            //'remarks'  => $msg
            );
         // print_r($array); 
        $insert = $this->insert_ignore('leads', $array);
         if($insert){
      echo $data->lead_name . " #Confideltial# - ". $lead_date . " : housing Inserted!";
  }else{
      echo $error = $this->mysqli->error;
  }
            }
            
            
            
            
}
}

}

}





    
    
    
    
    

 //<!-- ############################### 99 acres ############################### -->
 
 
 function acres99(){
  $qry = $this->getQuery("select * from api where name = '99acres'");
	    if($qry[0]->api_status=='1'){
	  
//$endpoint = $qry[0]->endpoint . '?key='. $qry[0]->api_key . '&startDate='.$sdate.'&endDate='.$date;


 $QueryId = '';

    $name = '';
       $phone = '';
        
        $mail = '';
        $project = '';
        $msg = '';
        $cron_ids = '';
        $dup_rem ='';
        
 
 date_default_timezone_set('Asia/Calcutta');
echo "<br />";
$dt = date('Y-m-d H:i:s');
 echo $end = date('Y-m-d H:i:s');
echo "<br />";
echo $start = date('Y-m-d H:i:s', strtotime("-1 day", strtotime($end)));
 
 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $qry[0]->endpoint,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('xml' => '<?xml version=\'1.0\'?><query><user_name>'. $qry[0]->username.'</user_name>
  <pswd>'.$qry[0]->password.'</pswd>
  <start_date>'. $start .'</start_date><end_date>'. $end .'</end_date></query>'),
  CURLOPT_HTTPHEADER => array(
    'cache-control: no-cache'
  ),
));

$response = curl_exec($curl);


if ($response === false) {
    echo "cURL Error: " . curl_error($curl);
} else {
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    echo "HTTP Response Code: " . $httpCode . "<br>";
    echo "API Response: " . $response;
    
    $xml = simplexml_load_string($response);
// echo "<pre>";
// print_r($xml);
$resultArray = array();

foreach ($xml->Resp as $item) {
    $entry = array(
        'ResType' => isset($item->QryDtl->attributes()->ResType) ? (string) $item->QryDtl->attributes()->ResType : '',
        'QueryId' => isset($item->QryDtl->attributes()->QueryId) ? (string) $item->QryDtl->attributes()->QueryId : '',
        'CmpctLabl' => isset($item->QryDtl->CmpctLabl) ? (string) $item->QryDtl->CmpctLabl : '',
        'RcvdOn' => isset($item->QryDtl->RcvdOn) ? (string) $item->QryDtl->RcvdOn : '',
        'ProjId' => isset($item->QryDtl->ProjId) ? (string) $item->QryDtl->ProjId : '',
        'ProjName' => isset($item->QryDtl->ProjName) ? (string) $item->QryDtl->ProjName : '',
        'CityName' => isset($item->QryDtl->CityName) ? (string) $item->QryDtl->CityName : '',
        'ResCom' => isset($item->QryDtl->ResCom) ? (string) $item->QryDtl->ResCom : '',
        'Price' => isset($item->QryDtl->Price) ? (float) $item->QryDtl->Price : 0.0,
        'PhoneVerificationStatus' => isset($item->QryDtl->PhoneVerificationStatus) ? (string) $item->QryDtl->PhoneVerificationStatus : '',
        'EmailVerificationStatus' => isset($item->QryDtl->EmailVerificationStatus) ? (string) $item->QryDtl->EmailVerificationStatus : '',
        'IDENTITY' => isset($item->QryDtl->IDENTITY) ? (string) $item->QryDtl->IDENTITY : '',
        'PROPERTY_CODE' => isset($item->QryDtl->PROPERTY_CODE) ? (string) $item->QryDtl->PROPERTY_CODE : '',
        'SubUserName' => isset($item->QryDtl->SubUserName) ? (string) $item->QryDtl->SubUserName : '',
        'ProdId' => isset($item->QryDtl->ProdId) ? (string) $item->QryDtl->ProdId : '',
        'Name' => isset($item->CntctDtl->Name) ? (string) $item->CntctDtl->Name : '',
        'Email' => isset($item->CntctDtl->Email) ? (string) $item->CntctDtl->Email : '',
        'Phone' => isset($item->CntctDtl->Phone) ? (string) $item->CntctDtl->Phone : '',
    );

    $resultArray[] = $entry;
}

// echo '<pre>';
// print_r($resultArray);
// echo '</pre>';

foreach($resultArray as $nra){
    
        $QueryId = $nra['QueryId'];

    $name = $nra['Name'];
       $phone = $nra['Phone'];
$splittedString = explode(' ', $phone);
$numbers = explode('-', $splittedString[0]);
$cid = $numbers[0];
$isd =  str_replace("+","",$cid);
$mobile = $numbers[1];
        
        $mail = $nra['Email'];
        $project = $nra['ProjName'];
        $msg = $nra['CmpctLabl'];


      $array99 = array(
            'cron_id' => $QueryId,
            'lead_name' => $name,
            'c_code' => $isd,
            'lead_contact'  => $mobile,
            'lead_mail'  => $mail,
           
            'project' => $project,
            'lead_status'  => $qry[0]->lead_status,
            'lead_uploaded_by'  => 'Cron',
            'lead_date'  => $dt,
           
            'reference'  => $qry[0]->source,
            
            'remarks'  => $msg,
            'hot' => '1',
             'assign_to' =>$qry[0]->assign_to
            );
           


// --------------------------- duplicate -------------------------
 $duplicate = $this->getQuery("select id, cron_id, lead_contact, remarks from leads where lead_contact = $mobile");

$cron_arr = explode(",",$duplicate[0]->cron_id);

      if ($duplicate[0]->lead_contact AND in_array($QueryId, $cron_arr)){
          echo "<br />Duplicate Record!" . $QueryId . " - ". $vdate. ", ". $vtime;
          echo "<br />Old Requirement: ".$duplicate[0]->remarks;
          echo "<br />New Requirements: ". $msg;
          echo "<br /><b>Duplicate</b><br />";
      }else{
         
           $cron_ids = (implode(",",$cron_arr));
            if($cron_ids){
          echo $updated_cron_id = $cron_ids .",".$QueryId;
            }else{
                $updated_cron_id = $QueryId;
            }
          
          
          $up_cron = array(
          'cron_id' => $updated_cron_id, 
          'lead_status' => 'un-attempted'
          );
          $data_update = $this->update_id('leads', $up_cron, $duplicate[0]->id);
          
          $dup_rem = "99 ID:". $QueryId . " - ". $msg ;
          $insert_cron = array(
              'lead_id' => $duplicate[0]->id,
              'remarks' => $dup_rem,
              'remarks_by' => 'Cron',
              'remarks_date' => $dt
              );
              
        $data_insert = $this->insert_qry('remarks', $insert_cron);
         // echo "insert into remarks (lead_id, remarks, remarks_by, remarks_date)
                  //  VALUES ('". $duplicate[0]->id."', '". $dup_rem ."', 'Cron', '". $dt ."')";
                
      }
// --------------------------- / duplicate ----------------------- 

        $data = $this->insert_ignore('leads', $array99);
             if($data){
      echo $name . " ". $this->mask($mobile) . " : Inserted! 99ID: ". $QueryId . ", CmpctLabl : ". $msg;
  }else{
      echo $error = $this->mysqli->error;
  }
  echo "<hr />";
  
}


}   
  
}

}
//----------------------------- 99 acres -------------------------------------------------
    
    
    
    // ######################## --------- follow up reminder --------- ########################
    
    
    function followup_reminder(){
        
        
        $company = $this->getQuery("SELECT * FROM company");
        $toMail = $company[0]->mail;
        
         $endTime = date("H:i",time() + 1800);
 $reminder_date = date('Y-m-d');

$qry = $this->getQuery("SELECT * FROM leads where follow_up_date = '$reminder_date' AND follow_up_time = '$endTime' "); 

if($qry){
    //print_r($qry);
    
    foreach($qry as $q){
       
        
        //$toMail = 'info@shrijiproperties.in';
        $cc = 'gagan@shrijiproperties.in, info@itways.in';
$fromMail = 'alert@shrijiproperties.in';
$boundary = str_replace(" ", "", date('l jS \of F Y h i s A'));
$subjectMail = "30 Minutes earlier followup reminder for ". $q->lead_name;


$contentHtml = '<div>Dear Admin<br /><br />New follow-up is in a que for: '. $q->lead_name .'. Follow up date time: '. $q->follow_up_date.' - '.$q->follow_up_time.'<br />';

$contentHtml .= '<div>Name : '.$q->lead_name.'<br />Contact : '. $q->lead_contact .'<br />E-Mail : '.$q->lead_mail.'</div>';
$contentHtml .= '<div><h4>Requirement:</h4></div>';
$contentHtml .= '<div>Want to : '. $q->contract . ', '. $q->property_type .' '. $q->category . '</div>';
$contentHtml .= '<div>Location : '.$q->required_property_location.'</div><br /><br />';
$contentHtml .= '<div>Lead Status : '.ucfirst($q->lead_status).'</div><div>Uploader : ' .$q->lead_uploaded_by.'</div>';
$contentHtml .= '<div>Last Update By : '.ucfirst($q->update_by).'</div><div>Assign to : ' .ucwords($this->get_staff((int)$q->assign_to)).'</div><hr /><a href="'.WEBSITE.'"><b>Click here</b></a> to check the Website.</div><br />';

echo $contentHtml;
$headers = 'From: ' . $fromMail . "\r\n";
// note: no "To: " !!!
$headers .= 'Cc: ' . $cc . "\r\n";
//$headers .= 'Bcc: ' . $contactEmailBcc . "\r\n";
$headers .= 'Return-Path: ' . $toMail . "\r\n";
$headers .= 'MIME-Version: 1.0' ."\r\n";   
$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1' . "\r\n";
$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\r\n";

try {
    if (mail($toMail, $subjectMail, $contentHtml, $headers)) {
        $status = 'success';
        $msg = 'Mail sent successfully.';
    } else {
        $status = 'failed';
        $msg = 'Unable to send mail.';
    }
} catch(Exception $e) {
    $msg = $e->getMessage();
}
}
}else{
  echo 'No data to mail';
}






echo $ctime = date("H:i",time());


$qry = $this->getQuery("SELECT * FROM leads where follow_up_date = '$reminder_date' AND follow_up_time = '$ctime' ");

//print_r($qry);
if($qry){

foreach($qry as $q){
$sub = "Current Follow-up Reminder for $q->lead_name";
       
        $cc = 'gagan@shrijiproperties.in, info@itways.in';
        
$fromMail = 'alert@shrijiproperties.in';
$boundary = str_replace(" ", "", date('l jS \of F Y h i s A'));
$subjectMail = $sub;


$contentHtml = '<div>Dear Admin<br /><br />New follow-up is in a que for: '. $q->lead_name .'. Follow up date time: '. $q->follow_up_date.' - '.$q->follow_up_time.'<br />';

$contentHtml .= '<div>Name : '.$q->lead_name.'<br />Contact : '. $q->lead_contact .'<br />E-Mail : '.$q->lead_mail.'</div>';
$contentHtml .= '<div><h4>Requirement:</h4></div>';
$contentHtml .= '<div>Want to : '. $q->contract . ', '. $q->property_type .' '. $q->category . '</div>';
$contentHtml .= '<div>Location : '.$q->required_property_location.'</div><br /><br />';
$contentHtml .= '<div>Lead Status : '.ucfirst($q->lead_status).'</div><div>Uploader : ' .$q->lead_uploaded_by.'</div>';
$contentHtml .= '<div>Last Update By : '.ucfirst($q->update_by).'</div><div>Assign to : ' .ucwords($this->get_staff((int)$q->assign_to)).'</div><hr /><a href="'.WEBSITE.'"><b>Click here</b></a> to check the Website.</div><br />';


echo $contentHtml;
$headers = 'From: ' . $fromMail . "\r\n";
// note: no "To: " !!!
$headers .= 'Cc: ' . $cc . "\r\n";
//$headers .= 'Bcc: ' . $contactEmailBcc . "\r\n";
$headers .= 'Return-Path: ' . $toMail . "\r\n";
$headers .= 'MIME-Version: 1.0' ."\r\n";   
$headers .= 'Content-Type: text/HTML; charset=ISO-8859-1' . "\r\n";
$headers .= 'Content-Transfer-Encoding: 8bit'. "\n\r\n";

try {
    if (mail($toMail, $subjectMail, $contentHtml, $headers)) {
        $status = 'success';
        $msg = 'Mail sent successfully.';
    } else {
        $status = 'failed';
        $msg = 'Unable to send mail.';
    }
} catch(Exception $e) {
    $msg = $e->getMessage();
}
}


}else{
    echo 'No data to mail';
}






    }
    
    
        // ######################## --------- / follow up reminder --------- ########################

    
   
   
    // ########################## -----------  IVR CALLING ----------------- ########################
   function IVRCall($contact, $key){
   
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'api.servetel.in/v1/click_to_call_support',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'customer_number='.$contact.'&api_key='. $key,
  CURLOPT_HTTPHEADER => array(
    'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjExNjY2MiwiaXNzIjoiaHR0cHM6XC9cL2N1c3RvbWVyLnNlcnZldGVsLmluXC90b2tlblwvZ2VuZXJhdGUiLCJpYXQiOjE2Nzc4MjgwMTgsImV4cCI6MTk3NzgyODAxOCwibmJmIjoxNjc3ODI4MDE4LCJqdGkiOiJXS3hoQXFoOGFOOW5pSENKIn0.0Xa0gkUGgYD_S7wTshBCeZVuMWSo_bqj0CuFCTXHmrI',
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$decodedData = json_decode($response, true);
return $decodedData;
   }
   
   // ########################## ----------- / IVR CALLING ----------------- ########################
   



// ############################# -------------  IVR Detail --------------- #########################

function ivr_call_records(){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.servetel.in/v1/call/records',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjExNjY2MiwiaXNzIjoiaHR0cHM6XC9cL2N1c3RvbWVyLnNlcnZldGVsLmluXC90b2tlblwvZ2VuZXJhdGUiLCJpYXQiOjE2Nzc4MjgwMTgsImV4cCI6MTk3NzgyODAxOCwibmJmIjoxNjc3ODI4MDE4LCJqdGkiOiJXS3hoQXFoOGFOOW5pSENKIn0.0Xa0gkUGgYD_S7wTshBCeZVuMWSo_bqj0CuFCTXHmrI',
    'accept: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$decodedData = json_decode($response, true);
return $decodedData;
}

function ivr_missed_calls(){
   
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.servetel.in/v1/call/stats',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjExNjY2MiwiaXNzIjoiaHR0cHM6XC9cL2N1c3RvbWVyLnNlcnZldGVsLmluXC90b2tlblwvZ2VuZXJhdGUiLCJpYXQiOjE2Nzc4MjgwMTgsImV4cCI6MTk3NzgyODAxOCwibmJmIjoxNjc3ODI4MDE4LCJqdGkiOiJXS3hoQXFoOGFOOW5pSENKIn0.0Xa0gkUGgYD_S7wTshBCeZVuMWSo_bqj0CuFCTXHmrI',
    'accept: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$decodedData = json_decode($response, true);
return $decodedData;


}
    
}






