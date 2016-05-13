<?php
global $db, $synRootPasswordSalt, $synVersion;
$alertBox = null;

if (isset($_POST['login']) and isset($_POST['password'])) {
  $login = addslashes(strip_tags($_POST['login']));
  $password = md5($_POST["password"].$synRootPasswordSalt);
  $res = $db->Execute("SELECT * FROM aa_users WHERE login='{$login}' AND passwd = '{$password}'");
  $q = $res->RecordCount();
  if ($q == 0)
    $alertBox = <<<EOALERT
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      Login Error
    </div>
EOALERT;
  elseif ($q>0) {
    $arr = $res->FetchRow();

    if (!isset($_SESSION))
      session_start();

    $tree = array();
    $_SESSION['synUser'] = $arr['id'];
    $_SESSION['synGroup'] = $arr['id_group'];
    $_SESSION['synCustomLang'] = $_POST['synCustomLang'];
    $_SESSION['synGroupTree'] = getGroupTree($arr['id_group'],$tree);
    $_SESSION['synGroupChild'] = array_reverse(getGroupChild($arr['id_group'],$tree));
    $_SESSION['synUsersInGroup'] = getUsersInGroup();
    header('location: ./');

    die();
  }
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Syntax Desktop - Ver. <?php echo htmlentities($synVersion)?></title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/animsition.min.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/syntax.css" />
</head>
<body class="login">
  <div class="container animsition">
    <div class="row">
      <div class="col-sm-6 col-md-4 col-md-offset-4">
        <h1 class="text-center login-title">Website Backoffice</h1>
        <div class="account-wall">
          <img class="profile-img" src="assets/images/user.jpg" alt="">
          <form id="login-form" class="form-signin" action="" method="post" autocomplete="off">
            <?php echo $alertBox ?>
            <input type="text" class="form-control" name="login" placeholder="Username" required autofocus tabindex="1">
            <input type="password" class="form-control" name="password" placeholder="Password" required tabindex="2">
            <input type="hidden" name="synCustomLang" value="user">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
          </form>
        </div>
        <p class="backtowebsite">
          <a class="animsition-link" href="../index.php"><i class="fa fa-arrow-left"></i> Back to website</a>
        </p>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="assets/js/js_libs.php?v=<?php echo $synVersion ?>"></script>
  <script>
    var $el = $('.animsition');
    $(document).ready(function() {
      $el.animsition({
        inClass: 'fade-in-up',
        outClass: 'fade-out-up',
        inDuration: 800,
        outDuration: 800,
        loading: false,
        loadingParentElement: 'body', //animsition wrapper element
        loadingClass: 'animsition-loading',
        loadingInner: '', // e.g '<img src="loading.svg" />'
        timeout: false,
        timeoutCountdown: 5000,
        onLoadEvent: true,
        browser: [ 'animation-duration', '-webkit-animation-duration'],
        overlay : false,
        overlayClass : 'animsition-overlay-slide',
        overlayParentElement : 'body',
      });

      $('#login-form').submit(function(){
        $el.animsition( 'out', $el, 'javascript:void(0)' );
      });
    });
  </script>
</body>
</html>
