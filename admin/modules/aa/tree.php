<?php
  session_start();
  include_once ("../../config/cfg.php");
  include_once ("classes/synContainer.php");

  define ("MODIFY", "modifyrow");
  define ("CHANGE", "changerow");
  define ("ADD", "addrow");
  define ("INSERT", "insertrow");
  define ("DELETE", "delrow");
  define ("MULTIPLEDELETE", "delmultrow");
  define ("RPC", "rpcfunction");

  //check the authorization
  auth();

  //load the lang settings
  lang($_SESSION["synUser"],$str);

  isset($_REQUEST["aa_service"])? $aa_service=$_REQUEST["aa_service"] : $aa_service=$_SESSION["aa_service"];
  $res = $db->Execute("SELECT path FROM aa_services WHERE id=".$aa_service);
  list ($targetFileName)=$res->FetchRow();
  if ($targetFileName=="") $targetFileName="ihtml/auto_service.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title> </title>
    <meta http-equiv="content-type"         content="text/html; charset=utf-8" />
    <meta http-equiv="content-language"     content="en" />
    <meta http-equiv="imagetoolbar"         content="no" />
    <meta http-equiv="msthemecompatible"    content="yes" />
    <meta http-equiv="content-script-type"  content="text/javascript" />
    <meta name="robots"                     content="noindex, nofollow" />
    <style type="text/css">
    html, body {margin:0; padding:0; height:100%;}
    body {background:#f5f5f5 url('img/sfondo_left.png') repeat-y 100% 0; font:11px/18px Arial,Helvetica,sans-serif;}
    a {text-decoration:none; color:#1C8CD1;}
    a:hover {/*background-color:#fff; color:#333;*/ /*text-decoration:underline;*/ color:#BC4305;}
    a:focus {outline: none;}
    a img {border:none;}
    p {margin:0 0 1em 0; padding:0}
    h1 {margin:0 0 .7em 0; padding:0}
    form {margin:0; padding:0}

    h2 {margin:0; padding:0 4px; line-height:28px; font-size:12px; background:#CDCDCD url('img/tool_sfondo.png') repeat-x 0 50%; border:1px outset #f0f0f0; text-align:left;}
    h2 span {display:block; padding-left:16px; background: url('img/tool_zigrinatura.png') no-repeat 0 50%;}

    #tree-box {padding:0 10px; width:210px;}
    h4.root {position:relative; margin:10px 0 0; padding-left:20px; background:url('img/tree_home.png') no-repeat; font-size:11px; white-space:nowrap;}
    h4.root a {position:absolute; right:0; width:16px; height:16px; background-repeat:no-repeat; background-position:0 0;}
    h4.root a.opener {background-image:url('img/tree_explode.png');}
    h4.root a.closer {background-image:url('img/tree_implode.png');}
    ul#tree {margin:0; padding:0; list-style-type:none; width:100%; overflow:hidden;}
    ul#tree ul {margin:0 0 0 13px; padding:0; list-style-type:none;}
    ul#tree li {padding-left:3px; background:url('img/tree_line.png') repeat-y; line-height:18px; /*white-space:nowrap;*/}
    ul#tree li:last-child, ul#tree li.last {background:url('img/tree_last.png') no-repeat;}

    ul#tree li div {padding-left:34px; background-repeat:no-repeat; background-position:20px 16px;}
    ul#tree li span {display:block;}
    ul#tree li span.child {padding-left:30px; background:url('img/tree_child.png') no-repeat 0 0;}
    ul#tree li span.parent {float:left; margin-left:-34px; padding-left:34px; height:18px; background-image:url('img/tree_parent.png'); background-repeat:no-repeat; cursor:pointer; }
    ul#tree li.open ul {display:block;}
    ul#tree li.open div {background-image:url('img/tree_parent_line.png');}
    ul#tree li.open span.parent {background-position:0 -18px;}
    ul#tree li.closed ul {display:none;}
    ul#tree li.closed div {background-image:none;}
    ul#tree li.closed span.parent {background-position:0 0;}
    ul#tree li a.delete img {vertical-align:middle}
    ul#tree li, ul#tree span {zoom:1 /*hack per IE6*/}
    </style>
<!--[if lt IE 7]>
    <style type="text/css">
    ul#tree li span.parent {display:inline;}
    ul#tree, h4.root {zoom:1;}
    ul#tree li span.parent {position:relative; zoom:1;}
    ul#tree li span.child {clear:both; zoom:1;}
    </style>
<![endif]-->
    <script type="text/javascript">
//<![CDATA[
window.onload = function() {
  var items = new Array;
  var counter = 0;
  var tree = document.getElementById('tree');
  if (tree) {
    var lis = tree.getElementsByTagName('li');
    for (i=0; i<lis.length; i++) {
      el = lis[i].childNodes;
      for (e=0; e<el.length; e++) {
        if (el[e].tagName=='DIV') { // se è un div
          var lospan = el[e].childNodes[0]; // allora seleziono il primo child
          lospan.onclick = function() {// apro/chiudo il ramo
            var genitore = this.parentNode.parentNode;
            if (/closed/.test(genitore.className)) {
              genitore.className = genitore.className.replace('closed', 'open');
            } else {
              genitore.className = genitore.className.replace('open', 'closed');
            }
          }
        }
      }
    }
    blossom(tree, 'collapse', true); //chiudo tutto tranne il livello 1

    /* pulsante apri/chiudi tutto*/
    var trigger = document.getElementById('trigger');
    trigger.className = 'closer';
    trigger.title = 'Chiudi tutto';
    trigger.onclick = function() {
      if (this.className=='opener') {
        blossom(document.getElementById('tree'), 'expand', false);
        this.className = 'closer';
        this.title = 'Chiudi tutto';
      } else {
        blossom(document.getElementById('tree'), 'collapse', false);
        this.className = 'opener';
        this.title = 'Apri tutto';
      }
    }
  }
}

function blossom(obj, action, except) {
  var lis = obj.getElementsByTagName('li');
  for (i=0; i<lis.length; i++) {
    if(action=='collapse') {//chiude tutto
      //se specificato lascio aperto il primo livello
      if (except && (lis[i].parentNode.getAttribute('id')=='tree')) {
        lis[i].className = lis[i].className.replace(/closed|open/g, '')+' open';
      } else {
        lis[i].className = lis[i].className.replace(/closed|open/g, '')+' closed';
      }
    } else {//apre tutto
      lis[i].className = lis[i].className.replace(/closed|open/g, '')+' open';
    }
  }
}
//]]>
    </script>
  </head>
  <body>

<?php
    $treeFrame="true";

    if (file_exists($targetFileName)) {
      include($targetFileName);
    }else {
      echo "Function not yet implemented...";
    }
?>
  </body>
</html>
