 

<?php

class payment extends db{

function property_payment(){



     
		date_default_timezone_set("Asia/Kolkata");
        $date=date('d-m-y');
        
      
		$uploads_dir2  = '../uploads';
		if(!empty($_FILES["upoad_document"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["upoad_document"]["tmp_name"];
		   
		  
			  $name=$_FILES['upoad_document']['name'];
			  $size=$_FILES['upoad_document']['size'];
			   $type=$_FILES['upoad_document']['type'];
			  $sani=$this->image_check($name,$size,$type);

               
			  $ext= explode(".",$sani);
		  
			  $document =       time().'.'.end($ext);

			   $image=base64_encode(file_get_contents($tmp_name2));
		       
		   
		}else{
			$document = '';
		}
        
		if($_POST['recieved_amount']>0){


		$paid = array(
			'client_id' => $_POST['client_id'],
			'property_id' => $_POST['plot_id'],
			'type' => 'property',
			'booking_id' => $_POST['booking_id'],
			'payment_received'	=>	$_POST['recieved_amount'],
			'mode'	=>	$_POST['payment_method'],
			'payment_type'=>$_POST['payment_type'],
			'bank'=>@$_POST['bank_name'],
			'cheque_date'=>@$_POST['cheque_date'],
			'file_upload' => $document,
			'img'=>@$image,
			'transaction_id' => $_POST['transection_id'],
			'payment_received_date' => $date,
			'due_date' => $_POST['due_date'],
			'reminder_status' =>@$_POST['payment_reminder'],
			'remarks' => $_POST['remarks'],
			'uploader' => $_POST['uploader'],
		);
         
//($paid);
//die;
		if(@$_POST['client_commission']>0){


			$array2=array(
				'client_id'=>$_POST['client_id'],
				'uploader'=>$_POST['uploader'],
				'property_id'=>$_POST['plot_id'],
				'commission'=>$_POST['client_commission'],
				'user_type'=>$_POST['client_user_type'],
				 'user_id'=>$_POST['client_user_id'],
				'property_type'=>'property',
				 
			 );
		
			}
		
			if(@$_POST['property_commission']>0){
		
		
				$array3=array(
					'client_id'=>$_POST['client_id'],
					'uploader'=>$_POST['uploader'],
					'property_id'=>$_POST['plot_id'],
					'commission'=>@$_POST['property_commission'],
					'user_type'=>$_POST['property_user_type'],
					 'user_id'=>$_POST['property_user_id'],
					'property_type'=>'property',
					 
				 );
			
				}
		 

 
//          echo "<pre>";
// 		print_r($paid);
// 		echo "</pre>";

// 		echo "<pre>";
// 		print_r(@$array2);
// 		echo "</pre>";

// 		echo "<pre>";
// 		print_r(@$array3);
// 		echo "</pre>";
// 		die;
		//$where = "id = " . $_POST['plot_id'];;

		$data = $this->insert_query('payments', $paid);

		$pid=$_POST['plot_id'];
		$pro=$this->getQuery("SELECT * from property_listing where id='$pid'");
		$pro_value=$pro[0];
		date_default_timezone_set("Asia/Kolkata");
		$today=  date("Y-m-d h:i:sa");
	   
		$act= array(
			'user_id' =>$_POST['user_id'],
			'action' =>" Property  " .$pro_value->property_title." Payment Updated",
			'date' =>$today
	   
		);
		$activity = $this->insert_query('user_actvity', $act);



				session_start();
				if( $data )
				{
 
if(@$_POST['client_commission']>0 or @$_POST['property_commission']>0){



	if(@$_POST['client_commission']>0){
$data2 = $this->insert_query('commission', $array2);
}

	if(@$_POST['property_commission']>0){
$data3 = $this->insert_query('commission', $array3);
 } 



					if($data2 or $data3){
						$msg = "Commission Added successfully";
					
					$_SESSION['suc'] = 'Payment Added Successfully! ' . $msg;
					
					
				}else{
					$_SESSION['fal'] = 'Payment added but commission not added! ' . $this->mysqli->error;
				}
				 
				 
				 
}else{
    
  	$_SESSION['suc'] = 'Payment Added Successfully! ' ;
					  
    
    
} 
				 
				 
				 
			}else{

        session_start();

		$_SESSION['fal']="Something went wrong "  . $this->mysqli->error;

	
			}
			
		header("location: ?nav=payments&id=". $_POST['plot_id']);
		die;
	
	
		}

}





// 	function image_check($name,$size,$type){
// 		$sanitize=$this->sanitize($name);
// 		if($type=='image/jpg' or $type=='image/jpeg' or $type=='image/png' or $type=='application/pdf'){
// 		if($size<=1000000){

// 			return $sanitize;

// 		}else{

// 			session_start();

// 			$_SESSION['fal']='File must be less than or equal to 1mb';
// 		}

// 	}else{

// 		session_start();

// 		$_SESSION['fal']='File type must be jpg/png/pdf';

// 	}
// 	header("location:?");
// 	die;
// }

function compress($tmp,$path,$type){

switch($type){
	case 'image/jpeg':
		$image=imagecreatefromjpeg($tmp);
		break;
	case 'image/png':
	    $image=imagecreatefrompng($tmp);
		break;
	case 'image/gif':
		$image=imagecreatefromgif($tmp);
		break;
	default:
		$image=imagecreatefromjpeg($tmp);
		 
}
imagejpeg($image,$path,70);
return true;

}


	function agent_payout(){



     
	date_default_timezone_set("Asia/Kolkata");
	
	
  
	 
	if(!empty($_FILES["agent-payout-doc"]["tmp_name"])){

		// uploaded file - sanitize, size max 2mb, compress, jpg, png
		  $tmp	 = $_FILES["agent-payout-doc"]["tmp_name"];
		 
	      $name=$_FILES['agent-payout-doc']['name'];
		 $size=$_FILES['agent-payout-doc']['size'];
		  $type=$_FILES['agent-payout-doc']['type'];
		  $sani=$this->image_check($name,$size,$type);
    
		  $temp2= explode(".",$sani);
		  
		  $document =       time().'.'.end($temp2);
		  //$path="../uploads/".$document;

		   $image=base64_encode(file_get_contents($tmp));
		    
		   //$file='../uploads2/bravo.png';
           //$dimage=base64_decode($image);
		 
		  //echo file_put_contents($file,$dimage);


         // die;
		  
		//   $up="../uploads2/".$document;
		//   echo"<pre>";
        //    print_r(getimagesize($tmp));
		//    echo"</pre>";
		//    $image=imagecreatefromjpeg($tmp);
		//    echo "<pre>";
		//    print_r(imagecreatefromjpeg($tmp));
		//    echo "</pre>";
		//    imagejpeg($image,$up,70);

        //     die;

		 // $compress=compressImage($tmp_name2,$uploadpath,75);
		//  if($type=='application/pdf'){
        //     move_uploaded_file($tmp,$path);
		//  }else{
		//   $this->compress($tmp,$path,$type);
		//  }
		 
		  //move_uploaded_file($tmp_name2, "$uploads_dir2/$document");
	}else{
		$document = '';
	}

	 $pay_comm=$_POST['agent-payout-payment'];
	 $prev_comm=$_POST['prev_comm'];
     
	 $agent_id = $_POST['agent_id'];
	 $property_id = $_POST['property_id'];
	$paid = array(
		'agent_id' => $_POST['agent_id'],
		'property_id' => $_POST['property_id'],
		'type' => 'property',
		'payment'	=>	$_POST['agent-payout-payment'],
		'payment_mode'	=>	$_POST['mode'],
		'bank'=>$_POST['bank_name'],
		'bank_date'=>$_POST['bank_date'],
		'img'=>$image,
		'document' => $document,
		'transaction_id' => $_POST['agent-payout-trans-id'],
		'remarks' => $_POST['agent-payout-remark'],
		'uploader' => $_POST['user']	 
	);
	 
	//$where = "id = " . $_POST['plot_id'];;
	
// 	echo "<pre>";
// 	print_r($paid);
// 	echo "</pre>";
// 	die;
	
	$data = $this->insert_query('agents_payout', $paid);
	
	
    session_start();
	  if($data){
    //      echo "Seccess check";
	//	  die;
    
		$_SESSION['suc'] = 'Paid successfully';

		  
		}else{

			$_SESSION['fal'] = 'something went wrong ' . $this->mysqli->error;
	}
	header("location:?property=$property_id&agents=$agent_id&nav=payments");
	die;


  
}




function update_payout(){



     
	date_default_timezone_set("Asia/Kolkata");
	
	
  
 
	if(!empty($_FILES["update-payout-doc"]["tmp_name"])){

                		// uploaded file - sanitize, size max 2mb, compress, jpg, png
                		  //$tmp_name2 	 = $_FILES["update-payout-doc"]["tmp_name"];
                		  //$all=$_FILES['update-payout-doc'];
                	       //print_r($all);
                		 //  //die;
                		//  $temp2= explode(".", $_FILES["update-payout-doc"]["name"]);
                		 // $document =       time().'.'.end($temp2);
                		//  move_uploaded_file($tmp_name2, "$uploads_dir2/$document");
                		  
                		  
                		  $tmp = $_FILES["update-payout-doc"]["tmp_name"];
                		  
                	      $name=$_FILES['update-payout-doc']['name'];
                		 $size=$_FILES['update-payout-doc']['size'];
                		  $type=$_FILES['update-payout-doc']['type'];
                		  $sani=$this->image_check($name,$size,$type);
                    
                		  $temp2= explode(".",$sani);
                		  
                		  $document =       time().'.'.end($temp2);
                		  //$path="../uploads/".$document;
                
                		   $image=base64_encode(file_get_contents($tmp));
                		   
                		   
                		    $paid = array(
                		'agent_id' => $_POST['agent_id'],
                		'property_id' => $_POST['property_id'],
                		'type' => 'property',
                		'payment'	=>	$_POST['agent-payout-payment'],
                		'payment_mode'	=>	$_POST['mode'],
                	    'document' => $document,
                	    'bank'=>@$_POST['bank_name'],
	                	'bank_date'=>@$_POST['bank_date'],
                	    'img'=>$image,
                		'transaction_id' => $_POST['agent-payout-trans-id'],
                		'remarks' => $_POST['agent-payout-remark'],
                		'uploader' => $_POST['user']	 
                	);
                	
                	
                	
                	
                	
                // 	echo "<pre>";
                // 	print_r($paid);
                // 	echo "</pre>";
                // 	die;
                	
                		    $agent_id = $_POST['agent_id'];
                	 $property_id = $_POST['property_id'];
                	
                		   
                		  $where="id=".$_POST['update_id']; 
                		   
                	$data = $this->update_query('agents_payout',$paid,$where);
                	
                    session_start();
                	  if($data){
                 
                		$_SESSION['suc'] = 'Updated successfully';
                
                		  
                		} else{
                
                			$_SESSION['fal'] = ' NOt updated Something went wrong '.$this->mysqli->error;
                	}
                	header("location:?property=$property_id&agents=$agent_id&nav=payments");
                	die;

		   
	}else{
                    	    
                    	      $paid = array(
                    		'agent_id' => $_POST['agent_id'],
                    		'property_id' => $_POST['property_id'],
                    		'type' => 'property',
                    		'payment'	=>	$_POST['agent-payout-payment'],
                    		'payment_mode'	=>	$_POST['mode'],
                    	    'bank'=>@$_POST['bank_name'],
	                    	'bank_date'=>@$_POST['bank_date'],
                    		'transaction_id' => $_POST['agent-payout-trans-id'],
                    		'remarks' => $_POST['agent-payout-remark'],
                    		'uploader' => $_POST['user']	 
                    	);
                    	 
                    	 
                    	 // 	 $pay_comm=$_POST['agent-payout-payment'];
                    // 	 $prev_comm=$_POST['prev_comm'];
                         
                    	 $agent_id = $_POST['agent_id'];
                    	 $property_id = $_POST['property_id'];
                    	
                    	 	
                // 	echo "<pre>";
                // 	print_r($paid);
                // 	echo "</pre>";
                // 	die;
                	
                    	 
                    	 	 $where="id=".$_POST['update_id'];
                    	//$where = "id = " . $_POST['plot_id'];;
                    	$data = $this->update_query('agents_payout',$paid,$where);
                    	
                        session_start();
                    	  if($data){
                     
                    		$_SESSION['suc'] = 'Updated successfully';
                    
                    		  
                    		} else{
                    
                    			$_SESSION['fal'] = 'Something went wrong'.$this->mysqli->error;
                    	}
                    	header("location:?property=$property_id&agents=$agent_id&nav=payments");
                    	die;
    	}




}



	function employee_payout(){



     
	date_default_timezone_set("Asia/Kolkata");
	
	
  
	 
	if(!empty($_FILES["employee-payout-doc"]["tmp_name"])){

		// uploaded file - sanitize, size max 2mb, compress, jpg, png
		  $tmp	 = $_FILES["employee-payout-doc"]["tmp_name"];
		 
	      $name=$_FILES['employee-payout-doc']['name'];
		 $size=$_FILES['employee-payout-doc']['size'];
		  $type=$_FILES['employee-payout-doc']['type'];
		  $sani=$this->image_check($name,$size,$type);
    
		  $temp2= explode(".",$sani);
		  
		  $document =       time().'.'.end($temp2);
		  //$path="../uploads/".$document;

		   $image=base64_encode(file_get_contents($tmp));
		    
		 

     
	}else{
		$document = '';
		$image="";
	}

	 $pay_comm=$_POST['employee-payout-payment'];
	 $prev_comm=$_POST['prev_comm'];
     
	 $employee_id = $_POST['employee_id'];
	 $property_id = $_POST['property_id'];
	$paid = array(
		'employee_id' => $_POST['employee_id'],
		'property_id' => $_POST['property_id'],
		'type' => 'property',
		'payment'	=>	$_POST['employee-payout-payment'],
		'payment_mode'	=>	$_POST['mode'],
		'bank'=>$_POST['bank_name'],
		'bank_date'=>$_POST['bank_date'],
		'img'=>$image,
		'document' => $document,
		'transaction_id' => $_POST['employee-payout-trans-id'],
		'remarks' => $_POST['employee-payout-remark'],
		'uploader' => $_POST['user']	 
	);
	 
 
 
//  echo "<pre>";
//  print_r($paid);
//  echo "</pre>";
//  die;
	
	$data = $this->insert_query('employee_payout', $paid);
	
	
    session_start();
	  if($data){
   
    
		$_SESSION['suc'] = 'Paid successfully';

		  
		}else{

			$_SESSION['fal'] = 'something went wrong ' . $this->mysqli->error;
	}
	header("location:?property=$property_id&employee=$employee_id&nav=payments");
	die;


  
}




function update_emp_payout(){



     
	date_default_timezone_set("Asia/Kolkata");
	
	
  
 
	if(!empty($_FILES["update-payout-doc"]["tmp_name"])){

                		// uploaded file - sanitize, size max 2mb, compress, jpg, png
                		  //$tmp_name2 	 = $_FILES["update-payout-doc"]["tmp_name"];
                		  //$all=$_FILES['update-payout-doc'];
                	       //print_r($all);
                		 //  //die;
                		//  $temp2= explode(".", $_FILES["update-payout-doc"]["name"]);
                		 // $document =       time().'.'.end($temp2);
                		//  move_uploaded_file($tmp_name2, "$uploads_dir2/$document");
                		  
                		  
                		  $tmp = $_FILES["update-payout-doc"]["tmp_name"];
                		  
                	      $name=$_FILES['update-payout-doc']['name'];
                		 $size=$_FILES['update-payout-doc']['size'];
                		  $type=$_FILES['update-payout-doc']['type'];
                		  $sani=$this->image_check($name,$size,$type);
                    
                		  $temp2= explode(".",$sani);
                		  
                		  $document =       time().'.'.end($temp2);
                		  //$path="../uploads/".$document;
                
                		   $image=base64_encode(file_get_contents($tmp));
                		   
                		   
                		    $paid = array(
                		'employee_id' => $_POST['employee_id'],
                		'property_id' => $_POST['property_id'],
                		'type' => 'property',
                		'payment'	=>	$_POST['employee-payout-payment'],
                		'payment_mode'	=>	$_POST['mode'],
                		'bank'=>@$_POST['bank_name'],
		                 'bank_date'=>@$_POST['bank_date'],
                	    'document' => $document,
                	    'img'=>$image,
                		'transaction_id' => $_POST['employee-payout-trans-id'],
                		'remarks' => $_POST['employee-payout-remark'],
                		'uploader' => $_POST['user']	 
                	);
                	
                	
                	
                // 	echo "<pre>";
                // 	print_r($paid);
                // 	echo "</pre>";
                // 	die;
                	
                	
                	
                		    $employee_id = $_POST['employee_id'];
                	 $property_id = $_POST['property_id'];
                	
                		   
                		  $where="id=".$_POST['update_id']; 
                		   
                	$data = $this->update_query('employee_payout',$paid,$where);
                	
                    session_start();
                	  if($data){
                 
                		$_SESSION['suc'] = 'Updated successfully';
                
                		  
                		} else{
                
                			$_SESSION['fal'] = ' NOt updated Something went wrong '.$this->mysqli->error;
                	}
                	header("location:?property=$property_id&employee=$employee_id&nav=payments");
                	die;

		   
	}else{
                    	    
                    	      $paid = array(
                    		'employee_id' => $_POST['employee_id'],
                    		'property_id' => $_POST['property_id'],
                    		'type' => 'property',
                    		'payment'	=>	$_POST['employee-payout-payment'],
                    		'payment_mode'	=>	$_POST['mode'],
                    	     'bank'=>@$_POST['bank_name'],
		                     'bank_date'=>@$_POST['bank_date'],
                    		'transaction_id' => $_POST['employee-payout-trans-id'],
                    		'remarks' => $_POST['employee-payout-remark'],
                    		'uploader' => $_POST['user']	 
                    	);
                    	 
                    	 
                    	 // 	 $pay_comm=$_POST['agent-payout-payment'];
                    // 	 $prev_comm=$_POST['prev_comm'];
                         
                    	 $employee_id = $_POST['employee_id'];
                    	 $property_id = $_POST['property_id'];
                    	
                    	 	
                // 	echo "<pre>";
                // 	print_r($paid);
                // 	echo "</pre>";
                // 	die;
                	
                    	 
                    	 	 $where="id=".$_POST['update_id'];
                    	//$where = "id = " . $_POST['plot_id'];;
                    	$data = $this->update_query('employee_payout',$paid,$where);
                    	
                        session_start();
                    	  if($data){
                     
                    		$_SESSION['suc'] = 'Updated successfully';
                    
                    		  
                    		} else{
                    
                    			$_SESSION['fal'] = 'Something went wrong'.$this->mysqli->error;
                    	}
                    	header("location:?property=$property_id&employee=$employee_id&nav=payments");
                    	die;
    	}




}









function export_payouts(){

	date_default_timezone_set("Asia/Kolkata");
	$today=  date("Y-m-d h:i:sa"); 
	
	
		$act= array(
			'user_id' =>$_POST['user_id'],
			'action' =>" Payouts  Exported",
			'date' =>$today
	   
		);
		print_r($act);
		$activity = $this->insert_query('user_actvity', $act);


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
	$strSQL = "SELECT * FROM $TableName ";
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
	exit;
		}













}


if(isset($_POST['property-payment'])){
	 
$obj=new payment();
$obj->property_payment();

}

if(isset($_POST['agent-payout'])){
	 
	$obj=new payment();
	$obj->agent_payout();
	
	}

	if(isset($_POST['update-payout'])){
	 
		$obj=new payment();
		$obj->update_payout();
		
		}
		
		
	if(isset($_POST['employee-payout'])){
	 
		$obj=new payment();
		$obj->employee_payout();
		
		}
		
		
			if(isset($_POST['update-employee-payout'])){
	 
		$obj=new payment();
		$obj->update_emp_payout();
		
		}
if(isset($_POST['export-payouts'])){	 
		$obj=new payment();
		$obj->export_payouts();
		
		}
		
		
		
		
		
		
		
		
?>