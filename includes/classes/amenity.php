<?php

class amenity extends db
{
	
	function amenity_add ()
	{
	    date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
$array = array(
    'amenity_name'  =>      $_POST['amenity'],
    'created_by'    =>      $_POST['user']
    );
    
$data = $this->insert_query('amenity', $array);
		
		session_start();
		if( $data )
		{
			$_SESSION['suc'] = $_POST['amenity'] . ' Added Successfully';
		}
		else
		{
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
			header("location: ?nav=masters");
			die;
	}

	
	function amenity_update()
	{
	    date_default_timezone_set("Asia/Muscat");
        $date_time =    date("d/m/Y h:i:sa");
		
$array = array(
    'amenity_name'  =>      $_POST['amenity'],
    'created_by'    =>      $_POST['user']
    );

	$update_id=$_POST['update_id'];
	$where="id=".$update_id;
    
$data = $this->update_query('amenity',$array,$where);
		
		session_start();
		if( $data )
		{
			$_SESSION['suc'] = $_POST['amenity'] . ' Updated Successfully';
		}
		else
		{
			$_SESSION['fal'] = ' not updated, Something wrong! ' . $this->mysqli->error;
		}
			header("location: ?nav=masters");
			die;
	}



}
	

if(isset($_POST['amenity-add']))
{
	$obj = new amenity();
	$obj->amenity_add ();
}

if(isset($_POST['amenity-update']))
{
	$obj = new amenity();
	$obj->amenity_update ();
}

?>