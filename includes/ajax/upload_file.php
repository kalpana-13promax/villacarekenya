<?php
include_once '../config.php';
// error_reporting(0);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $uploadDir = 'projects/';
    $filename = $_POST['column'];
    $user_id = $_POST['user_id'];


    // print_r($_FILES['file']);
    $allowedTypes = ["image/jpeg", "image/jpg", "application/pdf"];

    if (in_array($_FILES["file"]["type"], $allowedTypes)) {

        $file = $boj->uploadFiles($_FILES['file'], $uploadDir);


        // print_r($data);
        if ($file) {
            $msg = 'File uploaded successfully';
            $fileName = $file;

            $arr = array(
                "$filename" => $fileName
            );

            $pro_id = $_POST['project_id'];
            $where = "id='$pro_id'";
            $boj->csrf_update_gal('project', $arr, $where, $_POST['csrf_token']);
        }
    } else {
        $msg = "Invalid file type!";
    }
} else {
    $msg = "No file uploaded!";
}

echo json_encode([
    'message' => $msg

]);
?>