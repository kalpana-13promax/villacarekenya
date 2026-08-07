<?php
// get_users_by_location.php
include '../../config.php';
header('Content-Type: application/json');

error();

// Get location from AJAX request
$location = isset($_GET['location_id']) ? $boj->getConnection()->real_escape_string($_GET['location_id']) : '';

if (empty($location)) {
    echo json_encode(['error' => 'Location parameter missing']);
    exit;
}

$lt= $boj->getQuery("select location from locations where id=$location ");
// Query users by location
$lt= $lt[0]->location??'';
$sql = "SELECT id, `name`, usertype FROM user WHERE realted_area = '{$lt}'";
$result = $boj->getQuery($sql);

$users = [];
if ($result) {
    echo json_encode(['users' => $result]);
    
}else{
    echo json_encode(['users' => []]);

}


?>