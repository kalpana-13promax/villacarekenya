<?php



require_once('../../config.php');

$boj->check_session();

$view_all = $check->check_permission('properties', 'view_all');

$edit = $check->check_permission('properties', 'edit');

$delete = $check->check_permission('properties', 'delete');

$view_own = $check->check_permission('properties', 'view_own');



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

        

        $qry = $boj->getQuery("SELECT * FROM property_listing WHERE status='5'  

         and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%' OR   property_price LIKE '%{$search_value}%' ) order by id desc  LIMIT {$offset},{$limit_per_page} "); 



$total_record=$boj->count("SELECT * FROM property_listing WHERE status='5'  

and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%'  OR   property_price LIKE '%{$search_value}%')");



        }else{

          $qry = $boj->getQuery("SELECT * FROM property_listing WHERE status='5'  order by id desc  LIMIT {$offset},{$limit_per_page} "); 



          $total_record=$boj->count("SELECT * FROM property_listing WHERE status='5' order by id desc");

    

        }

       

    

  



 

 

 

  $output= "";

  if($qry){

    $output .= '<table class="table table-bordered table-striped mb-none">

    <tr>

    <th>S.No.</th>

    <th style="width:2%">ID</th>

    <th style="width:8%">Image</th>

    <th>Property Title</th>

    <th>Location</th>

    <th>Property Type</th>

    <th>For</th>

    <th>Uploader</th>

    <th style="width:2%">Price</th>

    <th style="width:8%">Action</th>

</tr>';

  

    //  $offset

      if( $qry ){

        $p=1;

        foreach($qry as $value){

          $res= $p+$offset;
          if ($value->project_id) {

            $qry2 = $boj->getQuery("SELECT pro_name FROM project where id = " . $value->project_id);
            $pro_name = $qry2[0]->pro_name;
          } else {
            $pro_name = '';
          }

          $output .="<tr class='gradeC'>

          <td>{$res}</td>

          <td>{$value->id}</td>

          <td>";

        if($value->property_image){

          $output.="<img src='../uploads/{$value->property_image}' width='60'>";

          

         

         }else{

             

            

             $output.="<img src='".DEFAULTIMG."' width='60'>";

             

         }

             $output.="</td>

									

          <td>{$value->property_title}<br>{$value->id}</td>

          

          <td>";

           $qry2 = $boj->getQuery("SELECT pro_name FROM project where id = '$value->project_id'"); 

          

          $output.="{$pro_name} <br /> - {$value->location}</td>

          

          <td>";

        

          $cat = $boj->getid('property_type', $value->category);



          $output.="{$value->property_type} <br /> {$cat[0]->type} <br /> {$value->furnished_status}</td>

      

      

      

          <td>"; 

          if($value->available_for){ 

           $for = $boj->contract('property', $value->available_for);

           if($for)

            $output.="{$for}";

             } 

             $output.="</td>

          <td>";



        

           if($value->reference_source == '1'){

           $agent = $boj->getQuery("SELECT agent_name FROM agent where id = '$value->referance_agent'"  ); 

          if($agent[0]->agent_name){ 

           $output.="{$agent[0]->agent_name}"; 

        }else{

            $output.="Deleted!"; 

        } 

        $output.="<br /><small><i>Employee</i></small>";

           }elseif($value->reference_source == '4'){

           $agent = $boj->getQuery("SELECT name FROM owner where id = '$value->referance_agent'"); 

           if(@$agent[0]->name){

            $output.="{$agent[0]->name} <br /><small><i>Owner</i></small>";

           }else{

              $output.="-----";

           }

         

           }else{

           if( $value->uploader )

           {

            $output.="{$value->uploader}";

           }else{

            $output.="Unknown";

           }

           }

           $output.="<hr>";

          if ($getuserdata->usertype == 'root'){ 

              if($value->owner_id){

                  $owner = $boj->getid('owner', $value->owner_id);

              

               $output.="<a href='owner/owner-view/?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a> </td>";



           }else{ $output.="---"; } }else{

          $output.= "Owner ID: {$value->owner_id}";

          }

          $output.="</td>

          <td align='center' >";

           if($value->property_price){ 

            $output.="{$boj->price($value->property_price)}";

             } if($value->deposit){ 

                $output.="<hr /> Security/Deposit: {$value->deposit}"; }

                $output.="</td><td>";

              

              if ($getuserdata->usertype == 'root' OR $getuserdata->usertype == 'admin'){ 

                $output.="<a  align='center' style='text-align:center' alt='View Detail' title='View Detail' href='property/property-info/?id={$value->id}&&nav=properties'><i class='fa fa-eye'></i></a>";

             

               }else{

              $output.="<a href='#'><i class='fa fa-eye'></i></a>";

              } 

          $output.="</td>

         </tr>";

          $p++; 

            }

        }

        

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

