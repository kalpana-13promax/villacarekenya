<?php

function start_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function error()
{
    // ini_set('display_errors', 'on');
    // error_reporting(E_ALL);
}

error_reporting(0);
// error();
//  @session_start();

define('EXPIRY', '2027-01-05');

define('HOSTS', 'localhost');

define('USERNAME', 'crm_crm');
define('PASSWORD', '8fYqyCDEQ&94');
define('DATABASE', 'crm_crm');

// define('HOSTS', 'localhost');
// define('USERNAME', 'sachin');
// define('PASSWORD', 'Sach@1234##$$');
// define('DATABASE', 'sachin_crm5');

// define('DOMAIN', 'http://localhost/projects/villacare/');
define('DOMAIN', 'https://crm.villacarekenya.com/en/');

// define('BASEURL', 'http://localhost/projects/villacare/crm/admin/');
define('BASEURL', 'https://crm.villacarekenya.com/crm/admin/');

define('LOGO', '../uploads/logo/logo.png');
define('FAVICON', '../uploads/logo/favicon.png');
define('DEFAULTIMG', '../uploads/logo/default.png');
define('IMGPATH', '../uploads/');
define('IMGAJAX', '../../uploads/');
define('UPLOADS', DOMAIN . '/crm/uploads/');

define('WEBSITE', 'https://crm.villacarekenya.com/');
define('LANIDNG_BASEURL', 'https://crm.villacarekenya.com/');
$folder = str_replace('/', '', $_SERVER['REQUEST_URI']);

define('DIR', $folder);

// defined constants of all website pages
define('HOME', DOMAIN . '/en/');
define('ABOUT', DOMAIN . '/en/about-us/');
define('PROJECT', DOMAIN . '/en/projects/');
define('PROPERTY', DOMAIN . '/en/properties/');
define('CONTACT_US', DOMAIN . '/en/contact-us/');
define('BLOG', DOMAIN . '/en/blog/');

define('PMS_PRO', 'PMS Pro');
define('CURRENCY', 'KES. ');

define('C_CODE', '+254');

define('DOCROOT', dirname(__FILE__));

/* load all classes */
require_once ('database.php');
require_once ('classes/project.php');
require_once ('classes/website.php');
require_once ('classes/property.php');
require_once ('classes/property-type.php');
require_once ('classes/amenity.php');
require_once ('classes/lead.php');
require_once ('classes/agents.php');
require_once ('classes/admin.php');
// require_once('classes/message.php');
require_once ('classes/client.php');
require_once ('classes/owner.php');

require_once ('classes/masters.php');
require_once ('classes/advertisement.php');
require_once ('classes/notice.php');
require_once ('classes/ploting.php');
require_once ('classes/payment.php');
require_once ('classes/emi.php');
require_once ('classes/agreement.php');
require_once ('classes/setting.php');
require_once ('classes/to-do.php');
require_once ('classes/leads-color-mark.php');
require_once ('classes/expenses.php');

require_once ('classes/lead-status.php');
require_once ('classes/template.php');
require_once ('classes/task.php');
require_once ('classes/visits.php');
require_once ('classes/language.php');
require_once ('classes/migrations.php');
require_once ('classes/LeadSettings.php');
require_once 'classes/custom-fields.php';
$customFields = new CustomFields();

spl_autoload_register(function ($class) {
    // include 'classes/' . $class . '.class.php';
    $filename = DOCROOT . '/classes/' . strtolower($class) . '.php';
    if (file_exists($filename)) {
        include_once $filename;
    }
});

/* login user data */
$boj = new itways();
$getuserdata = $boj->userdata();
$uri = $boj->uri();
$dir = $boj->cwd();
$getuserdata = $boj->userdata();
$company = $boj->company();
$check = new permissions();

/* Site Config */
if (@$company->name) {
    define('SITENAME', $company->name);
} else {
    define('SITENAME', 'IT Ways');
}

if (@$company->slogan) {
    define('SLOGAN', $company->slogan);
} else {
    define('SLOGAN', 'Find your dream property.');
}

if (@$company->phone) {
    define('CONTACT', $company->phone);
} else {
    define('CONTACT', '+91 0000 000000');
}

if (@$company->mail) {
    define('EMAIL', $company->mail);
} else {
    define('EMAIL', 'mail@demo.com');
}

if (@$company->alt_mail) {
    define('EMAIL_SALES', $company->alt_mail);
} else {
    define('EMAIL_SALES', 'sales@demo.com');
}

if ($company->logo_style) {
    define('LOGO_STYLE', $company->logo_style);
} else {
    define('LOGO_STYLE', '1');
}

$csrf_token = $boj->csrf_token();
