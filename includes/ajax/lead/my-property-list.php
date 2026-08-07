<?php



require_once('../config.php');

$boj->check_session();

$view_all = $check->check_permission('properties', 'view_all');

$edit = $check->check_permission('properties', 'edit');

$delete = $check->check_permission('properties', 'delete');

$view_own = $check->check_permission('properties', 'view_own');



$owner_all = $check->check_permission('owners', 'view_all');

$owner_own = $check->check_permission('owners', 'view_own');

$view_assign_property = $check->check_permission('properties', 'view_assign_property');



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

    if(isset($_POST['search'])){

        $search_value = $_POST["search"];

        $qry = $boj->getQuery("SELECT * FROM property_listing where (status != '7' AND referance_agent= '$getuserdata->id' ) or (status != '7' AND uploader = '$getuserdata->username')

         and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%') order by id desc  LIMIT {$offset},{$limit_per_page} "); 

        }else{

          $qry = $boj->getQuery("SELECT * FROM property_listing where (status != '7' AND referance_agent= '$getuserdata->id') or (status != '7' AND uploader = '$getuserdata->username') order by id desc  LIMIT {$offset},{$limit_per_page} "); 

    

        }

       

  



 

 

 

  $output= "";

  if($qry){

    $output .= '<table class="table table-bordered table-striped mb-none">

    <tr>

    <th>S.No.</th>

								<th>P.ID.</th>

								<th>Image</th>

								<th>Property Title</th>

								<th>Address</th>

								

								<th>Property Remark</th>

								

								<th>Location</th>

								<th>For</th>

								<th>Type</th>

								<th>Owner/Agent</th>

								<th>Price</th>

								<th>Status</th>

								<th>Action</th>

</tr>';

  

    //  $offset

      if( $qry ){

         $p=1;

        foreach($qry as $value){

             $output .="<tr class='gradeC'>

             <td>{$p}</td>

             <td><a href='property/property-info/?id={$value->id}' alt='View' title='View'>{$value->id}</a></td>

             <td><img src='../uploads/{$value->property_image}' width='60'></td>

             <td>{$value->property_title}</td>

             <td>{$value->address}</td>

             

                 <td>{$value->remark}</td>

                 

                 

         

             <td>";

              $qry2 = $boj->getid('project',$value->project_type); 

             $output.="{$qry2[0]->pro_name}

            <hr />

                  {$value->location}

             </td>

             

             <td>"; 

             if($value->available_for){ 

               $output.="$value->available_for";

                } 

                $output.="</td>

             

             <td>";

           if($value->property_type){ 

                 $output.="{$value->property_type}

              <hr />";



                 $cat    =   $boj->getid('property_type', $value->category);

                 $output.="{$cat[0]->type}

                 <hr />

                 {$value->furnished_status}";



                  } 

                  $output.="</td>

             

             <td>";

                 $ref  =   $boj->getid('source', $value->reference_source);

                

                 

                 if($value->reference_source == '1'){

              $agent = $boj->getid("agent",$value->referance_agent); 

             if($agent[0]->agent_name){ 

                $output.="{$agent[0]->agent_name}";

            }else{  $output.="Deleted!"; 

            } $output.="<br /><small><i>{$ref[0]->source_name}</i></small><br />";

              }

                 

                 if ($owner_all=='true' OR $owner_own=='true'){ 

                 if($value->owner_id){

                     $owner = $boj->getid('owner', $value->owner_id);

             

                     $output.="<a href='owner-view.php?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a>

<br />";

                    //  $ph = $owner[0]->c_code. $owner[0]->contact;

                    //  echo $boj->call_to($ph, $value->id) .' | ';

                    //  echo $boj->whatsapp_modal($ph).' | ';

                    //  echo $boj->mail_to($owner[0]->mail); 

               

        }else{ $output.="---"; } } 

$output.="</td>

             <td>";

        if($value->property_price){ $output.="{$boj->price($value->property_price)}"; } 

             if($value->deposit){ $output.="<hr /> Security/Deposit: {$boj->price($value->deposit)}";

             } 

             $output.="</td>

             <td>";

            if($value->status == '4'){ $output.="Unverified"; }

             if($value->status == '3'){  $output.="On-Hold"; }

             if($value->status == '2'){ $output.="Booked"; }

             if($value->status == '1'){ $output.="Published"; }

             if($value->status == '0'){ $output.="Inactive"; }

             $output.="</td>



             <td>

             <a href='property/property-info.php?id={$value->id}' alt='View' title='View'><i class='fa  fa-eye' aria-hidden='false'></i><span></span></a> ";	

            if($edit == 'true'){ 

         	$output.="| <a href='property/property-edit.php?edit={$value->id}' alt='Edit' title='Edit'><i class='fa  fa-edit' aria-hidden='false'></i><span></span></a>";



          } if($delete=='true'){ 

             	$output.="| <a href='?delete={$value->id}' onClick='return confirm('Are you sure want to delete?')'  alt='Delete' title='Delete'><i class='fa  fa-trash' aria-hidden='false'></i><span></span></a>"; 

             } 

             $output.="</td>

         </tr>";

          $p++; 

         } }

      

        

    $output .= "</table>";

    if(isset($_POST['search'])){

      $search_value = $_POST["search"];

        

        $query = $boj->getQuery("SELECT COUNT(*) as count  FROM property_listing where (status != '7' AND referance_agent= '$getuserdata->id') or (status != '7' AND uploader = '$getuserdata->username')  or property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%' OR available_for  LIKE '%{$search_value}%' "); 

    }else{

      $query = $boj->getQuery("SELECT COUNT(*) as count FROM property_listing where (status != '7' AND referance_agent= '$getuserdata->id') or (status != '7' AND uploader = '$getuserdata->username') order by id desc"); 

    }

       

    

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

