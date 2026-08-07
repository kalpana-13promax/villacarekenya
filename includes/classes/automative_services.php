<?php
// /services/AutomationService.php
require_once __DIR__ . '/LeadSettings.php';
require_once __DIR__ . '/send_msg.php';

class AutomationService extends db
{
    private $db;
    private $settings;
    private $send;

    public function __construct($senderId, $settings = null, $messenger = null)
    {
        // ensure parent DB connection is initialized
        parent::__construct();
        $this->db = $this->mysqli;
        // allow dependency injection for easier testing
        if ($settings !== null) {
            $this->settings = $settings;
        } else {
            $this->settings = new LeadSettings();
        }
        if ($messenger !== null) {
            $this->send = $messenger;
        } else {
            $this->send = new CRMMessenger($senderId); // tumhari send-msg class
        }
    }

    // ========== Helpers ==========
    /**
 * Fetch template dynamically by module, event, recipient, and channel
 *
 * @param string $module Module name, e.g., 'lead', 'payment'
 * @param string $event Event type, e.g., 'manual_add', 'welcome', 'reminder'
 * @param string $recipient Recipient type, e.g., 'client', 'admin', 'agent'
 * @param string $channel Channel type, e.g., 'email', 'sms', 'whatsapp'
 * @return array|null Template data if found, null otherwise
 */
function getTemplate($module, $event, $recipient, $channel) {
    // Build the template_key
    $template_key = strtolower("{$module}_{$event}_{$recipient}_{$channel}");

    $query = "SELECT id FROM templates WHERE template_key = '{$template_key}' LIMIT 1";
    $stmt = $this->db->query($query);
    if (!$stmt) return null;
    $template = $stmt->fetch_assoc();
    if (!$template || empty($template['id'])) return null;
    return (int)$template['id'];
}

    private function tpl(string $event, string $aud, string $ch)
    {
        // Try hierarchical keys first, then legacy keys used in UI
        $candidates = [
            "$event.$aud.$ch.template",
            "{$event}_{$aud}_{$ch}_template",
            "{$event}_{$ch}_template",
            "{$aud}_{$ch}_template",
            "{$ch}_template",
        ];
        return $this->getSettingFlexible($candidates, null);
    }

    private function enabled(string $event, string $aud)
    {
        $candidates = [
            "$event.$aud.enabled",
            "{$event}_{$aud}_enabled",
            "{$event}_enabled",
            "enable_{$event}_{$aud}",
            // legacy/alternate keys used in lead settings
            // map followup-specific keys like enable_reminder_assignee
            "enable_reminder_assignee",
            "enable_reminder_admin",
            "enable_reminder_client",
        ];
        return (int)$this->getSettingFlexible($candidates, 0) === 1;
    }

    private function getChannels(string $event, string $aud)
    {
        $candidateKeys = [
            "$event.$aud.channels",
            "{$event}_{$aud}_channels",
            "{$event}_channels",
            "{$event}_notification_channels",
            // common legacy names from lead-setting.php
            'send_notification_on_assign',
            'manual_add_notification_channels',
            'followup_notification_channels',
            'follow_up_channels',
            'untouched_reminder_channels',
            'escalation_channels',
        ];
        $val = $this->getSettingFlexible($candidateKeys, []);
        if (is_array($val)) return $val;
        if (is_string($val)) return array_filter(array_map('trim', explode(',', $val)));
        return [];
    }
    public function getCompany()
    {
        $company = $this->getQuery("select * from  company limit 1")??[];
        if ($company) {
$company=$company[0];
            $com= [
                'company_name'=>$company->name,
                'site_name'=>$company->slogan,
                'crm_link'=>BASEURL,
                'company_phone'=>$company->phone
            ];
            return $com;
        }    }

    private function getSettingFlexible(array $keys, $default = null)
    {
        foreach ($keys as $k) {
            $v = $this->settings->getSetting($k);
            if ($v !== null && $v !== '') return $v;
        }
        return $default;
    }
    public function getTemplateId(string $type, $ch)
    {
        $sql = "select id from templates where type='{$type}' and channel='{$ch}' limit 1";
        $res = $this->db->query($sql);
        return $res->fetch_object()->id ?? null;
    }

    // ========== Lead Assignment ==========
    public function onLeadAssigned(int $leadId, int $agentUserId, array $adminUserIds = [], array $vars = [], string $api_group='')
    {
        // Fetch enabled channels and template IDs from settings
        $channels = $this->settings->getSetting('send_notification_on_assign') ?? [];
        // print_r($channels); // Debug line to check channels
        $tplIds = [
            'whatsapp' => $this->getTemplateId('lead_assignment', 'whatsapp'),
            'sms'      => $this->getTemplateId('lead_assignment', 'sms'),
            'email'    => $this->getTemplateId('lead_assignment', 'email'),
        ];
        // die;
        if ($this->settings->getSetting('enable_lead_assignee')) {
        foreach ($channels as $ch) {
            $tplId = $tplIds[$ch] ?? null;
            if ($tplId) {
                try {
                    // Send to agent
                    $this->send->sendToAgentByTemplate($leadId, $agentUserId, $tplId, $vars);
                } catch (Exception $e) {
                    $this->error_log("LeadAssigned: Failed to send $ch to agent: " . $e->getMessage());
                }
            }
        }}

        // // Send to client if enabled
        // if ($this->settings->getSetting('send_to_client_on_assign')) {
        //     foreach ($channels as $ch) {
        //         $tplId = $tplIds[$ch] ?? null;
        //         if ($tplId) {
        //             try {
        //                 $this->send->sendToLeadByTemplate($leadId, $tplId, $vars);
        //             } catch (Exception $e) {
        //                 $this->error_log("LeadAssigned: Failed to send $ch to client: " . $e->getMessage());
        //             }
        //         }
        //     }
        // }

        // Send to admin if enabled
        if (!empty($adminUserIds) && $this->settings->getSetting('send_copy_to_admin')) {
             $groupId=$this->settings->getSetting('group_leadassign');
            $option=['group_id'=>$groupId];
            if(!empty($api_group)){
                $option=['group_id'=>$api_group];

            }
           
            foreach ($channels as $ch) {
                $tplId = (int)$this->getTemplate('lead','assignment', 'admin', $ch);
                if ($tplId) {
                    try {
                        $this->send->sendToGroupByTemplate($adminUserIds, $tplId, $leadId,$vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("LeadAssigned: Failed to send $ch to admin: " . $e->getMessage());
                    }
                }
            }
        }
    }


    // ========== 1. Manual Lead Add ==========
    public function onManualLeadAdd(int $leadId, int $agentUserId = 0, array $adminUserIds = [], array $vars = [])
    {
        // Client
       
      $vars = array_merge($vars, $this->getCompany());
        // determine client channels from settings (legacy-friendly)
        $channels = $this->settings->getSetting('manual_add_notification_channels') ?? [];
        if (empty($channels)) $channels = ['whatsapp', 'sms', 'email'];
        $shouldNotifyClient = (int)$this->settings->getSetting('send_to_client_manual_add', 0) === 1 ;
        if ($shouldNotifyClient) {
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','manual_add', 'client', $ch);
               
                if ($tpl) {
                    try {
                        $this->send->sendToLeadByTemplate($leadId, $tpl, $vars);
                    } catch (Exception $e) {
                        $this->error_log("ManualAdd: Failed to send $ch to client: " . $e->getMessage());
                    }
                }
            }
        }
        
        $agent = (int)$this->settings->getSetting('notify_user_after_manual_add', 0) === 1 ;
      
        if ($agent && $agentUserId!==0) {
           $this->onLeadAssigned($leadId, $agentUserId, $adminUserIds, $vars);
           
        }
      
        //addmin
        $shouldNotifyAdmin = (int)$this->settings->getSetting('notify_admin_manual_add', 0) === 1 ;
       
        if (!empty($adminUserIds) && $shouldNotifyAdmin) {  
             $groupId=$this->settings->getSetting('group_manual');
            $option=['group_id'=>$groupId];
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','manual_add', 'admin', $ch);
                // die($tpl);
                if ($tpl) {
                    try {
                        $this->send->sendToGroupByTemplate($adminUserIds,$tpl, $leadId, $vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("ManualAdd: Failed to send $ch to admin group: " . $e->getMessage());
                    }
                }
            }
        }
    }

  function getAdmin(){

      $admin=$this->getQuery("select id from user where usertype='root'");
      
      // Extract only the "id" values
      $adminUserIds = array_column($admin, 'id');
      
      // Convert strings to integers (optional)
      $adminUserIds = array_map('intval', $adminUserIds);
      return $adminUserIds;
    }

    // ========== 2. Visit Schedule ==========
    public function onVisitScheduled(int $leadId,  int $agentUserId,  array $vars = [])
    {
        $vars = array_merge($vars, $this->getCompany());

// fetch property information

if(!empty($vars['property'])){
    $propertyID=$vars['property'];
    $re=$this->getQuery("select p.property_title,l.location,c.city,sl.sub_location from property_listing p left join city c on c.id= p.city 
    left join locations l on  l.id= p.location 
    left join sub_location sl on  sl.id=p.sub_location where p.id= '{$propertyID}'")??[];
    if($re){
        $re= $re[0];
        $pro= [
            'property_name'=>$re->property_title,
            'property_address'=>$re->city.' '.$re->location.''.$re->sub_location
        ];

        $vars= array_merge($vars,$pro);
    };
}
// print_r($vars);
// die;

        // Client

       

 
        // determine client channels from settings (legacy-friendly)
        $channels = $this->settings->getSetting('visit_channels') ?? [];
        if (empty($channels)) $channels = ['whatsapp',  'email'];
        $shouldNotifyAssignee = (int)$this->settings->getSetting('visit_client_notification', 0) === 1 ;
        if ($shouldNotifyAssignee) {
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','visit_schedule', 'client', $ch);
              
                if ($tpl) {
                    try {
                        $this->send->sendToLeadByTemplate($leadId, $tpl, $vars);
                
                    } catch (Exception $e) {
                        $this->error_log("sitvisit: Failed to send $ch to agent: " . $e->getMessage());
                    }
                }
            }
        }
        //agent
        $shouldNotifyAssignee = (int)$this->settings->getSetting('visit_agent_notification', 0) === 1 ;
        if ($shouldNotifyAssignee) {
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','visit_schedule', 'agent', $ch);
              
                if ($tpl && $agentUserId) {
                    try {
                  $this->send->sendToAgentByTemplate($leadId, $agentUserId, $tpl, $vars);
                    } catch (Exception $e) {
                        $this->error_log("sitevisit: Failed to send $ch to agent: " . $e->getMessage());
                    }
                }
            }
        }

         // Admin
         $adminUserIds= $this->getAdmin();
         $shouldNotifyAdmin = (int)$this->settings->getSetting('visit_notify_admin', 0) === 1 ;
       
        if (!empty($adminUserIds) && $shouldNotifyAdmin) {  
           
              $groupId=$this->settings->getSetting('group_visit');
            $option=['group_id'=>$groupId];
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','visit_schedule', 'admin', $ch);
                // die($tpl);
                if ($tpl) {
                    try {
                        $this->send->sendToGroupByTemplate($adminUserIds,$tpl, $leadId, $vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("followup: Failed to send $ch to admin group: " . $e->getMessage());
                    }
                }
            }
        }


       
    }

    // ========== 3. Follow-Up Reminder ==========
    public function onFollowupReminder(int $leadId, int $agentUserId = 0, array $adminUserIds = [], array $vars = [])
    {
        // Agent
           $vars = array_merge($vars, $this->getCompany());
        
        // determine client channels from settings (legacy-friendly)
        $channels = $this->settings->getSetting('followup_notification_channels') ?? [];
        if (empty($channels)) $channels = ['whatsapp',  'email'];
        $shouldNotifyAssignee = (int)$this->settings->getSetting('enable_reminder_assignee', 0) === 1 ;
        if ($shouldNotifyAssignee) {
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','followup', 'agent', $ch);
                
                if ($tpl && $agentUserId) {
                    try {
                  $this->send->sendToAgentByTemplate($leadId, $agentUserId, $tpl, $vars);
                    } catch (Exception $e) {
                        $this->error_log("followup: Failed to send $ch to agent: " . $e->getMessage());
                    }
                }
            }
        }
        
       
        // Admin
         $shouldNotifyAdmin = (int)$this->settings->getSetting('enable_reminder_admin', 0) === 1 ;
       
        if (!empty($adminUserIds) && $shouldNotifyAdmin) {  
           
              $groupId=$this->settings->getSetting('group_followup');
            $option=['group_id'=>$groupId];
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','followup', 'admin', $ch);
                // die($tpl);
                if ($tpl) {
                    try {
                        $this->send->sendToGroupByTemplate($adminUserIds,$tpl, $leadId, $vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("followup: Failed to send $ch to admin group: " . $e->getMessage());
                    }
                }
            }
        }
        // Client
        $shouldNotifyClient = (int)$this->settings->getSetting('enable_reminder_client', 0) === 1 ;
        if ($shouldNotifyClient) {
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','followup', 'client', $ch);
               
                if ($tpl) {
                    try {
                        $this->send->sendToLeadByTemplate($leadId, $tpl, $vars);
                    } catch (Exception $e) {
                        $this->error_log("followup: Failed to send $ch to client: " . $e->getMessage());
                    }
                }
            }   
       
       
    }
    }
   

    // ========== 4. Untouched Lead ==========
    public function onUntouchedLead(  array $vars = [])
    {
       
        $adminUserIds= $this->getAdmin();
         // Admin
        $channels = $this->settings->getSetting('untouched_reminder_channels') ?? [];

         $shouldNotifyAdmin = (int)$this->settings->getSetting('enable_untouched_lead_tracking', 0) === 1 ;
         $miniThresold = $this->settings->getSetting('untouched_minimum_threshold', 0) === 1 ;
         $groupId=$this->settings->getSetting('group_untouched');
            $option=['group_id'=>$groupId];

        if (!empty($adminUserIds) && $shouldNotifyAdmin) {  
           
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','untouched_Report', 'admin', $ch);
                // die($tpl);
                if ($tpl) {
                    try {
                        $this->send->sendToGroupByTemplate($adminUserIds,$tpl, null, $vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("untouched_lead: Failed to send $ch to admin group: " . $e->getMessage());
                    }
                }
            }
        }
        if (!empty($adminUserIds) && $miniThresold && !empty($vars['threshold'])) {  
           
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','untouched_Report_th', 'admin', $ch);
                // die($tpl);
                if ($tpl) {
                    try {
                        $this->send->sendToGroupByTemplate($adminUserIds,$tpl,null, $vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("untouched_lead: Failed to send $ch to admin group: " . $e->getMessage());
                    }
                }
            }
        }
    }

    // ========== 5. Pending Reminder ==========
    public function onPendingReminder(  array $vars = [])
    {
        $notify_pending_reminder_escalation = (int)$this->settings->getSetting('notify_pending_reminder_escalation', 0) === 1;
        $channels = $this->settings->getSetting('pending_reminder_notification_channels') ?? [];
        if (empty($channels)) $channels = ['whatsapp', 'email'];
$adminUserIds = $this->getAdmin();
       
      
        // Admin notification
        if (!empty($adminUserIds&& $notify_pending_reminder_escalation) ) {
              $groupId=$this->settings->getSetting('group_pending');
            $option=['group_id'=>$groupId];
            foreach ($channels as $ch) {
               
                $tpl = (int)$this->getTemplate('lead', 'pending_reminder', 'admin', $ch);
                if ($tpl) {
                    
                    try {
                        $this->send->sendToGroupByTemplate($adminUserIds, $tpl, null, $vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("PendingReminder: Failed to send $ch to admin group: " . $e->getMessage());
                    }
                }
            }
        }

      
    }
// for pending lead per assing agent
     public function pendingReminderToAsign(int $agentUserId,$var=[])
    {
         $notify_pending_reminder_escalation = (int)$this->settings->getSetting('notify_pending_reminder_escalation', 0) === 1;
        $channels = $this->settings->getSetting('pending_reminder_notification_channels') ?? [];
        if (empty($channels)) $channels = ['whatsapp', 'email'];

        
        
        // Agent notification
        if ($agentUserId &&  $notify_pending_reminder_escalation) {

            foreach ($channels as $ch) {
              
                //  $agentUserId=$agentId;
                $tpl = (int)$this->getTemplate('lead', 'pending_reminder', 'agent', $ch);
                if ($tpl) {
                    try {
                        // $this->send->sendToContact();
                        $this->send->sendToUserByTemplate($agentUserId, $tpl, $var);
                    } catch (Exception $e) {
                        $this->error_log("PendingReminder: Failed to send $ch to agent: " . $e->getMessage());
                    }
                }
            }
        }
    }
    //=============7 new lead come for  any source send thank you msg to client or admin both
    public function onNewLead(int $leadId, int $agentUserId = 0, array $adminUserIds = [], array $vars = [],$api_group='')
    {
           $vars = array_merge($vars, $this->getCompany());
        // Client
        // determine client channels from settings (legacy-friendly)
        $channels = $this->settings->getSetting('notify_channel_newlead') ?? [];
        if (empty($channels)) $channels = ['whatsapp'];
        $shouldNotifyClient = (int)$this->settings->getSetting('enable_new_lead', 0) === 1 ;
        if ($shouldNotifyClient) {
            foreach ($channels as $ch) {
                $tpl = (int)$this->getTemplate('lead','newlead', 'client', $ch);
               
                if ($tpl) {
                    try {
                        $this->send->sendToLeadByTemplate($leadId, $tpl, $vars);
                    } catch (Exception $e) {

                        $this->error_log("new_lead_thankyou: Failed to send $ch to client: " . $e->getMessage());
                    }
                }
            }
        }
        
       
        
      //admin
        $shouldNotifyAdmin = (int)$this->settings->getSetting('enable_newlead_admin', 0) === 1 ;
       
      
        $groupId=$this->settings->getSetting('group_newlead');
        if(!empty($api_group)){
            
            $option=['group_id'=>$api_group];}else{
                
                $option=['group_id'=>$groupId];
            }
           
           if (!empty($adminUserIds) && $shouldNotifyAdmin) {  
               
               foreach ($channels as $ch) {
                   $tpl = (int)$this->getTemplate('lead','newlead', 'admin', $ch);
              
            
                   
                   if ($tpl) {
                   
                    try {
                       $re= $this->send->sendToGroupByTemplate($adminUserIds,$tpl, $leadId, $vars,$option);
                    } catch (Exception $e) {
                        $this->error_log("new_lead_thankyou: Failed to send $ch to admin group: " . $e->getMessage());
                        exit();
                    }
                }
            }
        }
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

}

    // ========== 6. Conversation Linking ==========
    public function onConversationLinked(int $leadId, int $agentUserId = null, array $adminUserIds = [], array $vars = [])
    {
        if ($agentUserId && $this->enabled('conversation', 'agent')) {
            foreach (['whatsapp', 'email'] as $ch) {
                $tpl = $this->tpl('conversation', 'agent', $ch);
                if ($tpl) $this->send->sendToAgentByTemplate($leadId, $agentUserId, $tpl, $vars);
            }
        }
        if (!empty($adminUserIds) && $this->enabled('conversation', 'admin')) {
            foreach (['email', 'whatsapp'] as $ch) {
                $tpl = $this->tpl('conversation', 'admin', $ch);
                if ($tpl) $this->send->sendToGroupByTemplate($adminUserIds, $tpl, $vars);
            }
        }
    }
}
