<?php



require_once('../../config.php');

if (!function_exists('hex2rgba')) {
  function hex2rgba($color, $alpha = 0.15) {
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

$search = '';

$limit_per_page = 10;



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



@$start_Date = $_SESSION['from_date'];

@$end_date = $_SESSION['to_date'];

@$ltype = $_SESSION['ltype'];



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

  @$ltype_search = "lead_type = '' OR lead_type IS NULL AND lead_type NOT IN ('hot', 'warm', 'cold')";



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

//   echo "<br/>";



//   $page = "";

if (isset($_POST["page_no"])) {

  $_SESSION['page_no'] = $_POST["page_no"];

  $page = $_SESSION['page_no'];

  // echo $page;



} else {

  $page = 1;

}

// if(isset($_POST['fillter_user'])){

//     $fillter_user=$_POST['fillter_user'];

//     echo $fillter_user;

// }

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

$offset = ($page - 1) * $limit_per_page;

// echo "Offset";

// echo $offset;

if (isset($_POST["search"])) {

  $_SESSION['search'] = $_POST["search"];

  //echo  $_SESSION['search'];



}

@$search_value = $_SESSION["search"];



if ($search_value) {

  $qry = $boj->getQuery("SELECT * FROM leads where  lead_status != 'not-interested'  $contracted $converted $search and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page} ");

  // echo ("SELECT * FROM leads where  lead_status != 'NULL' $contracted $converted $search and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' ) LIMIT {$offset},{$limit_per_page} ");

  $total_record = $boj->count("SELECT * FROM leads where  lead_status != 'not-interested'  $contracted $converted $search and (lead_name LIKE '%{$search_value}%' OR id LIKE '%{$search_value}%' OR lead_contact LIKE '%{$search_value}%' OR category LIKE '%{$search_value}%' )");

} else {



  $qry = $boj->getQuery("SELECT * FROM leads where  lead_status != 'not-interested'  $contracted $converted $search LIMIT {$offset},{$limit_per_page} ");



  // echo ("SELECT * FROM leads where  lead_status != 'NULL' $contracted $converted $search LIMIT {$offset},{$limit_per_page} ");

  $total_record = $boj->count("SELECT * FROM leads where lead_status != 'not-interested'  $contracted $converted $search");

}













$output = "";

if ($qry) {

  $output .= '<table class="table table-bordered table-striped mb-none">

    <th><input type="checkbox"  id="checkAll"></th>

    <th>#</th>

    <th>ID</th>

                        <th>Name</th>

                        <th>Contact</th>

                        <th>Requirement</th>

                        <th>Budget</th>

                        <th>Size</th>

                        <th>Remarks</th>

                        <th>Source</th>

                        <th>Matched</th>

                        <th>Date</th>

                        <th>Created</th>

                        <th style="width:110px">Action</th>';



  // echo  $offset;

  $pp = 1;

  if ($qry) {



    foreach ($qry as $value) {

      $ress = $pp + $offset;

      $output .= "<tr class='gradeC'";

      if ($value->mark_color) {

        $bgColor = hex2rgba($value->mark_color, 0.16);
        $colour = "style='color:black; background-color:" . $bgColor . "'";

        $output .= "{$colour}";

      }



      $output .= "<td></td>

           <td><input type='checkbox' class='select-checkbox' value='{$value->id}'></td>

           <td>{$ress}</td>

            <td>

 <a class='button' href='leads/lead-edit/?edit={$value->id}&nav=leads'>{$value->id}</a></td>

            <td> <a href='leads/lead-edit.php?edit=" . $value->id . "&nav=leads'>{$value->lead_name}</a>";



      $own = $boj->getQuery("SELECT * FROM owner WHERE lead_id = '$value->id' ");

      if ($own) {

        $own_status = $own[0]->owner_status;

      }

      if ($value->convert_to_client == '2') {

        $owner = "<br /> <ul><li> <a target='_blank' href='owner/owner-edit.php?nav=?nav=owners&edit=" . $own[0]->id . "'>Owner</a></li><li>";

        if ($own_status != 'NULL' or $own_status != '') {



          $output .= "{$own_status}";

        } else {

          $output .= "Not Updated";

        }



        $output .= "</li></ul>";

      } elseif ($value->convert_to_client == '1') {

        $output .= "<br /> <ul><li> Client</li></ul>";

      } elseif ($value->convert_to_client == '0') {

        $output .= "<br /> <ul><li> Lead/Enquiry</li></ul>";

      }

      $output .= "<br />";





      $ph = $value->lead_contact;

      // $call =$boj->call_to($ph, $value->id);

      // echo $boj->whatsapp_modal($ph).' | ';; 

      //echo $boj->mail_to($value->lead_mail); 



      $phoneLink = 'tel:' . $ph;

      ?>



      <?php

      $output .= "<a href='{$phoneLink}' id='myAnchor'  ><i class='fa fa-phone'></i></a>|<a target='_blank' href='mailto:{$value->lead_mail}'><i class='fa fa-envelope'></i></a> |";





      $data = $boj->getQuery("SELECT * FROM api where api_status = '1' ");

      if ($data) {

        $output .= "<a href='#myModal' data-id='{$ph}' data-toggle='modal' data-target='#myModal'><i class='fa fa-whatsapp'></i></a>";

      } else {

        $output .= "<a target='_blank' href='https://wa.me/{$ph}' ><i class='fa fa-whatsapp'></i></a>";



      }

      $output .= "<br/>";

      $output .= "<br/>";

      $output .= "{$value->lead_status}";

      $output .= "<br/>";

      $output .= "<br/>";

      $output .= "{$value->lead_type}";

      $output .= "</td>

           

            <td> ";

      $output .= "{$value->lead_contact}

 

 

 <br/>

            <br/>

            {$value->lead_call_status}

            </td>

            

    <td>{$value->property_type}<br />{$value->category}<br />{$value->furnished_status}<br />
    {$value->required_property_location} <br />
    <b> {$boj->contract('leads', $value->contract)}</b></td>

            <td>{$boj->price($value->client_budget_min)} - {$boj->price($value->client_budget_max)}";

      if ($value->deposit) {

        $output .= "<br />Deposit: {$boj->price($value->deposit)}";

      }

      $output .= "</td>

            <td>{$value->property_size_min}-{$value->property_size_max}</td>";

      $qry1 = $boj->getQuery("SELECT * FROM remarks where lead_id = '$value->id' order by id desc limit 1");

      if ($qry1) {

        foreach ($qry1 as $remarks) {

          $output .= "<td>{$remarks->remarks} <hr /><i>{$remarks->remarks_by} -{$remarks->remarks_date}</i></td>";

        }

      } else {

        $output .= "<td>--</td>";

      }



      $no_val = "";

      if ($value->reference != NULL) {



        $qry1 = $boj->getQuery("SELECT * FROM source where id = '$value->reference'");

        if ($qry1) {

          foreach ($qry1 as $ref_name) {

            $output .= "<td>{$ref_name->source_name} /";

          }



          if ($value->reference == 1) {



            if ($value->agent_id != NULL) {

              $qry2 = $boj->getQuery("SELECT * FROM agent where id = '$value->agent_id'");



              if ($qry2) {



                foreach ($qry2 as $name) {

                  $output .= "{$name->agent_name}</td>";

                }



              }



            }



          } elseif ($value->reference == 2) {

            if ($value->agent_id) {

              $qry2 = $boj->getQuery("SELECT * FROM user where id = '$value->agent_id'");



              if ($qry2) {



                foreach ($qry2 as $name) {

                  $output .= "{$name->name}</td>";

                }



              }

            } else {

              $output .= "Not Found!";

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

      $PType = $boj->getQuery("select id from property_type where $p");



      //  $category = implode(', ', array_map(function($item) {

      // return $item->id;

      // }, $PType));









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



      // category testing

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



      // end testing



      //5----------------- Category --------------------------- WORKING

      //  $category_search = '';

      //  if (@$category){

      //  @$category_search =   "category = '" . $category . "'";

      //  }



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

        @$project = "project_id = '" . $project . "'";

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





        </td>";

























      $output .= "<td>";

      if ($value->lead_date) {

        $output .= "{$value->lead_date}";

      } else {

        $output .= "No date found!";

      }

      $output .= "</td>";

      $output .= "<td> ";

      if ($value->lead_uploaded_by) {

        $output .= "{$value->lead_uploaded_by}";

      } else {

        $output .= "No A/c found!";

      }

      $output .= "</td>";



      $output .= "<td width='170px' style='color:black;'> 

        <a class='button'  target='_blank'  href='leads/lead-edit.php?edit=" . $value->id . "&nav=leads'>Update </a>";

      if ($value->contract == 'Rent' or $value->contract == 'Buy') {

        if ($value->convert_to_client != '1') {

          $output .= "<form action='' method='post'>



<input type='hidden' name='lead_id' value='{$value->id}'>

<input type='hidden' name='uploader' value='{$getuserdata->username}'>

<input type='hidden' name='user_id' value='{$getuserdata->id}'/>

<button name='convert-client' style='margin-bottom:10px; margin-top:10px;' class='col-md-12 btn btn-primary'>Convert to Client</button>

        </form>";

        }
      }

      if ($value->contract == 'Sell Owner' or $value->contract == 'Tenant Owner') {

        if ($value->convert_to_client != '1') {

          $output .= "<form action='' method='post'>



<input type='hidden' name='lead_id' value='{$value->id}'>

<input type='hidden' name='uploader' value='{$getuserdata->username}'>

<input type='hidden' name='user_id' value='{$getuserdata->id}'/>

<button name='convert-owner' style='margin-bottom:10px; margin-top:10px;' class='col-md-12 btn btn-primary'>Convert to Owner</button>

        </form>";

        }
      }



      if ($assign == 'true') {



        $output .= "<br />

            <form action='' method='post'>

                <select name='assign' onchange='this.form.submit()'>

                    <option value=''>Assigned to</option>";



        $agents = $boj->getQuery("SELECT * FROM user where status = 'active' order by usertype and usertype!='root' ASC");



        if ($agents) {

          foreach ($agents as $agent) {



            $output .= "<option value='{$agent->id}'";

            if ($agent->id == $value->assign_to) {
              $s = "selected";

              $output .= "{$s}";

            }

            $output .= " >

   {$agent->id} | {$agent->name} ({$agent->usertype}) </option>";



          }

        }

        $user = $getuserdata->username;

        $output .= "</select>

               <input type='hidden' name='user' value='{$user}' >

                <input type='hidden' name='lead_id' value='{$value->id}'>

                <input type='hidden' name='user_id' value='{$getuserdata->id}'/>

            </form>";

      }

      $output .= "<br />

            <form action='' method='post'>

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

            </form>";



      if ($value->mark_color) {

        $output .= "<form action='' method='post'>



<input type='hidden' name='lead_id' value='{$value->id}'>

<input type='hidden' name='user_id' value='{$getuserdata->id}'/>

<button name='color-reset' style='margin-bottom:10px; margin-top:10px;' class='col-md-12 btn btn-default'>Reset Color</button>

</form>";

      }



      $output .= "</td>		

     </tr>";





      $pp = $pp + 1;

    }







  }





  $output .= "</table>";





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

          <textarea name="msg" id="msg" style="height: 100px;" class="form-control"></textarea>

      </div>



      <div class="modal-footer">

        <input type="submit" name="wa-send" class="btn btn-primary" value="SEND">



        <input type="hidden" name="search" class="btn btn-primary" value="<?= $search; ?>">

        <input type="hidden" name="LeadID" class="btn btn-primary" value="<?= $lead; ?>">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>

      </form>

    </div>



  </div>

</div>

<script>

  $(document).ready(function () {



    $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {

      var id = '';



      if (typeof $(this).data('id') !== 'undefined') {

        data_id = $(this).data('id');

        $('#phone').val(data_id);

      }







    })

  });





  $('#myModal').on('show.bs.modal', function (e) {

    console.log($(e.relatedTarget).attr('data-id'))

    //var id = $(this).dataset.id

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

</script>

<script>

  $(document).ready(function () {

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

            // console.log(checkbox.value);

          }



        }

      });

    });



    const checkboxe = document.querySelectorAll(".select-checkbox");

    // const selectedIDs = []; // Array to store selected IDs

    checkboxe.forEach(function (checkbox) {

      checkbox.addEventListener("change", function () {



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

    console.log(selectedIDs);
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



      xhr.send("selectedIDs=" + JSON.stringify(selectedIDs));

      console.log("New" + selectedIDs);

      $('#hiddenInputId').val(selectedIDs);



    }



    // Example: Trigger sending selected IDs when a button is clicked

    document.getElementById("submitBtn").addEventListener("click", function () {

      sendSelectedIDs();

    });





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