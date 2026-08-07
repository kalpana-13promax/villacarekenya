<?php
date_default_timezone_set('Asia/Kolkata');
// set_time_limit(0);
// ini_set('max_execution_time', 0);

require_once __DIR__ . '/../config.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once 'automative_services.php';
$automative_service = new AutomationService($_SESSION['admin']);
$db = $boj->getConnection();

// Load settings
$S = [];
$q = $db->query("SELECT setting_key, setting_value FROM lead_settings");
while ($r = $q->fetch_assoc()) {
    $S[$r['setting_key']] = $r['setting_value'];
}

$offsetValue = (int)($S['reminder_time_value'] ?? 10);
$offsetUnit  = strtolower(trim($S['reminder_time_unit'] ?? 'minutes'));
$repeat      = strtolower(trim($S['repeat_frequency'] ?? 'once'));
$channels    = array_filter(array_map('trim', explode(',', (string)($S['followup_notification_channels'] ?? 'whatsapp'))));

$sendToAgent = (int)($S['enable_reminder_assignee'] ?? 1) === 1;
$sendToAdmin = (int)($S['enable_reminder_admin'] ?? 0) === 1;
$sendToClient= (int)($S['enable_reminder_client'] ?? 0) === 1;

// Window to fire in seconds (cron runs every 5 min)
$FIRE_WINDOW_SEC = 300;

$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));

$sql = "
SELECT
    l.id, l.lead_name, l.lead_contact, l.lead_mail, l.assign_to,
    l.lead_status, l.followup,
    l.follow_up_date, l.follow_up_time,
    l.last_followup_reminder_at
FROM leads l
WHERE l.followup='1'
  AND 
    l.follow_up_date IS NOT NULL
  AND l.follow_up_time IS NOT NULL
  AND l.follow_up_date <> '0000-00-00'
  and (l.last_followup_reminder_at IS NULL OR l.last_followup_reminder_at < CONCAT(l.follow_up_date,' ',l.follow_up_time))
 ORDER BY l.follow_up_date ASC, l.follow_up_time ASC
LIMIT 5

";

$res = $db->query($sql);
if (!$res) {
    error_log("Failed to fetch leads: " . $db->error);
    exit;
}

// Helper functions
function toDateTime(?string $date, ?string $time): ?DateTime {
    if (!$date || !$time) return null;
    try { return new DateTime("$date $time", new DateTimeZone('Asia/Kolkata')); } 
    catch(Throwable $e) { return null; }
}

function subtractOffset(DateTime $dt, int $value, string $unit): DateTime {
    $d = clone $dt;
    $unit = strtolower($unit);
    if ($value <= 0) return $d;
    switch ($unit) {
        case 'minute':
        case 'minutes': $d->modify("-{$value} minutes"); break;
        case 'hour':
        case 'hours':   $d->modify("-{$value} hours"); break;
        case 'day':
        case 'days':    $d->modify("-{$value} days"); break;
        default: $d->modify("-{$value} minutes");
    }
    return $d;
}

function nextRepeatFrom(?DateTime $lastSent, string $repeat): ?DateTime {
    if (!$lastSent) return null;
    switch (strtolower($repeat)) {
        case 'daily':  return (clone $lastSent)->modify('+1 day');
        case 'weekly': return (clone $lastSent)->modify('+7 days');
        case 'once':   return null;
        case 'until_followed': return (clone $lastSent)->modify('+1 day');
        default: return null;
    }
}

function isLeadClosed(array $lead): bool {
    $status = strtolower((string)($lead['lead_status'] ?? ''));
    if (in_array($status, ['converted','closed','lost','inactive'], true)) return true;

    $followupFlag = (int)$lead['followup'] ?? 0;
    if ($followupFlag === 0) return true; // No followup
    return false;
}

function fetchAdminIds($db): array {
    $ids = [];
    $res = $db->query("SELECT id FROM user WHERE usertype='root'");
    while ($row = $res->fetch_assoc()) $ids[] = (int)$row['id'];
    return $ids;
}

$adminIDs = fetchAdminIds($db);

while ($lead = $res->fetch_assoc()) {

    
    // print_r($lead); // DEBUG: check data
    if (isLeadClosed($lead)) continue;
    
    $eventDT = toDateTime($lead['follow_up_date'], $lead['follow_up_time']);
    if (!$eventDT) continue;
    
    $firstReminderDT = subtractOffset($eventDT, $offsetValue, $offsetUnit);
    
    $lastSent = !empty($lead['last_followup_reminder_at']) ? new DateTime($lead['last_followup_reminder_at'], new DateTimeZone('Asia/Kolkata')) : null;
    $targetDT = !$lastSent ? $firstReminderDT : nextRepeatFrom($lastSent, $repeat);
    
    if (!$targetDT) continue; // already sent once or invalid repeat
    if ($repeat !== 'until_followed' && $targetDT > $eventDT) continue;

    // Optionally use inWindow to limit timing, or skip it if cron runs frequently
    // if (!inWindow($now, $targetDT, $FIRE_WINDOW_SEC)) continue;

    $leadID  = (int)$lead['id'];
    $agentID = (int)$lead['assign_to'];

    // -------------------------------
    // Attempt send first
    try {
        $sent = $automative_service->onFollowupReminder($leadID, $agentID, $adminIDs);
        if ($sent) {
            // Only update DB if send succeeded
            $stmt = $db->prepare("UPDATE leads SET last_followup_reminder_at = ? WHERE id = ?");
            $sentAt = $now->format('Y-m-d H:i:s');
            $stmt->bind_param('si', $sentAt, $leadID);
            $stmt->execute();
            $stmt->close();
            $db->commit();
            echo "Lead $leadID reminder sent.\n";
        } else {
            error_log("Lead $leadID: Reminder send failed (WhatsApp/API)");
        }
    } catch (Throwable $e) {
        error_log("Lead $leadID: Exception during reminder: ".$e->getMessage());
    }

}
?>
