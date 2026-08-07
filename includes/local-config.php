<?php
error_reporting(~E_NOTICE);


/* database */
define('HOSTS', 'localhost'); 
define('USERNAME', 'root'); 
define('PASSWORD', ''); 
define('DATABASE', 'sjp-crm'); 


/* site config */
define('SITENAME', 'SHRIJI PROPERTIES :: CRM'); 
define('EMAIL', 'info@shrijiproperties.com'); 
define('DOCROOT', dirname(__FILE__));


/* load all classes */
require_once('database.php');
require_once('classes/project.php');
require_once('classes/property.php');
require_once('classes/property-type.php');
require_once('classes/amenity.php');
require_once('classes/lead.php');
require_once('classes/agents.php');
require_once('classes/client.php');


spl_autoload_register(function($class) {
   // include 'classes/' . $class . '.class.php';
        $filename = DOCROOT . "/classes/" . strtolower($class) . ".php";
    if ( file_exists($filename) )
    {
        include_once $filename;
    }
});

//function __autoload($class)
//{

//}


/* login user data */
$boj = new itways();
$getuserdata = $boj->userdata();




?>