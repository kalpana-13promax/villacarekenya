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

    $user_id = $getuserdata->id;

    // Call the handler function
    $response = handle_unit_type_save($_POST);

} catch (Exception $e) {
    $error_message = $e->getMessage();

    if (strpos($error_message, 'Duplicate entry') !== false) {
        $response['message'] = 'This unit type already exists.'; // ✅ Friendly message
    } else {
        $response['message'] = $error_message;
    }
}

echo json_encode($response);

// ------------------- MAIN FUNCTION ---------------------

function handle_unit_type_save($data)
{
    global $boj, $user_id;

    $unit_id = isset($data['edit_id']) ? intval($data['edit_id']) : null;

    // Validate Unit Type Name
    $typeName = trim($data['unit_type'] ?? '');
    if ($typeName === '') {
        throw new Exception('Unit type name is required.');
    }

    $csrf = $data['csrf_token'];

    if ($unit_id) {
        // Update Existing Record
        $updateData = [
            'name' => $typeName,
            'updated_by' => $user_id,
            'updated_at' => date('Y-m-d H:i:s') // optional
        ];
        $where = "id = '$unit_id'";

        $result = $boj->updateqry('unit_types', $updateData, $where, $csrf);

        if (!$result) {
            throw new Exception('Update failed.');
        }

        $action = 'updated';
    } else {
        // Insert New Record
        $insertData = [
            'name' => $typeName,
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s') // optional
        ];
        $result = $boj->insertData('unit_types', $insertData, $csrf);

        if (!$result) {
            throw new Exception('Insert failed.');
        }

        $action = 'added';
    }

    return [
        'success' => true, // ✅ string, not boolean
        'message' => "Unit type $action successfully."
    ];
}
?>