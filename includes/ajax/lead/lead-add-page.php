<?php
require_once('../../config.php');
$boj->check_session();
$create = $check->check_permission('leads', 'create');
$view_all = $check->check_permission('leads', 'view_all');
$delete = $check->check_permission('leads', 'delete');
  $limit_per_page = 10;
  $page = "";
  if(isset($_POST["page_no"])){
    $page = $_POST["page_no"];
  }else{
    $page = 1;
  }
  $offset = ($page - 1) * $limit_per_page;
  if(isset($_POST['search'])){
    $search_value = $_POST["search"];
    if( $view_all =='true' ){
        $qry = $boj->getQuery("SELECT * FROM leads where (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page} "); 
    }else{
        $qry = $boj->getQuery("SELECT * FROM leads where (update_by = '$getuserdata->username' or lead_uploaded_by = '$getuserdata->username') and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' )  LIMIT {$offset},{$limit_per_page}"); 
    }
  }else{
  if( $view_all =='true' ){
	$qry = $boj->getQuery("SELECT * FROM leads order by id desc LIMIT {$offset},{$limit_per_page} "); 
}else{
	$qry = $boj->getQuery("SELECT * FROM leads where update_by = '$getuserdata->username' or lead_uploaded_by = '$getuserdata->username'  LIMIT {$offset},{$limit_per_page}"); 
}
  }
  $output= "";
  if($qry){
    $output .= ' <table class="table table-bordered table-striped mb-none" >
    <thead>
     <tr>
        <th>Lead ID</th><th>Lead Name</th><th>Contact</th><th>Created By</th>';
          if($delete=='true'){ 
            $output.='<th>Action</th>'; }
    $output.='</tr>
</thead>
<tbody>';
      if( $qry ){
         $p=1;
        foreach($qry as $value){
            $output.= "<tr class='gradeC'>
            <td><a href='leads/lead-add?view={$value->id}&nav=leads'>{$value->id}</a> </td>
            <td><a href='leads/lead-add?view={$value->id}&nav=leads'>{$value->lead_name}</a </td>
                            <td>{$value->lead_contact}</td>
                            <td>{$value->lead_uploaded_by}</td>";
                         if($delete=='true'){ 
        $output.="<td><a href='leads/lead-add?delete={$value->id}&lname={$value->lead_name}'"; 
    echo "<script>onClick=return confirm('Are you sure want to delete?');</script>";
        $output.="><i class='fa fa-trash'></i></a></td>";
 } 
        $output.="</tr>";
          $p++; 
         } }
    $output .= "</table>";
    if(isset($_POST['search'])){
        $search_value = $_POST["search"];
        $query = $boj->getQuery("SELECT COUNT(*) as count FROM leads where (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) ");   
    }else{
$query = $boj->getQuery("SELECT COUNT(*) as count FROM leads ");   
    }
      $total_record=strtoupper(@$query[0]->count);
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
