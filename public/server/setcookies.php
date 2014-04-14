<?php
  error_reporting(E_ALL & ~(E_NOTICE | E_DEPRECATED | E_WARNING));
  ini_set('display_errors', 0);

require_once('../config/cfg.php');
global $db;

$login  = strip_tags($_POST['act']);
$logoff = addslashes(strip_tags($_GET['act']));
$ref    = $_SERVER['HTTP_REFERER'];
$l      = multiTranslateDictionary(array('flash_login_success', 'flash_logout','flash_error_user','flash_error_account','flash_error_password'));

if ($login=='login') {
  $mail = $_POST['email'];
  $pwd  = $_POST['password'];
  $hashed_pass = hash($pwd);
  $qry  = "SELECT id, hashed_id, `group`, active, password FROM users WHERE email = '{$mail}' LIMIT 0,1";

  $res  = $db->Execute($qry);
  $tot  = $res->RecordCount();

  if ($tot == 0) {
    // user not found
    set_flash_message(sprintf($l['flash_error_user'], $mail), 'alert-error');

  } else {
    $arr = $res->FetchRow();
    extract($arr);

    if (empty($hashed_id)) {
      $hashed_id = hash($id);
      $upd = "UPDATE users SET hashed_id = '{$hashed_id}' WHERE id = '{$id}'";
      $db->execute($upd);
    }
    
    if (empty($active)) {
      // utente disattivato
      set_flash_message($l['flash_error_account'], 'alert-error');
    } else {
      // utente valido
      if ($hashed_pass != $password) {
        // password cannata
        set_flash_message($l['flash_error_password'], 'alert-error');
      } else {
        // autenticato, setto i cookies
        setcookie (COOKIE_NAME.'[id]', $hashed_id, time()+(3600*24*30), '/'); // 30 days
        setcookie (COOKIE_NAME.'[group]',  $group, time()+(3600*24*30), '/');
        set_flash_message(sprintf($l['flash_login_success'], $email), 'alert-success');
        
        $last_ip = $_SERVER['REMOTE_ADDR'];
        $upd = "UPDATE users SET last_access = NOW(), last_ip = '{$last_ip}' WHERE id = '{$id}'";
        $db->execute($upd);
      }
    }
  }

} elseif ($logoff == 'logoff') {
  // logout, elimino i cookies
  setcookie (COOKIE_NAME.'[id]',    NULL, time()-(3600), '/');
  setcookie (COOKIE_NAME.'[group]', NULL, time()-(3600), '/');
  set_flash_message($l['flash_logout'], 'alert-info');
}

header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
header('location: '.$ref);
exit();

// EOF