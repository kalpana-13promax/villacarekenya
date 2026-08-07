 <?php

class to_do extends db
{
	
	 

	function add_to_do(){

		date_default_timezone_set("asia/kolkata");

		$today=date("Y-m-d");

		if($_POST['date']==""){
         
          $date=$today;
		}else{

			$date=$_POST['date'];
		}
       
  $array=array(

    'to_do'=>$_POST['to-do'],

    'date'=>$date,

	'uploader_id'=>$_POST['uploader']


  );


//   echo "<pre>";
//   print_r($array);
//   echo "</pre>";
//   die;

		$data=$this->insert_query('to_do',$array);

		session_start();

		if($data){
		

			$_SESSION['suc']="Added Successfully";
		}else{

			$_SESSION['fal']="something went wrong".$this->mysqli_error;
		}
		header("location:?#to-do");
		die;
			
	}

}
	
 

if(isset($_POST['to-do-btn']))
{
	$obj = new to_do();
	$obj->add_to_do();
}

 
 