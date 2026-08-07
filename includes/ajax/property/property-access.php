
<?php


require_once('../../config.php');

// Assuming ['project_id'] contains the selected project ID
$status = $_POST['status'];
$assgn_prop = $_POST['assgn_prop'];
$lid = $_POST['lid'];
// print_r($status);
// Fetch towers based on the selected project
// $rows = $boj->getQuery("SELECT * FROM user");
// // // print_r($rows);
// echo '<option value="">Select User</option>';
// foreach ($rows as $row){
//     echo "<option value='{$row->id}'>{$row->name}</option>";
// }

if ($status == '1') {

    // $html = '';


    if (!empty($assgn_prop)) {

        $assignedNew = explode(",", $assgn_prop);
    } else {
        $assignedNew = '';
    }
    // print_r($assigned);
    // echo ("select * from user where status = 'active' and  realted_area like '%$lid%'");


    $staffAssign = $boj->getQuery("select * from user where status = 'active'");
    $a = '';
    //  print_r($staffAssign);
    if ($staffAssign) {
        foreach ($staffAssign as $staffAssigns) {
            if (in_array($staffAssigns->id, $assignedNew)) {
                $a =  "selected";
            }
            // print_r($staffAssign);
            echo "<option value='<?=$staffAssigns->id;?>' echo $a >[ $staffAssigns->id]- $staffAssigns->name </option>";
        }
    }
} elseif ($status = '2') {


    //  $lid = $values->property_location;
    if (!empty($lid)) {

        $assigned = explode(",", $lid);
    } else {
        $assigned = '';
    }
    // print_r($assigned);
    // echo ("select * from user where status = 'active' and  realted_area like '%$lid%'");


    $staff = $boj->getQuery("select * from user where status = 'active' and  realted_area like '%$lid%'");
    // print_r($staff);

    if ($staff) {
        foreach ($staff as $staffs) {
            if (in_array($staffs->id, $assigned)) {
                $a =  "selected";
            }
            // print_r($staffAssign);
            echo "<option value='$staffs->id' echo $a >[$staffs->id]- $staffs->name </option>";
        }
    }
}

?>
