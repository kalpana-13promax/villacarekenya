<?php
include '../../config.php';

$conn = $boj->getConnection();
if($_POST['action'] == 'update_LTstatus'){
if(isset($_POST['leadId']) && isset($_POST['status'])){
    $leadId = (int)$_POST['leadId'];
    $status =$conn->real_escape_string($_POST['status']);

    $sql = "UPDATE leads SET lead_status = '$status' WHERE id = $leadId";
    if($conn->query($sql)){
        echo json_encode(['success' => true,'message'=>'Status updated successfully'   ]);
    } else {
        echo json_encode(['success' => false,'message'=>'Failed to update status'   ]);
    }
}
}
elseif($_POST['action'] == 'update_CallStatus'){
if(isset($_POST['leadId']) && isset($_POST['status'])){
    $leadId = (int)$_POST['leadId'];
    $status =$conn->real_escape_string($_POST['status']);

    $sql = "UPDATE leads SET lead_call_status = '$status' WHERE id = $leadId";
    if($conn->query($sql)){
        echo json_encode(['success' => true,'message'=>'Status updated successfully'   ]);
    } else {
        echo json_encode(['success' => false,'message'=>'Failed to update status'  ,'error' => $conn->error ]);
    }
}

}else{
    echo json_encode(['success' => false,'message'=>'Invalid request'   ]);
}
?>