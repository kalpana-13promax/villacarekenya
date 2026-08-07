<?php
/**
 * Follow-up Reminder Cron
 * - Uses leads.follow_up_date/time as the event
 * - Sends reminders starting at (event - offset)
 * - Repeats as per settings without changing the event date
 */

date_default_timezone_set('Asia/Kolkata');

require_once __DIR__ . '/../config.php'; // $db mysqli|PDO, and your sendEmail/WhatsApp/SMS wrappers
error();
require_once 'automative_services.php'; // $db mysqli|PDO, and your sendEmail/WhatsApp/SMS wrappers
$automative_service = new AutomationService($_SESSION['admin']);
$db = $boj->getConnection();
// ---------------------------
// Load settings into $S[]
// ---------------------------
$S = [];
$q = $db->query("SELECT setting_key, setting_value FROM lead_settings");
while ($r = $q->fetch_assoc()) {
    $S[$r['setting_key']] = $r['setting_value'];
}

$offsetValue = (int)($S['reminder_time_value'] ?? 10);   // e.g. 10
$offsetUnit  = strtolower(trim($S['reminder_time_unit'] ?? 'minutes')); // minutes|hours|days
$repeat      = strtolower(trim($S['repeat_frequency'] ?? 'once'));      // once|daily|weekly|until_followed
$channels    = array_filter(array_map('trim', explode(',', (string)($S['followup_notification_channels'] ?? 'whatsapp'))));
$sendToAgent = (int)($S['enable_reminder_assignee'] ?? 1) === 1;
$sendToAdmin = (int)($S['enable_reminder_admin'] ?? 0) === 1;
$sendToClient= (int)($S['enable_reminder_client'] ?? 0) === 1;

// Window to fire in seconds (cron runs every 5 min)
$FIRE_WINDOW_SEC = 300;

// ---------------------------
// Helpers for time math
// ---------------------------
function toDateTime(?string $date, ?string $time): ?DateTime {
    if (!$date || !$time) return null;
    try {
        return new DateTime("$date $time", new DateTimeZone('Asia/Kolkata'));
    } catch (Throwable $e) {
        return null;
    }
}

function subtractOffset(DateTime $dt, int $value, string $unit): DateTime {
    $d = clone $dt;
    $unit = strtolower($unit);
    if ($value <= 0) return $d;
    switch ($unit) {
        case 'minute':
        case 'minutes': $d->modify("-{$value} minutes"); break;
        case 'hour':
        case 'hours':   $d->modify("-{$value} hours");   break;
        case 'day':
        case 'days':    $d->modify("-{$value} days");    break;
        default:        $d->modify("-{$value} minutes");
    }
    return $d;
}

function nextRepeatFrom(?DateTime $lastSent, string $repeat): ?DateTime {
    if (!$lastSent) return null;
    switch (strtolower($repeat)) {
        case 'daily':  return (clone $lastSent)->modify('+1 day');
        case 'weekly': return (clone $lastSent)->modify('+7 days');
        case 'once':   return null;
        case 'until_followed':
            // Default to daily for until_followed; change to hours if you prefer.
            return (clone $lastSent)->modify('+1 day');
        default:       return null;
    }
}

function inWindow(DateTime $now, DateTime $target, int $windowSec): bool {
    $diff = abs($now->getTimestamp() - $target->getTimestamp());
    return $diff <= $windowSec;
}

function isLeadClosed(array $lead): bool {
    // Adjust these according to your CRM states
    $status = strtolower((string)($lead['lead_status'] ?? ''));
    if (in_array($status, ['converted','closed','lost','inactive'], true)) return true;

    // Skip if lead.followup column indicates "No Followup required"
    $followupFlag = (int)$lead['followup'] ?? 0;
    if ($followupFlag !=0)  return true;

    return false;
}

// ---------------------------
// Fetch candidates
// ---------------------------

$sql = "
SELECT
    l.id, l.lead_name, l.lead_contact, l.lead_mail, l.assign_to,
    l.lead_status, l.followup,
    l.follow_up_date, l.follow_up_time,
    l.last_followup_reminder_at
FROM leads l
WHERE l.follow_up_date IS NOT NULL
  AND l.follow_up_time IS NOT NULL
  AND l.follow_up_date <> '0000-00-00' order by l.id desc
";
$res = $db->query($sql);
$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
while ($lead = $res->fetch_assoc()) {
print_r($lead);
// continue;
    // Skip closed / "No Followup required"
    if (isLeadClosed($lead)) continue;

    $eventDT = toDateTime($lead['follow_up_date'], $lead['follow_up_time']);
    if (!$eventDT) continue;

    // First reminder moment
    $firstReminderDT = subtractOffset($eventDT, $offsetValue, $offsetUnit);

    // Determine next reminder target
    $lastSent = !empty($lead['last_followup_reminder_at']) ? new DateTime($lead['last_followup_reminder_at'], new DateTimeZone('Asia/Kolkata')) : null;
    $targetDT = null;

    if (!$lastSent) {
        // We haven't sent anything yet → first reminder time
        $targetDT = $firstReminderDT;
    } else {
        // Handle repeat policy
        $targetDT = nextRepeatFrom($lastSent, $repeat);
    }

    if (!$targetDT) {
        // "once" already sent OR invalid repeat → nothing to do
        continue;
    }

    // For daily/weekly repeats: stop repeating once the event time passes (unless until_followed)
    if ($repeat !== 'until_followed' && $targetDT > $eventDT) {
        continue;
    }

    // // Fire if we are in window (or slightly late but same run)
    // if (!inWindow($now, $targetDT, $FIRE_WINDOW_SEC)) {
    //     // If cron was down and we missed the exact window, you can optionally allow late send:
    //     // if ($now > $targetDT && $now <= ($repeat==='until_followed' ? (clone $targetDT)->modify('+12 hours') : $eventDT)) { /* send anyway */ }
    //     continue;
    // }

    
$leadid=$lead['id'];
$agentID=$lead['assign_to'];
$adminID=fetchAdminIds($db)??[];

// ---------------------------
// Mark last sent (never touch follow_up_date/time!)
// ---------------------------
$stmt = $db->prepare("UPDATE leads SET last_followup_reminder_at = ? WHERE id = ?");
$sentAt = $now->format('Y-m-d H:i:s');
$stmt->bind_param('si', $sentAt, $lead['id']);
$stmt->execute();
$stmt->close();
 $db->commit();

// ---------------------------
$automative_service->onFollowupReminder((int)$lead['id'], (int)$agentID,$adminID);
}

// ---------------------------
// Helpers (stubs) — replace with your actual implementations
// ---------------------------
function fetchAgentById($db, int $id): ?array {
    if ($id <= 0) return null;
    $stmt = $db->prepare("SELECT name, phone, email FROM agents WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $r ?: null;
}
function fetchAdminIds($db): array {
    $ids = [];
    $res = $db->query("SELECT id FROM user WHERE usertype='root'");
    while ($row = $res->fetch_assoc()) {
        $ids[] = (int)$row['id'];
    }
    return $ids;
}

?>