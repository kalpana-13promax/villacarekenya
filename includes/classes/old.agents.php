<?php

class agent extends db
{
	
	function agent_add ()
	{
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		

		$data = $this->mysqli->query("INSERT INTO agent (agent_name, agent_contact, agent_mail, agent_dob, agent_pan, agent_address, agent_city, agent_state, agent_pin, agent_deals_in, updated_by) 
        
        values
        
        ('$_POST[agent_name]', '$_POST[agent_contact]', '$_POST[agent_mail]', '$_POST[dob]', '$_POST[pan]', '$_POST[address]', '$_POST[city]', '$_POST[state]', '$_POST[pin]', '$_POST[deals_in]', '$_POST[user]') "); 
		session_start();
        
        
        
		if( $data )
		{
			$_SESSION['suc'] = 'Agent Added Successfully';
		}
		else
		{
			$_SESSION['fal'] = 'Oops! not insert, Something wrong or Duplicate Record Found!' . $_POST['lead_name'];
		}
			header("location: agent-add.php?nav=agents");
			die;
	}
    
    
    
    
    function agent_edit (){
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
        $agent_id    =   $_POST['agent_id'];
        
        
        
		$data = $this->mysqli->query("Update agent 
				Set agent_name = '$_POST[agent_name]',  
				agent_contact = '$_POST[agent_contact]', 
				agent_mail = '$_POST[agent_mail]', 
				agent_dob = '$_POST[dob]', 
				agent_pan = '$_POST[pan]', 

				agent_address = '$_POST[address]', 
				agent_city = '$_POST[city]', 
				agent_state = '$_POST[state]', 
				agent_pin = '$_POST[pin]', 
				agent_deals_in = '$_POST[deals_in]', 

				updated_by = '$_POST[user]' 

				Where id = $agent_id
			") ;

		session_start();
        
		if( $data ){
			$_SESSION['suc'] = 'Lead Updated!';

		}else{
			$_SESSION['fal'] = 'Oops! not insert, Something wrong.' . mysqli_error();

		}
		header("location: agent-edit.php?edit=$agent_id");
		die;
	}
    
}
	

if(isset($_POST['agent-add']))
{
	$obj = new agent();
	$obj->agent_add ();
}
if(isset($_POST['agent-edit']))
{
	$obj = new agent();
	$obj->agent_edit ();
}

?>