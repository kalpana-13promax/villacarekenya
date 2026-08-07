<?php

class owner extends db
{

	function owner_add()
	{
		if ($_POST['aadhar_number']) {
			$aadhar_number   =   $_POST['aadhar_number'];
		} else {
			$aadhar_number   =   '';
		}

		if ($_POST['pan_number']) {
			$pan_number   =   $_POST['pan_number'];
		} else {
			$pan_number   =   '';
		}

		if ($_POST['driving_licence']) {
			$driving_licence   =   $_POST['driving_licence'];
		} else {
			$driving_licence   =   '';
		}

		if ($_POST['passport_number']) {
			$passport_number   =   $_POST['passport_number'];
		} else {
			$passport_number   =   '';
		}

		if(!empty($_FILES['profile_image']['name'])){
			$image= $this->uploadFiles($_FILES['profile_image']);
		}else{
			$image= '';
		}






		



		// -------------- upload_aadhaar  ----------------
		if (!empty($_FILES["upload_aadhaar"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_aadhaar"]["tmp_name"];
			$name = $_FILES['upload_aadhaar']['name'];
			$size = $_FILES['upload_aadhaar']['size'];
			$type = $_FILES['upload_aadhaar']['type'];
			$sani = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_aadhaar = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_aadhaar = '';
		}
		// -------------- // upload_aadhaar ----------------
		// -------------- upload pan ----------------
		if (!empty($_FILES["upload_pan"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_pan"]["tmp_name"];
			$name = $_FILES['upload_pan']['name'];
			$size = $_FILES['upload_pan']['size'];
			$type = $_FILES['upload_pan']['type'];
			$sani2 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_pan = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_pan = '';
		}
		// -------------- //upload pan ----------------

		// -------------- upload_dl  ----------------
		if (!empty($_FILES["upload_dl"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_dl"]["tmp_name"];
			$name = $_FILES['upload_dl']['name'];
			$size = $_FILES['upload_dl']['size'];
			$type = $_FILES['upload_dl']['type'];
			$sani3 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_dl = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_dl = '';
		}
		// -------------- // upload_dl ----------------

		// -------------- upload_passport  ----------------
		if (!empty($_FILES["upload_passport"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_passport"]["tmp_name"];
			$name = $_FILES['upload_passport']['name'];
			$size = $_FILES['upload_passport']['size'];
			$type = $_FILES['upload_passport']['type'];
			$sani4 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_passport = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_passport = '';
		}




		if ($_POST['source_id'] == 1) {

			if (isset($_POST['agent_id'])) {
				$agent_id = $_POST['agent_id'];
			}
		}
		//echo $agent_id;
		if ($_POST['source_id'] == 2) {

			if (isset($_POST['staff_id'])) {
				$agent_id =    $_POST['staff_id'];

				//echo $agent_id;
			}
		}

		$array = array(
			'name' => $_POST['client_name'],
			'c_code' => $_POST['c_code'],
			'contact' => $_POST['client_contact'],
			'a_code' => $_POST['a_code'],
			'alternate_contact' => $_POST['alternate_contact'],
			'father' => $_POST['father'],
			'mail' => $_POST['client_mail'],
			'dob' => $_POST['client_dob'],
			'password' => md5($_POST['agent_pass']),
			'comment' => $_POST['owner_comment'],
			'address' => $_POST['client_address'],
			'city' => $_POST['client_city'],
			'state' => $_POST['client_state'],
			'pin' => $_POST['client_pin'],
			'occupation' => $_POST['client_occupation'],
			'organisation_name' => $_POST['client_organisation'],
			'designation' => $_POST['client_designation'],
			'contract' => $_POST['contract'],
			'reference' => @$_POST['source_id'],
			'agent_id' => $agent_id,
			'aadhaar' => $aadhar_number,
			'agent_pan' => $pan_number,
			'dl' => $driving_licence,
			'passport' => $passport_number,
			'uploaded_aadhaar'  => $uploaded_aadhaar,
			'uploaded_pan' => $uploaded_pan,
			'uploaded_dl' => $uploaded_dl,
			'uploaded_passport' => $uploaded_passport,
			'status' => $_POST['owner_ac_status'],
			'is_verified' => $_POST['is_verified'],
			'deal_status' => $_POST['deal_status'],
			'updated_by' => $_POST['user'],
			'mark_color' => $_POST['color'],
			'uploader' => $_POST['user'],
			'profile_img' => $image
		);

$csrf=$_POST['csrf_token'];

		$data = $this->csrf_insert('owner', $array,$csrf);
		$last_id = $this->mysqli->insert_id;

		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner " . $_POST['client_name'] . " (" . $last_id . ") Created",
			

		);
		 $this->insert_query('user_actvity', $act);




		$this->msg_set($data, 'owners');
	}


	function owner_adderror()
	{

		if ($_POST['aadhar_number']) {
			$aadhar_number   =   $_POST['aadhar_number'];
		} else {
			$aadhar_number   =   '';
		}

		if ($_POST['pan_number']) {
			$pan_number   =   $_POST['pan_number'];
		} else {
			$pan_number   =   '';
		}

		if ($_POST['driving_licence']) {
			$driving_licence   =   $_POST['driving_licence'];
		} else {
			$driving_licence   =   '';
		}

		if ($_POST['passport_number']) {
			$passport_number   =   $_POST['passport_number'];
		} else {
			$passport_number   =   '';
		}




		// -------------- upload_aadhaar  ----------------
		if (!empty($_FILES["upload_aadhaar"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_aadhaar"]["tmp_name"];
			$name = $_FILES['upload_aadhaar']['name'];
			$size = $_FILES['upload_aadhaar']['size'];
			$type = $_FILES['upload_aadhaar']['type'];
			$sani = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_aadhaar = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_aadhaar = '';
		}
		// -------------- // upload_aadhaar ----------------
		// -------------- upload pan ----------------
		if (!empty($_FILES["upload_pan"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_pan"]["tmp_name"];
			$name = $_FILES['upload_pan']['name'];
			$size = $_FILES['upload_pan']['size'];
			$type = $_FILES['upload_pan']['type'];
			$sani2 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_pan = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_pan = '';
		}
		// -------------- //upload pan ----------------

		// -------------- upload_dl  ----------------
		if (!empty($_FILES["upload_dl"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_dl"]["tmp_name"];
			$name = $_FILES['upload_dl']['name'];
			$size = $_FILES['upload_dl']['size'];
			$type = $_FILES['upload_dl']['type'];
			$sani3 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_dl = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_dl = '';
		}
		// -------------- // upload_dl ----------------

		// -------------- upload_passport  ----------------
		if (!empty($_FILES["upload_passport"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_passport"]["tmp_name"];
			$name = $_FILES['upload_passport']['name'];
			$size = $_FILES['upload_passport']['size'];
			$type = $_FILES['upload_passport']['type'];
			$sani4 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_passport = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_passport = '';
		}




		if ($_POST['source_id'] == 1) {

			if (isset($_POST['agent_id'])) {
				$agent_id = $_POST['agent_id'];
			}
		}
		//echo $agent_id;
		if ($_POST['source_id'] == 2) {

			if (isset($_POST['staff_id'])) {
				$agent_id =    $_POST['staff_id'];

				//echo $agent_id;
			}
		}

		$array = array(
			'name' => $_POST['client_name'],
			'c_code' => $_POST['c_code'],
			'contact' => $_POST['client_contact'],
			'a_code' => $_POST['a_code'],
			'alternate_contact' => $_POST['alternate_contact'],
			'father' => $_POST['father'],
			'mail' => $_POST['client_mail'],
			'dob' => $_POST['client_dob'],
			'password' => md5($_POST['agent_pass']),
			'comment' => $_POST['owner_comment'],
			'address' => $_POST['client_address'],
			'city' => $_POST['client_city'],
			'state' => $_POST['client_state'],
			'pin' => $_POST['client_pin'],
			'occupation' => $_POST['client_occupation'],
			'organisation_name' => $_POST['client_organisation'],
			'designation' => $_POST['client_designation'],
			'contract' => $_POST['contract'],
			'reference' => @$_POST['source_id'],
			'agent_id' => $agent_id,
			'aadhaar' => $aadhar_number,
			'agent_pan' => $pan_number,
			'dl' => $driving_licence,
			'passport' => $passport_number,
			'uploaded_aadhaar'  => $uploaded_aadhaar,
			'uploaded_pan' => $uploaded_pan,
			'uploaded_dl' => $uploaded_dl,
			'uploaded_passport' => $uploaded_passport,
			'status' => $_POST['owner_ac_status'],
			'is_verified' => $_POST['is_verified'],

			'updated_by' => $_POST['user'],
			'uploader' => $_POST['user']
		);
		//  	echo "<pre>";
		//  	print_r($array);
		// // 	echo "</pre>";
		//  	 die;


		$name = $_POST['client_name'];
		$contact = $_POST['client_contact'];
		$alternate = @$_POST['alternate_contact'];
		$comment = @$_POST['client_comment'];
		$mail = @$_POST['client_mail'];
		$dob = @$_POST['client_dob'];

		$address = @$_POST['client_address'];
		$city = @$_POST['client_city'];
		$state = @$_POST['client_state'];
		$pin = @$_POST['client_pin'];
		$occupation = @$_POST['client_occupation'];
		$organisation_name = @$_POST['client_organisation'];
		$designation = $_POST['client_designation'];
		$reference = @$_POST['source_id'];











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
		if ($sani == 1 or $sani2 == 1 or $sani3 == 1 or $sani4 == 1) {

			$_SESSION['fal'] = "File size must be less than or equal to 1MB.";

?>
			<script type="text/javascript">
				location.assign("client-add.php?name=<?php echo $name; ?> &contact=<?php echo $contact;  ?>&alternate=<?php echo $alternate; ?> &comment=<?php echo $comment; ?> &mail=<?php echo $mail; ?> &dob=<?php echo $dob; ?> &address=<?php echo $address; ?>  &city=<?php echo $city; ?> &state=<?php echo $state;  ?>&pin=<?php echo $pin; ?>&occ=<?php echo $occupation; ?>&org=<?php echo $organisation_name; ?> &desig=<?php echo $designation; ?>&ref=<?php echo $reference; ?> &agent_id=<?php echo $agent_id; ?> &aadhaar=<?php echo $aadhaar; ?> &pan=<?php echo $pan; ?>&dl=<?php echo $dl; ?> &passport=<?php echo $passport; ?> &status=<?php echo $status; ?>&is_v=<?php echo $is_verified; ?>");
			</script>


<?php

			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&status=$status&is_v=$is_verified");

			// ?name=$name&contact=$contact&alternate=$alternate&comment=$comment&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&status=$status&is_v=$is_verified")
			// ?name=$name&contact=$contact&alternate=$alternate&comment=$comment&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&status=$status&is_v=$is_verified")
			die;
		} elseif ($sani == 2 or $sani2 == 2 or $sani3 == 2 or $sani4 == 2) {

			$_SESSION['fal'] = "File type must be jpg/png/jpeg format.";
			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&status=$status&is_v=$is_verified");

			die;
		}


		$data = $this->insert_query('owner', $array);



		if ($data) {

			$_SESSION['suc'] = "Owner Added Succesfully";
		} else {
			$_SESSION['fal'] = "!Something went wrong" . $this->mysqli->error;
			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&status=$status&is_v=$is_verified");
			die;
		}
		header("location?nav=owners");
		die;
	}








	function owner_edit()
	{

		/*
			if($_POST['source_id']==1){

				if(isset($_POST['agent_id'])){
				   $agent_id = $_POST['agent_id']; 
				}
		   
			   }
				   //echo $agent_id;
			  if($_POST['source_id']==2){
		   
			   if(isset($_POST['staff_id'])){
					$agent_id =    $_POST['staff_id']; 
		   
					//echo $agent_id;
				}
			   }
			   
		*/


		$owner_id    =   $_POST['owner_id'];

		if (!$_POST['agent_id']) {
			$agent_id = $_POST['agent_id_backup'];
		} else {
			$agent_id =    $_POST['agent_id'];
		}

		if ($_POST['source_id'] != '1' or $_POST['source_id'] != '2') {
			$agent_id   =   'NULL';
		}
		if (isset($_POST['staff_id'])) {
			$agent_id =    $_POST['staff_id'];
		}

		if ($_POST['address'] != NULL) {
			$address = $_POST['address'];
		} else {

			$address = NULL;
		}

		if (isset($_POST['mail'])) {
			$mail = $_POST['mail'];
		} else {
			$mail = NULL;
		}
		$array = array(
			'name' => $_POST['name'],
			'c_code' => $_POST['c_code'],
			'contact' => $_POST['contact'],
			'a_code' => $_POST['a_code'],
			'alternate_contact' => $_POST['alt_contact'],
			'father' => $_POST['father'],
			'mail' => $mail,
			'dob' => $_POST['dob'],


			'address' => $address,
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'pin' => $_POST['pin'],
			'occupation' => @$_POST['client_occupation'],
			'organisation_name' => $_POST['client_organisation'],
			'designation' => $_POST['client_designation'],
			'contract' => $_POST['contract'],
			'reference' => $_POST['source_id'],
			'agent_id' => $agent_id,
			'deal_status' => $_POST['deal_status'],
			'comment' => $_POST['owner_comment'],
			'mark_color' => $_POST['color'],
			'follow_up_date' => $_POST['follow_date'],
			'follow_up_time' => $_POST['follow_time'],
			'updated_by' => $_POST['user']
		);


		//$where = $_POST['id'];
		// echo "<pre>";
		//print_r($array);
		// die;

$csrf= $_POST['csrf_token'];
		$where = "id = " . $_GET['edit'];
		$data = $this->csrf_update('owner', $array, $where,$csrf);



		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner " . $_POST['name'] . " (" . $_GET['edit'] . ") Basic Info Updated",
			

		);
		$data = $this->insert_query('user_actvity', $act);
		$this->msg_set($data, 'owners&edit=' . $owner_id);
	}



	function owner_search()
	{
		$owner_id = $_POST['owner_id'];

		$otp = $this->generateKey('6');
		$array = array(
			'otp' => $otp,
			'viewer' => $_POST['viewer']
		);
		$data = $this->update_id('owner', $array, $owner_id);
		session_start();
		if ($data) {
			$_SESSION['suc'] = 'OTP has been Generated and sent to Administrator ask OTP to your administrator to view owner details.<br /> Please do not refresh page or move to another page, Your otp will be change after reload page.';
		} else {
			$_SESSION['fal'] = 'Something went wrong. ' . $this->mysqli->error;
		}
		header("location: ?nav=owners&id=$owner_id");
		die;
	}





	function owner_kyc()
	{


		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("d/m/Y h:i:sa");

		$owner_id    =   $_POST['owner_id'];

		$owner = $this->getQuery("SELECT * from owner where id='$owner_id'");
		$owner_value = $owner[0];

		if ($_POST['aadhar_number']) {
			$aadhar_number   =   $_POST['aadhar_number'];
		} else {
			$aadhar_number   =   '';
		}

		if ($_POST['pan_number']) {
			$pan_number   =   $_POST['pan_number'];
		} else {
			$pan_number   =   '';
		}

		if ($_POST['driving_licence']) {
			$driving_licence   =   $_POST['driving_licence'];
		} else {
			$driving_licence   =   '';
		}

		if ($_POST['passport_number']) {
			$passport_number   =   $_POST['passport_number'];
		} else {
			$passport_number   =   '';
		}




		// -------------- upload_aadhaar  ----------------
		if (!empty($_FILES["upload_aadhaar"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_aadhaar"]["tmp_name"];
			$name = $_FILES['upload_aadhaar']['name'];
			$size = $_FILES['upload_aadhaar']['size'];
			$type = $_FILES['upload_aadhaar']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_aadhaar = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_aadhaar = '';
		}
		// -------------- // upload_aadhaar ----------------
		// -------------- upload pan ----------------
		if (!empty($_FILES["upload_pan"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_pan"]["tmp_name"];
			$name = $_FILES['upload_pan']['name'];
			$size = $_FILES['upload_pan']['size'];
			$type = $_FILES['upload_pan']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_pan = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_pan = '';
		}
		// -------------- //upload pan ----------------

		// -------------- upload_dl  ----------------
		if (!empty($_FILES["upload_dl"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_dl"]["tmp_name"];
			$name = $_FILES['upload_dl']['name'];
			$size = $_FILES['upload_dl']['size'];
			$type = $_FILES['upload_dl']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_dl = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_dl = '';
		}
		// -------------- // upload_dl ----------------

		// -------------- upload_passport  ----------------
		if (!empty($_FILES["upload_passport"]["tmp_name"])) {
			$tmp_name2 	 = $_FILES["upload_passport"]["tmp_name"];
			$name = $_FILES['upload_passport']['name'];
			$size = $_FILES['upload_passport']['size'];
			$type = $_FILES['upload_passport']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_passport = base64_encode(file_get_contents($tmp_name2));
		} else {
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

		$data = $this->update_id('owner', $array, $owner_id);

		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner " . $owner_value->name . " (" . $owner_id . ") KYC Updated",
			

		);
		$act = $this->insert_query('user_actvity', $act);

		$this->msg_set($data, 'owners&edit=' . $owner_id);
	}



	function owner_status()
	{



		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("d/m/Y h:i:sa");

		$owner_id    =   $_POST['owner_id'];
		$owner = $this->getQuery("SELECT * from owner where id='$owner_id'");
		$owner_value = $owner[0];

		$array = array(
			'is_verified' => $_POST['is_verified'],
			'status' => $_POST['owner_ac_status'],

			'updated_by' => $_POST['user']
		);

		$data	=	$this->update_id('owner', $array, $owner_id);


		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner " . $owner_value->name . " (" . $owner_id . ") Status Updated",
			

		);
		$act = $this->insert_query('user_actvity', $act);


		$this->msg_set($data, 'owners&edit=' . $owner_id);
	}



	function owner_pass_update()
	{

		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("d/m/Y h:i:sa");

		$owner_id    =   $_POST['owner_id'];
		$owner = $this->getQuery("SELECT * from owner where id='$owner_id'");
		$owner_value = $owner[0];

		$array = array(

			'password' => md5($_POST['psw']),
			'updated_by' => $_POST['user']
		);


		$data	=	$this->update_id('owner', $array, $owner_id);

		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner " . $owner_value->name . " (" . $owner_id . ") Password Updated",
			

		);
		$act = $this->insert_query('user_actvity', $act);

		$this->msg_set($data, 'agents&edit=' . $owner_id);
	}



	function add_owner_comment()
	{
		$owner_id    =   $this->int($_GET['view']);
		$owner = $this->getQuery("SELECT * from owner where id='$owner_id'");
		$owner_value = $owner[0];

		$array = array(
			'owner_id' => $this->int($_GET['view']),
			'date' => $_POST['date'],
			'uploader' => $_POST['uploader'],
			'comment' => $_POST['comment']
		);

		$data = $this->insert_qry('owner_comments', $array);

		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner  " . $owner_value->name . " (" . $this->int($_GET['view']) . ") Comment Added",
			

		);
		$data = $this->insert_query('user_actvity', $act);


		$nav = "?nav=owner&add=1&view=" . $_GET['view'] . "#comment";
		$this->msg_set($data, $nav);
	}

	function update_owner_comment()
	{
		$owner_id    =   $this->int($_GET['view']);
		$owner = $this->getQuery("SELECT * from owner where id='$owner_id'");
		$owner_value = $owner[0];
		$array = array(
			'owner_id' => $this->int($_GET['view']),
			'date' => $_POST['date'],
			'uploader' => $_POST['uploader'],
			'comment' => $_POST['comment']
		);

		$update_id = $_GET['edit'];
		$where = "id=" . $update_id;

		$data = $this->update_query('owner_comments', $array, $where);



		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner  " . $owner_value->name . " (" . $this->int($_GET['view']) . ") Comment Updated",
			

		);
		$data = $this->insert_query('user_actvity', $act);


		$nav = "?nav=owner&up=1&view=" . $_GET['view'] . "#comment";
		$this->msg_set($data, $nav);
	}


	function Convert_owner()
	{

		// echo "tryyy";   


		echo $leadId = $_POST['lead_id'];

		//	die;



		$uploader = $_POST['uploader'];

		$lead_data = $this->mysqli->query("select * from leads where id = '$leadId'");
		$row[] = $lead_data->fetch_object();


		$array = array(
			'lead_id' => $row[0]->id,
			'name' => $row[0]->lead_name,
			'c_code' => $row[0]->c_code,
			'contact' => $row[0]->lead_contact,
			'a_code' => $row[0]->a_code,
			'alternate_contact' => $row[0]->alternate_contact,
			'mail' => $row[0]->lead_mail,

			'contract' => $row[0]->contract,

			'address' => $row[0]->lead_location,

			'reference' => $row[0]->reference,
			'agent_id' => $row[0]->agent_id,
			'updated_by' => $uploader
		);

		//  	print_r($array);
		//     	die;	

		$data = $this->insert_query('owner', $array);
		//echo $error=$this->mysqli->error;
		//die;

		session_start();


		if ($data) {
			$data = $this->mysqli->query("Update leads Set convert_to_client = '2' Where id = '$leadId'");

			$_SESSION['suc'] = $row[0]->lead_name . 'Converted to Owner Successfully! <a href="owner-edit/?edit=' . $row[0]->lead_name;
			header("location: ?nav=clients");
			die;
		} else {
			$_SESSION['fal'] = 'Not able to conver lead as Owner!' . $_POST['lead_name'] . " - " . $this->mysqli->error;
			header("location: all-leads.php?nav=leads");
			die;
		}
		header("location: ?nav=clients");
		die;
	}





	function master_owner_status_add()
	{
		$array = array(
			'name' => $_POST['name'],
			'uploader' => $_POST['user']
		);

		$data = $this->insert_qry('owner_status', $array);
		$last_id = $this->mysqli->insert_id;

		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner Status " . $_POST['name'] . " (" . $last_id . ") Added",
			

		);
		$data = $this->insert_query('user_actvity', $act);
		$nav = "?nav=masters";
		$this->msg_set($data, $nav);
	}

	function master_owner_status_update()
	{
		$array = array(
			'name' => $_POST['name'],
			'uploader' => $_POST['user']
		);

		$update_id = $_GET['edit'];
		$where = "id=" . $update_id;

		$data = $this->update_query('owner_status', $array, $where);


		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Owner Status " . $_POST['name'] . " (" . $update_id . ") Updated",
			

		);
		$data = $this->insert_query('user_actvity', $act);



		$nav = "?nav=masters";
		$this->msg_set($data, $nav);
	}




	function owner_status_available()
	{
		$array = array(
			'owner_status' => $_POST['owner_status'],
			'contract' => $_POST['contract']
		);

		$update_id = $_GET['edit'];
		$where = "id=" . $update_id;

		$data = $this->update_query('owner', $array, $where);
		$nav = "?nav=owners&edit=" . $update_id;
		$this->msg_set($data, $nav);
	}
}


if (isset($_POST['owner-add'])) {
	$obj = new owner();
	$obj->owner_add();
}

if (isset($_POST['owner-edit'])) {
	$obj = new owner();
	$obj->owner_edit();
}

if (isset($_POST['owner-search'])) {
	$obj = new owner();
	$obj->owner_search();
}
if (isset($_POST['owner-kyc'])) {
	$obj = new owner();
	$obj->owner_kyc();
}
if (isset($_POST['owner-status'])) {
	$obj = new owner();
	$obj->owner_status();
}
if (isset($_POST['owner-pass-update'])) {
	$obj = new owner();
	$obj->owner_pass_update();
}




if (isset($_POST['add-owner-comment'])) {
	$obj = new owner();
	$obj->add_owner_comment();
}

if (isset($_POST['update-owner-comment'])) {
	$obj = new owner();
	$obj->update_owner_comment();
}
if (isset($_POST['convert-owner'])) {
	$obj = new owner();
	$obj->Convert_owner();
}




if (isset($_POST['owner-status-add'])) {
	$obj = new owner();
	$obj->master_owner_status_add();
}


if (isset($_POST['owner-status-update'])) {
	$obj = new owner();
	$obj->master_owner_status_update();
}

if (isset($_POST['owner-status-available'])) {
	$obj = new owner();
	$obj->owner_status_available();
}
?>