<?php
require_once('../config.php');

if (isset($_POST["tower"]) && !empty($_POST["tower"])) {
    $tower = $_POST['tower'];
    $query = $boj->getQuery("SELECT * FROM tower WHERE project_id = $tower ORDER BY id ASC");

    if (!empty($query)) {
        echo '<option value="">--Select Tower/Sub Project--</option>';
        foreach ($query as $value) {
            echo '<option value="' . htmlspecialchars($value->tower_name) . '">' . htmlspecialchars($value->tower_name) . '</option>';
        }
    } else {
        echo '<option value="">Tower/Sub Project not available</option>';
    }
}

if (isset($_POST["pid"]) && !empty($_POST["pid"])) {
    $pid = $_POST['pid'];
    $query = $boj->getQuery("SELECT * FROM project WHERE id = $pid ORDER BY id ASC");

    if (!empty($query)) {
        foreach ($query as $value) {
            echo '<option value="' . htmlspecialchars($value->city) . '">' . htmlspecialchars($value->city) . '</option>';
        }
    } else {
        $query = $boj->getQuery("SELECT * FROM city ORDER BY id ASC");
        if (!empty($query)) {
            foreach ($query as $value) {
                echo '<option value="' . htmlspecialchars($value->city) . '">' . htmlspecialchars($value->city) . '</option>';
            }
        }
    }
}

if (isset($_POST["stateid"]) && !empty($_POST["stateid"])) {
    $location_id = $_POST['stateid'];
    $qry = $boj->getQuery("SELECT * FROM project WHERE id = $location_id ORDER BY id ASC");

    if (!empty($qry)) {
        foreach ($qry as $val) {
            echo '<option value="' . htmlspecialchars($val->pro_location) . '">' . htmlspecialchars($val->pro_location) . '</option>';
        }
    } else {
        $query = $boj->getQuery("SELECT * FROM locations ORDER BY id ASC");
        if (!empty($query)) {
            foreach ($query as $value) {
                echo '<option value="' . htmlspecialchars($value->location) . '">' . htmlspecialchars($value->location) . '</option>';
            }
        }
    }
}

if (isset($_POST["cityid"]) && !empty($_POST["cityid"])) {
    $cityid = $_POST['cityid'];
    $qry = $boj->getQuery("SELECT * FROM project WHERE id = $cityid ORDER BY id ASC");

    if (!empty($qry)) {
        foreach ($qry as $val) {
            echo '<option value="' . htmlspecialchars($val->sub_location) . '">' . htmlspecialchars($val->sub_location) . '</option>';
        }
    } else {
        $query = $boj->getQuery("SELECT * FROM sub_location ORDER BY id ASC");
        if (!empty($query)) {
            foreach ($query as $value) {
                echo '<option value="' . htmlspecialchars($value->sub_location) . '">' . htmlspecialchars($value->sub_location) . '</option>';
            }
        }
    }
}

if (isset($_POST["city_id"]) && !empty($_POST["city_id"])) {
    $id = $_POST['city_id'];
    $data = $boj->getQuery("SELECT * FROM locations WHERE city = $id ORDER BY id ASC");

    if (!empty($data)) {
        echo '<option value="">--Select Location--</option>';
        foreach ($data as $value) {
            echo '<option value="' . htmlspecialchars($value->id) . '">' . htmlspecialchars($value->location) . '</option>';
        }
    } else {
        return;
    }
}

if (isset($_POST["location_id"]) && !empty($_POST["location_id"])) {
    $location_id = $_POST['location_id'];
    $qry = $boj->getQuery("SELECT * FROM sub_location WHERE location = $location_id ORDER BY id ASC");

    if (!empty($qry)) {
        echo '<option value="">--Select Sub Location--</option>';
        foreach ($qry as $val) {
            echo '<option value="' . htmlspecialchars($val->id) . '">' . htmlspecialchars($val->sub_location) . '</option>';
        }
    } else {
        return;
    }
}
?>