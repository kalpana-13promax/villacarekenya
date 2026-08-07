

<?php

class expenses extends db
{
	
	function add_expenses ()
	{
		
        
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");

        foreach($_POST as $key => $value )
        {
           if($key & $value){
            $array[$key] = $value;
           }
        }  
        // print_r( $array );
        //die;
    
    $data = $this->insert_query('expenses', $array);

    $last_id = $this->mysqli->insert_id;

    date_default_timezone_set("Asia/Kolkata");
    $date_time =    date("Y-m-d h:i:sa");
    $today=  date("Y-m-d h:i:sa");
   
    $act= array(
        'user_id' =>$_POST['user_id'],
        'action' =>" Expenses ".$_POST['name']." (".$last_id.") Added",
       
   
    );
    $data = $this->insert_query('user_actvity', $act);
   
   







    
    $this->msg_set($data, 'expense');
	}


	function update_expenses ()
	{   
        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("d/m/Y h:i:sa");
		
		foreach($_POST as $key => $value )
        {
            if($key & $value){
                $post[$key] = $value; //Thsi array holds all post data now.
                if (array_key_exists('edit', $post)) {
                    // no need of any statement blank for not include edit in array
                }else{
                $array[$key] = $value; //Thsi array holds all post data now.
                }
               }
        }  					
		$where = "id = " . $_POST['edit'] ;
		$data = $this->update_query('expenses', $array, $where);

        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("Y-m-d h:i:sa");
        $today=  date("Y-m-d h:i:sa");
       
        $act= array(
            'user_id' =>$_POST['user_id'],
            'action' =>" Expenses ".$_POST['name']." (".$_POST['edit'].") Updated",
           
       
        );
        $data = $this->insert_query('user_actvity', $act);

	$this->msg_set($data, 'expense');
	}



	function category_add ()
	{
		
        date_default_timezone_set("Asia/Kolkata");
        
        foreach($_POST as $key => $value )
        {
           if($key & $value){
            //echo "Field name : ".$key .", Value : ".$value."<br>";
            $array[$key] = $value; //Thsi array holds all post data now.
           }
        }  
        // print_r( $array );
        //die;
    
    $data = $this->insert_query('expense_category', $array);



    $last_id = $this->mysqli->insert_id;

    date_default_timezone_set("Asia/Kolkata");
    $date_time =    date("Y-m-d h:i:sa");
    $today=  date("Y-m-d h:i:sa");
   
    $act= array(
        'user_id' =>$_POST['user_id'],
        'action' =>" Expenses Category ".$_POST['name']." (".$last_id.") Added",
       
   
    );
    $data = $this->insert_query('user_actvity', $act);
   

    $this->msg_set($data, 'expense');

	}
	function category_update ()
	{
		
        date_default_timezone_set("Asia/Kolkata");
        
        foreach($_POST as $key => $value )
        {
           if($key & $value){
            $post[$key] = $value; //Thsi array holds all post data now.

            if (array_key_exists('edit', $post)) {
                // no need of any statement blank for not include edit in array
            }else{
                //echo "Field name : ".$key .", Value : ".$value."<br>";
            $array[$key] = $value; //Thsi array holds all post data now.
            }
           }
        }  
        // print_r( $array );
        //die;
    
        $where = "id = " . $_POST['edit'] ;
		$data = $this->update_query('expense_category', $array, $where);

        date_default_timezone_set("Asia/Kolkata");
        $date_time =    date("Y-m-d h:i:sa");
        $today=  date("Y-m-d h:i:sa");
       
        $act= array(
            'user_id' =>$_POST['user_id'],
            'action' =>" Expenses Category".$_POST['name']." (".$_POST['edit'].") Updated",
           
       
        );
        $data = $this->insert_query('user_actvity', $act);



    $this->msg_set($data, 'expense');

	}
}
	

if(isset($_POST['category-add']))
{
	$obj = new expenses();
	$obj->category_add ();
}
if(isset($_POST['category-update']))
{
	$obj = new expenses();
	$obj->category_update ();
}
if(isset($_POST['add-expenses']))
{
	$obj = new expenses();
	$obj->add_expenses ();
}
if(isset($_POST['update-expenses']))
{
	$obj = new expenses();
	$obj->update_expenses ();
}

?>