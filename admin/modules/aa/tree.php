<?php
  if (!isset($_SESSION))
    session_start();

  include_once ('../../config/cfg.php');
  include_once ('classes/synContainer.php');

  define('MODIFY',         'modifyrow');
  define('CHANGE',         'changerow');
  define('ADD',            'addrow');
  define('INSERT',         'insertrow');
  define('DELETE',         'delrow');
  define('MULTIPLEDELETE', 'delmultrow');
  define('RPC',            'rpcfunction');
  define('JSON',           'getjson');

  //check the authorization
  auth();

  //load the lang settings
  lang( $_SESSION['synUser'], $str );

  // get service ID
  $aa_service = isset($_REQUEST['aa_service'])
              ? $_REQUEST['aa_service']
              : $_SESSION['aa_service'];

  // check if service has a terget file
  $res = $db->Execute("SELECT path FROM aa_services WHERE id='{$aa_service}'");
  list ($targetFileName) = $res->FetchRow();

  // default target file
  if (empty($targetFileName))
    $targetFileName = "ihtml/auto_service.php";

?><!DOCTYPE html>
<html lang="en" style="box-shadow: -10px 0 15px -15px rgba(0, 0, 0, 0.3) inset;">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tree Frame</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:700,300,600,400&amp;subset=latin,cyrillic">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/syntax.css" />
  </head>
  <body class="">
    <div class="container-fluid">
<?php
    $treeFrame = 'true';

    if ( is_file($targetFileName) ) {
      include( $targetFileName );
    } else {
      echo 'Function not yet implemented...';
    }
?>
    </div>
    <script type="text/javascript" src="../../assets/js/js_libs.php?scope=tree&amp;v=<?php echo $synVersion ?>"></script>
  </body>
</html>