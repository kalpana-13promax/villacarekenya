<?php
require_once __DIR__ . '/../config.php';
class RoundRobinAssigner extends db
{
    protected $mysqli;
    protected $settings = [];
    protected $users = []; // Eligible users for assignment

    public function __construct($mysqli)
    {
        parent::__construct();

        $this->loadSettings();
        $this->loadUsersByRole();
    }

    /**
     * Load round-robin settings from lead_settings
     */
    protected function loadSettings()
    {
        $keys = [
            'enable_round_robin',
            'round_robin_method',
            'chunk_size',
            'round_robin_group', // stores roles, not user IDs
            'round_robin_reset_frequency',
            'notify_on_round_robin',
            'per_day_limit',
            'assignment_rule',
            'assignment_time',
            'enable_reassignment',
            'round_robin_lead_sources',
            'round_robin_include_exclude',
            'lead_response_timeout'
        ];

        $in = implode("' ,'", $keys);

        $stmt = $this->getQuery("SELECT setting_key, setting_value, setting_type FROM lead_settings WHERE setting_key IN ('$in')");


        foreach ($stmt as $key => $val) {



            $value = $val->setting_value ?? '';
            switch ($val->setting_type) {
                case 'boolean':
                    $value = $value === '1';
                    break;
                case 'csv':
                    $value = explode(',', $value);
                    break;
                case 'number':
                    $value = (float) $value;
                    break;
            }
            $this->settings[$val->setting_key] = $value;
        }
    }

    /**
     * Load users based on assigned roles (round_robin_group)
     */
    protected function loadUsersByRole()
    {
        $roles = $this->settings['round_robin_group'] ?? [];
        if (empty($roles))
            return;

        $in = implode("' ,'", $roles);

        $res = $this->getQuery("SELECT u.id FROM user u left join roles r on r.name= u.usertype WHERE r.id IN ('$in') AND status='active'") ?? [];

        $this->users = [];
        foreach ($res as $val) {


            $this->users[] = $val->id;
        }
    }

    /**
     * Main function to assign leads
     */
    public function assignLeads()
    {

        if (empty($this->users) || !$this->settings['enable_round_robin'])
            return;

        if ($this->settings['enable_reassignment']) {
            $this->unassignTimeoutLeads();

        }
        // $this->unassignTimeoutLeads();

        $this->resetCountersIfNeeded();

        $leads = $this->getUnassignedLeads();

        if (empty($leads))
            return;

        $method = $this->settings['round_robin_method'] ?? 'sequential';
        $chunkSize = (int) ($this->settings['chunk_size'] ?? 1);
        $perDayLimit = (int) ($this->settings['per_day_limit'] ?? 0);
        $assignmentRule = $this->settings['assignment_rule'] ?? 'real_time';

        if ($assignmentRule === 'specific_time' && !$this->isAssignmentTime())
            return;

        if ($method === 'sequential') {
            $this->assignSequential($leads, $this->users, $perDayLimit);
        } elseif ($method === 'chunk') {

            $this->assignChunk($leads, $this->users, $chunkSize, $perDayLimit);
        }
    }

    /**
     * Reset counters or assignment if frequency requires
     */
    protected function resetCountersIfNeeded()
    {
        $freq = $this->settings['round_robin_reset_frequency'] ?? 'never';
        if ($freq === 'never')
            return;

        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $monthStart = date('Y-m-01');

        switch ($freq) {
            case 'daily':
                $dateCondition = "DATE(assign_date) < '$today'";
                break;
            case 'weekly':
                $dateCondition = "DATE(assign_date) < '$weekStart'";
                break;
            case 'monthly':
                $dateCondition = "DATE(assign_date) < '$monthStart'";
                break;
            default:
                return;
        }

        $this->mysqli->query("UPDATE leads SET assigned_to = NULL, assign_date = NULL WHERE $dateCondition");
    }

    /**
     * Check if current time matches scheduled assignment time
     */
    protected function isAssignmentTime()
    {


        $scheduledTime = $this->settings['assignment_time'] ?? '10:00'; // "HH:MM"
        $currentTime = date('H:i');

        // Convert to minutes since midnight
        list($sh, $sm) = explode(':', $scheduledTime);
        $scheduledMinutes = ((int) $sh * 60) + (int) $sm;

        list($ch, $cm) = explode(':', $currentTime);
        $currentMinutes = ((int) $ch * 60) + (int) $cm;

        // 5-minute tolerance
        $tolerance = 5;

        if (abs($currentMinutes - $scheduledMinutes) <= $tolerance) {
            return true;
        }
        return false;
    }



    /**
     * Get all unassigned leads
     */
    // protected function getUnassignedLeads()
    // {
    //     // check methodfor elad show_source()
    //     $type = $this->settings['round_robin_include_exclude'] ?? 'include';

    //     $leadSourcesAr = $this->settings['round_robin_lead_sources'] ?? [];
    //     $leadSources = implode("' ,'", $leadSourcesAr) ?? '';
    //     if ($type == 'include' && !empty($leadSourcesAr)) {
    //         $stmt = $this->mysqli->prepare("SELECT id FROM  leads WHERE lead_status='un-attempted' and  assign_to ='' and reference IN ('$leadSources') limit 20");
    //     } elseif ($type == 'exclude' && !empty($leadSourcesAr)) {

    //         $stmt = $this->mysqli->prepare("SELECT id FROM  leads WHERE lead_status='un-attempted' and  assign_to ='' and reference NOT IN ('$leadSources') limit 20");
    //     } else {

    //         $stmt = $this->mysqli->prepare("SELECT id FROM  leads WHERE lead_status='un-attempted' and  assign_to ='' limit 20");
    //     }
    //     $stmt->execute();
    //     $res = $stmt->get_result();
    //     $leads = [];
    //     while ($row = $res->fetch_assoc()) {
    //         $leads[] = $row['id'];
    //     }

    //     $stmt->close();
    //     return $leads;
    // }
    protected function getUnassignedLeads()
{
    $type = $this->settings['round_robin_include_exclude'] ?? 'include';
    $leadSourcesAr = $this->settings['round_robin_lead_sources'] ?? [];

    $query = "SELECT id 
              FROM leads 
              WHERE lead_status = 'un-attempted' 
                AND (assign_to IS NULL OR assign_to = '')";

    // Add filtering by source if applicable
    if (!empty($leadSourcesAr)) {
        $leadSources = implode("','", $leadSourcesAr);

        if ($type === 'include') {
            $query .= " AND reference IN ('$leadSources')";
        } elseif ($type === 'exclude') {
            $query .= " AND reference NOT IN ('$leadSources')";
        }
    }

    $query .= " LIMIT 20";
echo $query;
    $stmt = $this->mysqli->prepare($query);
    $stmt->execute();
    $res = $stmt->get_result();

    $leads = [];
    while ($row = $res->fetch_assoc()) {
        $leads[] = $row['id'];
    }

    $stmt->close();
    return $leads;
}


    /**
     * Assign leads sequentially
     */
    protected function assignSequential($leads, $users, $perDayLimit)
    {
        $index = 0;
        $userCount = count($users);
        $userinfo = $this->getNextUserFromLog($users);
        $index = $userinfo['user_id'];

        foreach ($leads as $leadId) {
            $attempts = 0;

            // Find a user with remaining quota
            while ($attempts < $userCount) {
                $userId = $users[$index % $userCount];
                if ($this->checkUserLimit($userId, $perDayLimit)) {
                    $this->assignLead($leadId, $userId);
                    $index++;
                    break;
                } else {
                    $index++;
                    $attempts++;
                }
            }

            // If no one has quota left → stop assigning
            if ($attempts >= $userCount) {
                break;
            }
        }
    }


    /**
     * Assign leads in chunks
     */
    protected function assignChunk($leads, $users, $chunkSize, $perDayLimit)
    {
        $userInfo = $this->getNextUserFromLog($users);
        $userIndex = array_search($userInfo['user_id'], $users);
        $userCount = count($users);
        $remainingChunk = $userInfo['remaining_chunk'];

        while (!empty($leads)) {
            $attempts = 0;

            // Find a user with remaining quota
            while ($attempts < $userCount) {
                $userId = $users[$userIndex % $userCount];
                if ($this->checkUserLimit($userId, $perDayLimit)) {
                    break;
                }
                $userIndex++;
                $attempts++;
            }

            // Stop if no user has quota left
            if ($attempts >= $userCount) {
                break;
            }

            // Use remaining chunk for this user
            $assignLimit = $remainingChunk > 0 ? $remainingChunk : $chunkSize;
            $assigned = 0;

            while ($assigned < $assignLimit && !empty($leads) && $this->checkUserLimit($userId, $perDayLimit)) {
                $leadId = array_shift($leads);
                $this->assignLead($leadId, $userId);
                $assigned++;
            }

            // Reset remaining chunk for next user
            $remainingChunk = $chunkSize;
            $userIndex++; // move to next user
        }
    }



    private function logAssignment($leadId, $userId, $rule, $source = 'cron', $createdBy = null)
    {

        $data = [
            'lead_id' => $leadId,
            'user_id' => $userId,
            'assignment_rule' => $rule,
            'assignment_source' => $source
        ];

        return $this->insert_qry('round_robin_assignment_log', $data);
    }

    /**
     * Check if user has reached per-day limit
     */
    protected function checkUserLimit($userId, $limit)
    {
        if ($limit <= 0)
            return true;

        $today = date('Y-m-d');
        $stmt = $this->mysqli->prepare("SELECT assigned_count FROM round_robin_user_daily WHERE user_id = ? AND assign_date = ?");
        $stmt->bind_param("is", $userId, $today);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $assigned_today = $res['assigned_count'] ?? 0;
        return $assigned_today < $limit;
    }

    /**
     * Assign lead and increment daily counter
     */
    protected function assignLead($leadId, $userId)
    {
        // Assign lead
        $stmt = $this->mysqli->prepare("UPDATE leads SET assign_to = ?, assign_date = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $userId, $leadId);
        $stmt->execute();
        $stmt->close();

        // Increment daily counter
        $this->incrementUserDailyCount($userId);
        $this->logAssignment($leadId, $userId, $this->settings['round_robin_method'], 'cron');


        if ($this->settings['notify_on_round_robin']) {
            $this->notifyUser($userId, $leadId);
        }
    }

    /**
     * Increment or create daily count
     */
    public function incrementUserDailyCount($userId, $date = null)
    {
        $date = $date ?? date('Y-m-d');
        $stmt = $this->mysqli->prepare("
            INSERT INTO round_robin_user_daily (user_id, assign_date, assigned_count)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE assigned_count = assigned_count + 1
        ");
        $stmt->bind_param("is", $userId, $date);
        $stmt->execute();
        $stmt->close();
    }

    protected function getNextUserFromLog($users)
    {
        $rule = $this->settings['round_robin_method'] ?? 'sequential';
        $chunkSize = (int) ($this->settings['chunk_size'] ?? 1);

        // Get last assigned user
        $lastUserRow = $this->getQuery("SELECT user_id FROM round_robin_assignment_log WHERE assignment_rule = '$rule' ORDER BY id DESC LIMIT 1");
        $lastUserId = !empty($lastUserRow[0]) ? $lastUserRow[0]->user_id : null;

        if ($rule === 'sequential') {
            $index = $lastUserId ? array_search($lastUserId, $users) + 1 : 0;
            return ['user_id' => $users[$index % count($users)], 'remaining_chunk' => 0];
        }

        if ($rule === 'chunk') {
            // Get last chunkSize entries for chunk mode
            $stmt = $this->mysqli->prepare("
            SELECT user_id
            FROM round_robin_assignment_log
            WHERE assignment_rule = 'chunk'
            ORDER BY id DESC
            LIMIT ?
        ");
            $stmt->bind_param("i", $chunkSize);
            $stmt->execute();
            $res = $stmt->get_result();
            $assignments = [];
            while ($row = $res->fetch_assoc()) {
                $assignments[] = $row['user_id'];
            }
            $stmt->close();

            // Count continuous assignments for last user
            $cnt = 0;
            foreach ($assignments as $uid) {
                if ($uid === $lastUserId) {
                    $cnt++;
                } else {
                    break;
                }
            }

            $remaining = max(0, $chunkSize - $cnt);
            if ($remaining > 0) {
                return ['user_id' => $lastUserId, 'remaining_chunk' => $remaining];
            } else {
                $index = $lastUserId ? array_search($lastUserId, $users) + 1 : 0;
                print_r(['user_id' => $users[$index % count($users)], 'remaining_chunk' => $chunkSize]);
                return ['user_id' => $users[$index % count($users)], 'remaining_chunk' => $chunkSize];
            }
        }
    }
    private function unassignTimeoutLeads()
    {
        $timeout = (int) ($this->settings['lead_response_timeout'] ?? 0);
        if ($timeout <= 0)
            return;

        // Find leads assigned but untouched for > timeout minutes
        $sql = "
        SELECT id
        FROM leads 
        WHERE lead_status = 'un-attempted'
          AND assign_to IS NOT NULL
          AND TIMESTAMPDIFF(MINUTE, assign_date, NOW()) > $timeout
    ";
        $res = $this->mysqli->query($sql);
        $leads = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        // print_r($leads);
        // die;
        if (empty($leads))
            return;

        $ids = array_column($leads, 'id');
        $idList = implode(',', array_map('intval', $ids));

        // Step 1: Unassign (back to pool)
        $this->mysqli->query("
        UPDATE leads 
        SET assign_to = '', assign_date = NULL, lead_status = 'un-attempted' 
        WHERE id IN ($idList)
    ");

        // Step 2: Do NOT call roundRobinAssign() here.
        // The next cron run of assignLeads() will automatically pick them up.
    }



    /**
     * Notify user after assignment
     */
    protected function notifyUser($userId, $leadId)
    {
        $assign_name = $this->getQuery("select name from user where  id='$userId'") ?? [];
        $aN = '';
        if (!empty($assign_name)) {
            $aN = $assign_name[0]->name;
        }
        $vars = [];

        $payload = json_encode([
            'lead_id' => $leadId,
            'assign_to' => $userId,


            'vars' => ['company_name' => SITENAME, 'agent_name' => $aN, 'crm_link' => BASEURL, 'assigned_time' => date('D M Y H:i:s a')]
        ]);
        $this->insert_qry('jobs', ['type' => 'lead_assigned', 'payload' => $payload]);
    }
}
