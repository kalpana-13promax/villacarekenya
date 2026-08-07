<?php
require_once('../../config.php');
header('Content-Type: application/json');

$fields = $boj->getQuery("SELECT id, name,label FROM field_library WHERE field_type = 'unit' ORDER BY name");
echo json_encode($fields);