<?php

class user extends db
{


	function save_role(){
		echo "Bingo!";
		die;
	}
	
	function user_add ()
	{
	    
	    if(is_dir("../uploads/kyc"))
{
		$uploads_dir_kyc  = '../uploads/kyc';
}else{
		$uploads_dir_kyc  = '../crm/uploads/kyc';
}

		
		if(!empty($_FILES["upload_aadhaar"]["tmp_name"])){
		  	$tmp_name1 	 = $_FILES["upload_aadhaar"]["tmp_name"];
		  	$temp1 		 = explode(".", $_FILES["upload_aadhaar"]["name"]);
		  	$upload_aadhaar =       'a' . time().'.'.end($temp1);
		  	move_uploaded_file($tmp_name1, "$uploads_dir_kyc/$upload_aadhaar");
		  
		}else{

			$upload_aadhaar = '';
		}

		if(!empty($_FILES["upload_pan"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["upload_pan"]["tmp_name"];
		  	$temp2 		 = explode(".", $_FILES["upload_pan"]["name"]);
		  	$upload_pan =       'p'.time().'.'.end($temp2);
		  	move_uploaded_file($tmp_name2, "$uploads_dir_kyc/$upload_pan");
		  
		}else{

			$upload_pan = '';
		}

		if(!empty($_FILES["upload_dl"]["tmp_name"])){
		  	$tmp_name3 	 = $_FILES["upload_dl"]["tmp_name"];
		  	$temp3 		 = explode(".", $_FILES["upload_dl"]["name"]);
		  	$upload_dl =       'd'.time().'.'.end($temp3);
		  	move_uploaded_file($tmp_name3, "$uploads_dir_kyc/$upload_dl");
		  
		}else{

			$upload_dl = '';
		}

		if(!empty($_FILES["upload_passport"]["tmp_name"])){
		  	$tmp_name4 	 = $_FILES["upload_passport"]["tmp_name"];
		  	$temp4 		 = explode(".", $_FILES["upload_passport"]["name"]);
		  	$upload_passport =       'pp'.time().'.'.end($temp4);
		  	move_uploaded_file($tmp_name4, "$uploads_dir_kyc/$upload_passport");
		  
		}else{

			$upload_passport = '';
		}

		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		

       
	    $array = array(
			'name' => $_POST['name'],
			'username' => $_POST['username'],
			'password' => $_POST['password'], 
			'usertype' => $_POST['usertype'], 
			'contact' => $_POST['contact'], 
			'mail' => $_POST['mail'], 
			'dob' => $_POST['dob'], 
			'aadhar' => $_POST['aadhaar'],
			'pan' => $_POST['pan'],
			'dl' => $_POST['dl'],
			'passport' => $_POST['passport'],
			'uploaded_aadhaar' => $upload_aadhaar,
			'uploaded_pan' => $upload_pan,
			'uploaded_dl' => $upload_dl,
			'uploaded_passport' => $upload_passport,
			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'pin_code' => $_POST['pin_code'],
			'deals_in' => $_POST['deals_in'],
			'remarks' => $_POST['remarks'],
			'status' => $_POST['status'],
			'uploader' => $_POST['uploader'],
			
			
		);
		// print_r($array);
		// die;
		 $data = $this->insert_query('user', $array);
        session_start();
        
		if( $data )
		{
			$_SESSION['suc'] = 'Account Created Successfully';
		}
		else
		{
			$_SESSION['fal'] = 'Something wrong or Duplicate Record Found!' . $_POST['username'];
		}
			header("location: ?nav=setting");
			die;
	    
	
	}
    
    function user_edit (){
		
		 
		$uploads_dir_kyc  = '../uploads/kyc';
	
		if(!empty($_FILES["upload_aadhaar"]["tmp_name"])){
		  	$tmp_name1 	 = $_FILES["upload_aadhaar"]["tmp_name"];
		  	$temp1 		 = explode(".", $_FILES["upload_aadhaar"]["name"]);
		  	$upload_aadhaar =       'a' . time().'.'.end($temp1);
		  	move_uploaded_file($tmp_name1, "$uploads_dir_kyc/$upload_aadhaar");
		  
		}else{

			$upload_aadhaar = '';
		}

		if(!empty($_FILES["upload_pan"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["upload_pan"]["tmp_name"];
		  	$temp2 		 = explode(".", $_FILES["upload_pan"]["name"]);
		  	$upload_pan =       'p'.time().'.'.end($temp2);
		  	move_uploaded_file($tmp_name2, "$uploads_dir_kyc/$upload_pan");
		  
		}else{

			$upload_pan = '';
		}

		if(!empty($_FILES["upload_dl"]["tmp_name"])){
		  	$tmp_name3 	 = $_FILES["upload_dl"]["tmp_name"];
		  	$temp3 		 = explode(".", $_FILES["upload_dl"]["name"]);
		  	$upload_dl =       'd'.time().'.'.end($temp3);
		  	move_uploaded_file($tmp_name3, "$uploads_dir_kyc/$upload_dl");
		  
		}else{

			$upload_dl = '';
		}

		if(!empty($_FILES["upload_passport"]["tmp_name"])){
		  	$tmp_name4 	 = $_FILES["upload_passport"]["tmp_name"];
		  	$temp4 		 = explode(".", $_FILES["upload_passport"]["name"]);
		  	$upload_passport =       'pp'.time().'.'.end($temp4);
		  	move_uploaded_file($tmp_name4, "$uploads_dir_kyc/$upload_passport");
		  
		}else{

			$upload_passport = '';
		}

        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $user_id =   $_POST['user_id'];

        $array = array(
			'name' => $_POST['name'],
			'username' => $_POST['username'],
			'password' => $_POST['password'], 
			'usertype' => $_POST['usertype'], 
			'contact' => $_POST['contact'], 
			'mail' => $_POST['mail'], 
			'dob' => $_POST['dob'], 
			'aadhar' => $_POST['aadhaar'],
			'pan' => $_POST['pan'],
			'dl' => $_POST['dl'],
			'passport' => $_POST['passport'],
			'uploaded_aadhaar' => $upload_aadhaar,
			'uploaded_pan' => $upload_pan,
			'uploaded_dl' => $upload_dl,
			'uploaded_passport' => $upload_passport,
			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'pin_code' => $_POST['pin_code'],
			'deals_in' => $_POST['deals_in'],
			'remarks' => $_POST['remarks'],
			'status' => $_POST['status'],
			'uploader' => $_POST['user'],
			
		);

        $where = "id = " . $user_id ;
		$data = $this->update_query('user', $array, $where);

		session_start();
        
		if( $data ){
			$_SESSION['suc'] = 'Account Updated!';

		}else{
			$_SESSION['fal'] = 'Oops! not Updated, Something wrong.' . mysqli_error();

		}
		header("location: user-edit.php?edit=$user_id");
		die;
	}
	
	 function user_kyc (){
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $user_id    =   $_POST['user_id'];
        
        
        $array = array(
			'aadhar' => $_POST['aadhar_number'],
			'pan' => $_POST['pan_number'],
			'dl' => $_POST['driving_licence'],
			'passport' => $_POST['passport_number'],
			'uploader' => $_POST['user']
        );

        $where = "id = " . $id ;
		$data = $this->update_query('user', $array, $where);

		session_start();
        
		if( $data ){
			$_SESSION['suc'] = 'KYC Updated!';

		}else{
			$_SESSION['fal'] = 'Oops! KYC not Updated, Something went wrong.' . mysqli_error();

		}
		header("location: user-edit.php?edit=$user_id");
		die;
	}

	function user_login_update (){
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $user_id    =   $_POST['user_id'];
        
        
        
		$data = $this->mysqli->query("Update agent 
				Set username = '$_POST[agent_user]',  
				
				status = '$_POST[agent_ac_status]', 
				remarks = '$_POST[remarks]', 
				
				updated_by = '$_POST[user]' 

				Where id = $agent_id
			") ;

		session_start();
        
		if( $data ){
			$_SESSION['suc'] = 'Agent Login Updated!';

		}else{
			$_SESSION['fal'] = 'Oops! not insert, Something wrong.' . mysqli_error();

		}
		header("location: agent-edit.php?edit=$agent_id");
		die;
	}
	
	function agent_pass_update (){
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $agent_id    =   $_POST['agent_id'];
        
        
        
		$data = $this->mysqli->query("Update agent 
				Set username = '$_POST[agent_username]',  
				
				password = '$_POST[psw]', 
				
				updated_by = '$_POST[user]' 

				Where id = $agent_id
			") ;

		session_start();
        
		if( $data ){
			$_SESSION['suc'] = 'Agent Password Updated!';

		}else{
			$_SESSION['fal'] = 'Oops! Agent Password Not Updated, Something went wrong.' . mysqli_error();

		}
		header("location: agent-edit.php?edit=$agent_id");
		die;
	}

    
}
	

if(isset($_POST['agent-add']))
{
	$obj = new agent();
	$obj->agent_add ();
}
if(isset($_POST['agent-reg']))
{
	$obj = new agent();
	$obj->agent_reg ();
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


if(isset($_POST['save-role']))
{
	$obj = new user();
	$obj->save_role ();
}


?>