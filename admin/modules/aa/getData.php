<?php
  $xhr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) and (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
  header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
  header("Cache-Control: no-cache, must-revalidate" );
  header("Pragma: no-cache" );
  header('Content-Type: ' . ($xhr ? 'application/json' : 'text/plain'));

  session_start();
  include_once ('../../config/cfg.php');
  //include_once ('../../includes/php/jslib.inc');
  include_once ('../../includes/php/menu.php');
  include_once ('classes/synContainer.php');
  include_once ('classes/synHtml.php');

 /***************************************************************************
  *                             MULTILANG SECTION
  ***************************************************************************/
  if (isset($_GET['synSetLang']))
    setLang($_GET['synSetLang']);
  elseif ($_SESSION['aa_CurrentLang'] == '')
    setLang(1);


  define ('MODIFY',         'modifyrow');
  define ('CHANGE',         'changerow');
  define ('ADD',            'addrow');
  define ('INSERT',         'insertrow');
  define ('DELETE',         'delrow');
  define ('MULTIPLEDELETE', 'delmultrow');
  define ('RPC',            'rpcfunction');
  define ('JSON',           'getjson');

  //check the authorization
  auth();

  //load the lang settings
  lang(getSynUser(), $str);

  if (isset($_REQUEST['aa_service']))
    $_SESSION['aa_service'] = $_REQUEST['aa_service'];

  $res = $db->Execute('SELECT path FROM aa_services WHERE id='.$_SESSION['aa_service']);
  list($targetFileName)=$res->FetchRow();

  if ($targetFileName == '')
    $targetFileName = 'ihtml/auto_service.php';

  if (is_file($targetFileName))
    include($targetFileName);
  else
    echo '<p>Function not yet implemented...</p>';

// EOF