<?php
require_once('../../config.php');
@session_start();
error_reporting(0);
// require_once('../../includes/functions.php');

// $boj->check_session();
// $perm = $check->check_permission('projects', 'create');
// if ($perm != 'true') {
//     echo json_encode(['status' => 'error', 'message' => 'Permission denied']);
//     exit;
// }



// Validate CSRF token
if (!isset($_POST['csrf_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'CSRF token missing']);
    exit;
}

try {
    $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
    $project_type_id = isset($_POST['project_type']) ? intval($_POST['project_type']) : 0;
    $user_id = $_SESSION['admin'];

    // Basic validation
    if ($project_id <= 0 || $project_type_id <= 0) {
        throw new Exception('Invalid project or project type selected');
    }


    // 1. Insert main project data record
    $project_data_id = $boj->csrf_insert('project_data', [
        'project_id' => $project_id,
        'project_type_id' => $project_type_id,
        'project_type_name' => $_POST['type_name'],
        'created_by' => $user_id
    ], $_POST['csrf_token']);

    if (!$project_data_id) {
        throw new Exception('Failed to insert project data');
    }

    // 2. Insert project field values
    if (isset($_POST['fields']) && is_array($_POST['fields'])) {
        foreach ($_POST['fields'] as $field_name => $value) {
            // Get field ID
            $field = $boj->getQuery("SELECT id FROM field_library WHERE name = '$field_name'  LIMIT 1");
            if ($field && count($field) > 0) {
                $field_id = $field[0]->id;

                // Handle file uploads
                if (isset($_FILES['fields']['name'][$field_name]) && !empty($_FILES['fields']['name'][$field_name])) {
                    $arr = [
                        'name' => $_FILES['fields']['name'][$field_name],
                        'type' => $_FILES['fields']['type'][$field_name],
                        'tmp_name' => $_FILES['fields']['tmp_name'][$field_name],
                        'error' => $_FILES['fields']['error'][$field_name],
                        'size' => $_FILES['fields']['size'][$field_name],
                    ];
                    $value = $boj->uploadFiles($arr, 'projects');

                }

                $inserted = $boj->csrf_insert('project_field_value', [
                    'project_data_id' => $project_data_id,
                    'field_id' => $field_id,
                    'value' => $value
                ], $_POST['csrf_token']);

                //update project data for dataname

                if (!$inserted) {
                    throw new Exception('Failed to insert field value for: ' . $field_name);
                }
            }
        }
    }

    // 3. Insert unit data and field values
    if (isset($_POST['units']) && is_array($_POST['units']) || 1) {
        foreach ($_POST['units'] as $unit) {


            $unit_type_id = isset($unit['unit_type']) ? intval($unit['unit_type']) : 0;
            if ($unit_type_id <= 0)
                continue;

            // Insert unit data
            $unit_data_id = $boj->csrf_insert('unit_data', [
                'project_data_id' => $project_data_id,
                'unit_type_id' => $unit_type_id
            ], $_POST['csrf_token']);

            if (!$unit_data_id) {
                throw new Exception('Failed to insert unit data');
            }
            // Insert unit field values
            if (isset($unit) && is_array($unit)) {


                foreach ($unit as $field_name => $value) {


                    // Get field ID
                    $field = $boj->getQuery("SELECT id ,type FROM field_library WHERE name = '$field_name' LIMIT 1");
                    if ($field && count($field) > 0) {
                        $field_id = $field[0]->id;
                        if (strcasecmp(trim($field[0]->type), 'file') == 0) {
                            $fName = $field_name;
                            $i = 1;
                            if (
                                isset($_FILES['units']['name'][$i][$fName]) &&
                                !empty($_FILES['units']['name'][$i][$fName])
                            ) {
                                // Handle file uploads
                                $arr = [
                                    'name' => $_FILES['units']['name'][$i][$fName],
                                    'type' => $_FILES['units']['type'][$i][$fName],
                                    'tmp_name' => $_FILES['units']['tmp_name'][$i][$fName],
                                    'error' => $_FILES['units']['error'][$i][$fName],
                                    'size' => $_FILES['units']['size'][$i][$fName],
                                ];
                                $value = $boj->uploadFiles($arr, 'projects');
                                $i++;
                            }
                        }

                        $inserted = $boj->csrf_insert('unit_field_value', [
                            'unit_data_id' => $unit_data_id,
                            'field_id' => $field_id,
                            'value' => $value
                        ], $_POST['csrf_token']);


                        if (!$inserted) {
                            throw new Exception('Failed to insert unit field value for: ' . $field_name);
                        }
                    }
                }

            }
        }
    }

    // Commit transaction

    echo json_encode([
        'status' => 'success',
        'message' => 'Project data saved successfully',
        'project_data_id' => $project_data_id
    ]);
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo json_encode([
            'status' => 'error',
            'message' => 'This project with selected type name already exists.'
        ]);

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}