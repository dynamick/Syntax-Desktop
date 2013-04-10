<?php
class synPackage {
  var $is_installed;
  var $ini;

	function synPackage($packageName) {
    global $db,$synAbsolutePath,$synPublicPath,$synPackagePath;
    $this->ini=parse_ini_file($synAbsolutePath.$synPublicPath.$synPackagePath."/".$packageName."/config.ini",true);  
    //$this->is_installed=$this->check_install();
    //return $this;
	} 
  
  function getInstallLink() {
    $link  = "<a href=\"?cmd=uninstall&packageName=".$this->ini["info"]["package_name"]."\">Uninstall</a> ";
    $link .= "<a href=\"./?cmd=install&packageName=".$this->ini["info"]["package_name"]."\">Install</a> ";
    return $link;
  }
  
  function check_install() {
    global $db;
/*
    // service already installed
    $qry="SELECT * FROM aa_services WHERE syntable='".$this->ini["plugin_name"]."'";
    $res=$db->Execute($qry);
    if ($res->RecordCount()>0) {
      $arr=$res->FetchRow();
      return $arr["id"];
    }
    else return false;
*/
  } // end of check_install  
  
  function install() {
    global $db,$synAbsolutePath,$synPublicPath,$synPackagePath;
    $step=$_POST["step"];
    //$serviceData=$this->getServiceData();

    switch ($step) {
      case "10":
        //install the services
        if ($this->ini["info"]["serviceList"]!="") {
          $serviceArr=explode(",",$this->ini["info"]["serviceList"]);
          foreach ($serviceArr as $service) {

            $installService["name"]=insertTranslation($this->ini["service_".$service]["name"]);
            $installService["icon"]=$this->ini["service_".$service]["icon"];
            $installService["description"]=insertTranslation($this->ini["service_".$service]["description"]);
            $installService["syntable"]=$this->ini["service_".$service]["syntable"];
            $installService["dbsync"]=$this->ini["service_".$service]["dbsync"];
            $installService["multilang"]=$this->ini["service_".$service]["multilang"];
    
            $qry=generateInsertQry($installService,"aa_services");
            $db->Execute($qry);
            $this->ini["service_".$service]["id"]=$db->Insert_ID();
            echo "<p>inserted service id: ".$this->ini["service_".$service]["id"]."##</p>";
            
            //install the service elements
            $serviceElementArr=explode(",",$this->ini["service_".$service]["elements"]);
            if (is_array($serviceElementArr)) {
              foreach ($serviceElementArr as $i) {
                $install=array();
                $install["container"]=$this->ini["service_".$service]["id"];
                $install["name"]=$this->ini["serviceElement_".$i]["name"];
                $install["type"]=$this->ini["serviceElement_".$i]["type"];
                $install["iskey"]=$this->ini["serviceElement_".$i]["iskey"];  
                $install["isvisible"]=$this->ini["serviceElement_".$i]["isvisible"];  
                $install["iseditable"]=$this->ini["serviceElement_".$i]["iseditable"];  
                $install["label"]=insertTranslation($this->ini["serviceElement_".$i]["label"]);  
                $install["size"]=$this->ini["serviceElement_".$i]["size"];  
                $install["help"]=insertTranslation($this->ini["serviceElement_".$i]["help"]);  
                $install["path"]=$this->ini["serviceElement_".$i]["path"];  
                $install["qry"]=$this->ini["serviceElement_".$i]["qry"];  
                $install["value"]=$this->ini["serviceElement_".$i]["value"];
                $install["joins"]=$this->ini["serviceElement_".$i]["joins"]; 
                $install["order"]=$this->ini["serviceElement_".$i]["order"]; 
                $install["filter"]=$this->ini["serviceElement_".$i]["filter"]; 
                $install["ismultilang"]=$this->ini["serviceElement_".$i]["ismultilang"];
                $qry=generateInsertQry($install,"aa_services_element");
                $db->Execute($qry);
                $this->ini["serviceElement_".$i]["id"]=$db->Insert_ID();
              }
            }
            
            //add service to group
            $service=$this->ini["service_".$service]["id"];
            $idname=$installService["name"];
            print_r($_POST["group"]);
            if (is_array($_POST["group"]) ) {
              foreach ($_POST["group"][$installService["syntable"]] as $g) {
                $startingPoint=$_POST["startingPoint"][$g];
                $db->Execute("INSERT INTO aa_group_services (`name`,`group`,`service`,`parent`,`insert`,`modify`,`delete`) VALUES ('$idname','$g','$service',$startingPoint,1,1,1)");
              } 
            }			

          } // end of foreach
            
        } // end of if services
        
        //copy the file
        $fileArr=explode(",",$this->ini["file"]);
        if (is_array($fileArr)) {
          foreach ($fileArr as $file) {
            $source=$synAbsolutePath.$synPublicPath.$synPackagePath."/".$this->info["plugin_name"]."/".basename($file);
            $destination=$synAbsolutePath.$synPublicPath."/".$file;
            copy($source,$destination);
          }
        }
        
      break;
      
      default:
        $txt.= "<form action='$PHP_SELF?cmd=install&packageName=".$_GET["packageName"]."' method='POST' enctype='multipart/form-data'>";
        $txt.= "<p>".$str["service2groups"]."</p>";

        if ($this->ini["info"]["serviceList"]!="") {
          $serviceArr=explode(",",$this->ini["info"]["serviceList"]);
          foreach ($serviceArr as $service) {

            $txt.="<h4>".$this->ini["service_".$service]["syntable"]."</h4> ";
            $qry="SELECT * FROM aa_groups ORDER BY name";
            $res=$db->Execute($qry);
            while (list($idgroup,$namegroup)=$res->FetchRow()) {
              $txt.= "<input type=\"checkbox\" name=\"group[".$this->ini["service_".$service]["syntable"]."][]\" value=\"$idgroup\" /> <strong>$namegroup</strong> ";
    
              $qry="SELECT * FROM `aa_group_services` WHERE `group`=".$idgroup." ORDER BY parent";
              $opt="";
              $res2=$db->Execute($qry);
              while ($arr2=$res2->FetchRow()) {
                $opt.="<option value=\"".$arr2["id"]."\">".translate($arr2["name"])."</option>";
              }
              $txt.= "- Parent menu item: <select name=\"startingPoint[".$idgroup."]\">".$opt."</select><br/>";
            }
          }
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
