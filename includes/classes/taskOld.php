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

        $notification=array(

			'assing_to' => $_POST['assign_to'],

			'title'=>'Given a New Task',

			'description'=>$_POST['detail'],

			'uploader'=>$_POST['uploader']

             

		);

        // print_r($array);

        // die;

    $data = $this->insert_query('task', $array);

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



    function update_task (){

        if($_POST['status']=='1'){

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

        'status' => '2',

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









    function export_task(){

        if(isset($_POST["export-task"])){

		 date_default_timezone_set("Asia/Kolkata");

            $today=  date("Y-m-d h:i:sa"); 

            

            

            

                $act= array(

                    'user_id' =>$_POST['user_id'],

                    'action' =>" Task  Exported",

                   

               

                );

                $activity = $this->insert_query('user_actvity', $act);

            ob_start();

    

            header('Content-Type: text/csv; charset=utf-8');  

            header('Content-Disposition: attachment; filename=Task-data.csv');

            header('Cache-Control: no-cache');

            //header('Content-Length: '. ob_get_length());

    

            

            $output = fopen("php://output", "w");  

            fputcsv($output, array('ID', 'Task Name', 'Assign to', 'Status', 'Task Created by', 'Date Time'));  

            

 $exp = $this->mysqli->query("SELECT t.id, t.detail,u.name, t.status_name, t.uploader, t.timestamp from task t, user u where t.assign_to = u.id;");  

                            

            while($row = mysqli_fetch_assoc($exp))  

            {  

                 fputcsv($output, $row);  

            }  

            $streamSize = ob_get_length();

            header('Content-Length: '. ob_get_length());

    

            // Flush (send) the output buffer and turn off output buffering

            ob_end_flush();

            fclose($output);  

       }  

    }





















}

	

 



if(isset($_POST['new-task']))

{

	$obj = new task();

	$obj->new_task();

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



 

 