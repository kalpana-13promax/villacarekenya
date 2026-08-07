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

        if(isset($_POST['note_edit'])){
           /**
            * @ FOR EDIT WITH note_edit  
            */
		
			$pid = $adb->int($_POST['prop_id']);
			$pro = $adb->getQuery("SELECT * from property_listing where id='$pid'");
			$pro_value = $pro[0];
			
            $pr = $boj->getQuery("select * from property_remarks where property_id = " . $pid . " order by id desc limit 5");
        }elseif(isset($_POST['note_id'])){
            /**
             * @ FOR UPDATE CONVERSATION
             */
            $propId = $adb->int($_POST['prop_id']);
			$pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
			$pro_value = $pro[0];
			$array = array(
				'property_id' => $adb->int($_POST['prop_id']),
				'date' => $_POST['date'],

				'uploader' => $_POST['uploader'],
				'remarks' => $_POST['property_note']
			);

			$where = 'id = ' . $_POST['note_id'];
			$data = $adb->update_qry('property_remarks', $array, $where);
			// $nav = "?nav=properties&id=" . $_POST['id'] . "#notes";

			date_default_timezone_set("Asia/Kolkata");
			$today =  date("Y-m-d h:i:sa");

			$act = array(
				'user_id' => $_POST['user_id'],
				'action' => " Property " . @$pro_value->property_listing . " (" . $propId . ") Note " . $_POST['property_note'] . " has Updated",
				'date' => $today

			);
			$activity = $adb->insert_query('user_actvity', $act);


			// $adb->msg_set($data, $nav);
            $pr = $boj->getQuery("select * from property_remarks where property_id = " . $propId . " order by id desc limit 5");
        }elseif(isset($_POST['remark_id'])){
            /**
             * @ FOR DELETE CONVERSATION
             */
            $r = $_POST['remark_id'];
           
            // echo $r;
            // echo $pids;
            // die;

            $data = $boj->delQuery("delete FROM property_remarks where id = '$r'");
            
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
        // elseif(isset($_POST['assign_to'])){
        //     // echo $_POST['assign_to'];
        //     // print_r($_POST);
        //     // die;
        
        //     // remind_msg
        //     // prop_id
        //     // assign_to
        //     // remind_date
        
        
        //     $propId = $adb->int($_POST['prop_id']);
        //     $pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
        //     $pro_value = $pro[0];
        //     $array = array(
        //         'property_id' => $propId,
        //         'remind_msg' => $_POST['remind_msg'],
        //         'assign_to' => $_POST['assign_to'],
        //         'remind_date' => $_POST['remind_date'],
        //         'uploader' => $_POST['uploader']
        //     );
        
        //     // $where = 'id = ' . $_POST['prop_id'];
        //     $data = $adb->insert_qry('property_reminder', $array);
        //     // $nav = "?nav=properties&id=" . $_POST['id'] . "#notes";
        
        //     // date_default_timezone_set("Asia/Kolkata");
        //     // $today =  date("Y-m-d h:i:sa");
        //     // $user_id = $getuserdata->id;
        //     // $act = array(
        //     //     'user_id' => $user_id,
        //     //     'action' => " Property " . @$pro_value->property_listing . " (" . $propId . ") Note " . $_POST['property_note'] . " has Updated",
        //     //     'date' => $today
        
        //     // );
        //     // $activity = $adb->insert_query('user_actvity', $act);
        
        // }
        // elseif(isset($_POST['reminder_id'])){
        //     $propId = $adb->int($_POST['prop_id']);
		// 	$pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
		// 	$pro_value = $pro[0];
		// 	// $array = array(
		// 	// 	'property_id' => $adb->int($_POST['prop_id']),
		// 	// 	'date' => $_POST['date'],

		// 	// 	'uploader' => $_POST['uploader'],
		// 	// 	'remarks' => $_POST['property_note']
		// 	// );

        //     $array = array(
        //         'property_id' => $propId,
        //         'remind_msg' => $_POST['remind_msg'],
        //         'assign_to' => $_POST['assign_to'],
        //         'remind_date' => $_POST['remind_date'],
        //         'uploader' => $_POST['uploader']
        //     );

		// 	$where = 'id = ' . $_POST['note_id'];
		// 	$data = $adb->update_qry('property_reminder', $array, $where);
		// 	// $nav = "?nav=properties&id=" . $_POST['id'] . "#notes";

		// 	date_default_timezone_set("Asia/Kolkata");
		// 	$today =  date("Y-m-d h:i:sa");

		// 	$act = array(
		// 		'user_id' => $_POST['user_id'],
		// 		'action' => " Property " . @$pro_value->property_listing . " (" . $propId . ") Note " . $_POST['property_note'] . " has Updated",
		// 		'date' => $today

		// 	);
		// 	$activity = $adb->insert_query('user_actvity', $act);


		// 	// $adb->msg_set($data, $nav);
        //     $pr = $boj->getQuery("select * from property_remarks where property_id = " . $propId . " order by id desc limit 5");
        // }elseif(isset($_POST['reminder_edit'])){
        //     /**
        //      * @ FOR EDIT WITH note_edit  
        //      */
         
        //      $pid = $adb->int($_POST['prop_id']);
        //      $pro = $adb->getQuery("SELECT * from property_listing where id='$pid'");
        //      $pro_value = $pro[0];
             
        //      $pr = $boj->getQuery("select * from property_reminder where property_id = " . $pid . " order by id desc limit 5");
        //     }
        else{
            /**
             * @ ADD CONVERSATION
             */
            $propId = $adb->int($_POST['prop_id']);
			// echo $propId;
            // die;
			$pro = $adb->getQuery("SELECT * from property_listing where id='$propId'");
            // print_r($pro);
            // die;
			$pro_value = $pro[0];
            // echo '<pre>';
            //   print_r($pro_value);
            // die;
			$array = array(
				'property_id' => $adb->int($_POST['prop_id']),
				'date' => $_POST['date'],
				'uploader' => $_POST['uploader'],
				'remarks' => $_POST['property_note']
			);

			$data = $adb->insert_qry('property_remarks', $array);

			date_default_timezone_set("Asia/Kolkata");
			$today =  date("Y-m-d h:i:sa");

			$act = array(
				'user_id' => $_POST['user_id'],
				'action' => " Property " . @$pro_value->property_listing . " (" . $propId . ") Note " . $_POST['property_note'] . "   Added",
				'date' => $today

			);
			$activity = $adb->insert_query('user_actvity', $act);


			// $nav = "?nav=properties&id=" . $_POST['id'] . "#notes";
			// $adb->msg_set($data, $nav);
            $pr = $boj->getQuery("select * from property_remarks where property_id = " . $propId . " order by id desc");
        }
        
        // if(isset($_POST['offset'])){
        //     $offset = $_POST['offset'];
           
        // }
        
      
?>

<?php if(!isset($_POST['note_id'])){

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
                        <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home">Conversation</a>
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
                        
                                <!-- <form action="" method="POST">

                                    <div class="form-group">

                                        <select name="assign_to" class="form-control">

                                            <option value=""> -- SELECT -- </option>

                                            <?php



                                            $users = $boj->getQuery("select * from user ");



                                            foreach ($users as $user) {

                                                if ($user->status == 'active') {

                                            ?>

                                                    <option value="<?= $user->id; ?>"> <?= $user->id . '. ' . $user->name; ?></option>

                                            <?php }
                                            } ?>

                                        </select> 

                                    </div>

                                    <div class="form-group">

                                        <textarea required class="form-control" name="remark"> </textarea>

                                    </div>

                                        <div class="form-group">

                                            <input type="hidden" name="propId" id="propId">

                                            <input type="hidden" name="assign_prop" value="<?php echo $_SESSION['admin']?>">

                                            <input type="date" name="due_date" class="form-control"><br />
                                            <button name="new-conversation" class="btn btn-primary col-md-12 col-sm-12">

                                                <i class="fa fa-plus"></i> Add conversation</button>

                                        </div>
                                </form>-->
                        
                            <div class="panel-body" id="con">

                                <div class="col-md-8">
                    
                                    <div class="timeline">
                                                    <div class="tm-body">
                                                        <div class="tm-title">
                                                            <h5 class="m-0 pt-2 pb-2 text-uppercase"></h5>
                                                        </div>;

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
                                                                            </div>
                                                                        <?php } ?>

                                                                        </li>
                                                                        <!-- <button class="btn btn-primary" id="load-more" data-offset="0">Load more</button> -->

                                                                </ol>
                                                        <?php }
                                                        } else {
                                                            echo "No Data Available!";
                                                        } ?>

                                                </div>
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    <?php
                                    if ($getuserdata->usertype == "admin" or $getuserdata->usertype == "root") {
                                        if (isset($_POST["note_edit"])) {
                                            $note = $boj->getid("property_remarks", $_POST["note_edit"]);
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
                                            <?php if (isset($_POST["note_edit"]) and $getuserdata->usertype == "admin" or isset($_POST["note_edit"]) and $getuserdata->usertype == "root") { ?>
                                                <input type="hidden" name="note_id" value="<?php echo $_POST["note_edit"]; ?>">
                                                <button name="update-conversation-note" class="btn btn-primary col-md-12" value="submit">Update Note</button>
                                            <?php } else {
                                                echo '<input type="hidden" name="add" value="add">';
                                            echo '<button type="submit"  class="btn btn-primary col-md-12" value="submit">Add Note</button>';
                                            } ?>
                                            <input type="hidden" name="uploader" value="<?= $getuserdata->username; ?>">
                                            <?php date_default_timezone_set("Asia/Kolkata");
                                            $date_time =    date("Y/m/d"); ?>
                                            <input type="hidden" name="date" value="<?= $date_time; ?>">
                                    </form>
                                </div>

                

                         </div>
                    <div id="menu1" class="tab-pane fade"><br>
                        <h3>Reminder</h3>
                        
                        
                </div>
                    
                    </div>
            
    </div>

<!-- Modal footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>

</div>
<?php }else{?>
    <div class="col-md-8">
    
    <div class="timeline">
        <div class="tm-body">
            <div class="tm-title">
                <h5 class="m-0 pt-2 pb-2 text-uppercase"></h5>
            </div>;

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
                                </div>
                            <?php } ?>

                            </li>
                            <!-- <button class="btn btn-primary" id="load-more" data-offset="0">Load more</button> -->

                    </ol>
            <?php }
            } else {
                echo "No Data Available!";
            } ?>

    </div>
    </div>

</div>

<div class="col-md-4">

<?php
if ($getuserdata->usertype == "admin" or $getuserdata->usertype == "root") {
if (isset($_POST["note_edit"])) {
$note = $boj->getid("property_remarks", $_POST["note_edit"]);
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
<?php if (isset($_POST["note_edit"]) and $getuserdata->usertype == "admin" or isset($_POST["note_edit"]) and $getuserdata->usertype == "root") { ?>
<input type="hidden" name="note_id" value="<?php echo $_POST["note_edit"]; ?>">
<button name="update-conversation-note" class="btn btn-primary col-md-12" value="submit">Update Note</button>
<?php } else {
echo '<input type="hidden" name="add" value="add">';
echo '<button type="submit"  class="btn btn-primary col-md-12" value="submit">Add Note</button>';
} ?>
<input type="hidden" name="uploader" value="<?= $getuserdata->username; ?>">
<?php date_default_timezone_set("Asia/Kolkata");
$date_time =    date("Y/m/d"); ?>
<input type="hidden" name="date" value="<?= $date_time; ?>">
</form>
</div>



</div>
<?php } ?>