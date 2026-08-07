<?php
date_default_timezone_set('Asia/Kolkata');

class PendingReminder
{
    private $automative;
    private $db;
    private $settings = [];
    private $admins = [];

    public function __construct(mysqli $db, $automative = null)
    {
        $this->db = $db;
        $this->loadSettings();
      
        $this->automative = $automative;
    }

    private function loadSettings()
    {
        $res = $this->db->query("SELECT setting_key, setting_value FROM lead_settings");
        while ($row = $res->fetch_assoc()) {
            $this->settings[$row['setting_key']] = $row['setting_value'];
        }
    }

    private function loadAdmins()
    {
        $res = $this->db->query("SELECT name, contact as phone, mail as email FROM user WHERE usertype='root'");
        while ($row = $res->fetch_assoc()) {
            $this->admins[] = $row;
        }
    }

    private function setLastPendingReportAt($id = null)
    {
        try {
            if ($id) {
                $this->db->query("UPDATE leads SET last_escalation_at = NOW() WHERE id=" . (int)$id);
            }
        } catch (Exception $e) {
            error_log("Failed to update last_escalation_at: " . $e->getMessage());
        }
        // $this->db->query("UPDATE leads SET last_escalation_at = NOW() WHERE id=" . (int)$id);
    }
    // private function getThresholdSeconds(): int
    // {
    //     $value = (int)($this->settings['pending_definition_value'] ?? 24);
    //     $unit  = strtolower($this->settings['pending_definition_unit'] ?? 'hours');
    //     switch ($unit) {
    //         case 'minutes':
    //             return $value * 60;
    //         case 'hours':
    //             return $value * 3600;
    //         case 'days':
    //             return $value * 86400;
    //         default:
    //             return $value * 3600;
    //     }
    // }


    public function fetchPendingLeads(): array
    {
        $hours = $this->settings['escalation_hours'] ?? 24;
        $leads = [];
        $sql = "SELECT l.id as lead_id,  l.lead_date, l.assign_to ,u.name as assigned_person,u.id as user_id
        FROM leads l left join user u on l.assign_to=u.id
        WHERE l.lead_status IN ('un-attempted')
          AND (l.assign_to IS NOT NULL AND l.assign_to != '')
          AND l.lead_date <= NOW() - INTERVAL {$hours} HOUR
          AND (l.last_escalation_at IS NULL OR l.last_escalation_at <= NOW() - INTERVAL {$hours} HOUR)";

        $res = $this->db->query($sql);
        $now = time();

        $leads = $res->fetch_all(MYSQLI_ASSOC);



        return $leads;
    }



    public function sendReport()
    {
        if (!((int)$this->settings['notify_pending_reminder_escalation'] ?? 0)) return;
        if ($this->settings['escalation_hours'] <= 0) return;
        $sendN = $this->settings['send_notification_to_escalation'] ?? 'both';
        $sendToAdmin = true;
        $sendToassignee = true;
        if ($sendN == 'assignee') {
            $sendToAdmin = false;
        } elseif ($sendN == 'admin') {
            $sendToassignee = false;
        }
        $leads = $this->fetchPendingLeads();
        if (count($leads) === 0) {
            echo "No pending leads to send report.\n";
            return;
        }
        // Group leads by assignee
        $assigneeStats = [];
        foreach ($leads as $lead) {
            $assignee = $lead['assigned_person'] ?: 'Unassigned';
            $assign_id = $lead['user_id'] ?: 0;
            if (!isset($assigneeStats[$assign_id])) {
                $assigneeStats[$assign_id] = [
                    'assignee' => $assignee,
                    'pending_count' => 0
                ];
            }
            $assigneeStats[$assign_id]['pending_count']++;

            $this->setLastPendingReportAt((int)$lead['lead_id']);
        }

        $counter = 1;
        // $lead_list = [];
        foreach ($assigneeStats as $user_id => $l) {
            $lead_list[] = $counter . ') 👤 ' . $l['assignee'] . ' (' . $l['pending_count'] . ' Leads)';
            $counter++;
        }
  
        $varAdmin = [
            'lead_count' => count($leads),
            'hours' => $this->settings['escalation_hours'] ?? 24,
            'crm_link' => BASEURL,
            'report_time' => date('d M Y H:i A'),
            'lead_list' => implode("\n", $lead_list)
        ];

        if ($sendToassignee) {

            // for loop send to asingee person if assign to is not null
            foreach ($assigneeStats as $user_id => $l) {
                if ($user_id != 0) {
                    $varAsign = [
                        'lead_count' => $l['pending_count'],
                        'assignee_name' => $l['assignee'],
                        'hours' => $this->settings['escalation_hours'] ?? 24,
                        'crm_link' => BASEURL,
                        'report_time' => date('d M Y H:i A')
                    ];
                    // print_r($var);

                    $this->automative->pendingReminderToAsign((int) $user_id, $varAsign);

                    //insertions for job
          $payload = json_encode([
                    'vars'      => $varAsign,
                    'user_id'=>$user_id
                 
                ]);

                $payload = $this->db->real_escape_string($payload);

                $sql = "INSERT INTO jobs (type, payload) VALUES ('pending_reminder_toAssign', '$payload')";
                $this->db->query($sql);
                }
            }
        }

        if ($sendToAdmin) {
            //insertions for job
          $payload = json_encode([
                    'vars'      => $varAdmin
                 
                ]);

                $payload = $this->db->real_escape_string($payload);

                $sql = "INSERT INTO jobs (type, payload) VALUES ('pending_reminderToAdmin', '$payload')";
                $this->db->query($sql);

         
        }
        $this->setLastPendingReportAt();
    }
}


// // ------------------------- USAGE -------------------------
// require_once __DIR__ . '/../config.php';
// require_once __DIR__ . '/automative_services.php';
// $obj = new AutomationService($_SESSION['admin'] ?? 0);
// error();
// $db = $boj->getConnection(); // mysqli connection

// $reminder = new PendingReminder($db, $obj);
// echo "Starting Pending Lead Reminder Process...\n";
// $reminder->sendReport();
// echo "Process Completed.\n";
