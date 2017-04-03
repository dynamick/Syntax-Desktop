<?php

// HTTP headers for no cache etc
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if (!isset($_SESSION))
  session_start();

include_once ('../../config/cfg.php');
include_once ('classes/synContainer.php');
include_once ('classes/synElement.php');
include_once ('classes/synJoin.php');

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

$path = '';
$link = 'index.php?aa_group_services=' . $_SESSION['aa_group_services'];
if ( isset($_SESSION['aa_joinStack'])
  && is_array($_SESSION['aa_joinStack'])
  ){
  //$join=new synJoin($_SESSION["aa_joinStack"][0]["idjoin"]);
  //$link="index.php?aa_service=".$join->fromService;
  foreach ($_SESSION['aa_joinStack'] as $v) {
    $joinId = $v['idjoin'];
    $value  = $v['value'];
    $join   = new synJoin($joinId);
    $path  .= "<a href=\"{$link}\" target=\"_parent\">".$join->getServiceName($join->fromService)."</a> (<strong>".$join->getCaptionValue($value)."</strong>) &gt ";
    $link   = "index.php?aa_value={$value}&aa_idjoin={$joinId}";
  }
}

$qry = "SELECT name, description, syntable FROM aa_services WHERE id='{$_SESSION["aa_service"]}'";
$res = $db->Execute($qry);
list($name, $description, $table) = $res->FetchRow();
$path .= "<a href=\"{$link}\" target=\"_parent\">".translateDesktop($name)."</a>";
$description = translateDesktop( $description );

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Toolbar Frame</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:700,300,600,400&amp;subset=latin,cyrillic">
  <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/syntax.css" />
</head>
<body>
  <ol class="breadcrumb">
    <li><?= $path; ?></li>
    <li class="active"><?= $description; ?></li>
  </ol>
</body>
</html>