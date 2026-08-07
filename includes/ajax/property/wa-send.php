<?php
// Include config if you need database connection / API keys
require_once "../../config.php";
error();
require_once "../../classes/send_msg.php";


header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}


$phone = $_POST['phone'] ?? [];
$msg = trim($_POST['message'] ?? '');
$media = $_POST['media'] ?? null;


// Validate input
if (empty($phone) || empty($msg)) {
    echo json_encode([
        'status' => false,
        'message' => 'Mobile number and message are required'
    ]);
    exit;
}

// ===============================
// 🔹 PLACEHOLDER: Send WhatsApp API Call
// Replace this with your actual API integration
// Example using file_get_contents or cURL
// ===============================

try {
    // error();
    $ob = new CRMMessenger($_SESSION['admin']);

    $re = $ob->sendWhatsAppBulk($phone, $msg, $media);
    if ($re) {
        // print_r($re);

        // decode API json
        $data = json_decode($re['data'], true);

        $results = [];

        if (isset($data['data']['results'])) {
            foreach ($data['data']['results'] as $item) {
                $results[] = [
                    'phone' => $item['phone'],
                    'status' => $item['status']
                ];
            }
        }
        $ss = ['send' => $data['data']['sent'], 'fail' => $data['data']['failed']];
        echo json_encode([
            'status' => true,
            'message' => 'Message sent successfully',
            'data' => $ss,
            'response' => $re,
            'results' => $results
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'something Went wrong (phone No.  not registered on whatsapp )'
        ]);

    }
} catch (Exception $e) {
    echo json_encode([
        'status' => false,
        'message' => 'Failed to send message: ' . $e->getMessage()
    ]);
}
