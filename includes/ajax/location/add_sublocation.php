<?php
include '../../config.php';
@session_start();
error_reporting(0);
header('Content-Type: application/json');

$msg = "Something went wrong";
$status = "error";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sub_location'])) {

    $location = trim($_POST['location'] ?? '');
    $sub_location = trim($_POST['sub_location'] ?? '');
    $user_id = $_POST['user_id'];
    $uploader = $_POST['uploader'];
    $csrf = $_POST['csrf_token'];
    $city = $_POST['city'];

    if (empty($sub_location) || empty($location) || empty($city)) {
        echo json_encode(['status' => false, 'msg' => 'All fields is required']);
        exit;
    }

    if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $csrf) {
        echo json_encode(['status' => false, 'msg' => 'Invalid Session']);
        exit;
    }

    $where = "  location = '" . $boj->sanitize($location) . "' and sub_location = '" . $boj->sanitize($sub_location) . "'";
    $res = $boj->getwhere('sub_location', $where) ?? [];

    if (count($res) > 0) {
        echo json_encode([
            'status' => false,
            'msg' => "Sub Location '$sub_location' already exists. Please enter a different name."
        ]);
        exit;
    }

    $data_arr = [
        'location' => $location,
        'city' => $_POST['city'],
        'sub_location' => $sub_location,
        'uploader' => $uploader,
        'user_id' => $user_id
    ];

    $data = $boj->insertData('sub_location', $data_arr, $csrf);

    if ($data) {
        $activity = [
            'user_id' => $user_id,
            'type' => 'add sub_location',
            'action' => "sub_location $location has been created by $uploader",
        ];
        $boj->insertData('user_actvity', $activity, $csrf);

        // $boj->msg_set(true, 'Location');

        echo json_encode(['status' => true, 'msg' => 'Sub Location added successfully']);
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