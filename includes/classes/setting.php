<?php

class setting extends db
{


    function add_company_info()
    {

        date_default_timezone_set("Asia/Kolkata");

        $array = array(
            'name' => $_POST['name'],
            'slogan' => $_POST['slogan'],
            'address_line_1' => $_POST['address_line_1'],
            'address_line_2' => $_POST['address_line_2'],
            'phone' => $_POST['phone'],
            'alt_phone' => $_POST['alt_phone'],
            'mail' => $_POST['mail'],
            'city'=>$_POST['city'],
            'state'=>$_POST['state'],
            'pin'=>$_POST['pin_code'],
            'alt_mail' => $_POST['alt_mail'],
            'gst_no' => $_POST['gst_no'],
            'uploader' => $_POST['uploader'],

        );
        $csrf = $_POST['csrf_token'];
        $data = $this->csrf_insert('company', $array, $csrf);
        $last_id = $this->mysqli->insert_id;
        date_default_timezone_set("Asia/Kolkata");
        $today = date("Y-m-d h:i:sa");

        $act = array(
            'user_id' => $_POST['user_id'],
            'action' => " Company " . $_POST['name'] . " (" . $last_id . ") Added by $_POST[uploader]",


        );
        $data = $this->insert_userAct('user_actvity', $act, $csrf);







        $this->msg_set($data, 'setting');

    }



    function update_company_info()
    {

        date_default_timezone_set("Asia/Kolkata");

        $array = array(
            'name' => $_POST['name'],
            'slogan' => $_POST['slogan'],
            'address_line_1' => $_POST['address_line_1'],
            'address_line_2' => $_POST['address_line_2'],
            'phone' => $_POST['phone'],
            'alt_phone' => $_POST['alt_phone'],
            'city'=>$_POST['city'],
            'state'=>$_POST['state'],
            'pin'=>$_POST['pin_code'],
            'mail' => $_POST['mail'],
            'alt_mail' => $_POST['alt_mail'],
            'gst_no' => $_POST['gst_no'],
            'uploader' => $_POST['uploader'],

        );

        $csrf = $_POST['csrf_token'];

        $where = "id = " . $_POST['edit'];
        //  echo $where;
        //  echo"<pre/>";
        // print_r($array);
        // die;
        $data = $this->csrf_update('company', $array, $where, $csrf);





        date_default_timezone_set("Asia/Kolkata");
        $today = date("Y-m-d h:i:sa");

        $act = array(
            'user_id' => $_POST['user_id'],
            'action' => " Company " . $_POST['name'] . " (" . $_POST['edit'] . ") name has been Changed by $_POST[uploader]",


        );
        $this->insert_userAct('user_actvity', $act, $csrf);
        // $section = $_GET['api'];
        $this->msg_set($data, 'setting');

    }


    function logo_style()
    {
        if (isset($_POST['styler'])) {

            $array = array(
                'logo_style' => $_POST['styler']
            );
            $where = "id = 1";// . $_POST['edit'] ;
            $csrf = $_POST['csrf_token'];
            $data = $this->csrf_update('company', $array, $where, $csrf);
            $this->msg_set($data, 'setting');


        }

    }


    // function logo()
    // {
    //     if (!isset($_FILES["logo"])) {
    //         die("There is no file to upload.");
    //     }

    //     $filepath = $_FILES['logo']['tmp_name'];
    //     $fileSize = filesize($filepath);
    //     $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    //     $filetype = finfo_file($fileinfo, $filepath);

    //     if ($fileSize === 0) {
    //         die("The file is empty.");
    //     }

    //     if ($fileSize > 3145728) { // 3 MB (1 byte * 1024 * 1024 * 3 (for 3 MB))
    //         die("The file is too large");
    //     }

    //     $allowedTypes = [
    //         'image/png' => 'png'
    //     ];

    //     if (!in_array($filetype, array_keys($allowedTypes))) {
    //         die("File not allowed.");
    //     }

    //     $filename = basename($filepath); // I'm using the original name here, but you can also change the name of the file here
    //     $extension = $allowedTypes[$filetype];
    //     $targetDirectory = "../../uploads/logo/"; // __DIR__ is the directory of the current PHP file

    //     $newFilepath = $targetDirectory . "/" . "logo." . $extension;
    //     // date_default_timezone_set("Asia/Kolkata");
    //     // $today=  date("Y-m-d h:i:sa");

    //     // $act= array(
    //     //     'user_id' =>$_POST['user_id'],
    //     //     'action' =>" Logo  Uploaded",
    //     //     

    //     // );
    //     // $data = $this->insert_query('user_actvity', $act);
    //     if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
    //         die("Can't move file.");
    //     }
    //     unlink($filepath); // Delete the temp file




    //     session_start();



    //     $_SESSION['suc'] = "File uploaded successfully :)";


    // 


    function logo()
    {
        session_start();
        if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $_POST['csrf_token']) {
            return $_SESSION['fal'] = "Something Went Wrong !";
        }
        if (empty($_FILES['logo']['tmp_name'])) {
            return $_SESSION['fal'] = "There is no file to upload.";

        }

        $filepath = $_FILES['logo']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);

        if ($fileSize === 0) {
            return $_SESSION['fal'] = "The file is empty.";
        }

        if ($fileSize > 3145728) { // 3 MB (1 byte * 1024 * 1024 * 3 (for 3 MB))
            return $_SESSION['fal'] = "The file is too large";
        }

        $allowedTypes = [
            'image/png' => 'png'
        ];

        if (!in_array($filetype, array_keys($allowedTypes))) {
            return $_SESSION['fal'] = "File not allowed.";
        }

        $filename = basename($filepath); // I'm using the original name here, but you can also change the name of the file here
        $extension = $allowedTypes[$filetype];
        $targetDirectory = "../../uploads/logo/"; // __DIR__ is the directory of the current PHP file

        $newFilepath = $targetDirectory . "/" . "logo." . $extension;

        if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
            return $_SESSION['fal'] = "Can't move file.";
        } else {

            unlink($filepath); // Delete the temp file
            $_SESSION['suc'] = "File uploaded successfully :)";
        }




    }
    function Darklogo()
  
    {
        $this->start_session();
        if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $_POST['csrf_token']) {
            return $_SESSION['fal'] = "Something Went Wrong !";
        }
        if (empty($_FILES['dark_logo']['tmp_name'])) {
            return $_SESSION['fal'] = "There is no file to upload.";

        }

        $filepath = $_FILES['dark_logo']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);

        if ($fileSize === 0) {
            return $_SESSION['fal'] = "The file is empty.";
        }

        if ($fileSize > 3145728) { // 3 MB (1 byte * 1024 * 1024 * 3 (for 3 MB))
            return $_SESSION['fal'] = "The file is too large";
        }

        $allowedTypes = [
            'image/png' => 'png'
        ];

        if (!in_array($filetype, array_keys($allowedTypes))) {
            return $_SESSION['fal'] = "File not allowed.";
        }

        $filename = basename($filepath); // I'm using the original name here, but you can also change the name of the file here
        $extension = $allowedTypes[$filetype];
        $targetDirectory = "../../uploads/logo/"; // __DIR__ is the directory of the current PHP file

        $newFilepath = $targetDirectory . "/" . "dark_logo." . $extension;

        if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
            return $_SESSION['fal'] = "Can't move file.";
        } else {

            unlink($filepath); // Delete the temp file
            $_SESSION['suc'] = "File uploaded successfully :)";
        }




    }
    function favicon()
    {
        $this->start_session();
        if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $_POST['csrf_token']) {
            return $_SESSION['fal'] = "Something Went Wrong !";
        }
        if (empty($_FILES['favicon']['tmp_name'])) {
            return $_SESSION['fal'] = "There is no file to upload.";

        }

        $filepath = $_FILES['favicon']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);

        if ($fileSize === 0) {
            return $_SESSION['fal'] = "The file is empty.";
        }

        if ($fileSize > 3145728) { // 3 MB (1 byte * 1024 * 1024 * 3 (for 3 MB))
            return $_SESSION['fal'] = "The file is too large";
        }

        $allowedTypes = [
            'image/png' => 'png'
        ];

        if (!in_array($filetype, array_keys($allowedTypes))) {
            return $_SESSION['fal'] = "File not allowed.";
        }

        $filename = basename($filepath); // I'm using the original name here, but you can also change the name of the file here
        $extension = $allowedTypes[$filetype];
        $targetDirectory = "../../uploads/logo/"; // __DIR__ is the directory of the current PHP file

        $newFilepath = $targetDirectory . "/" . "favicon." . $extension;

        if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
            return $_SESSION['fal'] = "Can't move file.";
        } else {

            unlink($filepath); // Delete the temp file
            $_SESSION['suc'] = "File uploaded successfully :)";
        }




    }


    function api()
    {

        foreach ($_POST as $key => $value) {
            if ($key & $value) {
                $post[$key] = $value; //Thsi array holds all post data now.

                if (array_key_exists('edit', $post)) {
                    // no need of any statement blank for not include edit in array
                } else {
                    //echo "Field name : ".$key .", Value : ".$value."<br>";
                    $array[$key] = $value; //Thsi array holds all post data now.
                }
            }
        }
        // print_r( $array );
        // die;

        $where = "name = '" . $_POST['edit'] . "'";
        // print_r($array);
        // die;
        $data = $this->update_query('api', $array, $where);
        $section = $_GET['api'];
        $this->msg_set($data, 'setting&api=' . $section);


    }


    function apitest()
    {
        $i = 0;
        $api_status = $_POST['api_status'];
        $attributinfo = array();
        foreach ($_POST['key'] as $value) {
            $k = $_POST['key'][$i];
            $kv = $_POST['key_value'][$i];
            $info = array('key' => $this->sanitize($k), 'kv' => $this->sanitize($kv));
            array_push($attributinfo, $info);
            $i++;
        }

        $attributinfo_new = json_encode($attributinfo);
        $where = "name = '" . $_POST['edit'] . "'";


        $array = array(
            'api_status' => $api_status,
            'key_value' => $attributinfo_new,
            'name' => $_POST['edit'],
        );
        // print_r($array);
        // die;
        $data = $this->update_qry('apitest', $array, $where);
        // $data = $this->insert_qry('apitest', $array);

        $this->msg_set($data, 'setting');


    }

function start_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

    function save_api()
    {
        // error();
        $this->start_session();
        if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $_POST['csrf_token']) {
            return $_SESSION['fal'] = "Something Went Wrong !";
        }
        $csrf = $_POST['csrf_token'];
        date_default_timezone_set("Asia/Kolkata");
        $array = [
            'api_key' => $_POST['api_key'] ?? '',
            'secret' => $_POST['secret'] ?? '',
            'source' => $_POST['source'] ?? '',
            'auto_assign_to' => $_POST['auto_assign_to'] ?? '',
            'status' => $_POST['status'] ?? '',
            'group_api'=>$_POST['group_api'],
            'notification' => isset($_POST['notification']) ? implode(',', $_POST['notification']) : '',
        ];
        // print_r($_POST);
       try{
        $data = $this->csrf_insert('api_keys', $array, $csrf);
        
       }catch(Exception $e){
        return $_SESSION['fal'] = "Something Went Wrong  try again!";
       }

        $this->msg_set($data, 'setting');

    }



    function auto_add()
    {


        date_default_timezone_set("Asia/Kolkata");
        $date_time = date("d/m/Y h:i:sa");

        foreach ($_POST as $key => $value) {
            if ($key & $value) {
                $post[$key] = $value; //Thsi array holds all post data now.
                if (array_key_exists('table', $post) or array_key_exists('nav', $post)) {
                    // no need of any statement blank for not include edit in array
                } else {
                    $array[$key] = $value; //Thsi array holds all post data now.
                }
            }
        }
        // print_r( $array );
        //die;
        $table = $_POST['table'];
        $nav = $_POST['nav'];
        $data = $this->insert_query($table, $array);

        $this->msg_set($data, $nav);

    }

    function login_export()
    {

        if (isset($_POST["login_export"])) {

            ob_start();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            header('Cache-Control: no-cache');
            //header('Content-Length: '. ob_get_length());


            $output = fopen("php://output", "w");
            fputcsv($output, array('Name', 'UserName', 'UserType', 'A/c Status', 'Last Login'));
            $exp = $this->mysqli->query("SELECT name,username,usertype,status,last_login FROM user where usertype!='root' order by id ASC");

            while ($row = mysqli_fetch_assoc($exp)) {
                fputcsv($output, $row);
            }
            $streamSize = ob_get_length();
            header('Content-Length: ' . ob_get_length());

            // Flush (send) the output buffer and turn off output buffering
            ob_end_flush();
            fclose($output);
            exit();
        }
    }



    function activity_export()
    {

        if (isset($_POST["activity_export"])) {


            ob_start();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            header('Cache-Control: no-cache');
            //header('Content-Length: '. ob_get_length());


            $output = fopen("php://output", "w");
            fputcsv($output, array('User Id', 'UserName', 'Action', 'Date'));
            $exp = $this->mysqli->query("SELECT user_id,username,action,date FROM user_actvity INNER JOIN user ON user_actvity.user_id = user.id");

            while ($row = mysqli_fetch_assoc($exp)) {
                fputcsv($output, $row);
            }
            $streamSize = ob_get_length();
            header('Content-Length: ' . ob_get_length());

            // Flush (send) the output buffer and turn off output buffering
            ob_end_flush();
            fclose($output);
            exit();
        }
    }


}


if (isset($_POST['add-company-info'])) {
    $obj = new setting();
    $obj->add_company_info();
}

if (isset($_POST['auto-add'])) {
    $obj = new setting();
    $obj->auto_add();
}



if (isset($_POST['update-company-info'])) {
    $obj = new setting();
    $obj->update_company_info();
}

if (isset($_POST['logo-upload'])) {
    $obj = new setting();
    $obj->logo();
}
if (isset($_POST['dark-logo-upload'])) {
    $obj = new setting();
    $obj->Darklogo();
}
if (isset($_POST['favicon-upload'])) {
    $obj = new setting();
    $obj->favicon();
}

if (isset($_POST['api'])) {
    $obj = new setting();
    $obj->api();
}
if (isset($_POST['apitest'])) {
    $obj = new setting();
    $obj->apitest();
}

if (isset($_POST['login_export'])) {
    $obj = new setting();
    $obj->login_export();
}
if (isset($_POST['activity_export'])) {
    $obj = new setting();
    $obj->activity_export();
}
if (isset($_POST['logo-style'])) {
    $obj = new setting();
    $obj->logo_style();
}

if (isset($_POST['save-api'])) {
    $obj = new setting();
    $obj->save_api();
}



?>