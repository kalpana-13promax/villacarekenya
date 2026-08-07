<?php

class LeadSettings extends db
{
    // Original properties (if any, will be uncommented later if needed)
    // private $mysqli;

    // Remove constructor if it was added for dependency injection
    // public function __construct($mysqli_connection)
    // {
    //     $this->mysqli = $mysqli_connection;
    // }
    // Add this method to your LeadSettings class
    public function getTemplateIdByTypeAndChannel($type, $channel)
    {
        $stmt = $this->mysqli->prepare("SELECT id FROM templates WHERE type = ? AND channel = ? LIMIT 1");
        $stmt->bind_param("ss", $type, $channel);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? $row['id'] : null;
    }

    // Remove template ID storage from settings - we'll use the above method instead
    public function getSetting($key)
    {
        // Use $this->mysqli from the extended db class
        $stmt = $this->mysqli->prepare("SELECT setting_value, setting_type FROM lead_settings WHERE setting_key = ?");
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
       
            return $this->deserializeValue($row['setting_value'], $row['setting_type']);
        }
        return null;
    }

    public function setSetting($key, $value, $type,$description = null, $updated_by = 0)
    {
        $serialized_value = $this->serializeValue($value, $type);
        // Use $this->mysqli from the extended db class
        $stmt_check = $this->mysqli->prepare("SELECT id FROM lead_settings WHERE setting_key = ?");
        $stmt_check->bind_param("s", $key);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();
        $stmt_check->close();
        if ($row_check) {
          
            // Update existing setting
            $stmt_update = $this->mysqli->prepare("UPDATE lead_settings SET setting_value = ?, setting_type = ?, `description` = ?, updated_by = ? WHERE setting_key = ?");
            $stmt_update->bind_param("sssis", $serialized_value, $type, $description, $updated_by, $key);
          
            $result = $stmt_update->execute();
            $stmt_update->close();
            return $result;
        } else {
            // Insert new setting
            $stmt_insert = $this->mysqli->prepare("INSERT INTO lead_settings (setting_key, setting_value, setting_type, `description`, updated_by) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssi", $key, $serialized_value, $type, $description, $updated_by);
            $result = $stmt_insert->execute();
            $stmt_insert->close();
            return $result;
        }
    }


    public function getGroups($key='group')
    {
        if (empty($key)) return [];
        $selected = $this->getSetting($key) ?? '';
        $group = $this->getQuery("SELECT id,group_id,group_name FROM whatsapp_groups  ORDER BY group_name") ?? [];
        $res = '';
        foreach ($group as $k => $v) {
            $res .= '<option value="' . $v->group_id . '" ' . ($selected == $v->group_id ? 'selected' : '') . '>' . $v->group_name . '</option>';
        }
        return $res;
    }
   private function serializeValue($value, $type)
{
    switch ($type) {
        case 'boolean':
            return $value ? '1' : '0'; // string '1' or '0'
        case 'csv':
            return implode(',', (array)$value);
        case 'json':
            return json_encode($value);
        case 'number':
            return (string)$value; // cast number to string
        case 'string':
        default:
            return (string)$value;
    }
}

    private function deserializeValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value;
            case 'csv':
                return explode(',', $value);
            case 'json':
                return json_decode($value, true);
            case 'number':
                return (float)$value;
            case 'string':
            default:
                return (string)$value;
        }
    }

    /**
     * Fetches template content by template name (type) from the 'templates' table.
     * @param string $templateName The name of the template (e.g., 'whatsapp', 'email').
     * @return string The template content, or an empty string if not found.
     */
    public function getTemplateContent($templateName)
    {
        $stmt = $this->mysqli->prepare("SELECT body FROM templates WHERE name = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("s", $templateName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row && isset($row['body'])) {
            // Decode HTML entities to ensure proper display
            return htmlspecialchars_decode($row['body'], ENT_QUOTES);
        }
        return '';
    }


    /**
     * Retrieve existing workflow stages (dummy data)
     */
    public function getWorkflowStages() {
        // In a real implementation, this would fetch from database
        return [
            [
                'trigger_type' => 'time_after_status',
                'lead_status' => '1',
                'time_value' => '2',
                'time_unit' => 'days',
                'template_id' => '1',
                'channels' => ['whatsapp', 'email']
            ],
            [
                'trigger_type' => 'time_after_status',
                'lead_status' => '2',
                'time_value' => '10',
                'time_unit' => 'days',
                'template_id' => '2',
                'channels' => ['whatsapp']
            ]
        ];
    }
    
    /**
     * Get available lead statuses (dummy data)
     */
    public function getLeadStatuses() {
        // In a real implementation, this would fetch from database
        return [
            ['id' => '1', 'name' => 'New Lead'],
            ['id' => '2', 'name' => 'Contacted'],
            ['id' => '3', 'name' => 'Qualified'],
            ['id' => '4', 'name' => 'Proposal Sent'],
            ['id' => '5', 'name' => 'Negotiation'],
            ['id' => '6', 'name' => 'Closed Won'],
            ['id' => '7', 'name' => 'Closed Lost']
        ];
    }
    
    /**
     * Fetch message templates (dummy data)
     */
    public function getTemplates() {
        // In a real implementation, this would fetch from database
        return [
            ['id' => '1', 'name' => 'Initial Follow-up', 'content' => 'Hi {name}, thank you for your interest in our properties. We have options that match your preferences for {property_type} in {location} with your {budget} budget.'],
            ['id' => '2', 'name' => '10-Day Follow-up', 'content' => 'Hello {name}, we have new properties available in {location} that might interest you. Would you like to schedule a viewing?'],
            ['id' => '3', 'name' => 'Closing Template', 'content' => 'Dear {name}, we\'re excited to help you finalize your property decision. Our team has prepared a special offer for you!'],
            ['id' => '4', 'name' => 'Visit Confirmation', 'content' => 'Hi {name}, this is a reminder about your property visit scheduled for {date} at {time}. We look forward to seeing you!']
        ];
    }
    
    /**
     * Get template content for preview
     */
    public function getTemplatePreview($template_id) {
        $templates = $this->getTemplates();
        
        foreach ($templates as $template) {
            if ($template['id'] == $template_id) {
                return nl2br(htmlspecialchars($template['content']));
            }
        }
        
        return 'Template not found';
    }
    
   
    
   
}
