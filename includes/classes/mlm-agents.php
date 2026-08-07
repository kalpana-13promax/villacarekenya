<?php

class mlm_agent extends db
{
	
	function mlm_agent_add ()
	{
		
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		

		//$data = $this->mysqli->query("INSERT INTO mlm (agent_name, contact, mail, dob, pan, address, city, //state, pin) 
        
        //values
        echo $_POST[agent_name];
        die;
        // ('$_POST[agent_name]', '$_POST[agent_contact]', '$_POST[agent_mail]', '$_POST[dob]', '$_POST[pan]', '$_POST[address]', '$_POST[city]', '$_POST[state]', '$_POST[pin]'); 
            // "); 
		session_start();
        
        
        
		if( $data )
		{
			$_SESSION['suc'] = 'Agent Added Successfully';
		}
		else
		{
			$_SESSION['fal'] = 'Oops! not insert, Something wrong or Duplicate Record Found! MLM Add' . $_POST['lead_name'];
		}
			header("location: mlm-agent-add.php?nav=agents");
			die;
	}
    
    
    
    
    function mlml_agent_edit ()
	{
		
		
        
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
        
        
        
		if( $data )
		{
			$_SESSION['suc'] = 'Lead Updated!';
		}
		else
		{
			$_SESSION['fal'] = 'Oops! not insert, Something wrong.' . mysqli_error();
		}
			header("location: agent-edit.php?edit=$agent_id");
			die;
	}
    
}
	

if(isset($_POST['mlm-agent-add']))
{
	$obj = new mlm_agent();
	$obj->mlm_agent_add ();
}
if(isset($_POST['mlm-agent-edit']))
{
	$obj = new mlm_agent();
	$obj->mlm_agent_edit ();
}

?>