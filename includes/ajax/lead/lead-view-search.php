<?php

require_once('../../config.php');
$boj->check_session();
$perm = $check->check_permission('leads', 'view_all');
$edit = $check->check_permission('leads', 'edit');
$delete = $check->check_permission('leads', 'delete');
$view_own = $check->check_permission('leads', 'view_own');
  $limit_per_page = 10;
 
  $page = "";
  if(isset($_POST["page_no"])){
    $page = $_POST["page_no"];
  }else{
    $page = 1;
  }
  $offset = ($page - 1) * $limit_per_page;
  $search_value = $_POST["search"];
if($search_value){
 
  if($perm == 'true' OR $getuserdata->usertype == 'root'){
    $qry = $boj->getQuery("SELECT * FROM leads where (lead_status='un-attempted' AND assign_to!='public') AND (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' )  LIMIT {$offset},{$limit_per_page} "); 
    }else{
    $qry = $boj->getQuery("SELECT * FROM leads where (lead_status='un-attempted' AND assign_to='$getuserdata->id' AND assign_to!='public')  AND (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page} "); 
    }
}else{
    if($perm == 'true' OR $getuserdata->usertype == 'root'){
    $qry = $boj->getQuery("SELECT * FROM leads where (lead_status='un-attempted' AND assign_to!='public') LIMIT {$offset},{$limit_per_page} "); 
    }else{
    $qry = $boj->getQuery("SELECT * FROM leads where (lead_status='un-attempted' AND assign_to='$getuserdata->id' AND assign_to!='public')  LIMIT {$offset},{$limit_per_page} "); 
    }
}
 
  $output= "";
  if($qry){
    $output .= '<table class="table table-bordered table-striped mb-none">
      <tr>
      
      <th>ID</th>
      <th>Lead Name</th>
      <th>Contact</th>
      <th>Project</th>
      <th>Requirement</th>
      <th>For</th>
      <th>Source/Employee</th>
      <th>Remarks</th>
      <th>Lead Date</th>
      <th>Action</th>
      </tr>';
  
    //  $offset
      if( $qry ){
         $p=1;
        foreach($qry as $value){
            $output .="<tr class='gradeC'>
            <td><a href='leads/lead-edit/?edit={$value->id}&nav=leads'>{$value->id}</a></td>
            <td> <a href='leads/lead-edit/?edit={$value->id}&nav=leads'>{$value->lead_name}</a></td>
            <td>{$value->lead_contact}</td>
            <td>{$value->project}</td>
            <td>";
               
                if($value->property_type != NULL){  $output.="{$value->property_type}";}
                $output.="<BR />";
                if($value->category != NULL){  $output.="{$value->category}"; }
                $output.="<BR />";
                if($value->furnished_status != NULL){ $output.="{$value->furnished_status}"; } 
              
                $output.="</td>
            <td>{$value->contract}</td>
            <td>";
               
                if($value->reference==1){
                    $agent=$boj->getQuery("SELECT * FROM agent where id='$value->agent_id' ");
                    
                    
                }elseif($value->reference==2){
                    
                    $emp=$boj->getQuery("SELECT * FROM user where id='$value->agent_id' ");
                }
$qry1 = $boj->getQuery("SELECT * FROM source where id = $value->reference "); 
                if( $qry1 ){
                    foreach($qry1 as $ref_name){
                         $output.="{$ref_name->source_name}"; 
                         if($value->reference==1){
                            $output.="{$agent[0]->agent_name}";
                         }elseif($value->reference==2){
                            $output.="{$emp[0]->name}";
                         }
                     } 
                    
                }else{
                    $output.="---";
                } 
                 $output.="{$value->lead_uploaded_by}
            </td>
<td>"; 
if($value->remarks==NULL){
    $output.="---"; }else{ $output.="{$value->remarks}";
    } 
    $output.="</td>
            <td>{$value->lead_date}</td>
            <td> <a href='leads/lead-edit/?edit={$value->id}&nav=leads'>Attempt </a> | Assign Lead
                <form action='' method='post'>
                <select name='assign' onchange='this.form.submit()'>
                    <option value='' disabled selected>Assigned to</option>";
                
                    $agents = $boj->getQuery("SELECT * FROM user where usertype != 'root' order by usertype ASC"); 
                    if( $agents ){
                        foreach($agents as $agent){
                    
                    $output.="<option value='{$agent->id}'"; 
                    if($agent->id == $value->assign_to){ 
                        $output.="selected"; } 
                        $output.=">
                         {$agent->id} | {$agent->username} <sub> {$agent->usertype} </sub> </option>";

                        
                        } 
                    }
                 
              $output.= "</select>
                <input type='hidden' name='lead_id' value='{$value->id}'>
                <input type='submit' class='form-control' value='Assign'>
                </form>
            </td>
        </tr>";
          $p++; 
         } }
      
    
    $output .= "</table>";
  
$query = $boj->getQuery("SELECT COUNT(*) as count FROM leads where lead_status='un-attempted' AND assign_to!='public' AND (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' )");  
   
      $total_record=strtoupper(@$query[0]->count);
 
    $total_pages = ceil($total_record/$limit_per_page);  
    //echo $total_pages;
   
    $output .="<div id='pagination'style='margin-top:20px;'>
    <div class='col-sm-3' style='margin-top:20px;'>  
					Showing ".  $offset ." to ".$offset+$limit_per_page ." of ". $total_record ." Records
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
