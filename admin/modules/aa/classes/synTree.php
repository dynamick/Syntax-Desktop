<?php
/*************************************
* class TREE                         *
* Create a input type="text" obj     *
**************************************/
class synTree extends synElement {
  var $keyName;
  var $caption;

  //constructor(name, value, label, size, help)
  function synTree($n='', $v=null , $l=null, $s=11, $h='') {
    if ($n=='')
      $n = "text".date('his');

    if ($l=='')
      $l = ucfirst($n);

    $this->type    = "text";
    $this->name    = $n;
    if ($v==null) {
      global $$n;
      $this->value = $$n;
    } else {
      $this->value = $v;
    }
    $this->label   = $l;
    $this->size    = $s;
    $this->help    = $h;
    $this->keyName = "id";
    $this->db      = " INT(".$this->size.") NOT NULL";
    $this->hidden  = '';
    //$this->configuration();
  }


  function _html() {
    if ($this->chkTargetMultilang())
      $this->multilang = 1;

    echo "<script type=\"text/javascript\">parent.closeTreeFrame();</script>\n";

    $txt  = "<select name=\"{$this->name}\" class=\"tree-select\">\n";
    $txt .= "  <option value=\"0\">[No parent]</option>\n";
    $txt .= $this->createOptionsArray();
    $txt .= "</select>\n";

    //$txt .= "<pre>".htmlspecialchars($txt)."</pre>";

    if ($this->hidden!='')
      $txt = $this->hidden;

    return $txt;
  }


  // returns selectable options
  function createOptionsArray($parent='', $recursion=-1, $unselectable_children=0) {
    global $db, $contenitore;

    $recursion ++;
    $ret       = '';
    $myself    = 0;
    $table     = $this->container->table;
    $clause    = '';
    $join      = '';
    $joinfield = '';
    $synPrimaryKey = 0;
    //$synPrimaryKey = preg_filter('/(\D+)/', '', $_GET['synPrimaryKey']); //php 5.3+
    if (isset($_GET['synPrimaryKey'])) {
      $synPrimaryKey = preg_replace('/(\D+)/', '', $_GET['synPrimaryKey']);
    }

    if($this->multilang){
      $joinfield = ", t.{$this->getlang()} AS {$this->caption} ";
      $join = " LEFT JOIN aa_translation t ON {$this->caption} = t.id ";
    }

    if ($_SESSION['aa_service']==4) {
      // menu services
      $stackKeys = array_keys($_SESSION["aa_joinStack"]);
      $stackLastKey = $stackKeys[count($stackKeys)-1];
      $value = $_SESSION["aa_joinStack"][$stackLastKey]["value"];
      $clause = " AND `group`={$value}";
    }

    $qry = "SELECT {$table}.* {$joinfield} FROM {$table}{$join} WHERE {$this->name}='{$parent}'{$clause}";
    if (isset($_SESSION["aa_order"]))
      $qry .= " ORDER BY {$table}.`{$_SESSION["aa_order"]}`";

    //echo "query: {$qry}<br>";

    $res  = $db->Execute($qry);
    while($arr = $res->fetchrow()){
      //$indent = str_repeat('+-', $recursion);
      $indent = str_repeat('&nbsp;&nbsp;', $recursion);

      if ($synPrimaryKey == $arr['id']){
        $myself = 1; // found myself
      } elseif (!$unselectable_children){
        $myself = 0; // not me nor my children
      }

      // select current parent
      $selected = ($this->value==$arr['id']) ? ' selected="selected"' : '';

      // me and my children can't be selected
      $disabled = ($unselectable_children || $myself) ? ' disabled="disabled"' : '';

      // check ownership
      if ( !isset($contenitore->ownerField)
        || ($contenitore->ownerField == '')
        || in_array($arr[$contenitore->ownerField], $_SESSION["synGroupChild"])
        ){
        $ret .= "  <option value=\"{$arr['id']}\"{$selected}{$disabled}>{$indent}{$arr[$this->caption]}</option>\n";
      } else {
        // user didn't own the parent
        if ($selected)
          $this->hidden = "<input type=\"hidden\" value=\"{$arr['id']}\" name=\"{$this->name}\"> ({$arr[$this->caption]})";
      }

      if($children = $this->createOptionsArray($arr['id'], $recursion, ($unselectable_children+$myself))){
        $ret .= $children; // add my children
      }
    }
    return $ret;
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
      } else return "<span style='color:red'>&times;</span>";
  }

  //check if the target service is multilang
  function chkTargetMultilang($val='') {
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
      $options = "";
      foreach($synElmName as $name) {
        if (isset($synElmPath[$i]) and $name==$synElmPath[$i]) $selected="selected=\"selected\""; else $selected="";
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
      if ( (!isset($contenitore->ownerField) || $contenitore->ownerField=="" || in_array($arr[$contenitore->ownerField], $_SESSION["synGroupChild"])) && ($synLoggedUser->canModify==1) ) {
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

}

//end of class tree