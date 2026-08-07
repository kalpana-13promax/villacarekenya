<?php

class visits extends db
{
    function schedule_visit()
    {
        $lead_id = $_POST['lead_id'];
        $where = $_POST['where'];
        $s = strtotime($_POST['datetime']);
        $date = date('Y-m-d', $s);
        $time = date('H:i', $s);
        $array = array(
            'lead_id' => $_POST['lead_id'],
            'property_to_visit' => $_POST['property'],
            'assign_to' => $_POST['assign_to'],
            'scheduled_date' => $date,
            'scheduled_time' => $time,
            'remarks' => $_POST['detail'],
            'assign_by' => $_POST['uploader']
        );

        $pro = array(
            'assign_property' => $_POST['assign_to']

        );
        $w = "id = " . $_POST['property'];
        $data = $this->update_query('property_listing', $pro, $w);


        $data = $this->insert_query('visits', $array);
        $nav = "?nav=visits&lead_id=" . $lead_id . "&where=" . urlencode($where);
        $this->msg_set($data, $nav);
    }


    function visit_status()
    {
        $id = $_POST['id'];
        $array = [];
        if (isset($_POST['selfie']) && !empty($_POST['selfie'])) {
            $file = $_POST['selfie'];

            $array['visited_selfie'] = $this->uploadFiles($file);
        }

        $array = array(
            'visit_date' => $_POST['datetime'],
            'visit_status' => $_POST['visit_status'],
            'visit_remarks' => $_POST['visit_remarks'],

        );
        $where = "id =" . $id;
        $data = $this->update_query('visits', $array, $where);
        $nav = "visits&id=" . $id;
        $this->msg_set($data, $nav);
    }
}



if (isset($_POST['schedule-visit'])) {
    $obj = new visits();
    $obj->schedule_visit();
}
if (isset($_POST['visit-status'])) {
    $obj = new visits();
    $obj->visit_status();
}


