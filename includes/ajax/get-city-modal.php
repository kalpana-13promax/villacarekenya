<?php 
require_once('../config.php');

    
if(!empty($_POST["id"])){ 
$id=$_POST['id'];



$query = $boj->getQuery("SELECT * FROM locations WHERE city = '$id' ORDER BY id ASC");  
     
    if($query){ 
        echo '<option value="">--Select Location--</option>'; 
        foreach($query as $value){
            echo '<option value="'.$value->id.'">'.$value->location.'</option>'; 
        } 
    }else{ 
        echo '<option value="">Location not available</option>'; 
    } 
}
elseif(!empty($_POST["state_id"])){ 
    $location_id=$_POST['state_id'];
    //echo $location_id;
 
$qry =  $boj->getQuery("SELECT * FROM sub_location WHERE location = '$location_id' ORDER BY id ASC"); 
   
       if($qry){ 
        echo '<option value="">--Select Sub Location--</option>'; 
        foreach($qry as $val) {  
            echo '<option value="'.$val->sub_location.'">'.$val->sub_location.'</option>'; 
        } 
    }else{ 
        echo '<option value="">Sub Location Not Available</option>'; 
    } 
}


?>
