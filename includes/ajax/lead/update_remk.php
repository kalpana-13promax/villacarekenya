<?php
require_once('../../config.php');
$boj->check_session();
error();

header('Content-Type: application/json');

// make sure POST['action'] exists
$action = $_POST['action'] ?? '';
$date=date('Y-m-d h:i A');
if ($action == 'update') {
    if (!empty($_POST['remark_id']) && isset($_POST['remark'])) {
        $remark_id = intval($_POST['remark_id']);
        $remark = trim($_POST['remark']);
        $lid =(int) trim($_POST['lid']);
        $username = $getuserdata->username ?? 'Unknown';
$geR=$boj->getquery("select remarks from remarks where id=$remark_id order by id limit 1")[0]->remarks??'';
        $update = $boj->getConnection()->query("
            UPDATE remarks 
            SET remarks = '$remark',
                remarks_date ='$date',
                remarks_by = '$username',
                updated_at = NOW()
            WHERE id = $remark_id
        ");

        if ($update) {
		$description ="Remarks updated  "  ."<br><strong>$geR</strong>=>".$remark??''." on " . date("Y-m-d h:i:sa") ;
		$r=$boj->insertLeadActivityLog($lid, $getuserdata->id, 'Remarks Updated',  $description);

            echo json_encode([
                'success' => true,
               
                'updated_at' => date('d M Y h:i A'),
                'remark' => htmlspecialchars($remark)
            ]);
        } else {
            echo json_encode(['success' => false, 'error'=>$boj->getConnection()->error,]);
        }
    }

} elseif ($action == 'added') {

    $lead_id = intval($_POST['lead_id']);
    $remark = trim($_POST['remarks']);
    $username = $getuserdata->username ?? 'Unknown';
    $user_id = intval($_POST['user_id']);
    $contact_status = $_POST['contact_status'] ?? 'no';
    $contact_date = !empty($_POST['contact_date']) ? $_POST['contact_date'] : null;

    $query = "INSERT INTO remarks (lead_id, remarks, remarks_by, remarks_date, contacted_at)
              VALUES ('$lead_id', '$remark', '$username', '$date', " . 
              ($contact_date ? "'$contact_date'" : "NULL") . ")";

    $insert = $boj->getConnection()->query($query);
    $id=$boj->getConnection()->insert_id;

    if ($insert) {
        $description ="Remarks Added" .' '.$remark??''. " on " . date("Y-m-d h:i:s a")  ;
		$boj->insertLeadActivityLog($lead_id, $getuserdata->id, 'Remarks Add',  $description);

        echo json_encode([
            'success' => true,
            'remark' => htmlspecialchars($remark),
            'remarks_by' => $username,
            'remark_id'=>$id,
            'lid'=>$lead_id,
            'remarks_date' => date('d M Y h:i A'),
            'contacted_at' => $contact_date
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else if ($action == 'load') {
    $lid = intval($_POST['lid']);

    $rows = $boj->getQuery("SELECT * FROM remarks WHERE lead_id=$lid ORDER BY id ASC");
    $rr = $boj->getQuery("SELECT * FROM leads WHERE id=$lid ");
    $html = '';

    if(!empty($rr)&&!empty($rr[0]->remarks)){
        $rl=$rr[0];
          $isMyRemark = trim($rl->lead_uploaded_by) == $getuserdata->username;
            $contact_icon = !empty($rl->contacted_at)
                ? "<i class='fa fa-phone-square fa-1x' title='Contacted at $rl->contacted_at' style='color:green; margin-right:5px'></i>"
                : '';   

            $html .= '<div class="chat-bubble ' . ($isMyRemark ? 'chat-sent' : 'chat-received') . '"  data-lid="' . $lid . '">';
            $html .= '<div class="chat-meta"><strong>' . $contact_icon??'' . ucwords($rl->lead_uploaded_by) . '</strong> <span class="chat-date">' . $rl->lead_date . '</span></div>';
            $html .= '<div class="chat-message"><span class="remark-text">' . nl2br(htmlspecialchars($rl->remarks)) . '</span>';
           
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
           
    }
    if ($rows) {
        foreach ($rows as $conv) {
            $isMyRemark = trim($conv->remarks_by) == $getuserdata->username;
            $contact_icon = !empty($conv->contacted_at)
                ? "<i class='fa fa-phone-square fa-1x' title='Contacted at $conv->contacted_at' style='color:green; margin-right:5px'></i>"
                : '';

            $html .= '<div class="chat-bubble ' . ($isMyRemark ? 'chat-sent' : 'chat-received') . '" data-id="' . $conv->id . '" data-lid="' . $lid . '">';
            $html .= '<div class="chat-meta"><strong>' . $contact_icon . ucwords($conv->remarks_by) . '</strong> <span class="chat-date">' . $conv->remarks_date . '</span></div>';
            $html .= '<div class="chat-message"><span class="remark-text">' . nl2br(htmlspecialchars($conv->remarks)) . '</span>';
            if ($isMyRemark) {
                $html .= ' <a href="javascript:void(0)" class="edit-remark" style="color:#007bff;margin-left:8px;"><i class="fa fa-pencil"></i></a>';
            }
            $html .= '</div>';
            if (!empty($conv->updated_at)) {
                $html .= '<div class="text-muted small updated-time">Edited on ' . date('d M Y h:i A', strtotime($conv->updated_at)) . '</div>';
            }
            $html .= '</div>';
        }
        echo json_encode(['success' => true, 'html' => $html]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

?>
