<?php
session_start();
require_once '../../config.php';
error();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// // CSRF validation
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf'] ?? '')) {
//     echo json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
//     exit;
// }
// if(!isset($_POST['action'])){
//     echo json_encode(['success' => false, 'message' => 'Action is required']);
//     exit;
// }
  $conn = $boj->getConnection();
if(isset($_POST['action']) && $_POST['action'] == 'get'){

$i=$_POST['crm_project_id']??'';
if(empty($i)){
    echo json_encode(['success' => false, 'message' => 'CRM Project ID is required']);
    exit;
}
$get=$boj->getQuery("SELECT * FROM ext_project_map WHERE crm_project_id = '{$i}'"); 

if(empty($get)){
    echo json_encode(['success' => false, 'message' => 'No mapping found for this project']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Mapping retrieved successfully','data'=>$get]);
die;
}elseif(isset($_POST['action']) && $_POST['action'] == 'add'){

try {
    $crm_project_id = $_POST['crm_project_id'] ?? '';
    $sources = $_POST['source'] ?? [];
    $external_project_ids = $_POST['external_project_id'] ?? [];
    $descriptions = $_POST['description'] ?? [];

    // Validate inputs
    if (empty($crm_project_id)) {
        echo json_encode(['success' => false, 'message' => 'CRM Project ID is required']);
        exit;
    }

    if (empty($sources) || empty($external_project_ids)) {
        echo json_encode(['success' => false, 'message' => 'No mapping data provided']);
        exit;
    }

    // Get normal MySQLi connection
  

    // Verify CRM project exists
    $crm_project_id = (int)$crm_project_id;
    $result = $conn->query("SELECT id FROM project WHERE id = $crm_project_id");
    if (!$result || $result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid CRM project']);
        exit;
    }

    // Delete existing mappings for this project (optional - remove if you want to keep multiple)
    // $conn->query("DELETE FROM ext_project_map WHERE crm_project_id = $crm_project_id");

    $successCount = 0;
    for ($i = 0; $i < count($sources); $i++) {
        if (!empty($sources[$i]) && !empty($external_project_ids[$i])) {
            $source_id = (int)$sources[$i];
            $external_id = trim($external_project_ids[$i]);
            $description = trim($descriptions[$i] ?? '');

            // Escape strings
            $external_id = $conn->real_escape_string($external_id);
            $description = $conn->real_escape_string($description);

            // Check for duplicate external ID for same source
            $check = $conn->query("
                SELECT id FROM ext_project_map  
                WHERE source_id = $source_id AND external_project_id = '$external_id' AND crm_project_id != $crm_project_id
            ");
            if ($check && $check->num_rows > 0) {
                // Skip duplicate or return error
                continue;
            }

            $conn->query("
                INSERT INTO ext_project_map  
                (crm_project_id, source_id, external_project_id, description, created_at, updated_at) 
                VALUES ($crm_project_id, $source_id, '$external_id', '$description', NOW(), NOW())
            ");
            $successCount++;
        }
    }

    if ($successCount > 0) {
        echo json_encode([
            'success' => true, 
            'message' => "Successfully saved {$successCount} mapping(s)"
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'No valid mappings to save or all were duplicates'
        ]);
    }

} catch (Exception $e) {
    error_log("Error saving external mapping: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}}elseif(isset($_POST['action']) && $_POST['action'] == 'delete'){
    $id = $_POST['id']??'';
    if(empty($id)){
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        exit;
    }
    $id = (int)$id;
    $conn->query("DELETE FROM ext_project_map WHERE id = $id");
    echo json_encode(['success' => true, 'message' => 'Mapping deleted successfully']);
    exit;
}
?>