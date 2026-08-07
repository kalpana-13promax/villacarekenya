<?php 


   require_once('../includes/config.php');
   $boj->check_session();
   $perm = $check->check_permission('staff', 'create');
$view_all = $check->check_permission('staff', 'view_all');
$edit = $check->check_permission('staff', 'edit');
$delete = $check->check_permission('staff', 'delete');
$view_own = $check->check_permission('staff', 'view_own');
   include('../header.php');
?>
<style>
.contact-info {
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 36px;
}
.contact-info h4 {
    color: #0088cc;
    margin-bottom: 23px;
    width: auto;
}
</style>
<div class="inner-wrapper">
<?php include('../slider.php'); ?>
<section role="main" class="content-body">
   <header class="page-header">
      <h2>EMPLOYEE'S</h2>
      <!--change page here -->
      <div class="right-wrapper pull-right">
         <ol class="breadcrumbs">
            <li>
               <a href="dashboard.php">
               <i class="fa fa-home"></i>
               </a>
            </li>
         </ol>
         <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
      </div>
   </header>
   <!--page start here-->
   
   <div id="alert">
   <?php $boj->message(); ?>
   <?php
   if(@$perm=='true'){
   ?>
   </div>
   
   
   <?php
   
   if(isset($_GET['name'])){
      
$name = $_GET['name'];
 
		 
 

$contact = $_GET['contact']; 

$mail = $_GET['mail']; 
	$dob = $_GET['dob']; 
	
$aadhar = $_GET['aadhaar'];
$pan = $_GET['pan'];
$dl = $_GET['dl'];
$passport = $_GET['passport'];
$address = $_GET['address'];

$city = $_GET['city'];

$state = $_GET['state'];
$pin = $_GET['pin'];
$deals_in = $_GET['deals_in'];
 
			 
		
       
       
   }else{
       
       
// $name ="";
 
		 
 

// $contact = ""; 

// $mail = $_POST['mail']; 
// 	$dob = $_POST['dob']; 
	
// $aadhar = $_POST['aadhaar'];
// $pan = $_POST['pan'];
// $dl = $_POST['dl'];
// $passport = $_POST['passport'];
// $address = $_POST['address'];

// $city = $_POST['city'];

// $state = $_POST['state'];
// $pin = $_POST['pin_code'];
// $deals_in = $_POST['deals_in'];
 
			 
		
   }
   
   
   ?>
   <div class="col-md-12">
      <form id="selects-form" action="" method="post" enctype="multipart/form-data">
         <section class="panel">
            <header class="panel-heading">
               <div class="panel-actions">
                  <a href="#" class="fa fa-caret-down"></a>
                  <a href="#" class="fa fa-times"></a>
               </div>
               <h2 class="panel-title">Add Employee / Admin</h2>
               <!--<p class="panel-subtitle">
                  Easily validate select tags, does not matter if is single or multiple.
                  </p>-->
            </header>
            <div class="panel-body">
            	<div class="contact-info">	
            		<h4>Basic Info</h4>
            	  <div class="form-group">
                     <label class="col-sm-2 control-label">Admin/Employee Name*</label>
                     <div class="col-sm-4">
                        <input type="text" value="<?php echo @$name; ?>" name="name" class="form-control" required/>
                        <label class="error" for="name"></label>
                     </div>
                     <label class="col-sm-2 control-label">Contact*</label>
                     <div class="col-sm-4">
                        <input type="number" value="<?php echo @$contact; ?>" name="contact" class="form-control" required/>
                        <label class="error" for="contact"></label>
                     </div>
                     <label class="col-sm-2 control-label">E-mail*</label>
                     <div class="col-sm-4">
                        <input type="email" value="<?php echo @$mail; ?>" name="mail" class="form-control" required/>
                        <label class="error" for="mail"></label>
                     </div>
                     <label class="col-sm-2 control-label">DOB</label>
                     <div class="col-sm-4">
                        <input type="date"  value="<?php echo @$dob; ?>" name="dob" class="form-control" />
                        <label class="error" for="dob"></label>
                     </div>

                     <label class="col-sm-2 control-label">Address</label>
                     <div class="col-sm-4">
                        <textarea name="address" class="form-control"><?php echo @$address; ?></textarea>
                        <label class="error" for="address"></label>
                     </div>
                     <label class="col-sm-2 control-label">City</label>
                     <div class="col-sm-4">
                        <input type="text"  value="<?php echo @$city; ?>" name="city" class="form-control" />
                        <label class="error" for="city"></label>
                     </div>
                  
                     <label class="col-sm-2 control-label">State</label>
                     <div class="col-sm-4">
                        <input type="text" value="<?php echo @$state; ?>" name="state" class="form-control" />
                        <label class="error" for="state"></label>
                     </div>
                     <label class="col-sm-2 control-label">PIN Code</label>
                     <div class="col-sm-4">
                        <input type="text" value="<?php echo @$pin; ?>"  name="pin_code" class="form-control" />
                        <label class="error" for="pin_code"></label>
                     </div>
                  
                     <label class="col-sm-2 control-label">Country</label>
                     <div class="col-sm-4">
                        <input type="text" value="India" name="country" class="form-control" />
                        <label class="error" for="India"></label>
                     </div>
                     <label class="col-sm-2 control-label">Deals In</label>
                     <div class="col-sm-4">
                        <input type="text" value="<?php echo @$deals_in; ?>"  name="deals_in" class="form-control" />
                        <label class="error" for="deals_in"></label>
                     </div>
                  </div>
                </div>

                <div class="contact-info">	
            		<h4>KYC Details</h4>

                  <div class="form-group">
                  	 <div class="col-sm-6" style="border-right:1px solid #ccc;">
                  	 	<label class="col-sm-6 control-label" style="padding-left: 0;"><b>Select atleast one</b></label>
                  	 	<div class="col-sm-6">
                  	 		<input type="checkbox" class="form-checkbox" id="aadhar_card" name="aadhar_card" value="aadhar" required> <label for="aadhar_card"> Aadhaar Card </label>
                  	 		<br>
                  	 		<input type="checkbox" class="form-checkbox" id="pan_card" name="pan_card" value="pancard" > <label for="pan_card"> Pan Card </label>

                  	 		<br>
                  	 		<input type="checkbox" class="form-checkbox" id="driving_licence" name="driving_licence" value="drivinglicence"> <label id="label_input_6_0" for="driving_licence"> Driving Licence </label>

                  	 		<br>
                  	 		<input type="checkbox" class="form-checkbox" id="passport" name="passport" value="passport">
                  	 		 <label for="passport"> Passport </label>
                  	 	</div>
                  	 	
                  	 </div>

                  	 <div class="col-sm-6" align="center">
                  	 	<div class="aadhar-card" style="display:none">
              	 		     <div class="col-sm-6">
		                        <input type="text"  name="aadhaar" class="form-control" placeholder="Aadhar Number"  required>
		                        <label class="error" for="aadhaar"></label>
		                     </div>
		                     <div class="col-sm-6">
		                        <input type="file" name="uploaded_aadhaar" class="form-control" >
		                        <label class="error" for="uploaded_aadhaar"></label>
		                     </div>
                  	 	</div>


                  	 	<div class="pan-card" style="display:none">
                  	 		 <div class="col-sm-6">
		                        <input type="text" name="pan" class="form-control" placeholder="Pan Number">
		                        <label class="error" for="pan"></label>
		                     </div>
		                     <div class="col-sm-6">
		                        <input type="file" name="uploaded_pan" class="form-control">
		                        <label class="error" for="uploaded_pan"></label>
		                     </div>
                  	 	</div>

                  	 	<div class="driving-licence" style="display:none">
              	 			<div class="col-sm-6">
		                        <input type="text" name="dl" class="form-control" placeholder="DL Number">
		                        <label class="error" for="dl"></label>
		                     </div>
		                     <div class="col-sm-6">
		                        <input type="file" name="uploaded_dl" class="form-control">
		                        <label class="error" for="uploaded_dl"></label>
		                     </div>
                  	 	</div>

                  	 	<div class="passport" style="display:none">
                  	 		<div class="col-sm-6">
		                        <input type="text" name="passport" class="form-control" placeholder="Passport Number">
		                        <label class="error" for="passport"></label>
		                     </div>
		                     <div class="col-sm-6">
		                        <input type="file" name="uploaded_passport" class="form-control">
		                        <label class="error" for="uploaded_passport"></label>
		                     </div>
                  	 	</div>


                         <span class="format" style="font-size:12px; margin-top:2px; display:none;"><i>Note- Only jpg/jpeg/png files  are accepted and file size must be less than or equal to 1MB.</i></span>
                  	 </div>
                   
                  </div>
              </div>
                <div class="contact-info">	
            		
                  <h4>Account Details</h4>
                  
                  <div class="form-group">
                     <label class="col-sm-2 control-label">Username*</label>
                     <div class="col-sm-4">
                        <input type="text"  name="username" class="form-control" required/>
                        <label class="error" for="username"></label>
                     </div>

                     <label class="col-sm-2 control-label">Password*</label>
                     <div class="col-sm-4">
                        <input type="password" name="password" class="form-control" required/>
                        <label class="error" for="password"></label>
                     </div>
                  
                     <label class="col-sm-2 control-label">Set Status</label>
                     <div class="col-sm-4">
                        <select name="status">
                           <option value="active">Active</option>
                           <option value="active">Block</option>
                        </select>
                        <label class="error" for="status"></label>
                     </div>

                     <label class="col-sm-2 control-label">A/c Type*</label>
                     <div class="col-sm-4">
                        <select name="usertype" required>
                           <option value=""> -- SELECT -- </option>
                          <?php $roles = $boj->get('roles'); 
                          foreach($roles as $role){
                           echo '<option value="'.$role->id.'">'.$role->name.'</option>';
                          }?>
                        </select>
                        <label class="error" for="usertype"></label>
                     </div>
                     

                     <label class="col-sm-2 control-label">Remarks</label>
                     <div class="col-sm-10">
                        <textarea name="remarks" class="form-control" /></textarea>
                        <label class="error" for="remarks"></label>
                      </div>
                     
                  </div>
                  <?php       $uploader =  $getuserdata->username; ?>
                  <input type="hidden" name="uploader" value="<?php echo $uploader; ?>" >
                  <?php       $user_id =  $getuserdata->id; ?>
                  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" >
               </div>
               <footer class="panel-footer">
                  <div class="row">
                  	 
                     <div class="col-sm-4">
                        <button name="admin-add" class="btn btn-primary">Submit</button>
                     </div>
                  </div>
               </footer>
         </section>
      </form>
      </div>
      <?php 
}else{
$boj->no_access();
} 
?>
      <!--page end here-->			
</section>
</div>
<?php include('../footer.php'); ?>
<!-- Specific Page Vendor -->
<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>
<!-- Examples -->
<script src="assets/javascripts/forms/examples.validation.js"></script>

<script type="text/javascript">
	$(document).on('change', '#aadhar_card', function() {
	    if(this.checked) {
	     $('.aadhar-card').show();
        $('.format').show();
	    }else{
	     $('.aadhar-card').hide();	
	    }
	});


	$(document).on('change', '#pan_card', function() {
	    if(this.checked) {
	     $('.pan-card').show();
        $('.format').show();
	    }else{
	     $('.pan-card').hide();	
	    }
	});

	$(document).on('change', '#driving_licence', function() {
	    if(this.checked) {
	     $('.driving-licence').show();
        $('.format').show();
	    }else{
	     $('.driving-licence').hide();	
	    }
	});

	$(document).on('change', '#passport', function() {
	    if(this.checked) {
	     $('.passport').show();
        $('.format').show();
	    }else{
	     $('.passport').hide();	
	    }
	});


   $(document).on('change','.form-checkbox',function(){

var a=document.getElementById('aadhar_card');
var b=document.getElementById('pan_card');
var c=document.getElementById('driving_licence');
var d=document.getElementById('passport');
 

if(!(a.checked)&& !(b.checked) && !(c.checked) && !(d.checked)){
 
 $('.format').hide();
}
});
</script>


<script>



setTimeout(hide,5000);

function hide(){

 document.getElementById("alert").style.display="none";

}


</script>



</script>