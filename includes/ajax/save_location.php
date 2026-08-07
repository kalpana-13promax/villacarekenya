<?php
require_once('../config.php');
try {
    date_default_timezone_set('Asia/Kolkata'); // or your desired timezone
    $user_id = $_POST['user_id']; // or pass from JS securely
    $lat = $_POST['lat'];
    $lon = $_POST['lon'];
    $time = date("Y-m-d H:i:s");
    $re = $boj->getQuery("select * from user_tracking where user_id=$user_id  order by id desc limit 1");
    $address = isset($_POST['address']) ? $_POST['address'] : 'N/a';
    if ($re) {
        $time1 = strtotime($re[0]->tracked_at);
        $time2 = strtotime($time);
        $diff = $time2 - $time1;
        $dist = $diff / 60;

        if ($dist > 5) {
            // Insert into tracking table
            $boj->insert_qry('user_tracking', [
                'user_id' => $user_id,
                'latitude' => $lat,
                'longitude' => $lon,
                'address' => $address,
                'tracked_at' => $time
            ]);
        }
    } else {
        $boj->insert_qry('user_tracking', [
            'user_id' => $user_id,
            'latitude' => $lat,
            'address' => $address,
            'longitude' => $lon,
            'tracked_at' => $time
        ]);

    }

    echo json_encode(['status' => true]);





} catch (Exception $e) {
    echo json_encode(['status' => false, 'error' => $e->getMessage()]);
}
