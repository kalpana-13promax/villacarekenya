<?php

include_once '../config.php';
error_reporting(0);
/*
if (isset($_POST['pro_id']) && !empty($_POST['pro_id']) && isset($_POST['image'])) {
    $csrf = $_POST['csrf_token'];
    $id = $_POST['pro_id'];
    $img = $_POST['image']; // Image name to be deleted

    // Fetch gallery images from the database
    $sql = "SELECT gallery FROM project WHERE id = $id";
    $data = $boj->getQuery($sql);

    if (!empty($data[0])) {
        $gallery = json_decode($data[0]->gallery, true); // Decode JSON to array

        if (($key = array_search($img, $gallery)) !== false) {
            unset($gallery[$key]); // Remove image from the array
            $gallery = array_values($gallery); // Re-index array
        }

        // Convert back to JSON
        $newGalleryJson = json_encode($gallery);

        $data = array('gallery' => $newGalleryJson);
        $where = "id= $id";
        $da = $boj->csrf_update_gal('project', $data, $where, $csrf);
        // Optionally, delete the image file from the server

        if ($da) {

            $filePath = "../uploads/" . $img;
            if (file_exists($filePath)) {
                unlink($filePath);
            }


            echo json_encode([
                "status" => true,
                "message" => "Image deleted successfully"
            ]);
        }
    } else {
        echo json_encode(["status" => false, "message" => "No gallery data found"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Invalid request"]);
}*/


if (isset($_POST['pro_id']) && !empty($_POST['pro_id']) && isset($_POST['image']) && isset($_POST['type'])) {
    $csrf = $_POST['csrf_token'];
    $id = $_POST['pro_id'];
    $img = $_POST['image']; // Image name to be deleted
    $type = $_POST['type']; // "project" or "property"

    // Determine table based on type
    $table = ($type === 'property') ? 'property_listing' : 'project';

    // Fetch gallery images from the database
    $sql = "SELECT gallery FROM $table WHERE id = $id";
    $data = $boj->getQuery($sql);

    if (!empty($data[0])) {
        $gallery = json_decode($data[0]->gallery, true); // Decode JSON to array

        if (($key = array_search($img, $gallery)) !== false) {
            unset($gallery[$key]); // Remove image from array
            $gallery = array_values($gallery); // Re-index array
        }

        // Convert back to JSON
        $newGalleryJson = json_encode($gallery);

        $data = array('gallery' => $newGalleryJson);
        $where = "id= $id";
        $da = $boj->csrf_update_gal($table, $data, $where, $csrf);

        // Optionally, delete the image file from the server
        if ($da) {
            $filePath = "../uploads/" . $img;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            echo json_encode([
                "status" => true,
                "message" => "Image deleted successfully"
            ]);
        }
    } else {
        echo json_encode(["status" => false, "message" => "No gallery data found"]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Invalid request"]);
}

?>