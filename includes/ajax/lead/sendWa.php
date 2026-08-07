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

$mob = trim($_POST['mob'] ?? '');
$msg = trim($_POST['msg'] ?? '');
$lead_id = trim($_POST['LeadID'] ?? '');

// Validate input
if (empty($mob) || empty($msg)) {
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
    $ob = new CRMMessenger($_SESSION['admin']);
    // die(json_encode(('here')));
    $re = $ob->sendWhatsApp($mob, $msg);
    if ($re) {

        // Dummy success
        echo json_encode([
            'status' => true,
            'message' => "Message sent to {$mob} (LeadID: {$lead_id})"
        ]);
        $boj->insertLeadActivityLog($lead_id, $getuserdata->id, 'WhatsApp Message Sent', 'WhatsApp Message Sent to ' . $mob . ' on '.date('D M Y H:i:s '));
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
