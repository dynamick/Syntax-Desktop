<?

/******************************************************************************
***                                  VARIOUS FUNCTIONS
*******************************************************************************/

function synPackageList() {
  global $synAbsolutePath, $synPublicPath, $synPackagePath;
  $dir_name = $synAbsolutePath.$synPublicPath.$synPackagePath; 
  $dir = opendir($dir_name); 
  while ($file_name = readdir($dir)) { 
    $ext=substr($file_name,-4);
    if (($file_name != "." && $file_name != ".." && $file_name!="Thumbs.db")) { 
    	$file = $dir_name.$file_name;
      if (file_exists($dir_name."/".$file_name."/config.ini")) {
    	  $ret[$file_name]=new synPackage($file_name);
      }
    } 
  } 
  return $ret;
}

function synLoadPackage($packageName) {
  global $synPackageArr;
  $synPackageArr[]=new $packageName();
  
}

// generate an insert qry
function generateInsertQry($arr,$table) {
  $qry="";
  if (is_array($arr)) {
    foreach ($arr as $field=>$value) {
      $fieldList.="`$field`, ";
      $valueList.="'$value', ";
    }
    $fieldList=substr($fieldList,0,-2);
    $valueList=substr($valueList,0,-2);
    $qry="INSERT INTO `$table` ($fieldList) VALUES ($valueList)";
  }
  return $qry;
}


function synSelectService() {
  global $db;
  $qry="SELECT * FROM `aa_services`";
  $opt="<option value=\"\">[null]</option>";
  $res2=$db->Execute($qry);
  while ($arr2=$res2->FetchRow()) {
    $opt.="<option value=\"".$arr2["id"]."\">".translateDesktop($arr2["name"])."</option>";
  }
  $ret= "<select multiple=\"true\" name=\"service[]\">".$opt."</select><br/>";
  return $ret;
}

function synSelectFile() {
  global $synAbsolutePath, $synPublicPath, $synPackagePath, $synPluginPath;
  $dir_name = $synAbsolutePath.$synPublicPath.$synPluginPath;
  $dir = opendir($dir_name); 
  while ($file_name = readdir($dir)) { 
    $ext=substr($file_name,-4);
    if (($file_name != "." && $file_name != ".." && $file_name!="Thumbs.db")) { 
    	$path = $dir_name.$file_name;
      $ret.="<input type=\"checkbox\" name=\"file[]\" value=\"".$synPluginPath."/".$file_name."\"> ".$file_name."<br/>";
    } 
  } 
  return $ret;
}

if (!function_exists('write_ini_file')) {
    function write_ini_file($assoc_arr, $path, $has_sections=FALSE) {
        $content = "";

        if ($has_sections) {
            foreach ($assoc_arr as $key=>$elem) {
                $content .= "[".$key."]\n";
                foreach ($elem as $key2=>$elem2) {
                    $content .= $key2." = \"".$elem2."\"\n";
                }
            }
        }
        else {
            foreach ($assoc_arr as $key=>$elem) {
                $content .= $key." = \"".$elem."\"\n";
            }
        }

        if (!$handle = fopen($path, 'w')) {
            return false;
        }
        if (!fwrite($handle, $content)) {
            return false;
        }
        fclose($handle);
        return true;
    }
}

?>