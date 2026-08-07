<?php
require_once('../../config.php');

if (isset($_POST["location_id"]) && !empty($_POST["location_id"])) {
    $id = $_POST['location_id'];
    $data = $boj->getQuery("SELECT * FROM sub_location WHERE location = $id ORDER BY id ASC") ?? [];


    if (!empty($data)) {
        echo '<option value="">--Select Location--</option>';
        foreach ($data as $value) {
            echo '<option value="' . htmlspecialchars($value->id) . '">' . htmlspecialchars($value->sub_location) . '</option>';
        }

    } else {
        return;
    }
}
?>