<?php

ini_set('display_errors','On');
date_default_timezone_set ('Europe/Rome');

if (!isset($_SESSION))
  session_start();

$dir = dirname(__FILE__) . '/';

if (!file_exists($dir.'config.php')) {
  die("Please <strong>rename</strong> /admin/config/config.sample.php in /admin/config/config.php");
}

///////////////////////////////////////////////////////////////////////////////
//  DO NOT MODIFY UNDER THIS LINE
//////////////////////////////////////////////////////////////////////////////
//library inclusions
require_once $dir . '/config.php';
require_once $dir . '../includes/php/ADODB-PDO.php';
require_once $dir . '../includes/php/utility.php';
require_once $dir . '../includes/php/class.queryBuilder.php';

$db = NewADOConnection('mysql');

//connect to Database
$result=@$db->Connect($synDbHost, $synDbUser, $synDbPassword, $synDbName);

//if some configuration fails... reconfigure the parameter
if ($result===false) {
  $txt.="<p style='color: red'>Can't connect to the database.</p><p>Please follow these steps: </p>";
  //$txt.="<ol><li>Rename <strong>".$synAdminPath."/config/cfg-sample.php</strong> to ";
  //$txt.="<strong>".$synAdminPath."/config/cfg.php</strong>.</li>\n";
  $txt.="<li>Configure <strong>config.php</strong> with the right database account.</li>\n";
  $txt.="<li><strong>Refresh</strong> this page.</li>\n";
  $txt.="</ol>\n";
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


//bypass the registrer globals problem
if (!ini_get('register_globals')) {
  $superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);

  if (isset($_SESSION)) {
    array_unshift($superglobals, $_SESSION);
  }

  foreach ($superglobals as $superglobal) {
    extract($superglobal, EXTR_SKIP);
  }
}


  $path = pathinfo(getenv("SCRIPT_NAME"));
  //check the authorization and load translation strings
  //if (basename(getenv("SCRIPT_FILENAME"))!="setup.php") {

  if($path['dirname']!='/admin') {
    auth();
  }
?>
