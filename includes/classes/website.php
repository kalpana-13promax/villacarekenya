<?php

class website extends db
{

	// ===============ABOUT================

	function about_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'heading' => $this->sanitize($_POST['heading']),
			'sub_heading' => $this->sanitize($_POST['sub_heading']),
			'message' => $_POST['message']

		);

		// print_r
		$csrf_token = $_POST['csrf_token'];
		$data = $this->csrf_insert('tbl_about', $array, $csrf_token);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'ABOUT Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=website");
		die;
	}


	function about_edit()
	{
		// if(!empty($_FILES['browsers'])){
		// //$id = $_GET['edit'];
		// $file_name = explode ('.' , $_FILES['browsers']['name']);
		// $file_tmp = $_FILES['browsers']['tmp_name'];
		// $random = substr(number_format(time() * rand(),0,'',''),0,4);
		// $name = $random. '.' .end($file_name);
		// move_uploaded_file( $file_tmp,  "../../uploads/".$name );
		// }else{
		// 	$name=$_POST['image'];
		// }

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}

		$array = array(
			'heading' => $this->sanitize($_POST['heading']),
			'sub_heading' => $this->sanitize($_POST['sub_heading']),
			'message' => $_POST['message'],
			'pro_image' => $property_image,
			'uploader' => $_POST['uploader']
		);
		// 		 print_r($array);
		// 		  die;
		if ($_GET['edit']) {
			$where = "id = " . $_GET['edit'];
		} else {
			$where = "id = " . $_POST['edit'];
		}
		$csrf_token = $_POST['csrf_token'];
		$data = $this->csrf_update('tbl_about', $array, $where, $csrf_token);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'About Added Successfully';
		} else {
			$_SESSION['fal'] = 'Updation Failed!, Something wrong!' . $this->mysqli->error;
		}

		header("location: ?edit=" . $_GET['edit'] . "&nav=website");
		die;
	}



	// =================== Director=====================


	function director_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'name' => $this->sanitize($_POST['name']),
			'post' => $this->sanitize($_POST['post']),
			'director_message' => $_POST['director_message'],
			'pro_image' => $name,

		);

		$data = $this->insert_qry('tbl_director', $array);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Director Message Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=website");

		die;
	}


	function director_edit()
	{
		$id = $_GET['edit'];
		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");


		$array = array(
			'name' => $this->sanitize($_POST['name']),
			'post' => $this->sanitize($_POST['post']),
			'director_message' => $_POST['director_message'],
			'pro_image' => $property_image

		);


		$where = "id = $id";
		$data = $this->update_qry('tbl_director', $array, $where);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Director Message Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!' . $this->mysqli->error;
		}


		header("location: ?edit=" . $_GET['edit'] . "&nav=website");
		die;
	}


	// ========================= MISSION ============================


	function mission_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'mission_description' => $_POST['mission_description'],
			'vission_description' => $_POST['vission_description'],
			'pro_image' => $name

		);

		$data = $this->insert_qry('tbl_mission', $array);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Mission And Vissoin  Message Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		header("location: ?nav=website");

		die;
	}


	function mission_edit()
	{
		$id = $_GET['edit'];

		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}


		// 		$file_name = explode ('.' , $_FILES['browsers']['name']);
		// 		$file_tmp = $_FILES['browsers']['tmp_name'];
		// 		$random = substr(number_format(time() * rand(),0,'',''),0,4);
		// 		$name = $random. '.' .end($file_name);
		// 		move_uploaded_file( $file_tmp,  "../uploads/".$name );


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");


		$array = array(
			'mission_description' => $_POST['mission_description'],
			'vission_description' => $_POST['vission_description'],
			'pro_image' => $property_image


		);
		// 		print_r($array);
		// 		die;
		$where = "id = " . $_GET['edit'];
		$data = $this->update_qry('tbl_mission', $array, $where);


		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Mission & Vissoin Edited Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!' . $this->mysqli->error;
		}

		header("location: ?edit=" . $_GET['edit'] . "&nav=website");
		die;
	}



	// ======================================== BLOG ===================


	function blog_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'name' => $_POST['name'],
			'post' => $_POST['post'],
			'blog_title' => $_POST['blog_title'],
			'blog' => $_POST['blog'],
			'pro_image' => $name

		);
		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_insert('tbl_blog', $array, $csrf);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Blog Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		header("location: ?nav=website");
		die;
	}

	function slider_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

		$array = array(
			'user' => $_POST['pro_name'],
			'image' => $name

		);
		$csrf = $_POST['csrf_token'];

		$data = $this->csrf_insert('tbl_slider', $array, $csrf);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Slider Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=website");
		die;
	}




	function blog_edit()
	{
		$id = $_GET['edit'];


		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");


		$array = array(
			'name' => $_POST['name'],
			'post' => $_POST['post'],
			'blog_title' => $_POST['blog_title'],
			'blog' => $_POST['blog'],
			'pro_image' => $property_image

		);
		// print_r($array);
		// die;
		$where = "id = " . $_GET['edit'];
		$csrf_token = $_POST['csrf_token'];
		$data = $this->csrf_update('tbl_blog', $array, $where, $csrf_token);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Blog Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}
		header("location: ?edit=" . $_GET['edit'] . "&nav=website");
		die;
	}


	//================================ Testimonial ==================


	function testimonial_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'testimonial_name' => $this->sanitize($_POST['testimonial_name']),
			'testimonial_client_post' => $_POST['testimonial_client_post'],
			'testimonial_message' => $_POST['testimonial_message'],
			'pro_image' => $name

		);

		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_insert('tbl_testimonial', $array, $csrf);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Testimonial Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		header("location: ?nav=website");
		die;
	}


	function testimonial_edit()
	{

		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");


		$array = array(
			'testimonial_name' => $this->sanitize($_POST['testimonial_name']),
			'testimonial_client_post' => $_POST['testimonial_client_post'],
			'testimonial_message' => $_POST['testimonial_message'],
			'pro_image' => $property_image
		);
		// print_r($array);
		// die;
		$where = "id = " . $_GET['edit'];

		$csrf_token = $_POST['csrf_token'];
		$data = $this->csrf_update('tbl_testimonial', $array, $where, $csrf_token);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Testimonial Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}
		header("location: ?edit=" . $_GET['edit'] . "&nav=website");

		die;
	}





	// ==============================SERVICES========================

	function partner_add()
	{
	
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

if(!empty($_FILES['partner_image'])){
    $file_name= $this->uploadFiles($_FILES['partner_image']);
}else{
    $file_name='';
}
		$i = 0;

		$array = array(
			'partner_name' => $_POST['name'],
			'partner_image' => $file_name,
			'status'=>$_POST['status']??0,
			'uploader' => $_POST['user']
		);
		// print_r($array);
		// die;
		$csrf = $_POST['csrf_token'];
		$data = $this->csrf_insert('partner', $array, $csrf);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Partner Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		header("location: ?nav=website");
		die;
	}
	function partner_edit()
	{
	
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");

if(!empty($_FILES['partner_image'])){
    $file_name= $this->uploadFiles($_FILES['partner_image']);
}else{
    $file_name='';
}
		$i = 0;

		$array = array(
			'partner_name' => $_POST['name'],
			'partner_image' => $file_name,
			'status'=>$_POST['status']??0,
			'uploader' => $_POST['user']
		);
		// print_r($array);
		// die;
	
		$csrf = $_POST['csrf_token'];
		 $where=" id = ".$_POST['edit'];
	
		$data = $this->csrf_update('partner', $array, $where,$csrf);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Partner Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		header("location: ?nav=website");
		die;
	}


	function service_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'heading' => $this->sanitize($_POST['heading']),
			'short_description' => $_POST['short_description'],
			'image' => $name

		);

		$data = $this->insert_qry('tbl_services', $array);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Services Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=website");
		die;
	}


	function service_edit()
	{
		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");


		$array = array(
			'heading' => $_POST['heading'],
			'short_description' => $_POST['short_description'],
			'image' => $property_image

		);
		// print_r($array);
		// die;
		$where = "id = " . $_GET['edit'];
		$data = $this->update_qry('tbl_services', $array, $where);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Services Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}
		header("location: ?edit=" . $_GET['edit'] . "&nav=website");

		die;
	}





	//========================================= REALTORS ==========================================


	function realtors_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'name' => $this->sanitize($_POST['name']),
			'dealin' => $_POST['dealin'],
			'phone' => $_POST['phone'],
			'description' => $_POST['description'],
			'image' => $name

		);

		$data = $this->insert_qry('tbl_realtors', $array);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Realtors Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=website");

		die;
	}


	function realtors_edit()
	{

		$id = $_GET['edit'];


		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}

		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");


		$array = array(
			'name' => $this->sanitize($_POST['name']),
			'dealin' => $_POST['dealin'],
			'phone' => $_POST['phone'],
			'description' => $_POST['description'],
			'image' => $property_image

		);
		// print_r($array);
		// die;
		$where = "id = " . $_GET['edit'];
		$data = $this->update_qry('tbl_realtors', $array, $where);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Realtors Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}

		header("location: ?edit=" . $_GET['edit'] . "&nav=website");
		die;
	}



	//  ====================================Offer for Buyer ===============================


	function offer_buyer_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		if ($_POST['offer']) {
			$offer = '2';
		} else {
			$offer = $_POST['offer'];
		}
		$array = array(
			'offer_heading' => $this->sanitize($_POST['offer_heading']),
			'offer_description' => $_POST['offer_description'],
			'offer' => $offer,
			'offer_image' => $name

		);

		$data = $this->insert_qry('tbl_offer', $array);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Buyers Offer Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=website");

		die;
	}


	function offer_buyer_edit()
	{
		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['image'];
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		$offers = $_POST['offer'];


		if ($_POST['offer']) {
			$offer = 1;
		} else {
			$offer = 2;
		}


		$array = array(
			'offer_heading' => $this->sanitize($_POST['offer_heading']),
			'offer_description' => $_POST['description'],
			'offer' => $offer,
			'offer_image' => $property_image

		);
		//print_r($array);
		//die;
		$where = "id = " . $_GET['edit'];
		$data = $this->update_qry('tbl_offer', $array, $where);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Realtors Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!';
		}
		header("location: ?edit=" . $_GET['edit'] . "&nav=website");
		die;
	}










	// 	=================================== Offer for Realtors ================================

	function offer_realtor_add()
	{
		$uploads_dir2 = '../../uploads';
		$file_name = explode('.', $_FILES['browsers']['name']);
		$file_tmp = $_FILES['browsers']['tmp_name'];
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
		$name = $random . '.' . end($file_name);
		move_uploaded_file($file_tmp, "../../uploads/" . $name);
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");
		if (isset($_POST['amenities'])) {
			$amenities = implode(",", $_POST['amenities']);
		} else {
			$amenities = '';
		}
		$i = 0;
		$galleryimage = array();

		if (!empty($_FILES["gallery_image"]["tmp_name"])) {
			foreach ($_FILES["gallery_image"]["tmp_name"] as $key => $tmp_name) {
				$i++;
				$tmp_name3 = $_FILES["gallery_image"]["tmp_name"][$key];

				$temp3 = explode(".", $_FILES["gallery_image"]["name"][$key]);
				$gallery_image = 'gal_' . $i . '_' . time() . '.' . end($temp3);
				move_uploaded_file($tmp_name3, "$uploads_dir2/$gallery_image");
				array_push($galleryimage, $gallery_image);
			}
			$gallery_images = json_encode($galleryimage);
		} else {

			$gallery_images = '';
		}

		$array = array(
			'heading' => $this->sanitize($_POST['heading']),
			'description' => $_POST['description'],
			'image' => $name

		);

		$data = $this->insert_qry('tbl_offer_realtors', $array);



		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Realtors Offer Added Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong! ' . $this->mysqli->error;
		}
		header("location: ?nav=website");
		die;
	}


	function offer_realtor_edit()
	{
		$id = $_GET['edit'];
		$imgqry = $this->mysqli->query("SELECT * FROM tbl_offer_realtors where id ='$id' ");
		$r_data = $imgqry->fetch_assoc();

		$uploads_dir2 = '../../uploads';
		if (!empty($_FILES["browsers"]["tmp_name"])) {
			$tmp_name2 = $_FILES["browsers"]["tmp_name"];
			$temp2 = explode(".", $_FILES["browsers"]["name"]);
			$property_image = time() . '.' . end($temp2);
			move_uploaded_file($tmp_name2, "$uploads_dir2/$property_image");
		} else {

			$property_image = $_POST['b_image'];
		}


		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:sa");


		$array = array(
			'heading' => $this->sanitize($_POST['heading']),
			'description' => $_POST['description'],
			'image' => $property_image

		);
		//print_r($array);
		//die;
		$where = "id = " . $_GET['edit'];
		$data = $this->update_qry('tbl_offer_realtors', $array, $where);

		session_start();
		if ($data) {
			$_SESSION['suc'] = 'Offers Realtors Updated Successfully';
		} else {
			$_SESSION['fal'] = ' not insert, Something wrong!' . $this->mysqli->error;
		}
		header("location: ?edit=" . $_GET['edit'] . "&nav=website");
		die;
	}
}





if (isset($_POST['offer-realtor-add'])) {
	$obj = new website();
	$obj->offer_realtor_add();
}
if (isset($_POST['offer-realtor-edit'])) {
	$obj = new website();
	$obj->offer_realtor_edit();
}



if (isset($_POST['offer-buyer-add'])) {
	$obj = new website();
	$obj->offer_buyer_add();
}
if (isset($_POST['offer-buyer-edit'])) {
	$obj = new website();
	$obj->offer_buyer_edit();
}



if (isset($_POST['about-add'])) {
	$obj = new website();
	$obj->about_add();
}
if (isset($_POST['about-edit'])) {
	$obj = new website();
	$obj->about_edit();
}




if (isset($_POST['director-add'])) {
	$obj = new website();
	$obj->director_add();
}
if (isset($_POST['director-edit'])) {
	$obj = new website();
	$obj->director_edit();
}



if (isset($_POST['mission-add'])) {
	$obj = new website();
	$obj->mission_add();
}
if (isset($_POST['mission-edit'])) {
	$obj = new website();
	$obj->mission_edit();
}




if (isset($_POST['blog-add'])) {
	$obj = new website();
	$obj->blog_add();
}
if (isset($_POST['blog-edit'])) {
	$obj = new website();
	$obj->blog_edit();
}



if (isset($_POST['testimonial-add'])) {
	$obj = new website();
	$obj->testimonial_add();
}
if (isset($_POST['testimonial-edit'])) {
	$obj = new website();
	$obj->testimonial_edit();
}




if (isset($_POST['realtors-add'])) {
	$obj = new website();
	$obj->realtors_add();
}
if (isset($_POST['realtors-edit'])) {
	$obj = new website();
	$obj->realtors_edit();
}


if (isset($_POST['service-add'])) {
	$obj = new website();
	$obj->service_add();
}
if (isset($_POST['service-edit'])) {
	$obj = new website();
	$obj->service_edit();
}


if (isset($_POST['slider-add'])) {
	$obj = new website();
	$obj->slider_add();
}
if (isset($_POST['partner-add'])) {
	$obj = new website();
	// print_r($_FILES);
	// die;
	$obj->partner_add();
}
if (isset($_POST['partner-edit'])) {
	$obj = new website();
	// print_r($_FILES);
	// die;
	$obj->partner_edit();
}

