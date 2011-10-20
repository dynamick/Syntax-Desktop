<?php
global $db,$synRootPasswordSalt,$synVersion;
$err = $alertBox = null;

if (isset($_POST["login"]) and isset($_POST["password"])) {
  $login=addslashes(strip_tags($_POST["login"]));
  $password=md5($_POST["password"].$synRootPasswordSalt);
  $res=$db->Execute("select * from aa_users where login='$login' and passwd='$password'");
  $q=$res->RecordCount();
  if ($q==0) $err="<div style=' background: #FAFAFA;color: darkred; font-weight: bold; font-size: xx-small'>Login Error</div>";
  if ($q>0) {
    $arr=$res->FetchRow();

    if(!isset($_SESSION)) session_start();
    
    $tree=array();
    $_SESSION["synUser"]=$arr["id"];
    $_SESSION["synGroup"]=$arr["id_group"];
    $_SESSION["synCustomLang"]=$_POST["synCustomLang"];
    $_SESSION["synGroupTree"]=getGroupTree($arr["id_group"],$tree);
    $_SESSION["synGroupChild"]=array_reverse(getGroupChild($arr["id_group"],$tree));
    $_SESSION["synUsersInGroup"]=getUsersInGroup();
    header("location: ./");
    die;
  }
}
/*
  if (is_connected()) {
    include($synAbsolutePath.$synAdminPath."/includes/php/IXR_Library.inc.php");
    $client = new IXR_Client('http://www.syntaxdesktop.com/admin/public/server/notify.php');

    // Run a query for PHP
    if (!$client->query('syntax.notifyServer', getenv("HTTP_HOST"), $synVersion)) {
      die('Something went wrong - '.$client->getErrorCode().' : '.$client->getErrorMessage());
    }

    // Display the result
    $alert=$client->getResponse();
    if ($alert!="") $alertBox="<div class=\"alert\">$alert</div>\n";
  }
*/
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?=$synAdminPath?>/modules/login/login.css" />
</head>
<body style="overflow-y: hidden">
<div id="login">
  <h1>Website Backoffice</h1>
  <fieldset>
    <form action="" method="post" autocomplete="off">
      <table cellspacing="5">
        <tr><td><span class="label">Login </span></td><td><input type="text" name="login" value="" tabindex="1" id="start" /></td></tr>
        <tr><td><span class="label">Password </span></td><td><input type="password" name="password" value="" tabindex="2"/></td></tr>
        <tr><td><span class="label">Lang </span></td><td><select name="synCustomLang" tabindex="3"><option value="user">User Default</option><option value="2">English</option><option value="1">Italian</option></select></td></tr>
        <tr><td></td><td style="text-align: left"><div><input type="submit" tabindex="4" value="Start"/></td></tr>
        <tr><td colspan="2" style="text-align: center;"><?=$err;?></td></tr>
      </table>
    </form>
  </fieldset>
  <?=$alertBox?>
</div>
<div id="credits">
  <a href="http://www.syntaxdesktop.com"><img src="<?=$synAdminPath?>/modules/login/images/syntax-desktop.gif" alt="syntax desktop" /></a>
  <span>Syntax Desktop, the <strong>open source CMS</strong> made in Italy.</span>
  <span>For more informations, visit <a href="http://www.syntaxdesktop.com">www.syntaxdesktop.com</a>.</span>
</div>
<script type="text/javascript">document.getElementById("start").focus();</script>
</body>
</html>
