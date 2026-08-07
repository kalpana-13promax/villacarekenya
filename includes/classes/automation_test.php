<?php
// Simple test harness for AutomationService without touching production DB

// Minimal in-file stub for base `db` class used by included classes (keeps test isolated)
if (!class_exists('db')) {
    class db {
        protected $mysqli;
        public function __construct() {
            $this->mysqli = null; // not used in stubbed tests
        }
        // provide minimal getAll to satisfy calls in AutomationService->processFollowupReminders
        public function getAll($q) { return []; }
    }
}

require_once __DIR__ . '/automative_services.php';

class StubSettings {
    private $data = [];
    public function __construct($data = []) { $this->data = $data; }
    public function getSetting($key, $default = null) {
        return $this->data[$key] ?? $default;
    }
}

class StubMessenger {
    public function __construct($senderId = 1) {}
    public function sendToAgentByTemplate($leadId, $userId, $templateId, $vars = []) { return "sent_agent_{$userId}_tpl_{$templateId}"; }
    public function sendToLeadByTemplate($leadId, $templateId, $vars = []) { return "sent_lead_{$leadId}_tpl_{$templateId}"; }
    public function sendToGroupByTemplate($userIds, $templateId, $vars = []) { return "sent_group_" . implode('-', $userIds) . "_tpl_{$templateId}"; }
    public function sendToClientByTemplate($clientId, $templateId, $vars = []) { return "sent_client_{$clientId}_tpl_{$templateId}"; }
}

// Create stubs with settings enabling some channels
$stubSettingsData = [
    'assign.agent.enabled' => 1,
    'assign.agent.channels' => 'whatsapp,sms,email',
    'assign.agent.whatsapp.template' => 101,
    'assign.agent.sms.template' => 102,
    'assign.agent.email.template' => 103,
    'assign.client.enabled' => 1,
    'assign.client.channels' => 'whatsapp,email',
    'assign.client.whatsapp.template' => 201,
    'assign.client.email.template' => 203,
    'assign.admin.enabled' => 1,
    'assign.admin.channels' => 'email',
    'assign.admin.email.template' => 301,
];

$settings = new StubSettings($stubSettingsData);
$messenger = new StubMessenger(1);
$db = new class {
    public function getAll($q){ return []; }
};

$svc = new AutomationService($db, 1, $settings, $messenger);

// Run onLeadAssigned
$res = $svc->onLeadAssigned(55, 10, [1,2], ['name'=>'Test']);
print_r($res);

// Run manual add
$res2 = $svc->onManualLeadAdd(56, 11, [1], ['name'=>'Manual']);
print_r($res2);

// Visit scheduled
$res3 = $svc->onVisitScheduled(1, 77, 12, '2025-08-19 10:00:00', 'Plot A', ['extra'=>'x']);
print_r($res3);

echo "Test harness completed\n";
