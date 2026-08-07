<?php
require_once('../config.php');

// Assuming $_GET['location'] contains the selected location name and $_GET['city'] contains the selected city
// $location = $_GET['location'];
// $city = $_GET['city'];

// // Fetch sub-locations based on the selected location and city
// $rows = $boj->getQuery("SELECT id, sub_location FROM sub_location WHERE location = ? AND city = ?", [$location, $city]);

// echo '<option value="">Select Sub-location</option>';
// foreach ($rows as $row){
//     echo "<option value='{$row->sub_location}'>{$row->sub_location}</option>";
// }

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    $cities = $boj->getQuery("SELECT * FROM sub_location WHERE location = $state_id ORDER BY sub_location ASC");
    echo '<option value="">--Select Sub Location--</option>';
    foreach ($cities as $city) {
        echo '<option value="'.$city->id.'">'.ucwords($city->sub_location).'</option>';
    }
}
?>
