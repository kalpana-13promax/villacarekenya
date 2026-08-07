<?php
require_once('../../config.php');
$boj->check_session();
// error_reporting(0);
// Verify CSRF token
if (!$boj->verifyCsrf($_POST['csrf_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid security token']);
    exit;
}

// Validate required fields
$required_fields = ['visit_id', 'visit_status', 'visit_remarks'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

// Validate selfie upload
if (!isset($_FILES['selfie']) || $_FILES['selfie']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'Please upload a selfie']);
    exit;
}

try {
    $visit_id = intval($_POST['visit_id']);
    $visit_status = $boj->real_escape_string($_POST['visit_status']);
    $visit_remarks = $boj->real_escape_string($_POST['visit_remarks']);
    
    // Handle selfie upload
    $selfie = $_FILES['selfie'];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($selfie['type'], $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Please upload JPG or PNG']);
        exit;
    }
    
    $mime_type = $selfie['type'];
    try{

        // Get actual MIME type from file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detected_type = finfo_file($finfo, $selfie['tmp_name']);
        finfo_close($finfo);
    }catch(Exception $e){}
    
    if (!in_array($detected_type, $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Please upload JPG or PNG']);
        exit;
    }else{

    
  
       $img = $boj->uploadFiles($_FILES['selfie']);
    }
    
   
    
    // Update visit status
    $update_data = [
        'visit_status' => $visit_status,
        'visit_remarks' => $visit_remarks,
        'visited_selfie' => $img??'',
        'visit_date' => date('Y-m-d H:i:s'),
        'location' => $_POST['checkin_location'],
      
    ];
    $where = " id = '{$visit_id}'";
    $csrf_token = $_POST['csrf_token'];
    $boj->csrf_update('visits', $update_data, $where, $csrf_token);
    
    // Insert activity log
    $description = sprintf(
        "%s marked the visit as %s%s",
        $getuserdata->username,
        $visit_status,
        !empty($visit_remarks) ? " — Remarks: " . $visit_remarks : ""
    );
    $getid=$boj->getQuery("select lead_id from visits where id='{$visit_id}'");
    $lead_id=$getid[0]->lead_id;
    $boj->insertLeadActivityLog($lead_id, $getuserdata->id, 'Visit Marked as Done', $description,'',$img,);
    
    if($boj->getConnection()->affected_rows > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Visit marked as ' . $visit_status . ' successfully'
        ]);
    } else {
        // Delete uploaded file if update fails
        unlink($filepath);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error updating visit status'
        ]);
    }
    
} catch (Exception $e) {
    // Delete uploaded file if an error occurs
    if (isset($filepath) && file_exists($filepath)) {
        unlink($filepath);
    }
    echo json_encode(['status' => 'error', 'message' => 'Error processing request']);
} 