<?php
//ini_set('display_errors','On');

//$dir = dirname(__FILE__) . '/';
$ROOT = $_SERVER['DOCUMENT_ROOT'];

if (file_exists($ROOT.'/admin/config/config.php')) {
  require_once ($ROOT.'/admin/config/config.php');
} else {
  die('Please <strong>configure</strong> the application.');
}

///////////////////////////////////////////////////////////////////////////////
//  ADVANCED SETTINGS
//////////////////////////////////////////////////////////////////////////////

// PAGE TREE Entry Point
$synEntryPoint = array('syntax'=>22);


///////////////////////////////////////////////////////////////////////////////
//  DO NOT MODIFY UNDER THIS LINE
//////////////////////////////////////////////////////////////////////////////

$admin = $ROOT.$synAdminPath.'/';
$pub = $ROOT.$synPublicPath.'/';

// library inclusion (DB libraries)
require $admin.'includes/php/ADODB-PDO.php';

// library inclusion (Website libraries)
require $pub.'lib/class.synSmarty.php';
//require $pub.'lib/class.ImageToolbox.php';
require $pub.'lib/class.formBuilder.php';
require $pub.'lib/class.bsForm.php';
require $pub.'lib/class.synMailer.php';
require $pub.'lib/class.synUser.php';
require $pub.'lib/class.synAccount.php';
//require $pub.'lib/class.feedCreator.php';
require $pub.'lib/class.synPagerPublic.php';
//require $pub.'lib/phpmailer/class.phpmailer.php';
require $pub.'lib/lang.functions.php';
require $pub.'lib/page.functions.php';
require $pub.'lib/date.functions.php';
require $pub.'lib/misc.functions.php';
require $pub.'lib/utility.php';

$db = NewADOConnection('mysql');

//connect to Database
$result=@$db->Connect($synDbHost, $synDbUser, $synDbPassword, $synDbName);

//if some configuration fails... reconfigure the parameter
if ($result===false) {
  $txt.="<p style='color: red'>Database unavailable.</p>\n";
  die($txt);
  }

//if the DB is empty, populate it!
$a = $db->MetaTables();
if ($a!==false AND count($a)==0 and basename(getenv("SCRIPT_FILENAME"))!="setup.php") {
  die("Run <a href=\"".$synAdminPath."/setup.php\">Setup</a> to install Syntax Desktop. ");
}

//check if setup.php was removed
if (file_exists($synAbsolutePath.$synAdminPath."/setup.php") and basename(getenv("SCRIPT_FILENAME"))!="setup.php") {
  die("Please <strong>remove</strong> the <strong>".$synAdminPath."/setup.php</strong> before proceeding and refresh this page.");
}

// nome del cookie di autenticazione
define('COOKIE_NAME', 'syntax_web_user');

define('PAGE_ACCOUNT',  52);
define('DEFAULT_GROUP', 1); // gruppo utenti default
define('ACCOUNT_KEY',   'syntax_user'); // nome del cookie
define('ADMIN_NAME',    'Admin');
define('ADMIN_MAIL',    'assistenza@kleis.it');

?>
