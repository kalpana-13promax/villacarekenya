<?php
// error();
class leads extends db
{
    function lead_status()
    {
        date_default_timezone_set("Asia/Muscat");
        $array = [];

        // Validate required fields
        if (empty($_POST['status_type']) || empty($_POST['name'])) {
            $this->msg_set("error", "lead-status");
            return;
        }

        // Validate status type against allowed values
        $allowed_types = ['wip', 'visit-done', 're-visit-done', 'deal-done', 'not-interested'];
        if (!in_array($_POST['status_type'], $allowed_types)) {
            $this->msg_set("error", "lead-status");
            return;
        }

        $table = 'lead_status';
        $nav = $_GET['nav'] ?? 'lead-status';

        $array = [
            'status_type' => htmlspecialchars($_POST['status_type']),
            'name' => htmlspecialchars($_POST['name']),
            'category' => htmlspecialchars($_POST['category']),
            'uploader' => htmlspecialchars($_POST['uploader']),
        ];
        // Check if it's an edit
        if (!empty($_POST['edit'])) {
            $edit_id = (int) $_POST['edit']; // sanitize ID
            $res = $this->getQuery('SELECT * FROM ' . $table . ' WHERE id = ' . $edit_id);
            if ($res) {





                // Update the record
                $data = $this->update_query($table, $array, 'id=' . $edit_id);
                $this->msg_set($data, $nav);
                return;
            }
        }


        $data = $this->insert_query($table, $array);
        $this->msg_set($data, $nav);
    }
}

// Handle form submission
if (isset($_POST['lead-status'])) {
    $obj = new leads();
    $obj->lead_status();
}
