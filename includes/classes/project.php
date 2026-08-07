<?php

class project extends db
{

	function project_add()
	{

		if (isset($_FILES['browsers']) && !empty($_FILES['browsers']['tmp_name'][0])) {

			$name = $this->uploadFiles($_FILES['browsers']);
		} else {
			$name = '';
		}

		if (isset($_FILES['gallery_image']) && !empty($_FILES['gallery_image']['tmp_name'][0])) {

			$gallery_images = $this->uploadFiles($_FILES['gallery_image']);

		} else {
			$gallery_images = '';
		}

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		if (isset($_POST['offer'])) {
			$offer = implode(",", $_POST['offer']);
		} else {
			$offer = '';
		}





		if (isset($_POST['feature_property'])) {
			$feature_property = $_POST['feature_property'];
		} else {
			$feature_property = 0;
		}
		if (isset($_POST['show_builder'])) {
			$show_builder = $_POST['show_builder'];
		} else {
			$show_builder = 0;
		}

		if (isset($_POST['multi_city_loc'])) {
			$multi_city_loc = $_POST['multi_city_loc'];
		} else {
			$multi_city_loc = 0;
		}




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


		// user details 
		$userDetails = $this->user_detail();

		$array = array(
			'pro_name' => $this->sanitize($_POST['pro_name']),
			'pro_type' => implode(',', $_POST['project_type']),
			'rera_no' => $this->sanitize($_POST['rera']),
			'location' => $this->sanitize($_POST['location']),
			'city' => $this->sanitize($_POST['city']),
			'sub_location' => $this->sanitize($_POST['sub_location']),
			'pro_area' => $this->sanitize($_POST['pro_area']),
			'amenities' => $amenities,
			'pro_description' => $_POST['pro_description'],
			'pro_image' => $name,
			'offer' => $offer,
			'feature_property' => $this->sanitize($feature_property),
			'max_prize' => $this->sanitize($_POST['max_prize']),
			'status' => $this->sanitize($_POST['status']),
			'publish' => $this->sanitize($_POST['publish']),
			'min_prize' => $this->sanitize($_POST['min_prize']),
			'start_date' => $this->sanitize($_POST['l_date']),
			'end_date' => $this->sanitize($_POST['e_date']),

			'video' => $this->sanitize($_POST['video']),
			'map' => $_POST['map'],
			'pro_created_time' => $date_time,
			'multi_city_loc' => $this->sanitize($multi_city_loc),
			'pro_created_by' => $_POST['uploader'],
			// 'machine_name' => json_encode($userDetails),
			'client_ip' => $this->sanitize($userDetails['ip']),
			'client_browser' => $this->sanitize($userDetails['browser']),
			'area' => $this->sanitize($_POST['area']),
			'price' => $this->sanitize($_POST['price']),
			'max_area' => $this->sanitize($_POST['max_area']),
			'min_area' => $this->sanitize($_POST['min_area']),
			'builder' => $this->sanitize($_POST['builder']),
			'builder_staff' => $this->sanitize($_POST['contact_person']),
			'builder_email' => $this->sanitize($_POST['email']),
			'contact' => $this->sanitize($_POST['phone']),
			'show_builder_info' => $this->sanitize($show_builder),
			'gallery' => $gallery_images,
			'nearest_location' => $jsonData,
			'offer_keyword' => $offer_keyword,



		);




		$csrf_token = $_POST['csrf_token'];
		// print_r($_POST);
		// echo implode(',', $_POST['project_type']);
		// // // echo $csrf_token;
		// die;
		$data = $this->csrfProInsert('project', $array, $csrf_token);
		$last_id = $this->mysqli->insert_id;
		$keyword = !empty($_POST['meta_keywords']) ? json_encode(array_map('trim', explode(',', $_POST['meta_keywords']))) : '';
		$seoData = [
			'related_id' => $last_id,
			'type' => 'project',
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

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");
		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Project " . $_POST['pro_name'] . " (" . $last_id . ") Created by $_POST[uploader]",
			'type' => __FUNCTION__,
			'user_details' => json_encode($userDetails)

		);

		$this->insert_userAct('user_actvity', $act, $csrf_token);


		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Project Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		if ($_POST['for_plot'] == 1) {
			header("location: ?nav=ploting");
		} else {
			header("location: ?nav=projects");
		}
		die;
	}



	function project_update()
	{
		$id = $_POST['project_id'];

		// echo $id;
		// die;
		// fetch gallery image

		$imgqry = $this->mysqli->query("SELECT gallery FROM project where id ='$id' ");
		$r_data = $imgqry->fetch_assoc();


		if (!empty($_FILES["property_image"]["tmp_name"])) {
			$property_image = $this->uploadFiles($_FILES['property_image']);

		}
		// print_r($property_image);
		// die;



		if (!empty($r_data['gallery'])) {
			$galleryimage = json_decode($r_data['gallery']);
		} else {
			$galleryimage = array();
		}

		if (!empty($_FILES["gallery_image"]["tmp_name"][0])) {
			$gallery_image = $this->uploadFiles($_FILES['gallery_image']) ?? [];
			$gallery_images = array_merge($galleryimage, json_decode($gallery_image));

		}
		$gallery_images = json_encode($gallery_images ?? $galleryimage);

		// print_r($gallery_images);
		// print_r($property_image);
		// die;


		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}

		if (isset($_POST['offer'])) {
			$offer = implode(",", $_POST['offer']);
		} else {
			$offer = '';
		}

		if (!$_POST['publish']) {
			$publish = 0;
		} else {
			$publish = $_POST['publish'];
		}

		if (!$_POST['feature_property']) {
			$feature_property = 0;
		} else {
			$feature_property = $_POST['feature_property'];
			;
		}
		if (isset($_POST['show_builder'])) {
			$show_builder = $_POST['show_builder'];
		} else {
			$show_builder = 0;
		}
		if (isset($_POST['multi_city_loc'])) {
			$multi_city_loc = $_POST['multi_city_loc'];
		} else {
			$multi_city_loc = 0;
		}
		if ($multi_city_loc == 1) {
			$city = '';
			$location = '';
			$sub_location = "";
		} else {
			$city = $_POST['city'];
			$location = $_POST['location'];
			$sub_location = $_POST['sub_location'];
		}


		$nearest_locations = isset($_POST['nearest_loc']) ? $_POST['nearest_loc'] : [];
		$distances = isset($_POST['distance']) ? $_POST['distance'] : [];
		$times = isset($_POST['time']) ? $_POST['time'] : [];

		$dataArray = [];

		// Ensure all arrays have equal length
		if (!empty($nearest_locations) && !empty($distances) && !empty($times)) {
			foreach ($nearest_locations as $key => $locations) {
				if (!empty($locations) && !empty($distances[$key]) && !empty($times[$key])) {
					$dataArray[] = [
						"location" => $locations,
						"distance" => $distances[$key],
						"time" => $times[$key]
					];
				}
			}
		}
		// Convert the array to JSON
		$jsonData = !empty($dataArray) ? json_encode($dataArray) : '';

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


		$array = array(
			'pro_name' => $this->sanitize($_POST['pro_name']),
			'pro_type' => $this->sanitize($_POST['pro_type']),
			'rera_no' => $this->sanitize($_POST['rera']),
			'city' => $this->sanitize($city),
			'location' => $this->sanitize($location),
			'sub_location' => $this->sanitize($sub_location),
			'pro_area' => $this->sanitize($_POST['pro_area']),
			'amenities' => $amenities,
			'pro_description' => $_POST['pro_description'],
			'pro_image' => $property_image,
			'offer' => $offer,
			'state' => $this->sanitize($_POST['state']),
			'max_prize' => $this->sanitize($_POST['max_prize']),
			'status' => $this->sanitize($_POST['status']),
			'publish' => $publish,
			'feature_property' => $feature_property,
			'min_prize' => $this->sanitize($_POST['min_prize']),
			'start_date' => $this->sanitize($_POST['l_date']),
			'end_date' => $this->sanitize($_POST['e_date']),
			'gallery' => $gallery_images,
			'video' => $this->sanitize($_POST['video']),
			'multi_city_loc' => $this->sanitize($multi_city_loc),

			'area' => $this->sanitize($_POST['area']),
			'price' => $this->sanitize($_POST['price']),
			'max_area' => $this->sanitize($_POST['max_area']),
			'min_area' => $this->sanitize($_POST['min_area']),
			'builder' => $this->sanitize($_POST['builder']),
			'builder_staff' => $this->sanitize($_POST['contact_person']),
			'builder_email' => $this->sanitize($_POST['email']),
			'contact' => $this->sanitize($_POST['phone']),
			'show_builder_info' => $this->sanitize($show_builder),
			'nearest_location' => $dataArray,
			'offer_keyword'=>$offer_keyword



		);
		$csrf_token = $_POST['csrf_token'];
// 		print_r($array);
// 		die;

		$where = "id = " . $id;
		$data = $this->csrfProUpdate('project', $array, $where, $csrf_token);




		// activity code
		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");
		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Project " . $_POST['pro_name'] . " (" . $_POST['id'] . ") has Updated",


		);
		// $data = $this->csrf_insert('user_actvity', $act,$csrf_token);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Project Upadte Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		if ($_POST['for_plot'] == 1) {
			header("location: ?nav=ploting&edit=" . $id);
		} else {
			header("location: ?nav=projects&edit=" . $id);
		}
		die;
	}

	function seo_update()
	{
		$slug = trim($_POST['slug']);


		// 2. Prepare the data
		$seoData = [
			'related_id' => $this->sanitize($_POST['pro_id']),
			'type' => 'project',
			'slug' => $_POST['slug'],
			'canonical_url' => $_POST['canonical_url'],
			'meta_title' => $_POST['meta_title'],
			'meta_description' => $_POST['meta_description'],
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
		header("location: ?nav=projects&edit=" . $_POST['pro_id']);
		die;
	}



	function delete_proimage()
	{
		$id = $_GET['edit'];
		$image = $_GET['proimage'];
		$imgqry = $this->mysqli->query("SELECT * FROM project where id ='$id' ");
		$r_data = $imgqry->fetch_assoc();

		if ($r_data['gallery']) {
			$galleryimage = json_decode($r_data['gallery']);
			//print_r($galleryimage);
			//die;
			$final_array = array();
			foreach ($galleryimage as $img) {
				if ($img != $image) {
					array_push($final_array, $img);
				}
			}

			$gallery_images = json_encode($final_array);
			$query = "Update project 
			SET gallery='$gallery_images'
				WHERE id = '$id'
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
		header("location: ?nav=projects&edit=$id");
		die;
	}




	function tower_add()
	{

		$array = array(
			'project_id' => $_POST['project'],
			'tower_name' => $_POST['tower_name'],
			'total_floor' => $_POST['total_floor'],
			'launch_date' => $_POST['launch_date'],
			'possession_date' => $_POST['possession_date'],
			'status' => $_POST['status']
		);

		$data = $this->insert_qry('tower', $array);
		$last_id = $this->mysqli->insert_id;
		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");
		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Tower " . $_POST['tower_name'] . " (" . $last_id . ") has Created by $_POST[uploader]",


		);
		$data = $this->insert_query('user_actvity', $act);




		for ($i = 0; $i < count($_POST["floor_title"]); $i++) {


			$name = $this->uploadFiles($_FILES['floor_plan']);
			$floor = array(
				'floor_title' => $_POST['floor_title'][$i],
				'tower_id' => $last_id,
				'floor_image' => $name
			);
			$data1 = $this->insert_qry('floor', $floor);
		}

		session_start();
		if ($data) {
			if ($data1) {

				$_SESSION['suc'] = 'Data Added Successfully';
			} else {
				$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
			}
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=projects");

		die;
	}




	function another_flore()
	{
		$id = $_POST['tower_id'];
		for ($i = 0; $i < count($_POST["floor_title"]); $i++) {


			$uploads_dir2 = '../../uploads';
			$file_name = explode('.', $_FILES['floor_plan']['name'][$i]);
			$file_tmp = $_FILES['floor_plan']['tmp_name'][$i];
			$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
			$name = $random . '.' . end($file_name);
			move_uploaded_file($file_tmp, "uploads_dir2/$name");
			$name = $this->uploadFiles($_FILES['floor_plan']);

			$floor = array(
				'floor_title' => $_POST['floor_title'][$i],
				'tower_id' => $id,
				'floor_image' => $name

			);
			$data = $this->insert_qry('floor', $floor);

			$last_id = $this->mysqli->insert_id;
			date_default_timezone_set("Asia/Kolkata");
			$today = date("Y-m-d h:i:sa");
			$act = array(
				'user_id' => $_POST['user_id'],
				'action' => " Floor " . $_POST['floor_title'][$i] . " (" . $last_id . ") has Added by $_POST[uploader]",


			);
			$data = $this->insert_query('user_actvity', $act);
		}
		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Data Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=projects&edit=" . $id);

		die;
	}


	function tower_update()
	{
		$id = $_POST["id"];
		$array = array(
			'project_id' => $_POST['project'],
			'tower_name' => $_POST['tower_name'],
			'total_floor' => $_POST['total_floor'],
			'launch_date' => $_POST['launch_date'],
			'possession_date' => $_POST['possession_date'],
			'status' => $_POST['status']
		);
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('tower', $array, $where);


		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");
		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Tower " . $_POST['tower_name'] . " (" . $_POST['id'] . ") has Updated by $_POST[uploader]",


		);
		$data = $this->insert_query('user_actvity', $act);


		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Tower Upadte Successfully';
		} else {
			$_SESSION['fal'] = ' not update, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=projects&edit=" . $id);

		die;
	}
	function unit_add()
	{


		$i = 0;
		$csrf = $_POST['csrf_token'];

		foreach ($_POST['unit_name'] as $unit) {

			$uploads_dir2 = '../../uploads';
			$file_name = explode('.', $_FILES['unit_plan']['name'][$i]);
			$file_tmp = $_FILES['unit_plan']['tmp_name'][$i];
			$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
			$name = $random . '.' . end($file_name);
			move_uploaded_file($file_tmp, "../../uploads/" . $name);

			if (!empty($_FILES['unit_plan']['tmp_name'][$i])) {
				$file = $_FILES['unit_plan'][$i];
				$img = $this->uploadFiles($file);
			} else {
				$img = '';
			}
			$array = array(
				'unit_name' => $_POST['unit_name'][$i],
				'project_id' => $_POST['project'],
				'tower_id' => $_POST['tower'],
				'unit_plan' => $img,
				'unit_size' => $_POST['unit_size'][$i],
				'unit_prize' => $_POST['unit_prize'][$i],
				'unit_title' => $_POST['unit_title'][$i]


			);

			$data = $this->csrf_insert('unit', $array, $csrf);
			$last_id = $this->mysqli->insert_id;
			date_default_timezone_set("Asia/Kolkata");
			$today = date("Y-m-d h:i:sa");
			$act = array(
				'user_id' => $_POST['user_id'],
				'action' => " Unit " . $_POST['unit_name'][$i] . " (" . $last_id . ") has Created by $_POST[uploader]",


			);
			$data = $this->insert_query('user_actvity', $act);
			//print_r($array);
			$i++;
		}
		//die;

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Unit Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=projects");

		die;
	}
	function unit_update()
	{
		//image edit------------------------------------------
		$id = $_GET['edit'];
		$imgqry = $this->mysqli->query("SELECT * FROM unit where id ='$id'");
		$r_data = $imgqry->fetch_assoc();

		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["unit_plan"]["tmp_name"])) {
			$tmp_name2 = $_FILES["unit_plan"]["tmp_name"];
			$temp2 = explode(".", $_FILES["unit_plan"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['unit_image'];
		}
		// print_r($property_image);
		// die;
		//dfkfghhj------------------------------------------


		$array = array(
			'unit_name' => $_POST['unit_name'],
			'project_id' => $_POST['project'],
			'tower_id' => $_POST['tower'],
			'unit_plan' => $property_image,
			'unit_size' => $_POST['unit_size'],
			'unit_prize' => $_POST['unit_prize'],
			'unit_title' => $_POST['unit_title']


		);
		$where = "id = " . $_POST['id'];
		$data = $this->update_query('unit', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");
		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Unit " . $_POST['unit_name'] . " (" . $id . ") has Updated by $_POST[uploader]",


		);
		$data = $this->insert_query('user_actvity', $act);




		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Unit Upadte Successfully';
		} else {
			$_SESSION['fal'] = ' not update, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=projects&edit=" . $_GET['edit']);

		die;
	}
	function floor_edit()
	{
		$floor = $_POST["floor_hidden"];
		$id = $_POST["main_id"];

		// echo $floor;
		// die;
		$imgqry = $this->mysqli->query("SELECT * FROM floor where id ='$floor'");
		$r_data = $imgqry->fetch_assoc();
		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["floor_plan"]["tmp_name"])) {
			$tmp_name2 = $_FILES["floor_plan"]["tmp_name"];
			$temp2 = explode(".", $_FILES["floor_plan"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST["floor_image"];
		}
		// print_r($property_image);
		// die;



		$array = array(
			'floor_title' => $_POST['floor_title'],
			'floor_image' => $property_image
		);
		// print_r($array);
		// die;
		$where = "id = " . $_POST["floor_hidden"];
		$data = $this->update_query('floor', $array, $where);


		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");
		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Floor " . $_POST['floor_title'] . " (" . $_POST["floor_hidden"] . ") has Updated by $_POST[uploader]",


		);
		$data = $this->insert_query('user_actvity', $act);


		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Floor Upadte Successfully';
		} else {
			$_SESSION['fal'] = ' not update, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=projects&edit=" . $id);

		die;
	}


	function location_add()
	{

		date_default_timezone_set("Asia/Kolkata");

		$array = array(
			'location' => $_POST['location'],
			'city' => $_POST['city'],
			'uploader' => $_POST['uploader']
		);
		// print_r($array);
		// die;
		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_insert('locations', $array, $csrf);
		$last_id = $this->mysqli->insert_id;

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Location " . $_POST['location'] . "  has created under " . $_POST['city'] . " City ",


		);
		// print_r($act);
		// die;
		$activity = $this->csrf_insert('user_actvity', $act, $csrf);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Location Add Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		if ($_POST['id']) {
			$id = $_POST['id'];
			header("location: " . $id);

			die;
		} else {
			header("location: ?");

			die;
		}
	}
	function sub_location_add()
	{

		date_default_timezone_set("Asia/Kolkata");

		$array = array(
			'sub_location' => $_POST['sub_location'],
			'location' => $_POST['location'],
			'city' => $_POST['city'],
			'uploader' => $_POST['uploader']
		);
		//print_r($array);
		//die;
		$csrf = $_POST['csrf_token'];

		$data = $this->csrf_insert('sub_location', $array, $csrf);
		$last_id = $this->mysqli->insert_id;

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Sub Location " . $_POST['sub_location'] . " has Added by $_POST[uploader]",


		);
		$activity = $this->csrf_insert('user_actvity', $act, $csrf);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Sub Location Add Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		if ($_POST['id']) {
			$id = $_POST['id'];
			header("location: ?nav=projects&edit=" . $id);

			die;
		} else {
			header("location: ?nav=projects");

			die;
		}
	}
	function sub_location_update()
	{

		date_default_timezone_set("Asia/Kolkata");

		$array = array(
			'sub_location' => $_POST['sub_location'],
			'location' => $_POST['location'],
			'city' => $_POST['city'],
			'uploader' => $_POST['uploader']
		);
		//print_r($array);
		//die;
		$where = "id = " . $_POST['edit'];
		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_update('sub_location', $array, $where, $csrf);


		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Sub Location " . $_POST['sub_location'] . " Updated  by $_POST[uploader]",


		);
		$activity = $this->csrf_insert('user_actvity', $act, $csrf);


		$this->msg_set($data, 'projects');
	}

	function location_update()
	{


		$array = array(
			'location' => $_POST['location'],
			'city' => $_POST['city'],
			'uploader' => $_POST['uploader']
		);
		// print_r( $array );
		//die;

		$csrf = $_POST['csrf_token'];
		$where = "id = " . $_POST['edit'];
		$data = $this->csrf_update('locations', $array, $where, $csrf);
		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Location " . $_POST['location'] . "  has  Updated by $_POST[uploader] ",


		);
		$activity = $this->csrf_insert('user_actvity', $act, $csrf);

		$this->msg_set($data, 'projects');
	}




	function city_add()
	{

		date_default_timezone_set("Asia/Kolkata");




		$data_arr = array(
			'city' => $_POST['city'],
			'uploader' => $_POST['uploader'],
			'user_id' => $_POST['user_id']
		);
		$csrf = $_POST['csrf_token'];

		$data = $this->csrf_insert('city', $data_arr, $csrf);
		$last_id = $this->mysqli->insert_id;

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " City " . $_POST['city'] . " has Created by $_POST[uploader]",


		);
		$activity = $this->csrf_insert('user_actvity', $act, $csrf);




		$this->msg_set($data, 'projects');
	}

	function city_add_edit()
	{

		date_default_timezone_set("Asia/Kolkata");

		$array = array(
			'city' => $_POST['city'],
			'uploader' => $_POST['uploader']
		);
		// print_r($array);
		// die;
		$data = $this->insert_qry('city', $array);
		$id = $_POST['id'];
		session_start();
		if ($data) {
			$_SESSION['suc'] = 'City Add Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=projects&edit=" . $id);


		die;
	}

	function city_update()
	{

		$data_arr = array(
			'city' => $_POST['city'],
			'uploader' => $_POST['uploader'],
			'user_id' => $_POST['user_id']
		);
		// print_r($data_arr);
		// die;

		$where = "id = " . $_POST['edit'];
		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_update('city', $data_arr, $where, $csrf);


		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");

		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " City " . $_POST['city'] . "   Updated by $_POST[uploader]",


		);
		$data = $this->csrf_insert('user_actvity', $act, $csrf);

		$this->msg_set($data, 'projects');
	}



	function upload()
	{
		$pid = $_GET['edit'];
		$pro = $this->getQuery("SELECT * from project where id='$pid'");
		$pro_value = $pro[0];

		$uploads_dir2 = '../../uploads/projects/';
		$file_name = explode('.', $_FILES['file']['name']);
		$file_tmp = $_FILES['file']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$file = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, $uploads_dir2 . $file);

		$field = $_POST['column'];
		$array = array(
			$field => $file
		);
		//print_r($array);
		//die;
		$where = "id = " . $_GET['edit'];
		$data = $this->update_query('project', $array, $where);

		date_default_timezone_set("Asia/Kolkata");
		$today = date("Y-m-d h:i:sa");
		$act = array(
			'user_id' => $_POST['user_id'],
			'action' => " Project " . $pro_value->pro_name . "(" . $_GET['edit'] . " ) " . $_POST['column'] . "  Uploaded by $_POST[uploader]",


		);
		$data = $this->insert_query('user_actvity', $act);






		$this->msg_set($data, '?nav=projects&edit=' . $_GET['edit']);
	}


}


if (isset($_POST['project-add'])) {
	$obj = new project();
	$obj->project_add();
}
if (isset($_POST['project-update'])) {
	$obj = new project();

	$obj->project_update();
}

if (isset($_GET['proimage'])) {
	$obj = new project();
	$obj->delete_proimage();
}

if (isset($_POST['tower-add'])) {
	$obj = new project();
	$obj->tower_add();
}
if (isset($_POST['tower-update'])) {
	$obj = new project();
	$obj->tower_update();
}
if (isset($_POST['unit-add'])) {
	$obj = new project();
	$obj->unit_add();
}
if (isset($_POST['unit_update'])) {
	$obj = new project();
	$obj->unit_update();
}
if (isset($_POST['another-flore'])) {
	$obj = new project();
	$obj->another_flore();

}

if (isset($_POST['floor_edit'])) {
	$obj = new project();
	$obj->floor_edit();
}


if (isset($_POST['location-add'])) {
	$obj = new project();
	$obj->location_add();
}
if (isset($_POST['location-update'])) {
	$obj = new project();
	$obj->location_update();
}
if (isset($_POST['sub_location-add'])) {
	$obj = new project();
	$obj->sub_location_add();
}
if (isset($_POST['sub_location-update'])) {
	$obj = new project();
	$obj->sub_location_update();
}

if (isset($_POST['city-add'])) {
	$obj = new project();
	$obj->city_add();
}
if (isset($_POST['city-add-edit'])) {
	$obj = new project();
	$obj->city_add_edit();
}
if (isset($_POST['city-update'])) {
	$obj = new project();
	$obj->city_update();
}

if (isset($_POST['upload'])) {
	$obj = new project();
	$obj->upload();
}
if (isset($_POST['seo_update'])) {
	$obj = new project();
	$obj->seo_update();
}
