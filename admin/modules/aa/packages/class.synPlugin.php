<?php
class synPlugin {
  var $is_installed;
  var $info;
  var $file;
  var $service;
  var $serviceElement;

	function synPlugin($pluginName) {
    global $db,$synAbsolutePath,$synPublicPath,$synPackagePath;
    $conf=file_get_contents($synAbsolutePath.$synPublicPath.$synPackagePath."/".$pluginName."/config.inc.php");  
    eval($conf);
    $this->is_installed=$this->check_install();
    //return $this;
	} 
  
  function getInstallLink() {
    if ($this->is_installed) $link="<a href=\"?cmd=uninstall&pluginId=".$this->is_installed."&pluginName=".$this->info["plugin_name"]."\">Uninstall</a>";
    else $link="<a href=\"./?cmd=install&pluginName=".$this->info["plugin_name"]."\">Install</a>";
    return $link;
  }
  
  function check_install() {
    global $db;

    // service already installed
    $qry="SELECT * FROM aa_services WHERE syntable='".$this->info["plugin_name"]."'";
    $res=$db->Execute($qry);
    if ($res->RecordCount()>0) {
      $arr=$res->FetchRow();
      return $arr["id"];
    }
    else return false;
  } // end of check_install  
  
  function install() {
    global $db,$synAbsolutePath,$synPublicPath,$synPackagePath;
    $step=$_POST["step"];
    //$serviceData=$this->getServiceData();

    switch ($step) {
      case "10":
        //install the service
        $installService["name"]=insertTranslation($this->service["name"]);
        $installService["icon"]=$this->service["icon"];
        $installService["description"]=insertTranslation($this->service["description"]);
        $installService["syntable"]=$this->service["syntable"];
        $installService["dbsync"]=$this->service["dbsync"];
        $installService["multilang"]=$this->service["multilang"];

        $qry=generateInsertQry($installService,"aa_services");
        $db->Execute($qry);
        $this->service["id"]=$db->Insert_ID();
        echo $serviceId."##";
        
        //install the service elements
        if (is_array($this->serviceElement)) {
          foreach ($this->serviceElement as $index=>$se) {
            $install=array();
            $install["container"]=$this->service["id"];
            $install["name"]=$se["name"];
            $install["type"]=$se["type"];
            $install["iskey"]=$se["iskey"];  
            $install["isvisible"]=$se["isvisible"];  
            $install["iseditable"]=$se["iseditable"];  
            $install["label"]=insertTranslation($se["label"]);  
            $install["size"]=$se["size"];  
            $install["help"]=insertTranslation($se["help"]);  
            $install["path"]=$se["path"];  
            $install["qry"]=$se["qry"];  
            $install["value"]=$se["value"];
            $install["joins"]=$se["joins"]; 
            $install["order"]=$se["order"]; 
            $install["filter"]=$se["filter"]; 
            $install["ismultilang"]=$se["ismultilang"];
            $qry=generateInsertQry($install,"aa_services_element");
            $db->Execute($qry);
            $this->serviceElement[$index]["id"]=$db->Insert_ID();
          }
        }
        
        //add service to group
        $service=$this->service["id"];
        if (is_array($_POST["group"]) ) {
          foreach ($_POST["group"] as $g) {
            $idname=$installService["name"];
            $startingPoint=$_POST["startingPoint"][$g];
            $db->Execute("INSERT INTO aa_group_services (`name`,`group`,`service`,`parent`,`insert`,`modify`,`delete`) VALUES ('$idname','$g','$service',$startingPoint,1,1,1)");
          } 
        }			
        
        //copy the file
        if (is_array($this->file)) {
          foreach ($this->file as $file=>$destination) {
            $source=$synAbsolutePath.$synPublicPath.$synPackagePath."/".$this->info["plugin_name"]."/".$file;
            $destination=$synAbsolutePath.$synPublicPath.$destination."/".$file;
            copy($source,$destination);
          }
        }
        
      break;
      
      default:
        $txt.= "<form action='$PHP_SELF?cmd=install&pluginName=".$_GET["pluginName"]."' method='POST' enctype='multipart/form-data'>";
        $txt.= "<p>".$str["service2groups"]."</p>";
        $qry="SELECT * FROM aa_groups ORDER BY name";
        $res=$db->Execute($qry);
        while (list($idgroup,$namegroup)=$res->FetchRow()) {
          $txt.= "<input type=\"checkbox\" name=\"group[]\" value=\"$idgroup\" /> <strong>$namegroup</strong> ";

          $qry="SELECT * FROM `aa_group_services` WHERE `group`=".$idgroup." ORDER BY parent";
          $opt="";
          $res2=$db->Execute($qry);
          while ($arr2=$res2->FetchRow()) {
            $opt.="<option value=\"".$arr2["id"]."\">".translate($arr2["name"])."</option>";
          }
          $txt.= "- Parent menu item: <select name=\"startingPoint[".$idgroup."]\">".$opt."</select><br/>";
        }
        $txt.= "<input type=\"submit\" value='NEXT' class='action_button' />";
        $txt.= "<input type=\"hidden\" name=\"step\" value='10'  />";
        $txt.= "</form>";

      break;
    }


    return $txt;
  }


  function uninstall($serviceId) {
    global $db,$synAbsolutePath,$synPublicPath;
    $qry="DELETE FROM aa_services WHERE id=".$serviceId;
    $res=$db->Execute($qry);
    $qry="DELETE FROM aa_services_element WHERE container=".$serviceId;
    $res=$db->Execute($qry);
    $qry="DELETE FROM aa_group_services  WHERE service=".$serviceId;
    $res=$db->Execute($qry);

    //remove the copied file
    if (is_array($this->file)) {
      foreach ($this->file as $file=>$destination) {
        unlink($synAbsolutePath.$synPublicPath.$destination."/".$file);
      }
    }
    
    return true;
  }
  
}
?>
