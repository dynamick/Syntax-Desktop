<?php
  session_start();
  session_regenerate_id(true);

  if (file_exists(dirname(__FILE__)."/config/config.php")) {
    require_once("config/cfg.php");
  } else {
    die("Is it the first time you run Syntax Desktop? If yes, run <a href=\"setup.php\">setup</a>.");
  }

  if (getSynUser()):
  // user logged in:
    //load language strings
    $jsLang = lang(getSynUser(), $str);

    $aa_service = filter_input(INPUT_GET, 'aa_service', FILTER_SANITIZE_NUMBER_INT);
    $aa_group_services = filter_input(INPUT_GET, 'aa_group_services', FILTER_SANITIZE_NUMBER_INT);

    include ('includes/php/menu.php');

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Syntax Desktop - Ver. <?php echo htmlentities($synVersion)?></title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,300,600,400&subset=latin,cyrillic">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/syntax.css" />


<!-- INCLUDO LE STRINGHE PER LE LINGUE -->
  <script type="text/javascript"><?php echo $jsLang ?></script>

<!-- INCLUDO LO SCRIPT PER L'OROLOGIO IN ALTO A DX -->
  <!--script type="text/javascript" src="includes/js/liveclock/clock.js"></script-->

<!-- INCLUDO LE FUNZIONI PER GESTIRE IL DESKTOP -->
  <script type="text/javascript" src="functions/js/desktop.js"></script>
</head>
<body class="desktop" onload="desktopInit();">
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="fa fa-bars"></span>
        </button>
        <span class="navbar-brand syn-brand">sd</span>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <?php echo createMenu2( $aa_service ) ?>
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="/index.php" target="_blank">Visit Site</a>
          </li>
          <li class="dropdown ">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              Account
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li class="dropdown-header">SETTINGS</li>
              <li class="divider"></li>
              <li>
                <a href="modules/login/logoff.php">
                  <i class="fa fa-power-off"></i>
                  <?php echo $str["logoff"]?>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <footer class="footer" id="desktopbar_bottom">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-10">
          <span class="label label-primary">
            <i class="fa fa-user"></i>
            <small><?php echo username( getSynUser() );?></small>
          </span>&nbsp;
          <span class="label label-default">
            <i class="fa fa-group"></i>
            <small><?php echo groupname( $_SESSION['synUser'] ); ?></small>
          </span>
        </div>
        <div class="col-md-2 text-right">
          <a href="modules/login/logoff.php" class="btn btn-danger btn-xs" title="<?php echo $str["logoff"]?>">
            <i class="fa fa-power-off"></i>
          </a>
        </div>
      </div>
    </div>
  </footer>


  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  <script>
  $(document).ready(function(){
  });
  </script>
</body>
</html>
<?php

  else:
  // unauthenticated user or session timeout, request login:
    include ($synAbsolutePath.$synAdminPath."/modules/login/index.php");

  endif; //if getSynUser()
?>
