<?php
// error_reporting(E_ALL);  // Show all types of errors
// ini_set('display_errors', 1);  // Display errors on the screen

class lead extends db
{

	function lead_add()
	{
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d-m-Y h:i:s");

		$browserData = $this->getBrowser();

		$array = array(
			'lead_name' => $_POST['lead_name'],
			'lead_contact' => $_POST['lead_contact'],
			'lead_mail' => $_POST['lead_mail'],
			'remarks' => $_POST['lead_message'],
			'lead_location' => $_POST['lead_location'],
			'property_type' => $_POST['property_type'],
			'contract' => $_POST['contract'],
			'required_property_location' => $_POST['required_property_location'],
			'lead_status' => 'un-attempted',
			'reference' => $_POST['source_id'],
			'agent_id' => @$_POST['agent_id'],
			'lead_uploaded_by' => 'website',
			'reference' => 3,
			'lead_date' => $date_time,
			'property_link_id' => $_POST['property_id'],
			'machine_name' => "$browserData[platform]",
			'browser' => "$browserData[name]",
			'host_ip' => $_SERVER['REMOTE_ADDR']
		);


		// print_r($array);
		// die;
		$csrf_token = $_POST['csrf_token'];

		if (empty($_POST['lead_contact']) || empty($_POST['lead_name'])) {
			@session_start();
			return $_SESSION['fal'] = 'Please fill out all required fields before submitting.';
		}


		if (isset($_POST['lead_contact']) && !empty($_POST['lead_contact'])) {
			// check dublicate entry
			$contact = $_POST['lead_contact'];

			$fetch_data = $this->mysqli->query("select id from leads where lead_contact='$contact'");
			if ($fetch_data->num_rows > 0) {
				$fetch_data = $fetch_data->fetch_object();
				// var_dump($fetch_data);
				// die;
				$where = 'id = ' . $fetch_data->id;
				$arr = array(
					'lead_status' => 'un-attempted',

					'remarks' => $_POST['lead_message']

				);
				if (isset($_POST['property_id'])) {
					$arr['property_link_id'] = $_POST['property_id'];

				} elseif (isset($_POST['project_id'])) {
					$arr['project'] = $_POST['project_id'];
				}

				$updt = $this->update_query('leads', $arr, $where, $csrf_token);
				$affect_row = $fetch_data->id;
				if (!empty($affect_row)) {
					$arr_rem = array(
						'lead_id' => $affect_row,
						'remarks' => 'Visited by ' . $_POST['lead_name'] . '(' . $_POST['property_id'] . ')',
						'machine_name' => "$browserData[platform]",
						'browser' => "$browserData[name]",
						'ip' => $_SERVER['REMOTE_ADDR']
					);
					$this->insert_query('remarks', $arr_rem, $csrf_token);
				}
			} else {

				$data = $this->insert_query('leads', $array, $csrf_token);
			}
		}

		@session_start();
		if ($data || $updt) {
			header('location: ' . DOMAIN . 'thank_you/');
			exit;
			// 			$_SESSION['suc'] = 'Thank you for choosing ' . SITENAME . '. We recieved your requirement. Our represntative will contact you as soon as possible.<br /> For more information you can call us ' . CONTACT . ' or mail us at ' . EMAIL;
		} else {
			$_SESSION['fal'] = 'We already received your query with this No: <strong><i>' . $_POST['lead_contact'] . '</i></strong>. Duplicate record found in our database.<br />For more information you can call us at <strong>' . CONTACT . '</strong> or mail us at <strong>' . EMAIL . '</strong>';
		}

		if (isset($_POST['property_id']) && !empty($_POST['property_id'])) {
			$loc = "id=" . $_POST['property_id'];
		} else {
			$loc = '';
		}
		header("location: ?$loc");
		die;



	}
	function enquire_now()
	{
		date_default_timezone_set("Asia/Kolkata");
		$date_time = date("d/m/Y h:i:s");

		$browserData = $this->getBrowser();
		$array = array(
			'lead_name' => $_POST['lead_name'],
			'lead_contact' => $_POST['lead_contact'],
			'lead_mail' => $_POST['lead_mail'],
			'lead_message' => $_POST['lead_message'],
			'lead_location' => $_POST['lead_location'],
			'property_type' => $_POST['property_type'],
			'contract' => $_POST['contract'],
			'required_property_location' => $_POST['required_property_location'],
			'lead_status' => 'un-attempted',
			'reference' => $_POST['source_id'],
			'agent_id' => @$_POST['agent_id'],
			'lead_uploaded_by' => 'website',
			'reference' => 3,
			'lead_date' => $date_time,
			'property_link_id' => $_POST['property_id'],
			'machine_name' => "$browserData[platform]",
			'browser' => "$browserData[name]",
			'host_ip' => $_SERVER['REMOTE_ADDR']
		);


		// print_r($array);
// die;
		$csrf_token = $_POST['csrf_token'];

		if (empty($_POST['lead_contact']) || empty($_POST['lead_name'])) {
			@session_start();
			return $_SESSION['fal'] = 'Please fill out all required fields before submitting.';
		}

		if (isset($_POST['lead_contact']) && !empty($_POST['lead_contact'])) {
			// check dublicate entry
			$contact = $_POST['lead_contact'];

			$fetch_data = $this->mysqli->query("select id from leads where lead_contact='$contact'");
			if ($fetch_data->num_rows > 0) {
				$fetch_data = $fetch_data->fetch_object();
				// var_dump($fetch_data);
				// die;
				$where = 'id = ' . $fetch_data->id;
				$arr = array(
					'lead_status' => 'un-attempted',
					'property_link_id' => $_POST['property_id'],
					'remarks' => $_POST['lead_message']

				);
				$updat = $this->update_query('leads', $arr, $where, $csrf_token);
			} else {

				$data = $this->insert_query('leads', $array, $csrf_token);
			}


		}
		@session_start();
		if ($data || $updat) {
			header('location: ' . DOMAIN . 'thank_you/');
			exit();

			$_SESSION['su'] = 'Thank you for choosing ' . SITENAME . '. We recieved your requirement. Our represntative will contact you as soon as possible. For more information you can call us ' . CONTACT . ' or mail us at ' . EMAIL;
		} else {
			die('not update');
			$_SESSION['fa'] = 'We already received your query with this No: <strong><i>' . $_POST['lead_contact'] . '</i></strong>. Duplicate record found in our database.<br />For more information you can call us at <strong>' . CONTACT . '</strong> or mail us at <strong>' . EMAIL . '</strong>';
		}

		if (isset($_POST['property_id']) && !empty($_POST['property_id'])) {
			$loc = "id=" . $_POST['property_id'];
		} else {
			$loc = '';
		}

	}
	function mail()
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['message'];

		$fromMail = 'webmail@domain.com';
		$mailto = 'itwaysindia@gmail.com';
		$subject = "Website Contact form, submitted by " . $name;

		$eol = PHP_EOL;

		$uid = md5(uniqid(time()));

		$header = "From: " . $name . " <" . $email . ">\n";
		$header .= "Reply-To: " . $email . "\n";
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\n\n";
		$emessage = "--" . $uid . "\n";
		$emessage .= "Content-type:text/html; charset=iso-8859-1\n";
		$emessage .= "Content-Transfer-Encoding: 7bit\n\n";
		$emessage .= $message . "\n\n";
		$emessage .= "--" . $uid . "\n";
		$emessage .= "--" . $uid . "--";
		mail($mailto, $subject, $emessage, $header);
	}
}


if (isset($_POST['enquire-now']) && $_POST['lead_contact']) {
	$obj = new lead();
	$obj->enquire_now();
}

if (isset($_POST['lead_add'])) {
	$obj = new lead();
	$obj->lead_add();
}
if (isset($_POST['submit-contact'])) {

	$obj = new lead();
	$obj->lead_add();
}
?>