<?php
  session_start();
  session_regenerate_id(true);

  if (file_exists(dirname(__FILE__)."/config/config.php")) {
    require_once("config/cfg.php");
  } else {
    die("Is it the first time you run Syntax Desktop? If yes, run <a href=\"setup.php\">setup</a>.");
  }

  if (getSynUser()):
  # user logged in:
    //load language strings
    $jsLang = lang(getSynUser(), $str);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
  <head profile="http://www.w3.org/2005/10/profile">

  <title>Syntax Desktop - Ver. <?php echo htmlentities($synVersion)?></title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- INCLUDO LO STYLE SHEET PER IL DESKTOP -->
  <link rel="stylesheet" type="text/css" href="styles/xp-blue/desktop.css" title="xp-blue" />
  <link rel="alternate stylesheet" type="text/css" href="styles/orange/desktop.css" title="orange" />

<!-- INCLUDO IL FILE DI CONFIGURAZIONE -->
  <script type="text/javascript" src="config/config.js"></script>

<!-- INCLUDO LE STRINGHE PER LE LINGUE -->
  <script type="text/javascript"><?php echo $jsLang ?></script>

<!-- INCLUDO LO SCRIPT PER L'OROLOGIO IN ALTO A DX -->
  <script type="text/javascript" src="includes/js/liveclock/clock.js"></script>

<!-- INCLUDO LE FUNZIONI PER GESTIRE IL DESKTOP -->
  <script type="text/javascript" src="functions/js/desktop.js"></script>

<!-- INCLUDO LO GLI SCRIPT NECESSARI PER IL MENU Transmenus -->
  <link rel="stylesheet" type="text/css" href="includes/js/transmenus/transmenu.css" />
  <script type="text/javascript" src="includes/js/transmenus/transmenu.js"></script>
  <script type="text/javascript" src="functions/js/launchmenu.php"></script>
</head>
<body class="desktop" onload="desktopInit();">
    <div id="topbar">
      <div id="synMenu">
        <script type="text/javascript">
          createSynMenu();
        </script>
      </div>
      <div id="desktopbar_clock">
        <a href="/index.php" onclick="window.open(this.href); return false;" title="Site preview"><img src="images/preview.gif" id="preview" alt="website preview" /></a>
      </div>
    </div>

    <div id="desktopbar_bottom">
      <span id="userinfo"><img src="images/user.gif" alt="utente" />: <strong><?php echo username(getSynUser());?></strong> - <img src="images/group.gif" alt="gruppo" />: <strong><?php echo groupname($_SESSION["synUser"]);?></strong></span>
      <span id="userbutton"><a href="modules/login/logoff.php"><img src="images/exit.gif" alt="<?php echo $str["logoff"]?>" /></a></span>
    </div>
</body>
</html>
<?php

  else:
  # unauthenticated user or session timeout, request login:
    include ($synAbsolutePath.$synAdminPath."/modules/login/index.php");

  endif; //if getSynUser()
?>
