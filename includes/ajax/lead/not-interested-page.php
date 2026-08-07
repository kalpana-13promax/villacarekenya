<?php

require_once('../../config.php');
$boj->check_session();

  $limit_per_page = 10;

  $page = "";
  if(isset($_POST["page_no"])){
    $page = $_POST["page_no"];
  }else{
    $page = 1;
  }
// if(isset($_POST['fillter_user'])){
//     $fillter_user=$_POST['fillter_user'];
//     echo $fillter_user;
// }
$offset = ($page - 1) * $limit_per_page;
    
 //----------------------------------------------------------------------------------------------
 if(isset($_POST["search"])){
  $_SESSION['search'] = $_POST["search"];
 
 
 }
 

//---------------------------------------------------------------------------------------------------------
//---------------------------------------------------------
if(isset($_POST["pages"])){
  $_SESSION['pages'] = $_POST["pages"];
 
 
 }

@$pages_val=$_SESSION['pages'];
if($pages_val){
  $limit_per_page = $pages_val;
}
else{
  $limit_per_page = 10;
 }
//----------------------------------------------------------------------

$offset = ($page - 1) * $limit_per_page;
@$search_value = $_SESSION["search"];
if($search_value){
        
        if($getuserdata->usertype == 'root' || $getuserdata->usertype == 'admin'){
          
         
        $qry = $boj->getQuery("SELECT * FROM leads where lead_status = 'not-interested' and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page} ");

        $total_record=$boj->count("SELECT * FROM leads where lead_status = 'not-interested' and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' )  order by id desc");
        }else{
            $qry = $boj->getQuery("SELECT * FROM leads where lead_status = 'not-interested' and update_by = '$getuserdata->username' and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page} ");
            $total_record=$boj->count("SELECT * FROM leads where lead_status = 'not-interested' and update_by = '$getuserdata->username' and  (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' )  order by id desc");
        } 
      }else{
        
        if($getuserdata->usertype == 'root' || $getuserdata->usertype == 'admin'){
            
          
    $qry = $boj->getQuery("SELECT * FROM leads where lead_status = 'not-interested' LIMIT {$offset},{$limit_per_page} "); 
    $total_record=$boj->count("SELECT * FROM leads where lead_status = 'not-interested' order by id desc");
        }else{
          $qry = $boj->getQuery("SELECT * FROM leads where lead_status = 'not-interested' and update_by = '$getuserdata->username' LIMIT {$offset},{$limit_per_page} "); 
    
          $total_record=$boj->count("SELECT * FROM leads where lead_status = 'not-interested' and update_by = '$getuserdata->username' order by id desc");
        }
      } 
  

 
 
 
  $output= "";
  if($qry){
    $output .= '<table class="table table-bordered table-striped mb-none">
    <tr>
    <th>#</th><th>ID</th><th>Lead Name</th><th>Contact</th><th>Interested</th><th>Last Remark</th><th>Source/Agent</th><th>Lead Date</th><th>Follow-up Date</th><th>Created By</th><th>Action</th>
</tr>';
  
    //  $offset
      if( $qry ){
         $p=1;
        foreach($qry as $value){
          $res= $p+$offset;
          $output .="<tr class='gradeC'>
          <td>{$res}</td>
    <td><a  href='leads/lead-edit/?edit={$value->id}&nav=leads'>{$value->id}</a></td> 
     <td> <a  href='leads/lead-edit/?edit={$value->id}&nav=leads'>{$value->lead_name}</a>";
           
           
          
           
           
            $output .="</td>
           
            <td>{$value->lead_contact}</td>
            
    <td> {$value->property_type} <br />{$value->category} <br /> {$value->furnished_status} </td>";
   
          
    
  $qry1 = $boj->getQuery("SELECT * FROM remarks where lead_id = '$value->id' order by id desc limit 1"); 
            if( $qry1 ){
                foreach($qry1 as $remarks){
       	
            $output .=" <td>{$remarks->remarks} <hr /><i>{$remarks->remarks_by}{$remarks->remarks_date} </i></td>";
                 }
            }else{
                $output .="<td>---</td>";
            }
               
            $no_val = "";
            if ($value->reference != NULL AND $value->reference != '0')
                        {
$qry1 = $boj->getQuery("SELECT * FROM source where id = $value->reference"); 
if( $qry1 ){
foreach($qry1 as $ref_name){

                				$output.="<td>{$ref_name->source_name}</td>";
                } } 
                        }else{
                          $output.="<td> Unknown / Not Updated </td>";

                        }
                        $output.="<td>{$value->lead_date}</td>
                				<td>{$value->follow_up_date}</td>
                				<td>{$value->lead_uploaded_by}</td>
                <td> <a CLASS='btn btn-primary' href='leads/lead-edit/?edit={$value->id}'>Follow </a></td>
                					
       </tr>";
          $p++; 
         } }
      
        
    $output .= "</table>";
  
    $total_pages = ceil($total_record/$limit_per_page);  
    //echo $total_pages;
   $res_pae=$offset+$limit_per_page;
    $output .="<div id='pagination'style='margin-top:20px;'>
    <div class='col-sm-3' style='margin-top:20px;'>  
					Showing ".  $offset ." to {$res_pae} of ". $total_record ." Records
					</div> <ul class='pagination pagination-md pull-right' style='display:inline-block; margin:0;'>";
       
   
      if($page>=2){
      $j=$page-1;
        $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'><< Previous</span></a></li>";
      }
      for($j=1; $j <= $total_pages; $j++){
        
      if($j == $page){
        $class_name = "active";
        
      }else{
        $class_name = "";
      }
        $inc=$page+1;
        $dec=$page-1;
        if ($j == $page) { 
      $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";
        }
        else  {  
            if($j>=$inc && $j<=$inc) {
                $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";
            }   
          }
          if($j>=$dec && $j<=$dec) {
            $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";
		  }  
    }if($page<$total_pages){   
		  $j=$page+1;
      $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'>Next >></span></a></li>";
	  }    
   
  $output.="</ul>";

    echo $output;
  }else{
    echo "<h2>No Record Found.</h2>";
  }
?>
