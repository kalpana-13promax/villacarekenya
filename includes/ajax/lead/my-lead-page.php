<script>



</script>

<?php



require_once('../../config.php');

if (!function_exists('hex2rgba')) {
  function hex2rgba($color, $alpha = 0.15)
  {
    $color = trim($color);
    if (strpos($color, '#') === 0) {
      $color = substr($color, 1);
    }
    if (strlen($color) === 3) {
      $r = hexdec(str_repeat(substr($color, 0, 1), 2));
      $g = hexdec(str_repeat(substr($color, 1, 1), 2));
      $b = hexdec(str_repeat(substr($color, 2, 1), 2));
    } elseif (strlen($color) === 6) {
      $r = hexdec(substr($color, 0, 2));
      $g = hexdec(substr($color, 2, 2));
      $b = hexdec(substr($color, 4, 2));
    } else {
      return $color; // Fallback for non-hex
    }
    $alpha = max(0, min(1, floatval($alpha)));
    return "rgba({$r}, {$g}, {$b}, {$alpha})";
  }
}


$boj->check_session();

$assign = $check->check_permission('leads', 'assign_leads');
$delete = $check->check_permission('leads', 'delete');

if (!empty($_POST['pages'])) {
  $_SESSION['pages'] = $_POST['pages'] ?? 10;
}

if ($_POST['pages'] == 'all') {
  $_SESSION['pages'] = 99;
}
$limit_per_page = $_SESSION['pages'] ?? 10;
$search = '';





if (isset($_POST['convert_search'])) {

  $_SESSION['convert'] = $_POST['convert_search'];

  //echo $_SESSION['convert']; 

}

if (isset($_POST['contract_search'])) {

  $_SESSION['contract'] = $_POST['contract_search'];

  //echo  $_SESSION['contract']; 

}

if (isset($_POST['filter_call_search'])) {

  $_SESSION['filter_call'] = $_POST['filter_call_search'];

  // echo $_SESSION['filter_call']; 

}

if (isset($_POST['fillter_search'])) {

  $_SESSION['filter'] = $_POST['fillter_search'];

  //echo  $_SESSION['filter']; 

}



if (isset($_POST['filter_location'])) {

  $_SESSION['filter_location'] = $_POST['filter_location'];

  // echo  $_SESSION['filter_location']; 

}

if (isset($_POST['filter_u'])) {

  $_SESSION['filter_u'] = $_POST['filter_u'];

}



if (isset($_POST['min'])) {

  $_SESSION['min'] = $_POST['min'];

}

if (isset($_POST['max'])) {

  $_SESSION['max'] = $_POST['max'];

}

if (isset($_POST['marking'])) {

  $_SESSION['marking'] = $_POST['marking'];

}

if (isset($_POST['ltype'])) {

  $_SESSION['ltype'] = $_POST['ltype'];

}

if (isset($_POST['from_date']) and isset($_POST['to_date'])) {

  $_SESSION['from_date'] = $_POST['from_date'];

  $_SESSION['to_date'] = $_POST['to_date'];

}

@$filter_user = $_SESSION['contract'];

@$filter = $_SESSION['filter'];

@$filter_call = $_SESSION['filter_call'];

@$fillter_location = $_SESSION['filter_location'];

@$filter_u = $_SESSION['filter_u'];

@$convert = $_SESSION['convert'];

@$min = $_SESSION['min'];

@$max = $_SESSION['max'];

@$marking = $_SESSION['marking'];

@$ltype = $_SESSION['ltype'];



@$start_Date = $_SESSION['from_date'];

@$end_date = $_SESSION['to_date'];





// echo $start_Date;

// echo $end_date;

//----------------------------------------------------------------------

if ($convert) {

  $converted = " AND reference='" . $convert . "'";

} else {

  $converted = '';

}

@$contract = $_SESSION['contract'];

if ($contract) {

  $contracted = " AND category='" . $contract . "'";

} else {

  $contracted = '';

}





if ($start_Date and $end_date) {

  // WHERE 



  $filter_date_range = "lead_date BETWEEN '" . $start_Date . " 00:00:00' AND '" . $end_date . "23:59:59'";



}



if ($filter_user) {

  @$filter_user_search = " category = '" . $filter_user . "'";

}

if ($filter) {

  @$filter_search = "  lead_status = '" . $filter . " ' ";

}

if ($filter_call) {

  @$filter_call_search = "  lead_call_status = '" . $filter_call . " ' ";

}

if ($fillter_location) {

  @$fillter_location_search = "  required_property_location	 = '" . $fillter_location . " ' ";

}

if ($filter_u) {

  @$filter_u_search = "  assign_to = " . $filter_u . "  ";

}



if (@$min && !$max) {



  @$cost_search = "client_budget_min >= " . $min;

}



if (@$max && !$min) {





  @$cost_search = "client_budget_max <= " . $max;

}

if (@$min && $max) {

  @$cost_search = " ( client_budget_min >= " . $min . " AND  client_budget_max <=" . $max . ")";





}





if ($marking) {

  @$marking_search = "mark_color = '" . $marking . " ' ";

}



if ($ltype == 'neutral') {

  @$ltype_search = "(lead_type = '' OR lead_type IS NULL) AND (lead_type NOT IN ('hot', 'warm', 'cold'))";



} elseif ($ltype != '') {

  @$ltype_search = "lead_type = '" . $ltype . " ' ";



}







if (@$filter_user_search) {

  @$search = " AND " . $filter_user_search;

}



if (@$filter_search) {

  if (@$filter_user_search || @$filter_search) {

    @$search .= " AND ";

  }

  @$search .= $filter_search;

}

if (@$filter_call_search) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search) {

    @$search .= " AND ";

  }

  @$search .= $filter_call_search;

}

if (@$fillter_location_search) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search || @$fillter_location_search) {

    @$search .= " AND ";

  }

  @$search .= $fillter_location_search;

}

if (@$filter_price_search) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search || @$fillter_location_search || @$filter_price_search) {

    @$search .= " AND ";

  }

  @$search .= $filter_price_search;

}

if (@$marking_search) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search || @$fillter_location_search || @$filter_price_search || @$marking_search) {

    @$search .= " AND ";

  }

  @$search .= $marking_search;

}

if (@$filter_u_search) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search || @$fillter_location_search || @$filter_price_search || @$filter_u_search) {

    @$search .= " AND ";

  }

  @$search .= $filter_u_search;

}

if (@$cost_search) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search || @$fillter_location_search || @$filter_price_search || @$filter_u_search || @$cost_search) {

    @$search .= " AND ";

  }

  @$search .= $cost_search;

}

if (@$filter_date_range) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search || @$fillter_location_search || @$filter_price_search || @$filter_u_search || @$cost_search || @$filter_date_range) {

    @$search .= " AND ";

  }

  @$search .= $filter_date_range;

}

if (@$ltype_search) {

  if (@$filter_user_search || @$filter_search || @$filter_call_search || @$fillter_location_search || @$filter_price_search || @$filter_u_search || @$cost_search || @$ltype_search) {

    @$search .= " AND ";

  }

  @$search .= $ltype_search;

}





//   echo $search;



$page = "";

if (isset($_POST["page_no"])) {

  $page = $_POST["page_no"];

} else {

  $page = 1;

}





//----------------------------------------------------------------------------------------------

if (isset($_POST["search"])) {

  $_SESSION['search'] = $_POST["search"];





}





//---------------------------------------------------------------------------------------------------------



$offset = ($page - 1) * $limit_per_page;

@$search_value = $_SESSION["search"];

if ($search_value) {



  $qry = $boj->getQuery("SELECT * FROM leads where  ( lead_uploaded_by='$getuserdata->username' or assign_to='$getuserdata->id') $contracted $converted $search and  lead_status != 'not-interested' and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' OR furnished_status LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page}");

  // echo ("SELECT * FROM leads where  ( lead_uploaded_by='$getuserdata->username' or assign_to='$getuserdata->id') $contracted $converted $search and  lead_status != 'not-interested' and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' OR furnished_status LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page}");

  $total_record = $boj->count("SELECT * FROM leads where  ( lead_uploaded_by='$getuserdata->username' or assign_to='$getuserdata->id') $contracted $converted $search and lead_status != 'not-interested' and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' OR furnished_status LIKE '%{$search_value}%') ");

} else {

  $qry = $boj->getQuery("SELECT * FROM leads where  ( lead_uploaded_by='$getuserdata->username' or assign_to='$getuserdata->id') $contracted $converted $search and lead_status != 'not-interested' order by id desc LIMIT {$offset},{$limit_per_page} ");

  // echo ("SELECT * FROM leads where  ( lead_uploaded_by='$getuserdata->username' or assign_to='$getuserdata->id') $contracted $converted $search and lead_status != 'not-interested' order by id desc LIMIT {$offset},{$limit_per_page}");



  $total_record = $boj->count("SELECT * FROM leads where  ( lead_uploaded_by='$getuserdata->username' or assign_to='$getuserdata->id') $contracted $converted $search and lead_status != 'not-interested' order by id desc");

}







$output = "";



if ($qry) {

  $output .= '<table class="table table-bordered table-striped mb-none my-lead12">

      <tr>

      <th><input type="checkbox" class="checkAll" id="checkAll"></th>

                      <th>#</th>  

                        <th>ID</th>

                        <th>Name</th>

                        <th>Contact</th>

                        <th>Requirement</th>

                        <th>Budget</th>
                        <th>Remarks</th>


                     

                        <th>Source</th>

                        <th>Matched</th>

                        <th>Created By </th>

                        <th style="width:110px">Action</th>

      </tr>';



  //  $offset

  if ($qry) {

    $pp = 1;

    foreach ($qry as $value) {

      $output .= "<tr class='gradeC'";

      if ($value->mark_color) {
        $bgColor = hex2rgba($value->mark_color, 0.16);
        $colour = "style='color:black; background-color:" . $bgColor . "'";
        $output .= "{$colour}";
      }

      $output .= "<td></td>

            <td><input type='checkbox' class='select-checkbox' value='{$value->id}'></td> 

            <td>";

      $res = $pp + $offset;

      $ph = $value->lead_contact;
      $html12='';
      if (!empty($value->project)) {
    $html12 .= '<a href="javascript:void(0);" class="btn btn-xs btn-info view-project" data-project-id="' . htmlspecialchars($value->project) . '" style="margin:0px 2px">
                <i class="fa fa-info-circle" style="cursor:pointer;" data-toggle="tooltip" data-placement="right" title="View Project"></i>
              </a>';
} if (!empty($value->interested_property)) {
    $html12 .= '<a href="javascript:void(0);" class="btn btn-xs btn-info view-property" data-property-id="' . htmlspecialchars($value->interested_property ?? '') . '" title="View property">
                <i class="fa fa-info-circle" style="cursor:pointer;" data-toggle="tooltip" data-placement="right" title="View interested_property"></i>
              </a>';
}

							
      $output .= "{$res}</td><td>{$value->id} </td>

            <td style='width:30px'> <a class='' href='leads/lead-edit/?edit={$value->id}&nav=leads' style='text-align:center'>{$value->lead_name}</a>{$html12}

<br>";

      ?>

      <?php
      $phoneLink = 'tel:' . $ph;
      $emailLink = 'mailto:' . $value->lead_mail;

      // Start action container
      $output .= "<div class='enquiry-actions'>";

      // Phone
      $output .= "<a href='{$phoneLink}' class='action-btn phone' title='Call'>
               <i class='fa fa-phone'></i>
            </a>";
      if (!empty($value->lead_mail)) {
        // Email
        $output .= "<a href='{$emailLink}' target='_blank' class='action-btn email' title='Send Email'>
               <i class='fa fa-envelope'></i>
            </a>";
      }

      // WhatsApp
      $data = $boj->getQuery("SELECT * FROM api WHERE api_status = '1'");
      if ($data) {
        $output .= "<a href='#myModal' 
                   data-id='{$ph}'  data-leadid='{$value->id}'
                   data-toggle='modal' 
                   data-target='#myModal' 
                   class='action-btn whatsapp' 
                   title='Send WhatsApp'>
                   <i class='fa fa-whatsapp'></i>
                </a>";
      } else {
        $output .= "<a href='https://wa.me/{$ph}' 
                   target='_blank' 
                   class='action-btn whatsapp' 
                   title='Send WhatsApp'>
                   <i class='fa fa-whatsapp'></i>
                </a>";
      }

      // End container
      $output .= "</div>";

      $output .= "<br/>";

      // $output .= "<br/>";
      $output .= "<div style='display:flex; align-items: flex-start;'>";
      $output .= getLeadStatusBadgeByName($value->lead_status);

      $output .= "<br/>";

      $output .= "<br/>";

      $output .= getLeadStatusBadgeByName($value->lead_type);
      $output .= "</div>";
      $output .= "</td>";

      $own = $boj->getQuery("SELECT * FROM owner WHERE lead_id = '$value->id' ");

      $output .= "<td><a href='tel:{$value->lead_contact}'>{$value->lead_contact}</a>

            <br/>

            <br/>

            <span class='label label-success'>{$value->lead_call_status}</span>

            </td>

            
    <td> {$value->property_type} <br />{$value->category} <br /> {$value->furnished_status} <br/><b>{$boj->contract('leads', $value->contract)}</b></td>

            <td> {$boj->price($value->client_budget_min)} -  {$boj->price($value->client_budget_max)}";



      if ($value->deposit) {
        $deposit = $value->deposit;

        $output .= "<br />Deposit:{$deposit} </td>";

      }



      // $output .= "<td>{$value->property_size_min}- {$value->property_size_max}</td>";

// Get latest remark from remarks table
$qry1 = $boj->getQuery("SELECT * FROM remarks WHERE lead_id = '$value->id' ORDER BY id DESC LIMIT 1");

if (!empty($qry1)) {
    foreach ($qry1 as $remarks) {
        $remarkFull = htmlspecialchars($remarks->remarks, ENT_QUOTES, 'UTF-8'); // for tooltip safety
        $remarkText = $remarkFull;

        // Trim long remarks but keep full text in tooltip
        if (strlen($remarkText) > 50) {
            $remarkText = substr($remarkText, 0, 50) . '...';
        }

        $output .= "
            <td>
                <span title='{$remarkFull}' data-toggle='tooltip' data-html='true'>
                    {$remarkText}
                </span>
                <hr>
                <i>{$remarks->remarks_by} {$remarks->remarks_date}</i>
            </td>
        ";
    }

} else {

    if (!empty($value->remarks)) {
        $remarkText = $value->remarks;

        if (strlen($remarkText) > 50) {
            $remarkText = substr($remarkText, 0, 50) . '...';
        }

        $output .= "
            <td>
                <span title='{$value->remarks}' data-toggle='tooltip' data-html='true'>
                    {$remarkText}
                </span>
            </td>
        ";
    } else {
        $output .= "<td><i>No remark found</i></td>";
    }
}




      $no_val = "";

      if ($value->reference != NULL) {



        $qry1 = $boj->getQuery("SELECT * FROM source where id = $value->reference");

        if ($qry1) {

          foreach ($qry1 as $ref_name) {

            $output .= "<td>{$ref_name->source_name} /";

          }



          if ($value->reference == 1) {



            if ($value->agent_id != NULL) {

              $qry2 = $boj->getQuery("SELECT * FROM agent where id = $value->agent_id");



              if ($qry2) {



                foreach ($qry2 as $name) {

                  $output .= "{$name->agent_name} </td>";

                }



              }



            }



          } elseif ($value->reference == 2) {

            $qry2 = $boj->getQuery("SELECT * FROM user where id = '$value->agent_id'");



            if ($qry2) {



              foreach ($qry2 as $name) {



                $output .= "{$name->name} </td>";

              }



            }





          }

        } else {

          $output .= "<td> Unknown / Not Updated </td>";

        }



      } else {

        $output .= "<td> Unknown / Not Updated </td>";

      }



      $output .= "<td>";

      // property	=	Leads

      // property	=	Leads

      $pro_type = $value->property_type;

      // category(id) = 





      $LeadCat = $value->category;



      $l = explode(",", $value->category);



      @$p = "type = '" . implode("' or type ='", array_map('trim', $l)) . "'";

      // echo $p;

      // 	echo ("select id, type from property_type where $p");







      // 	 $PType = $boj->getQuery("select id, type from property_type where type='$LeadCat'");

      //          $PType = $boj->getQuery("select id from property_type where $p");

      //         //  print_r($PType);

      //          //$category = implode(', ', array_map(function($item) {

      //     return $item->id;

      // }, $PType));



      //  @$a =   "category = '" . implode("' or category ='",array_map('trim', $PType)) . "'";

      // echo $a;

      //  print_r($a);

      //  @$category = $PType[0]->id;

      $furnished_status = $value->furnished_status;

      $locations = $value->required_property_location;



      $available = $value->contract;

      // echo $available;

      $min_cost = $value->client_budget_min;

      $max_cost = $value->client_budget_max;

      // ($value->property_size_min ." - " . $value->property_size_max)





      //1----------------- Cost ---------------------------	 WORKING

      $cost_search = '';

      if (@$min_cost && !$max_cost) {



        @$cost_search = "property_price >= '$min_cost'";

      }



      if (@$max_cost && !$min_cost) {

        //echo "bingo max cost";



        @$cost_search = "property_price <= '" . $max_cost . "'";

      }

      if (@$min_cost && $max_cost) {

        @$cost_search = "property_price BETWEEN '" . $min_cost . "' AND '" . $max_cost . "'";

        // "buyPrice BETWEEN 90 AND 100";

      }



      //2----------------- Deposit ---------------------------	WORKING

      $deposit_search = '';

      if (@$min_deposit && !$max_deposit) {

        @$deposit_search = "deposit >= '" . $min_deposit . "'";



      }



      if (@$max_deposit && !$min_deposit) {

        @$deposit_search = "deposit <= '" . $max_deposit . "'";



      }



      if (@$min_deposit && $max_deposit) {

        @$deposit_search = "deposit BETWEEN '" . $min_deposit . "' AND '" . $max_deposit . "'";

      }



      //3----------------- Availability ---------------------------	WORKING

      $available_search = '';

      if ($available) {

        $available_search = "available_for='$available'";

        // echo $available_search;

      }



      //4----------------- Property type ---------------------------	WORKING

      $pro_search = '';

      if (@$pro_type) {

        @$pro_search = "property_type = '" . $pro_type . "'";

      }



      //5----------------- Category --------------------------- WORKING

      //  $category_search = '';

      // //  echo $category;

      //  if (@$category){

      // //  @$category_search =   "category = '" . $category . "'";

      //  @$category_search =   "category = '" . implode("' or category ='", $category) . "'";

      //  }









      //  test 

      if (@$category) {

        $loop = explode(", ", $category);



        $cat = "";



        foreach ($loop as $loo) {

          $cat .= " category = '" . $loo . "' OR";

        }

        $category_search = "";

        $category_search .= " (";

        $category_search .= trim($cat, " OR");

        $category_search .= ") ";

        //@$location =   "( interested_location = " . explode(". ", $location) . " ";

        //@$location =   "property_location = '" . $location . "'";

      } else {

        $category_search = '';

      }



      // end



      //  echo $category_search;



      //6----------------- Furnished Status --------------------------- WORKING

      $furnished_status_search = '';

      if (@$furnished_status) {

        @$furnished_status_search = "furnished_status = '" . $furnished_status . "'";

      }



      //7----------------- Status --------------------------- WORKING

      $status_search = '';

      if (@$status) {

        @$status_search = "status = " . implode(" or status = ", $status) . " ";

      }



      //8----------------- Location --------------------------- WORKING



      if (@$locations) {

        $loop = explode(", ", $locations);



        $loca = "";



        foreach ($loop as $loo) {

          $loca .= " location = '" . $loo . "' OR";

        }

        $location = "";

        $location .= " (";

        $location .= trim($loca, " OR");

        $location .= ") ";

        //@$location =   "( interested_location = " . explode(". ", $location) . " ";

        //@$location =   "property_location = '" . $location . "'";

      } else {

        $location = '';

      }



      //9----------------- Location --------------------------- WORKING



      if (@$city) {

        @$city = "city = '" . $city . "'";

      } else {

        $city = '';

      }



      //10----------------- Location --------------------------- WORKING



      if (@$project) {

        @$project = "project_type = '" . $project . "'";

      } else {

        $project = '';

      }





      //----------------- Search Condition --------------------------- WORKING

      $search = '';

      if (@$cost_search) {

        @$search = $cost_search;





      }



      if (@$available_search) {

        if (@$cost_search || @$deposit_search) {

          @$search .= " AND ";

        }

        @$search .= $available_search;

        //  echo $search;

      }



      if (@$pro_search) {

        if (@$cost_search || @$deposit_search || @$available_search) {

          @$search .= " AND ";

        }

        @$search .= $pro_search;

      }

      if (@$category_search) {

        if (@$cost_search || @$deposit_search || @$available_search || @$pro_search) {

          @$search .= " AND ";

        }

        @$search .= $category_search;

      }

      if (@$furnished_status_search) {

        if (@$cost_search || @$deposit_search || @$available_search || @$pro_search || @$category_search) {

          @$search .= " AND ";

        }

        @$search .= $furnished_status_search;

      }

      if (@$status_search) {

        if (@$cost_search || @$deposit_search || @$available_search || @$pro_search || @$category_search || @$furnished_status_search) {

          @$search .= " AND ";

        }

        @$search .= $status_search;

      }



      if (@$location) {

        if (@$cost_search || @$deposit_search || @$available_search || @$pro_search || @$category_search || @$furnished_status_search || @$status_search) {

          @$search .= " AND ";

        }

        @$search .= $location;

      }



      if (@$city) {

        if (@$cost_search || @$deposit_search || @$available_search || @$pro_search || @$category_search || @$furnished_status_search || @$status_search || @$location) {

          @$search .= " AND ";

        }

        @$search .= $city;

      }



      if (@$project) {

        if (@$cost_search || @$deposit_search || @$available_search || @$pro_search || @$category_search || @$furnished_status_search || @$status_search || @$location || @$city) {

          @$search .= " AND ";

        }

        @$search .= $project;

      }



      if (@$search) {

        $where = $search . " AND status !='2'";



        //echo "<br />";

      } else {

        $where = "status != '2'";

      }

      //   echo ("SELECT * FROM property_listing where $where");  

      $matched = $boj->count("SELECT * FROM property_listing where $where");



      $output .= "<form action='property/matched-properties/' method='POST' target='_blank'>

        

         <input type='hidden' name='LeadID' value='{$value->id}'/>

         <input type='submit' class='btn btn-default' value='{$matched}' >";

      ?>

      <?php



      $output .= '<input type="hidden" name="search" value="' . $search . '" />';

      ?>

      <?php

      $output .= "</form>





        </td>

<td>";

      if ($value->lead_uploaded_by) {
        $upload = $value->lead_uploaded_by;
      } else {
        $upload = 'No A/c found!';
      }

      if ($value->lead_date) {
        $date = $value->lead_date;
      } else {
        $date = 'No date found!';
      }

      $output .= "<span class='badge bg-primary' style='margn-bottom:20px'>{$upload} </span><br >{$date}</td>

        

        <td width='170px' style='color:black;'> 

            <a class='bt' target='_blank'  href='leads/lead-edit/?edit=" . $value->id . "&nav=leads'>Update </a>";



      if ($value->contract == 'Rent' or $value->contract == 'Buy') {

        if ($value->convert_to_client != '1') {

          $output .= "<form action='' method='post'>



<input type='hidden' name='lead_id' value='{$value->id}'/>

<input type='hidden' name='uploader' value='{$getuserdata->username}'/>

<input type='hidden' name='user_id' value='{$getuserdata->id}'/>

<button name='convert-client' style='margin-bottom:10px; margin-top:10px;' class='col-md-12 btn btn-primary'>Convert to Client</button>

        </form>";

        }
      }



      if ($value->contract == 'Sell Owner' or $value->contract == 'Tenant Owner') {

        if ($value->convert_to_client != '1') {

          $output .= "<form action='' method='post'>



<input type='hidden' name='lead_id' value= {$value->id}/>

<input type='hidden' name='uploader' value={$getuserdata->username}/>



<button name='convert-owner' style='margin-bottom:10px; margin-top:10px;' class='col-md-12 btn btn-primary'>Convert to Owner</button>

        </form>";

        }
      }

      if ($assign == 'true') {



        $output .= "<br />

            <form action='' method='post'>

                <select name='assign' onchange='this.form.submit()'>

                    <option value=''>Assigned to</option>";

        if ($getuserdata->usertype == 'root' || (string) $getuserdata->roleid == '1') {
          // Root sees all users except other roots
          $agents = $boj->getQuery("
        SELECT u.id, u.name, r.name AS role_name
        FROM user u
        LEFT JOIN roles r ON u.roleid = r.id
        WHERE u.status = 'active' AND ( u.roleid !=1 and  u.usertype != 'root' )
        ORDER BY r.hierarchy_level ASC ");
        } else {
          // Supervisor sees only direct subordinates
          $agents = $boj->getQuery("
        SELECT u.id, u.name, r.name AS role_name
        FROM user u
        LEFT JOIN roles r ON u.roleid = r.id
        WHERE  ( u.roleid !=1 and  u.usertype != 'root' ) AND u.status = 'active' AND u.supervisor_id = '{$getuserdata->id}'
        ORDER BY r.hierarchy_level ASC
    ");
        }


        if ($agents) {

          foreach ($agents as $agent) {

            $output .= "<option value='{$agent->id}'";

            if ($agent->id == $value->assign_to) {

              $s = "selected";

              $output .= "{$s}";

            }

            $output .= ">{$agent->id}| {$agent->name} <sub>({$agent->role_name})</sub></option>";





          }

        }



        $user = $getuserdata->username;

        $output .= "</select>

               <input type='hidden' name='user' value='{$user}' >

                <input type='hidden' name='lead_id' value={$value->id}>

                <input type='hidden' name='user_id' value='{$getuserdata->id}'/>

            </form>

            

               

        <br />";

      }

      $output .= "<form action='' method='post'>

        <select name='mark-color' onchange='this.form.submit()'>

            <option value=''>Mark Color</option>";





      $colors = $boj->getQuery("SELECT * FROM mark_color");



      if ($colors) {

        foreach ($colors as $color) {



          $output .= "<option value='{$color->color}'>{$color->label}</option>";





        }

      }





      $output .= "</select>

        <input type='hidden' name='lead_id' value='{$value->id}'>

        <input type='hidden' name='user_id' value='{$getuserdata->id}'/>

    </form>

                <input type='hidden' name='lead_id' value='{$value->id}'/>

            </form>";



      if ($value->mark_color) {

        $output .= "<form action='' method='post'>



<input type='hidden' name='lead_id' value='{$value->id}'/>

 <input type='hidden' name='user_id' value='{$getuserdata->id}'/>

<button name='color-reset' style='margin-bottom:10px; margin-top:10px;' class='col-md-12 btn btn-default'>Reset Color</button>

</form>";

      }





      $output .= "</td>		

       </tr>";

      $pp++;

    }
  }





  $output .= "</table>";



  //$total_record=strtoupper(@$query[0]->count);



  $total_pages = ceil($total_record / $limit_per_page);

  //echo $total_pages;



  $res_page = $offset + $limit_per_page;



  $output .= "<div id='pagination'style='margin-top:20px;'>

    <div class='col-sm-3' style='margin-top:20px;'>  

					Showing " . $offset . " to {$res_page} of " . $total_record . " Records

					</div> <ul class='pagination pagination-md pull-right' style='display:inline-block; margin:0;'>";



  if ($page >= 2) {

    $j = $page - 1;

    $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'><< Previous</span></a></li>";

  }

  for ($j = 1; $j <= $total_pages; $j++) {



    if ($j == $page) {

      $class_name = "active";



    } else {

      $class_name = "";

    }

    $inc = $page + 1;

    $dec = $page - 1;

    if ($j == $page) {

      $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";

    } else {

      if ($j >= $inc && $j <= $inc) {

        $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";

      }

    }

    if ($j >= $dec && $j <= $dec) {

      $output .= "<li><a class='{$class_name}' id='{$j}' href=''>{$j}</a></li>";

    }

  }
  if ($page < $total_pages) {

    $j = $page + 1;

    $output .= "<li><a  id='{$j}' href=''><span aria-hidden='true'>Next >></span></a></li>";

  }



  $output .= "</ul>";



  echo $output;

} else {

  echo "<h2>No Record Found.</h2>";

}

?>

<?php


function getLeadStatusBadgeByName($statusName)
{
  $badgeClass = 'label label-default'; // default

  switch (strtolower($statusName)) {
    case 'un-attempted':
      $badgeClass = 'badge bg-warning'; // bright yellow/orange for fresh leads
      break;
    case 'attempted':
      $badgeClass = 'badge bg-info';
      break;
    case 'visit done':
    case 'revisit done':
    case 'meeting done':
    case 'booking done':
    case 'office visit done':
    case 'registration complete':
      $badgeClass = 'badge bg-success';
      break;
    case 'visit planned':
    case 'interested':
      $badgeClass = 'badge bg-primary';
      break;
    // case 'revisit planned':
    // case 'final negotiation':
    //     badgeClass = 'label label-warning';
    //     break;
    case 'not-interested':
    case 'failed':
      $badgeClass = 'badge bg-secondary';
      break;
    case 'junk':
      $badgeClass = 'badge bg-danger';
      break;
    case 'hot':
      $badgeClass = 'label label-warning';
      break;
    case 'cold':
      $badgeClass = 'badge bg-info';
      break;
    case 'warm':
      $badgeClass = 'badge bg-primary';
      break;
    default:
      $badgeClass = 'badge bg-default';
      break;
  }

  return '<span class="' . $badgeClass . ' " style="font-size:13px">' . $statusName . '</span>';
}


?>

<script>



  $(document).ready(function () {

    // View Project button click
		$(document).on('click', '.view-project', function() {
			var projectId = $(this).data('project-id');
			$('#viewProjectModalLabel').text('View Project');
			$('#viewProjectFrame').attr('src', 'project/project-view-temp/?view=' + projectId + '&nav=project&iframe=true');
			$('#viewProjectModal').modal('show');
		});

		// View Property button click
		$(document).on('click', '.view-property', function() {
			var propertyId = $(this).data('property-id');
			$('#viewProjectModalLabel').text('View Property');
			$('#viewProjectFrame').attr('src', 'property/property-info-temp/?view=' + propertyId + '&nav=property&iframe=true');
			$('#viewProjectModal').modal('show');
		});

    const selectedIDs = []; // Array to store selected IDs

    const selectAllCheckbox = document.getElementById('checkAll');

    console.log(selectAllCheckbox);



    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');



    // Add a click event listener to the "Select All" checkbox

    selectAllCheckbox.addEventListener('click', function () {

      checkboxes.forEach((checkbox) => {

        checkbox.checked = selectAllCheckbox.checked;

        if (checkbox.checked) {

          // Get the ID of the checked checkbox







          if (checkbox.value !== 'on') {

            selectedIDs.push(checkbox.value);

            console.log(checkbox.value);

          }



        }

      });

    });



    const checkboxe = document.querySelectorAll(".select-checkbox");

    // const selectedIDs = []; // Array to store selected IDs

    checkboxe.forEach(function (checkbox) {

      checkbox.addEventListener("change", function () {

        console.log("chal");

        const row = this.closest("tr");

        console.log("row" + row);

        const id = row.querySelector("td:nth-child(3)").textContent; // Adjust the index if needed

        console.log("id" + id);

        if (this.checked) {

          // Checkbox is checked, do something with the ID                    

          selectedIDs.push(id);

        } else {

          // Checkbox is unchecked, handle accordingly

          const index = selectedIDs.indexOf(id);

          if (index !== -1) {

            selectedIDs.splice(index, 1);

          }

        }



      });

    });





    // Function to send selectedIDs to a PHP script using AJAX

    function sendSelectedIDs() {


      const xhr = new XMLHttpRequest();

      xhr.open("POST", "process.php", true);

      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function () {

        if (xhr.readyState === 4 && xhr.status === 200) {

          // Handle the response from the server if needed

          console.log(xhr.responseText);

        }

      };

      if (selectedIDs.length == 0) {
        toastr.warning('please select lead first');
        return;
      }

      xhr.send("selectedIDs=" + JSON.stringify(selectedIDs));


      $('#hiddenInputId').val(selectedIDs);



    }



    // Example: Trigger sending selected IDs when a button is clicked

    document.getElementById("submitBtn").addEventListener("click", function () {



      sendSelectedIDs();

    });

  });



</script>

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog">





    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">SEND WHATSAPP</h4>

      </div>

      <div class="modal-body">









        <form action="" method="POST">

          <input type="number" id="phone" name="mob" value="<?= $contact ?>" class="form-control">

          <br />

          <textarea name="msg" id="msg" style="height: 100px;" required class="form-control"></textarea>

      </div>
      <div class="form">
        <span id="wa-response"></span>
      </div>



      <div class="modal-footer">

        <input type="submit" name="wa-send" class="btn btn-primary" id="wa-send" value="SEND">



        <input type="hidden" name="search" value="<?= $search; ?>">

        <input type="hidden" name="LeadID" id="lid">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>

      </form>

    </div>



  </div>

</div>



<!-- View Project/Property Modal -->
			<div class="modal fade" id="viewProjectModal" tabindex="-1" role="dialog"
				aria-labelledby="viewProjectModalLabel">
				<div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1200px;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id="viewProjectModalLabel">View Details</h4>
						</div>
						<div class="modal-body" style="padding: 0;">
							<iframe id="viewProjectFrame" src="" style="width: 100%; height: 80vh; border: none;"></iframe>
						</div>
					</div>
				</div>
			</div>



<script>

  $(document).ready(function () {


$('[data-toggle="tooltip"]').tooltip()

    $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {

      var id = '';



      if (typeof $(this).data('id') !== 'undefined') {

        data_id = $(this).data('id');

        $('#phone').val(data_id);
        $('#lid').val($(this).data('leadid'));

      }








      $('#wa-send').on('click', function (e) {
        e.preventDefault();

        var formData = {
          mob: $('#phone').val(),
          msg: $('#msg').val(),
          LeadID: $('#lid').val()
        };

        $.ajax({
          url: 'includes/ajax/lead/sendWa.php',
          type: 'POST',
          data: formData,
          dataType: 'json',
          beforeSend: function () {
            $('#wa-send').prop('disabled', true).text('Sending...');
            $('#wa-response').html('');
          },
          success: function (response) {
            if (response.status) {

              $('#wa-response').html('<div class=\"alert alert-success\">' + response.message + '</div>');
              $('#wa-send').prop('disabled', false).text('SEND');
              $('#msg').val('');
            } else {
              href = "https://api.whatsapp.com/send/?phone=" + $('#phone').val() + "&text=" + encodeURIComponent($('#msg').val()) + "&type=phone_number&app_absent=0";
              $('#wa-response').html('<div class=\"alert alert-warning\">' + response.message + 'try to send via web.whatsapp</div>');
              $('#wa-send').prop('disabled', false).text('SEND');

              window.open(
                href,
                "popupWindow", // window name (can be anything or "_blank")
                "width=600,height=600,resizable=yes,scrollbars=yes"
              );
            }
          },
          error: function (xhr, status, error) {
            $('#wa-response').html('<div class=\"alert alert-danger\">Error: ' + error.message + '</div>');
            $('#wa-send').prop('disabled', false).text('SEND');
          }
        });
      });
    })
  })







</script>

<script type="text/javascript">

  var con = <?php echo json_encode($ph); ?>



</script>

<script>

  var anchor = document.getElementById('myAnchor');

  anchor.addEventListener('click', function (event) {

    if (!confirm('Are you sure you want to Call ?')) {

      event.preventDefault();

    }

  });
  function toggleDeleteButton() {
    if ($(".select-checkbox:checked").length > 0) {
      $("#deleteBtn").show();
    } else {
      $("#deleteBtn").hide();
    }
  }

  // Select all checkboxes
  $("#checkAll").click(function () {

    $(".select-checkbox").prop("checked", this.checked);
    toggleDeleteButton();
  });

  // Individual checkbox click
  $(".select-checkbox").click(function () {
    if ($(".select-checkbox:checked").length === $(".select-checkbox").length) {
      $("#checkAll").prop("checked", true);
    } else {
      $("#checkAll").prop("checked", false);
    }
    toggleDeleteButton();
  });

  // Delete selected leads using AJAX
  $("#deleteBtn").click(function () {
    var selectedIDs = [];
    $(".select-checkbox:checked").each(function () {
      selectedIDs.push($(this).val());
    });
    // console.log(selectedIDs);

    if (selectedIDs.length === 0) {
      alert("Please select at least one lead to delete.");
      return;
    }

    if (!confirm("Are you sure you want to delete selected leads ::(" + selectedIDs + ')')) {
      return;
    }

    $.ajax({
      url: "includes/ajax/lead/delete_lead.php",  // Replace with your backend script
      type: "POST",
      data: { lead_ids: selectedIDs, auth_token: 123 },
      dataType: "json",
      success: function (response) {

        if (response.status === "success") {
          // Remove deleted rows from table
          $(".select-checkbox:checked").each(function () {
            $(this).closest("tr").remove();
          });

          $("#checkAll").prop("checked", false);
          toggleDeleteButton();
          alert("Deleted Successfully");;
        } else {
          alert("Error: " + response.message);
        }
      },
      error: function () {
        alert("Server error. Please try again.");
      }
    });
  });

</script>


<style>
  .tooltip-inner {
  max-width: 400px !important;
  max-height: 250px !important;
  overflow-y: auto !important;
  white-space: normal !important;
  word-wrap: break-word;
  text-align: left;
}

</style>