<?php
include '../../config.php';
@session_start();
error_reporting(0);
header('Content-Type: application/json');

$msg = "Something went wrong";
$status = "error";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['city'])) {

    $city = trim($_POST['city']);
    $user_id = $_POST['user_id'];
    $uploader = $_POST['uploader'];
    $csrf = $_POST['csrf_token'];

    if (empty($city)) {
        echo json_encode(['status' => false, 'msg' => 'City name is required']);
        exit;
    }

    if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $csrf) {
        echo json_encode(['status' => false, 'msg' => 'Invalid CSRF token']);
        exit;
    }

    $where = "city = '" . $boj->sanitize($city) . "'";
    $res = $boj->getwhere('city', $where) ?? [];

    if (count($res) > 0) {
        echo json_encode([
            'status' => false,
            'msg' => "City '$city' already exists. Please enter a different name."
        ]);
        exit;
    }

    $data_arr = [
        'city' => $city,
        'uploader' => $uploader,
        'user_id' => $user_id
    ];

    $data = $boj->insertData('city', $data_arr, $csrf);

    if ($data) {
        $activity = [
            'user_id' => $user_id,
            'type' => 'add city',
            'action' => "City $city has been created by $uploader",
        ];
        $boj->insertData('user_actvity', $activity, $csrf);

        // $boj->msg_set(true, 'projects');

        echo json_encode(['status' => true, 'msg' => 'City added successfully']);
    } else {
        echo json_encode(['status' => false, 'msg' => 'Database insert error']);
    }

} else {
    echo json_encode(['status' => false, 'msg' => 'Invalid request']);
}
?>