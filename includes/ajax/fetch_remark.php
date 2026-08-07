<?php
require_once('../config.php');

if(isset($_POST['propId'])){
  

   if($_POST["propId"]){
    $propId = $_POST["propId"];
   }else{
    $propId = $_POST["prop_id"];
   }
//    echo "select * from property_remarks where property_id = " . $propId . " order by id desc";
//    die;	
					$pr = $boj->getQuery("select * from property_remarks where property_id = " . $propId . " order by id desc limit 5");

   if(isset($_POST['close'])){
    $d = $_POST["prop_id"];
   return $boj->remarks_modal_ajax($d);
   }
				

   ?>

<div class="modal-content">

<div class="modal-header">
    <h4 class="modal-title">Add Conversation
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
    </h4>
    </button>
</div>
<div class="modal-body">

        <!-- <h2></h2>
        <br> -->
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item active">
            <a class="nav-link" data-toggle="tab" href="#home">Conversation</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#menu1">Reminder</a>
            </li>
          
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="home" class="tab-pane active"><br>
            <!-- <h3>Conversation</h3> -->
             <div class="row">
                
             
                <div class="panel-body" id="con">


            <div class="col-md-8">
            
                <div class="timeline">
                    <div class="tm-body">
                        <!-- <div class="tm-title">
                            <h5 class="m-0 pt-2 pb-2 text-uppercase">Note History</h5>
                        </div>'; -->
            
                        <?php
                        if (@$pr) {
                            foreach ($pr as $p_r) { ?>
                            <ol class="tm-items">
            
                                    <li>
                                        <div class="tm-info">
                                            <div class="tm-icon"><i class="fa fa-comment"></i></div>
                                            <time class="tm-datetime" datetime="">
                                                <div class="tm-datetime-date"><?php echo $p_r->timestamp; ?></div>
                                                <div class="tm-datetime-time"><small><?php echo ucwords($p_r->uploader); ?></small></div>
                                            </time>
                                        </div>
            
                                        <div class="tm-box">
                                            <p>
                                                <?php echo $p_r->remarks; ?>
                                            </p>
            
                                        </div>
                                        <?php if ($getuserdata->usertype == "admin" or $getuserdata->usertype == "root") { ?>
                                        <div align="right">
                                        <button class="btn btn-link"  data-id='<?= $propId ?>' data-edit='<?= $p_r->id ?>' data-uploader='<?= $p_r->uploader ?>' data-remark='<?= $p_r->remarks ?>' data-user-id='<?= $getuserdata->id; ?>' id='edit-note'><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-link"  id="trsah" data-id='<?= $propId ?>' data-remark='<?= $p_r->id ?>'><i class="fa fa-trash"></i></button>
                                        <?php } ?>
            
                                        </li>
                                        
                                </ol>
                            
                        <?php }
                        } else {
                            echo "No Data Available!";
                        } ?>
                     <!-- <button class="btn btn-primary" id="load-more" data-offset="0">Load more</button> -->
                    </div>
                </div>
                
            </div>
            <div class="col-md-4">
            
                <?php
                if ($getuserdata->usertype == "admin" or $getuserdata->usertype == "root") {
                    if (isset($_POST['note_edit'])) {
                        $note = $boj->getid('property_remarks', $_POST['note_edit']);
                    } else {
                        $note = "";
                    }
                }
                ?>
             <form action="" method="post" id="conversatn_form">
                    <label class="control-label">Property Note* </label>
                    <textarea id="editor1" class="form-control" rows="5" name="property_note"><?php if (isset($note)) {
                                                                                                    echo @$note[0]->remarks;
                                                                                                } ?></textarea>
                    <br />
                    <input type="hidden" name="user_id" value="<?= $getuserdata->id; ?>">
                    <input type="hidden" name="prop_id" value="<?= $propId; ?>">
                    <?php if (isset($_POST['note_edit']) and $getuserdata->usertype == "admin" or isset($_POST['note_edit']) and $getuserdata->usertype == "root") { ?>
                        <input type="hidden" name="note_id" value="<?php echo $_POST['note_edit']; ?>">
                        <button name="update-conversation-note" class="btn btn-primary col-md-12" value="submit">Update Note</button>
                    <?php } else {
                        echo '<input type="hidden" name="add-conversation-note" value="add">';
                        echo '<button type="submit" class="btn btn-primary col-md-12" value="submit">Add Note</button>';
                    } ?>
                    <input type="hidden" name="uploader" value="<?= $getuserdata->username; ?>">
                    <?php date_default_timezone_set("Asia/Kolkata");
                    $date_time =    date("Y/m/d"); ?>
                    <input type="hidden" name="date" value="<?= $date_time; ?>">
                </form>
            </div>


              </div>
             </div>
               

            </div>
                    <div id="menu1" class="tab-pane fade"><br>
                        <div class="row"  id="remind">
                            
                            <div class="col-md-6">
                            
                                <div class="timeline">
                                    <div class="tm-body">
                                        <!-- <div class="tm-title">
                                            <h5 class="m-0 pt-2 pb-2 text-uppercase">Note History</h5>
                                        </div>'; -->
                            
                                        <?php
                                        $premark = $boj->getQuery("select * from property_reminder where property_id = " . $propId . "  order by id desc");
                                        if (@$premark) {
                                            foreach ($premark as $remind) { ?>
                                            <ol class="tm-items">
                            
                                                    <li>
                                                        <div class="tm-info">
                                                            <div class="tm-icon"><i class="fa fa-comment"></i></div>
                                                            <time class="tm-datetime" datetime="">
                                                                <div class="tm-datetime-date"><?php echo $remind->remind_date; ?></div>
                                                                <div class="tm-datetime-time"><small><?php echo ucwords($remind->uploader); ?></small></div>
                                                            </time>
                                                        </div>
                            
                                                        <div class="tm-box">
                                                            <p>
                                                                <?php echo $remind->remind_msg; ?>
                                                            </p>
                            
                                                        </div>
                                                        <?php if ($getuserdata->usertype == "admin" or $getuserdata->usertype == "root") { ?>
                                                        <div align="right">
                                                        <button class="btn btn-link"  data-id='<?= $propId ?>' data-edit='<?= $remind->id ?>' data-uploader='<?= $remind->uploader ?>' data-remark='<?= $remind->remind_msg ?>' data-user-id='<?= $getuserdata->id; ?>' id='edit-reminder'><i class="fa fa-pencil"></i></button>
                                                        <button class="btn btn-link"  id="trsahreminder" data-id='<?= $propId ?>' data-remark='<?= $remind->id ?>'><i class="fa fa-trash"></i></button>
                                                        <?php } ?>
                            
                                                        </li>
                                                        
                                                </ol>
                                            
                                        <?php }
                                        } else {
                                            echo "No Data Available!";
                                        } ?>
                                    <!-- <button class="btn btn-primary" id="load-more" data-offset="0">Load more</button> -->
                                    </div>
                                </div>
                            
                            </div>
                        <div class="col-md-6" >
                        
                            <?php
                            if ($getuserdata->usertype == "admin" or $getuserdata->usertype == "root") {
                                if (isset($_POST['reminder_edit'])) {
                                    $reminder = $boj->getid('property_remarks', $_POST['reminder_edit']);
                                } else {
                                    $reminder = "";
                                }
                            }
                            $premind = $boj->getQuery("SELECT * from property_reminder where id='$propId'");
                            ?>
              
                            <form action="" method="post" id="reminder">
                                <label class="control-label">Reminder* </label>
                                <textarea id="editor1" class="form-control" rows="5" name="remind_msg"><?php if (isset($reminder)) {
                                                                                                                echo @$reminder[0]->remarks;
                                                                                                            } ?></textarea>

                                <br />
                                <input type="hidden" name="assign_to" value="<?php echo $_SESSION['admin']?>">
                                <input type="date" name="remind_date" class="form-control" value=""><br />
                                <!-- <input type="hidden" name="user_id" value="<?= $getuserdata->id; ?>"> -->
                                <input type="hidden" name="prop_id" value="<?= $propId; ?>">
                                <?php if (isset($_POST['reminder_edit']) and $getuserdata->usertype == "admin" or isset($_POST['reminder_edit']) and $getuserdata->usertype == "root") { ?>
                                    <input type="hidden" name="reminder_id" value="<?php echo $_POST['reminder_edit']; ?>">
                                    <button name="update-reminder" class="btn btn-primary col-md-12" value="submit">Update reminder</button>
                                <?php } else {
                                    echo '<input type="hidden" name="add-reminder" value="add">';
                                    echo '<button type="submit" class="btn btn-primary col-md-12" value="submit">Add Note</button>';
                                } ?>
                                <input type="hidden" name="uploader" value="<?= $getuserdata->username; ?>">
                                <?php //date_default_timezone_set("Asia/Kolkata");
                                //$date_time =    date("Y/m/d"); ?>
                                <!-- <input type="hidden" name="date" value="<?= $date_time; ?>"> -->
                            </form>
                            <!-- <form action="" method="POST" id="reminder">
                                
                                <div class="form-group">
    
                                <textarea required class="form-control" name="remind_msg" value="<?php if($premind[0]->remind_msg != null){ echo $premind['0']->remind_msg; } ?>"><?= $premind['0']->remind_msg ?></textarea>
    
    
                                </div> 
    
                                <div class="form-group">
    
                                        <input type="hidden" name="prop_id" id="propId">
    
                                        <input type="hidden" name="assign_to" value="<?php echo $_SESSION['admin']?>">
                                    
    
                                        <input type="date" name="remind_date" class="form-control" value="<?php if($premind[0]->remind_date != null){ echo $premind['0']->remind_date; } ?>"><br />
                                      <?php   if($premind[0]->remind_msg == null){?>
                                            <button name="new-reminder" class="btn btn-primary col-md-12 col-sm-12">
    
                                <i class="fa fa-plus"></i> Add Reminder</button>
    
                                        <?php }else{?>
                                            <button name="new-reminder" class="btn btn-primary col-md-12 col-sm-12">
    
                                <i class="fa fa-plus"></i> Update Reminder</button>
                                        <?php } ?>
                                        
                                </div>
                            </form> -->

                        </div>
                                    <!-- <h3>Reminder</h3> -->
                                    <!-- <h3>Conversation</h3> -->
                                    <?php 
                                    //    echo "SELECT * from property_listing where id='$propId'";
                                    //    die;
                                    
                                    //    print_r($premind);
                                        ?>
                                        <!-- <form action="" method="POST" id="reminder">
                                                
                                            <div class="form-group">

                                            <textarea required class="form-control" name="remind_msg" value="<?php if($premind[0]->remind_msg != null){ echo $premind['0']->remind_msg; } ?>"><?= $premind['0']->remind_msg ?></textarea>


                                            </div> 

                                            <div class="form-group">

                                                    <input type="hidden" name="prop_id" id="propId">

                                                    <input type="hidden" name="assign_to" value="<?php echo $_SESSION['admin']?>">
                                                

                                                    <input type="date" name="remind_date" class="form-control" value="<?php if($premind[0]->remind_date != null){ echo $premind['0']->remind_date; } ?>"><br />
                                                <?php   if($premind[0]->remind_msg == null){?>
                                                        <button name="new-reminder" class="btn btn-primary col-md-12 col-sm-12">

                                            <i class="fa fa-plus"></i> Add Reminder</button>

                                                    <?php }else{?>
                                                        <button name="new-reminder" class="btn btn-primary col-md-12 col-sm-12">

                                            <i class="fa fa-plus"></i> Update Reminder</button>
                                                    <?php } ?>
                                                    
                                            </div>
                                        </form>
                                    </div> -->
                                
                   </div>
        
</div>

<!-- Modal footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>

</div>


    <?php 
}

?>

