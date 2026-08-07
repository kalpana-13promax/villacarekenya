<?php

class template extends db
{
	
	 

	function content(){

    
            $array = array(
                'name' => $_POST['name']??'whatsapp',
                'content' =>$_POST['content'],
                'uploader' => $_POST['uploader']
        );
        
        $update_id=$_GET['id'];
        $where="id=".$update_id;
        
        if(isset($_POST['id'])&&!empty($_POST['id'])){
            $data = $this->update_qry('templates', $array, $where);

        }else{
            $data = $this->insert_query('templates', $array);
        }



        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("Y-m-d h:i:sa");
        $today=  date("Y-m-d h:i:sa");
       
        $act= array(
            'user_id' =>$_POST['user_id'],
            'action' =>" Template ".$_POST['content']." (".$update_id.") Updated",
            'date' =>$today
       
        );
        $data = $this->insert_query('user_actvity', $act);


        $nav = "?nav=templates&id=". $update_id;
        $this->msg_set($data, $nav);


    }
    
    
    
}
	


if(isset($_POST['template_new'])) // Changed to 'template_new' as per the followup instruction
{$obj = new template();
   
    
    $obj->content();
}


 
 