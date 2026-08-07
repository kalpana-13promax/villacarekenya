<?php

class product extends db
{

	function add_property()
	{
		if (!empty($_FILES["property_image"]["tmp_name"])) {
			$pro_img = $this->uploadFiles($_FILES['property_image']);
		} else {

			$pro_img = '';
		}


		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"][0])) {

			$gallery_images = $this->uploadFiles($_FILES['gallery_image']);
		} else {

			$gallery_images = '';
		}




		$allIds = explode(',', $_POST['allIds']);
		$attributinfo = array();
		foreach ($allIds as $value) {
			$field_type = $_POST['field_type_' . $value];
			$info = array('field_id' => $value, 'field_type_value' => $this->sanitize($field_type));
			array_push($attributinfo, $info);
		}
		$attributinfo_new = json_encode($attributinfo);
		//  print_r($attributinfo);
		//  echo"<br>";
		// print_r($galleryimage);

		//$allIds 	= $_POST['field_type'];
		$a_id = NULL;
		$s_id = NULL;
		if ($_POST['agent_id'] != NULL) {
			$a_id = $_POST['agent_id'];
			$ref = $a_id;
		}

		if ($_POST['staff_id'] != NULL) {
			$s_id = $_POST['staff_id'];
			$ref = $s_id;
		}

		if ($a_id == NULL & $s_id == NULL) {
			$ref = 'NULL';
		}



		if (isset($_POST['feature_property'])) {
			$feature_property = $_POST['feature_property'];
		} else {
			$feature_property = '2';
		}


		if (isset($_POST['staff'])) {
			$staff = implode(",", $_POST['staff']);
		} else {
			$staff = '';
		}
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}




		if (isset($_POST['weekend'])) {
			$weekendstart_date = $_POST['weekendstart_date'];
			$weekendend_date = $_POST['weekendend_date'];
		} else {
			$weekend = '';
			$weekendstart_date = '';
			$weekendend_date = '';
		}

		// print_r($_POST);

		// for nearest location 

		$nearest_locations = isset($_POST['nearest_loc']) ? $_POST['nearest_loc'] : [];
		$distances = isset($_POST['distance']) ? $_POST['distance'] : [];
		$times = isset($_POST['time']) ? $_POST['time'] : [];

		$dataArray = [];

		// Ensure all arrays have equal length
		if (!empty($nearest_locations) && !empty($distances) && !empty($times)) {
			foreach ($nearest_locations as $key => $location) {
				if (!empty($location) && !empty($distances[$key]) && !empty($times[$key])) {
					$dataArray[] = [
						"location" => $location,
						"distance" => $distances[$key],
						"time" => $times[$key]
					];
				}
			}
			// Convert the array to JSON
		}
		$jsonData = !empty($dataArray) ? json_encode($dataArray) : null;


// offer section
		$offerK = [];

		if (!empty($_POST['offer_keyword']) && is_array($_POST['offer_keyword'])) {
			foreach ($_POST['offer_keyword'] as $value) {
				$trimmedValue = trim($value); // Remove unwanted spaces
				if (!empty($trimmedValue)) {
					$offerK[] = $trimmedValue;
				}
			}
		}

		$offer_keyword = json_encode($offerK, JSON_UNESCAPED_UNICODE); // Prevents Unicode escaping








		$property_id = uniqid();
		$array = array(
			'property_title' => $this->sanitize($_POST['property_title']),
			'unit_no' => $this->sanitize($_POST['unit_no']),
			'address' => $this->sanitize($_POST['address']),
			'project_id' => $this->sanitize($_POST['project']),
			'available_for' => $this->sanitize($_POST['available_for']),
			'property_type' => $this->sanitize($_POST['property_type']),
			'category' => $_POST['category'],
			'quota_id' => !empty($_POST['quota_id']) ?? 1,
			'priority' => $_POST['priority'],
			'property_id' => $property_id,
			'furnished_status' => $_POST['furnished_status'],
			'property_attribute' => $attributinfo_new,
			'reference_source' => $this->sanitize($_POST['refer_id']),
			'referance_agent' => $this->sanitize($ref),
			'owner_id' => $this->sanitize($_POST['owner_id']),
			'weekendstart_date' => $weekendstart_date,
			'weekendend_date' => $weekendend_date,
			'weekend' => $weekend,
			'property_price' => $this->int($_POST['property_price']),
			'deposit' => $this->int($_POST['deposit']),
			'property_amenities' => $this->sanitize($amenities),
			'assign_property' => $this->sanitize($staff),
			'tower' => $_POST['tower'],
			'property_image' => $this->sanitize($pro_img),
			'gallery' => $gallery_images,
			'property_description' => $_POST['description'],
			'city' => $_POST['city'] ?? '',
			'location' => $_POST['location'] ?? '',
			'sub_location' => $_POST['sub_location'] ?? '',
			'zip_code' => $this->int($_POST['zip_code']),
			'map' => $_POST['map'],
			'youtube' => $_POST['youtube'],
			'feature_property' => $feature_property,
			'is_perunit' => $_POST['is_perunit'],
			'measurement' => $_POST['measurement'],
			'size' => $_POST['size'],
			'status' => $this->sanitize($_POST['status']),
			'uploader' => $this->sanitize($_POST['uploader']),
			'user_id' => $this->sanitize($_POST['user_id']),
			'mark_color' => $_POST['color'],
			'remark' => $this->sanitize($_POST['remark']),
			'nearest_location' => $jsonData,
			'offer_keyword'=>$offer_keyword
		);


// 		echo "<pre>";
// 		print_r($array);
// 		die;

		$csrf_token = $_POST['csrf_token'];

		$data = $this->csrfProInsert('property_listing', $array, $csrf_token);

		$last_id = $this->mysqli->insert_id;
		
		if($data){

		//custom field insertion start/////////////////
		include 'custom-fields.php';

		//custom field 
		if (isset($_POST['custom_field'])) {

			$input = $_POST['custom_field'] ?? [];

			$fieldarr = array_filter($input, function ($val) {
				return $val !== null && $val !== '';
			});
			// print_r($_POST['custom_field']);
			// die;

			$cu = new CustomFields();
			foreach ($fieldarr as $key => $value) {

				$d = $cu->saveFieldValue('property_listings', $last_id, $key, $value);
				// echo '2';
				if (!$d) {
					error_log('err:' . $this->mysqli->error, 3, './logs/custom-field.log');
				}

			}
		}
	}

		$keyword = !empty($_POST['meta_keywords']) ? json_encode(array_map('trim', explode(',', $_POST['meta_keywords']))) : '';
		$seoData = [
			'related_id' => $last_id,
			'type' => 'property',
			'slug' => $_POST['slug'],
			'canonical_url' => $_POST['canonical_url'],
			'meta_title' => $_POST['meta_title'],
			'meta_description' => $_POST['meta_description'],
			'meta_keywords' => $keyword,
			'uploader' => $_POST['uploader'],
			'user_id' => $_POST['user_id']
		];
		// print_r($seoData);
		// die;
		$this->csrfProInsert('seo_data', $seoData, $csrf_token);



		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property " . $_POST['property_title'] . " (" . $last_id . ") has  Created by $_POST[uploader]",
			'type' => __FUNCTION__,
			'user_details' => json_encode($this->user_detail())

		);
		$this->insert_userAct('user_actvity', $act, $csrf_token);


		// session_start();


		// if( $data ){
		// 	$_SESSION['suc'] = 'Property created successfully';

		// }
		// else{
		// 	$_SESSION['fal'] = ' Sorry Something went wrong' . $this->mysqli->error;

		// }
		$this->msg_set($data, 'property_listing');
		// header("location: ?nav=properties");

		// die;
	}


	function edit_property_info()
	{
		$id = $_GET['edit'];
		$lit = $_GET['edit'];
		// echo "<pre>";
		// print_r($_POST);
		// die;
		$pro = $this->getQuery("SELECT * FROM property_listing where id ='$id' ");
		$pro_value = $pro[0];
		if ($pro_value->property_id == '' or $pro_value->property_id == NULL) {
			echo "Not";
			$property_id = uniqid();
		} else {

			$property_id = $_POST['property_id'];
		}

		$imgqry = $this->mysqli->query("SELECT gallery FROM property_listing where id ='$id' ");
		$r_data = $imgqry->fetch_assoc();



		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["property_image"]["tmp_name"])) {
			$property_image = $this->uploadFiles($_FILES['property_image']);
		} else {

			$property_image = $r_data['property_image'];
		}

		// print_r($property_image);
		// die;
		$i = 0;
		if (!empty($r_data['gallery_image'])) {
			$galleryimage = json_decode($r_data['gallery_image']);
		} else {
			$galleryimage = array();
		}

		if (!empty($_FILES["gallery_image"]["tmp_name"][0])) {
			$gallery_image = $this->uploadFiles($_FILES['gallery_image']) ?? [];
			$gallery_images = array_merge($galleryimage, json_decode($gallery_image));
		}
		$gallery_images = json_encode($gallery_images ?? $gallery_image);

		// print_r($gallery_images);
		// die;
		$allIds = explode(',', $_POST['allIds']);
		$attributinfo = array();
		foreach ($allIds as $value) {
			$field_type = $_POST['field_type_' . $value];
			$info = array('field_id' => $value, 'field_type_value' => $this->sanitize($field_type));
			array_push($attributinfo, $info);
		}
		$attributinfo_new = json_encode($attributinfo);

		//$allIds 	= $_POST['field_type'];


		if (isset($_POST['feature_property'])) {
			$feature_property = $_POST['feature_property'];
		} else {
			$feature_property = '2';
		}


		if (isset($_POST['refer_id'])) {
			$refer_id = $_POST['refer_id'];
		} else {
			$refer_id = '0';
		}


		if ($refer_id == '1') {
			$agent_id = $_POST['agent_id'];
		} elseif ($refer_id == '2') {
			$agent_id = $_POST['staff_id'];
		}

		/*			
																																																																																																																																						 if($_POST['agent_id'] != NULL){
																																																																																																																																						 $a_id = $_POST['agent_id'];
																																																																																																																																						 $ref = $a_id;
																																																																																																																																						 }else{
																																																																																																																																							 $ref = 'NULL';
																																																																																																																																						 }
																																																																																																																																				 */
		if (isset($_POST['weekend'])) {
			$weekend = $_POST['weekend'];
			$weekendstart_date = $_POST['weekendstart_date'];
			$weekendend_date = $_POST['weekendend_date'];
		} else {
			$weekend = "2";
			$weekendstart_date = "";
			$weekendend_date = "";
		}


		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}

		if (isset($_POST['staff'])) {
			$staff = implode(",", $_POST['staff']);
		} else {
			$staff = '';
		}
		$description = $_POST['description'];
		$status = $_POST['status'];

		if (isset($_POST['project'])) {
			$project = $_POST['project'];
		} else {
			$project = "";
		}
		if (isset($_POST['project'])) {
			$project = $_POST['project'];
		} else {
			$project = "";
		}

		// print_r($_POST);
		// die;

		// for nearest location 

		$nearest_locations = isset($_POST['nearest_loc']) ? $_POST['nearest_loc'] : [];
		$distances = isset($_POST['distance']) ? $_POST['distance'] : [];
		$times = isset($_POST['time']) ? $_POST['time'] : [];

		$dataArray = [];

		// Ensure all arrays have equal length
		if (!empty($nearest_locations) && !empty($distances) && !empty($times)) {
			foreach ($nearest_locations as $key => $location) {
				if (!empty($location) && !empty($distances[$key]) && !empty($times[$key])) {
					$dataArray[] = [
						"location" => $location,
						"distance" => $distances[$key],
						"time" => $times[$key]
					];
				}
			}
			// Convert the array to JSON
		}
		$jsonData = !empty($dataArray) ? json_encode($dataArray) : null;


	$offerK = [];

		if (!empty($_POST['offer_keyword']) && is_array($_POST['offer_keyword'])) {
			foreach ($_POST['offer_keyword'] as $value) {
				$trimmedValue = trim($value); // Remove unwanted spaces
				if (!empty($trimmedValue)) {
					$offerK[] = $trimmedValue;
				}
			}
		}

		$offer_keyword = json_encode($offerK, JSON_UNESCAPED_UNICODE); // Prevents Unicode escaping







		if ($_POST['measurement']) {
			$mu = $_POST['measurement'];
		} else {
			$mu = '0';
		}



		//echo "<pre>"; print_r($attributinfo_new); die;
		$array = array(
			'property_title' => $this->sanitize($_POST['property_title']),
			'project_id' => $this->sanitize($project),
			'unit_no' => $this->sanitize($_POST['unit_no']),
			'quota_id' => $_POST['quota_id'],
			'priority' => $_POST['priority'],
			'address' => $this->sanitize($_POST['address']),
			'available_for' => $this->sanitize($_POST['available_for']),
			'property_type' => $this->sanitize($_POST['property_type']),
			'category' => $_POST['category'],
			'weekendstart_date' => $weekendstart_date,
			'weekendend_date' => $weekendend_date,
			'weekend' => $weekend,
			'tower' => $_POST['tower'],
			'furnished_status' => $_POST['furnished_status'],
			// 'property_attribute' => $attributinfo_new,
			'reference_source' => $this->sanitize($refer_id),
			'referance_agent' => $this->sanitize(@$agent_id),
			'owner_id' => $this->sanitize($_POST['owner_id']),
			'property_price' => $this->int($_POST['property_price']),
			'deposit' => $this->int($_POST['deposit']),
			'property_amenities' => $this->sanitize($amenities),
			'property_image' => $property_image,
			'gallery' => $gallery_images,
			'property_description' => $_POST['description'],

			'city' => $this->sanitize($_POST['city']),
			'location' => $_POST['location'],
			'sub_location' => $_POST['sub_location'],

			'zip_code' => $this->int($_POST['zip_code']),

			'map' => $_POST['map'],
			'youtube' => $_POST['youtube'],
			'feature_property' => $feature_property,
			'is_perunit' => $_POST['is_perunit'],
			'measurement' => $mu,
			'size' => $_POST['size'],
			'status' => $this->sanitize($_POST['status']),
			'assign_property' => $staff,
			'property_id' => $property_id,
			'mark_color' => $_POST['color'],
			'remark' => $this->sanitize($_POST['remark']),
			'nearest_location' => $jsonData,
			'offer_keyword' => $offer_keyword
		);
		// echo "<pre>";

		// print_r($_POST);
		// print_r($array);
		// die;
		$where = "id = " . $id;
		$csrf_token = $_POST['csrf_token'];
		$data = $this->csrfProUpdate('property_listing', $array, $where, $csrf_token);

	if ($data) {
			//custom field insertion start/////////////////
			include 'custom-fields.php';

			//custom field 
			if (isset($_POST['custom_field'])) {

				$input = $_POST['custom_field'] ?? [];

				$fieldarr = array_filter($input, function ($val) {
					return $val !== null && $val !== '';
				});
				// print_r($_POST['custom_field']);
				// die;

				$cu = new CustomFields();
				foreach ($fieldarr as $key => $value) {

					$d = $cu->saveFieldValue('property_listings', $id, $key, $value);
					// echo '2';
					if (!$d) {
						error_log('err:' . $this->mysqli->error, 3, './logs/custom-field.log');
					}

				}
			}


			// end cusotm field insertion/////////////////

		}


		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property " . $_POST['property_title'] . " (" . $id . ") has  Updated by $_POST[uploader]",
			'type' => 'update',
			'user_details' => json_encode($this->user_detail())


		);
		$activity = $this->insert_userAct('user_actvity', $act, $csrf_token);

		session_start();

		if ($data) {
			$_SESSION['suc'] = 'Property Update successfully';
		} else {
			$_SESSION['fal'] = ' Sorry Something went wrong ' . $this->mysqli->error;
		}
		// $this->msg_set($data, 'property_listing');
		header("location: ?nav=property_listing&edit=$lit");

		die;
	}


	function seo_update()
	{
		$slug = trim($_POST['slug']);


		// 2. Prepare the data
		$seoData = [
			'related_id' => $this->sanitize($_POST['pro_id']),
			'type' => 'property',
			'slug' => $this->sanitize($_POST['slug']),
			'canonical_url' => $this->sanitize($_POST['canonical_url']),
			'meta_title' => $this->sanitize($_POST['meta_title']),
			'meta_description' => $this->sanitize($_POST['meta_description']),
			'meta_keywords' => json_encode(array_map('trim', explode(',', $_POST['meta_keywords']))),
			'uploader' => $_POST['uploader'],
			'user_id' => $_POST['user_id']
		];


		// print_r($seoData);
		// 	die;

		// 3. Insert or update
		$csrf = $_POST['csrf_token'];
		if (isset($_POST['seo_id']) && !empty($_POST['seo_id'])) {

			$where = "id = " . $_POST['seo_id'];
			$data12 = $this->csrfProUpdate('seo_data', $seoData, $where, $csrf);
		} else {
			$data = $this->csrfProInsert('seo_data', $seoData, $csrf);
		}


		// 4. Log activity
		$log = array(
			'user_id' => $_POST['user_id'],
			'action' => "SEO for slug <b>$slug</b> was " . "updated" . " by " . $_POST['user']
		);
		$this->csrf_insert('user_actvity', $log, $csrf);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Seo info inserted successfully';
		} elseif ($data12) {
			$_SESSION['suc'] = ' Seo info updated successfully';
		} else {
			$_SESSION['fal'] = ' Sorry Something went wrong';
		}
		header("location: ?nav=property&edit=" . $_POST['pro_id']);
		die;
	}





	function add_property_project()
	{
		$city_id = $_POST['city'];
		$city_data = $this->mysqli->query("select * from city where id = " . $city_id);
		$row[] = $city_data->fetch_object();
		$location_id = $_POST['location'];
		$location_data = $this->mysqli->query("select * from locations where id = " . $location_id);
		$row_location[] = $location_data->fetch_object();


		$array = array(
			'pro_name' => $this->sanitize($_POST['pro_name']),
			'pro_created_by' => $_POST['uploader'],
			'city' => $row[0]->city,
			'pro_location' => $row_location[0]->location,
			'sub_location' => $_POST['sub_location']
		);

		$data = $this->insert_qry('project', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");


		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Project " . $this->sanitize($_POST['pro_name']) . " (" . $last_id . ")  has Created",
			'date' => $today

		);
		$activity = $this->insert_query('user_actvity', $act);





		session_start();
		if ($data) {

			$_SESSION['suc'] = 'Data Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		if ($_POST['edit_id']) {
			$edit_id = $_POST['edit_id'];
			header("location: ?edit=" . $edit_id);
			die;
		} else {
			header("location: ?nav=properties");
			die;
		}
	}


	function delete_propertyimage()
	{
		$id = $_GET['edit'];
		$image = $_GET['propertyimage'];
		$imgqry = $this->mysqli->query("SELECT * FROM property_listing where id =$id ");
		$r_data = $imgqry->fetch_assoc();

		if ($r_data['gallery_image']) {
			$galleryimage = json_decode($r_data['gallery_image']);
			$final_array = array();
			foreach ($galleryimage as $img) {
				if ($img != $image) {
					array_push($final_array, $img);
				}
			}

			$gallery_images = json_encode($final_array);
			$query = "Update property_listing 
			SET gallery_image='$gallery_images'
				Where id = $id
				";
			$data = $this->mysqli->query($query);

			session_start();
			if ($data) {
				$_SESSION['suc'] = 'Image Deleted successfully';
			} else {
				$_SESSION['fal'] = ' Sorry Something went wrong';
			}
		} else {
			$_SESSION['fal'] = ' Sorry Something went wrong';
		}
	}

	function tower_property()
	{

		$p = $_POST['project'];
		$pro = $this->getQuery("SELECT * from project where id='$p'");
		$pro_value = $pro[0];
		$array = array(
			'project_id' => $_POST['project'],
			'tower_name' => $_POST['tower_name']

		);
		// print_r($array);
		// die;
		$data = $this->insert_qry('tower', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");


		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Project " . $pro_value->pro_name . "Tower " . $_POST['tower_name'] . " (" . $last_id . ")  has Created",
			'date' => $today

		);
		$activity = $this->insert_query('user_actvity', $act);

		session_start();
		if ($data) {

			$_SESSION['suc'] = 'Data Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		if ($_POST['edit_id']) {
			$edit_id = $_POST['edit_id'];
			header("location: ?edit=" . $edit_id);
			die;
		} else {
			header("location: ?nav=properties");
			die;
		}
	}



	function bookProperty()
	{

		$booking_id = uniqid();

		$array = array(
			'booked_to' => $_POST['client_id'],
			'booked_by' => $_POST['username'],
			'booking_id' => $booking_id,
			'status' => '2',
			'deal_price' => $_POST['deal_amount'],
			'remark' => $_POST['remark'],
			'bookingdate_start' => $_POST['booking_date'],
			'bookingdate_end' => $_POST['avilable_date']
		);
		// print_r($array);
		// die;
		$where = "id =" . $_POST['property_id'];






		if (isset($_POST['commission'])) {


			$array2 = array(
				'client_id' => $_POST['client_id'],
				'uploader' => $_POST['username'],
				'property_id' => $_POST['property_id'],
				'commission' => $_POST['commission'],
				'user_type' => $_POST['user_type'],
				'user_id' => $_POST['user_id'],
				'property_type' => 'property',

			);
		}

		if (isset($_POST['property_commission'])) {


			$array3 = array(
				'client_id' => $_POST['client_id'],
				'uploader' => $_POST['username'],
				'property_id' => $_POST['property_id'],
				'commission' => @$_POST['property_commission'],
				'user_type' => $_POST['property_user_type'],
				'user_id' => $_POST['property_user_id'],
				'property_type' => 'property',

			);
		}

		//   echo "<pre>";
		// 	print_r($array);
		// 	echo "</pre>";


		// // 	echo "<pre>";
		// // 	print_r($array2);
		// // 	echo "</pre>";

		// 	echo "<pre>";
		// 	print_r($array3);
		// 	echo "</pre>";

		//  	die;


		/*$property_id 					= $_POST['property_id'];
																																																																																																																																																																														 $client_id 						= $_POST['client_id'];
																																																																																																																																																																														 $agent_id 						= $_POST['agent_id'];
																																																																																																																																																																														 $deal_amount 					= $_POST['deal_amount'];
																																																																																																																																																																														 //$commission_first_agent 		= $_POST['commission_first_agent'];
																																																																																																																																																																														 //$commission_second_agent 		= $_POST['commission_second_agent'];
																																																																																																																																																																														 //$description 					= $_POST['description'];
																																																																																																																																																																														 //$status 	
																																																																																																																																																																														 
																																																																																																																																																																														 
																																																																																																																																																																														 = '1';*/

		$data = $this->update_query('property_listing', $array, $where);



		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property " . $_POST['property_title'] . " (" . $_POST['property_id'] . ") has  Booked",
			'date' => $today

		);
		$activity = $this->insert_query('user_actvity', $act);


		session_start();

		if ($data) {
			$booked = array(
				'property_id' => $_POST['property_id'],
				'client_id' => $_POST['client_id'],
				'deal_amount' => $_POST['deal_amount'],
				'booked_by' => $_POST['username'],
				'bookingdate_start' => $_POST['booking_date'],
				'bookingdate_end' => $_POST['avilable_date']
			);


			$data1 = $this->insert_qry('booked_log', $booked);


			if ($_POST['commission'] > 0 or $_POST['property_commission'] > 0) {


				if (isset($_POST['commission'])) {
					$data2 = $this->insert_query('commission', $array2);
				}

				if (isset($_POST['property_commission'])) {
					$data3 = $this->insert_query('commission', $array3);
				}



				if ($data2 or $data3) {

					$_SESSION['suc'] = 'Booked successfully with booking id: ' . $booking_id . ' and commission added successfully';
				} else {

					$_SESSION['suc'] = 'Booked successfully with booking id: ' . $booking_id . ' but commission not added ';
				}
			} else {

				$_SESSION['suc'] = 'Booked successfully with booking id: ' . $booking_id;
			}
		} else {
			$_SESSION['fal'] = ' Sorry Something went wrong';
		}
		header("location: ../../booking-payments.php?nav=leads&id=" . $_POST['property_id']);
		die;
	}


	function addAmount()
	{

		$property_id = $_POST['property_id'];
		$client_id = $_POST['client_id'];
		$add_payment = $_POST['add_payment'];
		$description = $_POST['description'];
		$payment_by = 'admin';
		$payment_method = $_POST['payment_method'];
		$transection_id = $_POST['transection_id'];
		$patment_reminder = $_POST['patment_reminder'];

		$data = $this->mysqli->query("INSERT INTO payment_details ( property_id, client_id, amount, comments, payment_by, 	payment_mode, transection_id, payment_reminder)

			values

			('$property_id', $client_id, $add_payment, '$description', '$payment_by', '$payment_method', '$transection_id', '$patment_reminder') ");

		if ($data) {
			$_SESSION['suc'] = 'Amount Added successfully';
		} else {
			$_SESSION['fal'] = ' Sorry Something went wrong';
		}
	}




	function add_property_note()
	{
		$pid = $this->int($_GET['id']);
		$pro = $this->getQuery("SELECT * from property_listing where id='$pid'");
		$pro_value = $pro[0];

		$array = array(
			'property_id' => $this->int($_GET['id']),
			'date' => $_POST['date'],
			'uploader' => $_POST['uploader'],
			'remarks' => $_POST['property_note']
		);

		$data = $this->insert_qry('property_remarks', $array);

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property " . $pro_value->property_listing . " (" . $pid . ") Note " . $_POST['property_note'] . "   Added",
			'date' => $today

		);
		$activity = $this->insert_query('user_actvity', $act);


		$nav = "?nav=properties&id=" . $_GET['id'] . "#notes";
		$this->msg_set($data, $nav);
	}



	function update_property_note()
	{
		$pid = $this->int($_GET['id']);
		$pro = $this->getQuery("SELECT * from property_listing where id='$pid'");
		$pro_value = $pro[0];
		$array = array(
			'property_id' => $this->int($_GET['id']),
			'date' => $_POST['date'],

			'uploader' => $_POST['uploader'],
			'remarks' => $_POST['property_note']
		);

		$where = 'id = ' . $_POST['note_id'];
		$data = $this->update_qry('property_remarks', $array, $where);
		$nav = "?nav=properties&id=" . $_GET['id'] . "#notes";

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property " . $pro_value->property_listing . " (" . $pid . ") Note " . $_POST['property_note'] . " has Updated",
			'date' => $today

		);
		$activity = $this->insert_query('user_actvity', $act);


		$this->msg_set($data, $nav);
	}





	function cancel_booking()
	{
		session_start();
		if ($_POST['booking']) {
			//booked_to, booking_date, booking_id, emi_plan, deal_price,booked_by, status
			$blank = NULL;


			$array = array(
				'booked_to' => $blank,

				'booking_id' => $blank,

				'deal_price' => $blank,
				'booked_by' => $blank,
				'status' => 1
			);


			// print_r($array);
			// die;
			$id = $_POST['plot_id'];
			//	$where = "id = " . $_POST['plot_id'];
			$data = $this->update_query_my("update property_listing set booked_to=NULL,booking_id=NULL,deal_price=NULL,booked_by=NULL,status=1 where id=$id");
			if ($data) {
				$booking = 'Booking Cancelled. ';
			}
		}



		if ($_POST['payments']) {
			$id = $_POST['plot_id'];
			echo $booking_id = $_POST['booking_id'];

			//$payments = $this->mysqli->query("SELECT * FROM payments where booking_id ='$booking_id' ");
			$payments = $this->mysqli->query("delete FROM payments where booking_id = '$booking_id' ");


			if ($payments) {

				$payments = 'Payment Cancelled. ';
			}
		}



		if ($_POST['commission']) {
			$id = $_POST['plot_id'];
			echo $booking_id = $_POST['booking_id'];

			//$payments = $this->mysqli->query("SELECT * FROM payments where booking_id ='$booking_id' ");
			$commission = $this->mysqli->query("delete FROM commission where property_id = '$id' ");


			if ($commission) {
				$commission = 'Commission Cancelled. ';
			}
		}


		// session_start();

		if ($data or $payments or $commission) {
			$_SESSION['suc'] = $booking . " " . $payments . " " . $commission;
		} else {
			$_SESSION['fal'] = 'Oops! not Updated!' . $this->mysqli->error;
		}
		header("location: ?nav=properties");
		die;
	}



	function property_export()
	{

		if (isset($_POST["export-property"])) {

			ob_start();

			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=listing-data.csv');
			header('Cache-Control: no-cache');
			//header('Content-Length: '. ob_get_length());


			$output = fopen("php://output", "w");
			fputcsv($output, array('ID', 'Name', 'Address', 'For', 'Type', 'Category', 'Furnished Status', 'Price', 'Deposit', 'Amenities', 'Remarks', 'Status', 'Date', 'Location', 'Project'));
			$exp = $this->mysqli->query("SELECT pl.id, pl.property_title, pl.address, pl.available_for, pl.property_type, pt.type, pl.furnished_status, pl.property_price, pl.deposit,pl.property_amenities, pl.remark, pl.status, pl.create_date,pl.location ,p.pro_name from property_listing as pl LEFT JOIN property_type as pt ON pl.category = pt.id LEFT JOIN project as p ON  pl.project_type = p.id   ORDER BY id DESC");

			while ($row = mysqli_fetch_assoc($exp)) {
				fputcsv($output, $row);
			}
			$streamSize = ob_get_length();
			header('Content-Length: ' . ob_get_length());

			// Flush (send) the output buffer and turn off output buffering
			ob_end_flush();
			fclose($output);





			date_default_timezone_set("Asia/Kolkata");
			$today = date("Y-m-d h:i:sa");

			$act = array(
				'user_id' => $_POST['user_id'],
				'action' => " Property has been export",
				'date' => $today

			);
			$activity = $this->insert_query('user_actvity', $act);
		}
	}















	function export_property()
	{

		// echo $_POST['status'];
		// die;
		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");


		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Property has Exported",
			'date' => $today

		);
		$activity = $this->insert_query('user_actvity', $act);

		// Server hostname or IP address
		$server_hostname = HOSTS;

		// The name of your MySQL database instance
		$database_name = DATABASE;

		// The username of your database login credential 
		$username = USERNAME;

		// The password of your database login credential
		$password = PASSWORD;

		$link_sqli = mysqli_connect($server_hostname, $username, $password, $database_name);

		// If an error occurred while connecting to the database, display the error code and exit.
		if (!$link_sqli) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging error #: " . mysqli_connect_errno() . PHP_EOL;
			echo "Error description: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		// END: Establish a connection to the database

		// BEGIN: Define some variables
		// INSTRUCTION: Specify your table name and the name of your export file.

		// The name of data table containing the data you wish to export
		$TableName = $_POST['table'];

		// The filename you want your export file to be named
		$Filename = $_POST['table'];
		$status = $_POST['status'];

		// END: Define some variables

		// *** No more configurable options below this point for this code to function on most servers ***
		// Fetch records from the database table specified in the variable $TableName
		$Output = "";
		$strSQL = "SELECT * FROM property_listing where status = '$status'";
		$sql = mysqli_query($link_sqli, $strSQL);
		// If the database query encounters an error, display the error message.
		// Otherwise, start the export process.
		if (mysqli_error($link_sqli)) {
			echo mysqli_error($link_sqli);
		} else {
			// Determine the number of data columns in the table
			$columns_total = mysqli_num_fields($sql);

			// Get the name of the data columns so it can be used in the header row of the export file.
			// Content of the export file is temporarily saved in the variable $Output
			for ($i = 0; $i < $columns_total; $i++) {
				$Heading = mysqli_fetch_field_direct($sql, $i);
				$Output .= '"' . $Heading->name . '",';
			}
			$Output .= "\n";
			// The /n is the control code to go to a new line in the export file.

			// Loop through each record in the table and read the data value from each column.
			while ($row = mysqli_fetch_array($sql)) {
				for ($i = 0; $i < $columns_total; $i++) {
					$Output .= '"' . $row["$i"] . '",';
				}
				$Output .= "\n";
			}

			// Create the export file and name it with the name specified in variable $Filename
			// Also appends the current timestamp (in the format yyyymmddhhmmss) to the filename and give it a .CSV file extension.
			// The timestamp serves as a time reference to identify when the data was exported.
			//File is comma delimited with double-quote used a the text qualifier
			// Once  file is created, download of the file begins automatically (tested on Google Chrome).
			$TimeNow = date("YmdHis");
			$Filename .= $TimeNow . ".csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $Filename);
			echo $Output;
		}
		exit;
	}



	function export_spro()
	{

		if (isset($_POST["export_spro"])) {


			if ($_POST['s']) {
				$search = " where " . $_POST['s'];
			}
			// echo ("SELECT pl.id,pl.property_title,pl.address,pl.available_for,pl.property_type,pl.category,pl.furnished_status,pl.property_price,pl.deposit,pl.property_amenities,pl.remark,pl.status,create_date from property_listing   $search ORDER BY id DESC"); 



			// die;

			ob_start();

			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=data.csv');
			header('Cache-Control: no-cache');
			//header('Content-Length: '. ob_get_length());


			$output = fopen("php://output", "w");
			fputcsv($output, array('ID', 'Name', 'Owner_name', 'Owner_mobile', 'Unit Number', 'Address', 'For', 'Type', 'Category', 'Furnished Status', 'Price', 'Deposit', 'Amenities', 'Remarks', 'Status', 'Date', 'Location', 'Project'));
			$exp = $this->mysqli->query("SELECT pl.id,pl.property_title, o.name ,o.contact,pl.unit_no,pl.address,pl.available_for,pl.property_type,pt.type,pl.furnished_status,pl.property_price,pl.deposit,pl.property_amenities,pl.remark,pl.status,pl.create_date , pl.location , p.pro_name  from property_listing as pl LEFT JOIN property_type as pt ON pl.category = pt.id  LEFT JOIN project as p ON  pl.project_id = p.id  LEFT JOIN owner as o ON pl.owner_id = o.id $search ORDER BY id DESC");

			while ($row = mysqli_fetch_assoc($exp)) {
				fputcsv($output, $row);
			}
			$streamSize = ob_get_length();
			header('Content-Length: ' . ob_get_length());

			// Flush (send) the output buffer and turn off output buffering
			ob_end_flush();
			fclose($output);
			exit;
		}
	}
}



if (isset($_POST['add_property'])) {
	$obj = new product();
	$obj->add_property();
}

if (isset($_POST['add_property_project'])) {
	$obj = new product();
	$obj->add_property_project();
}

if (isset($_POST['edit_property_info'])) {
	$obj = new product();
	$obj->edit_property_info();
}

if (isset($_GET['propertyimage']) && !isset($_POST['edit_property_info'])) {
	$obj = new product();
	$obj->delete_propertyimage();
}

if (isset($_POST['book_property'])) {
	$obj = new product();
	$obj->bookProperty();
	$boj->book_log();
}

if (isset($_POST['add_amount'])) {
	$obj = new product();
	$obj->addAmount();
}

if (isset($_POST['add-property-note'])) {
	$obj = new product();
	$obj->add_property_note();
}

if (isset($_POST['update-property-note'])) {
	$obj = new product();
	$obj->update_property_note();
}

if (isset($_POST['cancel-booking'])) {
	$obj = new product();
	$obj->cancel_booking();
}

if (isset($_POST['export-property'])) {
	$obj = new product();
	$obj->property_export();
}
if (isset($_POST['export-property-status'])) {
	$obj = new product();
	$obj->export_property();
}
if (isset($_POST['tower-property'])) {
	$obj = new product();
	$obj->tower_property();
}
if (isset($_POST['export_spro'])) {
	$boj = new product();
	$boj->export_spro();
}
if (isset($_POST['seoUpdate'])) {
	$boj = new product();
	$boj->seo_update();
}
