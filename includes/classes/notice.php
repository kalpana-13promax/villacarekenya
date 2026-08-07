<?php

class notice extends db
{
	
	function notice_add ()
	{
	
        
        date_default_timezone_set("Asia/Muscat");

		//$data = $this->mysqli->query("INSERT INTO notice (
			$array	=	array(
			'subject' => $_POST['subject'],
			'notice' => $_POST['notice'],
			'uploader' => $_POST['uploader']
);

        $data	=	$this->insert_query('notice', $array);
		$this->msg_set($data, 'notice');
	}





	function notice_update ()
	{
	
        
        date_default_timezone_set("Asia/Muscat");

		//$data = $this->mysqli->query("INSERT INTO notice (
			$array	=	array(
				'subject' => $_POST['subject'],
				'notice' => $_POST['notice'],
				'uploader' => $_POST['uploader']
	);

	$subject = $_POST['subject'];
	$notice = $_POST['notice'];
	$uploader = $_POST['uploader'];

    $update_id=$_POST['update_id'];

        $data	=	$this->mysqli->query("UPDATE notice set subject='$subject',notice='$notice',uploader='$uploader' where id='$update_id'");
		 session_start();
		
		if($data){

			$_SESSION['suc']="Updated Successfully";


		 }else{
			$_SESSION['fal']="!Something Went Wrong";



		 }

		 header("location:notice-view.php");
		 die;
	}
}
	

if(isset($_POST['notice-add']))
{
	$obj = new notice();
	$obj->notice_add ();
}


if(isset($_POST['notice-update']))
{
	$obj = new notice();
	$obj->notice_update();
}





?>