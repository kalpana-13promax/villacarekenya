<?php
//error_reporting(~E_NOTICE);
//error_reporting(E_ALL);
//error_reporting(0);



/* database */
define('HOSTS', 'localhost'); 
define('USERNAME', 'smartproperty_crm'); 
define('PASSWORD', '!5EpJMno~fg-'); 
define('DATABASE', 'smartproperty_crm'); 


/* site config */
define('SITENAME', 'SMART PROPERTY : REALESTATE'); 
//define('LOGO', 'Sathi-Logo-Dark.png');
define('SLOGAN', 'One of the leading Property Consultants in Balasore, Odisha'); 
define('CONTACT', '+91 92382 30915');
define('EMAIL', 'info@smartproperty.co.in'); 
define('EMAIL_SALES', 'sales@smartproperty.co.in'); 
define('DOCROOT', dirname(__FILE__));

define ('PMS_PRO', 'PMS Pro');

define('CURRENCY', '&#8377;');


/* load all classes */
require_once('database.php');
require_once('classes/project.php');
require_once('classes/property.php');
require_once('classes/property-type.php');
require_once('classes/amenity.php');
require_once('classes/lead.php');
require_once('classes/agents.php');
require_once('classes/admin.php');
require_once('classes/mlm-agents.php');
require_once('classes/client.php');
require_once('classes/masters.php');
require_once('classes/advertisement.php');
require_once('classes/notice.php');
require_once('classes/ploting.php');
require_once('classes/emi.php');



spl_autoload_register(function($class) {
   // include 'classes/' . $class . '.class.php';
        $filename = DOCROOT . "/classes/" . strtolower($class) . ".php";
    if ( file_exists($filename) )
    {
        include_once $filename;
    }
});


/* login user data */
$boj = new itways();
$getuserdata = $boj->userdata();




?>