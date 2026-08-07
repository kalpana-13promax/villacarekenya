<?php
require_once '../../config.php'; // adjust path

header('Content-Type: application/json');

// --- Check if query parameter exists ---
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
    echo json_encode([]);
    exit;
}

$con = $boj->getConnection();
$q_escaped = $con->real_escape_string($q);

// --- Build base query depending on user role ---
if ($getuserdata->usertype == 'root' || (string)$getuserdata->roleid === '1') {
    // Root/Admin: can see all leads
    $sql = "
        SELECT id, lead_name 
        FROM leads
        WHERE (lead_name LIKE '%$q_escaped%' 
            OR lead_contact LIKE '%$q_escaped%' 
            OR lead_mail LIKE '%$q_escaped%')
        ORDER BY id DESC
        LIMIT 50
    ";
} else {
    // Supervisor or normal user: only own + subordinates' leads
    $sql = "
        SELECT l.id, l.lead_name 
        FROM leads l
        LEFT JOIN user u ON l.assign_to = u.id
        WHERE (
            (l.lead_name LIKE '%$q_escaped%' 
            OR l.lead_contact LIKE '%$q_escaped%' 
            OR l.lead_mail LIKE '%$q_escaped%')
        )
        AND (
            l.assign_to = '{$getuserdata->id}'
            OR u.supervisor_id = '{$getuserdata->id}'
            OR l.lead_uploaded_by = '{$getuserdata->username}'
        )
        ORDER BY l.id DESC
        LIMIT 50
    ";
}

// --- Execute query ---
$leads = $boj->getQuery($sql) ?? [];

// --- Return formatted JSON for Select2 ---
$results = [];
foreach ($leads as $lead) {
    $results[] = [
        'id' => $lead->id,
        'text' => $lead->lead_name.' ('.$lead->id.')',
    ];
}

echo json_encode($results);
exit;
?>
