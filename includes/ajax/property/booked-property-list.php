
<?php

require_once('../../config.php');

$boj->check_session();

if (isset($_POST['status']) && $_POST['id'] != null) {
	$status = $_POST['status'];
	$id = $_POST['id'];

	if($status === '1'){
		$qry = $boj->getQuery("UPDATE property_listing SET status = '1' WHERE id = $id");


	}else{

		$qry = $boj->getQuery("UPDATE property_listing SET status = '6' WHERE id = $id");
	}

	// Return a success response
	$response['data'] = ['message' => 'Status updated successfully'];
	
	} else {
	// $response['data'] = ['message' => 'Invalid request'];
	}


    ?>