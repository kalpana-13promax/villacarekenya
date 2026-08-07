<?php
// File: admin/includes/classes/VisitReminder.php

class VisitReminder
{
    protected $db;
    protected $settings;
    protected $send;


    public function __construct($db, $settings, $send)
    {
        $this->db = $db;
        $this->settings = $settings;
        $this->send = $send;
    }

    public function run()
    {
        $timeBefore = (int)$this->settings->getSetting('visit_reminder_time', 0);
        $unit       = $this->settings->getSetting('visit_reminder_time_unit'); // minutes/hours

        if ($timeBefore <= 0) {
            return; // no reminder configured
        }

        // calculate reminder cutoff
        $interval = ($unit === 'hours') ? $timeBefore * 3600 : $timeBefore * 60;
        $now = time();
        $targetTime = $now + $interval;


        $sql = " SELECT v.id AS visit_id,
       v.lead_id,
       v.property_to_visit,
       v.assign_to,
       v.scheduled_date,
       v.scheduled_time,
       l.lead_name,
       l.lead_contact,
       u.name AS agent_name,
       u.contact AS agent_phone
      
    
FROM visits v
JOIN leads l ON v.lead_id = l.id
LEFT JOIN user u ON v.assign_to = u.id
WHERE TIMESTAMP(v.scheduled_date, v.scheduled_time)
      BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL '{$timeBefore}' HOUR)
  AND v.reminder_sent = 0";

        $res = $this->db->query($sql);
        $rows = $res->fetch_all(MYSQLI_ASSOC);


      

        foreach ($rows as $visit) {
            $vars = [
                'visit_id'     => $visit['visit_id'],
                'lead_id'      => $visit['lead_id'],
                'customer_name'  => $visit['lead_name'],
                'customer_phone' => $visit['lead_contact'],
                'agent_name'   => $visit['agent_name'],
                'agent_phone'  => $visit['agent_phone'],
                'visit_date'   => $visit['scheduled_date'],
                'visit_time'   => $visit['scheduled_time'],
                'property'     => $visit['property_to_visit'],

                'company_name' => SITENAME
            ];
            $assignto = $visit['assign_to'];

            // trigger same notification logic
            //    $this->send->onVisitScheduled($visit['lead_id'], $assignto,$vars);


            $id = $visit['visit_id'];

            // mark reminder as sent
            try {
                $payload = json_encode([
                    'vars'      => $vars,
                    'lead_id'   => $visit['lead_id'],
                    'assign_to' => $assignto
                ]);

                $payload = $this->db->real_escape_string($payload);

                $sql = "INSERT INTO jobs (type, payload) VALUES ('visits', '$payload')";
                $this->db->query($sql);


                $this->db->query(
                    "UPDATE visits SET reminder_sent = 1, reminder_sent_at = NOW() WHERE id ='{$id}' "

                );
            } catch (Exception $e) {
                echo $e->getmessage();
                die;
            }
        }
    }
}
