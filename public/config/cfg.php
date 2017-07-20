<?php

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
$synEntryPoint = array( 'syntax' => 22 );


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
require $pub.'lib/phpmailer/class.phpmailer.php';
require $pub.'lib/class.synMailer.php';
require $pub.'lib/class.synUser.php';
require $pub.'lib/class.synAccount.php';
//require $pub.'lib/class.feedCreator.php';
require $pub.'lib/class.synPagerPublic.php';

require $pub.'lib/lang.functions.php';
require $pub.'lib/page.functions.php';
require $pub.'lib/date.functions.php';
require $pub.'lib/misc.functions.php';
require $pub.'lib/utility.php';

$db = NewADOConnection('mysql');

//connect to Database
$result = $db->Connect( $synDbHost, $synDbUser, $synDbPassword, $synDbName );

//if some configuration fails... reconfigure the parameter
if ( false === $result ) {
  echo "<p style='color: red'>Database unavailable.</p>\n";
  die();
}

//if the DB is empty, populate it!
$a = $db->MetaTables();
if ( false !== $a
  && 0 == count( $a )
  && basename( getenv('SCRIPT_FILENAME')) != 'setup.php'
  ){
  echo "<p>Run <a href=\"{$synAdminPath}/setup.php\">Setup</a> to install Syntax Desktop.</p>\n";
  die();
}

//check if setup.php was removed
if ( is_file($synAbsolutePath.$synAdminPath.'/setup.php')
  && basename( getenv('SCRIPT_FILENAME')) != 'setup.php'
  ){
  echo "<p>Please <strong>remove</strong> the <strong>{$synAdminPath}/setup.php</strong> before proceeding and refresh this page.</p>\n";
  die();
}


// enable social network for sharing
$enabled_socials = array(
  'facebook',
  'twitter',
  'linked-in',
  //'pinterest',
  'google-plus',
  //'delicious',
  //'reddit',
  //'stumble-upon',
  //'digg'
);

// nome del cookie di autenticazione
define('COOKIE_NAME', 'syntax_web_user');

define('PAGE_HOME',     22);
define('PAGE_404',      51);
define('PAGE_ACCOUNT',  52);
define('PAGE_GALLERY',  54);
define('PAGE_NEWS',     55);
define('PAGE_SEARCH',   58);
define('PAGE_PRIVACY',  59);
define('PAGE_PRODOTTI', 60);
define('DEFAULT_GROUP',  1); // gruppo utenti default

define('ACCOUNT_KEY',   'syntax_user'); // nome del cookie
define('ADMIN_NAME',    'Admin');
define('ADMIN_MAIL',    'assistenza@kleis.it');

define('GMAPS_API',     'AIzaSyBbV3Qf4JBM54aazvYSD1pRLW1fHHtXmPs');

// configurazione SMTP
$smtp_conf = array();
/*
$smtp_conf = array(
  'host'    => 'ssl://smtp.gmail.com',
  'port'    => '465',
  'auth'    => true,
  'secure'  => '', // gmail -> '', aruba -> 'ssl'
  'user'    => 'user',
  'pass'    => 'password'
);
*/
?>
