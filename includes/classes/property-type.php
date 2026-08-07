<?php

class property_type extends db
{
	
	function property_type_add ()
	{

		// echo '<pre>';
		// print_r($_POST);
		// echo '</pre>';
		// die;

		// $file_name = explode ('.' , $_FILES['browsers']['name']);
		// $file_tmp = $_FILES['browsers']['tmp_name'];
		// $random = substr(number_format(time() * rand(),0,'',''),0,4);
		// $name = $random. '.' .end($file_name);
		// move_uploaded_file( $file_tmp,  "uploads/".$name );
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
        

		// $fields=$_POST['fields'];
		// //echo $_POST['fields'];
		//  echo  implode(",",$fields);
		// //  echo $imp;
		// //echo "try";
		// die;
		if(isset($_POST['fields'])){
			$field=$_POST['fields'];

        	$fields =  implode(",",$field);
		}else{
			$fields =  '';
		}
       

		// echo $_POST['category']."<br>";

		// echo $_POST['property_type']."<br>";
		// echo $_POST['user']."<br>";
		
if($_POST['category'] == 'main' OR $_POST['category'] == 'status' OR $fields){

$category=$this->sanitize($_POST['category']);
$p_type=$this->sanitize($_POST['property_type']);
$user=$this->sanitize($_POST['user']);

$array = array(
	'category' => $category,
	'type' => $p_type,
	'created_by' => $user,
	'fields' => $fields
);

$data = $this->insert_query('property_type',$array);
$this->msg_set($data, 'masters');
	}else{
	    session_start();
    $_SESSION['fal'] = 'Please select fields or create fields from Masters - Add Fields!';
}
	}

	
	
	
	function property_type_update ()
	{

        if(isset($_POST['fields'])){
			
            $fields =  implode(",",$_POST['fields']);
        }else{
            $fields = 'NULL';
        }

		echo $fields."<br>";
        $pid =  $_POST['id'];
      $category = $this->sanitize($_POST['category']);
	    	$data = $this->mysqli->query("Update property_type 
			Set type = '$category', 
			fields = '$fields', 
			
			created_by = '$_POST[user]' 

			Where id = $pid
		") ;

		session_start();
               
        
		if( $data ){
			$_SESSION['suc'] = ' Updated!';

		}else{
			$_SESSION['fal'] = ' Something wrong ' . $this->mysqli->error;
			
		}
			header("location: ?nav=master");
			die;
	    
	}
	
	
}
	

if(isset($_POST['property-type-add']))
{
	$obj = new property_type();
	$obj->property_type_add ();
}
if(isset($_POST['property-type-update']))
{
	$obj = new property_type();
	$obj->property_type_update ();
}

?>