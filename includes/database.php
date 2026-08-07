<?php

class db
{
	protected $mysqli;

	// Constructor
	public function __construct()
	{
		$this->mysqli = new mysqli(HOSTS, USERNAME, PASSWORD, DATABASE);

		// Check for connection errors
		if ($this->mysqli->connect_error) {
			die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
		}
		$this->mysqli->set_charset("utf8mb4");

	}

	public function __destruct()
	{
		$this->mysqli->close();
	}

	function sanitize($data)
	{
		if ($this->isJson($data)) {
			return trim($data);
		}

		$data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);
		$data = trim($data);
		$data = stripslashes($data);
		$data = $this->mysqli->real_escape_string($data);
		//  $data = htmlspecialchars($data);
		return $data;
	}

	function isJson($string)
	{
		if (!is_string($string)) {
			return false;
		}

		$result = json_decode($string, true);

		return json_last_error() === JSON_ERROR_NONE
			&& (is_array($result) || is_object($result));
	}

	function int($data)
	{
		$data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
		//$data = preg_replace("/[^0-9]/", "", $data );

		return $data;
	}

	function filter($var, $type)
	{
		$flags = NULL;
		switch ($type) {
			case 'url':
				$filter = FILTER_SANITIZE_URL;
				break;
			case 'int':
				$filter = FILTER_SANITIZE_NUMBER_INT;
				break;
			case 'float':
				$filter = FILTER_SANITIZE_NUMBER_FLOAT;
				$flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
				break;
			case 'email':
				$var = substr($var, 0, 254);
				$filter = FILTER_SANITIZE_EMAIL;
				break;
			case 'string':
				$var = filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);
				$var = trim($var);
				$var = stripslashes($var);
				$var = $this->mysqli->real_escape_string($var);
				break;
			default:
				$filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
				$flags = FILTER_FLAG_NO_ENCODE_QUOTES;
				break;
		}
		$output = filter_var($var, $filter, $flags);
		return ($output);
	}




	function date()
	{
		date_default_timezone_set("Asia/Kolkata");
		return $date = date("d/m/Y ");
	}

	function dateFormat($d)
	{
		date_default_timezone_set("Asia/Kolkata");
		return $date = date("d-m-Y", strtotime($d));
	}


	function datetime()
	{
		date_default_timezone_set("Asia/Kolkata");
		return $date = date("Y-m-d h:i:sa");
	}





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





	function insert_ignore($table, $data)
	{
		$data = array_filter($data);
		//	echo"<pre>";

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





	function insert_query($table, $data)
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
		$query = "INSERT INTO $table ($key) 
        values
        ('$values')";
		// 		print_r($query);
		// 		die;
		return $this->mysqli->query($query);
	}


	function csrf_insert($table, $data, $csrf)
	{
		@session_start();
		if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] != $csrf) {
			return false;
			// die(" CSRF token validation  failed.");

		}
		$data = array_filter($data, function ($value) {
			return !empty($value) || $value === 0;
		});


		// 		print_r($data);
		// 		echo "</pre>";
		// 		die;
		$values = "";
		$key = array_keys($data);
		$key = implode(",", $key);

		foreach ($data as $k => $v) {
			$values_array[] = "'" . $this->sanitize($v) . "'";
		}
		$values = implode(", ", $values_array);

		$query = "INSERT INTO $table ($key) 
        values
        ($values)";
		// print_r($query);
		// die;
		if (strcasecmp($_SESSION['csrf'], $csrf) == 0) {
			if ($this->mysqli->query($query)) {
				$id = $this->mysqli->insert_id;
				return $id;
			}
			;
		}
		return false;
	}
	function csrfProInsert($table, $data, $csrf)
	{
		@session_start();
		if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] != $csrf) {
			return false;
			// die(" CSRF token validation  failed.");

		}
		$data = array_filter($data, function ($value) {
			return !empty($value) || $value === 0;
		});


		// 		print_r($data);
		// 		echo "</pre>";
		// 		die;
		$values = "";
		$key = array_keys($data);
		$key = implode(",", $key);
		$values = implode("','", $data);

		$query = "INSERT INTO $table ($key) 
        values
        ('$values')";

		if ($_SESSION['csrf'] == $csrf) {

			$result = $this->mysqli->query($query);
		}
		return $result;
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




	/**
	 * Resize image while maintaining aspect ratio
	 */
	function resizeImage($image, $origWidth, $origHeight, $maxWidth, $maxHeight)
	{
		if ($origWidth <= $maxWidth && $origHeight <= $maxHeight) {
			return $image; // No resizing needed
		}
		// Maintain aspect ratio
		$ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
		$newWidth = (int) ($origWidth * $ratio);
		$newHeight = (int) ($origHeight * $ratio);

		// Create new blank image with new size
		$resizedImage = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

		return $resizedImage;
	}


	function insert_qry($table, $data)
	{
		$data = array_filter($data);
		//print_r($data);
		//die;
		$values = "";
		$key = array_keys($data);
		$key = implode(",", $key);
		foreach ($data as $value) {
			$values .= $value . "','";
		}
		$values = trim($values, ",'");
		$query = "INSERT INTO $table ($key) 
        values
        ('$values')";
		// print_r($query);
		// die;
		return $this->mysqli->query($query);
	}



	//Update leads 
	//		Set lead_name = '$_POST[lead_name]',  
	//		lead_contact = '$_POST[lead_contact]'
	//		Where id = $lead_id

	function update_query($table, $data, $where)
	{

		//	$data = array_filter($data);
		//print_r($data);
		//die;
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
		$result = $this->mysqli->query($query);
		return $result;
	}
// 	function csrf_update($table, $data, $where, $csrf)
// 	{
// 		@session_start();


// 		if ($_SESSION['csrf'] !== $csrf) {

// 			return false;
// 			// die(" CSRF  validation failled.");

// 		}

// 		$data = array_filter($data, function ($value) {
// 			return $value !== false && $value !== null && $value !== '';
// 		});

// 		$values = "";
// 		$key = array_keys($data);
// 		//  $key = implode(",", $key);
// 		$i = 0;
// 		$trail = "";
// 		foreach ($data as $value) {
// 			$trail .= $key[$i] . " = '" . $this->sanitize($value) . "',";

// 			$i++;
// 		}
// 		$trail = trim($trail, ",");
// 		$query = 'UPDATE ' . $table . ' SET ' . $trail . ' WHERE ' . $where . ' ';

// 		// $values = trim($values, ",'");
// 		//  values
// 		// ('$values')";
// 		// print_r($query);
// 		// die;
// 		@session_start();
// 		if ($_SESSION['csrf'] === $csrf) {
// 			$result = $this->mysqli->query($query);
// 		} else {
// 			$result = false;
// 		}

// 		return $result;
// 	}
	
	function csrf_update($table, $data, $where, $csrf)
{
    @session_start();

    // ✅ 1. CSRF check
    if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $csrf) {
        return false;
    }

    // ✅ 2. Filter out only NULL and FALSE (keep empty string '')
    $data = array_filter($data, function ($value) {
        return $value !== false && $value !== null;
    });

    // ✅ 3. Fetch table column info (type detection)
    $columns = [];
    $result = $this->mysqli->query("DESCRIBE {$table}");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[$row['Field']] = strtolower($row['Type']);
        }
    }

    // ✅ 4. Build SET part of query dynamically
    $setParts = [];
    foreach ($data as $key => $value) {
        $safeValue = $this->sanitize($value);

        // ✅ Auto-handle numeric fields (convert '' -> 0)
        if (isset($columns[$key])) {
            $colType = $columns[$key];
            if (preg_match('/int|decimal|float|double|tinyint|bigint/', $colType)) {
                if ($safeValue === '' || $safeValue === null) {
                    $safeValue = 0;
                }
            }

            // ✅ Auto-handle date fields (convert '' -> NULL)
            elseif (preg_match('/date|datetime|timestamp/', $colType)) {
                if ($safeValue === '' || $safeValue === null) {
                    $setParts[] = "`{$key}` = NULL";
                    continue;
                }
            }
        }

        // ✅ Default: escape and quote string values
        $escapedValue = $this->mysqli->real_escape_string($safeValue);
        $setParts[] = "`{$key}` = '{$escapedValue}'";
    }

    // ✅ 5. If no valid fields, return false
    if (empty($setParts)) {
        return false;
    }

    $setClause = implode(', ', $setParts);
    $query = "UPDATE `{$table}` SET {$setClause} WHERE {$where}";

    // ✅ Debug
    // echo $query; die;

    // ✅ 6. Execute safely
    return $this->mysqli->query($query);
}
	function csrfProUpdate($table, $data, $where, $csrf)
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
			$trail .= $key[$i] . " = '" . $value . "',";

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
	function uploadFiles($files, $path = '/../../uploads/', $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'], $max_size = 5 * 1024 * 1024, $maxWidth = 1200, $maxHeight = 1200)
	{
		ini_set('memory_limit', '180M'); // Increase memory limit
		$uploads_dir = str_replace('\\', '/', __DIR__);
		$uploads_dir = realpath($uploads_dir . $path);

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
				$responses[] = ['file' => $name, 'status' => 'error', 'message' => 'Upload error.'];
				continue;
			}

			$file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$file_tmp = $files['tmp_name'][$key];

			// Validate file extension
			if (!in_array($file_ext, $allowed_extensions)) {
				$responses[] = ['file' => $name, 'status' => 'error', 'message' => 'Invalid file type.'];
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
				$responses[] = ['file' => $name, 'status' => 'error', 'message' => 'Invalid file type.'];
				continue;
			}

			// Validate file size
			if ($files['size'][$key] > $max_size) {
				$responses[] = ['file' => $name, 'status' => 'error', 'message' => 'File size exceeds the maximum limit of 10MB.'];
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

					$responses[] = ['file' => $new_file_name, 'status' => 'success'];
				} else {
					$responses[] = ['file' => $name, 'status' => 'error', 'message' => 'Failed to process image.'];
				}
			} else {
				// Move non-image files
				if (move_uploaded_file($file_tmp, $target_path)) {
					$responses[] = ['file' => $new_file_name, 'status' => 'success'];
				} else {
					$responses[] = ['file' => $name, 'status' => 'error', 'message' => 'Failed to upload file.'];
				}
			}
			$i++;
		}

		$gallery_image = array_filter($responses, function ($response) {
			return $response['status'] === 'success';
		});

		$gallery_image = array_column($gallery_image, 'file');

		return $galImg ? json_encode($gallery_image) : $gallery_image[0];
	}
	function update_query_my($query)
	{

		$data = $this->mysqli->query($query);

		if ($data) {

			return true;
		} else {

			return $this->mysqli->error;
		}
	}

	function update_id($table, $data, $where)
	{
		$data = array_filter($data, function ($value) {
			return $value !== false && $value !== null && $value !== '';
		});
		//print_r($data);
		//die;
		$values = "";
		$key = array_keys($data);
		//$key = implode(",", $key);
		$i = 0;
		$trail = "";
		foreach ($data as $value) {
			$trail .= $key[$i] . " = '" . $this->sanitize($value) . "',";

			$i++;
		}
		$trail = trim($trail, ",");
		$query = 'UPDATE ' . $table . ' SET ' . $trail . ' WHERE id= ' . $where . ' ';

		// $values = trim($values, ",'");
		//values
		//('$values')";
		//print_r($query);
		//die;
		return $this->mysqli->query($query);
	}



	function update_qry($table, $data, $where)
	{
		$data = array_filter($data);
		//print_r($data);
		//die;
		$values = "";
		$key = array_keys($data);
		//$key = implode(",", $key);
		$i = 0;
		$trail = "";
		foreach ($data as $value) {
			$trail .= $key[$i] . " = '" . $value . "',";

			$i++;
		}
		$trail = trim($trail, ",");
		$query = 'UPDATE ' . $table . ' SET ' . $trail . ' WHERE ' . $where . ' ';

		// $values = trim($values, ",'");
		//values
		//('$values')";
		// print_r($query);
		// die;
		return $this->mysqli->query($query);
	}


	function mask($cc, $fillChar = '#')
	{

		$last4 = substr(str_replace(['-', ' '], '', $cc), -4);
		return str_pad($last4, 10, $fillChar, STR_PAD_LEFT);
	}




	function msg_set($data, $nav)
	{

		if ($data) {
			$_SESSION['suc'] = 'Updated Successfully';
		} else {
			$_SESSION['fal'] = ' Something went wrong! ' . $this->mysqli->error;
		}

		header("location: ?nav=" . $nav);
		die;
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
			$key = hash_pbkdf2("sha256", $password, $keysalt, 20000, 32, true);
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


	function image_check($name, $size, $type)
	{



		$sanitize = $this->sanitize($name);
		if ($type == 'image/jpg' or $type == 'image/jpeg' or $type == 'image/png') {
			if ($size <= 1000000) {
				return $sanitize;
			} else {
				session_start();
				$_SESSION['fal'] = 'File must be less than or equal to 1mb';
			}
		} else {
			session_start();
			$_SESSION['fal'] = 'File type must be jpeg/jpg/png';
		}
		header("location:?edit=" . $_GET['edit']);
		die;
	}



	function image_check_values($name, $size, $type)
	{



		$sanitize = $this->sanitize($name);
		if ($type == 'image/jpg' or $type == 'image/jpeg' or $type == 'image/png') {
			if ($size <= 1000000) {
				return $sanitize;
			} else {

				return 1; //for wrong size
				//session_start();
				//$_SESSION['fal']='File must be less than or equal to 1mb';
			}
		} else {

			return 2; //for wrong format
			//session_start();
			//$_SESSION['fal']='File type must be jpeg/jpg/png';
		}
		//header("location:?edit=".$_GET['edit']);
		//die;
	}

	// function user_details()
	// {

	// 	//-------------- ip --------------------------------

	// 	$ip = $_SERVER['REMOTE_ADDR'];

	// 	$json = file_get_contents("https://ipwhois.app/json/$ip");
	// 	$json = json_decode($json, true);
	// 	$country_name = $json['country'];
	// 	$country_code = $json['country_code'];
	// 	$country = $country_name . " [" . $country_code . "]";
	// 	$country_flag = $json['country_flag'];
	// 	$region = $json['region'];
	// 	$city = $json['city'];
	// 	$lat = $json['latitude'];
	// 	$lon = $json['longitude'];
	// 	$location = $lat . "," . $lon;
	// 	$isp = $json['isp'];



	// 	//------------------------browser os--------------------------

	// 	function getBrowser()
	// 	{
	// 		$u_agent = $_SERVER['HTTP_USER_AGENT'];
	// 		$bname = 'Unknown';
	// 		$platform = 'Unknown';
	// 		$version = "";

	// 		//First get the platform?
	// 		if (preg_match('/linux/i', $u_agent)) {
	// 			$platform = 'linux';
	// 		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	// 			$platform = 'mac';
	// 		} elseif (preg_match('/windows|win32/i', $u_agent)) {
	// 			$platform = 'windows';
	// 		}

	// 		// Next get the name of the useragent yes seperately and for good reason
	// 		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
	// 			$bname = 'Internet Explorer';
	// 			$ub = "MSIE";
	// 		} elseif (preg_match('/Firefox/i', $u_agent)) {
	// 			$bname = 'Mozilla Firefox';
	// 			$ub = "Firefox";
	// 		} elseif (preg_match('/OPR/i', $u_agent)) {
	// 			$bname = 'Opera';
	// 			$ub = "Opera";
	// 		} elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
	// 			$bname = 'Google Chrome';
	// 			$ub = "Chrome";
	// 		} elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
	// 			$bname = 'Apple Safari';
	// 			$ub = "Safari";
	// 		} elseif (preg_match('/Netscape/i', $u_agent)) {
	// 			$bname = 'Netscape';
	// 			$ub = "Netscape";
	// 		} elseif (preg_match('/Edge/i', $u_agent)) {
	// 			$bname = 'Edge';
	// 			$ub = "Edge";
	// 		} elseif (preg_match('/Trident/i', $u_agent)) {
	// 			$bname = 'Internet Explorer';
	// 			$ub = "MSIE";
	// 		}

	// 		// finally get the correct version number
	// 		$known = array('Version', $ub, 'other');
	// 		$pattern = '#(?<browser>' . join('|', $known) .
	// 			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	// 		if (!preg_match_all($pattern, $u_agent, $matches)) {
	// 			// we have no matching number just continue
	// 		}
	// 		// see how many we have
	// 		$i = count($matches['browser']);
	// 		if ($i != 1) {
	// 			//we will have two since we are not using 'other' argument yet
	// 			//see if version is before or after the name
	// 			if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
	// 				$version = $matches['version'][0];
	// 			} else {
	// 				$version = $matches['version'][1];
	// 			}
	// 		} else {
	// 			$version = $matches['version'][0];
	// 		}

	// 		// check if we have a number
	// 		if ($version == null || $version == "") {
	// 			$version = "?";
	// 		}

	// 		return array(
	// 			'userAgent' => $u_agent,
	// 			'name' => $bname,
	// 			'version' => $version,
	// 			'platform' => $platform,
	// 			'pattern' => $pattern
	// 		);
	// 	}

	// }
	function user_detail()
	{
		//-------------- Get IP & Location Details --------------
		$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
		$country_name = $country_code = $country_flag = $region = $city = $lat = $lon = $location = $isp = 'Unknown';

		$json = @file_get_contents("https://ipwhois.app/json/$ip");
		if ($json) {
			$json = json_decode($json, true);
			if (isset($json['country'])) {
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
			}
		}

		// ------------------- Get Browser & OS -------------------
		$browserData = $this->getBrowser();

		// ------------------- Return User Details -------------------
		return array(
			'ip' => $ip,
			'country' => $country ?? 'Unknown',
			'country_flag' => $country_flag ?? 'Unknown',
			'region' => $region ?? 'Unknown',
			'city' => $city ?? 'Unknown',
			'location' => $location ?? 'Unknown',
			'isp' => $isp ?? 'Unknown',
			'browser' => $browserData['name'] . " " . $browserData['version'],
			'platform' => $browserData['platform'],
			'device' => $this->detectDevice(),
			'userAgent' => $browserData['userAgent']
		);
	}

	// ------------------- Get Browser & OS Function -------------------
	function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "?";
		$ub = "Unknown";

		// Get OS
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'Linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'Mac OS';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'Windows';
		}

		// Get Browser
		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif (preg_match('/Firefox/i', $u_agent)) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif (preg_match('/OPR|Opera/i', $u_agent)) {
			$bname = 'Opera';
			$ub = "Opera";
		} elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif (preg_match('/Edge/i', $u_agent)) {
			$bname = 'Microsoft Edge';
			$ub = "Edge";
		} elseif (preg_match('/Trident/i', $u_agent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif (preg_match('/Netscape/i', $u_agent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		// Get Browser Version
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

		if (preg_match_all($pattern, $u_agent, $matches)) {
			$i = count($matches['browser']);
			if ($i > 0) {
				$version = isset($matches['version'][1]) ? $matches['version'][1] : $matches['version'][0];
			}
		}

		return array(
			'userAgent' => $u_agent,
			'name' => $bname,
			'version' => $version,
			'platform' => $platform
		);
	}

	// ------------------- Detect Device Type -------------------
	function detectDevice()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

		if (preg_match('/mobile/i', $u_agent)) {
			return 'Mobile';
		} elseif (preg_match('/tablet/i', $u_agent)) {
			return 'Tablet';
		} else {
			return 'Desktop';
		}
	}
}
