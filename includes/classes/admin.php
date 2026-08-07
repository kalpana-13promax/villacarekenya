<?php

class admin extends db
{

	function admin_add()
	{

		if (!empty($_FILES["uploaded_aadhaar"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_aadhaar"]["tmp_name"];
			$name = $_FILES['uploaded_aadhaar']['name'];
			$size = $_FILES['uploaded_aadhaar']['size'];
			$type = $_FILES['uploaded_aadhaar']['type'];
			//	$sani=$this->image_check($name,$size,$type);
			$sani = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_aadhaar = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_aadhaar = '';
		}
		// -------------- // upload_aadhaar ----------------
		// -------------- upload pan ----------------
		if (!empty($_FILES["uploaded_pan"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_pan"]["tmp_name"];
			$name = $_FILES['uploaded_pan']['name'];
			$size = $_FILES['uploaded_pan']['size'];
			$type = $_FILES['uploaded_pan']['type'];
			//	$sani=$this->image_check($name,$size,$type);
			$sani2 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_pan = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_pan = '';
		}
		// -------------- //upload pan ----------------

		// -------------- upload_dl  ----------------
		if (!empty($_FILES["uploaded_dl"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_dl"]["tmp_name"];
			$name = $_FILES['uploaded_dl']['name'];
			$size = $_FILES['uploaded_dl']['size'];
			$type = $_FILES['uploaded_dl']['type'];
			//	$sani=$this->image_check($name,$size,$type);
			$sani3 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_dl = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_dl = '';
		}
		// -------------- // upload_dl ----------------

		// -------------- upload_passport  ----------------
		if (!empty($_FILES["uploaded_passport"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_passport"]["tmp_name"];
			$name = $_FILES['uploaded_passport']['name'];
			$size = $_FILES['uploaded_passport']['size'];
			$type = $_FILES['uploaded_passport']['type'];
			//$sani=$this->image_check($name,$size,$type);
			$sani4 = $this->image_check_values($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_passport = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_passport = '';
		}

		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["profile_image"]["tmp_name"])) {
			 $name=$this->uploadFiles($_FILES['profile_image']);
			// $tmp_name2 = $_FILES["profile_image"]["tmp_name"];
			// $temp2 = explode(".", $_FILES["profile_image"]["name"]);
			// $name = time() . '.' . end($temp2);
			// move_uploaded_file($tmp_name2, "$uploads_dir2/$name");
		} else {

			$name = '';
		}

		if (isset($_POST['realted_location'])) {
			$arr = $_POST['realted_location'];
			$realted_location = implode(", ", $arr);
		} else {
			$realted_location = '';
		}

		$rid = $_POST['usertype'];
		$roles = $this->mysqli->query("select * from roles where id='$rid' ");
		$data = $roles->fetch_assoc();
		// print_r($data);
		$perm = $data['permissions'];
		$utype = $data['name'];


		$array = array(
			'name' => $_POST['name'],
			'username' => $_POST['username'],
			'password' => md5($_POST['password']),
			'usertype' => $utype,
			'roleid' => $rid,
			'permissions' => $perm,
			'contact' => $_POST['contact'],
			'mail' => $_POST['mail'],
			'dob' => $_POST['dob'],
			'aadhar' => $_POST['aadhaar'],
			'uploaded_aadhaar' => $uploaded_aadhaar,
			'pan' => $_POST['pan'],
			'uploaded_pan' => $uploaded_pan,
			'dl' => $_POST['dl'],
			'uploaded_dl' => $uploaded_dl,
			'passport' => $_POST['passport'],
			'uploaded_passport' => $uploaded_passport,
			'profile_img' => $this->sanitize($name),

			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'pin_code' => $_POST['pin_code'],
			'deals_in' => $_POST['deals_in'],
			'remarks' => $_POST['remarks'],
			'status' => $_POST['status'],
			'realted_area' => $realted_location,
			'uploader' => $_POST['uploader'],
			'supervisor_id'=>$_POST['supervisor_id']??''

		);


		// print_r($array);
		// die;
		$name = $_POST['name'];
		$contact = $_POST['contact'];

		$mail = $_POST['mail'];
		$dob = $_POST['dob'];

		$aadhar = $_POST['aadhaar'];
		$pan = $_POST['pan'];
		$dl = $_POST['dl'];
		$passport = $_POST['passport'];
		$address = $_POST['address'];

		$city = $_POST['city'];

		$state = $_POST['state'];
		$pin = $_POST['pin_code'];
		$deals_in = $_POST['deals_in'];



		//          echo "<pre>";
		// 		print_r($array);
		// 		echo "</pre>";
		// 		die;

		session_start();

		if ($sani == 1 or $sani2 == 1 or $sani3 == 1 or $sani4 == 1) {

			$_SESSION['fal'] = "File size must be less than or equal to 1MB.";

			header("location: ?nav=users&name=$name&contact=$contact&mail=$mail&dob=$dob&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&address=$address&city=$city&state=$state&pin=$pin&deals_in=$deals_in");
			die;
		} elseif ($sani == 2 or $sani2 == 2 or $sani3 == 2 or $sani4 == 2) {

			$_SESSION['fal'] = "File type must be jpg/png/jpeg format.";
			header("location: ?nav=users&name=$name&contact=$contact&mail=$mail&dob=$dob&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&address=$address&city=$city&state=$state&pin=$pin&deals_in=$deals_in");
			die;
		} else {

			try{

				$dt = $this->insert_query('user', $array);
			}catch(Exception $e){
				$msg= "Oops! Failed, Something wrong or Duplicate Record Found! " . $e->getMessage();
			}
			if($dt){

				$last_id = $this->mysqli->insert_id;
				date_default_timezone_set("Asia/Kolkata");
				$date_time = date("Y-m-d h:i:sa");
				$today = date("Y-m-d h:i:sa");
				$ac= " New User " . $_POST['name'] . " (" . $last_id . ") has Created";
				
			}else{
				$last_id = 0;
				$msg="Oops! Failed, Something wrong or Duplicate Record Found! " . $this->mysqli->error;
				$ac="New User " . $_POST['name'] .$msg;
			}
			$act = array(
					'user_id' => $_POST['user_id'],
					'action' =>$ac,
					
					
				);
			$data = $this->insert_query('user_actvity', $act);


			if ($dt) {
				$_SESSION['suc'] = 'Account Created Successfully';
			} else {
				//$_SESSION['fal'] = 'Oops! Failed, Something wrong or Duplicate Record Found!' . $_POST['username'];
				$_SESSION['fal'] = "Oops! Failed, Something wrong or Duplicate Record Found! " . $this->mysqli->error;
			}
			header("location: ?nav=users&name=$name&contact=$contact&mail=$mail&dob=$dob&aadhaar=$aadhaar&pan=$pan&dl=$dl&passport=$passport&address=$address&city=$city&state=$state&pin=$pin&deals_in=$deals_in");
			die;
		}
	}









	function admin_update()
	{



		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

		$agent_id = $_POST['agent_id'];



		$data = $this->mysqli->query("Update agent 
				Set agent_name = '$_POST[agent_name]',  
				agent_contact = '$_POST[agent_contact]', 
				agent_mail = '$_POST[agent_mail]', 
				agent_dob = '$_POST[dob]', 
				agent_pan = '$_POST[pan]', 
				
				uploaded_aadhaar = $upload_aadhaar, 
				uploaded_pan = $upload_pan, 
				uploaded_dl = $upload_dl, 
				uploaded_passport = $upload_passport, 

				agent_address = '$_POST[address]', 
				agent_city = '$_POST[city]', 
				agent_state = '$_POST[state]', 
				agent_pin = '$_POST[pin]', 
				agent_deals_in = '$_POST[deals_in]', 

				updated_by = '$_POST[user]' 

				Where id = $agent_id
			");

		session_start();

		if ($data) {
			$_SESSION['suc'] = 'Lead Updated!';
		} else {
			$_SESSION['fal'] = 'Oops! not insert, Something wrong.' . $this->mysqli->error;
		}
		header("location: ?nav=users&edit=" . $agent_id);
		die;
	}






	function admin_status_update()
	{

		$id = $_POST['admin_id'];
		$array = array(
			'status' => $_POST['admin-status']
		);
		//	$where = $_POST['id'];
		$where = "id = " . $id;
		$data = $this->update_query('user', $array, $where);

		if ($_POST['admin-status'] == 'active') {
			$var = "Activated!";
		}
		if ($_POST['admin-status'] == 'block') {
			$var = "Blocked!";
		}

		session_start();


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " User Status " . $_POST['admin-status'] . "  has Changed",


		);
		$data = $this->insert_query('user_actvity', $act);



		if ($data) {
			$_SESSION['suc'] = 'Account ' . $var;
		} else {
			$_SESSION['fal'] = 'Something went wrong.' . $this->mysqli->error;
		}
		header("location: ?nav=users");
		die;
	}


	function admin_login_update()
	{


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

		$agent_id = $_POST['agent_id'];


		$rid = $_POST['usertype'];
		$roles = $this->mysqli->query("select * from roles where id='$rid' ");
		$data = $roles->fetch_assoc();
		// print_r($data);
		$perm = $data['permissions'];
		$utype = $data['name'];



		if (isset($_POST['realted_location'])) {
			$arr = $_POST['realted_location'];
			$realted_location = implode(", ", $arr);
		} else {
			$realted_location = '';
		}


		$array = array(
			'status' => $_POST['agent_ac_status'],
			'usertype' => $utype,
			'roleid' => $rid,
			'permissions' => $perm,
			'remarks' => $_POST['remarks'],
			'realted_area' => $realted_location,
			'uploader' => $_POST['user']
		);
		//	print_r($array);
		//	die;
		//$where = $_POST['id'];
		$where = "id = " . $_POST['agent_id'];
		$data = $this->update_query('user', $array, $where);

		$ag = $_POST['agent_id'];

		$user_detail = $this->getQuery("SELECT * from user where id='$ag'");
		$us = $user_detail[0];

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " User " . $us->name . " (" . $ag . ") Account Details Updated",


		);
		$data = $this->insert_query('user_actvity', $act);


		session_start();

		if ($data) {
			$_SESSION['suc'] = ' Login Updated!';
		} else {
			$_SESSION['fal'] = 'Oops! not Updated, Something wrong.' . $this->mysqli->error;
		}
		header("location: ?nav=users&edit=$agent_id");
		die;
	}



	function admin_pass_update()
	{

		if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $_POST['csrf_token']) {

			$_SESSION['fal'] = "Something Went Wrong! or Invalid Credential";

			header("location: ?nav=users");
			die;
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

		$current_pass = md5($_POST['c_pass']);
		$new_pass = md5($_POST['new_pass']);
		$confirm_pass = md5($_POST['conf_pass']);

		$user = $_POST['user'];

		// echo $user;
		// die;
		$data = $this->mysqli->query("SELECT * FROM user where username = '$user' and password = '$current_pass' ");
		if ($data->num_rows) {
			//echo "Current password is OK!";
			if ($new_pass == $confirm_pass) {
				// echo "New and conf pass is ok";
				$data = $this->mysqli->query("Update user 
                        Set password = '$new_pass'
                
                        Where username = '$user'
                            ");

				session_start();




				date_default_timezone_set("Asia/Kolkata");
				$date_time = date("Y-m-d h:i:sa");
				$today = date("Y-m-d h:i:sa");

				$act = array(
					'user_id' => $_POST['userr_id'],
					'action' => " Password has Changed",


				);
				$data = $this->insert_query('user_actvity', $act);





				if ($data) {
					$_SESSION['suc'] = 'Password Updated!';
				} else {
					$_SESSION['fal'] = 'Oops! Password Not Updated, Something went wrong.';
				}
				header("location: ?nav=setting");
				die;
			} else {
				session_start();

				$_SESSION['fal'] = 'New Password and Confirmed Password Not Matched!';
				header("location: ?nav=setting");
				die;
			}
		} else {
			session_start();

			$_SESSION['fal'] = 'Current Password not Matched!';
			header("location: ?nav=setting");
			die;
		}
	}






	function pass_update()
	{



		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

		$agent_id = $_POST['agent_id'];


		$psw = md5($_POST['psw']);
		$data = $this->mysqli->query("Update user 
                        Set password = '$psw',
						uploader = '$_POST[user]'
                
                        Where id = '$agent_id'
                            ");

		session_start();

		if ($data) {
			$_SESSION['suc'] = ' Password Updated!';
		} else {
			$_SESSION['fal'] = 'Oops! Password Not Updated! ' . $this->mysqli->error;
		}
		header("location: ?edit=$agent_id");
		die;
	}


	function admin_edit()
	{
		$id = $_GET['edit'];

		$imgqry = $this->mysqli->query("SELECT * FROM user where id ='$id' ");
		$r_data = $imgqry->fetch_assoc();

		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["profile_image"]["tmp_name"])) {
			$tmp_name2 = $_FILES["profile_image"]["tmp_name"];
			$temp2 = explode(".", $_FILES["profile_image"]["name"]);
			$profile_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$profile_image");
		} else {

			$profile_image = $r_data['profile_img'];
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		$array = array(

			'name' => $_POST['agent_name'],
			'contact' => $_POST['agent_contact'],
			'mail' => $_POST['agent_mail'],
			'dob' => $_POST['dob'],
			'deals_in' => $_POST['deals_in'],
			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'profile_img' => $profile_image,
			'pin_code' => $_POST['pin']



		);

		$update_id = $_POST['user_id'];
		$where = "id=" . $update_id;

		$data = $this->update_query('user', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['userr_id'],
			'action' => " User  " . $_POST['agent_name'] . " (" . $update_id . ") Basic Info has Updated",


		);
		$data = $this->insert_query('user_actvity', $act);



		session_start();

		if ($data) {
			$_SESSION['suc'] = 'Updated Successfully';
		} else {
			$_SESSION['fal'] = 'Oops! Not Updated! ' . $this->mysqli->error;
		}
		header("location: ?edit=$update_id");
		die;
	}






	function admin_kyc()
	{

		$edit = $_GET['edit'];

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		$aadhaar_number = $_POST['aadhaar_number'];
		if (!empty($_FILES["uploaded_aadhaar"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_aadhaar"]["tmp_name"];
			$name = $_FILES['uploaded_aadhaar']['name'];
			$size = $_FILES['uploaded_aadhaar']['size'];
			$type = $_FILES['uploaded_aadhaar']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_aadhaar = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_aadhaar = '';
		}

		if (!empty($_FILES["uploaded_pan"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_pan"]["tmp_name"];
			$name = $_FILES['uploaded_pan']['name'];
			$size = $_FILES['uploaded_pan']['size'];
			$type = $_FILES['uploaded_pan']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_pan = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_pan = '';
		}

		if (!empty($_FILES["uploaded_dl"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_dl"]["tmp_name"];
			$name = $_FILES['uploaded_dl']['name'];
			$size = $_FILES['uploaded_dl']['size'];
			$type = $_FILES['uploaded_dl']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_dl = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_dl = '';
		}


		if (!empty($_FILES["uploaded_passport"]["tmp_name"])) {
			$tmp_name2 = $_FILES["uploaded_passport"]["tmp_name"];
			$name = $_FILES['uploaded_passport']['name'];
			$size = $_FILES['uploaded_passport']['size'];
			$type = $_FILES['uploaded_passport']['type'];
			$sani = $this->image_check($name, $size, $type);
			$ext = explode(".", $sani);
			// $uploaded_pan =       time().'.'.end($ext);
			$uploaded_passport = base64_encode(file_get_contents($tmp_name2));
		} else {
			$uploaded_passport = '';
		}

		$array = array(
			'aadhar' => $aadhaar_number,
			'uploaded_aadhaar' => $uploaded_aadhaar,
			'pan' => $_POST['pan_number'],
			'uploaded_pan' => $uploaded_pan,

			'dl' => @$_POST['dl'],
			'uploaded_dl' => $uploaded_dl,
			'passport' => $_POST['passport_number'],
			'uploaded_passport' => $uploaded_passport



		);


		// echo $_POST['dl_check'];
		//echo "<pre>";
		// print_r($array);
		//echo "</pre>";
		// die; 
		$where = "id=" . $edit;
		session_start();
		$user_detail = $this->getQuery("SELECT * from user where id='$edit'");
		$us = $user_detail[0];
		$data = $this->update_query("user", $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['userr_id'],
			'action' => " User KYC " . $us->name . " (" . $edit . ") has Updated",


		);
		$data = $this->insert_query('user_actvity', $act);




		if ($data) {
			$_SESSION['suc'] = 'UPDATED SUCCESSFULLY.';
		} else {
			$_SESSION['fal'] = 'Oops! Not Updated' . $this->mysqli->error;
		}


		header("location: ?edit=$edit");
		die;
	}



	function update_profile()
	{


		$id = $_POST['user_id'];

		$imgqry = $this->mysqli->query("SELECT * FROM user where id ='$id' ");
		$r_data = $imgqry->fetch_assoc();

		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["profile_image"]["tmp_name"])) {
			$tmp_name2 = $_FILES["profile_image"]["tmp_name"];
			$temp2 = explode(".", $_FILES["profile_image"]["name"]);
			$profile_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$profile_image");
		} else {

			$profile_image = $r_data['profile_img'];
		}

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

		$id = $_POST['user_id'];



		$data = array(
			'name' => $_POST['name'],
			'username' => $_POST['username'],
			'contact' => $_POST['contact'],
			'mail' => $_POST['mail'],
			'ivr_key' => $_POST['ivr_key'],
			'dob' => $_POST['dob'],
			'username' => $_POST['username'],
			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'pin_code' => $_POST['pin_code'],
			'profile_img' => $profile_image
		);

		$where = "id=" . $id;

		$data = $this->update_query("user", $data, $where);

		session_start();

		if ($data) {
			$_SESSION['suc'] = 'Profile Updated!';
		} else {
			$_SESSION['fal'] = 'Oops! not insert, Something wrong.' . $this->mysqli->error;
		}
		header("location: ?nav=users&edit=" . $id);
		die;
	}









	function save_role()
	{
		$str = $_POST['perm'];

		$perm = implode(', ', $str);
		//die;
		$array = array(
			'name' => $_POST['role_name'],
			'parent_id'=>$_POST['parent_role'],
			'hierarchy_level'=>$_POST['hierarchy_level'],
			'permissions' => $perm
		);
		// print_r($array);
		// die;
		$data = $this->insert_query('roles', $array);
		$last_id = $this->mysqli->insert_id;

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "New Role " . $_POST['role_name'] . " (" . $last_id . ") Added",


		);
		$data = $this->insert_query('user_actvity', $act);





		$this->msg_set($data, 'users');
	}

	function update_role()
	{
		$str = $_POST['perm'];

		$perm = implode(', ', $str);
		//die;
		if(empty($_POST['parent_role'])){
			$_POST['parent_role']=null;
		}
		$array = array(
			'name' => $_POST['role_name'],
			'parent_id'=>$_POST['parent_role']??null,
			'hierarchy_level'=>$_POST['hierarchy_level'],
			'permissions' => $perm
		);
		
		$roleid = $_GET['edit'];
		$where = 'id =' . $_GET['edit'];
		$nav = 'users&edit=' . $_GET['edit'];
		try{

			$data = $this->update_query('roles', $array, $where);
		}catch(Exception $e){
			echo $e->getmessage();
		}
	
		if ($data) {
			$data = $this->mysqli->query("Update user Set permissions = '$perm'	Where roleid = $roleid");
			$this->msg_set($data, $nav);
		}





		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Role " . $_POST['role_name'] . "  Updated",


		);
		$data = $this->insert_query('user_actvity', $act);

		$this->msg_set($data, $nav);
	}


	function update_user_role()
	{
		$str = $_POST['perm'];
if(empty($str)){
	return $_SESSION['fal']="you have not any permission selected ";
}
		$perm = implode(', ', $str);
		//die;
		$array = array(
			//'name' => $_POST['role_name'],
			'permissions' => $perm
		);
		// print_r($array);
		//die;
		$where = 'id =' . $_GET['edit'];
		$nav = 'users&edit=' . $_GET['edit'];
		$data = $this->update_query('user', $array, $where);

		$ag = $_GET['edit'];
		$user_detail = $this->getQuery("SELECT * from user where id='$ag'");
		$us = $user_detail[0];

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("Y-m-d h:i:sa");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " User " . $us->name . " (" . $ag . ") Permission Updated",


		);
		$data = $this->insert_query('user_actvity', $act);
		$this->msg_set($data, $nav);
	}

	function update_hierarchy(){

		$id = $_POST['user_id'];
		$supervisor_id=$_POST['supervisor_id'];

		$array = array(
		
			'supervisor_id' => $supervisor_id
		);
	$where = "id = " . intval($id);
	$data = $this->update_query('user', $array, $where);

	
		if ($data) {
			$_SESSION['suc'] = 'Hierarchy Updated Successfully';
		} else {
			$_SESSION['fal'] = 'Oops! Not Updated! ' . $this->mysqli->error;
		}
		header("location: ?nav=users&edit=$id");
		die;

	}
}




if (isset($_POST['admin-add'])) {
	$obj = new admin();
	$obj->admin_add();
}

if (isset($_POST['admin-update'])) {
	$obj = new admin();
	$obj->admin_update();
}

if (isset($_POST['admin-pass-update'])) {
	$obj = new admin();
	$obj->admin_pass_update();
}

if (isset($_POST['admin-status'])) {
	$obj = new admin();
	$obj->admin_status_update();
}
if (isset($_POST['admin-login-update'])) {
	$obj = new admin();
	$obj->admin_login_update();
}
if (isset($_POST['pass-update'])) {
	$obj = new admin();
	$obj->pass_update();
}
if (isset($_POST['admin-edit'])) {

	$obj = new admin();
	$obj->admin_edit();
}
if (isset($_POST['admin-kyc'])) {

	$obj = new admin();
	$obj->admin_kyc();
}


if (isset($_POST['update-profile'])) {

	$obj = new admin();
	$obj->update_profile();
}



if (isset($_POST['save-role'])) {
	$obj = new admin();
	$obj->save_role();
}

if (isset($_POST['update-role'])) {
	$obj = new admin();
	$obj->update_role();
}
if (isset($_POST['update-user-role'])) {
	$obj = new admin();
	$obj->update_user_role();
}
if (isset($_POST['update-hierarchy'])) {
	$obj = new admin();
	$obj->update_hierarchy();
}
