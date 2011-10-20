<?php
  session_start();
  include ("../../config/cfg.php");

  //parse the link to launch.
  function synParse($txt) {
    global $synWebsite;
    if (strpos($txt,"§siteURL§")!==false) $txt=str_replace("§siteURL§",$synWebsite,$txt);
    if ($txt!="") $txt="javascript: createWindow('','".$txt."');";
    return $txt;
  }

  function getService($id,$caption,$idgroupservice) {
    global $db;
    $res=$db->Execute("SELECT * FROM aa_services where id='$id'");
    list($id,$name,$path,$icon,$description,$parent,$order)=$res->FetchRow();
    if ($icon!="") $icon="modules/aa/$icon";
    if ($name!="") $ret=" \"<img src='$icon'>&nbsp;&nbsp; $caption\", \"javascript: createWindow('$caption','modules/aa/index.php?aa_service=$id&aa_group_services=$idgroupservice');\" ";
    else $ret="";
    return $ret;
  }

  function createAdminTree($objFrom, $objTo, $parent=0) {
    global $db;
    $ret="";
    $count=0;
    $first=true;
    $qry="SELECT gs.* FROM aa_group_services gs, aa_users u where u.id_group=gs.group and u.id=".getSynUser()." and gs.parent='$parent' order by `order`";

    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      if ($first) {
        $ret="var menu$parent = $objFrom.addMenu($objTo);\n";
        $first=false;
      }
      $id=$arr["id"];
      $caption=translateDesktop($arr["name"]);
      $service=$arr["service"];
      $link=synParse($arr["link"]);
      if ($arr["icon"]!="") $icon="modules/aa/images/service_icon/".$arr["icon"]; else $icon="modules/aa/images/service_icon/folderxp.gif";

      $serviceScript=getService($service,$caption,$id);
      if ($serviceScript!="") $ret.= "menu$parent.addItem($serviceScript); \n";
      else if ($arr["link"]!="") $ret.= "menu$parent.addItem(\"<img src='$icon'>&nbsp;&nbsp;  $caption\",\"$link\"); \n";
      else $ret.= "menu$parent.addItem(\"<img src='$icon'>&nbsp;&nbsp; $caption\",\"\"); \n";

      $retchild=createAdminTree("menu$parent","menu$parent.items[$count]",$id);
      if ($retchild!="") $ret.= $retchild;
      $count++;
    }
    return $ret;

  }
  //echo nl2br(strip_tags(createTree("ms","m1")));
?>


function createSynMenuItem(id,html) {
  var newElm = document.createElement("div");
  newElm.id=id;
  newElm.innerHTML=html;
  newElm.className="synMenuItem";
  document.getElementById("synMenu").appendChild(newElm);
  return newElm;
}

function createSynMenu() {
  var ms = new TransMenuSet(TransMenu.direction.down, 1, 0, TransMenu.reference.bottomLeft);
<?php
  $qry = "SELECT gs.* FROM aa_group_services gs, aa_users u where u.id_group=gs.group and u.id=".getSynUser()." and gs.parent='0' order by `order`";
  $res = $db->Execute($qry);
  $txt = '';
  while ($arr=$res->FetchRow()) {
    $id=$arr["id"];
    $caption=translateDesktop($arr["name"]);
    echo "  var menuContent$id=createSynMenuItem(\"contentmenu$id\",\"$caption\");\n";
    $txt .= "	menuContent$id.onactivate = function() { alert('active');document.getElementById(\"contentmenu$id\").className = \"hover\"; };\n";
		$txt .= "	menuContent$id.ondeactivate = function() { document.getElementById(\"contentmenu$id\").className = \"\"; };\n";
    echo createAdminTree("ms","menuContent$id",$id);
  }
?>
  TransMenu.renderAll();

}


//////////////////////////////////////////
//          VARIOUS FUNCTIONS            //
//////////////////////////////////////////
function callDumpServer() {
  var g_remoteServer = 'modules/dump/export.php';
	var head = document.getElementsByTagName('head').item(0);
	var old  = document.getElementById('lastLoadedCmds');
	if (old) head.removeChild(old);

	script = document.createElement('script');
	script.src = g_remoteServer+"?rand="+Math.random();
  script.type = 'text/javascript';
	script.defer = true;
	script.id = 'lastLoadedCmds';
	void(head.appendChild(script));
}
function changeStyle(style) {
  //Menu.prototype.cssFile = "styles/"+style+"/desktop.css";
  //setActiveStyleSheet(style);
  location.reload();
}

