<?php
require_once('../../config.php');


if (isset($_POST['unit_id'])) {
    $unit = $_POST['unit_id'];
    $city = $_POST['city_id'];
    $location = $_POST['location_id'];
    $subloc = $_POST['subloc_id'];
    $q = '';

    if (!empty($unit)) {


        if (!empty($city)) {

            $sql = "SELECT unit_no FROM property_listing WHERE unit_no = $unit AND city = '" . $city . "' ";
            // AND location= '" . $location . "' AND sub_location ='" . $sub_location . "'");

        }
        if (!empty($location)) {
            $sql .= " AND location = '{$location}' ";

        }
        if (!empty($subloc)) {
            $sql .= " AND sub_location = '{$subloc}' ";
        }

        $states = $boj->getQuery($sql) ?? [];



        if ($states) {

            echo json_encode([
                'msg' => '<span style="color:red">This unit no already exist.</span>',
                'status' => false
            ]);
        } else {
            echo json_encode([
                'msg' => '<span style="color:green">This unit no unique <i class="fa fa-ckeck"></i></span>',
                'status' => true
            ]);
        }
    }

}
?>