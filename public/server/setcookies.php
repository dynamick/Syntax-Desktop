<?php
require_once("../config/cfg.php");
global $db, $userid, $synRootPasswordSalt;

$login  = strip_tags($_POST["act"]);
$logoff = addslashes(strip_tags($_GET["act"]));
$ref    = $_SERVER["HTTP_REFERER"];

if ($login=='login') {
  $mail = $_POST["email"];
  $pwd  = $_POST["password"];
  $qry  = "SELECT `id`,`group` FROM users WHERE email='$mail' AND password='".md5($pwd.$synRootPasswordSalt)."' and active='1'";
  $res  = $db->Execute($qry);
  $tot  = $res->RecordCount();

  if ($tot==0) {
    # utente non riconosciuto!
    # passo la stringa di errore, controllando che non sia già presente
    $err = (stristr($ref, 'err=wrong_pwd')) ? '' : '?err=wrong_pwd'; 
    header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
    header("location: ".$ref.$err);
    exit;

  } else {
    # autenticato, setto i cookies
    list($id, $group) = $res->FetchRow();
    setcookie ("web_user[id]", $id, time()+(3600*24*365), "/");
    setcookie ("web_user[group]", $group, time()+(3600*24*365), "/");
    # rimuovo la stringa di errore, se presente
    $ref = (stristr($ref, 'err=wrong_pwd')) ? str_replace('err=wrong_pwd', '', $ref) : $ref;
    header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
    header("location: ".$ref);
    exit;
  }

} elseif ($logoff=='logoff') {
  # logout, elimino i cookies
  setcookie ("web_user[id]", "", time()-(3600), "/"); 
  setcookie ("web_user[group]", "", time()-(3600), "/");
  header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
  header("location: ".$ref);
}
?>
