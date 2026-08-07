<?php

class agent extends db
{
	
	function agent_add (){


		if($_POST['aadhar_number']){
			$aadhar_number   =   $_POST['aadhar_number'];
			}else{ $aadhar_number   =   ''; }
			
			if($_POST['pan_number']){
			$pan_number   =   $_POST['pan_number'];
			}else{ $pan_number   =   ''; }
			
			if($_POST['driving_licence']){
			$driving_licence   =   $_POST['driving_licence'];
			}else{ $driving_licence   =   ''; }
	
			if($_POST['passport_number']){
			$passport_number   =   $_POST['passport_number'];
			}else{ $passport_number   =   ''; }
			
			
	
			
			// -------------- upload_aadhaar  ----------------
			if(!empty($_FILES["upload_aadhaar"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_aadhaar"]["tmp_name"];
				$name=$_FILES['upload_aadhaar']['name'];
				$size=$_FILES['upload_aadhaar']['size'];
				$type=$_FILES['upload_aadhaar']['type'];
				$sani=$this->image_check_values($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_aadhaar=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_aadhaar = '';
			}
			// -------------- // upload_aadhaar ----------------
			// -------------- upload pan ----------------
			if(!empty($_FILES["upload_pan"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_pan"]["tmp_name"];
				$name=$_FILES['upload_pan']['name'];
				$size=$_FILES['upload_pan']['size'];
				$type=$_FILES['upload_pan']['type'];
				$sani2=$this->image_check_values($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_pan=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_pan = '';
			}
			// -------------- //upload pan ----------------
	
			// -------------- upload_dl  ----------------
			if(!empty($_FILES["upload_dl"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_dl"]["tmp_name"];
				$name=$_FILES['upload_dl']['name'];
				$size=$_FILES['upload_dl']['size'];
				$type=$_FILES['upload_dl']['type'];
				$sani3=$this->image_check_values($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_dl=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_dl = '';
			}
			// -------------- // upload_dl ----------------
	
			// -------------- upload_passport  ----------------
			if(!empty($_FILES["upload_passport"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_passport"]["tmp_name"];
				$name=$_FILES['upload_passport']['name'];
				$size=$_FILES['upload_passport']['size'];
				$type=$_FILES['upload_passport']['type'];
				$sani4=$this->image_check_values($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_passport=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_passport = '';
			}
		
		

			$uploads_dir2  = '../../uploads';
			if(!empty($_FILES["profile_image"]["tmp_name"])){
				 $name=$this->uploadFiles($_FILES["profile_image"]);
			  
			}else{
	
				$name = '';
			}
	


        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		$agent_id    =   $_POST['agent_id'];
	
		//$data = $this->mysqli->query("INSERT INTO agent (
		$array = array(
			'agent_name' => $_POST['agent_name'], 
			'c_code' => $_POST['c_code'], 
			'agent_contact' => $_POST['agent_contact'],
			'a_code' => $_POST['a_code'],
			'alternate_contact' => $_POST['alt_contact'], 
			'agent_mail' => $_POST['agent_mail'], 
             'country'=>$_POST['country'],
			'agent_dob' => $_POST['dob'], 
			'firm' => $_POST['firm'], 
			'reg_no' => $_POST['reg_no'], 
			'agent_deals_in' => $_POST['deals_in'], 
			'profile_img' =>$this->sanitize($name),

			 

			'aadhaar' => $aadhar_number, 
			'agent_pan' => $pan_number, 
			'dl' => $driving_licence, 
			'passport' => $passport_number, 
			'uploaded_aadhaar'  => $uploaded_aadhaar, 
			'uploaded_pan' => $uploaded_pan, 
			'uploaded_dl' => $uploaded_dl, 
			'uploaded_passport' => $uploaded_passport, 
			'agent_address' => $_POST['address'], 
			'agent_city' => $_POST['city'], 
			'agent_state' => $_POST['state'], 
			'agent_pin' => $_POST['pin'], 
			'agent_deals_in' => $_POST['deals_in'], 
			
			'password' => md5($_POST['agent_pass']), 
			'status' => $_POST['agent_ac_status'], 
			'remarks' => $_POST['remark'], 
			'updated_by' => $_POST['user'], 
			'uploader' => $_POST['user']
		);
       


if(!empty($aadhar_number)||!empty($pan_number)||!empty($driving_licence)||!empty($passport_number)){
	
	if($_POST['is_verified']==1){
		$array['is_verified'] = 1;
	}else{
		$array['is_verified'] = 0;
	}
}


		$name = $_POST['agent_name'];
		$contact = $_POST['agent_contact'];
		$alternate=@$_POST['alternate_contact'];
 
		$mail =@$_POST['agent_mail'];
		$dob =@$_POST['dob'];
		$country=@$_POST['country'];
		$address =@$_POST['address'];
		$city =@$_POST['city'];
		$state =@$_POST['state'];
		$pin =@$_POST['pin'];

		$deals_in=@$_POST['deals_in'];
		 
	 
	 
	 

	  
		 

             

 

		$agent_id = $agent_id;
		 
			$aadhaar = $aadhar_number;
			$pan = $pan_number;
			$dl = $driving_licence; 
			$passport = $passport_number; 
			$uploaded_aadhaar  = $uploaded_aadhaar;
			$uploaded_pan = $uploaded_pan;
			$uploaded_dl = $uploaded_dl;
			$uploaded_passport = $uploaded_passport;
		$status = $_POST['status'];
		$is_verified = $_POST['is_verified'];
		
		$updated_by = $_POST['user'];

		session_start();
		if($sani==1 or $sani2==1 or $sani3==1 or $sani4==1){
	
			$_SESSION['fal']="File size must be less than or equal to 1MB.";
	
			?>
	 
	
	
		<?php
	
	header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&deals_in=$deals_in&country=$country");
	 
		// ?name=$name&contact=$contact&alternate=$alternate&comment=$comment&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&status=$status&is_v=$is_verified")
		// ?name=$name&contact=$contact&alternate=$alternate&comment=$comment&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&status=$status&is_v=$is_verified")
		die;
	
		}elseif($sani==2 or $sani2==2 or $sani3==2 or $sani4==2){
		 
			$_SESSION['fal']="File type must be jpg/png/jpeg format.";
			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&deals_in=$deals_in&country=$country");
	 
	 
			die;
	
		}
		
	
		$data = $this->insert_query('agent', $array);
		$last_id = $this->mysqli->insert_id;

		
		$today=  date("Y-m-d h:i:sa");
	   
		$act= array(
			'user_id' =>$_POST['user_id'],
			'action' =>" Agent ".$_POST['agent_name']." (".$last_id.") Created",
			
	   
		);
		$data = $this->insert_query('user_actvity', $act);
	   
	   
		
	
		if($data){
	
		   $_SESSION['suc']="Agent Added Succesfully";
	
		}else{
			$_SESSION['fal']="!Something went wrong".$this->mysqli->error;
			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&deals_in=$deals_in&country=$country");
	 
			die;
		}
	  header("location:?nav=agents");
	  die;
	
		}
		
	
	
	
	
    
    function agent_edit (){

		$id = $_GET['edit'];
	
		$imgqry = $this->mysqli->query("SELECT * FROM agent where id ='$id' ");
		$r_data = $imgqry->fetch_assoc();
		
		$uploads_dir2  = '../../uploads';
		if(!empty($_FILES["profile_image"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["profile_image"]["tmp_name"];
		  	$temp2 		 = explode(".", $_FILES["profile_image"]["name"]);
		  	$profile_image =       time().'.'.end($temp2);
		  	move_uploaded_file($tmp_name2, "$uploads_dir2/$profile_image");
		  
		}else{

			$profile_image = $r_data['profile_img'];
		}
		
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $agent_id    =   $_POST['agent_id'];
        
				$array = array(
				'agent_name' => $_POST['agent_name'], 
				'c_code' => $_POST['c_code'],
				'agent_contact' => $_POST['agent_contact'],
				'a_code' => $_POST['a_code'],
				'alternate_contact' => $_POST['alt_contact'],
				'agent_mail' => $_POST['agent_mail'], 
				'agent_dob' => $_POST['dob'], 
				'firm' => $_POST['firm'], 
			'reg_no' => $_POST['reg_no'], 
				'agent_deals_in' => $_POST['deals_in'], 
				'agent_address' => $_POST['address'], 
                'agent_city' => $_POST['city'], 
				'agent_state' => $_POST['state'], 
				'agent_pin' => $_POST['pin'],
				'remarks' => $_POST['remark'], 
				'updated_by' => $_POST['user'],
				'profile_img' => $profile_image,
				'uploader' => $_POST['user']
				);

            // echo "<pre>";
			// print_r($array);
			// echo"</pre>";
			// die;

				$data = $this->update_id('agent', $array, $agent_id);
				
date_default_timezone_set("Asia/Kolkata");
$date_time =    date("Y-m-d h:i:sa");
$today=date("Y-m-d h:i:sa");

$act= array(
	'user_id' =>$_POST['user_id'],
	'action' =>" Agent ".$_POST['agent_name']." (".$_GET['edit'].") Basic Info Updated",
	

);
$data = $this->insert_query('user_actvity', $act);
				$this->msg_set($data, 'agents&edit='.$agent_id);
		
	}
	
	 function agent_kyc (){
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $agent_id    =   $_POST['agent_id'];
        
        $agent=$this->getQuery("SELECT * from agent where id='$agent_id'");
		$agent_value=$agent[0];
        
		if($_POST['aadhar_number']){
			$aadhar_number   =   $_POST['aadhar_number'];
			}else{ $aadhar_number   =   ''; }
			
			if($_POST['pan_number']){
			$pan_number   =   $_POST['pan_number'];
			}else{ $pan_number   =   ''; }
			
			if($_POST['driving_licence']){
			$driving_licence   =   $_POST['driving_licence'];
			}else{ $driving_licence   =   ''; }
	
			if($_POST['passport_number']){
			$passport_number   =   $_POST['passport_number'];
			}else{ $passport_number   =   ''; }
			
			
	
			
			// -------------- upload_aadhaar  ----------------
			if(!empty($_FILES["upload_aadhaar"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_aadhaar"]["tmp_name"];
				$name=$_FILES['upload_aadhaar']['name'];
				$size=$_FILES['upload_aadhaar']['size'];
				$type=$_FILES['upload_aadhaar']['type'];
				$sani=$this->image_check($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_aadhaar=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_aadhaar = '';
			}
			// -------------- // upload_aadhaar ----------------
			// -------------- upload pan ----------------
			if(!empty($_FILES["upload_pan"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_pan"]["tmp_name"];
				$name=$_FILES['upload_pan']['name'];
				$size=$_FILES['upload_pan']['size'];
				$type=$_FILES['upload_pan']['type'];
				$sani=$this->image_check($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_pan=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_pan = '';
			}
			// -------------- //upload pan ----------------
	
			// -------------- upload_dl  ----------------
			if(!empty($_FILES["upload_dl"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_dl"]["tmp_name"];
				$name=$_FILES['upload_dl']['name'];
				$size=$_FILES['upload_dl']['size'];
				$type=$_FILES['upload_dl']['type'];
				$sani=$this->image_check($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_dl=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_dl = '';
			}
			// -------------- // upload_dl ----------------
	
			// -------------- upload_passport  ----------------
			if(!empty($_FILES["upload_passport"]["tmp_name"])){
				$tmp_name2 	 = $_FILES["upload_passport"]["tmp_name"];
				$name=$_FILES['upload_passport']['name'];
				$size=$_FILES['upload_passport']['size'];
				$type=$_FILES['upload_passport']['type'];
				$sani=$this->image_check($name,$size,$type);
				$ext= explode(".",$sani);
				// $uploaded_pan =       time().'.'.end($ext);
				$uploaded_passport=base64_encode(file_get_contents($tmp_name2));
			}else{
				$uploaded_passport = '';
			}
			
				$array = array(
					'aadhaar' => $aadhar_number,
					'uploaded_aadhaar' => $uploaded_aadhaar,
		
					'agent_pan' => $pan_number,
					'uploaded_pan' => $uploaded_pan,
		
					'dl' => $driving_licence,
					'uploaded_dl' => $uploaded_dl,
		
					'passport' => $passport_number,
					'uploaded_passport' => $uploaded_passport,
				);
		
				$data = $this->update_id('agent', $array, $agent_id);
				date_default_timezone_set("Asia/Kolkata");
				$date_time =    date("Y-m-d h:i:sa");
				$today= date("Y-m-d h:i:sa");
			   
				$act= array(
					'user_id' =>$_POST['user_id'],
					'action' =>" Agent ".$agent_value->agent_name." (".$agent_id .") KYC Updated",
					
			   
				);
				$act = $this->insert_query('user_actvity', $act);
				$this->msg_set($data, 'agents&edit='.$agent_id);
	}

	function agent_login_update (){
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $agent_id    =   $_POST['agent_id'];
		$agent=$this->getQuery("SELECT * from agent where id='$agent_id'");
		$agent_value=$agent[0];

				$array = array(
				'is_verified' => $_POST['is_verified'],  
				'status' => $_POST['agent_ac_status'], 
				'remarks' => $_POST['remarks'], 
				'updated_by' => $_POST['user'] 
				);
				
				$agent_id = $_GET['edit'];
				// $is_verified=$_POST['is_verified']; 
				// $status = $_POST['agent_ac_status']; 
				// $remarks = $_POST['remarks']; 
				// $updated_by = $_POST['user']; 
				// echo "<pre>";
				// print_r($array);
				// echo"</pre>";
				// die;
				//$data	=	$this->update_query("UPDATE agent set is_verified=$is_verified,status=$status,remarks=$remarks,updated_by=$updated_by");
				$data	=	$this->update_id('agent', $array, $agent_id);
				
				date_default_timezone_set("Asia/Kolkata");
				$date_time =    date("Y-m-d h:i:sa");
				$today= date("Y-m-d h:i:sa");
			   
				$act= array(
					'user_id' =>$_POST['user_id'],
					'action' =>" Agent ".$agent_value->agent_name." (".$agent_id .") Account Details Updated",
					
			   
				);
				$act = $this->insert_query('user_actvity', $act);
				$this->msg_set($data, 'agents&edit='.$agent_id);
		
				

	}
	
	function agent_pass_update (){
		
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $agent_id    =   $_POST['agent_id'];
        $agent=$this->getQuery("SELECT * from agent where id='$agent_id'");
		$agent_value=$agent[0];
				$array = array(
				
				'password' => md5($_POST['psw']), 
				'updated_by' => $_POST['user'] 
				);
	
			
			$data	=	$this->update_id('agent', $array, $agent_id);
			date_default_timezone_set("Asia/Kolkata");
			$date_time =    date("Y-m-d h:i:sa");
			$today=date("Y-m-d h:i:sa");
		   
			$act= array(
				'user_id' =>$_POST['user_id'],
				'action' =>" Agent ".$agent_value->agent_name." (".$agent_id.") Password Updated",
				
		   
			);
			$act = $this->insert_query('user_actvity', $act);
			$this->msg_set($data, 'agents&edit='.$agent_id);
	}

    
}
	

if(isset($_POST['agent-add']))
{
	$obj = new agent();
	$obj->agent_add ();
}

if(isset($_POST['agent-edit']))
{
	$obj = new agent();
	$obj->agent_edit ();
}
if(isset($_POST['agent-kyc']))
{
	$obj = new agent();
	$obj->agent_kyc ();
}
if(isset($_POST['agent-login-update']))
{
	$obj = new agent();
	$obj->agent_login_update ();
}
if(isset($_POST['agent-pass-update']))
{
	$obj = new agent();
	$obj->agent_pass_update ();
}

?>