<?php
  session_start();
  include_once ("../../config/cfg.php");
  include_once ("../../includes/php/jslib.inc");
  include_once ("classes/synContainer.php");
  include_once ("classes/synHtml.php");

 /***************************************************************************
  *                             MULTILANG SECTION
  ***************************************************************************/
  if (isset($_GET["synSetLang"]))
    setLang($_GET["synSetLang"]);
  elseif ($_SESSION["aa_CurrentLang"]=="") 
    setLang(1);
  
  
  define ('MODIFY',         'modifyrow');
  define ('CHANGE',         'changerow');
  define ('ADD',            'addrow');
  define ('INSERT',         'insertrow');
  define ('DELETE',         'delrow');
  define ('MULTIPLEDELETE', 'delmultrow');
  define ('RPC',            'rpcfunction');

  //check the authorization
  auth();

  //load the lang settings
  lang(getSynUser(), $str);

  if (isset($_REQUEST["aa_service"])) 
    $_SESSION["aa_service"] = $_REQUEST["aa_service"];

  $res = $db->Execute("SELECT path FROM aa_services WHERE id=".$_SESSION["aa_service"]);
  list($targetFileName)=$res->FetchRow();
  
  if ($targetFileName=="") 
    $targetFileName = "ihtml/auto_service.php";

 /***************************************************************************
  *                             MULTILANG SECTION
  ***************************************************************************/
  //if (isset($_GET["synSetLang"])) $_SESSION["aa_CurrentLang"]=$_GET["synSetLang"];
  //elseif ($_SESSION["aa_CurrentLang"]=="") $_SESSION["aa_CurrentLang"]=1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
  <head profile="http://www.w3.org/2005/10/profile">
    <title>Syntax Desktop content frame</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="imagetoolbar" content="no" />
    <meta http-equiv="msthemecompatible" content="yes" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <link rel="stylesheet" type="text/css" href="content.css" />
    <script type="text/javascript" src="<?=$synAdminPath?>/includes/js/DateTimePicker/datetimepicker.js"></script>
    <script type="text/javascript" src="<?=$synAdminPath?>/includes/js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="plupload/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="content.js?rand=<?=rand(0,1000)?>"></script>
    <script type="text/javascript">
    //<![CDATA[
      //RPC FUNCTION CALLER
      var g_remoteServer = '<?=$targetFileName?>';
      var g_intervalID;
      function callServer(synPrimaryKey, field, value) {
      	var head = document.getElementsByTagName('head').item(0);
      	var old  = document.getElementById('lastLoadedCmds');
      	if (old) head.removeChild(old);

      	script = document.createElement('script');
      	script.src = g_remoteServer+"?synPrimaryKey="+synPrimaryKey+"&field="+field+"&value="+value+"&cmd=rpcfunction&aa_service=<?=$_SESSION["aa_service"]?>&rand="+Math.random();
        script.type = 'text/javascript';
      	script.defer = true;
      	script.id = 'lastLoadedCmds';
      	void(head.appendChild(script));
        debug("<b>RPC:</b> "+g_remoteServer+"?synPrimaryKey="+synPrimaryKey+"&field="+field+"&value="+value+"&cmd=rpcfunction&aa_service=<?=$_SESSION["aa_service"]?>");
      }

      //BUTTON FUNCTION
      action('newBtn',    'window.parent.content.document.location.href="content.php?cmd=<?=ADD?>";');
      action('backBtn',   'window.parent.content.history.back();');
      action('refreshBtn','window.parent.content.location.reload();');
      action('saveBtn',   'window.parent.content.document.forms[0].submit()');
      action('removeBtn', 'if (confirm(top.str["aa_confirmSelDel"])) window.parent.content.document.forms[0].submit();');
      action("homeBtn",   'window.parent.content.location.href="<?=$PHP_SELF?>"');

      var marked_row = new Array;
      var toggle = false;
      function markAllRows(container_id) {//funzione adattata da phpMyAdmin
        var rows = document.getElementById(container_id).getElementsByTagName('tr');
        var unique_id;
        var checkbox;
        for ( var i = 0; i < rows.length; i++ ) {
          checkbox = rows[i].getElementsByTagName( 'input' )[0];
          if ( checkbox && checkbox.type == 'checkbox' ) {
            unique_id = checkbox.name + checkbox.value;
            if (toggle==false) {
              checkbox.checked = true;
              if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
                rows[i].className = 'marked';
                marked_row[unique_id] = true;
              }
            } else {
              checkbox.checked = false;
              rows[i].className = '';
              marked_row[unique_id] = false;
            }
          }
        }
        toggle = (toggle==false ? true : false);
        return true;
      }

      function selectRow(el) {
        var tr = el.parentNode.parentNode;
        if ( el.checked != false ) tr.className = ''; else tr.className = 'marked';
      }
    //]]>
    </script>
  </head>
  <body>
    <div id="content"><?php
    if (file_exists($targetFileName)) include($targetFileName);
    else echo "<p>Function not yet implemented...</p>";
?>
    </div>

    <!--this is a div for the floating img viewer-->
    <div id="popupbox" style="position:absolute"></div>
  </body>
</html>
