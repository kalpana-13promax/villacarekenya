<?php

class ploting extends db
{
	
	function block_add ()
	{        
        date_default_timezone_set("Asia/Muscat");
        $date_time =    date("d/m/Y h:i:sa");
        
        $array = array(
            'project_id' => $_POST['project_id'],
            'prefix' => $_POST['block_prefix'],
            'block_name' => $_POST['block_name'],
            'area' => $_POST['area'],
			'width' => $_POST['plot_width'],
			'length' => $_POST['plot_length'],
			'default_plot_size' => $_POST['default_plot_size'],
			'unit' => $_POST['unit'],
			'default_price' => $_POST['default_price'],
			'description' => $_POST['description'],
            'uploader' => $_POST['uploader']
		);
		$data = $this->insert_query('block', $array);

		session_start();
		if( $data ){

			$_SESSION['suc'] = $_POST['block_name'] . 'Block/Sector, Added Successfully';
		}
		else{

			$_SESSION['fal'] = ' not insert, Something wrong!';
		}

		header("location: ?nav=ploting");
		die;
	}

	function block_update ()
	{        
        date_default_timezone_set("Asia/Muscat");
        $date_time =    date("d/m/Y h:i:sa");
        
        $array = array(
            'project_id' => $_POST['project_id'],
            'prefix' => $_POST['block_prefix'],
            'block_name' => $_POST['block_name'],
            'area' => $_POST['area'],
			'width' => $_POST['plot_width'],
			'length' => $_POST['plot_length'],
			'default_plot_size' => $_POST['default_plot_size'],
			'unit' => $_POST['unit'],
			'default_price' => $_POST['default_price'],
			'description' => $_POST['description'],
            'uploader' => $_POST['uploader']
		);
		//print_r($array);
		//die;
		$where = "id = " . $_POST['block_id'];
		$data = $this->update_query('block', $array, $where);
		session_start();
		if( $data ){

			$_SESSION['suc'] = $_POST['block_name'] . 'Block/Sector, Added Successfully';
		}
		else{

			$_SESSION['fal'] = ' not insert, Something wrong!';
		}

		header("location: ?nav=ploting");
		die;
	}
	
	
	

	function plot_add ()
	{        
        date_default_timezone_set("Asia/Muscat");
        $date_time =    date("d/m/Y h:i:sa");
		if(isset($_POST['plot_type'])){
        	$plot_type =  implode(",",$_POST['plot_type']);
		}else{
			$plot_type =  '';
		}
        
		if($_POST['end_plot_no'] > 1)
		{
			 $plot_start	=	$this->int($_POST['plot_no']);
			 $plot_end	=	$this->int($_POST['end_plot_no']);
			 $block_id	= $_POST['block_id'];

			 $result = $this->mysqli->query("SELECT * FROM block where id='$block_id'"); 
			
			 while($row = $result->fetch_assoc()) 
			 {
				 $prefix	=	$row["prefix"];
			 }

			for ($i = $plot_start; $i <= $plot_end; $i++)
			{
				echo $plot_no = $prefix. $i;
				$array = array(
					'project_id' => $_POST['project_id'],
					'block_id' => $_POST['block_id'],
					'plot_no' => $plot_no,
					'width' => $_POST['plot_width'],
					'length' => $_POST['plot_length'],
					'plot_area' => $_POST['area'],
					'extra_area' => $_POST['ext_area'],
					'unit' => $_POST['unit'],
					'plot_features' => $plot_type,
					'description' => $_POST['description'],
					'price' => $_POST['price'],
					'status' => '1',
					'uploader' => $_POST['uploader']
				);
				$data = $this->insert_query('plot', $array);

			}
		}else{
			$array = array(
				'project_id' => $_POST['project_id'],
				'block_id' => $_POST['block_id'],
				'plot_no' => $_POST['plot_no'],
				'width' => $_POST['plot_width'],
				'length' => $_POST['plot_length'],
				'plot_area' => $_POST['area'],
				'extra_area' => $_POST['ext_area'],
				'unit' => $_POST['unit'],
				'plot_features' => $plot_type,
				'description' => $_POST['description'],
				'price' => $_POST['price'],
				'status' => '1',
				'uploader' => $_POST['uploader']
			);
			$data = $this->insert_query('plot', $array);
		}

      
		
		session_start();
		if( $data ){
			$_SESSION['suc'] = $_POST['block_name'] . 'Block/Sector, Added Successfully';
		}else{
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}
		header("location: ?nav=ploting&&b=$_POST[block_id]&&p=$_POST[project_id]");
		die;
	}

	function plot_update ()
	{
		date_default_timezone_set("Asia/Muscat");
        $date_time =    date("d/m/Y h:i:sa");

		if(isset($_POST['plot_type'])){
        	$plot_type =  implode(",",$_POST['plot_type']);
		}else{
			$plot_type =  '';
		}

		$array = array(
			'project_id' => $_POST['project_id'],
			'block_id' => $_POST['block_id'],
			'plot_no' => $_POST['plot_no'],
			'width' => $_POST['plot_width'],
			'length' => $_POST['plot_length'],
			'plot_area' => $_POST['area'],
			'extra_area' => $_POST['ext_area'],
			'unit' => $_POST['unit'],
			'plot_features' => $plot_type,
			'description' => $_POST['description'],
			'price' => $_POST['price'],
			'uploader' => $_POST['uploader']
		);
		
		$where = "id = " . $_POST['plot_id'];;
		$data = $this->update_query('plot', $array, $where);
		session_start();
		if( $data ){
			$_SESSION['suc'] = $_POST['block_name'] . 'Plot, Updated Successfully';
		}else{
			$_SESSION['fal'] = ' Updation Failed! Something wrong!';
		}
		header("location: ?nav=ploting&&b=$_POST[block_id]&&p=$_POST[project_id]&edit=$_POST[plot_id]");
		die;
	}




	function plot_type ()
	{

$array = array(
	'field_name' => $_POST['field_name'],
	'uploader' => $_POST['user']
);
$data = $this->insert_query('plot_type', $array);


		session_start();
                
		if( $data )
		{
			$_SESSION['suc'] = 'Plot Type Added Successfully';
		}
		else
		{
			$_SESSION['fal'] = 'Oops! not insert, Something wrong or Duplicate Record Found!';
		}
			header("location: ?nav=ploting");
			die;
    }

	function book_plot()
	{
		$booking_id	= uniqid();

	$array = array(
		'booked_to' => $_POST['client_id'],
		'booking_id' => $booking_id,
		'remarks' => $_POST['remarks'],
		'status' => '2',
		'deal_price'	=>	$_POST['deal_amount'],
		'emi_plan'	=>	$_POST['emi_plan'],
		'booked_by' => $_POST['uploader']
	);
	//print_r($array);
	//die;
	
	$where = "id = " . $_POST['plot_id'];;
	$data = $this->update_query('plot', $array, $where);
	
	
			session_start();
			if( $data )
			{
				$_SESSION['suc'] = ' Booked Successfully with booking id:'. $booking_id;
			}else{
				$_SESSION['fal'] = ' not insert, Something wrong!' . $_POST['lead_name'];
			}
			//header("location: lead-add.php?nav=leads");
				header("location: booking-payments.php?nav=leads&id=". $_POST['plot_id']);
				die;

	}

	function plot_payment(){
		date_default_timezone_set("Asia/Muscat");
        $today =    date("d/m/Y");

		$uploads_dir2  = '../uploads';
		if(!empty($_FILES["upoad_document"]["tmp_name"])){
		  	$tmp_name2 	 = $_FILES["upoad_document"]["tmp_name"];
		  	$temp2 		 = explode(".", $_FILES["upoad_document"]["name"]);
		  	$document =       time().'.'.end($temp2);
		  	move_uploaded_file($tmp_name2, "$uploads_dir2/$document");
		}else{
			$document = '';
		}
        
		$paid = array(
			'client_id' => $_POST['client_id'],
			'property_id' => $_POST['plot_id'],
			'type' => 'ploting',
			'payment_received'	=>	$_POST['recieved_amount'],
			'mode'	=>	$_POST['payment_method'],
			'file_upload' => $document,
			'transaction_id' => $_POST['transection_id'],
			'payment_received_date' => $today,
			'due_date' => $_POST['due_date'],
			'reminder_status' => $_POST['payment_reminder'],
			'remarks' => $_POST['remarks'],
			'uploader' => $_POST['uploader'],
		);		

		//$where = "id = " . $_POST['plot_id'];;
		$data = $this->insert_query('payments', $paid);
		
				session_start();
				if( $data )
				{
					$emp_id	=	$_POST['employee_id'];
					$emp_com	=	$_POST['emp_com'];
			
					$i=0;
					foreach($emp_id as $empid)
					{
						$commission =  $emp_com[$i];
						 $data1 = $this->mysqli->query("INSERT INTO commission ( user_id, user_type, commission, property_id, property_type, client_id, uploader) 
						values 
						('$empid', 'employee', '$commission', '$_POST[plot_id]', 'ploting', '$_POST[client_id]','$_POST[uploader]') "); 
							$i++;
					}
					
					
					
					$agent_id	=	$_POST['agent_id'];
					$agent_com	=	$_POST['agent_com'];
			
					$j=0;
					foreach($agent_id as $agentid)
					{
						$commission =  $agent_com[$j];
						 $data2 = $this->mysqli->query("INSERT INTO commission ( user_id, user_type, commission, property_id, property_type, client_id, uploader) 
						values 
						('$agentid', 'agent', '$commission', '$_POST[plot_id]', 'ploting', '$_POST[client_id]','$_POST[uploader]') "); 
							$j++;
					}
					if($data1)
					{
$msg1	=	"Employee";
					}
					if ($data2)
					{
$msg2	=	"Agent";
					}
					if($data1 || $data2){
						$msg = "Commission Added to " . $msg1 . " ". $msg2 ;
					}
					$_SESSION['suc'] = 'Payment Added Successfully! ' . $msg;
				}else{
					$_SESSION['fal'] = 'Payment updation failed!';
				}
				//header("location: lead-add.php?nav=leads");
					header("location: ?nav=payments&id=". $_POST['plot_id']);
					die;
	}

	
	
}
	

if(isset($_POST['block-add']))
{
	$obj = new ploting();
	$obj->block_add ();
}
if(isset($_POST['block-update']))
{
	$obj = new ploting();
	$obj->block_update ();
}
if(isset($_POST['plot-add']))
{
	$obj = new ploting();
	$obj->plot_add ();
}
if(isset($_POST['plot-update']))
{
	$obj = new ploting();
	$obj->plot_update ();
}
if(isset($_POST['plot-type-add']))
{
	$obj = new ploting();
	$obj->plot_type ();
}
if(isset($_POST['book-plot']))
{
	$obj = new ploting();
	$obj->book_plot ();
}
if(isset($_POST['plot-payment']))
{
	$obj = new ploting();
	$obj->plot_payment();
}
?>