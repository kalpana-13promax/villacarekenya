<?php
require_once('../config.php');


if (isset($_POST['unit_id'])) {
    $unit = $_POST['unit_id'];
    $city = $_POST['city_id'];
    $state = $_POST['state_id'];
    $subloc = $_POST['subloc_id'];
    $q = '';

    if(!empty($unit)){
        $cityqry = $boj->getQuery("SELECT * FROM city where id =$city"); 
        $stateqry = $boj->getQuery("SELECT * FROM locations where id ='$state' and city =$city"); 
        $subLocation = $boj->getQuery("SELECT * FROM sub_location where city ='$city' AND location='$state'"); 
  
        if(!empty($subloc)){
        
            $states = $boj->getQuery("SELECT unit_no FROM property_listing WHERE unit_no = $unit AND city = '".$cityqry[0]->city."' AND property_location= '".$stateqry[0]->location."' AND sub_location ='".$subLocation[0]->sub_location."'"); 
         
        }else{

            $states = $boj->getQuery("SELECT unit_no FROM property_listing WHERE unit_no = $unit AND city =  '".$cityqry[0]->city."' AND property_location= '".$stateqry[0]->location."'"); 
           
        }
 
        if($states){
            echo '<span style="color:red">This unit no already exist.</span>';
        }else{
            echo '';
        }
    }
   
}
?>

