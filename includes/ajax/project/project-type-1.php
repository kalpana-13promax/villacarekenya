<?php
require_once('../../config.php');
header('Content-Type: application/json');
@session_start();

// print_r($_POST);
// die;
// === Helper Functions ===
function insertRecord($boj, $table, $data)
{
    return $boj->insert_query($table, $data);
}

function updateRecord($boj, $table, $data, $where)
{
    return $boj->update_query($table, $data, $where);
}

// === CSRF Validation ===
if (!isset($_POST['csrf_token']) || $_SESSION['csrf'] !== $_POST['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

try {
    $userId = $_POST['user_id'];
    $now = date('Y-m-d H:i:s');

    // === 1. Save Project Type ===
    $projectTypeData = [
        'name' => $_POST['name'],
        'created_by' => $userId,
        'created_at' => $now,
    ];

    $projectTypeId = !empty($_POST['edit_id']) ? $_POST['edit_id'] : null;

    if ($projectTypeId) {
        updateRecord($boj, 'project_types', $projectTypeData, "id = '$projectTypeId'");
    } else {
        insertRecord($boj, 'project_types', $projectTypeData);
        $projectTypeId = $boj->getConnection()->insert_id;

    }

    // === 2. Save Project Fields ===
    $boj->delQuery("DELETE FROM pro_type_fields WHERE project_type_id = '$projectTypeId'");

    if (isset($_POST['fields'])) {
        foreach ($_POST['fields'] as $fieldId) {
            insertRecord($boj, 'pro_type_fields', [
                'project_type_id' => $projectTypeId,
                'field_id' => $fieldId,
                'created_by' => $userId,
                'created_at' => $now
            ]);

        }
    }

    // === 3. Save Unit Types ===
    $boj->delQuery("DELETE FROM pro_unit_types WHERE project_type_id = '$projectTypeId'");

    if (isset($_POST['unit_types'])) {
        foreach ($_POST['unit_types'] as $unitKey => $unitData) {
            // Skip the template entry
            if ($unitKey === '{index}')
                continue;

            insertRecord($boj, 'pro_unit_types', [
                'project_type_id' => $projectTypeId,
                'name' => $unitData['name'],
                'base_price' => isset($unitData['base_price']) ? $unitData['base_price'] : null,
                'created_by' => $userId,
                'created_at' => $now
            ]);
            $unitTypeId = $boj->getConnection()->insert_id;


            // === 4. Save Unit Fields ===
            if (isset($unitData['fields'])) {
                foreach ($unitData['fields'] as $fieldId) {
                    insertRecord($boj, 'unit_type_fields', [
                        'unit_type_id' => $unitTypeId,
                        'field_id' => $fieldId,
                        'created_by' => $userId,
                        'created_at' => $now
                    ]);
                }
            }
        }
    }

    // Log activity
    $action = !empty($_POST['edit_id']) ? "Project type updated" : "Project type created";
    $boj->insert_query('user_actvity', [
        'user_id' => $userId,
        'action' => $action,
        'date' => $now
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Project type saved successfully',
        'project_type_id' => $projectTypeId
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}