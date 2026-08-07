<?php 
include('../config.php');
// Database connection info 
$sql_details = array( 
    'host' => HOSTS, 
    'user' => USERNAME, 
    'pass' => PASSWORD, 
    'db'   => DATABASE
); 
 
// DB table to use 
$table = 'leads'; 

// Table's primary key 
$primaryKey = 'id'; 

 
// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
     
    array( 'db' => 'l.id', 'dt' => 0, 'field' => 'id',
    'formatter' => function( $d, $row ) { 
          $re = '<a href="lead-edit.php?edit='.$d.'&nav=leads">'.$d.'</a>';
          return $re;
    } 
    ), 
    array( 'db' => 'l.lead_name',  'dt' => 1,  'field' => 'lead_name'
    // 'formatter' => function( $d, $row ) { 
    //     return  '<a href="lead-edit.php?edit='..'&nav=leads">'.$d.'</a>';
    // }  
), 
    array( 'db' => 'l.lead_contact',      'dt' => 2,  'field' => 'lead_contact',
    ), 
    array( 'db' => 'l.project',     'dt' => 3,  'field' => 'project' ), 
 
    array( 'db' => 'l.property_type',    'dt' => 4,  'field' => 'property_type'
        

), 
    
    array( 'db' => 'l.assign_to',    'dt' => 5,  'field' => 'assign_to' ), 
    array( 'db' => 's.source_name',    'dt' => 6,  'field' => 'source_name'), 
    array( 'db' => 'l.remarks',    'dt' => 7, 'field' => 'remarks' ), 
    array( 
        'db'        => 'l.lead_date', 
        'dt'        => 8, 
        'field' => 'lead_date'
        
    ), 

    array( 
        'db'        => 'l.id', 
        'dt'        => 9,
        'field' => 'id', 
        'formatter' => function( $d, $row ) { 
        return  '<a class="btn btn-primary" href="lead-edit.php?edit='.$d.'&nav=leads">Attempt</i></a>';
         }
    ) 
   
); 

$joinQuery = "FROM `leads` AS `l` JOIN `source` AS `s` ON (`l`.`reference`=`s`.`id`)";

// Include SQL query processing class 
require 'ssp.class.php'; 

 
if('u.usertype'=='employee'){
    $where = "lead_status='un-attempted' and assign_to='public'";
}else{
    $where = "lead_status='un-attempted' and assign_to='public'";
}
//$where="lead_status='un-attempted' OR lead_status=''". $value;  
// Output data as json format 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery,$where)
);