<?php
//error_reporting(0);

session_start();
ob_start();
header('Access-Control-Allow-Origin: *');
ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');

$configArray = json_decode(file_get_contents('config/config.php', true));
  
define("DB_NAME", $configArray->dbname);  // db name
define("DB_USER", $configArray->username);     // db username
define("DB_PASS", $configArray->passsword);         // db password
define("DB_HOST", $configArray->dbhost);    // db server
define("DB_PORT", $configArray->dbport);         // db server
define("TABLE_PREFIX", $configArray->prefix);

require_once __DIR__ . "/assests/language/table.php";
require_once __DIR__ . "/assests/classes/database.php";
require_once __DIR__ . "/assests/classes/functions.php";
require_once __DIR__ . "/assests/classes/phpmailer/class.phpmailer.php";
include_once __DIR__ . '/assests/classes/mainframe.php';
include_once __DIR__ . '/assests/classes/class.thumbnail.php';

$objDatabase = new Database();
$objFunctions = new ISP();

$siteUrl = $objFunctions->iFind(TBL_CONFIGURATION, 'config_value', array('config_key'=>'site_url'));
$siteTitle = $objFunctions->iFind(TBL_CONFIGURATION, 'config_value', array('config_key'=>'site_title'));
$siteContactEmail = $objFunctions->iFind(TBL_CONFIGURATION, 'config_value', array('config_key'=>'site_email'));

define("SITE_URL", $siteUrl);
define("SITE_ABSPATH", __DIR__);
define("SITE_UPLOADPATH", __DIR__. '/upload/');
define('SITE_MEDIA', SITE_URL.'upload/');
define('ADMIN_FOLDER' ,'webadmin');

//Website Path Settings

define("SITE_NAME", $siteTitle);
define("SITE_ADMINURL", SITE_URL .ADMIN_FOLDER . '/');
define("SITE_IMAGEURL", SITE_URL. 'assets/img/');
define("SITE_CSSURL", SITE_URL. 'assets/css/');
define("SITE_JSURL", SITE_URL. 'assets/js/');

define('PLEASE_ENTER', 'Required field can not be blank');

define('VC_SEF', 1); // Set 1 for Url rewriting on and 0 to Off Url Rewriting		

define('mosConfig_smtpauth', '0');
define('mosConfig_smtphost', 'mail.jobnotes.com');
define('mosConfig_smtppass', 'password');
define('mosConfig_smtpuser', 'enquiry@jobnotes.com');

define('mosConfig_mailfrom', $siteContactEmail);
define('mosConfig_fromname', $siteTitle);
define('mosConfig_mailer', 'mail');

define("LANGUAGE_CODE", "en");
define("META_TITLE", $siteTitle);
define("META_KEYWORDS", "");
define("META_DESCRIPTION", "");


// ---------- Cookie Info ---------- //
 
define("cookie_time", (3600 * 24 * 30)); // 30 days

if(isset($_COOKIE['siteAuth']))
{
    parse_str($_COOKIE['siteAuth']);
    $_SESSION['adminid']= $usr;
    $_SESSION['role']= $role;
    $_SESSION['fname']= $fname;
}


$objFunctions->loadPlugins($objFunctions);

require_once( 'includes/sef.php' );

if($_REQUEST['site_id']=='' && $_SESSION['site_id']=='')
 $_SESSION['site_id'] = 1; 
  
if ($_REQUEST['site_id']<>'')
 $_SESSION['site_id'] = $_REQUEST['site_id']; 

?>