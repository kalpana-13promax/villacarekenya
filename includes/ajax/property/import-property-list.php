<?php

require_once('../../config.php');
$boj->check_session();
$view_all = $check->check_permission('properties', 'view_all');
$edit = $check->check_permission('properties', 'edit');
$delete = $check->check_permission('properties', 'delete');
$address = $check->check_permission('properties', 'view_address');
$view_assign_property = $check->check_permission('properties', 'view_assign_property');

  $limit_per_page = 10;

  $page = "";
  if(isset($_POST["page_no"])){
    $page = $_POST["page_no"];
  }else{
    $page = 1;
  }
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
//@$offset = ($page - 1) * $limit_per_page;
  if(isset($_POST["search"])){
    $_SESSION['search'] = $_POST["search"];
   //echo  $_SESSION['search'];
   
   }
   

  //---------------------------------------------------------------------------------------------------------

// if(isset($_POST['fillter_user'])){
//     $fillter_user=$_POST['fillter_user'];
//     echo $fillter_user;
// }
$offset = ($page - 1) * $limit_per_page;
@$search_value = $_SESSION["search"];

  
    if($search_value){
        
  $qry = $boj->getQuery("SELECT * FROM import_property where is_add = '0' and ( property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%'  OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%')  order by id desc  LIMIT {$offset},{$limit_per_page} "); 

$total_record=$boj->count("SELECT * FROM import_property  where is_add = '0' and (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%') ");

        }else{
          $qry = $boj->getQuery("SELECT * FROM import_property  where is_add = '0' order by id desc  LIMIT {$offset},{$limit_per_page} "); 

          $total_record=$boj->count("SELECT * FROM import_property where is_add = '0' order by id desc");
    
        }
       
    

    
  

 
 
 
  $output= "";
  if($qry){
    $output .= '<table class="table table-bordered table-striped mb-none">
    <tr>
                                <th>S.No.</th>
								<th>P.ID.</th>
								<th>Property Title</th>
								<th>Address</th>
								<th>Owners Details</th>
								<th>Location</th>
								<th>For</th>
								<th>Type</th>
								<th>Price</th>
								<th>Status</th>
								<th>Action</th>
                                <th>Add Property</th>
</tr>';
  
    //  $offset
      if( $qry ){
        $p=1;
        foreach($qry as $value){
          $res= $p+$offset;
          $output .="<tr class='gradeC'>
          <td>{$res}</td>
             <td><a href='property/property-info/?id={$value->id}' alt='View' title='View'>{$value->id}</a></td>
           
             <td>{$value->property_title}</td>
             <td>{$value->address}</td>

             <td>";
        if($value->owner_name){ $output.="$value->owner_name"; } 
             if($value->owner_contact){ $output.="<hr />$value->owner_contact"  ;
             } 
             $output.="</td>

           
             <td>{$value->sub_location}</td>
             <td>{$value->available_for}</td>
            <td>{$value->property_type}</td>
                 
                 
         
             
             
            
             
           
            
                 

                

             <td>";
        if($value->property_price){ $output.="{$boj->price($value->property_price)}"; } 
             if($value->deposit){ $output.="<hr /> Security/Deposit: {$boj->price($value->deposit)}";
             } 
             $output.="</td>
             <td>";
             if($value->status){ $output.="{$value->status}"; } 
                           $output.="</td>

             <td>";
             $sql1= $boj->getQuery("SELECT * FROM owner where contact = '$value->owner_contact'");
             if(!$sql1)
             {
              $output.="<form action='' method= 'post'>
              <input type= 'hidden' name='p_id' value='{$value->id}'/>
            <input type='submit' name='property-owner' class='btn btn-primary' value='Add Owner'/></form><br>";
             }
             else
             {
              $output.="<button class= 'btn btn-primary' disabled  > Add Owner</button>";
             }

            
            $output.="</td>
            <td>
           
             ";	
            if($edit == 'true'){ 
         	$output.=" <a href='property/import-property-edit/?edit={$value->id}' alt='Edit' title='Edit'><i class='fa fa-plus' aria-hidden='true'></i><span></span></a>";

          } 
             
             
             
            

            
            $output.="
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
