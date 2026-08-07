<?php
require_once('../config.php');

// Assuming database connection is already established
$rows = $boj->getQuery("SELECT id, pro_name FROM project");

foreach ($rows as $row){
    echo "<option value='{$row->id}'>{$row->pro_name}</option>";
}
?>
