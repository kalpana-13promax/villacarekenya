<?php
include('../../config.php'); // your DB connection + class + session
header('Content-Type: application/json');
error_reporting(0);
@session_start();
$response = ['status' => 'error', 'message' => 'Something went wrong.'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Validate CSRF Token
    if (empty($_POST['csrf_token']) || ($_SESSION['csrf'] !== $_POST['csrf_token'])) {
        throw new Exception('Invalid CSRF token.');
    }

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $response = handle_project_type_update($_POST);

    } else {

        // Call the handler function
        $response = handle_project_type_insert($_POST);
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

// ------------------- MAIN FUNCTION ---------------------

function handle_project_type_insert($data)
{
    global $boj;

    // Validate Project Type Name
    $typeName = trim($data['project_type_name'] ?? '');
    if ($typeName === '') {
        throw new Exception('Project type name is required.');
    }

    // Collect Fields (label, name, type, is_required)
    $labels = $data['field_label'] ?? [];
    $names = $data['field_name'] ?? [];
    $types = $data['field_type'] ?? [];
    $required = $data['is_required'] ?? [];

    if (empty($labels) || empty($names)) {
        throw new Exception('At least one field is required.');
    }

    $fields = [];
    for ($i = 0; $i < count($labels); $i++) {
        if (trim($labels[$i]) && trim($names[$i])) {
            $fields[] = [
                'label' => trim($labels[$i]),
                'name' => trim($names[$i]),
                'type' => $types[$i] ?? 'text',
                'required' => ($required[$i] ?? '1') === '1'
            ];
        }
    }
    // Collect sub Fields -> units (label, name, type, is_required)
    $label = $data['unit_label'] ?? [];
    $name = $data['unit_name'] ?? [];
    $type = $data['unit_type'] ?? [];
    $required_ = $data['is_unit_required'] ?? [];

    if (empty($label) || empty($name)) {
        throw new Exception('At least one sub field is required.');
    }

    $sub_fields = [];
    for ($i = 0; $i < count($label); $i++) {
        if (trim($label[$i]) && trim($name[$i])) {
            $sub_fields[] = [
                'label' => trim($label[$i]),
                'name' => trim($name[$i]),
                'type' => $type[$i] ?? 'text',
                'required' => ($required_[$i] ?? '1') === '1'
            ];
        }
    }

    if (empty($sub_fields)) {
        throw new Exception('Sub Fields are not valid.');
    }

    // Prepare data to insert
    $insertData = [
        'name' => $typeName,
        'pro_fields' => json_encode($fields),
        'pro_subfields' => json_encode($sub_fields),
        'created_by' => $_POST['uploader']
    ];
    $csrf = $data['csrf_token'];
    // Insert using your global class

    $result = $boj->insertData('project_types', $insertData, $csrf);

    if ($result) {
        return ['status' => 'success', 'message' => 'Project Type inserted successfully.'];
    } else {
        throw new Exception('Insert failed.');
    }
}
function handle_project_type_update($data)
{
    global $boj;

    // Validate Project Type Name
    $typeName = trim($data['project_type_name'] ?? '');
    if ($typeName === '') {
        throw new Exception('Project type name is required.');
    }

    // Collect Fields (label, name, type, is_required)
    $labels = $data['field_label'] ?? [];
    $names = $data['field_name'] ?? [];
    $types = $data['field_type'] ?? [];
    $required = $data['is_required'] ?? [];
    $id = $_POST['edit_id'];
    if (empty($labels) || empty($names)) {
        throw new Exception('At least one field is required.');
    }

    $fields = [];
    for ($i = 0; $i < count($labels); $i++) {
        if (trim($labels[$i]) && trim($names[$i])) {
            $fields[] = [
                'label' => trim($labels[$i]),
                'name' => trim($names[$i]),
                'type' => $types[$i] ?? 'text',
                'required' => ($required[$i] ?? '1') === '1'
            ];
        }
    }

    if (empty($fields)) {
        throw new Exception('Fields are not valid.');
    }



    // Collect sub Fields -> units (label, name, type, is_required)
    $label = $data['unit_label'] ?? [];
    $name = $data['unit_name'] ?? [];
    $type = $data['unit_type'] ?? [];
    $required_ = $data['is_unit_required'] ?? [];

    if (empty($label) || empty($name)) {
        throw new Exception('At least one sub field is required.');
    }

    $sub_fields = [];
    for ($i = 0; $i < count($label); $i++) {
        if (trim($label[$i]) && trim($name[$i])) {
            $sub_fields[] = [
                'label' => trim($label[$i]),
                'name' => trim($name[$i]),
                'type' => $type[$i] ?? 'text',
                'required' => ($required_[$i] ?? '1') === '1'
            ];
        }
    }

    if (empty($sub_fields)) {
        throw new Exception('Sub Fields are not valid.');
    }


    // Prepare data to insert
    $updateData = [
        'name' => $typeName,
        'pro_fields' => json_encode($fields),
        'pro_subfields' => json_encode($sub_fields),
        'created_by' => $_POST['uploader']
    ];
    $csrf = $data['csrf_token'];
    $where = "id= $id";
    // Insert using your global class

    $result = $boj->updateQry('project_types', $updateData, $where, $csrf);

    if ($result) {
        return ['status' => 'success', 'message' => 'Project Type inserted successfully.'];
    } else {
        throw new Exception('Update failed.');
    }
}
