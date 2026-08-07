<?php
include('../../config.php'); // your DB connection + class + session
header('Content-Type: application/json');
error_reporting(0);
@session_start();
// print_r($_POST);
// die;

$response = ['status' => 'error', 'message' => 'Something went wrong.'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Validate CSRF Token
    if (empty($_POST['csrf_token']) || ($_SESSION['csrf'] !== $_POST['csrf_token'])) {
        throw new Exception('Invalid CSRF token.');
    }

    $user_id = $getuserdata->id ?? 0;

    $response = handle_field_save($_POST);

} catch (Exception $e) {
    $error_message = $e->getMessage();

    if (strpos($error_message, 'Duplicate entry') !== false) {
        $response['message'] = 'This field already exists.'; // Friendly error
    } else {
        $response['message'] = $error_message;
    }
}

echo json_encode($response);

// ------------------- MAIN FUNCTION ---------------------

function handle_field_save($data)
{
    global $boj, $user_id;

    // Validate Input Data
    $fieldType = trim($data['field_type'] ?? '');
    if ($fieldType === '') {
        throw new Exception('Please select field type (Project/Unit).');
    }

    $fieldLabel = trim($data['label'] ?? '');
    $fieldName = trim($data['name'] ?? '');
    $fieldFieldType = trim($data['type'] ?? 'text');
    $required = isset($data['required']) ? intval($data['required']) : 0;
    $csrf = $data['csrf_token'];

    // Check if Field Name and Label are valid
    if ($fieldLabel === '' || $fieldName === '') {
        throw new Exception('Field Label and Field Name are required.');
    }

    // Duplicate Check
    $check_duplicate = $boj->getQuery("SELECT id FROM field_library WHERE name = '$fieldName' AND field_type = '$fieldType'");
    if ($check_duplicate > 0) {
        throw new Exception("Field '$fieldName' already exists.");
    }

    // Insert Field
    $insertData = [
        'field_type' => $fieldType,
        'label' => $fieldLabel,
        'name' => $fieldName,
        'type' => $fieldFieldType,
        'required' => $required,
        'created_by' => $user_id,
        'created_at' => date('Y-m-d H:i:s')
    ];


    $boj->insertData('field_library', $insertData, $csrf);
    $field_id = $boj->getConnection()->insert_id;



    if (!$field_id) {
        throw new Exception('Failed to save field.');
    }

    // Insert Dropdown Options (if dropdown selected)
    if ($fieldFieldType === 'dropdown' && !empty($data['options'])) {
        foreach ($data['options'] as $optionValue) {
            // die;
            $optionValue = trim($optionValue);
            if ($optionValue !== '') {
                $optionData = [
                    'field_id' => $field_id,
                    'option_value' => $optionValue,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $boj->insertData('field_options', $optionData, $csrf);
            }
        }
    }

    return [
        'status' => true,
        'message' => 'Field saved successfully.'
    ];
}
?>