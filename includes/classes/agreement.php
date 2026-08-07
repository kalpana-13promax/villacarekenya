<?php

class agreement extends db
{
	
	function agreement_add (){
	 
/*         $uploads_dir2  = '../uploads';
		if(!empty($_FILES["upload-document"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["upload-document"]["tmp_name"];
		  	$temp2 		 = explode(".", $_FILES["upload-document"]["name"]);
		  	$name =       time().'.'.end($temp2);
		  	move_uploaded_file($tmp_name2, "$uploads_dir2/$name");
		  
		}else{

			$name = '';
		}

		echo $name;
		die;
*/
		 $doc_name=implode(",",$_POST['doc']);
      
		 $upload_doc  = '../uploads2';
		  $i=0;
		 // $agreementfile=array();

		 if(!empty($_FILES["upload-document"]["tmp_name"])){
			$doc_no=0;
		
		foreach($_FILES["upload-document"]["tmp_name"] as $key=>$tmp_name){
		  $docs=$_POST['doc'];
		  $start_d=$_POST['start_date'];
		  $end_d=$_POST['end_date'];
		    
		  			$i++;
					  //echo $key;
					  //echo $tmp_name;
			 $tmp_name1 	 = $_FILES["upload-document"]["tmp_name"][$key];
			$image=base64_encode(file_get_contents($tmp_name1));
			 
			
			$name=$_FILES['upload-document']['name'][$key];
			$size=$_FILES['upload-document']['size'][$key];
			 $type=$_FILES['upload-document']['type'][$key];
			 $sani=$this->image_check($name,$size,$type);
			 $temp1   = explode(".",$sani);
			  
			$upload_agreement ='agree'.$i.'_'. time().'.'.end($temp1);

			//move_uploaded_file($tmp_name1, "$upload_doc/$upload_agreement");
            //array_push($agreementfile,$upload_agreement);
	    
			$array = array(
				'document_name' => $docs[$doc_no],
				'effective_date'=>$start_d[$doc_no],
				'expiry_date'=>$end_d[$doc_no],
				'property_id' => $_POST['p_id'],
				'file_name' => $upload_agreement,
				'img'=>$image,
				'remarks' => $_POST['p_id'],
				'uploader' => $_POST['uploader']
			);
			//print_r($array);
			//die;
			$data = $this->insert_query('document', $array);
			//echo $this->mysqli->error;
		
		$doc_no++;
	}
		echo $upload_agreement;
		 

		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today=date("Y-m-d");
	
		
	
		$act= array(
			'user_id' =>$_POST['user_id'],
			'action' =>" Agreement Added",
			'date' =>$today
	
		);


		$data = $this->insert_query('user_actvity', $act);
		
	  } 
		 
		//print_r($doc_name);
		//die;
	
	session_start();
                 
        
		if( $data ){
			$_SESSION['suc'] = 'Document Added Successfully';

		}else{
			$_SESSION['fal'] = 'Oops! not insert - '.$this->mysqli->error;

		}
		header("location: ?nav=clients");
		die;
	}
    

    
    


}
	




if(isset($_POST['agreement-add'])){
	$obj = new agreement();
	$obj->agreement_add ();
}



?>