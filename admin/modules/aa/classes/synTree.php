<?php
/*************************************
* class TREE                         *
* Create a input type="text" obj     *
**************************************/
class synTree extends synElement {
  var $keyName;
  var $caption;

  //constructor(name, value, label, size, help)
  function synTree($n="", $v=null , $l=null, $s=11, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->keyName="id";
    $this->db    = " INT(".$this->size.") NOT NULL";

    //$this->configuration();
  }

  //private function
  function _html() {
    global $db, $contenitore;
    $hidden = $where = $disable = $p_old = $close = '';
    $table=$this->container->table;
    if ($this->chkTargetMultilang()) $this->multilang=1;

    echo "<script>parent.closeTreeFrame();</script>";

    if ($_SESSION["aa_service"]==4) {
    
      $stackKeys=array_keys($_SESSION["aa_joinStack"]);
      $stackLastKey=$stackKeys[count($stackKeys)-1];
      $value=$_SESSION["aa_joinStack"][$stackLastKey]["value"];
    
      $where = " WHERE `group`=".$value;
    }
    
    //$res=$db->Execute("SELECT id,".$this->caption.",".$this->name." FROM $table $where ORDER BY ".$this->name);
    $res=$db->Execute("SELECT * FROM $table $where ORDER BY ".$this->name);
    $txt="<select name='".$this->name."' $disable >";

    $txt.="<OPTION VALUE=\"0\" selected=\"selected\">[No parent]</option>";
    while ($arr = $res->FetchRow()) {
      $k=$arr["id"];
      $v=$arr[$this->caption];
      $p=$arr[$this->name];
      if (($p!=$p_old and trim($p)!="")) {
        $q="SELECT ".$this->caption." FROM $table WHERE id=".$p;
        $resParent=$db->Execute($q);
        $arrParent=$resParent->FetchRow($q);
        if ($arrParent[0]==0 or $arrParent[0]=="") $p_name="[root]"; 
        else $p_name=$this->translate($arrParent[0]);
        $txt.=$close."<OPTGROUP LABEL=\"".$p_name."\">";
        $close="</OPTGROUP>";
      } 
  
      if (trim(stripslashes(urldecode($_GET["synPrimaryKey"])))!="`id`='".$k."'" and $_GET["synPrimaryKey"]!="`id`=".$k) { 
        if ($this->value==$k) $selected="selected=\"selected\""; else $selected="";
        if ($contenitore->ownerField=="" OR in_array($arr[$contenitore->ownerField],$_SESSION["synGroupChild"])) { 
          $txt.="<OPTION VALUE=\"".$k."\" $selected> ".$this->translate($v)."</option>";
        } else {
          if ($this->value==$k)
            $hidden.="<input type=\"hidden\" VALUE=\"".$k."\" name=\"".$this->name."\"> (".$this->translate($v).")";
        }
      }
      $p_old=$p;
    }
    $txt.=$close;
    $txt.="</select>\n";
    if ($hidden!="") $txt=$hidden;  
    return $txt; 
  }
  
  //get the label of the element
  function getCell() {
    global $db;
    $container=$this->container;
    $key=$container->getKeyString();
    $table=$container->table;
    if ($this->chkTargetMultilang()) $this->multilang=1;
  
      if (isset($this->value)) {  
        $res=$db->Execute("SELECT id,".$this->caption." FROM $table WHERE id=".$this->getValue());
        list ($k, $v) = $res->FetchRow();
        return $this->translate($v);
      } else return "<span style='color:red'>x</span>";
  }
  
  //check if the target service is multilang
  function chkTargetMultilang() {
    global $db;
    $container=$this->container;
    $ret=false;
    foreach($container->element as $k=>$el) {
      if ($this->caption==$el->name)  $ret=$el->multilang;
    }
    return $ret;
  }
  
  //set Caption field
  function setPath($key) {
    $this->caption=$key;
  }
  
  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmPath;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //retrieve the fields name
    //if (is_array($synElmName)) {
      foreach($synElmName as $name) {
        if ($name==$synElmPath[$i]) $selected="selected=\"selected\""; else $selected="";
        $options.="<option value=\"".htmlentities($name)."\" $selected>".htmlentities($name)."</option>";
      }
      $txt="<select name=\"synElmPath[$i]\">$options</select>"; 
      $this->configuration[5]="Caption Field: ".$txt;
    //}

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;
    $_SESSION["synChkKey"][$i]=0;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }

//-------------PRIVATE FUNCTIONS------------------------------------------------  

  function indent($x, $rec){
    $space = "  ";
    $plus  = str_repeat($space, $x);
    $base  = str_repeat($space, $rec+1);
    return $base.$plus;
  }

  function createTree($qry, $id=0, $startingQry="", $recursion=0) {
    global $db,$contenitore,$synLoggedUser;
    $ret      = "";
    $tmpCount = 0;
    $newRec   = $recursion+2;
    
    // calculate indentation based on recursion
    $_   = $this->indent(0, $newRec);
    $__  = $this->indent(1, $newRec);

    if ($this->chkTargetMultilang()) $this->multilang=1;
    if (strpos(strtolower($qry), "where")) $and=" AND "; else $and=" WHERE ";
    if (strpos(strtolower($qry), "order")) 
      $q=str_replace("ORDER", $and.$this->name."=$id ORDER",$qry);
    else $q=$qry.$and.$this->name."=$id";
    if ($startingQry!="") $q=$startingQry;

    $res = $db->Execute($q);
    $totalNodes = $res->RecordCount();

    while ($arr=$res->FetchRow()) {
      $child = "";
      $tmpCount++;

      // build 'Delete' button
      $delete = "<a class=\"delete\" href=\"content.php?cmd=delrow&amp;synPrimaryKey=`id`=".$arr["id"]."\" ";
      $delete.= "target=\"content\" onclick=\"if (confirm('Are you sure to delete?')) return true; else return false;\"><img src=\"img/tree_delete.png\" alt=\"Elimina\" /></a>";

      // build link label
      $label = strip_tags($this->translate($arr[$this->caption]));

      // get possible children
      $childret = $this->createTree($qry, $arr["id"], '', $newRec);
      if ($childret!="") {
        $wrapper = array("<div><span class=\"parent\"></span>", "</div>");
        $child  .= $__."<ul>\n";
        $child  .= $childret;
        $child  .= $__."</ul>\n";
      } else {
        $wrapper = array("<span class=\"child\">", "</span>");
      }

      // put it all together
      $ret.= $_."<li".($tmpCount==$totalNodes ? " class=\"last\"" : "").">\n";
      if ( ($contenitore->ownerField=="" || in_array($arr[$contenitore->ownerField], $_SESSION["synGroupChild"])) && ($synLoggedUser->canModify==1) ) {
        $ret .= $__.$wrapper[0]."<a target=\"content\" href=\"content.php?cmd=modifyrow&amp;synPrimaryKey=`id`=".$arr["id"]."\">".$label."</a> ";
        $ret .= ($synLoggedUser->canDelete==1 ? $delete : "");
        $ret .= $wrapper[1]."\n";
      } else {
        $ret .= $__.$wrapper[0].$label.$wrapper[1];
      }
      $ret .= $child;
      $ret .= $_."</li>\n";
    }
    return $ret;
  }
  
  function getTree($qry,$startingQry) {
    global $db, $str;

?>

  <h2><span>Site map</span></h2>
  <div id="tree-box">
    <h4 class="root"><?php echo $_SERVER['SERVER_NAME']?> <a href="javascript:void(0)" id="trigger"></a></h4>
    <ul id="tree">
<?php echo $this->createTree($qry, 0, $startingQry); ?>
    </ul>
  </div>

<?php
  } // end of PRIVATE FUNCTIONS
} //end of class tree
?>
