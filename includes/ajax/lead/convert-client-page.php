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
if(isset($_POST["search"])){
  $_SESSION['search'] = $_POST["search"];
 //echo  $_SESSION['search'];
 
 }
 @$search_value = $_SESSION["search"];
 // View Own → restrict to own + subordinates
    $userId = $boj->real_escape_string($getuserdata->id);

    $subordinates = $boj->getQuery("SELECT id FROM user WHERE supervisor_id = '$userId'");
    $ids = [$userId];
    if ($subordinates) {
        foreach ($subordinates as $s) {
            $ids[] = $s->id;
        }
    }
    $idList = implode(',', array_map('intval', $ids));
 if($search_value){
        
         
        $qry = $boj->getQuery("SELECT * FROM leads where convert_to_client='1'  and assign_to in ($idList)  and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page} ");

        $total_record=$boj->count("SELECT * FROM leads where convert_to_client='1'  and assign_to in ($idList) and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' )");
        }else{
          
            $qry = $boj->getQuery("SELECT * FROM leads where convert_to_client='1'  and assign_to in ($idList) order by id desc LIMIT {$offset},{$limit_per_page} ");
            $total_record=$boj->count("SELECT * FROM leads where convert_to_client='1'  and assign_to in ($idList) order by id desc");
        } 
      
  

 
 
 
  $output= "";
  if($qry){
    $output .= '<table class="table table-bordered table-striped mb-none">
    <tr>
    <th>#</th>
    <th>ID</th>
    <th>Name</th>
    <th>Contact</th>
    <th>Requirement</th>
    <th>Budget</th>
    <th>Size</th>
    <th>Remarks</th>
    <th>Source</th>
    <th>Date</th>
    <th>Created </th>
     
    
</tr>';
  
    //  $offset
      if( $qry ){
         $p=1;
        foreach($qry as $value){
          $res= $p+$offset;
          $output .="<tr class='gradeC'>
          <td>{$res}</td>
             <td>{$value->id}</td>
             <td>{$value->lead_name}</td>
           
             <td> 
             {$value->lead_contact}</td>
             
     <td> {$value->property_type} <br /> {$value->category} <br /> {$value->furnished_status} <br />{$boj->contract('leads', $value->contract)}</td>
             <td>{$value->client_budget_min} - {$value->client_budget_max}"; 
             if($value->deposit){ 
                 $output.="<br />Deposit: {$value->deposit}";
                 } 
                 $output.="</td>
             <td>{$value->property_size_min} - {$value->property_size_max}</td>";
      $qry1 = $boj->getQuery("SELECT * FROM remarks where lead_id = '$value->id' order by id desc limit 1"); 
             if( $qry1 ){
                 foreach($qry1 as $remarks){
                     $output.="<td> {$remarks->remarks} <hr /><i>{$remarks->remarks_by}  - {$remarks->remarks_date} </i></td>";
            }
             }else{
                $output.="<td>--</td>";
             }
                
             $no_val = "";
             if ($value->reference != NULL){
 
                 $qry1 = $boj->getQuery("SELECT * FROM source where id = $value->reference"); 
                 if( $qry1 ){
                     foreach($qry1 as $ref_name){
                         $output.="<td>{$ref_name->source_name}";
                     } 
                     
                      if($value->reference==1){
                          
 if($value->agent_id!=NULL){
                     $qry2 = $boj->getQuery("SELECT * FROM agent where id = $value->agent_id"); 
 
                     if($qry2){
 
                         foreach($qry2 as $name){
                             $output.="/ {$name->agent_name}</td>";
                         } 
 
                     }
                     
 }
 
                 }elseif($value->reference==2){
                     $qry2 = $boj->getQuery("SELECT * FROM user where id = '$value->agent_id'"); 
 
                     if($qry2){
 
                         foreach($qry2 as $name){
                             $output.="/{$name->name}</td>";
                         } 
 
                     }
                   
 
                 }
                 }else{
                     $output.="<td> Unknown / Not Updated </td>";
               }
 
             }else{
                 $output.="<td> Unknown / Not Updated </td>";
             }
 
         $output.="<td>";
              if($value->lead_date){ 
                 $output.="{$value->lead_date}"; 
                 }else{  
                     $output.="No date found!";
                 } 
                     $output.="</td>
         <td>";
         if($value->lead_uploaded_by){ 
             $output.="{$value->lead_uploaded_by}";
         }else{  
             $output.="No A/c found!"; 
             }   
                 $output.="</td>
         
         
         </tr>";
          $p++; 
         } }
      
        
    $output .= "</table>";
    
 
    $total_pages = ceil($total_record/$limit_per_page);  
    //echo $total_pages;
   
 $res_page=$offset+$limit_per_page;
   
    $output .="<div id='pagination'style='margin-top:20px;'>
    <div class='col-sm-3' style='margin-top:20px;'>  
					Showing ".  $offset ." to {$res_page} of ". $total_record ." Records
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
