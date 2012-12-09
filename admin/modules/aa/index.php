<?php
session_start();
include_once ("../../config/cfg.php");
include_once ("classes/synContainer.php");
include_once ("classes/synElement.php");
include_once ("classes/synJoin.php");

// *********************
// Join Stack management
// *********************

if (isset($_GET["aa_idjoin"])) {
  $stack=array(
    "idjoin"=>$_GET["aa_idjoin"],
    "value"=>$_GET["aa_value"]
  );
  
  $toRemove=false;
  if (isset($_SESSION["aa_joinStack"]) and is_array($_SESSION["aa_joinStack"])){
    foreach ($_SESSION["aa_joinStack"] as $k=>$s) {
      if  ( $s["idjoin"] == $_GET["aa_idjoin"] && $s["value"]  == $_GET["aa_value"] ) $toRemove=true;
      if ($toRemove) unset($_SESSION["aa_joinStack"][$k]);
    }
  }
    
  $_SESSION["aa_joinStack"][]=$stack;

  $join=new synJoin($_GET["aa_idjoin"]);
  $_SESSION["aa_service"]=$join->toService;
  
  //rimuovo le variabili di sessione che regolano il meccanismo dell'ordinamento
  unset($_SESSION["aa_order"]);  
  unset($_SESSION["aa_order_direction"]);  
  //rimuovo le variabili di sessione che regolano le ricerche
  unset($_SESSION["aa_qry"]);
  //rimuovo le variabili di sessione della paginazione
  unset($_SESSION["syntax_curr_page"]);
  
}


// *********************
// Changing a service
// *********************
if (isset($_GET["aa_group_services"])) {

/*
  if (isset($_GET["aa_join"])) {
    $_SESSION["aa_fromservice"]=$_SESSION["aa_service"];
    $_SESSION["aa_fromservice_g"]=$_SESSION["aa_group_services"];
    if (!isset($_SESSION["aa_join"])) $_SESSION["aa_join"]=$_GET["aa_join"];
    if (!isset($_SESSION["aa_value"])) $_SESSION["aa_value"]=$_GET["aa_value"];
    if (!isset($_SESSION["aa_jointable"])) $_SESSION["aa_jointable"]=$synTable;

  } else {
    //rimuovo le variabili di sessione che regolano il meccanismo di join
    unset($_SESSION["aa_join"]); unset($_SESSION["aa_value"]); 
    unset($_SESSION["aa_jointable"]); unset($_SESSION["aa_fromservice"]);
    //rimuovo le variabili di sessione che regolano il meccanismo dell'ordinamento
    unset($_SESSION["aa_order"]);  
    unset($_SESSION["aa_order_direction"]);  
    //rimuovo le variabili di sessione che regolano le ricerche
    unset($_SESSION["aa_qry"]);
    //rimuovo le variabili di sessione della paginazione
    unset($_SESSION["syntax_curr_page"]);
    
  }
*/
  $_SESSION["aa_service"] = extractService($_GET["aa_group_services"]);
  $_SESSION["aa_group_services"] = $_GET["aa_group_services"];
  unset($_SESSION["aa_joinStack"]);
  //rimuovo le variabili di sessione che regolano il meccanismo dell'ordinamento
  unset($_SESSION["aa_order"]);  
  unset($_SESSION["aa_order_direction"]);  
  //rimuovo le variabili di sessione che regolano le ricerche
  unset($_SESSION["aa_qry"]);
  //rimuovo le variabili di sessione della paginazione
  unset($_SESSION["syntax_curr_page"]);
}

?>
<html>
<head>
    <script>
      
      var fixedWidth=230;     //left frame width
      var treeFrameWidth=230; //tree frame width
    //  var treeFramePosition=0;//actually tree frame width
      var sxLoaded=false;
      var firstTime=true;
      var step=10;
      var time=(Math.PI);
      
      function optionFrame() {
        document.getElementById("option").src="option.php?<?=getenv("QUERY_STRING")?>";
        sxLoaded=true;
      }
    
      function contentFrame() {
        if (sxLoaded==true) {
          document.getElementById("content").src="content.php?<?=getenv("QUERY_STRING")?>";
        }
      }
    
      function treeFrame() {
        if (firstTime==true && sxLoaded==true) {
          document.getElementById("tree").src="tree.php?<?=getenv("QUERY_STRING")?>";
          firstTime=false;
        }
      }
      function refreshTreeFrame() {
        window.tree.location.reload();
      }
      
      function openTreeFrame() {
      	if (time>=0) {
      		treeFramePosition=treeFrameWidth*Math.pow((Math.cos(time)+1)/2,3);
          document.getElementById("framesetBottom").cols=treeFramePosition+",*,"+fixedWidth;
          time-=.2;
      		timer= setTimeout("openTreeFrame()",0)
      	}
      	else {
          time=0;
          document.getElementById("framesetBottom").cols=treeFrameWidth+",*,"+fixedWidth;
      		clearTimeout(timer);
      	}
      }
    
      function closeTreeFrame() {
      	if (time<=Math.PI) {
      		treeFramePosition=treeFrameWidth*Math.pow((Math.cos(time)+1)/2,3);
          document.getElementById("framesetBottom").cols=treeFramePosition+",*,"+fixedWidth;
          time+=.2;
      		timer= setTimeout("closeTreeFrame()",0);
      	}
      	else {
          time=Math.PI;
          document.getElementById("framesetBottom").cols="0,*,"+fixedWidth;
      		clearTimeout(timer);
      	}
      }
    
    </script>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="imagetoolbar" content="no" />
</head>
<frameset name='framesetTop' id='framesetTop' rows="28,*" frameborder="0" framespacing="0" style="width: 100%; height: 100%" >
  <frame name="frameToolbar" scrolling="no" src="toolbar.php?<?=getenv("QUERY_STRING")?>" onload="optionFrame();">
  <frameset id='framesetBottom' cols="0,*,230" rows="*" >
    <frame name="tree" id="tree">    
    <frame name="content" id="content" onload="treeFrame();">    
    <frame name="option"  id="option"  onload="contentFrame();">
  </frameset>
</frameset>
</html>
