<?php

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION))
  session_start();

include_once ("../../config/cfg.php");
include_once ("classes/synContainer.php");
include_once ("classes/synElement.php");
include_once ("classes/synJoin.php");

$path = null;

//check the authorization
auth();

/*
isset($_GET["aa_service"])?$aa_service=$_GET["aa_service"]:$aa_service=$_SESSION["aa_service"];
$res=$db->Execute("SELECT * FROM `aa_services` WHERE id=$aa_service");
list($id,$nameService,$path,$icon,$description,$parent,$order)=$res->FetchRow(ANYDB_RES_NUM);
if ($icon!="") $icon="<img src=\"$icon\" style=\"float: left;\" /> ";
if ($description!="") $description=" :: ".htmlentities(translateDesktop($description));


if (isset($_SESSION["aa_group_services"])) {
  $res=$db->Execute("SELECT name FROM `aa_group_services` WHERE id=".$_SESSION["aa_group_services"]);
  list($nameGroupService)=$res->FetchRow(ANYDB_RES_NUM);
  $name=$icon.htmlentities(translateDesktop($nameGroupService));
} else
  $name=$icon.htmlentities(translateDesktop($nameService));


if (isset($_SESSION["aa_fromservice"])) {
  $res=$db->Execute("SELECT * FROM `aa_services` WHERE id=".$_SESSION["aa_fromservice"]);
  //die("SELECT * FROM `aa_services` WHERE id=".$_SESSION["aa_fromservice"]);
  $arr=$res->FetchRow();
  $idj=$arr["id"];
  $namej=$arr["name"];
  $tablej=$arr["syntable"];
  $res=$db->Execute("SELECT * FROM `$tablej` WHERE id=".$_REQUEST["aa_value"]);
  $arr=$res->FetchRow();
  $valuej=translateDesktop($arr[1]);
  $from="<div style=\"float: left\"><a href=\"index.php?aa_service=$idj&aa_group_services=".$_SESSION["aa_fromservice_g"]."\" target=\"_parent\">".htmlentities(translateDesktop($namej))." ($valuej)</a> <span style='font-size: xx-small; color: gray;'>&gt;</span> </div>";
}
*/

$link = "index.php?aa_group_services=".$_SESSION["aa_group_services"];
if ( isset($_SESSION["aa_joinStack"])
  && is_array($_SESSION["aa_joinStack"])
  ){
  //$join=new synJoin($_SESSION["aa_joinStack"][0]["idjoin"]);
  //$link="index.php?aa_service=".$join->fromService;
  foreach ($_SESSION["aa_joinStack"] as $v) {
    $joinId = $v["idjoin"];
    $value = $v["value"];
    $join = new synJoin($joinId);
    $path .= "<a href=\"$link\" target=\"_parent\">".$join->getServiceName($join->fromService)."</a> (<strong>".$join->getCaptionValue($value)."</strong>) &gt ";
    $link = "index.php?aa_value=".$value."&aa_idjoin=".$joinId;
  }
}

$qry = "SELECT name,description,syntable FROM aa_services WHERE id=".$_SESSION["aa_service"];
//echo $qry.'<br>';
$res = $db->Execute($qry);
list($name, $description, $table) = $res->FetchRow();
$path .= "<a href=\"".$link."\" target=\"_parent\">".translateDesktop($name)."</a>";
$description = translateDesktop($description);

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Toolbar</title>
  <style type="text/css">
    body{font:10pt sans-serif; margin: 0px;border: 2px solid #BABABA;color: black;
      background: #E5E5E5 url(./images/toolbar-bg.gif) repeat-x top left;}
    a {color: #1C8CD1; text-decoration: none;}
    #close {position: absolute;top: 3px;right: 27px; display:none;}
  </style>
</head>
<body>
  <div >
  <div style="text-align: left; float: left;">
    <img src="images/sep.gif" />
  </div>
  <div style="text-align: left; float: left; margin-left: 10px; margin-top: 3px;margin-bottom: 5px;">
    <span id="servicename"><?= $path;?></span>
    <span style="padding-left: 5px; font-size: xx-small; color: gray" id="servicedescription"><?= $description; ?></span>
  </div>
  <div style="clear: both"></div>
  </div>
</body>
</html>

