<?php
require_once('../../config.php');

if (isset($_POST["city_id"]) && !empty($_POST["city_id"])) {
    $id = $_POST['city_id'];
    $data = $boj->getQuery("SELECT * FROM locations WHERE city = '$id' ORDER BY id ASC") ?? [];


    if (!empty($data)) {
        echo '<option value="">--Select Location--</option>';
        foreach ($data as $value) {
            echo '<option value="' . htmlspecialchars($value->id) . '">' . htmlspecialchars($value->location) . '</option>';
        }

    } else {
        return;
    }

}
?>