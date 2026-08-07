<?php

class advertisement extends db
{
	
	function advertisement_add ()
	{
		$uploads_dir2  = '../uploads/adv';
		if(!empty($_FILES["adv"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["adv"]["tmp_name"];
		  	$temp2 		 = explode(".", $_FILES["adv"]["name"]);
		  	$name =       time().'.'.end($temp2);
		  	move_uploaded_file($tmp_name2, "$uploads_dir2/$name");
		  
		}else{

			$name = '';
		}
        
        date_default_timezone_set("Asia/Muscat");
        $date_time =    date("Y-m-d h:i:sa");
		
		$data = $this->mysqli->query("INSERT INTO advertisement (name, location, advimage, link, startdate, enddate, remarks, uploadedby, upload_datetime) 
        values
        
        ('$_POST[adv_name]', '$_POST[ad_location]', '$name', '$_POST[url_link]', '$_POST[start_from]',  '$_POST[end_time]', '$_POST[adv_description]','$_POST[uploader]','$_POST[upload_time]')
			"); 
		session_start();
		if( $data )
		{
			$_SESSION['suc'] = 'Advertisement Added Successfully';
		}
		else
		{
			$_SESSION['fal'] = ' Not Added, Something wrong!' . $this->mysqli->error;
		}
			header("location: ad-new.php?nav=advertisment");
			die;
	}








function ad_update()
	{
		$uploads_dir2  = '../uploads/adv';
		if(!empty($_FILES["adv"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["adv"]["tmp_name"];
		  	$temp2 		 = explode(".", $_FILES["adv"]["name"]);
		  	$name =       time().'.'.end($temp2);
		  	move_uploaded_file($tmp_name2, "$uploads_dir2/$name");
		  
		}else{

			$name = '';
		}
        
        date_default_timezone_set("Asia/Muscat");
        $date_time =    date("Y-m-d h:i:sa");
		$update_id=$_POST['update_id'];
		// echo $update_id;
		// die;

// 		echo "update advertisement set name='$_POST[adv_name]', location='$_POST[ad_location]', advimage='$name', link='$_POST[url_link]', startdate='$_POST[start_from]', enddate='$_POST[end_time]', remarks='$_POST[adv_description]', uploadedby='$_POST[uploader]', upload_datetime='$_POST[upload_time]' where id='$update_id'" ;

//  die;
		$data = $this->mysqli->query("update advertisement set name='$_POST[adv_name]', location='$_POST[ad_location]', advimage='$name', link='$_POST[url_link]', startdate='$_POST[start_from]', enddate='$_POST[end_time]', remarks='$_POST[adv_description]', uploadedby='$_POST[uploader]', upload_datetime='$_POST[upload_time]' where id='$update_id'");
       
		session_start();
		if( $data )
		{
			$_SESSION['suc'] = 'Advertisement Updated Successfully';
		}
		else
		{
			$_SESSION['fal'] = ' Not Updated, Something wrong!' . $this->mysqli_error;
		}
			header("location: ad-update.php?update_id=$update_id&&nav=advertisment");
			die;
	}



}
	

if(isset($_POST['advertisement-add']))
{
	$obj = new advertisement();
	$obj->advertisement_add ();
}



if(isset($_POST['ad-update']))
{
	$obj = new advertisement();
	$obj->ad_update();
}

?>