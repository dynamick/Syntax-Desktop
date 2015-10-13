<?php
  if(!isset($_SESSION))
    session_start();
  include_once ('../../config/cfg.php');
  include_once ('../../includes/php/menu.php');
  include_once ('classes/synContainer.php');
  include_once ('classes/synHtml.php');

 /***************************************************************************
  *                             MULTILANG SECTION
  ***************************************************************************/

  if ( isset($_GET['synSetLang']) ) {
    $langid = intval($_GET['synSetLang']);
    if ( isset($_SESSION['aa_CurrentLang']) && $_SESSION['aa_CurrentLang'] != $langid ) {
      setLang($langid);
      setAlert( sprintf('Lingua attiva: <b>%s</b>', getLangInfo($langid, 'lang') ) ) ;
    }
  } elseif( !isset($_SESSION['aa_CurrentLang']) || empty($_SESSION['aa_CurrentLang']) ) {
    setLang();
  }

  define ('MODIFY',         'modifyrow');
  define ('CHANGE',         'changerow');
  define ('ADD',            'addrow');
  define ('INSERT',         'insertrow');
  define ('DELETE',         'delrow');
  define ('MULTIPLEDELETE', 'delmultrow');
  define ('RPC',            'rpcfunction');
  define ('JSON',           'getjson');

  // javascript array
  static $js = array();

  //check the authorization
  auth();

  //load the lang settings
  lang( getSynUser(), $str );

  if (isset($_REQUEST['aa_service']))
    $_SESSION['aa_service'] = $_REQUEST['aa_service'];

  $res = $db->Execute("SELECT path FROM aa_services WHERE id=" . $_SESSION['aa_service']);
  list( $targetFileName ) = $res->FetchRow();

  if ( empty($targetFileName) )
    $targetFileName = "ihtml/auto_service.php";

  ob_start();
    if (is_file($targetFileName))
      include($targetFileName);
    else
      echo '<p>Function not yet implemented...</p>';
    $contents = ob_get_contents();
  ob_end_clean();

  if ( isset($cmd)
    && in_array( $cmd, array(ADD, MODIFY))
    ){
    $js_scope = 'single';
  } else {
    $js_scope = 'index';
  }

 /***************************************************************************
  *                             MULTILANG SECTION
  ***************************************************************************/
  //if (isset($_GET["synSetLang"])) $_SESSION["aa_CurrentLang"]=$_GET["synSetLang"];
  //elseif ($_SESSION["aa_CurrentLang"]=="") $_SESSION["aa_CurrentLang"]=1;
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Content Frame</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,300,400,600&amp;subset=latin,cyrillic">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/animsition.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-switch.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-multiselect.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-table.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/fontawesome-iconpicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/fileinput.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/animate.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/typeahead.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/syntax.css" />
    <script type="text/javascript">
    var
      preview = new Array(),
      syntax = {
        appname: '<?php echo sanitizePath($synWebsiteTitle) ?>',
        service: {
          id: '<?php echo $synContainer ?>',
          table: '<?php echo $contenitore->getTable() ?>',
          lang: '<?php echo getLang(true); ?>'
        },
        cmd: {
          multidel: '<?php echo MULTIPLEDELETE ?>',
          rpc: '<?php echo RPC ?>'
        },
        str: {
          confirm: '<?php echo $str["sure_delete"] ?>'
        }
      },
      actions = {
        newBtn:     'document.location.href="content.php?cmd=<?= ADD ?>"',
        backBtn:    'history.back()',
        refreshBtn: 'location.reload()',
        saveBtn:    'document.forms[0].submit()',
        removeBtn:  'confirmDeletion()',
        homeBtn:    'location.href="<?= $PHP_SELF ?>"'
      },
      CKEDITOR_BASEPATH = '<?php echo $synAdminPath ?>/assets/js/vendor/ckeditor/';
    </script>
  </head>
  <body>
    <div id="content"
      class="container-fluid animsition"
      data-animsition-in-class="fade-in-left-sm"  data-animsition-in-duration="1000"
      data-animsition-out-class="fade-out-left-sm"  data-animsition-out-duration="800">
    <?php echo $contents; ?>
    </div>

    <script type="text/javascript" src="../../assets/js/js_libs.php?scope=<?php echo $js_scope ?>&amp;v=<?php echo $synVersion ?>"></script>
    <script type="text/javascript">
    //BUTTON FUNCTIONS
    for( var key in actions ) {
      action( key, 'window.parent.content.' + actions[key]);
    }
    $(function() {
      <?php foreach( $js as $script) echo $script . PHP_EOL; ?>
      <?php echo getAlert(); ?>
    });
    </script>
  </body>
</html>
