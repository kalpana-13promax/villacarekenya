<?php

class client extends db
{

	function client_add()
	{


		if ($_POST['aadhar_number']) {
			$aadhar_number = $_POST['aadhar_number'];
		} else {
			$aadhar_number = '';
		}

		if ($_POST['pan_number']) {
			$pan_number = $_POST['pan_number'];
		} else {
			$pan_number = '';
		}

		if ($_POST['driving_licence']) {
			$driving_licence = $_POST['driving_licence'];
		} else {
			$driving_licence = '';
		}

		if ($_POST['passport_number']) {
			$passport_number = $_POST['passport_number'];
		} else {
			$passport_number = '';
		}




		// -------------- upload_aadhaar  ----------------
		if (!empty($_FILES["upload_aadhaar"]["tmp_name"])) {
			$tmp_name2 = $_FILES["upload_aadhaar"]["tmp_name"];
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
			$tmp_name2 = $_FILES["upload_pan"]["tmp_name"];
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
			$tmp_name2 = $_FILES["upload_dl"]["tmp_name"];
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
			$tmp_name2 = $_FILES["upload_passport"]["tmp_name"];
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


		// profile img
		if (!empty($_FILES['profile_image']['tmp_name'])) {
			$profile_img = $this->uploadFiles($_FILES['profile_image']);


		}


		if ($_POST['source_id'] == 1) {

			if (isset($_POST['agent_id'])) {
				$agent_id = $_POST['agent_id'];
			}

		}
		//echo $agent_id;
		if ($_POST['source_id'] == 2) {

			if (isset($_POST['staff_id'])) {
				$agent_id = $_POST['staff_id'];

				//echo $agent_id;
			}
		}

		//echo $_POST['agent_id'];
		// echo $agent_id;
		//die;

		$array = array(
			'name' => $_POST['client_name'],
			'c_code' => $_POST['c_code'],
			'contact' => $_POST['client_contact'],
			'a_code' => $_POST['a_code'],
			'alternate_contact' => $_POST['alternate_contact'],
			'comment' => $_POST['client_comment'],
			'mail' => $_POST['client_mail'],
			'dob' => $_POST['client_dob'],

			'address' => $_POST['client_address'],
			'city' => $_POST['client_city'],
			'state' => $_POST['client_state'],
			'pin' => $_POST['client_pin'],
			'occupation' => $_POST['client_occupation'],
			'organisation_name' => $_POST['client_organisation'],
			'designation' => $_POST['client_designation'],
			'contract' => $_POST['contract'],
			'reference' => $_POST['source_id'],
			'agent_id' => $agent_id,
			'aadhaar' => $aadhar_number,
			'agent_pan' => $pan_number,
			'dl' => $driving_licence,
			'passport' => $passport_number,
			'uploaded_aadhaar' => $uploaded_aadhaar,
			'uploaded_pan' => $uploaded_pan,
			'uploaded_dl' => $uploaded_dl,
			'uploaded_passport' => $uploaded_passport,
			'status' => $_POST['status'],
			'is_verified' => $_POST['is_verified'],
			'is_verify' => $_POST['is_verify'],
			'uploader' => $_POST['user'],
			'updated_by' => $_POST['user'],
			'profile_img' => $profile_img
		);



		//for sending...

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
		$reference = $_POST['source_id'];



		if ($reference == 1) {

			$agent_id = @$_POST['agent_id'];
		} elseif ($reference == 2) {


			$agent_id = @$_POST['staff_id'];
		}








		$agent_id = $agent_id;

		$aadhaar = $aadhar_number;
		$pan = $pan_number;
		$dl = $driving_licence;
		$passport = $passport_number;
		$uploaded_aadhaar = $uploaded_aadhaar;
		$uploaded_pan = $uploaded_pan;
		$uploaded_dl = $uploaded_dl;
		$uploaded_passport = $uploaded_passport;
		$status = $_POST['status'];
		$is_verified = $_POST['is_verified'];

		$updated_by = $_POST['user'];

		// echo $_POST['staff_id'];
// echo $agent_id;
// die;
		// echo "<pre>";
		// print_r($array);
		// echo"</pre>";
		// die;

		session_start();
		if ($sani == 1 or $sani2 == 1 or $sani3 == 1 or $sani4 == 1) {

			$_SESSION['fal'] = "File size must be less than or equal to 1MB.";

			?>
			<script type="text/javascript">
				location.assign("client-add.php?name=<?php echo $name; ?> &contact=<?php echo $contact; ?>&alternate=<?php echo $alternate; ?> &comment=<?php echo $comment; ?> &mail=<?php echo $mail; ?> &dob=<?php echo $dob; ?> &address=<?php echo $address; ?>  &city=<?php echo $city; ?> &state=<?php echo $state; ?>&pin=<?php echo $pin; ?>&occ=<?php echo $occupation; ?>&org=<?php echo $organisation_name; ?> &desig=<?php echo $designation; ?>&ref=<?php echo $reference; ?> &agent_id=<?php echo $agent_id; ?> &aadhaar=<?php echo $aadhaar; ?> &pan=<?php echo $pan; ?>&dl=<?php echo $dl; ?> &passport=<?php echo $passport; ?> &status=<?php echo $status; ?>&is_v=<?php echo $is_verified; ?>");
			</script>


			<?php

			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&status=$status&is_v=$is_verified&comment=$comment");

			// ?name=$name&contact=$contact&alternate=$alternate&comment=$comment&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&status=$status&is_v=$is_verified")
			// ?name=$name&contact=$contact&alternate=$alternate&comment=$comment&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&status=$status&is_v=$is_verified")
			die;

		} elseif ($sani == 2 or $sani2 == 2 or $sani3 == 2 or $sani4 == 2) {

			$_SESSION['fal'] = "File type must be jpg/png/jpeg format.";
			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&status=$status&is_v=$is_verified&comment=$comment");

			die;

		}
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d");

		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_insert('client', $array, $csrf);
		$last_id = $this->mysqli->insert_id;

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Client " . $_POST['client_name'] . " (" . $last_id . ") Created by $_POST[uploader]",
			'date' => $today,
			'type' => 'insert',
			'user_details' => json_encode($this->user_detail())

		);


		if ($data) {
			$data = $this->insert_userAct('user_actvity', $act, $csrf);
			$_SESSION['suc'] = "Client Added Succesfully";

		} else {
			$_SESSION['fal'] = "!Something went wrong" . $this->mysqli->error;
			header("location:?name=$name&contact=$contact&alternate=$alternate&mail=$mail&dob=$dob&address=$address&city=$city&state=$state&pin=$pin&occ=$occupation&org=$organisation_name&desig=$designation&ref=$reference&agent_id=$agent_id&status=$status&is_v=$is_verified&comment=$comment");
			die;
		}
		header("location:?nav=clients");
		die;

	}


	function leadConvertToClient()
	{
		$leadId = $_POST['lead_id'];

		$array = array(
			'name' => $_POST['client_name'],
			'contact' => $_POST['client_contact'],
			'mail' => $_POST['client_mail'],
			'dob' => $_POST['client_dob'],
			'agent_pan' => $_POST['client_pan'],
			'address' => $_POST['client_address'],
			'city' => $_POST['client_city'],
			'state' => $_POST['client_state'],
			'pin' => $_POST['client_pin'],
			'occupation' => $_POST['client_occupation'],
			'organisation_name' => $_POST['client_organisation'],
			'designation' => $_POST['client_designation'],
			'reference' => $_POST['source_id'],
			'agent_id' => $_POST['agent_id'],
			'updated_by' => $_POST['user']
		);
		// 		print_r($array);
// 		die;
		$data = $this->insert_query('client', $array);
		//echo $error=$this->mysqli->error;
		//die;

		// print_r($act);
		// die;



		// 		activity

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d");



		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Lead " . $_POST['client_name'] . " (" . $leadId . ") Convert into Client",
			'date' => $today

		);
		$data = $this->insert_query('user_actvity', $act);

		// activity




		session_start();


		if ($data) {

			$data = $this->mysqli->query("Update leads Set convert_to_client = '1'	Where id = $leadId");




			$_SESSION['suc'] = 'Client Added Successfully';
			header("location: client-list.php?nav=clients");

		} else {
			$_SESSION['fal'] = 'Not able to conver lead as client!' . $_POST['lead_name'] . " - " . $this->mysqli->error;
			header("location: all-leads.php?nav=leads");
		}
		header("location: client-list.php?nav=clients");
		die;
	}



	function client_edit()
	{
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d");
		$owner_id = $_GET['edit'];

		if ($_POST['source_id'] == 1) {

			$agent_id = $_POST['agent_id'];


		} elseif ($_POST['source_id'] == 2) {

			$agent_id = $_POST['staff_id'];

		} else {

			$agent_id = "";
		}


		// profile img

		if (!empty($_FILES['profile_image']['tmp_name'])) {
			$profile_img = $this->uploadFiles($_FILES['profile_image']);
		}

		$array = array(
			'name' => $_POST['name'],
			'c_code' => $_POST['c_code'],
			'contact' => $_POST['contact'],
			'a_code' => $_POST['a_code'],
			'alternate_contact' => $_POST['alt_contact'],
			'mail' => $_POST['mail'],
			'dob' => $_POST['dob'],


			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'pin' => $_POST['pin'],
			'occupation' => @$_POST['client_occupation'],
			'organisation_name' => $_POST['client_organisation'],
			'designation' => $_POST['client_designation'],
			'contract' => $_POST['contract'],
			'reference' => $_POST['source_id'],
			'agent_id' => $agent_id,
			'profile_img' => $profile_img,
			'comment' => $_POST['comment'],

			'updated_by' => $_POST['user']
		);

		// print_r($array);

		// echo $agent_id;
		// echo $owner_id;
		// die;

		$act = array(
			'user_id' => $_POST['user_id'],
			'date' => $today,
			'action' => " Client " . $_POST['name'] . " (" . $_GET['edit'] . ") Basic Info Updated by " . $_POST['user'],
			'type' => "update",
			'user_details' => json_encode($this->user_detail())
		);
		session_start();
		$csrf = $_POST['csrf_token'];
		$where = "id = $owner_id";

		$data = $this->csrf_update('client', $array, $where, $csrf);
		$actvity = $this->insert_userAct('user_actvity', $act, $csrf);
		$this->msg_set($data, 'clients&edit=' . $owner_id);
	}



	function client_kyc()
	{
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d");

		$client_id = $_POST['client_id'];

		$cli = $this->getQuery("SELECT * from client where id='$client_id'");
		$cli_value = $cli[0];

		if ($_POST['aadhar_number']) {
			$aadhar_number = $_POST['aadhar_number'];
		} else {
			$aadhar_number = '';
		}

		if ($_POST['pan_number']) {
			$pan_number = $_POST['pan_number'];
		} else {
			$pan_number = '';
		}

		if ($_POST['driving_licence']) {
			$driving_licence = $_POST['driving_licence'];
		} else {
			$driving_licence = '';
		}

		if ($_POST['passport_number']) {
			$passport_number = $_POST['passport_number'];
		} else {
			$passport_number = '';
		}




		// -------------- upload_aadhaar  ----------------
		if (!empty($_FILES["upload_aadhaar"]["tmp_name"])) {
			$tmp_name2 = $_FILES["upload_aadhaar"]["tmp_name"];
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
			$tmp_name2 = $_FILES["upload_pan"]["tmp_name"];
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
			$tmp_name2 = $_FILES["upload_dl"]["tmp_name"];
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
			$tmp_name2 = $_FILES["upload_passport"]["tmp_name"];
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

		$act = array(
			'user_id' => $_POST['user_id'],
			'date' => $today,
			'action' => " Client " . $cli_value->name . "(" . $_POST['client_id'] . ") KYC Updated"

		);
		$where = "id=$client_id";
		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_update('client', $array, $where, $csrf);
		$actvity = $this->insert_query('user_actvity', $act);
		$this->msg_set($data, 'clients&edit=' . $client_id);
	}


	function add_client_comment()
	{
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d");

		$cid = $this->int($_GET['view']);

		$cli = $this->getQuery("SELECT * from client where id='$cid'");
		$cli_value = $cli[0];

		$array = array(
			'client_id' => $this->int($_GET['view']),
			'date' => $_POST['date'],
			'uploader' => $_POST['uploader'],
			'comment' => $_POST['comment']
		);


		$data = $this->insert_query('client_comments', $array);
		$act = array(
			'user_id' => $_POST['user_id'],
			'date' => $today,
			'action' => " Client " . $cli_value->name . " (" . $cid . ") Comment Added"

		);
		$actvity = $this->insert_query('user_actvity', $act);
		$nav = "?nav=clients&add=1&view=" . $_GET['view'] . "#comment";
		$this->msg_set($data, $nav);


	}

	function update_client_comment()
	{
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d");

		$cid = $this->int($_GET['view']);
		$cli = $this->getQuery("SELECT * from client where id='$cid'");
		$cli_value = $cli[0];
		$array = array(
			'client_id' => $this->int($_GET['view']),
			'date' => $_POST['date'],
			'uploader' => $_POST['uploader'],
			'comment' => $_POST['comment']
		);

		$update_id = $_GET['edit'];
		$where = "id=" . $update_id;

		$act = array(
			'user_id' => $_POST['user_id'],
			'date' => $today,
			'action' => " Client  " . $cli_value->name . " (" . $cid . ") Comment Updated"

		);

		$data = $this->update_query('client_comments', $array, $where);
		$actvity = $this->insert_query('user_actvity', $act);
		$nav = "?nav=clients&up=1&view=" . $_GET['view'] . "#comment";
		$this->msg_set($data, $nav);


	}



	function leadConvert()
	{

		// echo "tryyy";   

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d");

		echo $leadId = $_POST['lead_id'];
		echo $id = $_POST['user_id'];

		echo $uploader = $_POST['uploader'];
		// die;
		$lead_data = $this->mysqli->query("select * from leads where id = " . $leadId);
		$row[] = $lead_data->fetch_object();


		$array = array(
			'lead_id' => $row[0]->id,
			'name' => $row[0]->lead_name,
			'contact' => $row[0]->lead_contact,
			'alternate_contact' => $row[0]->alternate_contact,
			'mail' => $row[0]->lead_mail,

			'contract' => $row[0]->contract,

			'reference' => $row[0]->reference,
			'agent_id' => $row[0]->agent_id,
			'updated_by' => $uploader
		);

		//  	print_r($array);
//     	die;	
		$act = array(
			'user_id' => $_POST['user_id'],
			'date' => $today,
			'action' => " Lead " . $row[0]->lead_name . " (" . $_POST['lead_id'] . ") Converted into Client"

		);
		$data = $this->insert_query('client', $array);
		//echo $error=$this->mysqli->error;
		//die;

		session_start();


		if ($data) {
			$actvity = $this->insert_query('user_actvity', $act);
			$data = $this->mysqli->query("Update leads Set convert_to_client = '1'	Where id = $leadId");

			$_SESSION['suc'] = $row[0]->lead_name . 'Converted to Client Successfully! <a href="client-edit.php?edit=' . $row[0]->lead_name;
			header("location: ?nav=clients");

		} else {
			$_SESSION['fal'] = 'Not able to conver lead as client!' . $_POST['lead_name'] . " - " . $this->mysqli->error;
			header("location: all-leads.php?nav=leads");
		}
		header("location: ?nav=clients");
		die;

	}



	function client_status()
	{



		// date_default_timezone_set("Asia/Kolkata");
		// $date_time =    date("d/m/Y h:i:sa");

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");
		;
		$cid = $_POST['client_id'];
		$owner_id = $_POST['client_id'];
		$cli = $this->getQuery("SELECT * from client where id='$cid'");
		$cli_value = $cli[0];

		$array = array(
			'is_verified' => $_POST['is_verified'],

			'is_verify' => $_POST['is_verify'],
			'updated_by' => $_POST['user']
		);

		$act = array(
			'user_id' => $_POST['user_id'],
			'date' => $today,
			'action' => " Client  " . $cli_value->name . " (" . $_POST['client_id'] . ") Status Updated by " . $_POST['user'],
			'type' => 'update',
			'user_details' => json_encode($this->user_detail())

		);
		$csrf = $_POST['csrf_token'];

		// print_r($_POST);
		// die;

		$where = " id= $owner_id";
		$data = $this->csrf_update('client', $array, $where, $csrf);
		$actvity = $this->insert_userAct('user_actvity', $act, $csrf);
		$this->msg_set($data, '?nav=clients&edit=' . $owner_id);


	}






}





if (isset($_POST['client-add'])) {
	$obj = new client();
	$obj->client_add();
}

if (isset($_POST['convert-clients'])) {
	$obj = new client();
	$obj->leadConvertToClient();
}


if (isset($_POST['client-edit'])) {
	$obj = new client();
	$obj->client_edit();
}

if (isset($_POST['client-kyc'])) {
	$obj = new client();
	$obj->client_kyc();
}

if (isset($_POST['add-comment'])) {
	$obj = new client();
	$obj->add_client_comment();
}

if (isset($_POST['update-comment'])) {
	$obj = new client();
	$obj->update_client_comment();
}


if (isset($_POST['convert-client'])) {
	$obj = new client();
	$obj->leadConvert();
}

if (isset($_POST['client-status'])) {
	$obj = new client();
	$obj->client_status();
}

?>