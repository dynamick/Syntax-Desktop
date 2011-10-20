<?
  include ("../../../config/cfg.php");
  include ("lib.php");
  include ("class.synPackage.php");
  
  
  $cmd=$_GET["cmd"];
  switch ($cmd) {

    // ----------------------------INSTALL A PACKAGE ---------------------------
    case "install":
      $packageName=$_GET["packageName"];
      $txt.="installing $packageName package";
      //require ($synAbsolutePath.$synPublicPath.$synPackagePath."/".$packageName."/index.php");
      //$package=new $packageName;
      $package=new synPackage($packageName);
      $txt.=$package->install();
      
      $txt.="<p><a href=\"index.php\" >Back</a></p>";
    break;

    // ----------------------------UNINSTALL A PACKAGE -------------------------
    case "uninstall":
      $packageName=$_GET["packageName"];
      $packageId=$_GET["packageId"];
      $txt.="Uninstalling $packageName package...";
      flush();
      //require ($synAbsolutePath.$synPublicPath.$synPackagePath."/".$packageName."/index.php");
      $package=new synPackage($packageName);
      if ($package->uninstall($packageId)) {
        $txt.="<p>package uninstalled</p>";
      }
            
      $txt.="<p><a href=\"index.php\">Back</a></p>";
    break;

    // ----------------------------UNINSTALL A PACKAGE -------------------------
    case "export":
      /*
        _REQUEST["cmd"]	export
        _REQUEST["service"]	
        
        Array
        (
            [0] => 4
            [1] => 116
            [2] => 128
        )
        
        _REQUEST["file"]	
        
        Array
        (
            [0] => /package/function.lang.php
            [1] => /package/function.map.php
            [2] => /package/function.menu.php
        )
        
        _REQUEST["package_name"]	name
        _REQUEST["author_name"]	autor
        _REQUEST["author_email"]	email
        _REQUEST["website"]	web
        _REQUEST["version"]	verion
        _REQUEST["comment"]	scomment
      */
      
      @mkdir($synAbsolutePath.$synPublicPath.$synPackagePath."/".$_REQUEST["package_name"]);
      if (is_array($_REQUEST["file"])) {
        foreach ($_REQUEST["file"] as $f) {
          copy($synAbsolutePath.$synPublicPath.$f, $synAbsolutePath.$synPublicPath.$synPackagePath."/".$_REQUEST["package_name"]."/".basename($f));
        }
      } 
    
      $ini=array();
      $ini["info"]=array(
        "package_name"=>$_REQUEST["package_name"],
        "author_name"=>$_REQUEST["author_name"],
        "author_email"=>$_REQUEST["author_email"],
        "website"=>$_REQUEST["website"],
        "version"=>$_REQUEST["version"],
        "comment"=>$_REQUEST["comment"]
      );
      
    
      if (is_array($_REQUEST["service"]) && count($_REQUEST["service"])>0) {
        $serviceList="";
        foreach ($_REQUEST["service"] as $service) {
          $qry="SELECT * FROM aa_services WHERE id=".$service;
          $res=$db->Execute($qry);
          while ($arr=$res->FetchRow()) {
            $serviceList.=$arr["syntable"].",";
            
            $ini["service_".$arr["syntable"]]=array(
            "name"=>translateDesktop($arr["name"]),
            "icon"=>$arr["icon"],
            "description"=>addslashes(translateDesktop($arr["description"])),
            "syntable"=>$arr["syntable"],
            "dbsync"=>$arr["dbsync"],
            "multilang"=>$arr["multilang"]
            );
            
            $elmId="";
            $qry="SELECT * FROM aa_services_element WHERE container=".$service;
            $resElm=$db->Execute($qry);
            while ($arrElm=$resElm->FetchRow()) {
              $elmId.=$arrElm["id"].",";
          
              $ini["serviceElement_".$arrElm["id"]]=array(
                "name"=>$arrElm["name"],
                "type"=>$arrElm["type"],
                "iskey"=>$arrElm["iskey"],
                "isvisible"=>$arrElm["isvisible"],
                "iseditable"=>$arrElm["iseditable"],
                "label"=>addslashes(translateDesktop($arrElm["label"])),
                "size"=>$arrElm["size"],
                "help"=>addslashes(translateDesktop($arrElm["help"])),
                "path"=>$arrElm["path"],
                "qry"=>$arrElm["qry"],
                "value"=>$arrElm["value"],
                "joins"=>$arrElm["joins"],
                "order"=>$arrElm["order"],
                "filter"=>$arrElm["filter"],
                "ismultilang"=>$arrElm["ismultilang"]
              );
            }
            $ini["service_".$arr["syntable"]]["elements"]=substr($elmId,0,-1);
            
            
          }
        }
        $ini["info"]["serviceList"]=substr($serviceList,0,-1);
      }
                  
        
      if (is_array($_REQUEST["file"])) {
        foreach($_REQUEST["file"] as $f) {
          $fileList.=$f.",";
        }
        $ini["info"]["file"]=substr($fileList,0,-1);
      }
      
      
      $iniFile=$synAbsolutePath.$synPublicPath.$synPackagePath."/".$_REQUEST["package_name"]."/config.ini";
      write_ini_file($ini, $iniFile, true);

      $txt="<h1>Package Exported!</h1>";
      $txt.="<p><a href=\"./index.php\">Back to the package list.</a></p>";
      

    break;
    
    // ----------------------------package LIST ---------------------------------
    default:
    
      // package list
      $synpackageArr=array();
    
      $txt.= "<h3>Manage Packages</h3>";
      $txt.= "\t<ul>\n";
      $arr=synPackageList();
      if (is_array($arr)) {
        foreach ($arr as $name=>$obj) {
          $link=$obj->getInstallLink();
          $txt.= "\t\t<li>".$name."  $link </li>\n";
        }
      }
      $txt.= "\t</ul>\n";
      
      $txt.= "<h3>Export Package</h3>";
      $txt.= "<form action='$PHP_SELF?cmd=export' method='POST' enctype='multipart/form-data'>";
      $txt.="<table cellspacing=\"20\"><tr>";
      $txt.= "<td valign=\"top\"><h4>Services</h4>".synSelectService()."</td>\n";
      $txt.= "<td valign=\"top\"><h4>Files</h4>".synSelectFile()."</td>\n";
      $txt.= "<td valign=\"top\"><h4>Info</h4>\n";
      $txt.= "<p>Package Name: <input type=\"text\" name=\"package_name\" /></p>\n";
      $txt.= "<p>Author Name: <input type=\"text\" name=\"author_name\" /></p>\n";
      $txt.= "<p>Author Email: <input type=\"text\" name=\"author_email\" /></p>\n";
      $txt.= "<p>Website: <input type=\"text\" name=\"website\" /></p>\n";
      $txt.= "<p>Version: <input type=\"text\" name=\"version\" /></p>\n";
      $txt.= "<p>Comment: <textarea name=\"comment\" ></textarea></p>\n";
      $txt.= "<input type=\"submit\" value='NEXT' class='action_button' />";
      $txt.= "</td>\n";
      $txt.="</tr></table>";
      $txt.= "</form>";

    break;
  }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<?=$txt?>

</body>
</html>