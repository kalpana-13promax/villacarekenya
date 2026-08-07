
<style>
  .ml-2{
    margin-top:5px;
    margin-left:5px;
  }
</style>


<?php



require_once('../../config.php');

$boj->check_session();

$view_all = $check->check_permission('properties', 'view_all');

$edit = $check->check_permission('properties', 'edit');

$delete = $check->check_permission('properties', 'delete');

$address = $check->check_permission('properties', 'view_address');
$view_own = $check->check_permission('properties', 'view_own');


$view_assign_property = $check->check_permission('properties', 'view_assign_property');

$search = '';

$limit_per_page = 10;



$page = "";

if (isset($_POST["page_no"])) {

  $page = $_POST["page_no"];
} else {

  $page = 1;
}

//---------------------------------------------------------



if (isset($_POST["pages"])) {

  $_SESSION['pages'] = $_POST["pages"];
}



@$pages_val = $_SESSION['pages'];

if ($pages_val) {

  $limit_per_page = $pages_val;
} else {

  $limit_per_page = 10;
}

//----------------------------------------------------------------------

//@$offset = ($page - 1) * $limit_per_page;

if (isset($_POST["search"])) {

  $_SESSION['search'] = $_POST["search"];

  //echo  $_SESSION['search'];



}





if (isset($_POST['filter_marking'])) {

  $_SESSION['filter_marking'] = $_POST['filter_marking'];
}



@$filter_marking = $_SESSION['filter_marking'];





if (@$filter_marking) {

  @$filter_marking_search = "mark_color = '" . $filter_marking . " ' ";
}

//  echo $filter_marking_search; 

if (@$filter_marking_search) {

  @$search = " AND " . $filter_marking_search;
}





//---------------------------------------------------------------------------------------------------------



// if(isset($_POST['fillter_user'])){

//     $fillter_user=$_POST['fillter_user'];

//     echo $fillter_user;

// }

$offset = ($page - 1) * $limit_per_page;

@$search_value = $_SESSION["search"];

if (@$view_all == 'true') {



  if ($search_value) {
    if ($getuserdata->username == 'root') {
      $qry = $boj->getQuery("SELECT * FROM property_listing where status!='7' $search 
        and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%' )   order by id desc  LIMIT {$offset},{$limit_per_page} ");

      $total_record = $boj->count("SELECT * FROM property_listing where status!='7' $search 
        and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%' )");
    } else {
      $qry = $boj->getQuery("SELECT * FROM property_listing where status!='7' $search 
        and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%' ) and  FIND_IN_SET('$getuserdata->id', assign_property)   order by id desc  LIMIT {$offset},{$limit_per_page} ");
      $total_record = $boj->count("SELECT * FROM property_listing where status!='7' $search 
        and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%' ) and FIND_IN_SET('$getuserdata->id', assign_property)");
    }
  } else {

    if ($getuserdata->username == 'root') {
      $qry = $boj->getQuery("SELECT * FROM property_listing where status!='7' $search  order by id desc  LIMIT {$offset},{$limit_per_page} ");

      $total_record = $boj->count("SELECT * FROM property_listing where status!='7' $search order by id desc");
    } else {

      $qry = $boj->getQuery("SELECT * FROM property_listing where status!='7' $search  and FIND_IN_SET('$getuserdata->id', assign_property) order by id desc  LIMIT {$offset},{$limit_per_page} ");

      $total_record = $boj->count("SELECT * FROM property_listing where status!='7' $search and FIND_IN_SET('$getuserdata->id', assign_property) order by id desc");
    }
  }
} elseif (@$view_own == 'true') {

  if ($search_value) {
    $qry = $boj->getQuery("SELECT * FROM property_listing where status!='7' AND uploader='$getuserdata->username' and (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%') order by id desc  LIMIT {$offset},{$limit_per_page} ");
    $total_record = $boj->count("SELECT * FROM property_listing where status!='7' AND uploader='$getuserdata->username' and (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%')");
  } else {

    $qry = $boj->getQuery("SELECT * FROM property_listing where status!='7'   AND uploader='$getuserdata->username' order by id desc  LIMIT {$offset},{$limit_per_page} ");
    $total_record = $boj->count("SELECT * FROM property_listing where status!='7'   AND uploader='$getuserdata->username' order by id desc");
  }
}













$output = "";

if ($qry) {

  $output .= '<div class="table-responsive-sm">
      <table class="table table-bordered table-striped mb-none">

          <tr>

          <th>S.No.</th>

                      <th>P.ID.</th>

                      <th>Unit No.</th>

                      <th>Property Title</th>

                      <th>Address</th>

                      

                      <th>Property Remark</th>

                      

                      <th>Location</th>

                      <th>For</th>

                      <th>Type</th>

                      <th>Owner/Agent</th>

                      <th>Size</th>
                      <th>Price</th>

                      <th>Status</th>

                      <th>Action</th>

      </tr>';



  //  $offset

  if ($qry) {

    $p = 1;

    foreach ($qry as $value) {

      $res = $p + $offset;
      if ($value->project_type) {

        $qry2 = $boj->getQuery("SELECT pro_name FROM project where id = " . $value->project_type);
        $pro_name = $qry2[0]->pro_name;
      } else {
        $pro_name = '';
      }
      $output .= "<tr class='gradeC'";

      if ($value->mark_color) {

        $colour = "style='color:white; background:" . $value->mark_color . "'";

        $output .= "{$colour}";
      }



      $output .= "<td></td>

          <td>{$res}</td>

             <td><a href='property/property-info/?id={$value->id}' alt='View' title='View'>{$value->id}</a></td>

             <td>";
$output .= "{$value->unit_no}";
      // if ($value->property_image) {

      //   $output .= "<img src='../uploads/{$value->property_image}' width='60'>";
      // } else {





      //   $output .= "<img src='" . DEFAULTIMG . "' width='60'>";
      // }

      $output .= "</td>

             <td>{$value->property_title}</td> 

             <td>{$value->address}</td>

             

                 <td>{$value->remark}</td>

             <td>";

             if(@$proname){
              $output .= "{$proname} <hr />";
            }
        
              $output .= "
        
                    
        
            {$value->sub_location}, {$value->location}{$value->city}
        

             </td>

             

             <td>";

      if ($value->available_for) {

        $for = $boj->contract('property', $value->available_for);

        if ($for)

          $output .= "{$for}";
      }

      $output .= "</td>

             

             <td>";

      if ($value->property_type) {

        $output .= "{$value->property_type}

              ";



        $cat    =   $boj->getid('property_type', $value->category);

        $output .= "{$cat[0]->type}

               

                 {$value->furnished_status}";
      }

      $output .= "</td>

             

             <td>";

      $ref  =   $boj->getid('source', $value->reference_source);





      if ($value->reference_source == '1') {

        $agent = $boj->getid("agent", $value->referance_agent);

        if ($agent[0]->agent_name) {

          $output .= "{$agent[0]->agent_name}";
        } else {
          $output .= "Deleted!";
        }
        $output .= "<br /><small><i>{$ref[0]->source_name}</i></small><br />";
      }



      if ($getuserdata->usertype == 'root') {

        if ($value->owner_id) {

          $owner = $boj->getid('owner', $value->owner_id);



          $output .= "<a href='owner/owner-view/?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a>

<br />";

          // $ph = $owner[0]->c_code. $owner[0]->contact;

          // echo $boj->call_to($ph, $value->id) .' | ';

          // echo $boj->whatsapp_modal($ph).' | ';

          // echo $boj->mail_to($owner[0]->mail); 



        } else {
          $output .= '---';
        }
      }

      $output .= "</td>
      <td>{$value->size} {$value->measurement}</td>

             <td>";

      if ($value->property_price) {
        $output .= "{$boj->price($value->property_price)}";
      }
if($value->is_perunit){
  $total = $value->size *$value->property_price;
  $output .= " Per {$value->measurement} <hr> {$boj->price($total)}";

}
      if ($value->deposit) {
        $output .= "<hr /> Security/Deposit: {$boj->price($value->deposit)}";
      }

      $output .= "</td>

             <td>";

      if ($value->status == '4') {
        $output .= "Unverified";
      }

      if ($value->status == '3') {
        $output .= "On-Hold";
      }

      if ($value->status == '2') {
        $output .= "Booked";
      }

      if ($value->status == '1') {
        $output .= "Published";
      }

      if ($value->status == '0') {
        $output .= "Inactive";
      }

      $output .= "</td>



        <td>
   

             <div style='display:flex;'>
             
                  
                  <a href='../property-info/?token={$value->property_id}' target='_blank'  class='ml-2'><i class='fa fa-share-alt' aria-hidden='false'></i></a>

                  <a alt='Book' title='Book' href='property/book-property/?id={$value->id}' class='ml-2'>";

            if ($value->status == '1') {

              $output .= " <i class='fa fa-shopping-cart' aria-hidden='false'></i>";
            }

            $output .= "</a> 

                  <a href='property/property-info/?id={$value->id}' alt='View' title='View' class='ml-2'><i class='fa  fa-eye' aria-hidden='false'></i><span></span></a> ";

            if ($edit == 'true') {

              $output .= " <a href='property/property-edit/?edit={$value->id}' alt='Edit' title='Edit' class='ml-2'><i class='fa  fa-edit' aria-hidden='false'></i><span></span></a>";
            }
            if ($delete == 'true') {

              $output .= " <a href='property/property-list/?delete={$value->id}' onClick='return confirm('Are you sure want to delete?')'  alt='Delete' title='Delete' class='ml-2'><i class='fa  fa-trash' aria-hidden='false'></i><span></span></a>";
            }

            $output .= "</td>
       </div>

         </tr>";

      $p++;
    }
  }





  $output .= "</table></div>";









  // $total_pages = ceil($total_record / $limit_per_page);

  // //echo $total_pages;



  // $res_page = $offset + $limit_per_page;



  // $output .= "<div id='pagination'style='margin-top:20px;'>

  //   <div class='col-sm-3' style='margin-top:20px;'>  

	// 				Showing " .  $offset . " to {$res_page} of " . $total_record . " Records

	// 				</div> <ul class='pagination pagination-md pull-right' style='display:inline-block; margin:0;'>";

  // if ($page >= 2) {

  //   $j = $page - 1;

  //   $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'><< Previous</span></a></li>";
  // }

  // for ($j = 1; $j <= $total_pages; $j++) {



  //   if ($j == $page) {

  //     $class_name = "active";
  //   } else {

  //     $class_name = "";
  //   }

  //   $inc = $page + 1;

  //   $dec = $page - 1;

  //   if ($j == $page) {

  //     $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";
  //   } else {

  //     if ($j >= $inc && $j <= $inc) {

  //       $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";
  //     }
  //   }

  //   if ($j >= $dec && $j <= $dec) {

  //     $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";
  //   }
  // }
  // if ($page < $total_pages) {

  //   $j = $page + 1;

  //   $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'>Next >></span></a></li>";
  // }



  // $output .= "</ul>";



  // echo $output;

  
  $response['data'][] = [
    's_no' => $res,
    'unit_no' => $value->unit_no,
    'property_title' => $value->property_title,
    'address' => $value->address,
    'remark' => $value->remark,
    'property_location' =>  `{$value->sub_location}, {$value->location}{$value->city}`,
    'available_for' => $for,
    'property_type' => $value->property_type,
    'property_price' => $cat[0]->type,
    'status' => $value->furnished_status,
    'owner_name' => $owner[0]->name,
    'size' => `{$value->size} {$value->measurement}`,
    'property_price' => $value->property_price,
    'status' => $value->status,
    // 'action' => $action
];

}else{
  $response['data'] = []; // Explicitly set an empty array for no records
  $response['recordsFiltered'] = 0; // Set filtered records count to 0
}
// Return the response as JSON
echo json_encode($response);
//  else {

//   echo "<h2>No Record Found.</h2>";
// }
