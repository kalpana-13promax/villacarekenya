<?php
include '../../config.php';
@session_start();
error_reporting(0);
header('Content-Type: application/json');

$msg = "Something went wrong";
$status = "error";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location'])) {

    $location = trim($_POST['location']);
    $user_id = $_POST['user_id'];
    $uploader = $_POST['uploader'];
    $csrf = $_POST['csrf_token'];
    $city = $_POST['city'];

    if (empty($location) || empty($city)) {
        echo json_encode(['status' => false, 'msg' => 'All fields is required']);
        exit;
    }

    if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $csrf) {
        echo json_encode(['status' => false, 'msg' => 'Invalid Session']);
        exit;
    }

    $where = " city = '$city' and location = '" . $boj->sanitize($location) . "'";
    $res = $boj->getwhere('locations', $where) ?? [];

    if (count($res) > 0) {
        echo json_encode([
            'status' => false,
            'msg' => "Location '$location' already exists. Please enter a different name."
        ]);
        exit;
    }

    $data_arr = [
        'location' => $location,
        'city' => $_POST['city'],
        'uploader' => $uploader,
        'user_id' => $user_id
    ];

    $data = $boj->insertData('locations', $data_arr, $csrf);

    if ($data) {
        $activity = [
            'user_id' => $user_id,
            'type' => 'add location',
            'action' => "location $location has been created by $uploader",
        ];
        $boj->insertData('user_actvity', $activity, $csrf);

        // $boj->msg_set(true, 'Location');

        echo json_encode(['status' => true, 'msg' => 'Location added successfully']);
        exit;
    } else {
        echo json_encode(['status' => false, 'msg' => 'Database insert error']);
        exit;
    }

} else {
    echo json_encode(['status' => false, 'msg' => 'Invalid request']);
    exit;
}
?>