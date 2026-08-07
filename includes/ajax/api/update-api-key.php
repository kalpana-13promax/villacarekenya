<?php
require_once('../../config.php');
// error();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api_id = intval($_POST['api_id'] ?? 0);
    $auto_assign_to = trim($_POST['auto_assign_to'] ?? '');
    $notification = isset($_POST['edit_notification']) ? implode(',', $_POST['edit_notification']) : '';
    $group  = isset($_POST['group_api']) ?  $_POST['group_api'] : '';

    try{
    if ($api_id > 0) {
            
            $con= $boj->getConnection();
            // Use direct query, not PDO or prepared statements
            $auto_assign_to_esc = $con->real_escape_string($auto_assign_to);
            $notification_esc = $con->real_escape_string($notification);
            $group = $con->real_escape_string($group);
            $api_id_int = intval($api_id);
            
            $sql = "UPDATE api_keys SET auto_assign_to = '{$auto_assign_to_esc}', notification = '{$notification_esc}' , group_api='{$group}' WHERE id = {$api_id_int}";
            if ($boj->getConnection()->query($sql)) {
            echo json_encode(['status'=>true,'message'=> 'API Key updated successfully']);
            exit;
        } else {
            http_response_code(500);
           
              echo json_encode(['status'=>true,'message'=> 'Failed to update API Key']);
                 exit;
        }
    } else {
        http_response_code(400);
      
          echo json_encode(['status'=>true,'message'=> 'Invalid API Key ID']);
             exit;
    }
}catch(Exception $e){
    return $e->getmessage();
}
} else {
    http_response_code(405);

      echo json_encode(['status'=>true,'message'=> 'Method Not Allowed']);
         exit;
}
