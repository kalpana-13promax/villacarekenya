<?php

class masters extends db
{

	function property_fields()
	{

		$array = array(
			'field_name' => $_POST['field_name'],
			'created_by' => $_POST['user']
		);
		$data = $this->insert_query('property_fields', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property Field " . $_POST['amenity'] . "  (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);





		$this->msg_set($data, 'masters');
	}

	function source_add()
	{

		$array = array(
			'source_name' => $_POST['source'],
			'created_by' => $_POST['user']
		);
		$data = $this->insert_query('source', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Source " . $_POST['source'] . " (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);





		$this->msg_set($data, "masters");
	}




	function source_update()
	{


		$array = array(
			'source_name' => $_POST['source']

		);
		//$where = $_POST['id'];
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('source', $array, $where);
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Source " . $_POST['source'] . "   Updated",

		);
		$data = $this->insert_query('user_actvity', $act);





		$this->msg_set($data, "masters");
	}




	function project_status()
	{
		if(!empty($_FILES['image']['name'])){
			$image = $_FILES['image'];
			$image_name= $this->uploadFiles($image);
		}else{
			$image_name = '';
		}
		$array = array(
			'status' => $_POST['status'],
			'user_name' => $_POST['user'],
			'image' => $image_name

		); 
			$data = $this->csrf_insert('project_status', $array,$_POST['csrf_token']);

		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "  Project Status  " . $_POST['status'] . " (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);

		$this->msg_set($data, 'masters');
	}

	function update_project_status()
	{
		if(!empty($_FILES['image']['name'])){
			$image = $_FILES['image'];
			$image_name= $this->uploadFiles($image);
		}else{
			$image_name ='';
		}
	
		$array = array(
			'status' => $_POST['status'],
			'user_name' => $_POST['user'],
			'image' => $image_name
		);
	
		$where = "id = " . $_GET['edit'];
		$data = $this->csrf_update('project_status', $array, $where,$_POST['csrf_token']);
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "  Project Status  " . $_POST['status'] . " (" . $_GET['edit'] . ")  Updated",

		);
		$data = $this->insert_query('user_actvity', $act);


		$this->msg_set($data, 'masters');
	}

	function mark_color()
	{

		// echo "bingo!";
		$array = array(
			'label' => $_POST['label'],
			'color' => $_POST['color'],
			'description' => $_POST['description'],
			'uploader' => $_POST['uploader']
		);
		$data = $this->insert_query('mark_color', $array);

		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Conditional Marking " . $_POST['label'] . " (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);


		session_start();
		//echo $_SESSION['suc'] = 'Course Added Successfully';
		if ($data) {
			$_SESSION['suc'] = ' Added Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong! '  . $this->mysqli->error;
		}
		header("location: ?nav=masters");
		die;
	}

	function mark_update()
	{

		// echo "bingo!";
		$array = array(
			'label' => $_POST['label'],
			'color' => $_POST['color'],
			'description' => $_POST['description'],
			'uploader' => $_POST['uploader']
		);
		//$where = $_POST['id'];
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('mark_color', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Conditional Marking " . $_POST['label'] . " (" . $_POST['id'] . ")  Updated",

		);
		$data = $this->insert_query('user_actvity', $act);




		session_start();
		if ($data) {
			$_SESSION['suc'] = ' Updated Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong!';
		}
		header("location: ?nav=masters");
		die;
	}



	function contract_add()
	{

		$array = array(
			'module' => $_POST['module'],
			'contract_name' => $_POST['contract_name'],
			'contract_type' => $_POST['contract_value'],
			'uploader' => $_POST['user']
		);
		$data = $this->insert_query('contract', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Contract  " . $_POST['contract_name'] . " (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);
		session_start();


		if ($data) {
			$_SESSION['suc'] = ' Added Successfully';
		} else {
			$_SESSION['fal'] = 'Something wrong or Duplicate Record Found!';
		}
		header("location: ?nav=masters");
		die;
	}


	function contract_update()
	{

		$array = array(
			'module' => $_POST['module'],
			'contract_name' => $_POST['contract_name'],
			'contract_type' => $_POST['contract_value'],
			'uploader' => $_POST['user']
		);
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('contract', $array, $where);
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Contract " . $_POST['contract_name'] . " (" . $_POST['id'] . ")  Updated",

		);
		$data = $this->insert_query('user_actvity', $act);
		session_start();


		if ($data) {
			$_SESSION['suc'] = ' Added Successfully';
		} else {
			$_SESSION['fal'] = 'Something wrong or Duplicate Record Found!';
		}
		header("location: ?nav=masters");
		die;
	}





	function add_payment_mode()
	{
		$array = array(
			'mode_name' => $_POST['source'],
			'status' => $_POST['status'],
			'uploader' => $_POST['user']
		);
		$data = $this->insert_query('payment_modes', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "  Payment Modes  " . $_POST['source'] . " (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);
		session_start();


		if ($data) {
			$_SESSION['suc'] = ' Added Successfully';
		} else {
			$_SESSION['fal'] = 'Something wrong!';
		}
		header("location: ?nav=masters");
		die;
	}

	function update_payment_mode()
	{

		$array = array(
			'mode_name' => $_POST['source'],
			'status' => $_POST['status'],
			'uploader' => $_POST['user']
		);
		//$where = $_POST['id'];
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('payment_modes', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "  Payment Modes  " . $_POST['source'] . " (" . $_POST['id'] . ")  Updated",

		);
		$data = $this->insert_query('user_actvity', $act);

		session_start();
		if ($data) {
			$_SESSION['suc'] = ' Updated Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong!';
		}
		header("location: ?nav=masters");
		die;
	}



	function add_payment_type()
	{
		$array = array(
			'type' => $_POST['source'],
			'status' => $_POST['status'],
			'uploader' => $_POST['user']
		);
		$data = $this->insert_query('payment_type', $array);

		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "  Payment Type  " . $_POST['source'] . " (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);
		session_start();


		if ($data) {
			$_SESSION['suc'] = ' Added Successfully';
		} else {
			$_SESSION['fal'] = 'Something wrong!';
		}
		header("location: ?nav=masters");
		die;
	}


	function update_payment_type()
	{

		$array = array(
			'type' => $_POST['source'],
			'status' => $_POST['status'],
			'uploader' => $_POST['user']
		);



		// print_r($array);
		// die;
		//$where = $_POST['id'];
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('payment_type', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "  Payment Type  " . $_POST['source'] . " (" . $_POST['id'] . ")  Updated",

		);
		$data = $this->insert_query('user_actvity', $act);

		session_start();
		if ($data) {
			$_SESSION['suc'] = ' Updated Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong!';
		}
		header("location: ?nav=masters");
		die;
	}


	function add_bank()
	{



		$array = array(
			'bank_name' => $_POST['bank_name'],
			'status' => $_POST['status'],
			'uploader' => $_POST['user']

		);
		//print_r($array);
		//	die;

		$data = $this->insert_query('bank', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Bank  " . $_POST['bank_name'] . " (" . $last_id . ")  Added",

		);
		$data = $this->insert_query('user_actvity', $act);
		session_start();
		if ($data) {

			$_SESSION['suc'] = "Bank added successfully";
		} else {

			$_SESSION['fal'] = "Some thing went wrong";
		}
		header("location:?nav=masters");
		die;
	}







	function export_data()
	{





		// Server hostname or IP address
		$server_hostname = HOSTS;

		// The name of your MySQL database instance
		$database_name = DATABASE;

		// The username of your database login credential 
		$username = USERNAME;

		// The password of your database login credential
		$password = PASSWORD;

		$link_sqli = mysqli_connect($server_hostname, $username, $password, $database_name);

		// If an error occurred while connecting to the database, display the error code and exit.
		if (!$link_sqli) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging error #: " . mysqli_connect_errno() . PHP_EOL;
			echo "Error description: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		// END: Establish a connection to the database

		// BEGIN: Define some variables
		// INSTRUCTION: Specify your table name and the name of your export file.

		// The name of data table containing the data you wish to export
		$TableName = $_POST['table'];

		// The filename you want your export file to be named
		$Filename = $_POST['table'];
		// END: Define some variables

		// *** No more configurable options below this point for this code to function on most servers ***
		// Fetch records from the database table specified in the variable $TableName
		$Output = "";
		$strSQL = "SELECT * FROM $TableName";
		$sql = mysqli_query($link_sqli, $strSQL);
		// If the database query encounters an error, display the error message.
		// Otherwise, start the export process.
		if (mysqli_error($link_sqli)) {
			echo mysqli_error($link_sqli);
		} else {
			// Determine the number of data columns in the table
			$columns_total = mysqli_num_fields($sql);

			// Get the name of the data columns so it can be used in the header row of the export file.
			// Content of the export file is temporarily saved in the variable $Output
			for ($i = 0; $i < $columns_total; $i++) {
				$Heading = mysqli_fetch_field_direct($sql, $i);
				$Output .= '"' . $Heading->name . '",';
			}
			$Output .= "\n";
			// The /n is the control code to go to a new line in the export file.

			// Loop through each record in the table and read the data value from each column.
			while ($row = mysqli_fetch_array($sql)) {
				for ($i = 0; $i < $columns_total; $i++) {
					$Output .= '"' . $row["$i"] . '",';
				}
				$Output .= "\n";
			}

			// Create the export file and name it with the name specified in variable $Filename
			// Also appends the current timestamp (in the format yyyymmddhhmmss) to the filename and give it a .CSV file extension.
			// The timestamp serves as a time reference to identify when the data was exported.
			//File is comma delimited with double-quote used a the text qualifier
			// Once  file is created, download of the file begins automatically (tested on Google Chrome).
			$TimeNow = date("YmdHis");
			$Filename .= $TimeNow . ".csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $Filename);
			echo $Output;
		}

		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");


		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property " . $Filename . " has Exported",

		);
		$activity = $this->insert_query('user_actvity', $act);
		exit;
	}




	function owner_mark_color()
	{

		// echo "bingo!";
		$array = array(
			'label' => $_POST['label'],
			'color' => $_POST['color'],
			'description' => $_POST['description'],
			'uploader' => $_POST['uploader']
		);
		// 		print_r($array);
		// 		die;
		$data = $this->insert_query('owner_marking', $array);

		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "Owner Conditional Marking " . $_POST['label'] . " (" . $last_id . ")  Added",

		);
		// 		$data = $this->insert_query('user_actvity', $act);		

		// 		print_r($act);
		// 		die;
		session_start();
		//echo $_SESSION['suc'] = 'Course Added Successfully';
		if ($data) {
			$_SESSION['suc'] = ' Added Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong! '  . $this->mysqli->error;
		}
		header("location: ?nav=masters");
		die;
	}

	function owner_mark_update()
	{

		// echo "bingo!";
		$array = array(
			'label' => $_POST['label'],
			'color' => $_POST['color'],
			'description' => $_POST['description'],
			'uploader' => $_POST['uploader']
		);
		//$where = $_POST['id'];
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('owner_marking', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "Owner Conditional Marking " . $_POST['label'] . " (" . $_POST['id'] . ")  Updated",

		);
		// 		$data = $this->insert_query('user_actvity', $act);	




		session_start();
		if ($data) {
			$_SESSION['suc'] = ' Updated Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong!';
		}
		header("location: ?nav=masters");
		die;
	}


	function property_mark_color()
	{

		// echo "bingo!";
		$array = array(
			'label' => $_POST['label'],
			'color' => $_POST['color'],
			'description' => $_POST['description'],
			'uploader' => $_POST['uploader']
		);
		// 		print_r($array);
		// 		die;
		$data = $this->insert_query('property_marking', $array);

		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "Property Conditional Marking " . $_POST['label'] . " (" . $last_id . ")  Added",

		);
		// 		$data = $this->insert_query('user_actvity', $act);		

		// 		print_r($act);
		// 		die;
		session_start();
		//echo $_SESSION['suc'] = 'Course Added Successfully';
		if ($data) {
			$_SESSION['suc'] = ' Added Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong! '  . $this->mysqli->error;
		}
		header("location: ?nav=masters");
		die;
	}



	function property_mark_update()
	{

		// echo "bingo!";
		$array = array(
			'label' => $_POST['label'],
			'color' => $_POST['color'],
			'description' => $_POST['description'],
			'uploader' => $_POST['uploader']
		);
		//$where = $_POST['id'];
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('property_marking', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$today =  date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => "Property Conditional Marking " . $_POST['label'] . " (" . $_POST['id'] . ")  Updated",

		);
		// 		$data = $this->insert_query('user_actvity', $act);	




		session_start();
		if ($data) {
			$_SESSION['suc'] = ' Updated Successfully';
		} else {
			$_SESSION['fal'] = ' Failiure!, Something Wrong!';
		}
		header("location: ?nav=masters");
		die;
	}







	function add_area(){
		$array = array(
'area' => $_POST['area'],

'uploader' => $_POST['user']
);
$data = $this->insert_query('area', $array);

session_start();


if( $data ){
$_SESSION['suc'] = ' Added Successfully';
}else{
$_SESSION['fal'] = 'Something wrong!';

}
header("location: ?nav=masters");
die;
}

function update_area(){
	$array = array(
'area' => $_POST['area'],

'uploader' => $_POST['user']
);
$where = "id = " . $_POST['id'];
$data = $this->update_query('area', $array, $where);

session_start();


if( $data ){
$_SESSION['suc'] = ' Added Successfully';
}else{
$_SESSION['fal'] = 'Something wrong!';

}
header("location: ?nav=masters");
die;
}




function add_quota(){
	$array = array(
'quota_name' => $_POST['quota_name'],

'uploader' => $_POST['user']
);
$data = $this->insert_query('quota', $array);

session_start();


if( $data ){
$_SESSION['suc'] = ' Added Successfully';
}else{
$_SESSION['fal'] = 'Something wrong!';

}
header("location: ?nav=masters");
die;
}

function update_quota(){
	$array = array(
'quota_name' => $_POST['quota_name'],

'uploader' => $_POST['user']
);

$where = "id = " . $_POST['edit_id'];
$data = $this->update_query('quota', $array, $where);

session_start();


if( $data ){
$_SESSION['suc'] = ' Updated Successfully';
}else{
$_SESSION['fal'] = 'Something wrong!';

}
header("location: ?nav=masters");
die;
}





function add_priority(){
	$array = array(
'priority' => $_POST['priority'],

'uploader' => $_POST['user']
);
$data = $this->insert_query('priority', $array);

session_start();


if( $data ){
$_SESSION['suc'] = ' Added Successfully';
}else{
$_SESSION['fal'] = 'Something wrong!';

}
header("location: ?nav=masters");
die;
}

function update_priority(){
	$array = array(
'priority' => $_POST['priority'],

'uploader' => $_POST['user']
);

$where = "id = " . $_POST['edit_id'];
$data = $this->update_query('priority', $array, $where);

session_start();


if( $data ){
$_SESSION['suc'] = ' Updated Successfully';
}else{
$_SESSION['fal'] = 'Something wrong!';

}
header("location: ?nav=masters");
die;
}





}

if (isset($_POST['field-add'])) {
	$obj = new masters();
	$obj->property_fields();
}

if (isset($_POST['source-add'])) {
	$obj = new masters();
	$obj->source_add();
}
if (isset($_POST['source-update'])) {
	$obj = new masters();
	$obj->source_update();
}
if (isset($_POST['mark'])) {
	$obj = new masters();
	$obj->mark_color();
}
if (isset($_POST['mark-update'])) {
	$obj = new masters();
	$obj->mark_update();
}
if (isset($_POST['contract-add'])) {
	$obj = new masters();
	$obj->contract_add();
}

if (isset($_POST['add-project-status'])) {
	$obj = new masters();
	$obj->project_status();
}
if (isset($_POST['update-project-status'])) {
	$obj = new masters();
	$obj->update_project_status();
}
if (isset($_POST['contract-update'])) {
	$obj = new masters();
	$obj->contract_update();
}
if (isset($_POST['add-payment-mode'])) {
	$obj = new masters();
	$obj->add_payment_mode();
}

if (isset($_POST['update-payment-mode'])) {
	$obj = new masters();
	$obj->update_payment_mode();
}

if (isset($_POST['add-payment-type'])) {
	$obj = new masters();
	$obj->add_payment_type();
}

if (isset($_POST['update-payment-type'])) {
	$obj = new masters();
	$obj->update_payment_type();
}

if (isset($_POST['add-bank'])) {

	$obj = new masters();
	$obj->add_bank();
}

if (isset($_POST['export-data'])) {
	$obj = new masters();
	$obj->export_data();
}
if (isset($_POST['owner-mark'])) {
	$obj = new masters();
	$obj->owner_mark_color();
}
if (isset($_POST['owner-mark-update'])) {
	$obj = new masters();
	$obj->owner_mark_update();
}


if (isset($_POST['property-mark'])) {
	$obj = new masters();
	$obj->property_mark_color();
}
if (isset($_POST['property-mark-update'])) {
	$obj = new masters();
	$obj->property_mark_update();
}
if(isset($_POST['add-area'])){

	$obj=new masters();
	$obj->add_area();
 }
 //update-area
 if(isset($_POST['update-area'])){

	$obj=new masters();
	$obj->update_area();
 }
 if(isset($_POST['add-quota'])){

	$obj=new masters();
	$obj->add_quota();
 }
 if(isset($_POST['update-quota'])){

	$obj=new masters();
	$obj->update_quota();
 }

 if(isset($_POST['add-priority'])){

	$obj=new masters();
	$obj->add_priority();
 }
 if(isset($_POST['update-priority'])){

	$obj=new masters();
	$obj->update_priority();
 }