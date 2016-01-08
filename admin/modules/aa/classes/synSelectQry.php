<?php

/*************************************
* class SELECT                       *
* Create a input select obj          *
**************************************/
class synSelectQry extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synSelectQry($n="", $v="0", $l="", $s=11, $h="") {
    global $$n;

    if ($n=="") $n = "text".date("his");
    if ($l=="") $l = ucfirst($n);
    $this->type  = "text";
    $this->name  = $n;
    if (isset($$n) and $$n) { $this->selected = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " int(".$this->size.") NOT NULL DEFAULT 0";
  }

  //private function
  function _html() {
    $disable = '';
    if ($this->chkTargetMultilang($this->qry)==1) $this->multilang=1;
    $this->value = $this->createArray($this->qry,$this->path);
    $txt = "<select name='".$this->name."' {$disable} class=\"form-control\">\n";
    if (is_array($this->value)) {
      #while (list($k, $v) = each($this->value)) {
      foreach($this->value AS $k=>$v){
        $selected = ($this->selected==$k) ? ' selected="selected"' : '';
        $txt .= "  <option value=\"{$k}\"{$selected}>".$this->translate($v,true)."</option>\n";
      }
      reset($this->value);
    }
    $txt .= "</select>\n";

    return $txt;
  }

  //sets the value of the element
  function setValue($v) {
    #global $$n;
    #if (!isset($_REQUEST[$$n]))
      $this->value = $this->createArray($this->qry,$this->path);
    $this->selected = $v;
  }

  //sets the value of the element
  function getValue() {
    //return $this->selected;
    return intval($this->selected);
  }

  function getSQLValue() {
    return intval($this->getValue());
  }

  //get the label of the element
  function getCell() {
    if ($this->chkTargetMultilang($this->qry)==1) $this->multilang=1;
    if (is_array($this->value)) {
      if (array_key_exists($this->selected, $this->value))
        return $this->translate($this->value[$this->selected],true);
      else return "<font color='red'>&times;</font>";
    } else return $this->selected;
  }

  function setPath($path) {
    $this->path = $path;
  }

  function setQry($qry) {
    $this->qry = $qry;
  }

  function createArray($qry,$null=false) {
    global $db;
    $qry = $this->compileQry($qry);
    $ret = array();
    $owner = '';

    $table = preg_match("/from (\w+)[\s]?(.*)/i",$qry, $matches);
    $destTable = isset($matches[1]) ? $matches[1] : "";
    if ($destTable!="") {
      $qry1 = "SELECT * FROM aa_services WHERE syntable='{$destTable}'";
      $res  = $db->Execute($qry1);
      if ($res->RecordCount()>0) {
        $arr  = $res->FetchRow();
        $id   = $arr['id'];
        $qry2 = "SELECT ase.*,ae.classname FROM aa_services_element ase JOIN aa_element ae ON ase.type=ae.id WHERE ase.container='{$id}' ORDER BY ase.`order`";
        $res  = $db->Execute($qry2);
        while ($arr = $res->FetchRow()) {
          if (strtolower($arr['classname'])=='synowner') $owner = $arr['name'];
        }
      }
    }

    // ATTENTION: This is a security control. All the "select" widget on the
    // "aa_groups" table are filtered with the allowed groups.
    if ($destTable=="aa_groups") {
      $ret = array_flip($_SESSION["synGroupChild"]);
    } else {
      $res = $db->Execute($qry);
      if ($null==true) $ret[] = '';
      while ($arr=$res->FetchRow()) {
        if ($owner == ''
        || $arr[$owner] == ''
        || in_array($arr[$owner], $_SESSION['synGroupChild'])
        || $this->selected == $arr[0]
        ){
          $ret[$arr[0]] = $arr[1];
        }
      }

    } //end if aa_groups


    return $ret;
  }

  // enhanced & moved to synElement
/*
  //check if the target service is multilang
  function chkTargetMultilang() {
    global $db;
    $ret=false;
    $qry="SELECT * FROM aa_services";
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $table=$arr["syntable"];
      if (@strpos($this->qry,$table)!==false)  $ret=$arr["multilang"];
    }
    return $ret;
  }
*/

  //function for the auto-configuration
  function configuration( $i = '', $k = 99) {
    global
      $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp, $synElmPath, $synElmQry,
      $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;

    $synHtml = new synHtml();
    //parent::configuration();
    $value = (isset($synElmQry[$i])) ? htmlentities($synElmQry[$i]) : NULL;
    $this->configuration[4] = 'Query: ' . $synHtml->text( " name=\"synElmQry[{$i}]\" value=\"{$value}\"" );

    if ( !isset($synElmSize[$i] ) || $synElmSize[$i] == "")
      $synElmSize[$i] = $this->size;
    $this->configuration[6] = "Dimensione: " . $synHtml->text(" name=\"synElmSize[{$i}]\" value=\"{$synElmSize[$i]}\"");

    if (!isset($synElmPath[$i]) || $synElmPath[$i] == "")
      $checked = '';
    else
      $checked = ' checked="checked"';

    $this->configuration[5] = "NULL: " . $synHtml->hidden(" name=\"synElmPath[$i]\" value=\"\"") . $synHtml->check(" name=\"synElmPath[{$i}]\" value=\"1\" {$checked}");
    //enable or disable the 3 check at the last configuration step

    $_SESSION["synChkKey"][$i] = 1;
    $_SESSION["synChkVisible"][$i] = 1;
    $_SESSION["synChkEditable"][$i] = 0;
    $_SESSION["synChkMultilang"][$i] = 0;

    if ( $k == 99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }

} //end of class
?>
