<?php
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli('localhost', 'root', '', 'sjp-crm');
if ($conn->connect_error) {
    $conn = @new mysqli('localhost', 'crm_crm', '8fYqyCDEQ&94', 'crm_crm');
}

if ($conn->connect_error) {
    die("Connection failed");
}

$res = $conn->query("
    SELECT p.id, p.property_title, p.available_for, pt.type as type_name
    FROM property_listing p
    LEFT JOIN property_type pt ON pt.id = p.category
    WHERE p.status != '7'
");

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
