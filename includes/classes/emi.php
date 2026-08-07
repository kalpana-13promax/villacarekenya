<?php

class emi extends db
{
	
	function emi_plan_new ()
	{
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");


	$array = array(
			'plan_name' => $_POST['plan_name'],
			'down_payment' => $_POST['dp'],
			'duration' => $_POST['duration'],
			'interest' => $_POST['interest'],
			'late_fee' => $_POST['late_fee'],
			'ci' => $_POST['ci'],
			'remarks' => $_POST['remarks'],
			'uploader' => $_POST['uploader'],
			'status' => $_POST['status']
		);
		//print_r($array);
		//die;
		 $data = $this->insert_query('emi_plan', $array);
		session_start();
		if( $data )
		{
			$_SESSION['suc'] = 'EMI Plan Added Successfully';
		}
		else
		{
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}
		
			header("location: ?nav=ploting");
		
			die;
	}
	function emi_plan_update ()
	{
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
							
		$array = array(
			'plan_name' => $_POST['plan_name'],
			'down_payment' => $_POST['dp'],
			'duration' => $_POST['duration'],
			'interest' => $_POST['interest'],
			'late_fee' => $_POST['late_fee'],
			'ci' => $_POST['ci'],
			'remarks' => $_POST['remarks'],
			'uploader' => $_POST['uploader'],
			'status' => $_POST['status']
		);
		//print_r($array);
		//die;
		$where = "id = " . $_POST['edit_id'] ;
		$data = $this->update_query('emi_plan', $array, $where);

		session_start();
		if( $data )
		{
			$_SESSION['suc'] = 'Updated Successfully';
		}
		else
		{
			$_SESSION['fal'] = ' Something went wrong!';
		}
		
			header("location: ?nav=ploting");
			die;
	}



	function down_payment ()
	{
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
        $today =    date("d/m/Y");
		$emi_month = $_POST['emi_start_month'];
		$emi_date = $_POST['emi_date'];
		$monthly_date = $emi_month .'-'. $emi_date;

		$paid = array(
			'client_id' => $_POST['client_id'],
			'property_id' => $_POST['plot_id'],
			'type' => 'ploting',
			'payment_received'	=>	$_POST['down_payment'],
			'mode'	=>	$_POST['payment_method'],
			'transaction_id' => $_POST['transection_id'],
			'payment_received_date' => $today,
			'due_date' => $monthly_date,
			'reminder_status' => '1',
//			'remarks' => $_POST['remarks'],
			'uploader' => $_POST['uploader']
		);	
		$payment_plan = array(
			'client_id' => $_POST['client_id'],
			'property_id' => $_POST['plot_id'],
			'property_type' => 'ploting',
			'down_payment'	=>	$_POST['down_payment'],
			'balance' => $_POST['balance'],
			'balance_with_interest' => $_POST['balance_with_interest'],
			'monthly_emi' => $_POST['monthly_emi'],
			'emi_start_date	' => $monthly_date,
			'monthly_date' => $emi_date,

			'uploader' => $_POST['uploader']
		);		
//print_r($paid);
//echo "<br />";
//print_r($payment_plan);
//die;

		//$where = "id = " . $_POST['plot_id'];;
		$data = $this->insert_query('payments', $paid);
		$data1 = $this->insert_query('payment_plan', $payment_plan);

		session_start();
		if( $data & $data1)
		{
			$_SESSION['suc'] = 'Down Payment Added Successfully';
		}
		else
		{
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}
		
			header("location: ?nav=ploting&id=". $_POST['plot_id']);
		
			die;
	}
}
	

if(isset($_POST['emi-plan-add']))
{
	$obj = new emi();
	$obj->emi_plan_new ();
}
if(isset($_POST['emi-plan-update']))
{
	$obj = new emi();
	$obj->emi_plan_update ();
}
if(isset($_POST['down-payment']))
{
	$obj = new emi();
	$obj->down_payment ();
}
?>