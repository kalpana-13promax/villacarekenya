<?php

@session_start();
// require_once  '../config.php';
// error();
class RoundRobinAssigner extends db
{
    protected $mysqli;
    protected $settings = [];
    protected $users = []; // Eligible users for assignment
    protected $rules = []; // Round robin rules

    public function __construct()
    {
        parent::__construct();
        $this->mysqli = $this->mysqli;
        $this->loadSettings();
        $this->loadRules();
        $this->preloadUserDailyCounts();
        $this->logMessage("RoundRobinAssigner initialized.");
    }

    /**
     * Load round-robin settings from lead_settings
     */
    protected function loadSettings()
    {
        try {

            if (isset($_SESSION['settings']) && $_SESSION['settings']) {
                $this->settings = $_SESSION['settings'];
                return;
            }
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
            $_SESSION['settings'] = $this->settings;
        } catch (Exception $e) {
            $this->logMessage("Exception in loadSettings: " . $e->getMessage());
        }
    }

    /**
     * Load active round robin rules
     */
    protected function loadRules()
    {
        try {

            if (isset($_SESSION['rules']) && $_SESSION['rules']) {
                $this->rules = $_SESSION['rules'];
                return;
            }
            $rules = $this->getQuery("SELECT * FROM round_robin_rules WHERE status = 1") ?? [];

            foreach ($rules as $rule) {
                $this->rules[] = [
                    'id' => $rule->id,
                    'name' => $rule->rule_name,
                    'city_id' => $rule->city_id,
                    'project_id' => $rule->project_id,
                    'sources' => $rule->source ? explode(',', $rule->source) : [],
                    'level' => $rule->level,
                    'user_ids' => $rule->user_ids ? explode(',', $rule->user_ids) : [],
                    'assignment_type' => $rule->assignment_type ?? 'project',
                    'contract_types' => !empty($rule->con_type) ? explode(',', $rule->con_type) : []
                ];
            }
            $_SESSION['rules'] = $this->rules;
        } catch (Exception $e) {
            $this->logMessage("Exception in loadRules: " . $e->getMessage());
        }
    }

    /**
     * Main function to assign leads based on rules
     */
    public function assignLeads()
    {
        try {

            if (!$this->settings['enable_round_robin'] || empty($this->rules)){
                return;}


               
            if ($this->settings['enable_reassignment']) {
               
                $this->unassignTimeoutLeads();
            }

            $this->resetCountersIfNeeded();

            // Process each rule
            foreach ($this->rules as $rule) {
                $leads = $this->getUnassignedLeadsForRule($rule);


             // Skip only for genuine reasons
if (empty($leads)) {
    $this->logMessage("Rule {$rule['id']} skipped: no leads found");
    continue;
}

if (empty($rule['user_ids'])) {
    $this->logMessage("Rule {$rule['id']} skipped: no user_ids configured");
    continue;
}

// Validate users separately
$validUsers = $this->validateUsers($rule['user_ids']);
if (!$validUsers) {
    $this->logMessage("Rule {$rule['id']} skipped: invalid/inactive user(s)", $rule['user_ids']);
    // Optional: you can still assign to other active users instead of skipping
    $rule['user_ids'] = $this->getActiveUsersOnly($rule['user_ids']);
    if (empty($rule['user_ids'])) {
        continue;
    }
}


                $method = $this->settings['round_robin_method'] ?? 'sequential';
                $chunkSize = (int) ($this->settings['chunk_size'] ?? 1);
                $perDayLimit = (int) ($this->settings['per_day_limit'] ?? 0);
                $assignmentRule = $this->settings['assignment_rule'] ?? 'real_time';

                if ($assignmentRule === 'specific_time' && !$this->isAssignmentTime())
                    continue;

                if ($method === 'sequential') {
                    $this->assignSequentialByRule($leads, $rule, $perDayLimit);
                } elseif ($method === 'chunk') {
                    $this->assignChunkByRule($leads, $rule, $chunkSize, $perDayLimit);
                }
            }
        } catch (Exception $e) {
            $this->logMessage("Exception in assignLeads: " . $e->getMessage());
        }
    }

    /**
     * Get unassigned leads for a specific rule
     */
    protected function getUnassignedLeadsForRule($rule)
    {

        try {

            $conditions = [];
            $externalProjects = [];

            // Base condition
            $conditions[] = "l.lead_status = 'un-attempted'";
            $conditions[] = "(l.assign_to IS NULL OR l.assign_to = '')";

            // -------------------------------
            // 1️⃣ PROJECT-BASED ASSIGNMENT
            // -------------------------------
            if (!empty($rule['project_id']) && $rule['project_id'] !== 0) {

                $srcs = !empty($rule['sources']) ? implode(',', $rule['sources']) : '';
                $externalProjects = $this->getExternalProjectIds($rule['project_id'], $srcs);

                if (!empty($externalProjects)) {
                    $findSetConditions = [];
                    foreach ($externalProjects as $ext) {
                        $findSetConditions[] = "FIND_IN_SET('{$this->mysqli->real_escape_string($ext)}', l.cron_id)";
                    }

                    $conditions[] = '(' . implode(' OR ', $findSetConditions) . " OR l.project = '{$rule['project_id']}')";
                } else {
                    // No external projects found, fallback to project ID only
                    $conditions[] = "l.project = '{$rule['project_id']}'";
                }
            }

            // -------------------------------
            // 2️⃣ SOURCE-BASED ASSIGNMENT
            // -------------------------------
            if (!empty($rule['sources']) && $rule['assignment_type'] === 'source') {
                $sourceIds = implode(',', array_map('intval', $rule['sources']));
                $conditions[] = "l.reference IN ($sourceIds)";
            }

            // -------------------------------
            // 3️⃣ BOTH PROJECT + SOURCE ASSIGNMENT
            // -------------------------------
            if($rule['assignment_type'] === 'both'){
                if( !empty($rule['project_id'])){
                    
                $externalProjects = $this->getExternalProjectIds($rule['project_id'], $srcs);
                 // Add external or project
                if (!empty($externalProjects)) {
                    $findSetConditions = [];
                    foreach ($externalProjects as $ext) {
                        $findSetConditions[] = "FIND_IN_SET('{$this->mysqli->real_escape_string($ext)}', l.cron_id)";
                    }
                    $conditions[] = '(' . implode(' OR ', $findSetConditions) . " OR l.project = '{$rule['project_id']}')";
                } else {
                    $conditions[] = "l.project = '{$rule['project_id']}'";
                }
                }
            
                
            
            if (!empty($rule['sources']) ) {
                $srcs = implode(',', $rule['sources']);

                // Add source filter
                $sourceIds = implode(',', array_map('intval', $rule['sources']));
                $conditions[] = "l.reference IN ($sourceIds)";
}
               
            }

            //add contract information
            if (!empty($rule['contract_types'])) {
                $con22 = implode("','", array_map([$this->mysqli, 'real_escape_string'], $rule['contract_types']));

                $conditions[] =  "l.contract in ('$con22')";
            };

            // -------------------------------
            // 4️⃣ BUILD FINAL QUERY
            // -------------------------------
            $whereClause = implode(' AND ', $conditions);
            $sql = "SELECT l.id FROM leads l WHERE $whereClause LIMIT 40";

            // Debugging
            // echo "<pre>$sql</pre>";
            
            $res = $this->mysqli->query($sql);

            if (!$res) {
                $this->logMessage("Failed to fetch unassigned leads for rule {$rule['id']}: " . $this->mysqli->error . 'sql: ' . $sql);
                return [];
            }
            $leads = [];

            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $leads[] = $row['id'];
                }
            }
            return $leads;
        } catch (Exception $e) {
            $this->logMessage("Exception in getUnassignedLeadsForRule: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Get external project IDs from mapping
     */
    protected function getExternalProjectIds($projectId, $srcs)
    {
        try {
            $externalIds = [];
            $stmt = $this->mysqli->prepare("
            SELECT source_id ,external_project_id
            FROM ext_project_map 
            WHERE crm_project_id = ? AND source_id IN ($srcs)
        ");

            if (!$stmt) {
                $this->logMessage("Failed to prepare statement in getExternalProjectIds", $this->mysqli->error);
                return $externalIds;
            }
            $stmt->bind_param("i", $projectId);
            $stmt->execute();
            $res = $stmt->get_result();

            while ($row = $res->fetch_assoc()) {
                $externalIds[] = $row['external_project_id'];
            }
            $stmt->close();
            return $externalIds;
        } catch (Exception $e) {
            $this->logMessage("Exception in getExternalProjectIds: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Validate that users in rule are active
     */
    protected function validateUsers($userIds)
    {
        try {

            if (empty($userIds)) return false;

            $placeholders = implode(',', array_fill(0, count($userIds), '?'));
            $stmt = $this->mysqli->prepare("
            SELECT COUNT(*) as count 
            FROM user 
            WHERE id IN ($placeholders) AND status = 'active'
            ");
            if (!$stmt) {
                $this->logMessage("Failed to prepare statement in validateUsers", $this->mysqli->error);
                return false;
            }

            $types = str_repeat('i', count($userIds));
            $stmt->bind_param($types, ...$userIds);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $res['count'] == count($userIds);
        } catch (Exception $e) {
            $this->logMessage("Exception in validateUsers: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Assign leads sequentially for a specific rule
     */
    protected function assignSequentialByRule($leads, $rule, $perDayLimit)
    {
        try {

            $users = $rule['user_ids'];
            $userCount = count($users);

            // Get last assigned user for this rule
            $lastUser = $this->getLastAssignedUserForRule($rule['id']);
            $startIndex = $lastUser ? (array_search($lastUser, $users) + 1) % $userCount : 0;

            $index = $startIndex;
            $assignedCount = 0;

            foreach ($leads as $leadId) {
                $attempts = 0;

                while ($attempts < $userCount) {
                    $userId = $users[$index % $userCount];

                    if ($this->checkUserLimit($userId, $perDayLimit)) {
                        echo $userId ; echo '-'; echo $perDayLimit; echo "<br>";
                        $this->assignLeadToRule($leadId, $userId, $rule['id']);
                        $index++;
                        $assignedCount++;
                        break;
                    } else {
                        $index++;
                        $attempts++;
                    }
                }

                if ($attempts >= $userCount) {
                    break;
                }
            }
        } catch (Exception $e) {
            $this->logMessage("Exception in assignSequentialByRule: " . $e->getMessage());
        }
    }

    /**
     * Assign leads in chunks for a specific rule
     */
    protected function assignChunkByRule($leads, $rule, $chunkSize, $perDayLimit)
    {
        $users = $rule['user_ids'];
        $userCount = count($users);

        // Get assignment state for this rule
        $assignmentState = $this->getAssignmentStateForRule($rule['id'], $chunkSize);
        $userIndex = $assignmentState['user_index'];
        $remainingChunk = $assignmentState['remaining_chunk'];

        while (!empty($leads)) {
            $attempts = 0;

            // Find user with quota
            while ($attempts < $userCount) {
                $userId = $users[$userIndex % $userCount];
                if ($this->checkUserLimit($userId, $perDayLimit)) {
                    break;
                }
                $userIndex++;
                $attempts++;
            }

            if ($attempts >= $userCount) {
                break;
            }

            // Assign leads to current user
            $assignLimit = $remainingChunk > 0 ? $remainingChunk : $chunkSize;
            $assigned = 0;

            while ($assigned < $assignLimit && !empty($leads) && $this->checkUserLimit($userId, $perDayLimit)) {
                $leadId = array_shift($leads);
                $this->assignLeadToRule($leadId, $userId, $rule['id']);
                $assigned++;
            }

            $remainingChunk = $chunkSize;
            $userIndex++;

            // Save state for next run
            $this->saveAssignmentStateForRule($rule['id'], $userIndex % $userCount, $remainingChunk);
        }
    }

    /**
     * Get last assigned user for a rule
     */
    protected function getLastAssignedUserForRule($ruleId)
    {
        $stmt = $this->mysqli->prepare("
            SELECT user_id 
            FROM round_robin_assignment_log 
            WHERE rule_id = ? 
            ORDER BY id DESC 
            LIMIT 1
        ");

        if (!$stmt) {
            $this->logMessage("Failed to prepare statement in getLastAssignedUserForRule", $this->mysqli->error);
            return null;
        }
        $stmt->bind_param("i", $ruleId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $res['user_id'] ?? null;
    }

    /**
     * Get assignment state for chunk-based rule
     */
    protected function getAssignmentStateForRule($ruleId, $chunkSize)
    {
        try {

            $stmt = $this->mysqli->prepare("
                    SELECT user_index, remaining_chunk 
                    FROM round_robin_rule_state 
                    WHERE rule_id = ?
                    ");
            if (!$stmt) {
                $this->logMessage("Failed to prepare statement in getAssignmentStateForRule", $this->mysqli->error);
                return ['user_index' => 0, 'remaining_chunk' => $chunkSize];
            }
            $stmt->bind_param("i", $ruleId);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            $stmt->close();


            if ($res) {
                return [
                    'user_index' => $res['user_index'],
                    'remaining_chunk' => $res['remaining_chunk']
                ];
            }

            return ['user_index' => 0, 'remaining_chunk' => $chunkSize];
        } catch (Exception $e) {
            $this->logMessage("Exception in getAssignmentStateForRule: " . $e->getMessage());
            return ['user_index' => 0, 'remaining_chunk' => $chunkSize];
        }
    }

    /**
     * Save assignment state for chunk-based rule
     */
    protected function saveAssignmentStateForRule($ruleId, $userIndex, $remainingChunk)
    {
        try {

            $stmt = $this->mysqli->prepare("
                    INSERT INTO round_robin_rule_state (rule_id, user_index, remaining_chunk, updated_at)
                    VALUES (?, ?, ?, NOW())
                    ON DUPLICATE KEY UPDATE 
                    user_index = VALUES(user_index), 
                    remaining_chunk = VALUES(remaining_chunk),
                    updated_at = VALUES(updated_at)
        ");

            $stmt->bind_param("iii", $ruleId, $userIndex, $remainingChunk);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            $this->logMessage("Exception in saveAssignmentStateForRule: " . $e->getMessage());
        }
    }

    /**
     * Assign lead with rule context
     */
    protected function assignLeadToRule($leadId, $userId, $ruleId)
    {
        try {

            $stmt = $this->mysqli->prepare("UPDATE leads SET assign_to = ?, assign_date = NOW() WHERE id = ?");
            $stmt->bind_param("ii", $userId, $leadId);
            $stmt->execute();
            $stmt->close();
        } // Assign lead
        catch (Exception $e) {
            $this->logMessage("Exception in assignLeadToRule: " . $e->getMessage());
        }


        // Increment daily counter
        $this->incrementUserDailyCount($userId);

        $sessionKey = 'assigned_today_' . $userId;
        if (isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey]++;
        } else {
            $_SESSION[$sessionKey] = 1;
        }
        // Log assignment with rule context
        $this->logAssignment($leadId, $userId, $this->settings['round_robin_method'], 'cron', $ruleId);

        if ($this->settings['notify_on_round_robin']) {
            $this->notifyUser($userId, $leadId);
        }
    }

    /**
     * Enhanced log assignment with rule support
     */
    private function logAssignment($leadId, $userId, $rule, $source = 'cron', $ruleId = null)
    {
        try {

            $data = [
                'lead_id' => $leadId,
                'user_id' => $userId,
                'assignment_rule' => $rule,
                'assignment_source' => $source,
                'rule_id' => $ruleId
            ];

            $re = $this->insert_qry('round_robin_assignment_log', $data);
            if (!$re) {
                $this->logMessage("Failed to log assignment" . $this->mysqli->error, $data);
            }
            return $re;
        } catch (Exception $e) {
            $this->logMessage("Exception in logAssignment: " . $e->getMessage());
            return false;
        }
    }

    // Keep all other existing methods (resetCountersIfNeeded, isAssignmentTime, checkUserLimit, 
    // incrementUserDailyCount, unassignTimeoutLeads, notifyUser) as they are...

    /**
     * Reset counters or assignment if frequency requires
     */
    protected function resetCountersIfNeeded()
    {
        $freq = $this->settings['round_robin_reset_frequency'] ?? 'never';
        if ($freq === 'never')
            return;



        // Also reset rule states if needed
        if ($freq === 'daily') {
            $this->mysqli->query("DELETE FROM round_robin_rule_state");
        }
        return;
    }

    /**
     * Check if current time matches scheduled assignment time
     */
    protected function isAssignmentTime()
    {
        try {

            $scheduledTime = $this->settings['assignment_time'] ?? '10:00';
            $currentTime = date('H:i');

            list($sh, $sm) = explode(':', $scheduledTime);
            $scheduledMinutes = ((int) $sh * 60) + (int) $sm;

            list($ch, $cm) = explode(':', $currentTime);
            $currentMinutes = ((int) $ch * 60) + (int) $cm;

            $tolerance = 5;

            if (abs($currentMinutes - $scheduledMinutes) <= $tolerance) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->logMessage("Exception in isAssignmentTime: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user has reached per-day limit
     */

    protected function checkUserLimit($userId, $limit)
    {
        try {

            if ($limit <= 0) return true;

            $sessionKey = 'assigned_today_' . $userId;

            if (isset($_SESSION[$sessionKey])) {
                $assigned_today = $_SESSION[$sessionKey];
                return $assigned_today < $limit;
            }

            $assigned_today = $_SESSION['user_daily_counts'][$userId] ?? 0;
            $_SESSION[$sessionKey] = $assigned_today;
            return $assigned_today < $limit;
        } catch (Exception $e) {
            $this->logMessage("Exception in checkUserLimit: " . $e->getMessage());
            return false;
        }
    }
protected function getActiveUsersOnly($userIds)
{
    $active = [];
    if (empty($userIds)) return $active;
    $placeholders = implode(',', array_fill(0, count($userIds), '?'));
    $stmt = $this->mysqli->prepare("SELECT id FROM user WHERE id IN ($placeholders) AND status='active'");
    $types = str_repeat('i', count($userIds));
    $stmt->bind_param($types, ...$userIds);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $active[] = (int)$row['id'];
    }
    $stmt->close();
    return $active;
}


    protected function preloadUserDailyCounts()
    {
        try {


            if (isset($_SESSION['user_daily_counts']) && $_SESSION['assign_date'] === date('Y-m-d')) {
                return; // already loaded for today
            }

            $today = date('Y-m-d');
            $sql = "SELECT user_id, assigned_count FROM round_robin_user_daily WHERE assign_date = '$today'";
            $res = $this->mysqli->query($sql);
            if (!$res) {
                $this->logMessage("Failed to preload user daily counts: " . $this->mysqli->error);
            }

            $_SESSION['user_daily_counts'] = [];
            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $_SESSION['user_daily_counts'][$row['user_id']] = $row['assigned_count'];
                }
            }

            $_SESSION['assign_date'] = $today;
        } catch (Exception $e) {
            $this->logMessage("Exception in preloadUserDailyCounts: " . $e->getMessage());
        }
    }
    /**
     * Increment or create daily count
     */
    public function incrementUserDailyCount($userId, $date = null)
    {
        try {

            $date = $date ?? date('Y-m-d');
            $stmt = $this->mysqli->prepare("
                   INSERT INTO round_robin_user_daily (user_id, assign_date, assigned_count)
                   VALUES (?, ?, 1)
                   ON DUPLICATE KEY UPDATE assigned_count = assigned_count + 1
                   ");
            if (!$stmt) {
                $this->logMessage("Failed to prepare statement in incrementUserDailyCount", $this->mysqli->error);
                return;
            }
            $stmt->bind_param("is", $userId, $date);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            $this->logMessage("Exception in incrementUserDailyCount: " . $e->getMessage());
        }
    }
    private function unassignTimeoutLeads()
    {
        try {

            $timeout = (int) ($this->settings['lead_response_timeout'] ?? 0);
            if ($timeout <= 0)
                return;

            $sql = "
                    SELECT id
                    FROM leads 
                    WHERE lead_status = 'un-attempted'
                    AND assign_to IS NOT NULL
                    AND TIMESTAMPDIFF(MINUTE, assign_date, NOW()) > $timeout
                    ";
            $res = $this->mysqli->query($sql);
            if (!$res) {
                $this->logMessage("Failed to fetch timeout leads: " . $this->mysqli->error);
                return;
            }
            $leads = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

            if (empty($leads))
                return;

            $ids = array_column($leads, 'id');
            $idList = implode(',', array_map('intval', $ids));

            $r =  $this->mysqli->query("
                    UPDATE leads 
                    SET assign_to = '', assign_date = NULL, lead_status = 'un-attempted' 
        WHERE id IN ($idList)
        ");
            if (!$r) {
                $this->logMessage("Failed to unassign timeout leads: " . $this->mysqli->error);
            }
        } catch (Exception $e) {
            $this->logMessage("Exception in unassignTimeoutLeads: " . $e->getMessage());
        }
    }

    /**
     * Notify user after assignment
     */
    protected function notifyUser($userId, $leadId)
    {
        $this->insertNotification( $userId,               // User who got the lead
    'New Lead Assigned',           // Notification title
    'A new lead has been assigned to you.',  // Description
    "CRM" ,
    $leadId
);
        $assign_name = $this->getQuery("select name from user where id='$userId'") ?? [];
        if (!$assign_name) {
            $this->logMessage("Failed to fetch user name for notification: User ID $userId" . $this->mysqli->error);
        }
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
        $r = $this->insert_qry('jobs', ['type' => 'lead_assigned', 'payload' => $payload]);
        if (!$r) {
            $this->logMessage("Failed to insert notification job for user $userId and lead $leadId: " . $this->mysqli->error);
        }
    }

    /**
     * Universal log writer for RoundRobinAssigner
     * Creates daily log file in /logs/ folder (auto if missing)
     */
    protected function logMessage($message, $context = null)
    {
        try {
            $logDir = __DIR__ . '/logs'; // adjust path if needed
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }

            $file = $logDir . '/round_robin.log';
            $time = date('Y-m-d H:i:s');

            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : 'unknown';
            $fileLine = isset($backtrace[0]['line']) ? " [line {$backtrace[0]['line']}]" : '';

            $contextText = '';
            if ($context !== null) {
                $contextText = is_array($context) ? json_encode($context, JSON_PRETTY_PRINT) : $context;
            }

            $logEntry = "[$time] Function: {$caller}{$fileLine} → {$message}";
            if ($contextText) $logEntry .= " | Context: {$contextText}";
            $logEntry .= PHP_EOL;

            file_put_contents($file, $logEntry, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            // If logging fails, fallback to system log
            error_log("RoundRobinAssigner logging failed: " . $e->getMessage());
        }
    }
    
    public function insertNotification($assignTo, $title, $description, $uploader,$leadId)
{
	try {
		// Sanitize all inputs
		$assignTo   = $this->mysqli->real_escape_string($assignTo);
		$title      = $this->mysqli->real_escape_string($title);
		$description= $this->mysqli->real_escape_string($description);
		$uploader   = $this->mysqli->real_escape_string($uploader);

		// Build insert query
		$sql = "INSERT INTO notification (assign_to, title, description, uploader, lead_id)
				VALUES ('$assignTo', '$title', '$description', '$uploader', '$leadId')";

		// Execute
		if ($this->mysqli->query($sql)) {
			return $this->mysqli->insert_id; // Return last inserted notification ID
		} else {
			throw new Exception("Insert notification failed: " . $this->mysqli->error);
		}
	} catch (Exception $e) {
		error_log("Notification Error: " . $e->getMessage());
		return false;
	}
}

}


        // $rb = new RoundRobinAssigner();
        // try {
        //     echo "starting";
        //     $rb->assignLeads();
        //     echo "\nending";
        // } catch (Exception $e) {
        //     echo 'error' . $e->getMessage() . "\n";
        //     echo $e->getTraceAsString() . "\n";
        // }
