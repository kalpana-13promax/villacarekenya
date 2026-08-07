<?php


class itways extends db
{
	public function getConnection()
	{
		return $this->mysqli;
	}
	function mysql($qry)
	{
		return $this->mysqli->query($qry);
	}

	function login()
	{
		//-------------- ip --------------------------------

		$ip = $_SERVER['REMOTE_ADDR'];

		$json = file_get_contents("https://ipwhois.app/json/$ip");
		$json = json_decode($json, true);
		$country_name = $json['country'];
		$country_code = $json['country_code'];
		$country = $country_name . " [" . $country_code . "]";
		$country_flag = $json['country_flag'];
		$region = $json['region'];
		$city = $json['city'];
		$lat = $json['latitude'];
		$lon = $json['longitude'];
		$location = $lat . "," . $lon;
		$isp = $json['isp'];


		$expiry = strtotime(EXPIRY);

		date_default_timezone_set("Asia/Kolkata");
		$cdate = date("Y-m-d"); // Current date
		$today = strtotime($cdate); // Convert current date to timestamp





		if ($today < $expiry) {
			$pass = md5($_POST['password']);
			$data = $this->mysqli->query("SELECT * FROM user where username = '$_POST[username]' and password = '$pass' and status='active' ");


			if ($data->num_rows) {
				$data_obj = $data->fetch_object();


				@session_start();
				$_SESSION['admin'] = $data_obj->id;

				$id_ll = $_SESSION['admin'];
				$username = $data_obj->username;
				$usertype = $data_obj->usertype;
				$uname = $data_obj->name;

				// Set "Remember Me" cookie if checkbox is checked
				if (isset($_POST['remember_me'])) {
					$token = hash('sha256', $data_obj->id . $data_obj->username);  // Generate a secure token
					setcookie('remember_me', $token, time() + 86400, '/', '', true, true);  // Set for 24 hours
					setcookie('user_id', $data_obj->id, time() + 86400, '/', '', true, true);
				}

				date_default_timezone_set('Asia/Kolkata');

				$ctime = date('Y-m-d H:i:s');


				$ua = $this->getBrowser();
				$browser = $ua['name'] . ", Ver: " . $ua['version'];
				$os = $ua['platform'];




				$user_logtime = "User Login Time is " . $ctime;
				$sql = $this->mysqli->query("INSERT INTO login_history (userid, username, login_date_time, os, browser, ip, host, country, state, city, loc, postal) 
        values ('$id_ll', '$username', '$ctime', '$os', '$browser', '$ip', '$isp', '$country', '$region', '$city', '$location', '$country_flag')");

				$sql = $this->mysqli->query("INSERT INTO user_actvity (user_id, action ,date) values ('$id_ll', '$user_logtime', '$ctime')");

				date_default_timezone_set('Asia/Kolkata');

				$time = date('H:i');

				header("location: dashboard");
				die;
			} else {

				$_SESSION['msg'] = 'incorrect username or password';
				header("location: ?login-error");
				die;
			}
		} else {
			session_start();
			$_SESSION['msg'] = "Login Alert: Your Licence period has ended. Please contact to service provider.";
			header("location: ?security-error");
			die;
		}
	}





	function otp_log()
	{
		$otp = $_POST['otp'];
		// echo $otp;
		// die;
		$otp = $this->mysqli->query("SELECT * FROM user where otp = '$otp' and status='active' ");
		if ($otp->num_rows) {
			//$data_obj = $data->fetch_object();
			// echo $otp;
			// die;
			header("location: dashboard/");
		} else {

			session_start();
			$_SESSION['msg'] = 'incorrect username or password';
			header("location: ?login-error");
			die;
		}
	}



	//------------------------ Check browser os--------------------------

	function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}

		// Next get the name of the useragent yes seperately and for good reason
		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif (preg_match('/Firefox/i', $u_agent)) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif (preg_match('/OPR/i', $u_agent)) {
			$bname = 'Opera';
			$ub = "Opera";
		} elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif (preg_match('/Netscape/i', $u_agent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		} elseif (preg_match('/Edge/i', $u_agent)) {
			$bname = 'Edge';
			$ub = "Edge";
		} elseif (preg_match('/Trident/i', $u_agent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
				$version = $matches['version'][0];
			} else {
				$version = $matches['version'][1];
			}
		} else {
			$version = $matches['version'][0];
		}

		// check if we have a number
		if ($version == null || $version == "") {
			$version = "?";
		}

		return array(
			'userAgent' => $u_agent,
			'name' => $bname,
			'version' => $version,
			'platform' => $platform,
			'pattern' => $pattern
		);
	}



	//------------------------/browser 0S Check-------------------------



	function csrf()
	{
		@session_start();
		$token = bin2hex(random_bytes(32)); // Generate a random token
		$_SESSION['csrf_token'] = $token; // Store the token in the session
		return $token;
	}



	function userdata()
	{
		@session_start();
		$user = @$_SESSION['admin'];
		if ($user) {
			$data = $this->mysqli->query("SELECT * FROM user where id = $user ");
			if ($data->num_rows) {
				return $data->fetch_object();
			} else {
				$obj = new itways();
				$obj->logout();
			}
		}
	}

	function company()
	{

		$data = $this->mysqli->query("SELECT * FROM company where id = '1' ");
		if ($data->num_rows) {
			return $data->fetch_object();
		}
	}




	/* ----------------------------- sanitizing data -------------------------- */

	function int($data)
	{
		$data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
		return $data;
	}


	function sanitize($data)
	{
		$data = trim($data); // Remove extra spaces, newlines, etc.
		$data = stripslashes($data); // Remove backslashes
		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); // Convert special characters to HTML entities
		return $data;
	}


	function sanitize_old($data)
	{
		$data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		$data = trim($data);
		$data = stripslashes($data);
		//$san = mysqli_real_escape_string($data);
		//  $data = htmlspecialchars($data);
		return $data;
	}


	function render($data)
	{
		return htmlspecialchars_decode($data, ENT_QUOTES); // Decode HTML entities to render original characters
	}


	/*------------------------------ Indian Currency ---------------------- */

	function price($num)
	{
		if (empty($num) || !is_numeric($num)) {
			// error_log(message: "Invalid price value: " . var_export($amount, true)); // Log invalid value
			return CURRENCY . ' 0.00';
		} else {

			$explrestunits = "";
			if (strlen($num) > 3) {
				$lastthree = substr($num, strlen($num) - 3, strlen($num));
				$restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
				$restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
				// explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
				$expunit = str_split($restunits, 2);
				for ($i = 0; $i < sizeof($expunit); $i++) {
					// creates each of the 2's group and adds a comma to the end
					if ($i == 0) {
						$explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
					} else {
						$explrestunits .= $expunit[$i] . ",";
					}
				}
				$thecash = $explrestunits . $lastthree;
			} else {
				$thecash = $num;
			}
			$price = CURRENCY . "&nbsp;" . $thecash;
			return $price; // writes the final format where $currency is the currency symbol.
		}
	}

	// function price($amount) {
	// 					$formattedAmount = number_format($amount, 2, '.', ',');
	// 					$currency = 'AED';
	// 					$convertedAmount = $currency . ' ' . $formattedAmount;
	// 					return $convertedAmount;
	// 				}
	/* ----------------------  // Indian Currency ------------------ */






	function getQuery($sql)
	{

		$data = $this->mysqli->query($sql);
		if ($data->num_rows == '1') {
			$row[] = $data->fetch_object();
		} else {

			while ($r = $data->fetch_object()) {
				$row[] = $r;
			}
		}
		return @$row;
	}

	function get($table)
	{

		$data = $this->mysqli->query("SELECT * FROM $table order by id desc ");
		if ($data->num_rows == '1') {
			$row[] = $data->fetch_object();
		} else {
			while ($r = $data->fetch_object()) {
				$row[] = $r;
			}
		}
		return @$row;
	}
	function getid($table, $id)
	{

		$data = $this->mysqli->query("SELECT * FROM $table where id = $id ");
		if ($data->num_rows == '1') {
			$row[] = $data->fetch_object();
		} else {
			while ($r = $data->fetch_object()) {
				$row[] = $r;
			}
		}
		return @$row;
	}
	function getwhere($table, $where)
	{
		//echo ("SELECT * FROM $table where $where ");
		//die;
		$data = $this->mysqli->query("SELECT * FROM $table where $where ");
		if ($data->num_rows == '1') {
			$row[] = $data->fetch_object();
		} else {
			while ($r = $data->fetch_object()) {
				$row[] = $r;
			}
		}
		return @$row;
	}
	function delete($table, $where, $location)
	{
		//echo "delete FROM $table where $where ";
		//die;
		$data = $this->mysqli->query("delete FROM $table where $where ");
		if ($data) {
			$_SESSION['suc'] = $_GET['source'] . ' deleted successfully';
		} else {
			$_SESSION['fal'] = 'Opps! not deleted';
		}
		header("location: $location");
		die;
	}

	function count_months($date1, $date2)
	{
		$d1 = strtotime($date1);
		$d2 = strtotime($date2);

		$totalSecondsDiff = abs($d1 - $d2); //42600225
		//$totalMinutesDiff = $totalSecondsDiff/60; //710003.75
		//$totalHoursDiff   = $totalSecondsDiff/60/60;//11833.39
		//$totalDaysDiff    = $totalSecondsDiff/60/60/24; //493.05
		$totalMonthsDiff = $totalSecondsDiff / 60 / 60 / 24 / 30; //16.43
		//$totalYearsDiff   = $totalSecondsDiff/60/60/24/365; //1.35

		return $totalMonthsDiff;
	}








	function count($sql)
	{
		$data = $this->mysqli->query($sql) ?? [];
		$row_count = $data->num_rows;

		return $row_count;
	}

	function delQuery($sql)
	{
		return $this->mysqli->query($sql);
	}

	function logout()
	{
		session_start();
		session_destroy();

		// Clear cookies
		setcookie('remember_me', '', time() - 3600, '/');
		setcookie('user_id', '', time() - 3600, '/');

		header("location: ?logout");
		die;
	}

	function check_session()
	{
		@session_start();
		if (empty($_SESSION['admin'])) {
			// Check if "Remember Me" cookie is set and valid
			if (isset($_COOKIE['remember_me']) && isset($_COOKIE['user_id'])) {
				$user_id = $_COOKIE['user_id'];
				$token = $_COOKIE['remember_me'];

				// Validate the token from the cookie
				$data = $this->mysqli->query("SELECT * FROM user WHERE id = '$user_id' AND status='active'");
				if ($data->num_rows) {
					$data_obj = $data->fetch_object();
					$valid_token = hash('sha256', $data_obj->id . $data_obj->username);
					if ($token === $valid_token) {
						// Restore the session
						$_SESSION['admin'] = $data_obj->id;
						return true;
					}
				}
			}

			// If session and cookie are both not valid, redirect to login
			session_destroy();
			header("location:" . BASEURL);
			die;
		}
	}




	// 	function no_access()
	// 	{
	// 		$_SESSION['fal'] = 'You are not permitted to access this page!';
	// 		echo '<div class="alert alert-danger" >
	// 						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	// 						<strong>Failed </strong> ' . $_SESSION['fal'] . '
	// 					</div>';
	// 		unset($_SESSION['fal']);
	// 	}

	function no_access($options = [])
	{
		// Default options
		$defaults = [
			'title' => 'Access Denied',
			'message' => 'You are not permitted to access this page!',
			'icon' => 'fa-lock',
			'type' => 'danger',
			'dismissible' => true,
			'return_url' => null,
			'show_contact' => false,
			'full_page' => false,
			'custom_class' => ''
		];

		// Merge options
		$options = array_merge($defaults, $options);

		// Alert type classes
		$typeClasses = [
			'danger' => 'alert-danger',
			'warning' => 'alert-warning',
			'info' => 'alert-info',
			'success' => 'alert-success'
		];
		$alertClass = $typeClasses[$options['type']] ?? $typeClasses['danger'];

		// Generate HTML
		$html = '
    <style>
        .no-access-alert {
            border-left: 4px solid;
            padding: 20px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .no-access-alert.danger {
            border-left-color: #d9534f;
            background-color: #fdf7f7;
        }
        .no-access-alert.warning {
            border-left-color: #f0ad4e;
            background-color: #fcf8f2;
        }
        .no-access-alert.info {
            border-left-color: #5bc0de;
            background-color: #f4f8fa;
        }
        .no-access-alert.success {
            border-left-color: #5cb85c;
            background-color: #f1f9f1;
        }
        .no-access-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .no-access-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        .no-access-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .no-access-message {
            margin-bottom: 15px;
            padding-left: 40px;
        }
        .no-access-actions {
            padding-left: 40px;
        }
        .no-access-btn {
            margin-right: 10px;
            margin-bottom: 5px;
        }
        .full-page-access-denied {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0,0,0,0.05);
            z-index: 1050;
        }
        .full-page-access-content {
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }
    </style>
    ';

		// Main alert content
		$alertContent = '
    <div class="no-access-alert ' . $alertClass . ' ' . $options['custom_class'] . '">
        <div class="no-access-header">
            <div class="no-access-icon">
                <i class="fa ' . $options['icon'] . '"></i>
            </div>
            <h3 class="no-access-title">' . $options['title'] . '</h3>
        </div>
        <div class="no-access-message">' . $options['message'] . '</div>';

		// Add action buttons if needed
		if ($options['return_url'] || $options['show_contact']) {
			$alertContent .= '<div class="no-access-actions">';
			if ($options['return_url']) {
				$alertContent .= '<a href="' . $options['return_url'] . '" class="btn btn-default btn-sm no-access-btn">
                                <i class="fa fa-arrow-left"></i> Go Back
                             </a>';
			}
			if ($options['show_contact']) {
				$alertContent .= '<a href="" class="btn btn-link btn-sm no-access-btn">
                                <i class="fa fa-envelope"></i> Contact Support
                              </a>';
			}
			$alertContent .= '</div>';
		}

		$alertContent .= '</div>';

		// Wrap in full page container if needed
		if ($options['full_page']) {
			$html .= '
        <div class="full-page-access-denied">
            <div class="full-page-access-content">
                ' . $alertContent . '
            </div>
        </div>';
		} else {
			$html .= $alertContent;
		}

		// Output the HTML
		echo $html;

		// Clear session message if it exists
		if (isset($_SESSION['fal'])) {
			unset($_SESSION['fal']);
		}
	}


	function slider($page1)
	{
		$page2 = basename($_SERVER['PHP_SELF']);
		if ($page1 == $page2) {
			echo 'class="nav-active"';
		}
	}

	function sendWhatsAppMessageToAgent($leadId, $phone)
	{
		// 1. Fetch lead and user data
		$query = "
            SELECT 
             l.*
            FROM leads l
            WHERE l.id = $leadId
        ";

		$result = $this->mysqli->query($query);
		if (!$result || $result->num_rows === 0) {
			throw new Exception("Lead not found.");
		}

		$data = $result->fetch_assoc();
		$tempate = $this->getQuery("SELECT content FROM templates WHERE name= 'whatsapp'");
		$tempate = $tempate[0]->content;




		// 3. Replace placeholders with actual data
		$placeholders = [
			'{name}',
			'{contact}',
			'{mail}',
			'{location}',
			'{project_name}',
			'{contract_type}',
			'{property_type}',
			'{category}',
			'{furnished_status}'
		];
		$replacements = [
			$data['name'],
			$data['contact'],
			$data['mail'],
			$data['location'],
			$data['project_name'],
			$data['contract_type'],
			$data['property_type'],
			$data['category'],
			$data['furnished_status']
		];
		$messageText = str_replace($placeholders, $replacements, $tempate);



		$token = 'XpUN2dqakhFbWhlbkJDaTNOamNQZz09'; // Replace with your actual token

		// 1. Prepare the payload
		$payload = [
			'username' => 'sachin7054',
			'token' => $token,
			'number' => $phone,
			'message' => html_entity_decode($messageText),
		];




		$url = 'http://wapi.itways.in/api/send-msg?' . http_build_query($payload);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			// Remove the Cookie header unless required
		));

		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlError = curl_error($curl);

		curl_close($curl);

		// Debug output
		if ($httpCode == 200) {
			return $response;
		} else {
			return "❌ Failed. HTTP $httpCode | Error: $curlError";
		}
	}
	function message()
	{
		if (@$_SESSION['suc']) {
			echo '<div class="alert alert-success" id="alert-success" >
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Success </strong> ' . $_SESSION['suc'] . '
					</div>';
		} elseif (@$_SESSION['fal']) {
			echo '<div class="alert alert-danger" id="alert-success" >
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Failed </strong> ' . $_SESSION['fal'] . '
					</div>';
		}
		unset($_SESSION['suc']);
		unset($_SESSION['fal']);
	}

	function datetime()
	{
		date_default_timezone_set("Asia/Kolkata");
		return $date = date("d/m/Y ");
	}

	function format_date($date)
	{
		date_default_timezone_set("Asia/Kolkata");
		if ($date) {
			return $newDate = date("d/m/Y", strtotime($date));
		} else {
			return $newDate = "";
		}
	}

	function mask($cc, $fillChar = '#')
	{

		$last4 = substr(str_replace(['-', ' '], '', $cc), -4);
		return str_pad($last4, 10, $fillChar, STR_PAD_LEFT);
	}



	function tel($code, $contact)
	{
		if ($code and $contact) {
			$country_code = '+' . $code . '-' . $contact;
		} elseif (!$code and $contact) {
			$country_code = '+' . C_CODE . '-' . $contact;
		} elseif (!$contact) {
			$country_code = "";
		}
		return $country_code;
	}

	//----------------------------- property related functions ------------------------------

	function property_status($type)
	{
		switch ($type) {
			case '0':
				$filter = 'Deactive';
				break;
			case '1':
				$filter = 'Available';
				break;
			case '2':
				$filter = 'Booked';
				break;
			case '3':
				$filter = 'Hold';
				break;
			case '4':
				$filter = 'Pending';
				break;
			default:
				$filter = 'Not Available';

				break;
		}

		return ($filter);
	}

	function property_category(int $category)
	{
		$data = $this->mysqli->query("SELECT * FROM property_type where id = $category ");
		$row[] = $data->fetch_object();
		$row = $row[0]->type;
		return $row;
	}
	function property_owner(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM owner where id = $id ");
		$row[] = $data->fetch_object();
		return $row;
	}


	function source(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM source where id = $id ");
		$row[] = $data->fetch_object();
		$row = $row[0]->source_name;
		return $row;
	}


	function contract($module, $contract_type)
	{
		$data = $this->mysqli->query("SELECT * FROM contract where module = '$module' AND contract_type = '$contract_type' ");
		$row[] = $data->fetch_object();
		$row = $row[0]->contract_name;
		return $row;
	}

	function get_staff(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM user where id = $id");
		$row[] = $data->fetch_object();
		$row = $row[0]->username . " [" . $row[0]->usertype . "]";
		return ucwords($row);
	}
	function getusername(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM user where id = $id");
		$row[] = $data->fetch_object();
		@$row = $row[0]->username . " [" . $row[0]->usertype . "]";
		return $row;
	}
	function get_agent(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM agent where id = $id ");
		$row[] = $data->fetch_object();
		$row = $row[0]->agent_name . " [" . $row[0]->id . "]";
		return $row;
	}
	function get_lead(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM leads where id = $id ");
		$row[] = $data->fetch_object();
		$row = $row[0];
		return array('lid' => $row->id, 'name' => $row->lead_name, 'contact' => $row->lead_contact);
	}

	function payment_mode(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM payment_modes where id = $id ");
		$row[] = $data->fetch_object();
		$row = $row[0]->mode_name;
		return $row;
	}

	function reset_otp(int $id)
	{
		$this->mysqli->query("Update owner Set otp = '' Where id = $id");
	}



	function update_notification($qry)
	{
		$data = $this->mysqli->query($qry);

		if ($data) {

			return true;
		} else {

			return $this->mysqli->error;
		}
	}

	function timeBefore($times)
	{
		$currentTime = time();
		$timestamp = strtotime($times); // Convert to UNIX timestamp
		$timeDifference = $currentTime - $timestamp;

		if ($timeDifference < 0) {
			// If the timestamp is in the future
			$timeDifference = abs($timeDifference);
			$prefix = 'after';
		} else {
			// If the timestamp is in the past
			$prefix = 'ago';
		}

		$intervals = array(
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);

		foreach ($intervals as $seconds => $unit) {
			if ($timeDifference >= $seconds) {
				$count = floor($timeDifference / $seconds);
				return $count . ' ' . $unit . ($count > 1 ? 's' : '') . ' ' . $prefix;
			}
		}

		return 'just now';
	}

	function lead_by_mobile($mobile)
	{
		$conuntry_code = "+" . C_CODE;
		$mobile = ltrim($number, $conuntry_code);
		return $mobile;
	}

	function call_to($phoneNumber)
	{
		$phoneLink = 'tel:' . $phoneNumber;
		?>
		<script type="text/javascript">
			let con = <?php echo $phoneNumber; ?>
		</script>

		<a href="<?= $phoneLink ?>" data-toggle="tooltip" title="<?= $phoneNumber; ?>"
			onClick="return confirm('Are you sure want to Call on '+con+ '?')"><i class="fa fa-phone"></i></a>
		<?php

	}

	function call_ivr($contact, $edit)
	{
		//	echo '<a href="tel:+'.$contact.'"><i class="fa fa-phone"></i></a>';
		?>
		<script type="text/javascript">
			var con = <?php echo json_encode($contact); ?>
			//console.log(con);
		</script>
		<?php if ($edit) {
			$redirect = 'href="' . BASEURL . 'leads/lead-edit/?edit=' . $edit . '&nav=leads&contact=' . $contact . '"';
		} else {
			$redirect = 'href="tel:+' . $contact . '" alt="' . $contact . '"';
		} ?>
		<a onClick="return confirm('Are you sure want to Call on '+con+ '?')" <?= $redirect; ?>><i class="fa fa-phone"></i></a>
		<?php


	}


	function mail_to($mail)
	{
		echo '<a target="_blank" href="mailto:' . $mail . '"><i class="fa fa-envelope"></i></a>';
	}

	function whatsapp_modal_ajax(int $contact, $lead_id)
	{
		$data = $this->mysqli->query("SELECT * FROM api WHERE name = 'whatsapp' AND api_status = '1'");
		$html = '';

		if ($data->num_rows) {
			$html .= '<a href="#myModal" data-id="' . $contact . '" data-toggle="modal" data-target="#myModal"><i class="fa fa-whatsapp"></i></a>';

			$html .= '<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">SEND WHATSAPP</h4>
						</div>
						<div class="modal-body">
							<form action="" method="POST">
								<input type="number" id="phone" name="mob" value="' . $contact . '" class="form-control">
								<br />
								<textarea name="msg" id="msg" style="height: 100px;" class="form-control"></textarea>
						</div>
						<div class="modal-footer">
							<input type="submit" name="wa-send" class="btn btn-primary" value="SEND">
							<input type="hidden" name="LeadID" class="btn btn-primary" value="' . $lead_id . '">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
							</form>
					</div>
				</div>
			</div>';

			$html .= "<script>
				$(document).ready(function() {
					$('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
						if (typeof $(this).data('id') !== 'undefined') {
							$('#phone').val($(this).data('id'));
						}
					});
				});
			</script>";
		} else {
			$html .= '<a target="_blank" href="https://wa.me/91' . $contact . '" ><i class="fa fa-whatsapp"></i></a>';
		}

		return $html;
	}






	function whatsapp_modal(int $contact, $lead_id)
	{
		$data = $this->mysqli->query("SELECT * FROM api where name = 'whatsapp' and api_status = '1' ");
		// $data = $this->mysqli->query("SELECT * FROM `api` where api_key='TnpqRVhMVmZMS2JXTC9UZEJFV2NXdz09' and api_status='1'"); 

		if ($data->num_rows) {



			echo '<a href="#myModal" data-id="' . $contact . '" data-toggle="modal" data-target="#myModal"><i class="fa fa-whatsapp"></i></a>';

			echo '<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
  
	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">SEND WHATSAPP</h4>
		</div>
		<div class="modal-body">
		  
  
  
  
	  <form action="" method="POST">
		 <input type="number" id="phone" name="mob" value="' . $contact . '" class="form-control"  >
	   <br />
		  <textarea name="msg" id="msg" style="height: 100px;" class="form-control"></textarea>
		</div>
	  
		<div class="modal-footer">
		  <input type="submit" name="wa-send" class="btn btn-primary" value="SEND">
		 
		  <input type="hidden" name="search" class="btn btn-primary" value="<?= $search; ?>">
		  <input type="hidden" name="LeadID" class="btn btn-primary" value="' . $lead_id . '">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
   </form>
	  </div>
  
	</div>
  </div>';
			echo "<script>
	$(document).ready(function() {

		$('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
		  var id = '';
		
		  if (typeof $(this).data('id') !== 'undefined') {
			data_id = $(this).data('id');
			$('#phone').val(data_id);
		  }
		  
		
		
		})
		});


	$('#myModal').on('show.bs.modal', function(e){
		console.log($(e.relatedTarget).attr('data-id'))
		var id = $(this).dataset.id
	})

	</script>";
		} else {
			echo '<a target="_blank" href="https://wa.me/' . $contact . '" ><i class="fa fa-whatsapp"></i></a>';
		}
	}


	function whatsapp_modal2(int $contact, $lead_id)
	{
		$data = $this->mysqli->query("SELECT * FROM api WHERE name = 'whatsapp' AND api_status = '1'");
		if (empty($data)) {
			$data = $this->mysqli->query("
		 SELECT wu.username,wa.api_key,wu.base_url FROM whatsapp_users wu  join  whatsapp_accounts wa  on wu.id=wa.user_id
        WHERE wu.status = 'connected'
        ORDER BY wa.id DESC 
        LIMIT 1");

		}
		;

		if ($data->num_rows) {
			echo '<a href="#myModal" data-id="' . $contact . '" data-toggle="modal" data-target="#myModal"><i class="fa fa-whatsapp"></i></a>';

			echo '
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">SEND WHATSAPP</h4>
                    </div>
                    <div class="modal-body">
                        <form id="wa-form">
                            <input type="number" id="phone" name="mob" value="' . $contact . '" class="form-control">
                            <br />
                            <textarea name="msg" id="msg" style="height: 100px;" class="form-control"></textarea>
                            <input type="hidden" name="LeadID" id="LeadID" value="' . $lead_id . '">
                        </form>
                        <div id="wa-response" style="margin-top:10px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="wa-send" class="btn btn-primary">SEND</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';

			echo "
        <script>
        $(document).ready(function() {
            // Prefill phone when modal opens
            $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
                if (typeof $(this).data('id') !== 'undefined') {
                    var data_id = $(this).data('id');
                    $('#phone').val(data_id);
                }
            });

            // Handle AJAX Send
            $('#wa-send').on('click', function(e) {
                e.preventDefault();

                var formData = {
                    mob: $('#phone').val(),
                    msg: $('#msg').val(),
                    LeadID: $('#LeadID').val()
                };

                $.ajax({
                    url: 'includes/ajax/lead/sendWa.php',
                    type: 'POST',
                    data: formData,
					dataType:'json',
                    beforeSend: function(){
                        $('#wa-send').prop('disabled', true).text('Sending...');
                        $('#wa-response').html('');
                    },
                    success: function(response){
					if(response.status){

                        $('#wa-response').html('<div class=\"alert alert-success\">'+response.message+'</div>');
                        $('#wa-send').prop('disabled', false).text('SEND');
                        $('#msg').val('');
                    }else{
						  $('#wa-response').html('<div class=\"alert alert-warning\">'+response.message+'</div>');
                        $('#wa-send').prop('disabled', false).text('SEND');
                        $('#msg').val('');
}},
                    error: function(xhr, status, error){
                        $('#wa-response').html('<div class=\"alert alert-danger\">Error: '+error.message+'</div>');
                        $('#wa-send').prop('disabled', false).text('SEND');
                    }
                });
            });
        });
        </script>";
		} else {
			echo '<a target="_blank" href="https://wa.me/' . $contact . '"><i class="fa fa-whatsapp"></i></a>';
		}
	}


	function remarks_modal_ajax(int $id)
	{
		$data = $this->mysqli->query("SELECT * FROM property_remarks WHERE property_id = '$id' ORDER BY id DESC");
		$tableRow = '';
		$i = 1;
		while ($row = $data->fetch_object()) {
			$tableRow .= '<tr><td>' . $i++ . '</td><td>' . $row->remarks . '</td><td>' . $row->date . '</td></tr>';
		}

		if ($data->num_rows) {
			return '
			<a class="btn btn-primary btn-xs btn-block" href="#propertyRemarksModal" data-toggle="modal" data-target="#propertyRemarksModal">
				<i class="fa fa-history"></i> View All
			</a>
			<div id="propertyRemarksModal" class="modal fade" role="dialog">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Remarks History</h4>
						</div>
						<div class="modal-body">
							<table id="remarksTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                ' . $tableRow . '
                            </tbody>
                        </table>
						</div>
					</div>
				</div>
			</div>
			<script>
            $(document).ready(function() {
                // Initialize DataTable with simple pagination
                $("#remarksTable").DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthMenu: [5, 10, 25, 50],
                    pageLength: 10
                });

               
            });
        </script>';
		}

		return '';
	}





	//----------------------------- property related functions ends------------------------------


	function inword(float $number)
	{
		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(
			0 => '',
			1 => 'One',
			2 => 'Two',
			3 => 'Three',
			4 => 'Four',
			5 => 'Five',
			6 => 'Six',
			7 => 'Seven',
			8 => 'Eight',
			9 => 'Nine',
			10 => 'Ten',
			11 => 'Eleven',
			12 => 'Twelve',
			13 => 'Thirteen',
			14 => 'Fourteen',
			15 => 'Fifteen',
			16 => 'Sixteen',
			17 => 'Seventeen',
			18 => 'Eighteen',
			19 => 'Nineteen',
			20 => 'Twenty',
			30 => 'Thirty',
			40 => 'Forty',
			50 => 'Fifty',
			60 => 'Sixty',
			70 => 'Seventy',
			80 => 'Eighty',
			90 => 'Ninety'
		);
		$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
		while ($i < $digits_length) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
			} else
				$str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
	}




	function csrf_token()
	{
		@session_start();
		// Check if a CSRF token already exists in the session
		if (!isset($_SESSION['csrf'])) {
			$token = bin2hex(random_bytes(32)); // Generate a random token
			$_SESSION['csrf'] = $token;   // Store the token in the session
		}
		return $_SESSION['csrf'];
	}



	function generateKey($keyLength)
	{
		// Set a blank variable to store the key in
		$key = "";
		for ($x = 1; $x <= $keyLength; $x++) {
			// Set each digit
			$key .= random_int(0, 9);
		}
		return $key;
	}

	function pass($length)
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ12345689';
		$my_string = '';
		for ($i = 0; $i < $length; $i++) {
			$pos = mt_rand(0, strlen($chars) - 1);
			$my_string .= substr($chars, $pos, 1);
		}
		return $my_string;
	}


	function enc($plaintext, $password, $encoding = null)
	{
		if ($plaintext != null && $password != null) {
			$keysalt = openssl_random_pseudo_bytes(16);
			$key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-gcm"));
			$tag = "";
			$encryptedstring = openssl_encrypt($plaintext, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag, "", 16);
			return $encoding == "hex" ? bin2hex($keysalt . $iv . $encryptedstring . $tag) : ($encoding == "base64" ? base64_encode($keysalt . $iv . $encryptedstring . $tag) : $keysalt . $iv . $encryptedstring . $tag);
		}
	}

	function dec($encryptedstring, $password, $encoding = null)
	{
		if ($encryptedstring != null && $password != null) {
			$encryptedstring = $encoding == "hex" ? hex2bin($encryptedstring) : ($encoding == "base64" ? base64_decode($encryptedstring) : $encryptedstring);
			$keysalt = substr($encryptedstring, 0, 16);
			$key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
			$ivlength = openssl_cipher_iv_length("aes-256-gcm");
			$iv = substr($encryptedstring, 16, $ivlength);
			$tag = substr($encryptedstring, -16);
			return openssl_decrypt(substr($encryptedstring, 16 + $ivlength, -16), "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag);
		}
	}






	function get99AcresLeads($allParams, $url)
	{
		$crl = curl_init($url);
		curl_setopt($crl, CURLOPT_POST, 1);
		curl_setopt($crl, CURLOPT_POSTFIELDS, $allParams);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
		return curl_exec($crl);
	}





	function insert_ignore($table, $data)
	{
		$data = array_filter($data);


		// 		print_r($data);
		// 		echo "</pre>";
		// 		die;
		$values = "";
		$key = array_keys($data);
		$key = implode(",", $key);
		foreach ($data as $value) {
			$values .= $this->sanitize($value) . "','";
		}
		$values = trim($values, ",'");
		$query = "INSERT IGNORE INTO $table ($key) 
        values
        ('$values')";
		//print_r($query);
		//die;
		return $this->mysqli->query($query);
	}




	function update_duplicate($table, $data)
	{
		$data = array_filter($data);
		echo "<pre>";

		// 		print_r($data);
		// 		echo "</pre>";
		// 		die;
		$values = "";
		$key = array_keys($data);
		$key = implode(",", $key);
		foreach ($data as $value) {
			$values .= $this->sanitize($value) . "','";
		}
		$values = trim($values, ",'");
		$query = "INSERT INTO $table ($key) 
        values
        ('$values')
        ON DUPLICATE KEY UPDATE lead_status = 'un-attempted'";
		//print_r($query);
		//die;
		return $this->mysqli->query($query);
	}






	function uri()
	{
		$dir = basename(getcwd());
		$path = $_SERVER['PHP_SELF'];
		$filename = basename($path, ".php");
		return $dir . '/' . $filename . '/';
	}

	function cwd()
	{
		$dir = basename(getcwd());

		return $dir . '/';
	}

	function setting()
	{
		$data = $this->mysqli->query("SELECT * FROM settings where id ='1' ");
		$data_obj = $data->fetch_object();


		if ($_FILES['logo']['name']) {
			$file_name = explode('.', $_FILES['logo']['name']);
			$file_tmp = $_FILES['logo']['tmp_name'];
			$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
			$logo = $random . '.' . end($file_name);
			move_uploaded_file($file_tmp, "uploads/" . $logo);
			//unlink('uploads/'.$data_obj->logo);
		} else {
			$logo = $data_obj->logo;
		}

		if ($_FILES['video']['name']) {

			$file_name = explode('.', $_FILES['video']['name']);
			$file_tmp = $_FILES['video']['tmp_name'];
			$random = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
			$video = $random . '.' . end($file_name);
			move_uploaded_file($file_tmp, "uploads/" . $video);
			//@unlink('uploads/'.$data_obj->video);
		} else {
			$video = $data_obj->video;
		}

		$data = $this->mysqli->query("UPDATE settings SET
														name = '$_POST[name]',
														logo = '$logo',
														video = '$video'
														where id = '1'
									");
		@session_start();
		if ($data) {
			$_SESSION['suc'] = 'setting updated successfully';
		} else {
			$_SESSION['fal'] = ' not updated';
		}
		header("location: setting.php");
		die;
	}







	function matched_property_by_leadid($lead_id)
	{
		// Get lead details
		$data = $this->mysqli->query("SELECT * FROM leads WHERE id = " . intval($lead_id));
		$row = $data->fetch_object();

		if (!$row) {
			return array('matched' => 0, 'q' => '');
		}

		// Build search conditions
		$conditions = array();

		// Cost condition
		if ($row->client_budget_min && $row->client_budget_max) {
			$conditions[] = "property_price BETWEEN '" . floatval($row->client_budget_min) . "' AND '" . floatval($row->client_budget_max) . "'";
		} else if ($row->client_budget_min) {
			$conditions[] = "property_price >= '" . floatval($row->client_budget_min) . "'";
		} else if ($row->client_budget_max) {
			$conditions[] = "property_price <= '" . floatval($row->client_budget_max) . "'";
		}

		// Contract/Availability condition
		if ($row->contract) {
			$conditions[] = "available_for = '" . $this->mysqli->real_escape_string($row->contract) . "'";
		}

		// Property type condition
		if ($row->property_type) {
			$property_types = explode(", ", $row->property_type);
			$type_conditions = array();
			foreach ($property_types as $type) {
				$type_conditions[] = "property_type = '" . $this->mysqli->real_escape_string($type) . "'";
			}
			if (!empty($type_conditions)) {
				$conditions[] = "(" . implode(" OR ", $type_conditions) . ")";
			}
		}

		// Furnished status condition
		if ($row->furnished_status) {
			$conditions[] = "furnished_status = '" . $this->mysqli->real_escape_string($row->furnished_status) . "'";
		}

		// Location condition
		if ($row->required_property_location) {
			$locations = explode(", ", $row->required_property_location);
			$location_conditions = array();
			foreach ($locations as $loc) {
				$location_conditions[] = "location = '" . $this->mysqli->real_escape_string($loc) . "'";
			}
			if (!empty($location_conditions)) {
				$conditions[] = "(" . implode(" OR ", $location_conditions) . ")";
			}
		}

		// Build WHERE clause
		$where = implode(" AND ", $conditions);
		if ($where) {
			$where .= " AND status != '2'";
		} else {
			$where = "status != '2'";
		}

		// Get matching properties count
		$matched = $this->count("SELECT * FROM property_listing WHERE $where");

		return array(
			'matched' => $matched,
			'q' => $where
		);
	}




	function csrf_update_gal($table, $data, $where, $csrf)
	{
		@session_start();


		if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $csrf) {

			return false;
			// die(" CSRF  validation failled.");

		}

		$data = array_filter($data);

		$values = "";
		$key = array_keys($data);
		//  $key = implode(",", $key);
		$i = 0;
		$trail = "";
		foreach ($data as $value) {
			$trail .= $key[$i] . " = '" . $value . "',";

			$i++;
		}
		$trail = trim($trail, ",");
		$query = 'UPDATE ' . $table . ' SET ' . $trail . ' WHERE ' . $where . ' ';

		// print_r($query);
		// die;
		@session_start();
		if ($_SESSION['csrf'] === $csrf) {
			$result = $this->mysqli->query($query);
		} else {
			$result = false;
		}

		return $result;
	}









	function schedule_visits($lid, $pid, $where)
	{
		?>



		<a CLASS="btn btn-primary" href="#visit" data-id="'.$lid.'" data-toggle="modal" data-target="#visit">SCHEDULE VISIT</a>

		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">SCHEDULE VISIT</h4>
					</div>
					<div class="modal-body">

						<?php
						$query = $this->mysqli->query("select * from property_listing");
						?>

						<form action="" method="POST">
							<select name="pid" class="form-control" required>
								<option value="">--- SELECT PROPERTY ---</option>

							</select>
							<br />
							<textarea name="msg" id="msg" style="height: 100px;" class="form-control"></textarea>
					</div>

					<div class="modal-footer">
						<input type="submit" name="wa-send" class="btn btn-primary" value="SEND">

						<input type="hidden" name="search" class="btn btn-primary" value="<?= $search; ?>">
						<input type="hidden" name="LeadID" class="btn btn-primary" value="'.$lead_id.'">
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


			$('#visit').on('show.bs.modal', function (e) {
				console.log($(e.relatedTarget).attr('data-id'))
				var id = $(this).dataset.id
			})
		</script>


		<?php

	} // function close


	function uploadFiles($files, $path = NULL, $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'], $max_size = 10 * 1024 * 1024, $maxWidth = 1500, $maxHeight = 2000)
	{
		ini_set('memory_limit', '180M'); // Increase memory limit
		$uploads_dir = dirname(__DIR__, 3);
		$uploads_dir = str_replace('\\', '/', $uploads_dir . '/uploads/');

		//  $uploads_dir = realpath($uploads_dir . $path);
		//  echo $uploads_dir;
		//  die;
		$responses = [];
		$i = 0;

		if (!is_array($files['name'])) {
			$galImg = false;
			$files = [
				'name' => [$files['name']],
				'type' => [$files['type']],
				'tmp_name' => [$files['tmp_name']],
				'error' => [$files['error']],
				'size' => [$files['size']]
			];
		} else {
			$galImg = true;
		}

		// Loop through each file
		foreach ($files['name'] as $key => $name) {
			if ($i == 15) {

				break;
			}

			if ($files['error'][$key] !== UPLOAD_ERR_OK) {
				$responses[] = ['file' => $name, 'status' => false, 'message' => 'Upload error.'];
				continue;
			}

			$file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$file_tmp = $files['tmp_name'][$key];

			// Validate file extension
			if (!in_array($file_ext, $allowed_extensions)) {
				$responses[] = ['file' => $name, 'status' => false, 'message' => 'Invalid file type.'];
				continue;
			}

			// Validate MIME type
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime_type = finfo_file($finfo, $file_tmp);
			finfo_close($finfo);

			$allowed_mime_types = [
				'image/jpeg',
				'image/png',
				'application/pdf',
			];
			if (!in_array($mime_type, $allowed_mime_types)) {
				$responses[] = ['file' => $name, 'status' => false, 'message' => 'Invalid file type.'];
				continue;
			}

			// Validate file size
			if ($files['size'][$key] > $max_size) {
				$responses[] = ['file' => $name, 'status' => false, 'message' => 'File size exceeds the maximum limit of 10MB.'];
				continue;
			}

			// Generate a unique file name
			if ($galImg) {
				$new_file_name = 'gal_' . $i . '_' . time() . '.' . $file_ext;
			} else {
				$random = substr(number_format(time() * rand(), 0, '', ''), 0, 5);
				$new_file_name = 'pro_' . $random . '.' . $file_ext;
			}

			$target_path = "$uploads_dir/$new_file_name";

			// Process and resize images
			if (in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
				list($origWidth, $origHeight) = getimagesize($file_tmp);
				$image = ($file_ext === 'png') ? imagecreatefrompng($file_tmp) : imagecreatefromjpeg($file_tmp);

				if ($image) {
					$resizedImage = $this->resizeImage($image, $origWidth, $origHeight, $maxWidth, $maxHeight);
					if ($file_ext === 'png') {
						imagepng($resizedImage, $target_path, 7);
					} else {
						imagejpeg($resizedImage, $target_path, 85);
					}
					imagedestroy($image);
					imagedestroy($resizedImage);

					$responses[] = ['file' => $new_file_name, 'status' => true];
				} else {
					$responses[] = ['file' => $name, 'status' => false, 'message' => 'Failed to process image.'];
				}
			} else {
				// Move non-image files
				if (move_uploaded_file($file_tmp, $target_path)) {
					$responses[] = ['file' => $new_file_name, 'status' => true];
				} else {
					$responses[] = ['file' => $name, 'status' => false, 'message' => 'Failed to upload file.'];
				}
			}
			$i++;
		}

		$gallery_image = array_filter($responses, function ($response) {
			return $response['status'] === true;
		});

		$gallery_image = array_column($gallery_image, 'file');

		return $galImg ? json_encode($gallery_image) : $gallery_image[0];
	}


	function insertData($table, $data, $csrf)
	{
		@session_start();
		if (isset($_SESSION['csrf']) && $_SESSION['csrf' !== $csrf]) {
			return false;
		}

		$data = array_filter($data, function ($value) {
			return $value !== null && $value !== '';
		});


		$values = [];
		$key = array_keys($data);
		$key = implode(",", $key);
		foreach ($data as $value) {
			$values[] = "'" . $this->sanitize($value) . "'";
		}
		$values = implode(' , ', $values);
		$query = "INSERT INTO $table ($key) 
        values
        ($values)";

		return $this->mysqli->query($query);
	}
	function insert_userAct($table, $data, $csrf)
	{
		@session_start();

		// Validate CSRF token
		if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $csrf) {
			return false; // CSRF token mismatch
		}

		// Ensure data is not empty
		if (empty($data)) {
			return false;
		}

		$columns = implode(", ", array_keys($data));
		$values = [];

		foreach ($data as $key => $value) {

			if ($key === 'user_details') {
				$values[] = "'" . $value . "'";
			} else {
				$values[] = "'" . $this->sanitize($value) . "'";
			}
		}

		$valuesString = implode(", ", $values);
		$query = "INSERT INTO `$table` ($columns) VALUES ($valuesString)";

		return $this->mysqli->query($query);
	}

	function updateQry($table, $data, $where, $csrf)
	{
		@session_start();


		if ($_SESSION['csrf'] !== $csrf) {

			return false;
			// die(" CSRF  validation failled.");

		}

		$data = array_filter($data, function ($value) {
			return $value !== false && $value !== null && $value !== '';
		});

		$values = "";
		$key = array_keys($data);
		//  $key = implode(",", $key);
		$i = 0;
		$trail = "";
		foreach ($data as $value) {
			$trail .= $key[$i] . " = '" . $this->sanitize($value) . "',";

			$i++;
		}
		$trail = trim($trail, ",");
		$query = 'UPDATE ' . $table . ' SET ' . $trail . ' WHERE ' . $where . ' ';

		// $values = trim($values, ",'");
		//  values
		// ('$values')";
		// print_r($query);
		// die;
		@session_start();
		if ($_SESSION['csrf'] === $csrf) {
			$result = $this->mysqli->query($query);
		} else {
			$result = false;
		}

		return $result;
	}

	function real_escape_string($data)
	{
		return $this->mysqli->real_escape_string($data ?? '');
	}
	function shareEntity($id, $type)
	{
		global $t_count, $uri, $check;

		// Determine module and table names based on type
		$module = ($type === 'property') ? 'properties' : 'projects';
		$table = ($type === 'property') ? 'property_listing' : 'project';
		$nameColumn = ($type === 'property') ? 'property_title' : 'pro_name';

		// Fetch data from the database
		$data = $this->getQuery("SELECT p.*, s.slug, s.canonical_url 
                           FROM $table p 
                           LEFT JOIN seo_data s ON p.id = s.related_id and s.type =  '{$type}' 
                           WHERE p.id = '{$id}' ");

		if (empty($data)) {
			return '<span class="text-danger"><i class="fa fa-exclamation-circle"></i> Invalid ID</span>';
		}
		$data = $data[0];
		$title = $data->$nameColumn;
		$link = $data->canonical_url ?? '';
		$pro = $data->id;
		$basePath = LANIDNG_BASEURL;
		$landing = $basePath . '/' . $data->slug;
		$crmproperty = dirname(BASEURL, 1) . '/property-info?token=' . $data->property_id;
		;

		// Check permissions
		$canEdit = $check->check_permission($module, 'edit');
		$canDelete = $check->check_permission($module, 'delete');
		$canBook = $check->check_permission('properties', 'book');
		$owner_all = $check->check_permission('owners', 'view_all');
		$owner_own = $check->check_permission('owners', 'view_own');

		// $ref = $this->getid('source', $data->reference_source);
		$agent = '';
		if ($data->reference_source == '1' && !empty($data->referance_agent)) {

			$agent = $this->getid("agent", $data->referance_agent);

			if ($agent[0]->agent_name) {

				$agent = $agent[0]->id;
			}
		}
		// 		$agent = '';
		// 	}

		// }

		// if ($owner_all == 'true' or $owner_own == 'true') {
		// 	if ($data->owner_id) {

		// 		$owner = $this->getid('owner', $data->owner_id);



		// 		// $agentOwner .= "<a href='owner/owner-view/?view={$owner[0]->id}' data-toggle='tooltip' data-placement='right' title='{$owner[0]->contact}'>{$owner[0]->name}</a>";

		// 	}
		// }


		if ($type == 'project') {
			$res = $this->getQuery("select  id from property_listing where project_id='{$pro}'");
			if (!empty($res)) {
				$t_count = count($res);
			} else {
				$t_count = 0;
			}
		}
		$pt = '';
		// 		$pt .= '
//  <li><a href="' . $link . '" target="_blank">
//                     <i class="fa fa-external-link"></i> Direct Link
//                 </a></li>';

		$pt .= '  <li><a href="' . $link . '" target="_blank">
                    <i class="fa fa-external-link"></i> Direct Link
                </a></li>';


		// $pt .= ' <li><a href="' . $landing . '" target="_blank">
		//             <i class="fa fa-map-marker"></i> Landing Page
		//         </a></li>';



		// Generate Share & Action buttons
		$value = '
    <div class="btn-group btn-group-sm" style="display: flex; gap: 8px;">
        <!-- Share Dropdown -->
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" 
                    aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-share-alt"></i> <span class="sr-only">Share</span>
            </button>
            <ul class="dropdown-menu ">
                <li class="dropdown-header"><i class="fa fa-share"></i> Share Options</li>
               ' . $pt . '
                <li role="separator" class="divider"></li>
                <li>
  <a href="javascript:void(0);" class="send-whatsapp" data-property-id="'.$pro.'">
    <i class="fa fa-whatsapp text-success"></i> Send WA Message
  </a>
</li>

                <li><a href="https://api.whatsapp.com/send?text=' . urlencode($link) . '" target="_blank">
                    <i class="fa fa-whatsapp"></i> WhatsApp
                </a></li>
                <li><a href="mailto:?subject=' . urlencode("Check this $type: $title") . '&body=' . urlencode($link) . '">
                    <i class="fa fa-envelope-o"></i> Email
                </a></li>
                <li><a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($link) . '" target="_blank">
                    <i class="fa fa-facebook"></i> Facebook
                </a></li>
            </ul>
        </div>

        <!-- Actions Dropdown -->
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" 
                    aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog"></i> <span class="sr-only">Actions</span>
            </button>
            <ul class="dropdown-menu ">
                <li><a href="' . ($type === 'project' ? 'project/project-view/' : 'property/property-info/') . '?nav=' . ($type === 'project' ? 'projects' : 'property') . '&view=' . $id . '">
                    <i class="fa fa-eye"></i> View Details
                </a></li>';

		if ($owner_all == 'true' && $owner_own == 'true' && !empty($data->owner_id) && $type == 'property') {
			$value .= '
					<li><a href="owner/owner-view/?view=' . $data->owner_id . '">
						<i class="fa fa-eye"></i> View Owner Details
					</a></li>';
		}
		// Type-specific actions
		if ($type === 'project') {
			if ($canEdit === 'true') {
				$value .= '<li><a href="project/project-edit/?nav=projects&edit=' . $id . '">
                        <i class="fa fa-edit text-warning"></i> Edit Project
                      </a></li>';
			}

			if ($canDelete === 'true') {
				if ($t_count > 0) {

					// Project has attached properties, cannot delete
					$value .= '<li>
        <a href="javascript:void(0)" onclick="alert(\'This project cannot be deleted because it has ' . $t_count . ' attached properties.\')">
            <i class="fa fa-ban text-warning"></i> Delete Project
        </a>
    </li>';
				} else {
					// Project has no attached properties, allow delete
					$value .= '<li>
        <a href="project/project-list?delete=' . $id . '&pro_name=' . urlencode($title) . '" 
           onclick="return confirm(\'Are you sure you want to delete this project?\')">
            <i class="fa fa-trash text-danger"></i> Delete Project
        </a>
    </li>';
				}
			}
			$value .= '
				<li><a href="javascript:void(0)" class="add-mapping-btn" data-project-id="' . $id . '">
                                       <i class="fa fa-link"></i> Map External IDs

                      </a></li>';
		} else {
			if ($data->status == '1' && $canBook === 'true') {
				$value .= '<li><a href="property/book-property/?id=' . $id . '">
                        <i class="fa fa-shopping-cart text-success"></i> Book Property
                      </a></li>';
			}

			if ($canEdit === 'true') {
				$value .= '<li><a href="property/property-edit/?nav=property&edit=' . $id . '">
                        <i class="fa fa-edit text-warning"></i> Edit Property
                      </a></li>';
			}

			if ($canDelete === 'true') {
				$value .= '<li><a href="' . $uri . '?delete=' . $id . '&pro_name=' . urlencode($title) . '" 
                        onclick="return confirm(\'Are you sure you want to delete this property?\')">
                        <i class="fa fa-trash text-danger"></i> Delete Property
                      </a></li>';
			}
		}

		$value .= '</ul>
        </div>
    </div>';

		return $value;
	}
	function shareEntity1($id, $type)
	{

		global $edit, $delete, $t_count, $uri;

		// Determine table and column names based on type
		$table = ($type === 'property') ? 'property_listing' : 'project';
		$nameColumn = ($type === 'property') ? 'property_title' : 'pro_name';

		// Fetch data from the database
		$data = $this->getQuery("SELECT p.*,s.slug,s.canonical_url FROM $table p left join seo_data s on p.id= s.related_id WHERE p.id = '{$id}' and s.type = '{$type}'");

		if (empty($data)) {
			return '<span class="text-danger">Invalid ID</span>';
		}
		$data = $data[0];
		$title = $data->$nameColumn;
		$link = $data->canonical_url;
		$pro = $data->id;

		if ($type == 'project') {
			$res = $this->getQuery("select  id from property_listing where project_id='{$pro}'");
			if (!empty($res)) {
				$t_count = count($res);
			} else {
				$t_count = 0;
			}
		}
		// Generate URLs
		$basePath = WEBSITE . (($type === 'property') ? 'pro' : 'project');

		$landing = $basePath . '/' . $data[0]->Slug;

		// Generate Share & CRUD buttons
		$value = '
    <div class="btn-group" style="display: flex; gap: 5px;">
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" data-container="body" aria-expanded="false">
                <i class="fa fa-share-alt"></i> <span class="share-text">Share</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="' . $link . '" target="_blank"><i class="fa fa-link"></i> Open Link</a></li>
                <li><a href="' . $landing . '" target="_blank"><i class="fa fa-link"></i> Open Landing Page</a></li>
                <li><a href="https://api.whatsapp.com/send?text=' . $link . '" target="_blank"><i class="fa fa-whatsapp"></i> Share on WhatsApp</a></li>
                <li><a href="mailto:?subject=Check this ' . $type . '&body=' . $link . '"><i class="fa fa-envelope"></i> Share via Email</a></li>
                <li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $link . '" target="_blank"><i class="fa fa-facebook"></i> Share on Facebook</a></li>
            </ul>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" data-container="body" aria-expanded="false">
                <i class="fa fa-wrench"></i> <span class="share-text">Actions</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">';
		if (strcasecmp($type, 'project') == 0) {
			$value .= '<li><a href="project/project-view/?nav=projects&view=' . $id . '" title="View"><i class="fa fa-eye"></i> View</a></li>';


			if (@$edit == 'true') {
				$value .= '<li><a href="project/project-edit/?nav=projects&edit=' . $id . '" title="Edit"><i class="fa fa-edit"></i> Edit</a></li>';
			}

			if (@$delete == 'true' && $t_count == '0') {
				$value .= '<li><a href="' . $uri . '?delete=' . $id . '&pro_name=' . urlencode($title) . '" onclick="return confirm(\'Are you sure you want to delete this?\');"><i class="fa fa-trash"></i> Delete</a></li>';
			}
		} elseif (strcasecmp($type, 'property') == 0) {
			if ($data[0]->status == '1') {

				$value .= '<li><a href="property/book-property/?id=' . $id . '"><i class="fa fa-shopping-cart"></i> Book</a></li>';
			}
			$value .= '<li><a href="property/property-info/?nav=projects&view=' . $id . '" title="View"><i class="fa fa-eye"></i> View</a></li>';

			if (@$edit == 'true') {
				$value .= '<li><a href="property/property-edit/?nav=property&edit=' . $id . '" title="Edit"><i class="fa fa-edit"></i> Edit</a></li>';
			}

			if (@$delete == 'true' && $t_count == '0') {
				$value .= '<li><a href="' . $uri . '?delete=' . $id . '&pro_name=' . urlencode($title) . '" onclick="return confirm(\'Are you sure you want to delete this?\');"><i class="fa fa-trash"></i> Delete</a></li>';
			}
		}
		$value .= '</ul>  
        </div>
    </div>';

		return $value;
	}

	function verifyCsrf($csrf)
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$session_csrf = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : '';

		if (!empty($csrf) && hash_equals($session_csrf, $csrf)) {
			return true;
		} else {
			return false;
		}
	}

	function insertLeadActivityLog($lead_id, $user_id, $action_type, $action_details, $location = null, $photo = null)
	{
		global $getuserdata; // assuming $boj is your database class

		// Default location if not provided
		if (!$location) {
			$location = ''; // IP address
		}
		if (!$photo) {
			$photo = '';
		}

		if($user_id=='crm'){
			$user_id=$getuserdata->id;
		}
		$lead_id = intval($lead_id);
		$user_id = intval($user_id);
		$action_type = addslashes($action_type);
		$action_details = addslashes($action_details);
		$location = addslashes($location);

		$sql = "INSERT INTO lead_activity_log 
            (lead_id, user_id, photo, action_type, action_details, location) 
            VALUES 
            ($lead_id, $user_id, '$photo', '$action_type', '$action_details', '$location')";

		return  $this->mysqli->query($sql);
	}
	
	function matched_leads_by_property_id($property_id)
	{
		// Get property details
		$sql = "SELECT pl.*, pt.type as categorytype,c.city as city_name,l.location as location_name,sl.sub_location as sub_location_name FROM property_listing  pl left join city c on pl.city=c.id  left join locations l on pl.location=l.id left join sub_location sl on pl.sub_location=sl.id  left join  property_type pt on pt.id=pl.category WHERE pl.id = " . intval($property_id);

		$data = $this->mysqli->query($sql);
		$prop = $data->fetch_object();

		if (!$prop) {
			return array('matched' => 0, 'leads' => [], 'q' => '');
		}

		// Build search conditions
		$conditions = array();
		$price = $prop->price;
		// Budget condition - property price should be within lead's budget
		$conditions[] = "(
    (client_budget_min IS NULL AND client_budget_max IS NULL) 
    OR
    (client_budget_min <= '$price' AND (client_budget_max >= '$price' OR client_budget_max IS NULL))
    OR
    (client_budget_max >= '$price' AND (client_budget_min <= '$price' OR client_budget_min IS NULL))
)";


		// Contract/Availability condition
		if ($prop->available_for) {
			$conditions[] = "(contract = '{$prop->available_for}' OR contract IS NULL OR contract = '')";

		}

		// Property type condition
		if ($prop->property_type) {
			$type = $prop->property_type;
			$conditions[] = " (property_type LIKE '%$type%' OR property_type IS NULL OR property_type = '') ";
		}

		// Location condition
		if ($prop->location) {
			$location = $prop->location;
			$conditions[] = " (required_property_location LIKE '%$location%' OR required_property_location IS NULL OR required_property_location = '') ";
		}

		if ($prop->project_id) {
			$conditions[] = " (project = '{$prop->project_id}' OR project IS NULL OR project = '') ";
		}

		if ($prop->category) {

			$conditions[] = " (leads.category LIKE '%{$prop->categorytype}%' OR leads.category IS NULL OR leads.category = '') ";
		}
		if ($prop->furnished_status) {
			$conditions[] = " (furnished_status LIKE '%{$prop->furnished_status}%' OR furnished_status IS NULL OR furnished_status = '') ";
		}

		if ($prop->size) {
			$conditions[] = " (property_size_min <= '{$prop->size}' AND (property_size_max >= '{$prop->size}' OR property_size_max IS NULL)) OR property_size_min IS NULL OR property_size_min = '') ";
		}

		// Build WHERE clause
		$where = implode(" AND ", $conditions);

		if (!empty($where)) {
			$where = "(" . $where . " AND ls.status_type NOT IN ('not-interested','deal-done')";
		} else {
			$where = "ls.status_type NOT IN ('not-interested','deal-done')";
		}

		// Get matching leads
		$sql = "SELECT leads.id, lead_name, lead_contact FROM leads left join lead_status ls on ls.name = leads.lead_status and ls.category='lead_status' WHERE $where";
		// echo $sql;
		// die;
		$result = $this->mysqli->query($sql);
		if (!$result) {
			return array('matched' => 0, 'leads' => [], 'q' => '', 'error' => $this->mysqli->error);
		}

		$leads = [];
		while ($row = $result->fetch_object()) {
			$leads[] = [
				'id' => $row->id,
				'name' => $row->lead_name,
				'contact' => $row->lead_contact
			];
		}

		return array(
			'matched' => count($leads),
			'leads' => $leads,
			'q' => $where,
			'error' => $this->mysqli->error
		);
	}


	
		function fetchUser()
	{
		global $getuserdata;

		// ROOT OR ROLE 1 → SEE ALL USERS EXCEPT SELF
		if ($getuserdata->roleid == '1' || $getuserdata->usertype == 'root') {

			$sql = "
            SELECT id, name, usertype AS role_name,status
            FROM user
            WHERE id != '{$getuserdata->id}' and status='active' 
            ORDER BY roleid ASC
        ";

		} else {

			$sql = "
            WITH RECURSIVE staff AS (
                SELECT id, name, supervisor_id, roleid, usertype, 0 AS depth,status
                FROM user WHERE id = '{$getuserdata->id}'

                UNION ALL

                SELECT u.id, u.name, u.supervisor_id, u.roleid, u.usertype, s.depth + 1,status
                FROM user u 
                JOIN staff s ON u.supervisor_id = s.id
            )
            SELECT s.id, s.name, s.roleid, s.usertype AS role_name,status, s.depth, us.name AS supervisor_name
            FROM staff s
            LEFT JOIN user us ON s.supervisor_id = us.id
            WHERE s.id != '{$getuserdata->id}' and s.status='active' 
            ORDER BY s.roleid ASC
        ";
		}

		return $this->getQuery($sql);
	}
}

if (isset($_POST['login'])) {
	$obj = new itways();
	$obj->login();
}

if (isset($_GET['act']) == 'logout') {
	$obj = new itways();
	$obj->logout();
}



if (isset($_POST['setting-add'])) {
	$obj = new itways();
	$obj->setting();
}
// if(isset($_POST['otp-log']))
// {
// 	$obj = new itways();
// 	$obj->otp_log ();
// }


?>