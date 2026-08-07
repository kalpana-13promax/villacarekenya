<?php
date_default_timezone_set('Asia/Kolkata');

class UntouchedLeadReminder
{
    private $automative;
    private $db;
    private $settings = [];
    private $admins = [];

    public function __construct(mysqli $db,  $automative = null)
    {
        $this->db = $db;
        $this->loadSettings();
        $this->loadAdmins();
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

    private function getThresholdSeconds(): int
    {
        $value = (int)($this->settings['untouched_definition_value'] ?? 24);
        $unit  = strtolower($this->settings['untouched_definition_unit'] ?? 'hours');
        switch ($unit) {
            case 'minutes':
                return $value * 60;
            case 'hours':
                return $value * 3600;
            case 'days':
                return $value * 86400;
            default:
                return $value * 3600;
        }
    }

    private function getMinLeads(): int
    {
        return (int)($this->settings['untouched_minimum_threshold'] ?? 1);
    }

    private function getSendChannels(): array
    {
        // Customize if you have another setting for channels, else default:
        return ['whatsapp', 'sms', 'email'];
    }

    public function fetchUntouchedLeads(): array
    {
        $leads = [];
        $sql = "SELECT id, lead_name, lead_contact, lead_mail, lead_status, followup, lead_date, last_followup_reminder_at, update_by 
                FROM leads
                WHERE lead_status IN ('un-attempted','new','public','open') 
                  AND followup != '1'";
        $res = $this->db->query($sql);
        $now = time();
        $threshold = $this->getThresholdSeconds();

        while ($lead = $res->fetch_assoc()) {
            $lastActivity = $lead['last_followup_reminder_at'] ?? $lead['lead_date'];
            $lastTS = strtotime($lead['lead_date']);
            echo "<pre>";
            print_r($lead);

            echo "\nLast Activity TS: $lastTS, Now: $now, Threshold: $threshold\n\n and  = " . ($now - $lastTS) . "\n\n   ";
            // if ($lastTS === false) continue;
            if (($now - $lastTS) >= $threshold) {
                $leads[] = $lead;
            }
        }

        return $leads;
    }
    public function fetchUntouchedLeadsByThreshold(): array
    {
        $sql = "SELECT id, lead_name, lead_contact, lead_mail, lead_status, followup, lead_date, last_followup_reminder_at, update_by 
            FROM leads
            WHERE lead_status IN ('un-attempted','new','public','open')
              AND followup != '1'";
        $res = $this->db->query($sql);

        $leads = [];
        while ($lead = $res->fetch_assoc()) {
            $leads[] = $lead;
        }

        return $leads; // Use count($leads) to compare with minimum threshold
    }

    public function setlast_untouched_report_at()
    {
        $now = date('Y-m-d H:i:s');
        // Check if the row exists
        $res = $this->db->query("SELECT 1 FROM lead_settings WHERE setting_key = 'last_untouched_report_at' LIMIT 1");
        if ($res && $res->num_rows > 0) {
            // Update if exists
            $stmt = $this->db->prepare("UPDATE lead_settings SET setting_value = ? WHERE setting_key = 'last_untouched_report_at'");
            $stmt->bind_param("s", $now);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insert if not exists
            $stmt = $this->db->prepare("INSERT INTO lead_settings (setting_key, setting_value,setting_type) VALUES ('last_untouched_report_at', ?,'datetime')");
            $stmt->bind_param("s", $now);
            $stmt->execute();
            $stmt->close();
        }
    }
   
    public function getlast_untouched_report_at()
    {
        $res = $this->db->query("SELECT setting_value FROM lead_settings WHERE setting_key = 'last_untouched_report_at' LIMIT 1");
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return $row['setting_value'];
        }
        return null;
    }
    public function check(){
        $last_untouched_report_at = $this->settings['last_untouched_report_at'] ?? null;
        if ($last_untouched_report_at) {
            $lastTS = strtotime($last_untouched_report_at);
            if ($lastTS === false) return; // Invalid timestamp
            $now = time();
            $interval = (int)($this->settings['untouched_report_frequency_value'] ?? 24);
            $unit = strtolower($this->settings['untouched_report_frequency_unit'] ?? 'hours');
            switch ($unit) {
                case 'minutes':
                    $intervalSeconds = $interval * 60;
                    break;
                case 'hours':
                    $intervalSeconds = $interval * 3600;
                    break;
                case 'days':
                    $intervalSeconds = $interval * 86400;
                    break;
                default:
                    $intervalSeconds = $interval * 3600;
            }
            if (($now - $lastTS) < $intervalSeconds) {
                // Not enough time has passed since last report
                return false;
            }
            return true;
        }
        return true; // No previous report, so proceed
    }
    public function sendReport()
    {
        if (!((int)$this->settings['enable_untouched_lead_tracking'] ?? 0)) return;

        if(!$this->check()) return;
        $leads = $this->fetchUntouchedLeads();
        $fetchUntouchedLeadsByThreshold = $this->fetchUntouchedLeadsByThreshold();
        print_r($fetchUntouchedLeadsByThreshold);
       
        $var = [

            'lead_count' => count($leads),
            'crm_link' => BASEURL,
            'report_time' => date('d M Y H:i A'),
            'time_frame' => $this->settings['untouched_definition_value'] . ' ' . $this->settings['untouched_definition_unit']


        ];
        if (!count($fetchUntouchedLeadsByThreshold) < $this->getMinLeads()) {

            $var['threshold'] = count($fetchUntouchedLeadsByThreshold);
        }

        if(empty($var['lead_count'])||$var['lead_count']<1) return;
        
        //insertions for job
          $payload = json_encode([
                    'vars'      => $var
                 
                ]);

                $payload = $this->db->real_escape_string($payload);

                $sql = "INSERT INTO jobs (type, payload) VALUES ('untouched', '$payload')";
                $this->db->query($sql);
//////////////////===================================

       
         $this->setlast_untouched_report_at();
       
    }

   
    // public function log($message)
    // {
    //     $logFile = __DIR__ . '../../untouched_lead.log';
    //     $timestamp = date('Y-m-d H:i:s');
    //     file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    // }
}


// // ------------------------- USAGE -------------------------
// require_once __DIR__ . '/../config.php';
// require_once __DIR__ . '/automative_services.php';
// $obj = new AutomationService($_SESSION['admin'] ?? 0);
// error();
// $db = $boj->getConnection(); // mysqli connection

// $reminder = new UntouchedLeadReminder($db, $obj);
// echo "Starting Untouched Lead Reminder Process...\n";
// $reminder->sendReport();
// echo "Process Completed.\n";
