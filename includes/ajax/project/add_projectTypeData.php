<?php
require_once('../../config.php');
print_r($_POST);
die;
// Initialize response
$response = ['status' => 'error', 'message' => ''];

try {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !$boj->verifyCsrf($_POST['csrf_token'])) {
        throw new Exception("Invalid CSRF token");
    }

    // // Check permissions
    // $perm = $check->check_permission('projects_type', 'create');
    // if ($perm !== 'true') {
    //     throw new Exception("You don't have permission to perform this action");
    // }

    // Validate required fields
    if (empty($_POST['project_id']) || empty($_POST['project_type'])) {
        throw new Exception("Project and Project Type are required");
    }

    $projectId = (int) $_POST['project_id'];
    $projectTypeId = (int) $_POST['project_type'];

    // Prepare data structure
    $projectData = [
        'project_id' => $projectId,
        'project_type_id' => $projectTypeId,
        'fields' => [],
        'units' => []
    ];

    // Process main project fields
    if (!empty($_POST['fields'])) {
        echo current($_POST['fields']);
        foreach ($_POST['fields'] as $fieldName => $value) {

            $projectData['fields'][$fieldName] = $boj->sanitize($value);
        }
    }

    // Process file uploads for main project
    if (!empty($_FILES['fields'])) {
        foreach ($_FILES['fields']['name'] as $fieldName => $filename) {
            if ($_FILES['fields']['error'][$fieldName] === UPLOAD_ERR_OK) {
                $uploadResult = $boj->uploadFiles($_FILES['fields'], 'project/');
                $projectData['fields'][$fieldName] = $uploadResult;
            }
        }
    }

    // Process units
    if (!empty($_POST['units'])) {
        foreach ($_POST['units'] as $unitIndex => $unitData) {
            if (empty($unitData['unit_type']))
                continue;

            $unitEntry = [
                'unit_type_id' => (int) $unitData['unit_type'],
                'fields' => []
            ];

            // Process unit fields
            foreach ($unitData as $fieldName => $value) {
                if ($fieldName === 'unit_type')
                    continue;
                $unitEntry['fields'][$fieldName] = $boj->sanitize($value);
            }

            // Process file uploads for unit
            if (!empty($_FILES['units']['name'][$unitIndex])) {
                foreach ($_FILES['units']['name'][$unitIndex] as $fieldName => $filename) {
                    if ($fieldName === 'unit_type')
                        continue;
                    if ($_FILES['units']['error'][$unitIndex][$fieldName] === UPLOAD_ERR_OK) {
                        $uploadResult = $boj->uploadFiles($_FILES['units'], '/projects');
                        if ($uploadResult['status']) {
                            $unitEntry['fields'][$fieldName] = $uploadResult;
                        }
                    }
                }
            }

            $projectData['units'][] = $unitEntry;
        }
    }

    // Save to database (JSON format)
    $jsonData = json_encode($projectData, JSON_PRETTY_PRINT);
    $createdAt = date('Y-m-d H:i:s');


    $params = [
        'project_id' => $projectId,
        'project_type_id' => $projectTypeId,
        'project_type_name' => 'yy',
        'data_json' => $jsonData,
        'created_at' => $createdAt,
        'created_by' => $_SESSION['admin']
    ];

    $csrf = $_POST['csrf_token'];
    $boj->csrf_insert('project_type_data', $params, $csrf);
    $response = [
        'status' => 'success',
        'message' => 'Project data saved successfully'

    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);