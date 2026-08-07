<?php 
require_once('../config.php');

if (!empty($_POST["tower"])) {
    $tower = $_POST['tower'];
    $query = $boj->getQuery("SELECT * FROM tower WHERE project_id = '$tower' ORDER BY id ASC");
    if ($query) {
        echo '<option value="">--Select Tower/Sub Project--</option>';
        foreach ($query as $value) {
            echo '<option value="'.$value->tower_name.'">'.$value->tower_name.'</option>';
        }
    } else {
        echo '<option value="">Tower/Sub Project not available</option>';
    }
}

if (!empty($_POST["pid"])) {
    $pid = $_POST['pid'];
    $query = $boj->getQuery("SELECT * FROM project WHERE id = '$pid' ORDER BY id ASC");
    if ($query) {
        echo '<option value="">--Select City--</option>';
        foreach ($query as $value) {
            echo '<option value="'.$value->city.'">'.$value->city.'</option>';
        }
    }
}

if (!empty($_POST["stateid"])) {
    $location_id = $_POST['stateid'];
    $query = $boj->getQuery("SELECT * FROM project WHERE id = '$location_id' ORDER BY id ASC");
    if ($query) {
        echo '<option value="">--Select Location--</option>';
        foreach ($query as $val) {
            echo '<option value="'.$val->pro_location.'">'.$val->pro_location.'</option>';
        }
    }
}

if (!empty($_POST["cityid"])) {
    $cityid = $_POST['cityid'];
    $query = $boj->getQuery("SELECT * FROM project WHERE id = '$cityid' ORDER BY id ASC");
    if ($query) {
        echo '<option value="">--Select Sub Location--</option>';
        foreach ($query as $val) {
            echo '<option value="'.$val->sub_location.'">'.$val->sub_location.'</option>';
        }
    }
}

if (isset($_POST["action"]) && $_POST["action"] == 'all_locations') {
    // Load all cities
    $cityQuery = $boj->getQuery("SELECT * FROM city ORDER BY id ASC");
    if ($cityQuery) {
        echo '<option value="">--Select City--</option>';
        foreach ($cityQuery as $value) {
            echo '<option value="'.$value->city.'">'.$value->city.'</option>';
        }
    }
}

    // Load all locations
    $locationQuery = $boj->getQuery("SELECT * FROM locations ORDER BY id ASC");
    if ($locationQuery) {
        echo '<option value="">--Select Location--</option>';
        foreach ($locationQuery as $value) {
            echo '<option value="'.$value->location.'">'.$value->location.'</option>';
        }
    }

    // Load all sub-locations
    $subLocationQuery = $boj->getQuery("SELECT * FROM sub_location ORDER BY id ASC");
    if ($subLocationQuery) {
        echo '<option value="">--Select Sub Location--</option>';
        foreach ($subLocationQuery as $value) {
            echo '<option value="'.$value->sub_location.'">'.$value->sub_location.'</option>';
        }
    }
// }
?>
