
<?php 
require_once('../config.php');

if(!empty($_POST['matched_pro'])){
$pid=$_POST['matched_pro'];
$pro=$boj->getQuery("select * from property_listing where id='$pid'");
$properties =$pro[0];
// print_r($properties);
?>
   
<div class="col-sm-12">               
 <?php 
//  if(!empty($properties->property_location)){
     $staff=$boj->getQuery("select * from user where status = 'active' and  realted_area like '%$properties->property_location%'");      
//  }else{
if($staff==''){
      $staff=$boj->getQuery("select * from user where status = 'active' "); 
}
    
//  }
 ?>
  
      <?php
    if($staff){
    foreach($staff as $user){
    ?>
    <option value="<?= $user->id; ?>"> <?= $user->id . '. ' . ucwords($user->username); ?>  (<?= ucwords($user->usertype); ?>)</option>        
    <?php } } 
    
                                                                                    
?>

                                            
</div>          

<?php
}

?>
