<?php

class color extends db
{
	
	function lead_color ()
	{
	      $lead_id    =   $_POST['lead_id'];
	      $color = $_POST['mark-color'];
        
		$array = array(
			'mark_color' => $color
		);
		//echo $lead_id . '<br />';
		//print_r($array);
		//die;
		$where = "id = " . $lead_id;
		$data = $this->update_query('leads', $array, $where);

date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today=date("Y-m-d");
	
   
	
		$act= array(
			'user_id' =>$_POST['user_id'],
			'action' =>" Mark Color ".$color." (".$lead_id.") Updated",
			'date' =>$today
	
		);
		$data = $this->insert_query('user_actvity', $act);
		$this->msg_set($data, 'leads');
	}

	
	
	
	function color_reset(){
	        $lead_id    =   $_POST['lead_id'];
        $blank = '';
		$array = array(
			'mark_color' => $blank
		);
		//echo $lead_id . '<br />';
		//print_r($array);
		//die;
				$where = "id = " . $lead_id;
		$data = $this->update_query('leads', $array, $where);
		
		
		date_default_timezone_set("Asia/Kolkata");
		$date_time =    date("Y-m-d h:i:sa");
		$today=date("Y-m-d");
	

  $l=$this->getQuery("SELECT * from leads where id='$lead_id'") ;
  $lv=$l[0];
	
		$act= array(
			'user_id' =>$_POST['user_id'],
			'action' =>" Mark Color of lead ".$lv->lead_name." Reset",
			'date' =>$today
	
		);
		$data = $this->insert_query('user_actvity', $act);
		
		
		
		
		$this->msg_set($data, 'leads'); 
	}
	
}

if(isset($_POST['mark-color']))
{
	$obj = new color();
	$obj->lead_color ();
}

if(isset($_POST['color-reset']))
{
	$obj = new color();
	$obj->color_reset ();
}