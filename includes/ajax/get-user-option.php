
<?php 
require_once('../config.php');

if(!empty($_POST['s_id'])){
    $lid=$_POST['s_id'];
    // echo ("select * from user where status = 'active' and  realted_area like '%$lid%'");
   if($getuserdata->usertype == 'root' || $getuserdata->usertype == 'admin'){ ?>
        
      <div class="col-sm-12">  
              <!-- <label class="col-sm-3 control-label">Assign Property to Staff </label>                              -->
        <select    name="staff[]"  style="width:100%;">
           <?php $staff=$boj->getQuery("select * from user where status = 'active' and  realted_area like '%$lid%'");
          
           
           if($staff){
               foreach($staff as $staffs){
                   ?>
   <option value="<?= $staffs->id; ?>">[<?= $staffs->id ?>]-<?= $staffs->name ?> </option>
                   <?php
               }
           } 
           
          else {
          $s=$boj->getQuery("select * from user where status = 'active' ");
          	foreach($s as $staffs){
          	?>
			<option value="<?= $staffs->id; ?>" >[<?= $staffs->id ?>]-<?= $staffs->name ?> </option>
			<?php
          	}  } ?>

        
            </select>
        <label class="error" for="price">  </label>
          </div>
          <?php }else{

          } ?>
       </div>
       <?php
}
else{
   ?>
   
   <?php
}


?>
