<?php
require_once('../../config.php');
header('Content-Type: application/json');

$unitTypes = $boj->getQuery("SELECT id, name FROM unit_types ORDER BY name");
echo json_encode($unitTypes);