<?php
include('../../config.php');
header('Content-Type: application/json');
@session_start();

$response = ['status' => 'error', 'message' => 'Something went wrong.'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf'] ?? '')) {
        throw new Exception('Invalid CSRF token.');
    }

    // Validate required fields
    $required = ['client_name', 'client_contact', 'user'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field '{$field}' is required.");
        }
    }

    // Sanitize input
    function sanitize($data, $db)
    {
        $data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        return $db->real_escape_string(trim($data));
    }

    $db = $boj->getConnection(); // mysqli connection

    $data = [
        'name' => sanitize($_POST['client_name'], $db),
        'c_code' => sanitize($_POST['c_code'] ?? '', $db),
        'contact' => sanitize($_POST['client_contact'], $db),
        'a_code' => sanitize($_POST['a_code'] ?? '', $db),
        'alternate_contact' => sanitize($_POST['alternate_contact'] ?? '', $db),
        'father' => sanitize($_POST['father'] ?? '', $db),
        'mail' => sanitize($_POST['client_mail'] ?? '', $db),
        'dob' => sanitize($_POST['client_dob'] ?? '', $db),
        'address' => sanitize($_POST['client_address'] ?? '', $db),
        'status' => 'active',
        'uploader' => sanitize($_POST['user'], $db)
    ];

    // Prepare insert query
    $columns = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";
    $sql = "INSERT INTO owner ($columns) VALUES ($values)";

    if ($db->query($sql)) {
        $id = $db->insert_id;
        $response = [
            'status' => true,
            'message' => 'Owner added successfully!',
            'data' => [
                'id' => $id,
                'name' => $data['name']
            ]
        ];
    } else {
        throw new Exception("DB error: " . $db->error);
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
