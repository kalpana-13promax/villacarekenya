<?php
require_once('../config.php');

// // Assuming $_GET['city'] contains the selected city name
// $city = $_GET['city'];

// // Fetch locations based on the selected city
// $rows = $boj->getQuery("SELECT id, location FROM locations WHERE city = ?", [$city]);

// echo '<option value="">Select Location</option>';
// foreach ($rows as $row){
//     echo "<option value='{$row->location}'>{$row->location}</option>";
// }

if (isset($_POST['country_id'])) {
    $country_id = $_POST['country_id'];
    $states = $boj->getQuery("SELECT * FROM locations WHERE city = $country_id ORDER BY location ASC");
    echo '<option value="">--*Select Location--</option>';
    foreach ($states as $state) {
        echo '<option value="'.$state->id.'">'.ucwords($state->location).'</option>';
    }
}
?>

