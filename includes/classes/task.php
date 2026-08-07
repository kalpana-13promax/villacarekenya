<?php



class task extends db

{

	

	 



function new_task(){

	

        date_default_timezone_set("Asia/Kolkata");

        $date_time =    date("d/m/Y h:i:sa");

     

          foreach($_POST as $key => $value )

            {
    
               if($key & $value){
    
                $array[$key] = $value; //Thsi array holds all post data now.
    
               }
    
            }  
            $data = $this->insert_query('task', $array);
        

        $notification=array(

			'assing_to' => $_POST['assign_to'],

			'title'=>'Given a New Task',

			'description'=>$_POST['detail'],

			'uploader'=>$_POST['uploader']

             

		);

        // print_r($array);

        // die;

   

    $last_id = $this->mysqli->insert_id;

    $data2= $this->insert_qry('notification', $notification);

    

    

    // activity

    

     date_default_timezone_set("Asia/Kolkata");

    $today=  date("Y-m-d h:i:sa");

   

    $act= array(

        'user_id' =>$_POST['user_id'],

        'action' =>"New Task ".$_POST['detail']." (".$last_id.") Added",

       

   

    );

    $activity = $this->insert_query('user_actvity', $act);

    // activity

    

    if($data){

        if($data2){

            $this->msg_set($data, 'task');

        }

    }

}

function updte_task(){
    
    date_default_timezone_set("Asia/Kolkata");

    $date_time =    date("d/m/Y h:i:sa");

   
       $uploader = $_POST['uploader']; 
        $user_id=$_POST['user_id']; 
        $detail = $_POST['detail'];
        $assign_to =$_POST['assign_to'];  
        $status ='1'; 
        $status_name =Null; 
        $due_date =$_POST['due_date']; 
        $where = "id = " . $_POST['editId'];

        $array['assign_to'] = (int)$assign_to;
        $array['due_date'] = $due_date;
        $array['user_id'] = $user_id;
        $array['status'] = $status;
        $array['status_name'] = $status_name;
        $array['uploader'] = $uploader;
        $array['detail'] = $detail;
       
        // foreach($_POST as $key => $value )

        // {

        //    if($key & $value){

        //     $array[$key] = $value; //Thsi array holds all post data now.

        //    }

        // } 

        $data = $this->update_query('task', $array, $where);
   
    $notification=array(

        'assing_to' => $_POST['assign_to'],

        'title'=>'Given a New Task',

        'description'=>$_POST['detail'],

        'uploader'=>$_POST['uploader']

         

    );

    // print_r($array);

    // die;



$last_id = $this->mysqli->insert_id;

$data2= $this->insert_qry('notification', $notification);





// activity



 date_default_timezone_set("Asia/Kolkata");

$today=  date("Y-m-d h:i:sa");



$act= array(

    'user_id' =>$_POST['user_id'],

    'action' =>"New Task ".$_POST['detail']." (".$last_id.") Added",

   



);

$activity = $this->insert_query('user_actvity', $act);

// activity



if($data){

    if($data2){

        // $this->msg_set($data, 'task');
        header('location:task-new.php');

    }
   
}
}

    function update_task (){

        if($_POST['status']=='0'){

            $title="Task Is Finish";

            $status_name='Finish';

        }else{

            $title="Task Is Rejected"; 

             $status_name='Rejected';

        }

		

        $array = array(

			'status' => $_POST['status'],

			'status_name'=>$status_name

		);

		// print_r($array);

		// die;

        $notification=array(

			'assing_to' => $_POST['assign_to'],

			'title'=>'Task Update',

			'description'=>$title,

			'uploader'=>$_POST['uploader']

             

		);



          

        $where = "id = " . $_POST['edit'] ;

		$data = $this->update_query('task', $array, $where);

        $data2= $this->insert_qry('notification', $notification);

        

        

       //  activity

     date_default_timezone_set("Asia/Kolkata");

    $today=  date("Y-m-d h:i:sa");

   

    $act= array(

        'user_id' =>$_POST['user_id'],

        'action' =>" Task ".$_POST['detail']." (".$_POST['edit'].") Changed",

       

   

    );

    $activity = $this->insert_query('user_actvity', $act);

    // activity

        

        

        

        if($data){

            if($data2){

    $this->msg_set($data, 'task');

            }

        }



	}

	

	

   function accept_task(){

    $title="Task Is Accepted"; 

    $array = array(

        'status' => '0',

        'status_name'=>'Accepted'

    );

    // print_r($array);

    // die;

    $notification=array(

        'assing_to' => $_POST['assign_to'],

        'title'=>'Task Update',

        'description'=>$title,

        'uploader'=>$_POST['uploader']

         

    );

    $where = "id = " . $_POST['edit'] ;

    $data = $this->update_query('task', $array, $where);

    $data2= $this->insert_qry('notification', $notification);

    

    

    

      //  activity

     date_default_timezone_set("Asia/Kolkata");

    $today=  date("Y-m-d h:i:sa");

   

    $act= array(

        'user_id' =>$_POST['user_id'],

        'action' =>" Task Is Accepted",

       

   

    );

    $activity = $this->insert_query('user_actvity', $act);

    // activity

    

    

    

    

    if($data){

        if($data2){

$this->msg_set($data, 'task');

        }

    }



   }








   function export_task() {
    if (isset($_POST["export-task"])) {
        date_default_timezone_set("Asia/Kolkata");
        $today = date("Y-m-d h:i:sa");

        // Log activity
        $act = array(
            'user_id' => $_POST['user_id'],
            'action' => "Task Exported"
        );
        $this->insert_query('user_actvity', $act);

        // Start output buffering
        ob_start();

        // Set headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Task-data.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream
        $output = fopen("php://output", "w");

        // Write CSV header
        fputcsv($output, array('ID', 'Task Name', 'Assign to', 'Status', 'Task Created by', 'Date Time'));

        // Fetch task data
        $query = "SELECT t.id, t.detail AS task_name, u.name AS assign_to, t.status_name, t.uploader, t.timestamp 
                  FROM task t 
                  LEFT JOIN user u ON t.assign_to = u.id";

        $exp = $this->mysqli->query($query);

        if ($exp && $exp->num_rows > 0) {
            while ($row = $exp->fetch_assoc()) {
                fputcsv($output, $row);
            }
        }

        fclose($output);

        // Send all buffered output
        ob_end_flush();

        // Important: exit to prevent further HTML output
        exit;
    }
}





















}

	

 



if(isset($_POST['new-task']))

{

	$obj = new task();

	$obj->new_task();

}


if(isset($_POST['updte-task']))

{

	$obj = new task();

	$obj->updte_task();

}




if(isset($_POST['finish-task']))

{

	$obj = new task();

	$obj->update_task();

}

if(isset($_POST['task-accept']))

{

	$obj = new task();

	$obj->accept_task();

}

if(isset($_POST['export-task']))

{

	$obj = new task();

	$obj->export_task();

}



 

 