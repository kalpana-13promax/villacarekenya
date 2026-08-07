<?php



require_once('../../config.php');
error_reporting(0);

$boj->check_session();

$view_all = $check->check_permission('properties', 'view_all');

$edit = $check->check_permission('properties', 'edit');

$delete = $check->check_permission('properties', 'delete');

$address = $check->check_permission('properties', 'view_address');



// $limit_per_page = 10;



// $page = "";

// if (isset($_POST["page_no"])) {

//   $page = $_POST["page_no"];

// } else {

//   $page = 1;

// }

// //---------------------------------------------------------



// if (isset($_POST["pages"])) {

//   $_SESSION['pages'] = $_POST["pages"];





// }



// @$pages_val = $_SESSION['pages'];

// if ($pages_val) {

//   $limit_per_page = $pages_val;

// } else {

//   $limit_per_page = 10;

// }

// //----------------------------------------------------------------------

// //@$offset = ($page - 1) * $limit_per_page;

// if (isset($_POST["search"])) {

//   $_SESSION['search'] = $_POST["search"];

//   //echo  $_SESSION['search'];



// }





// //---------------------------------------------------------------------------------------------------------



// // if(isset($_POST['fillter_user'])){

// //     $fillter_user=$_POST['fillter_user'];

// //     echo $fillter_user;

// // }

// $offset = ($page - 1) * $limit_per_page;

// @$search_value = $_SESSION["search"];

// if (@$view_all == 'true') {



//   if ($search_value) {



//     $qry = $boj->getQuery("SELECT * FROM property_listing where status='1' 

//          and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR property_location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%') order by id desc  LIMIT {$offset},{$limit_per_page} ");



//     $total_record = $boj->count("SELECT * FROM property_listing where status='1' 

// and  (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR property_location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%')");



//   } else {

//     $qry = $boj->getQuery("SELECT * FROM property_listing where status='1' order by id desc  LIMIT {$offset},{$limit_per_page} ");



//     $total_record = $boj->count("SELECT * FROM property_listing where status='1' order by id desc");



//   }



// } elseif (@$view_own == 'true') {



//   if ($search_value) {



//     $qry = $boj->getQuery("SELECT * FROM property_listing where status='1' 

//               AND uploader='$getuserdata->username' and (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR property_location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%') order by id desc  LIMIT {$offset},{$limit_per_page} ");



//     $total_record = $boj->count("SELECT * FROM property_listing where status='1' 

//     AND uploader='$getuserdata->username' and (property_title LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR property_location LIKE '%{$search_value}%' OR available_for LIKE '%{$search_value}%' OR address LIKE '%{$search_value}%')");



//   } else {

//     $qry = $boj->getQuery("SELECT * FROM property_listing where status='1'   AND uploader='$getuserdata->username' order by id desc  LIMIT {$offset},{$limit_per_page} ");



//     $total_record = $boj->count("SELECT * FROM property_listing where status='1'   AND uploader='$getuserdata->username' order by id desc");



//   }

// }













// $output = "";

// if ($qry) {

//   $output .= '<table class="table table-bordered table-striped mb-none">

//     <tr>

//     <th>S.No.</th>

// 								<th>P.ID.</th>

// 								<th>Image</th>

// 								<th>Property Title</th>

// 								<th>Address</th>



// 								<th>Property Remark</th>



// 								<th>Location</th>

// 								<th>For</th>

// 								<th>Type</th>

// 								<th>Owner/Agent</th>

// 								<th>Price</th>

// 								<th>Status</th>

// 								<th>Action</th>

// </tr>';



//   //  $offset

//   if ($qry) {

//     $p = 1;

//     foreach ($qry as $value) {

//       $res = $p + $offset;
//       if ($value->project_type) {

//         $qry2 = $boj->getQuery("SELECT pro_name FROM project where id = " . $value->project_type);
//         $pro_name = $qry2[0]->pro_name;
//       } else {
//         $pro_name = '';
//       }
//       $output .= "<tr class='gradeC'>

//           <td>{$res}</td>

//              <td><a href='property/property-info/?id={$value->id}' alt='View' title='View'>{$value->id}</a></td>

//              <td>";

//       if ($value->property_image) {

//         $output .= "<img src='../uploads/{$value->property_image}' width='60'>";





//       } else {





//         $output .= "<img src='" . DEFAULTIMG . "' width='60'>";



//       }



//       $output .= "</td>

//              <td>{$value->property_title}</td>

//              <td>{$value->address}</td>



//                  <td>{$value->remark}</td>







//              <td>";



//       $output .= "{$pro_name}

//             <hr />

//                   {$value->property_location}

//              </td>



//              <td>";

//       if ($value->available_for) {

//         $for = $boj->contract('property', $value->available_for);

//         if ($for)

//           $output .= "{$for}";

//       }

//       $output .= "</td>



//              <td>";

//       if ($value->property_type) {

//         $output .= "{$value->property_type}

//               <hr />";



//         $cat = $boj->getid('property_type', $value->category);

//         $output .= "{$cat[0]->type}

//                  <hr />

//                  {$value->furnished_status}";



//       }

//       $output .= "</td>



//                   <td>";





//       if ($value->reference_source == '1') {

//         $agent = $boj->getQuery("SELECT agent_name FROM agent where id = '$value->referance_agent'");

//         if ($agent[0]->agent_name) {

//           $output .= "{$agent[0]->agent_name}";
//         } else {

//           $output .= "Deleted!";

//         }

//         $output .= "<br /><small><i>Employee</i></small>";

//       } elseif ($value->reference_source == '4') {

//         @$agent = $boj->getQuery("SELECT name FROM owner where id = '$value->referance_agent'");

//         if (@$agent[0]->name) {

//           $output .= "{$agent[0]->name} <br /><small><i>Owner</i></small>";

//         } else {

//           $output .= "-----";

//         }

//       } else {

//         if ($value->uploader) {

//           $output .= "{$value->uploader}";

//         } else {

//           $output .= "Unknown";

//         }

//       }

//       $output .= "<hr>";

//       if ($getuserdata->usertype == 'root') {

//         if ($value->owner_id) {

//           $owner = $boj->getid('owner', $value->owner_id);



//           $output .= "<a href='owner/owner-view.php?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a> </td>";

//         } else {

//           $output .= "---";

//         }
//       } else {

//         $output .= "Owner ID: {$value->owner_id}";

//       }
//       ;



//       $output .= "</td>



//              <td>";

//       if ($value->property_price) {
//         $output .= "{$boj->price($value->property_price)}";
//       }

//       if ($value->deposit) {
//         $output .= "<hr /> Security/Deposit: {$boj->price($value->deposit)}";

//       }

//       $output .= "</td>

//              <td>";

//       if ($value->status == '4') {
//         $output .= "Unverified";
//       }

//       if ($value->status == '3') {
//         $output .= "On-Hold";
//       }

//       if ($value->status == '2') {
//         $output .= "Booked";
//       }

//       if ($value->status == '1') {
//         $output .= "Published";
//       }

//       if ($value->status == '0') {
//         $output .= "Inactive";
//       }





//       $output .= "</td>



//              <td>

//              <a href='../property-info/?token={$value->property_id}'  target='_blank'  class='btn btn-success'>Share</a>

//               <br/> <a alt='Book' title='Book'href='property/book-property/?id={$value->id}'>";

//       if ($value->status == '1') {

//         $output .= "<i class='fa fa-shopping-cart'></i> |";

//       }

//       $output .= "</a> 

//              <a href='property/property-info/?id={$value->id}' alt='View' title='View'><i class='fa  fa-eye' aria-hidden='false'></i><span></span></a> ";

//       if ($edit == 'true') {

//         $output .= "| <a href='property/property-edit/?edit={$value->id}' alt='Edit' title='Edit'><i class='fa  fa-edit' aria-hidden='false'></i><span></span></a>";



//       }
//       if ($delete == 'true') {

//         $output .= "| <a href='?delete={$value->id}' onClick='return confirm('Are you sure want to delete?')'  alt='Delete' title='Delete'><i class='fa  fa-trash' aria-hidden='false'></i><span></span></a>";

//       }

//       $output .= "</td>

//          </tr>";

//       $p++;

//     }
//   }





//   $output .= "</table>";









//   $total_pages = ceil($total_record / $limit_per_page);

//   //echo $total_pages;



//   $res_page = $offset + $limit_per_page;



//   $output .= "<div id='pagination'style='margin-top:20px;'>

//     <div class='col-sm-3' style='margin-top:20px;'>  

// 					Showing " . $offset . " to {$res_page} of " . $total_record . " Records

// 					</div> <ul class='pagination pagination-md pull-right' style='display:inline-block; margin:0;'>";





//   if ($page >= 2) {

//     $j = $page - 1;

//     $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'><< Previous</span></a></li>";

//   }

//   for ($j = 1; $j <= $total_pages; $j++) {



//     if ($j == $page) {

//       $class_name = "active";



//     } else {

//       $class_name = "";

//     }

//     $inc = $page + 1;

//     $dec = $page - 1;

//     if ($j == $page) {

//       $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";

//     } else {

//       if ($j >= $inc && $j <= $inc) {

//         $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";

//       }

//     }

//     if ($j >= $dec && $j <= $dec) {

//       $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";

//     }

//   }
//   if ($page < $total_pages) {

//     $j = $page + 1;

//     $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'>Next >></span></a></li>";

//   }



//   $output .= "</ul>";



//   echo $output;

// } else {

//   echo "<h2>No Record Found.</h2>";

// }




$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'] ?? '';
$orderColumn = $_POST['order'][0]['column'];
$orderDir = $_POST['order'][0]['dir'] ?? 'DESC';

$columns = ['pl.id', 'pl.property_title', 'l.location', 'c.city', 's.sub_location', 'o.name'];
$orderBy = $columns[$orderColumn] ?? 'pl.id';

// Base FROM clause with joins
$baseSQL = "FROM property_listing pl
JOIN city c ON c.id = pl.city
JOIN locations l ON l.id = pl.location
JOIN sub_location s ON s.id = pl.sub_location
JOIN owner o ON o.id = pl.owner_id
LEFT JOIN seo_data sd ON sd.related_id = pl.id AND sd.type = 'property'
WHERE 1=1 and pl.status='1'";

// Search query
$searchQuery = "";
if (!empty($search)) {
  $searchEscaped = $boj->real_escape_string($search);
  $searchQuery .= " AND (
        pl.property_title LIKE '%$searchEscaped%' OR 
        l.location LIKE '%$searchEscaped%' OR 
        c.city LIKE '%$searchEscaped%' OR 
        s.sub_location LIKE '%$searchEscaped%' OR
        o.name LIKE '%$searchEscaped%'
    )";
}

// Total records count
$totalRecordsRes = $boj->getQuery("SELECT COUNT(DISTINCT pl.id) as count $baseSQL");
$totalRecords = $totalRecordsRes[0]->count ?? 0;

// Filtered records count
$totalFilteredRes = $boj->getQuery("SELECT COUNT(DISTINCT pl.id) as count $baseSQL $searchQuery");
$totalFiltered = $totalFilteredRes[0]->count ?? 0;

// Main data query
$dataSQL = "SELECT 
    pl.*,
    c.city,
    l.location,
    s.sub_location,
    o.name AS owner_name,
    sd.slug
    $baseSQL 
    $searchQuery 
ORDER BY $orderBy $orderDir 
LIMIT $start, $length";

$rows = $boj->getQuery($dataSQL) ?? [];
$path = IMGPATH;
$data = [];
$i = $start + 1;

$statusColors = [
  'success',
  'primary',
  'warning',
  'info',
  'danger',
  'default',

];

foreach ($rows as $row) {
  $actions = "
        <a href='property/property-view/?id={$row->id}'><i class='fa fa-eye'></i></a> |
        <a href='property/property-edit/?id={$row->id}'><i class='fa fa-edit'></i></a> |
        <a href='property/property-list?delete={$row->id}' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i></a>
    ";
  $file = $path . $row->property_image;
  if (!empty($row->property_image) && file_exists($file)) {
    $img = $file;
  } else {

    $img = DEFAULTIMG;
  }



  $available_for = !empty($row->available_for) ? $boj->contract('property', $row->available_for) : '';

  //type

  $type = $row->property_type ?? '';
  $category = $boj->getid('property_type', $row->category);
  $hr = '<hr>';
  $type .= $hr . $category[0]->type . $hr . $row->furnished_status . $hr;

  $ref = $boj->getid('source', $row->reference_source);
  $agentOwner = '';
  if ($row->reference_source == '1' && !empty($value->referance_agent)) {

    $agent = $boj->getid("agent", $value->referance_agent);

    if ($agent[0]->agent_name) {

      $agentOwner .= $agent[0]->agent_name . $hr;
    }
    ;
  } else {
    $agentOwner .= "Deleted!$hr";
  }
  $agentOwner .= "<small><i>{$ref[0]->source_name}</i></small>$hr";
  if ($getuserdata->usertype == 'root') {
    if ($row->owner_id) {

      $owner = $boj->getid('owner', $row->owner_id);



      $agentOwner .= "<a href='owner/owner-view/?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a>";

    }
  }

  $status = $boj->getid('property_status', $row->status ?? '') ?? [];
  $color = $statusColors[$row->status - 1] ?? 'default';

  $status = "<span class='label label-$color ' style='font-size:inherit'>" . $status[0]->status_name . "</span>";

  //address

  if (strcasecmp($address, 'true') == 0) {
    $address = $row->address;
  } else {
    $address = '';
  }
  $data[] = [
    $i++,
    $row->id,
    "<img src='" . $img . "' style='width: 100px;height: 80px;'>",
    $row->property_title ?? '',
    $address,
    ucwords($row->city ?? ''),
    ucwords($row->location ?? '') . '/' . ucwords($row->sub_location ?? ''),

    ucwords($row->remark ?? ''),
    ucwords($row->available_for ?? ''),
    ucwords($type ?? ''),
    ucwords($agentOwner ?? ''),
    ucwords($boj->price($row->property_price) ?? 0) . $hr . 'Security/Deposit: ' . $boj->price($value->deal_price ?? 0),
    $status ?? '',

    $boj->shareEntity($row->id, 'property')
  ];
}

echo json_encode([
  "draw" => $draw,
  "recordsTotal" => $totalRecords,
  "recordsFiltered" => $totalFiltered,
  "data" => $data
]);


?>