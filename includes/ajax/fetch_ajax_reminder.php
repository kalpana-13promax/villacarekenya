<?php
require_once('../config.php');
// print($_POST['note_edit']);
$adb = new db();

// // echo isset($_POST['property_note']);
// $propId = (int)$_POST['prop_id'];
// echo $propId;
// die;
// echo "SELECT * from property_listing where id='$propId'";
// echo $_POST['note_edit'];
// die; 
// $pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
// print_r($pro);
// die;



        if(@$_POST['prop_id']){
            $propId = (int)$_POST['prop_id'];
        }

        if(isset($_POST['reminder_edit'])){
           /**
            * @ FOR EDIT WITH reminder_edit  
            */
		
			$pid = $adb->int($_POST['prop_id']);
			$pro = $adb->getQuery("SELECT * from property_listing where id='$pid'");
			$pro_value = $pro[0];
			
            $pr = $boj->getQuery("select * from property_remarks where property_id = " . $pid . " order by id desc limit 5");
        }
        elseif(isset($_POST['reminder_id'])){
            /**
             * @ FOR UPDATE CONVERSATION
             */
            $propId = $adb->int($_POST['prop_id']);
			$pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
			$pro_value = $pro[0];
			// $array = array(
			// 	'property_id' => $adb->int($_POST['prop_id']),
			// 	'date' => $_POST['date'],

			// 	'uploader' => $_POST['uploader'],
			// 	'remarks' => $_POST['property_note']
			// );
            $array = array(
                'property_id' => $propId,
                'remind_msg' => $_POST['remind_msg'],
                // 'assign_to' => $_POST['assign_to'],
                'remind_date' => $_POST['remind_date'],
                'uploader' => $_POST['uploader']
            );

			$where = 'id = ' . $_POST['reminder_id'];
			$data = $adb->update_qry('property_reminder', $array, $where);
			// $nav = "?nav=properties&id=" . $_POST['id'] . "#notes";

			date_default_timezone_set("Asia/Kolkata");
			$today =  date("Y-m-d h:i:sa");

			$act = array(
				'user_id' => $_POST['user_id'],
				'action' => " Property " . @$pro_value->property_listing . " (" . $propId . ") Note " . $_POST['remind_msg'] . " has Updated",
				'date' => $today

			);
			$activity = $adb->insert_query('user_actvity', $act);


			// $adb->msg_set($data, $nav);
            $pr = $boj->getQuery("select * from property_reminder where property_id = " . $propId . " order by id desc limit 5");
        }
        elseif(isset($_POST['remark_id'])){
            /**
             * @ FOR DELETE CONVERSATION
             */
            $r = $_POST['remark_id'];
           
            // echo $r;
            // echo $pids;
            // die;

            $data = $boj->delQuery("delete FROM property_reminder where id = '$r'");
            
                $pid = $boj->int($_POST['prop_id']);
                $pro = $boj->getQuery("SELECT * from property_listing where id='$pid'");
                $pro_value = $pro[0];
                date_default_timezone_set("Asia/Kolkata");
                $today =  date("Y-m-d h:i:sa");
                $user_id = $getuserdata->id;
            
                $act = array(
                    'user_id' => $user_id,
                    'action' => " Property " . @$pro_value->property_title . " (" . $pid . ") Note has Deleted",
                    'date' => $today
            
                );
                $activity = $boj->insert_query('user_actvity', $act);
                // header("location: ?nav=properties&id='$pid'");
                $pr = $boj->getQuery("select * from property_remarks where property_id = " . $pid . " order by id desc limit 5");
        }
        elseif(isset($_POST['assign_to'])){
            // echo $_POST['assign_to'];
            // print_r($_POST);
            // die;
        
            // remind_msg
            // prop_id
            // assign_to
            // remind_date
        
        
            $propId = $adb->int($_POST['prop_id']);
            $pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
            $pro_value = $pro[0];
            $array = array(
                'property_id' => $propId,
                'remind_msg' => $_POST['remind_msg'],
                'assign_to' => $_POST['assign_to'],
                'type' => 'property',
                'remind_date' => $_POST['remind_date'],
                'uploader' => $_POST['uploader']
            );
        
            // $where = 'id = ' . $_POST['prop_id'];
            $data = $adb->insert_qry('property_reminder', $array);
            // $nav = "?nav=properties&id=" . $_POST['id'] . "#notes";
        
            // date_default_timezone_set("Asia/Kolkata");
            // $today =  date("Y-m-d h:i:sa");
            // $user_id = $getuserdata->id;
            // $act = array(
            //     'user_id' => $user_id,
            //     'action' => " Property " . @$pro_value->property_listing . " (" . $propId . ") Note " . $_POST['property_note'] . " has Updated",
            //     'date' => $today
        
            // );
            // $activity = $adb->insert_query('user_actvity', $act);
        
        }
       
        // elseif(isset($_POST['reminder_edit'])){
        //     /**
        //      * @ FOR EDIT WITH note_edit  
        //      */
         
        //      $pid = $adb->int($_POST['prop_id']);
        //      $pro = $adb->getQuery("SELECT * from property_listing where id='$pid'");
        //      $pro_value = $pro[0];
             
        //      $pr = $boj->getQuery("select * from property_reminder where property_id = " . $pid . " order by id desc limit 5");
        //     }
        // else{
        //     /**
        //      * @ ADD CONVERSATION
        //      */
        //     $propId = $adb->int($_POST['prop_id']);
		// 	// echo $propId;
        //     // die;
		// 	$pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
        //     // print_r($pro);
        //     // die;
		// 	$pro_value = $pro[0];
        //     // echo '<pre>';
        //     //   print_r($pro_value);
        //     // die;
		// 	$array = array(
		// 		'property_id' => $adb->int($_POST['prop_id']),
		// 		'date' => $_POST['date'],
		// 		'uploader' => $_POST['uploader'],
		// 		'remarks' => $_POST['property_note']
		// 	);

		// 	$data = $adb->insert_qry('property_remarks', $array);

		// 	date_default_timezone_set("Asia/Kolkata");
		// 	$today =  date("Y-m-d h:i:sa");

		// 	$act = array(
		// 		'user_id' => $_POST['user_id'],
		// 		'action' => " Property " . @$pro_value->property_listing . " (" . $propId . ") Note " . $_POST['property_note'] . "   Added",
		// 		'date' => $today

		// 	);
		// 	$activity = $adb->insert_query('user_actvity', $act);


		// 	// $nav = "?nav=properties&id=" . $_POST['id'] . "#notes";
		// 	// $adb->msg_set($data, $nav);
        //     $pr = $boj->getQuery("select * from property_remarks where property_id = " . $propId . " order by id desc");
        // }
        
        // if(isset($_POST['offset'])){
        //     $offset = $_POST['offset'];
           
        // }
        
      
?>
<?php if(isset($_POST['add-reminder'])){ ?>
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
                    <div class="col-md-6">
                    
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

<?php  }else{?>
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
                    <div class="col-md-6">
                    
                        <?php
                        if ($getuserdata->usertype == "admin" or $getuserdata->usertype == "root") {
                            if (isset($_POST['reminder_edit'])) {
                                $reminder = $boj->getid('property_reminder', $_POST['reminder_edit']);
                            } else {
                                $reminder = "";
                            }
                        }
                        $premind = $boj->getQuery("SELECT * from property_reminder where id='$propId'");
                        ?>
          
                        <form action="" method="post" id="reminder">
                            <label class="control-label">Reminder* </label>
                            <textarea id="editor1" class="form-control" rows="5" name="remind_msg"><?php if(isset($reminder)) {
                                                                                                            echo @$reminder[0]->remind_msg;
                                                                                                        } ?></textarea>

                            <br />
                            <!-- <input type="hidden" name="assign_to" value="<?php echo $_SESSION['admin']?>"> -->
                            <input type="date" name="remind_date" class="form-control" value="<?php if(isset($reminder)){ echo @$reminder[0]->remind_date;} ?>"><br />
                            <input type="hidden" name="user_id" value="<?= $getuserdata->id; ?>">
                            <input type="hidden" name="prop_id" value="<?= $propId; ?>">
                            <?php if (isset($_POST['reminder_edit']) and $getuserdata->usertype == "admin" or isset($_POST['reminder_edit']) and $getuserdata->usertype == "root") { ?>
                                <input type="hidden" name="reminder_id" value="<?php echo $_POST['reminder_edit']; ?>">
                                <button name="update-reminder" class="btn btn-primary col-md-12" value="submit">Update reminder</button>
                            <?php } else {
                                echo '<input type="hidden" name="add" value="add">';
                            echo '<button type="submit"  class="btn btn-primary col-md-12" value="submit">Add Note</button>';
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

<?php } ?>
                 
                      
                   