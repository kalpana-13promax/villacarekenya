<?php
class CRMMessenger extends db
{
    // Channel constants
    public const CHANNEL_EMAIL = 'email';
    public const CHANNEL_SMS = 'sms';
    public const CHANNEL_WHATSAPP = 'whatsapp';
    public const CHANNEL_IN_APP = 'in_app';

    private $senderId;
    private $defaultChannel = self::CHANNEL_WHATSAPP;

    /**
     * Constructor (uses parent mysqli connection from db)
     * @param int $senderId ID of the user sending the message
     */
    public function __construct(int $senderId)
    {




        parent::__construct();
        $this->senderId = $senderId;
    }

    /**
     * Set default communication channel
     * @param string $channel Channel type
     */
    public function setDefaultChannel(string $channel): void
    {
        $allowedChannels = [
            self::CHANNEL_EMAIL,
            self::CHANNEL_SMS,
            self::CHANNEL_WHATSAPP,
            self::CHANNEL_IN_APP
        ];
        if (in_array($channel, $allowedChannels, true)) {
            $this->defaultChannel = $channel;
        }
    }


    function getMimeTypeByExtension($filePath)
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav'
        ];
        return $map[$ext] ?? null; // return null if unknown
    }

    /**
     * Send message to lead (raw template string or content)
     */
    public function sendToLead(int $leadId, string $messageTemplate, array $placeholders = [], string $channel = null): bool
    {
        $lead = $this->getLeadDetails($leadId);
        if (!$lead) {
            throw new Exception("Lead not found");
        }
        return $this->sendToContact($lead, $messageTemplate, $placeholders, $channel, 'lead', $leadId, null, []);
    }

    /**
     * Send message to client
     * @param int $clientId
     * @param string $messageTemplate
     * @param array $placeholders
     * @param string|null $channel
     * @return bool
     * @throws Exception
     */
    public function sendToClient(int $clientId, string $messageTemplate, array $placeholders = [], string $channel = null): bool
    {
        $client = $this->getClientDetails($clientId);
        if (!$client) {
            throw new Exception("Client not found");
        }
        return $this->sendToContact($client, $messageTemplate, $placeholders, $channel, 'client', null, $clientId, []);
    }

    /**
     * Send payment notification
     * @param int $clientId
     * @param float $amount
     * @param string $paymentMethod
     * @param string|null $channel
     * @return bool
     * @throws Exception
     */
    public function sendAppNotification(int $leadId, int $userId, int $templateId, array $extraPlaceholders = [], array $option = []): bool
    {
        $template = $this->getTemplate((int) $templateId);
        if (!$template) {
            throw new Exception('Template not found');
        }
        $placeholders = $this->getPlaceholdersForType($templateId, $leadId, null, null);

        $placeholders = array_merge($placeholders, $extraPlaceholders);
        $templateContent = isset($template['body']) && $template['body'] !== '' ? $template['body'] : ($template['content'] ?? '');

        $messageContent = $this->processTemplate($templateContent, $placeholders);
        $subject = isset($option['subject']) ? (string) $option['subject'] : null;


        //  die($messageContent);

        return $this->sendMessage($userId, $messageContent, 'in_app', '', $subject, []);

    }

    /**
     * Private method to handle sending to any contact (lead/client)
     * @param array $contact
     * @param string $messageTemplate
     * @param array $placeholders
     * @param string|null $channel
     * @param string $messageType
     * @param int|null $leadId
     * @param int|null $clientId
     * @return bool
     * @throws Exception
     */
    public function sendToContact(array $contact, string $messageTemplate, array $placeholders, ?string $channel, string $messageType, ?int $leadId, ?int $clientId, array $options = []): bool
    {
        $channel = $channel ?? $this->defaultChannel;
        $recipient = $this->getRecipientByChannel($contact, $channel);
        if (!$recipient || $recipient == null || $recipient == '') {
            throw new Exception("No recipient found for the selected channel: $channel");
        }
        // Validate recipient
        switch ($channel) {
            case self::CHANNEL_EMAIL:
                if (!$this->isValidEmail($recipient)) {
                    throw new Exception("Invalid email address: $recipient");
                }
                break;
            case self::CHANNEL_SMS:
            case self::CHANNEL_WHATSAPP:
                if (!$this->isValidPhone($recipient)) {
                    throw new Exception("Invalid phone number: $recipient");
                }
                break;
            case self::CHANNEL_IN_APP:
                if (!is_numeric($recipient)) {
                    throw new Exception("Invalid user ID for in-app notification: $recipient");
                }
                $recipient = (int) $recipient;
                break;
            default:
                throw new Exception("Unsupported channel: $channel");
        }
        $messageContent = $this->processTemplate($messageTemplate, $placeholders);
        $subject = isset($options['subject']) ? (string) $options['subject'] : null;
        $media = isset($options['media']) ? (string) $options['media'] : null;
        $attachments = isset($options['attachments']) && is_array($options['attachments']) ? $options['attachments'] : [];

        //  die($messageContent);

        $success = $this->sendMessage($recipient, $messageContent, $channel, $media, $subject, $attachments);
        if ($success) {
            $this->logMessage(
                $this->senderId,
                $leadId,
                $clientId,
                $messageType,
                $messageContent,
                $channel
            );
        }
        return $success;
    }


    protected function sendToWhatsAppGroup(
        $groupId,
        string $content,
        array $placeholders = [],
        $leadId = null,
        array $options = []
    ): bool {
        // Resolve placeholders into content
        $messageContent = $this->processTemplate($content, $placeholders);
        $media = isset($options['media']) ? (string) $options['media'] : null;
        $attachments = isset($options['attachments']) && is_array($options['attachments']) ? $options['attachments'] : [];

        // Example group send call (depends on your WhatsApp API)
        $sent = $this->sendWhatsAppGroup($groupId, $messageContent, $media);

        return $sent;
    }

    /**
     * Validate email address
     * @param string $email
     * @return bool
     */
    private function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone number (basic, can be improved for international)
     * @param string $phone
     * @return bool
     */
    private function isValidPhone(string $phone): bool
    {
        // Accepts numbers, spaces, +, -, and parentheses
        return preg_match('/^[0-9\s\-\+\(\)]+$/', $phone) === 1;
    }

    /**
     * Get lead details from database
     * @param int $leadId
     * @return array|null Lead data or null if not found
     */
    private function getLeadDetails(int $leadId): ?array
    {
        $leadId = (int) $leadId;
        $query = "SELECT id, lead_mail AS email, lead_contact AS phone, assign_to AS user_id FROM leads WHERE id = $leadId LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result) {
            return null;
        }
        $row = $result->fetch_assoc();
        return $row ?: null;
    }

    /**
     * Get client details from database
     * @param int $clientId
     * @return array|null Client data or null if not found
     */
    private function getClientDetails(int $clientId): ?array
    {
        $clientId = (int) $clientId;
        $query = "SELECT id, email AS email, contact AS phone FROM clients WHERE id = $clientId LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result) {
            return null;
        }
        $row = $result->fetch_assoc();
        if ($row && !isset($row['user_id'])) {
            $row['user_id'] = $row['id'];
        }
        return $row ?: null;
    }

    /**
     * Get recipient address based on channel
     * @param array $contact Contact/lead/client data
     * @param string $channel Communication channel
     * @return string Recipient address
     */
    private function getRecipientByChannel(array $contact, string $channel): string
    {
        switch ($channel) {
            case 'sms':
                return $contact['phone'];
            case 'whatsapp':
                return $contact['phone']; // Might need international format
            case 'in_app':
                return $contact['user_id']; // For in-app notifications
            case 'email':
            default:
                return $contact['email'];
        }
    }

    /**
     * Process message template with placeholders
     * @param string $template Template name or content
     * @param array $placeholders Key-value pairs for replacement
     * @return string Processed message content
     */
    private function processTemplate(string $template, array $placeholders): string
    {
        // If template is a registered template name, fetch from database
        // if (!$this->isContentHtml($template)) {
        // $templateContent = $this->getTemplateFromDb($template);
        // if ($templateContent) {
        // $template = $templateContent;
        // }
        // }

        // Replace placeholders
        foreach ($placeholders as $key => $value) {
            $template = str_replace("{{{$key}}}", $value, $template);
        }
        $template = preg_replace('/{{[^}]+}}/', 'N/A', $template);
        return $template;
    }

    /**
     * Get template content from database
     * @param string $templateName
     * @return string|null Template content or null if not found
     */
    private function getTemplateFromDb(string $templateName): ?string
    {
        $safeName = $this->sanitize($templateName);
        $query = "SELECT body FROM templates WHERE name = '$safeName' LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result) {
            return null;
        }
        $row = $result->fetch_assoc();
        return $row['body'] ?? null;
    }

    /**
     * Check if content is HTML
     * @param string $content
     * @return bool
     */
    private function isContentHtml(string $content): bool
    {
        return $content !== strip_tags($content);
    }

    // ===== New: Universal Template-based API =====
    public function sendToLeadByTemplate(int $leadId, int $templateId, array $extraPlaceholders = [], array $options = []): bool
    {
        $lead = $this->getLeadDetails($leadId);
        if (!$lead) {
            throw new Exception('Lead not found');
        }
        $template = $this->getTemplate((int) $templateId);
        if (!$template) {
            throw new Exception('Template not found');
        }
        $placeholders = $this->getPlaceholdersForType($templateId, $leadId, null, null);

        $placeholders = array_merge($placeholders, $extraPlaceholders);
        $templateContent = isset($template['body']) && $template['body'] !== '' ? $template['body'] : ($template['content'] ?? '');
        // Auto-add subject from template if present
        if (!empty($template['subject'])) {
            $options = array_merge(['subject' => $template['subject']], $options);
        }
        return $this->sendToContact(
            $lead,
            $templateContent,
            $placeholders,
            $template['channel'] ?? null,
            'lead',
            $leadId,
            null,
            $options
        );
    }

    public function sendToAgentByTemplate(int $leadId, int $userId, int $templateId, array $extraPlaceholders = [], array $options = []): bool
    {
        if ($userId <= 0) {
            throw new Exception('Invalid user ID');
        }
        $user = $this->getUserDetails($userId);
        if (!$user) {
            throw new Exception('User not found');
        }
        $template = $this->getTemplate((int) $templateId);
        // print_r($template);
        // die;
        if (!$template) {
            throw new Exception('Template not found');
        }
        $placeholders = $this->getPlaceholdersForType((int) $templateId, $leadId, $userId, null);

        $placeholders = array_merge($placeholders, $extraPlaceholders);
        $templateContent = isset($template['body']) && $template['body'] !== '' ? $template['body'] : ($template['content'] ?? '');
        if (!empty($template['subject'])) {
            $options = array_merge(['subject' => $template['subject']], $options);
        }

        return $this->sendToContact(
            $user,
            $templateContent,
            $placeholders,
            $template['channel'] ?? null,
            'agent_assignment',
            $leadId,
            null,
            $options
        );
    }

    public function sendToClientByTemplate(int $clientId, int $templateId, array $extraPlaceholders = [], array $options = []): bool
    {
        $client = $this->getClientDetails($clientId);
        if (!$client) {
            throw new Exception('Client not found');
        }
        $template = $this->getTemplate((int) $templateId);
        if (!$template) {
            throw new Exception('Template not found');
        }
        $placeholders = $this->getPlaceholdersForType('client', null, null, $clientId);
        $placeholders = array_merge($placeholders, $extraPlaceholders);
        $templateContent = isset($template['body']) && $template['body'] !== '' ? $template['body'] : ($template['content'] ?? '');
        if (!empty($template['subject'])) {
            $options = array_merge(['subject' => $template['subject']], $options);
        }
        return $this->sendToContact(
            $client,
            $templateContent,
            $placeholders,
            $template['channel'] ?? null,
            'client',
            null,
            $clientId,
            $options
        );
    }

    public function sendBulkToLeadsByTemplate(array $leadIds, int $templateId, array $extraPlaceholders = [], array $options = []): array
    {
        $results = [];
        foreach ($leadIds as $leadId) {
            $leadId = (int) $leadId;
            $ok = false;
            try {
                $ok = $this->sendToLeadByTemplate($leadId, $templateId, $extraPlaceholders, $options);
            } catch (Exception $e) {
                $ok = false;
            }
            $results[$leadId] = $ok;
        }
        return $results;
    }

    private function getTemplate(int $templateId): ?array
    {
        $templateId = (int) $templateId;
        $query = "SELECT id, title as name, type, channel, body   FROM templates WHERE id = $templateId LIMIT 1";
        $res = $this->mysqli->query($query);
        if (!$res) {
            return null;
        }
        $row = $res->fetch_assoc();
        return $row ?: null;
    }

    private function getUserDetails(int $userId): ?array
    {
        $userId = (int) $userId;

        // Assuming columns: id, contact (phone), mail (email)
        $query = "SELECT id, contact AS phone, mail AS email FROM user WHERE id = $userId LIMIT 1";
        $result = $this->mysqli->query($query);
        if (!$result) {
            return null;
        }
        $row = $result->fetch_assoc();
        if ($row && !isset($row['user_id'])) {
            $row['user_id'] = $row['id'];
        }
        return $row ?: null;
    }

    private function getPlaceholdersForType(int $templateId, ?int $leadId, ?int $userId, ?int $clientId): array
    {
        $placeholders = [];

        // 1) load placeholder metadata for this template
        $temid = $this->int($templateId);
        $q = "SELECT plh.name AS placeholder_key, plh.entity AS entity, plh.source AS source
              FROM template_placeholders tmpl
              INNER JOIN placeholders plh ON plh.id = tmpl.placeholder_id
              WHERE tmpl.template_id = $temid";
        $res = $this->mysqli->query($q);

        if (!$res)
            return $placeholders;

        $rows = $res->fetch_all(MYSQLI_ASSOC);

        // 2) group sources by simple and relation types
        $simpleSources = [];   // table => field => [placeholder_keys]
        $relationSources = []; // leftTable => [ ['left_field','right_table','right_field','key'] , ... ]

        foreach ($rows as $row) {
            $key = $row['placeholder_key'];
            $src = trim($row['source']);
            if ($src === '')
                continue;

            if (strpos($src, '->') !== false) {
                [$left, $right] = explode('->', $src, 2);
                $left = trim($left);
                $right = trim($right);
                $leftParts = explode('.', $left, 2);
                $rightParts = explode('.', $right, 2);
                if (count($leftParts) === 2 && count($rightParts) === 2) {
                    $leftTable = trim(strtolower($leftParts[0]));
                    $leftField = trim($leftParts[1]);
                    $rightTable = trim($rightParts[0]);
                    $rightField = trim($rightParts[1]);
                    $relationSources[$leftTable][] = [
                        'left_field' => $leftField,
                        'right_table' => $rightTable,
                        'right_field' => $rightField,
                        'key' => $key
                    ];
                } else {
                    // fallback to simple parse
                    $parts = explode('.', $src, 2);
                    if (count($parts) === 2) {
                        $table = trim(strtolower($parts[0]));
                        $field = trim($parts[1]);
                        $simpleSources[$table][$field][] = $key;
                    }
                }
            } else {
                $parts = explode('.', $src, 2);
                if (count($parts) === 2) {
                    $table = trim(strtolower($parts[0]));
                    $field = trim($parts[1]);
                    $simpleSources[$table][$field][] = $key;
                }
            }
        }

        // Helper: map a logical table name to (idField, idValue)
        $getIdForTable = function (string $table) use ($leadId, $userId, $clientId) {
            if ($table === 'leads')
                return ['id', $leadId];
            if ($table === 'user' || $table === 'users')
                return ['id', $userId];
            if ($table === 'clients' || $table === 'client')
                return ['id', $clientId];
            return [null, null];
        };

        // 3) batch fetch simple sources: one SELECT per table
        foreach ($simpleSources as $table => $fieldsMap) {
            [$idField, $idValue] = $getIdForTable($table);
            if (!$idField || !$idValue)
                continue;

            $safeTable = $this->sanitize($table);
            $safeFields = [];
            foreach (array_keys($fieldsMap) as $f) {
                $safeFields[] = '`' . preg_replace('/[^a-zA-Z0-9_]/', '', $f) . '`';
            }
            $sql = "SELECT " . implode(',', $safeFields) . " FROM `" . $safeTable . "` WHERE `" . $idField . "` = " . intval($idValue) . " LIMIT 1";
            $r = $this->mysqli->query($sql);
            if (!$r)
                continue;
            $d = $r->fetch_assoc();
            if (!$d)
                continue;
            foreach ($fieldsMap as $field => $keys) {
                $val = $d[$field] ?? null;
                if ($val !== null) {
                    foreach ($keys as $k)
                        $placeholders[$k] = $val;
                }
            }
        }

        // 4) handle relations: for each left table, fetch left fields then referenced rows in batch
        foreach ($relationSources as $leftTable => $relations) {
            [$leftIdField, $leftIdValue] = $getIdForTable($leftTable);
            if (!$leftIdField || !$leftIdValue)
                continue;

            $safeLeftTable = $this->sanitize($leftTable);
            $leftFields = array_unique(array_map(function ($r) {
                return $r['left_field'];
            }, $relations));
            $safeLeftFields = array_map(function ($f) {
                return '`' . preg_replace('/[^a-zA-Z0-9_]/', '', $f) . '`';
            }, $leftFields);
            $sql1 = "SELECT " . implode(',', $safeLeftFields) . " FROM `" . $safeLeftTable . "` WHERE `" . $leftIdField . "` = " . intval($leftIdValue) . " LIMIT 1";
            $r1 = $this->mysqli->query($sql1);
            if (!$r1)
                continue;
            $row1 = $r1->fetch_assoc();
            if (!$row1)
                continue;

            // collect fks per referenced table/field
            $fkMap = []; // refTable => refField => fkValue => [keys]
            foreach ($relations as $rel) {
                $lf = $rel['left_field'];
                $fk = $row1[$lf] ?? null;
                if ($fk === null || $fk === '')
                    continue;
                $refTable = $rel['right_table'];
                $refField = $rel['right_field'];
                $key = $rel['key'];
                $fkMap[$refTable][$refField][intval($fk)][] = $key;
            }

            foreach ($fkMap as $refTable => $fieldsByRef) {
                $safeRefTable = $this->sanitize($refTable);
                foreach ($fieldsByRef as $refField => $idsMap) {
                    $ids = array_keys($idsMap);
                    if (empty($ids))
                        continue;
                    $safeField = '`' . preg_replace('/[^a-zA-Z0-9_]/', '', $refField) . '`';
                    $sql2 = "SELECT `id`, " . $safeField . " FROM `" . $safeRefTable . "` WHERE `id` IN (" . implode(',', array_map('intval', $ids)) . ")";
                    $r2 = $this->mysqli->query($sql2);
                    if (!$r2)
                        continue;
                    while ($rrow = $r2->fetch_assoc()) {
                        $val = $rrow[$refField] ?? null;
                        $id = intval($rrow['id']);
                        if ($val === null)
                            continue;
                        foreach ($idsMap[$id] as $k) {
                            $placeholders[$k] = $val;
                        }
                    }
                }
            }
        }

        // 5) fallback: if any placeholders are still missing, resolve individually
        foreach ($rows as $row) {
            $k = $row['placeholder_key'];
            if (isset($placeholders[$k]))
                continue;
            $src = trim($row['source']);
            $val = $this->resolvePlaceholder($src, $leadId, $userId, $clientId);
            if ($val != null)
                $placeholders[$k] = $val;
        }

        return $placeholders;
    }


    private function resolvePlaceholder(string $source, ?int $leadId, ?int $userId, ?int $clientId): ?string
    {

        // Support two formats:
        // 1) 'table.field' (existing behaviour)
        // 2) 'table.field->ref_table.ref_field' (new): read table.field (should contain an id),
        //    then lookup ref_table.ref_field WHERE id = that id.

        // Relation syntax
        if (strpos($source, '->') !== false) {
            [$left, $right] = explode('->', $source, 2);
            $left = trim($left);
            $right = trim($right);
            // left must be table.field; right must be refTable.refField
            $leftParts = explode('.', $left, 2);
            $rightParts = explode('.', $right, 2);
            if (count($leftParts) !== 2 || count($rightParts) !== 2) {
                return null;
            }
            list($leftTable, $leftField) = $leftParts;
            list($refTable, $refField) = $rightParts;
            $leftTable = trim(strtolower($leftTable));
            $leftField = preg_replace('/[^a-zA-Z0-9_]/', '', trim($leftField));
            $refTable = trim($refTable);
            $refField = preg_replace('/[^a-zA-Z0-9_]/', '', trim($refField));

            // Resolve which id to use based on leftTable
            $idField = null;
            $idValue = null;
            if ($leftTable === 'leads' && $leadId) {
                $idField = 'id';
                $idValue = (int) $leadId;
            } elseif ($leftTable === 'user' && $userId) {
                $idField = 'id';
                $idValue = (int) $userId;
            } elseif ($leftTable === 'clients' && $clientId) {
                $idField = 'id';
                $idValue = (int) $clientId;
            }
            if (!$idField) {
                return null;
            }

            // First read the left field value (expected to be an id referencing refTable)
            $q1 = "SELECT `$leftField` AS v FROM `$leftTable` WHERE `$idField` = $idValue LIMIT 1";
            $r1 = $this->mysqli->query($q1);

            if (!$r1) {
                return null;
            }
            $row1 = $r1->fetch_assoc();
            $fk = $row1['v'] ?? null;
            if ($fk === null || $fk === '') {
                return null;
            }

            // Now query the referenced table for the requested field using the FK
            $fkVal = (int) $fk;
            $q2 = "SELECT `$refField` AS v FROM `$refTable` WHERE `id` = $fkVal LIMIT 1";
            $r2 = $this->mysqli->query($q2);
            if (!$r2) {
                return null;
            }
            $row2 = $r2->fetch_assoc();
            return $row2['v'] ?? null;
        }

        // Basic resolver: supports patterns like 'leads.field', 'users.field', 'clients.field'
        $parts = explode('.', $source, 2);
        if (count($parts) !== 2) {
            return null;
        }
        list($table, $field) = $parts;
        $table = trim(strtolower($table));
        $field = trim($field);
        $idField = null;
        $idValue = null;
        if ($table === 'leads' && $leadId) {
            $idField = 'id';
            $idValue = (int) $leadId;
        } elseif ($table === 'user' && $userId) {
            $idField = 'id';
            $idValue = (int) $userId;
        } elseif ($table === 'clients' && $clientId) {
            $idField = 'id';
            $idValue = (int) $clientId;
        } elseif ($table === 'company') {
        }
        if (!$idField) {
            return null;
        }
        $fieldSafe = preg_replace('/[^a-zA-Z0-9_]/', '', $field);
        $query = "SELECT `$fieldSafe` AS v FROM `$table` WHERE `$idField` = $idValue LIMIT 1";
        $res = $this->mysqli->query($query);
        if (!$res) {
            return null;
        }
        $row = $res->fetch_assoc();
        return $row['v'] ?? null;
    }
    /**
     * Actually send the message via appropriate channel
     * @param string $recipient Recipient address
     * @param string $content Message content
     * @param string $channel Communication channel
     * @return bool Success status
     */
    private function sendMessage(string $recipient, string $content, string $channel, ?string $media = null, ?string $subject = null, array $attachments = []): bool
    {
        // Implement actual sending logic for each channel
        switch ($channel) {
            case 'email':
                return $this->sendEmail($recipient, $content, $subject, $attachments);
            case 'sms':
                return $this->sendSMS($recipient, $content);
            case 'whatsapp':
                return $this->sendWhatsApp($recipient, $content, $media);
            case 'in_app':
                return $this->sendInAppNotification($recipient, $content, $subject);
            default:
                throw new Exception("Unsupported channel: $channel");
        }
    }

    public function sendEmail(string $toEmail, string $content, ?string $subject = null, array $attachments = []): bool
    {
        $subject = $subject ?: "Message from your CRM";
        $tmpFiles = [];
        try {
            // Fetch mail/SMS type from DB
            $cfgRes = $this->getQuery("SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1");
            $cfg = !empty($cfgRes) ? (array) $cfgRes[0] : [];

            $type = strtolower($cfg['type'] ?? 'mail'); // mail or smtp
            // Determine From Email & From Name
            if ($type === 'smtp') {
                $fromEmail = $cfg['from_email'] ?? 'no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost');
                $fromName = $cfg['from_name'] ?? 'CRM System';
            } else {
                // type = mail
                $fromEmail = $cfg['from_email'] ?? (defined('EMAIL') ? EMAIL : 'no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
                $fromName = $cfg['from_name'] ?? 'CRM System';
            }

            // If type mail → use PHP mail()
            if ($type === 'mail') {
                try {

                    // Prepare headers
                    $fromEmail = filter_var($fromEmail, FILTER_VALIDATE_EMAIL) ? $fromEmail : 'no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost');
                    $fromName = $fromName ?: 'CRM System';

                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
                    $headers .= "Reply-To: {$fromEmail}\r\n";
                    $headers .= "Return-Path: {$fromEmail}\r\n"; // Helps with bounces
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    $headers .= "X-Priority: 3\r\n"; // Normal priority
                    $headers .= "X-MSMail-Priority: Normal\r\n";

                    // Send mail

                    $result = mail($toEmail, $subject, $content, $headers, "-f{$fromEmail}");
                    if (!$result) {
                        return false;
                    }

                    return $result;
                } catch (Exception $e) {
                    $this->error_log("PHP mail() failed to send to {$toEmail} . Error: " . $e->getMessage());
                    $this->error_log("getTraceAsString: " . $e->getTraceAsString());
                    $this->error_log("getFile: " . $e->getFile() . " Line: " . $e->getLine());
                    return false;
                }

            }


            // Type SMTP → use PHPMailer
            $phpmailerPath = __DIR__ . '/PHPMailer/src';
            if (!file_exists($phpmailerPath . '/PHPMailer.php')) {
                $phpmailerPath = __DIR__ . '/../PHPMailer-6.10.0/src';
            }
            if (!file_exists($phpmailerPath . '/PHPMailer.php')) {
                $this->error_log('PHPMailer not found.');
                return false;
            }


            require_once $phpmailerPath . '/Exception.php';
            require_once $phpmailerPath . '/PHPMailer.php';
            require_once $phpmailerPath . '/SMTP.php';

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = $cfg['smtp_host'] ?? 'localhost';
            $mail->SMTPAuth = true;
            $mail->Username = $cfg['smtp_user'] ?? '';
            $mail->Password = $cfg['smtp_pass'] ?? '';

            $secure = strtolower(trim($cfg['smtp_secure'] ?? ''));
            $port = (int) ($cfg['smtp_port'] ?? 587);

            if ($secure === 'ssl' && $port === 587)
                $secure = 'tls';
            if ($secure === 'tls' || $secure === 'ssl')
                $mail->SMTPSecure = $secure;

            $mail->Port = $port;

            // From Email & Name
            $mail->setFrom($fromEmail, $fromName);

            $mail->addAddress($toEmail);
            $mail->Subject = $subject;

            if ($this->isContentHtml($content)) {
                $mail->isHTML(true);
                $mail->Body = $content;
                $mail->AltBody = trim(html_entity_decode(strip_tags($content)));
            } else {
                $mail->isHTML(false);
                $mail->Body = $content;
            }

            // Attachments
            foreach ($attachments as $att) {
                if (filter_var($att, FILTER_VALIDATE_URL)) {
                    $tmp = tempnam(sys_get_temp_dir(), 'crm_att_');
                    $bin = @file_get_contents($att);
                    if ($bin !== false) {
                        file_put_contents($tmp, $bin);
                        $tmpFiles[] = $tmp;
                        $mail->addAttachment($tmp, basename(parse_url($att, PHP_URL_PATH)) ?: 'attachment');
                    }
                } elseif (is_file($att)) {
                    $mail->addAttachment($att);
                }
            }

            // Send email
            if (!$mail->send()) {
                $this->error_log('PHPMailer failed: ' . $mail->ErrorInfo);
                // Fallback to PHP mail()
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
                return @mail($toEmail, $subject, $content, $headers);
            }

            return true;
        } catch (Exception $ex) {
            $this->error_log('sendEmail exception: ' . $ex->getMessage());
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
            return @mail($toEmail, $subject, $content, $headers);
        } finally {
            if (!empty($tmpFiles)) {
                foreach ($tmpFiles as $f) {
                    if (file_exists($f))
                        @unlink($f);
                }
            }
        }
    }

    public function sendWhatsApp(string $phone, string $content, ?string $media = null): bool
    {

        $accRes = $this->mysqli->query("
        SELECT wu.username,wa.api_key,wu.base_url FROM whatsapp_users wu  join  whatsapp_accounts wa  on wu.id=wa.user_id
        WHERE wu.status = 'connected'
        ORDER BY wa.id DESC 
        LIMIT 1
    ");


        $cfg = null;
        if ($accRes && $accRes->num_rows > 0) {

            $cfg = $accRes->fetch_assoc();
            $base = rtrim($cfg['base_url'] ?? '', '/');
            $token = $cfg['api_key'] ?? '';
            $username = $cfg['username'] ?? '';
            $base = $base . '/message/send?username=' . urlencode($username);
        } else {
            // 2. Fallback: load from `api` table
            $apiRes = $this->mysqli->query("SELECT * FROM api WHERE name = 'whatsapp' AND api_status = '1' LIMIT 1");
            if (!$apiRes || $apiRes->num_rows === 0) {
                $this->error_log('WhatsApp API config not found in whatsapp_accounts or api table');
                return false;
            }
            $cfg = $apiRes->fetch_assoc();
            $base = rtrim($cfg['endpoint'] ?? 'http://wapi.itways.in', '/');
            $token = $cfg['token'] ?? ($cfg['api_key'] ?? '');
        }

        if (empty($base) || empty($token)) {
            $this->error_log('WhatsApp API missing endpoint or token');
            return false;
        }

        $endpoint = $base; // webhook_url or endpoint
        $messageText = isset($content) ? html_entity_decode(strip_tags($content)) : '';
        $hasMedia = !empty($media);

        $curl = curl_init();
        $headers = [];

        if ($hasMedia) {
            $postFields = [
                'token' => $token,
                'to' => $phone,
            ];
            if ($messageText !== '') {
                $postFields['message'] = $messageText;
            }

            $mediaFile = null;
            if (filter_var($media, FILTER_VALIDATE_URL)) {
                $tmp = tempnam(sys_get_temp_dir(), 'wa_');
                $bin = @file_get_contents($media);
                if ($bin !== false) {
                    file_put_contents($tmp, $bin);
                    $mime = function_exists('mime_content_type') ? mime_content_type($tmp) : 'application/octet-stream';
                    $mediaFile = new CURLFile($tmp, $mime, basename(parse_url($media, PHP_URL_PATH)) ?: 'media');
                }
            } elseif (is_string($media) && file_exists($media)) {
                $mime = function_exists('mime_content_type') ? mime_content_type($media) : 'application/octet-stream';
                $mediaFile = new CURLFile($media, $mime, basename($media));
            }

            if ($mediaFile instanceof CURLFile) {
                $postFields['media'] = $mediaFile;
                curl_setopt_array($curl, [
                    CURLOPT_URL => $endpoint,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postFields,
                    CURLOPT_SSL_VERIFYPEER => false,  // 🔴 disable peer verification
                    CURLOPT_SSL_VERIFYHOST => false,  // 🔴 disable host verification
                ]);
            } else {
                $hasMedia = false; // fallback to text-only
            }
        }

        if (!$hasMedia) {
            $payload = [
                'token' => $token,
                'to' => $phone,
            ];
            if ($messageText !== '') {
                $payload['message'] = $messageText;
            }
            $json = json_encode($payload);
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 50,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,  // 🔴 disable peer verification
                CURLOPT_SSL_VERIFYHOST => false,  // 🔴 disable host verification
            ]);
        }

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($httpCode === 200 && $response !== false) {
            return true;
        }
        $this->error_log("WhatsApp API failed: HTTP $httpCode Error: $curlError Resp: $response");
        return false;
    }

    private function sendWhatsAppGroup(string $groupId, string $content = '', ?string $media = null, int $delaySeconds = 1): bool
    {
        // 1. Always get WhatsApp account config from session-based accounts
        $accRes = $this->mysqli->query("
        SELECT wu.username,wa.api_key,wu.base_url FROM whatsapp_users wu  join  whatsapp_accounts wa  on wu.id=wa.user_id
        WHERE wu.status = 'connected'
        ORDER BY wa.id DESC 
        LIMIT 1
    ");


        if (!$accRes || $accRes->num_rows === 0) {
            $this->error_log('WhatsApp group API config not found in whatsapp_accounts');
            return false;
        }

        $cfg = $accRes->fetch_assoc();
        $base = rtrim($cfg['base_url'] ?? '', '/');
        $token = $cfg['api_key'] ?? '';
        $username = $cfg['username'] ?? '';

        if (empty($base) || empty($token) || empty($username)) {
            $this->error_log('WhatsApp group API missing endpoint, token or username');
            return false;
        }

        // 2. Build endpoint for group messages
        $endpoint = $base . "/message/group?username=" . urlencode($username);

        $curl = curl_init();
        $headers = [];
        $messageText = isset($content) ? html_entity_decode(strip_tags($content)) : '';
        $hasMedia = !empty($media);

        if ($hasMedia) {
            // Multipart with file
            $postFields = [
                'token' => $token,
                'groupId' => $groupId,
                'delaySeconds' => $delaySeconds,
            ];
            if ($messageText !== '') {
                $postFields['message'] = $messageText;
            }

            $mediaFile = null;
            if (filter_var($media, FILTER_VALIDATE_URL)) {
                $tmp = tempnam(sys_get_temp_dir(), 'wa_');
                $bin = @file_get_contents($media);
                if ($bin !== false) {
                    file_put_contents($tmp, $bin);
                    $mime = function_exists('mime_content_type') ? mime_content_type($tmp) : 'application/octet-stream';
                    $mediaFile = new CURLFile($tmp, $mime, basename(parse_url($media, PHP_URL_PATH)) ?: 'media');
                }
            } elseif (is_string($media) && file_exists($media)) {
                $mime = function_exists('mime_content_type') ? mime_content_type($media) : 'application/octet-stream';
                $mediaFile = new CURLFile($media, $mime, basename($media));
            }

            if ($mediaFile instanceof CURLFile) {
                $postFields['media'] = $mediaFile;
                curl_setopt_array($curl, [
                    CURLOPT_URL => $endpoint,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postFields,
                ]);
            } else {
                $hasMedia = false; // fallback to text-only
            }
        }

        if (!$hasMedia) {
            $payload = [
                'token' => $token,
                'groupId' => $groupId,
                'delaySeconds' => $delaySeconds,
            ];
            if ($messageText !== '') {
                $payload['message'] = $messageText;
            }
            $json = json_encode($payload);
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 50,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => $headers,
            ]);
        }

        // 3. Execute request
        $response = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($httpCode === 200 && $response !== false) {
            $res = json_decode($response, true);
            if (!empty($res['success'])) {
                return true;
            }
            $this->error_log("WhatsApp Group API error: " . $response);
            return false;
        }

        $this->error_log("WhatsApp Group API failed: HTTP $httpCode Error: $curlError Resp: $response");
        return false;
    }

    // private function sendWhatsAppChannel(string $channelId, string $content = '', ?string $media = null, int $delaySeconds = 1): bool
    // {
    //     // 1. Always get WhatsApp account config from session-based accounts
    //     $accRes = $this->mysqli->query("
    //     SELECT wu.username,wa.api_key,wu.base_url FROM whatsapp_users wu  join  whatsapp_accounts wa  on wu.id=wa.user_id
    //     WHERE wu.status = 'connected'
    //     ORDER BY wa.id DESC 
    //     LIMIT 1
    // ");


    //     if (!$accRes || $accRes->num_rows === 0) {
    //         $this->error_log('WhatsApp group API config not found in whatsapp_accounts');
    //         return false;
    //     }

    //     $cfg = $accRes->fetch_assoc();
    //     $base     = rtrim($cfg['base_url'] ?? '', '/');
    //     $token    = $cfg['api_key'] ?? '';
    //     $username = $cfg['username'] ?? '';

    //     if (empty($base) || empty($token) || empty($username)) {
    //         $this->error_log('WhatsApp group API missing endpoint, token or username');
    //         return false;
    //     }

    //     // 2. Build endpoint for group messages
    //     $endpoint = $base . "/message/channel?username=" . urlencode($username);

    //     $curl = curl_init();
    //     $headers = [];
    //     $messageText = isset($content) ? html_entity_decode(strip_tags($content)) : '';
    //     $hasMedia = !empty($media);
    //     if ($hasMedia) {
    //         // Multipart with file
    //         $postFields = [
    //             'token' => $token,
    //             'channelId' => $channelId,
    //             'delaySeconds' => $delaySeconds,
    //         ];
    //         if ($messageText !== '') {
    //             $postFields['message'] = $messageText;
    //         }

    //         $mediaFile = null;


    //         if ($mediaFile instanceof CURLFile) {
    //             $postFields['media'] = $mediaFile;
    //             curl_setopt_array($curl, [
    //                 CURLOPT_URL => $endpoint,
    //                 CURLOPT_RETURNTRANSFER => true,
    //                 CURLOPT_TIMEOUT => 60,
    //                 CURLOPT_POST => true,
    //                 CURLOPT_POSTFIELDS => $postFields,
    //                 CURLOPT_SSL_VERIFYPEER => false,  // 🔴 disable peer verification
    //                 CURLOPT_SSL_VERIFYHOST => false,  // 🔴 disable host verification

    //             ]);
    //         } else {
    //             $hasMedia = false; // fallback to text-only
    //         }
    //     }

    //     if (!$hasMedia) {
    //         $payload = [
    //             'token' => $token,
    //             'channelId' => $channelId,
    //             'delaySeconds' => $delaySeconds,
    //         ];
    //         if ($messageText !== '') {
    //             $payload['message'] = $messageText;
    //         }

    //         $json = json_encode($payload);
    //         $headers[] = 'Content-Type: application/json';
    //         curl_setopt_array($curl, [
    //             CURLOPT_URL => $endpoint,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_TIMEOUT => 50,
    //             CURLOPT_CUSTOMREQUEST => 'POST',
    //             CURLOPT_POSTFIELDS => $json,
    //             CURLOPT_HTTPHEADER => $headers,
    //             CURLOPT_SSL_VERIFYPEER => false,  // 🔴 disable peer verification
    //             CURLOPT_SSL_VERIFYHOST => false,  // 🔴 disable host verification
    //         ]);
    //     }

    //     // 3. Execute request
    //     $response = curl_exec($curl);

    //     $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //     $curlError = curl_error($curl);
    //     curl_close($curl);

    //     if ($httpCode === 200 && $response !== false) {
    //         $res = json_decode($response, true);
    //         if (!empty($res['success'])) {

    //             $this->error_log("WhatsApp channelId  " . $response);
    //             return true;
    //         }
    //         $this->error_log("WhatsApp channelId API error: " . $response);
    //         return false;
    //     }

    //     $this->error_log("WhatsApp channelId API failed: HTTP $httpCode Error: $curlError Resp: $response");
    //     return false;
    // }


 public function sendWhatsAppBulk(array $phones, string $content, ?string $media = null)
    {
        $accRes = $this->mysqli->query("
        SELECT wu.username,wa.api_key,wu.base_url FROM whatsapp_users wu  join  whatsapp_accounts wa  on wu.id=wa.user_id
        WHERE wu.status = 'connected'
        ORDER BY wa.id DESC 
        LIMIT 1
    ");

        $cfg = null;
        if ($accRes && $accRes->num_rows > 0) {
            $cfg = $accRes->fetch_assoc();
            $base = rtrim($cfg['base_url'] ?? '', '/');
            $token = $cfg['api_key'] ?? '';
            $username = $cfg['username'] ?? '';
            $base = $base . '/message/bulk?username=' . urlencode($username);
        } else {
            // Fallback
            $apiRes = $this->mysqli->query("SELECT * FROM api WHERE name = 'whatsapp' AND api_status = '1' LIMIT 1");
            if (!$apiRes || $apiRes->num_rows === 0) {
                $this->error_log('WhatsApp API config not found');
                return ['status' => false, 'message' => 'WhatsApp API config not found'];
            }
            $cfg = $apiRes->fetch_assoc();
            $base = rtrim($cfg['endpoint'] ?? 'http://wapi.itways.in', '/');
            $token = $cfg['token'] ?? ($cfg['api_key'] ?? '');
            $base = $base . '/message/bulk';
        }

        if (empty($base) || empty($token)) {
            $this->error_log('WhatsApp API missing endpoint or token');
            return ['status' => false, 'message' => 'WhatsApp API missing endpoint or token'];
        }

        $endpoint = $base;
        $messageText = isset($content) ? html_entity_decode(strip_tags($content)) : '';
        $hasMedia = !empty($media);

        $curl = curl_init();
        $headers = [];

        if ($hasMedia) {
            $postFields = [
                'token' => $token,
                'phones' => json_encode($phones), // Send as JSON string for multipart
            ];
            if ($messageText !== '') {
                $postFields['message'] = $messageText;
            }

            $mediaFile = null;
            if (preg_match('~^https?://~i', $media)) {

                $tmp = tempnam(sys_get_temp_dir(), 'wa_');
                $bin = @file_get_contents($media);

                if ($bin !== false) {
                    file_put_contents($tmp, $bin);
                    $mime = function_exists('mime_content_type') ? mime_content_type($tmp) : 'application/octet-stream';

                    $filename = basename(parse_url($media, PHP_URL_PATH)) ?: 'media';
                    $mediaFile = new CURLFile($tmp, $mime, $filename);
                }
                // $media = dirname(BASEURL, 1) . '/uploads/' . $media;
            } elseif (is_string($media)) {

                // If $media is only "image.jpg", build full path
                // Adjust this path as per your project structure
                 if ($media == 'logo.png') {
                    $mediaPath = dirname(__DIR__, 3) . '/uploads/logo/' . ltrim($media, '/');
                } else {

                    $mediaPath = dirname(__DIR__, 3) . '/uploads/' . ltrim($media, '/');
                }
                $mediaPath = str_replace('\\', '/', $mediaPath);
                if (is_file($mediaPath)) {
                    $mime = function_exists('mime_content_type') ? mime_content_type($mediaPath) : 'application/octet-stream';
                    $mediaFile = new CURLFile($mediaPath, $mime, basename($mediaPath));
                } else {
                    // Optional: log error if file not found
                    // $mediaFile = $mediaPath;
                    $this->error_log("Media file not found: " . $mediaPath);
                }
            }


            if ($mediaFile instanceof CURLFile) {
                $postFields['media'] = $mediaFile;
                curl_setopt_array($curl, [
                    CURLOPT_URL => $endpoint,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 120, // Longer timeout for bulk
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postFields,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ]);
            } else {
                $hasMedia = false;
            }
        }

        if (!$hasMedia) {
            $payload = [
                'token' => $token,
                'phones' => $phones,
            ];
            if ($messageText !== '') {
                $payload['message'] = $messageText;
            }
            $json = json_encode($payload);
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);
        }

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($httpCode === 200 && $response !== false) {
            return ['status' => true, 'message' => 'WhatsApp Bulk sent successfully', 'data' => $response];
        }
        $this->error_log("WhatsApp Bulk API failed: HTTP $httpCode Error: $curlError Resp: $response");
        return ['status' => false, 'message' => "WhatsApp Bulk API failed: HTTP $httpCode Error: $curlError Resp: $response"];
    }

    private function sendWhatsAppChannel(string $channelId, string $content = '', ?string $mediaFileName = null, int $delaySeconds = 1): bool
    {
        // --- 1. Get WhatsApp account config
        $accRes = $this->mysqli->query("
        SELECT wu.username, wa.api_key, wu.base_url 
        FROM whatsapp_users wu 
        JOIN whatsapp_accounts wa ON wu.id = wa.user_id
        WHERE wu.status = 'connected'
        ORDER BY wa.id DESC 
        LIMIT 1
    ");

        if (!$accRes || $accRes->num_rows === 0) {
            $this->error_log('WhatsApp group API config not found');
            return false;
        }

        $cfg = $accRes->fetch_assoc();
        $base = rtrim($cfg['base_url'] ?? '', '/');
        $token = $cfg['api_key'] ?? '';
        $username = $cfg['username'] ?? '';

        if (empty($base) || empty($token) || empty($username)) {
            $this->error_log('WhatsApp API missing endpoint, token or username');
            return false;
        }

        $endpoint = $base . "/message/channel?username=" . urlencode($username);

        $curl = curl_init();
        $headers = [];
        $messageText = isset($content) ? html_entity_decode(strip_tags($content)) : '';
        if (empty($mediaFileName))
            $mediaFileName = 'logo/logo.png';
        $hasMedia = !empty($mediaFileName);

        if ($hasMedia) {
            $filePath = dirname(__DIR__, 3) . '/uploads/' . $mediaFileName; // uploaded file path

            if (!file_exists($filePath)) {
                $this->error_log("File not found in uploads folder: $filePath");
                return false;
            }

            // $mime = function_exists('mime_content_type') ? mime_content_type($filePath) : 'application/octet-stream';
            $mime = $this->getMimeTypeByExtension($filePath);



            $mediaFile = new CURLFile($filePath, $mime, basename($filePath));

            $postFields = [
                'token' => $token,
                'channelId' => $channelId,
                'delaySeconds' => $delaySeconds,
                'media' => $mediaFile
            ];

            if ($messageText !== '') {
                $postFields['message'] = $messageText;
            }

            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);
        } else {

            // text-only message
            $payload = [
                'token' => $token,
                'channelId' => $channelId,
                'delaySeconds' => $delaySeconds,
                'message' => $messageText
            ];
            $json = json_encode($payload);
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 50,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);
        }

        // Execute request
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($httpCode === 200 && $response !== false) {
            $res = json_decode($response, true);
            if (!empty($res['success'])) {
                $this->error_log("WhatsApp channelId sent successfully: " . $response);
                return true;
            }
            $this->error_log("WhatsApp API error: " . $response);
            return false;
        }

        $this->error_log("WhatsApp API failed: HTTP $httpCode Error: $curlError Resp: $response");
        return false;
    }


    private function sendSMS(string $phone, string $content): bool
    {
        // Fetch SMS API configuration from api table
        $smsConfig = $this->getQuery("SELECT * FROM api WHERE name = 'sms' AND api_status = '1' LIMIT 1");

        if (empty($smsConfig)) {
            $this->error_log('SMS API configuration not found or disabled');
            return false;
        }

        $cfg = $smsConfig[0];
        $endpoint = $cfg->endpoint ?? '';
        $apiKey = $cfg->api_key ?? '';
        $username = $cfg->username ?? '';
        $password = $cfg->password ?? '';

        if (empty($endpoint) || empty($apiKey)) {
            $this->error_log('SMS API missing endpoint or API key');
            return false;
        }

        // Clean phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($phone)) {
            $this->error_log('Invalid phone number provided');
            return false;
        }

        // Prepare message content
        $message = trim(strip_tags($content));
        if (empty($message)) {
            $this->error_log('Empty message content');
            return false;
        }

        // Build request payload based on common SMS API formats
        $payload = [
            'apikey' => $apiKey,
            'username' => $username,
            'password' => $password,
            'to' => $phone,
            'message' => $message,
            'sender' => $cfg->source ?? 'CRM',
            'type' => 'text'
        ];

        // Remove empty values
        $payload = array_filter($payload);

        $curl = curl_init();

        // Check if endpoint expects GET or POST
        if (strpos($endpoint, '?') !== false) {
            // GET request
            $queryString = http_build_query($payload);
            $url = $endpoint . '&' . $queryString;

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET'
            ]);
        } else {
            // POST request
            curl_setopt_array($curl, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($payload),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/x-www-form-urlencoded'
                ]
            ]);
        }

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            $this->error_log("SMS API cURL error: $curlError");
            return false;
        }

        if ($httpCode !== 200) {
            $this->error_log("SMS API HTTP error: $httpCode, Response: $response");
            return false;
        }

        // Parse response based on common SMS API formats
        $responseData = json_decode($response, true);
        if ($responseData === null) {
            // Handle non-JSON response
            if (strpos($response, 'success') !== false || strpos($response, 'Success') !== false) {
                $this->error_log("SMS sent successfully to $phone: $response");
                return true;
            } else {
                $this->error_log("SMS API error response: $response");
                return false;
            }
        }

        // Check success indicators in JSON response
        $isSuccess = false;
        if (isset($responseData['status']) && in_array(strtolower($responseData['status']), ['success', 'ok', '1'])) {
            $isSuccess = true;
        } elseif (isset($responseData['response']['status']) && in_array(strtolower($responseData['response']['status']), ['success', 'ok', '1'])) {
            $isSuccess = true;
        } elseif (isset($responseData['success']) && $responseData['success'] === true) {
            $isSuccess = true;
        }

        if ($isSuccess) {
            $this->error_log("SMS sent successfully to $phone");
            return true;
        } else {
            $errorMessage = $responseData['message'] ?? $responseData['error'] ?? 'Unknown error';
            $this->error_log("SMS API error: $errorMessage");
            return false;
        }
    }

    /**
     * Send in-app notification
     * @param int $userId User ID
     * @param string $content Message content
     * @return bool Success status
     */
    private function sendInAppNotification(int $userId, string $content, string $title = 'Given a New Task'): bool
    {
        $userId = (int) $userId;
        $safeContent = $this->sanitize($content);
        $notification = array(

            'assing_to' => $userId,

            'title' => $title,

            'description' => $safeContent,

            'uploader' => 'CRM-Auto'



        );

        // print_r($array);

        // die;





        return (bool) $this->insert_qry('notification', $notification);

    }

    /**
     * Send a template to a group of users (e.g., admin group)
     * @param array $userIds
     * @param int $templateId
     * @param array $extraPlaceholders
     * @param array $options
     * @return array map of userId => bool success
     */
    public function sendToGroupByTemplate(array $userIds, int $templateId, $leadID = null, array $extraPlaceholders = [], array $options = []): bool
    {
        // die('here');
        $results = [];
        $template = $this->getTemplate((int) $templateId);

        if (!$template) {
            foreach ($userIds as $uid)
                $results[$uid] = false;
            return false;
        }
        // Try to infer lead id if provided in placeholders/options
        $leadId = $leadID ?? $extraPlaceholders['lead_id'] ?? $options['lead_id'] ?? null;

        $templateContent = isset($template['body']) && $template['body'] !== '' ? $template['body'] : ($template['content'] ?? '');



        // ✅ Middleware check: group_id + WhatsApp channel
        $channel = $template['channel'] ?? null;
        $groupId = $options['group_id'] ?? null;



        // Resolve placeholders for this recipient: allow user-specific and lead-specific values
        $placeholders = $this->getPlaceholdersForType((int) $templateId, $leadId, null, null);
        $placeholders = array_merge($placeholders, $extraPlaceholders);


        if ($channel === 'whatsapp' && !empty($groupId)) {
            try {

                // Custom group send (only WhatsApp groups)
                $res = $this->sendToWhatsAppGroup(
                    $groupId,
                    $templateContent,
                    $placeholders,
                    $leadId,
                    $options
                );
                return $res;
            } catch (Exception $e) {
                $this->error_log('sendToGroupByTemplate error for WhatsApp group ' . $groupId . ': ' . $e->getMessage());
                return false;
            }
        }
        ///////////////////////////////////////////////////////



        foreach ($userIds as $uid) {
            $uid = (int) $uid;
            try {
                $user = $this->getUserDetails($uid);
                if (!$user) {
                    $results[$uid] = false;
                    continue;
                }


                // Merge subject if present
                if (!empty($template['subject'])) {
                    $options = array_merge(['subject' => $template['subject']], $options);
                }

                $sent = $this->sendToContact(
                    $user,
                    $templateContent,
                    $placeholders,
                    $template['channel'] ?? null,
                    'admin',
                    $leadId ?? null,
                    null,
                    $options
                );

                $results[$uid] = (bool) $sent;
            } catch (Exception $e) {
                $this->error_log('sendToGroupByTemplate error for user ' . $uid . ': ' . $e->getMessage());
                $results[$uid] = false;
            }
        }

        return true;
    }


    /**
     * Send property or lead details to a WhatsApp Channel by Template
     *
     * @param string|int $channelId       WhatsApp channel ID (from settings or DB)
     * @param int $templateId             Template ID
     * @param int|null $propertyId        Property ID for placeholder binding
     * @param array $extraPlaceholders    Extra variables for template replacement
     * @param array $options              Extra options (like media attachments, captions, etc.)
     * @return bool
     */
    public function sendToChannelByTemplate($channelId, int $templateId, array $extraPlaceholders = [], array $options = []): bool
    {
        $template = $this->getTemplate((int) $templateId);
        if (!$template) {
            $this->error_log("sendToChannelByTemplate: No template found for ID $templateId");
            return false;
        }
        print_r($options);
        if (empty($channelId)) {
            $this->error_log("sendToChannelByTemplate: No channel id found");
            return false;
        }

        // Template content
        $templateContent = isset($template['body']) && $template['body'] !== ''
            ? $template['body']
            : ($template['content'] ?? '');

        // Resolve placeholders (property placeholders + extras)
        $placeholders = [];

        $placeholders = array_merge($placeholders, $extraPlaceholders);

        // Merge subject if template has one
        if (!empty($template['subject'])) {
            $options = array_merge(['subject' => $template['subject']], $options);
        }

        // Channel must be WhatsApp
        $channel = $template['channel'] ?? null;
        if ($channel !== 'whatsapp') {
            $this->error_log("sendToChannelByTemplate: Template $templateId is not set for WhatsApp channel");
            return false;
        }
        try {

            $messageContent = $this->processTemplate($templateContent, $placeholders);
            $media = isset($options['media']) ? (string) $options['media'] : null;


            // Example group send call (depends on your WhatsApp API)
            $sent = $this->sendWhatsAppChannel($channelId, $messageContent, $media);

            return $sent;
        } catch (Exception $e) {
            $this->error_log("sendToChannelByTemplate error for channel $channelId: " . $e->getMessage());
            return false;
        }
    }


    public function sendToUserByTemplate(int $userId, int $templateId, array $extraPlaceholders = [], array $options = []): bool
    {
        // Get user details
        $user = $this->getUserDetails($userId);
        if (!$user) {
            return false;
        }

        // Get the template
        $template = $this->getTemplate($templateId);
        if (!$template) {
            return false;
        }

        // Get template content
        $templateContent = $template['body'] ?? ($template['content'] ?? '');

        // Merge subject if present
        if (!empty($template['subject'])) {
            $options = array_merge(['subject' => $template['subject']], $options);
        }

        // Resolve placeholders for this specific user
        $placeholders = $this->getPlaceholdersForType($templateId, null, $userId, null);
        $placeholders = array_merge($placeholders, $extraPlaceholders);

        // Send the message
        try {
            return $this->sendToContact(
                $user,
                $templateContent,
                $placeholders,
                $template['channel'] ?? null,
                'admin', // message type
                null,    // leadId not needed
                null,    // clientId not needed
                $options
            );
        } catch (Exception $e) {
            $this->error_log('sendToUserByTemplate error for user ' . $userId . ': ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Log message in CRM database
     * @param int $senderId
     * @param int|null $leadId
     * @param int|null $clientId
     * @param string $messageType
     * @param string $content
     * @param string $channel
     * @return bool Success status
     */
    private function logMessage(int $senderId, ?int $leadId, ?int $clientId, string $messageType, string $content, string $channel): bool
    {
        $senderId = (int) $senderId;
        $leadIdSql = is_null($leadId) ? 'NULL' : (string) (int) $leadId;
        $clientIdSql = is_null($clientId) ? 'NULL' : (string) (int) $clientId;
        $safeType = $this->sanitize($messageType);
        $safeContent = $this->sanitize($content);
        $safeChannel = $this->sanitize($channel);
        $query = "INSERT INTO message_logs (sender_id, lead_id, client_id, message_type, content, channel, sent_at) VALUES ($senderId, $leadIdSql, $clientIdSql, '$safeType', '$safeContent', '$safeChannel', NOW())";
        return (bool) $this->mysqli->query($query);
    }

    private function error_log(string $message, string $level = 'INFO'): void
    {
        // Log file path (make sure the folder is writable)
        $logFile = __DIR__ . '/logs/app.log';

        // Ensure log folder exists
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }

        // Format entry
        $timestamp = date('Y-m-d H:i:s');
        $entry = "[$timestamp] [$level] $message" . PHP_EOL;

        // Append to file
        file_put_contents($logFile, $entry, FILE_APPEND);

        // Also log to PHP error log for quick debugging
        // error_log($entry);
    }
}
