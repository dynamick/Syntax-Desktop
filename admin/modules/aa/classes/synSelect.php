<?php

/*************************************
* class SELECT                       *
* Create a input select obj          *
**************************************/
class synSelect extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synSelect($n="", $v="", $l="", $s=11, $h="") {
    global $$n;
    
    if ($n=="") 
      $n =  "text".date("his");
    if ($l=="") 
      $l =  ucfirst($n);
    $this->type = "file";
    $this->name  = $n;
    if (isset($$n) and $$n) 
      $this->selected = $$n; 
    else 
      $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    //$this->db    = " int(".$this->size.") NOT NULL";
    $this->db    = " INT(".$this->size.") NOT NULL DEFAULT 0";
  }

  //private function
  function _html() {
    $disable = "";
    $selected = "";
    $this->value = $this->createArray($this->qry,$this->path);
    if ($this->chkTargetMultilang()==1) 
      $this->multilang=1;
    $txt = "<select name=\"{$this->name}\" {$disable} class=\"form-control\">";
    if (is_array($this->value)) {
      foreach ($this->value as $k=>$v) {
        $selected = ($this->selected == $k) ? 'selected="selected"' : NULL;
        $label = ($this->multilang==1) ? $this->translate($v, true) : $v;
        $txt .= "<option value=\"{$k}\" {$selected}>{$label}</option>";
      }
    } 
    $txt .= "</select>\n";

    return $txt; 
  }
  
  //sets the value of the element
  function setValue($v) {
    global $n, $$n;
    //if (is_array($v)) $this->value = $v;
    if (!isset($_REQUEST[$$n])) 
      $this->value = $this->createArray($this->qry, $this->path);
    $this->selected = $v;
  }  

  //sets the value of the element
  function getValue() {
    return $this->selected;
  }  

  function getSQLValue() {
    return intval($this->getValue());
  } 


  //get the label of the element
  function getCell() {
    if ($this->chkTargetMultilang()==1) $this->multilang=1;
    if (is_array($this->value)) {
      if (array_key_exists($this->selected, $this->value))
        return $this->translate($this->value[$this->selected],true);
      else return "<font color='red'>x</font>";
    } else return $this->selected;
  }
  
  //check if the target service is multilang
  function chkTargetMultilang($val='') {
    global $db;
    $ret=false;
    // discover an "owner field" in the service
    $table = preg_match("/from (\w+)[\s]?(.*)/i", $this->qry, $matches);
    $destTable=$matches[1];
    if ($destTable != "") {
      $qry1 = "SELECT * FROM aa_services WHERE syntable='{$destTable}'";
      $res = $db->Execute($qry1);
      $arr = $res->FetchRow();
      $id = $arr["id"];
      $qry2 = "SELECT * FROM aa_services_element WHERE container='{$id}' ORDER BY `order` LIMIT 1, 1";
      $res = $db->Execute($qry2);
      //$arr=$res->FetchRow(); //salto il primo elemento: campo "value" dell'option
      $arr = $res->FetchRow(); //prendo il secondo elemento: campo "innerHTML" dell'option
      $ret = $arr["ismultilang"];
    }
    /*
    $qry="SELECT * FROM aa_services";
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $table=$arr["syntable"];
      if (@strpos($this->qry,$table)!==false) $ret=$arr["multilang"];
    }
    */
    return $ret;
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
    $ownerField = '';
    // discover an "owner field" in the service
    $table = preg_match("/from (\w+)[\s]?(.*)/i", $qry, $matches);
    $destTable = $matches[1];
    if ($destTable != '') {
      $qry1 = "SELECT * FROM aa_services WHERE syntable='{$destTable}'";
      $res = $db->Execute($qry1);
      $arr = $res->FetchRow();
      $id = $arr["id"];
      $qry2 = "SELECT ase.*,ae.classname FROM aa_services_element ase JOIN aa_element ae ON ase.type=ae.id WHERE ase.container='{$id}' ORDER BY ase.`order`";
      $res = $db->Execute($qry2);
      while ($arr = $res->FetchRow()) {
        if (strtolower($arr["classname"]) == "synowner") 
          $ownerField = $arr["name"];
      }
    }
    
    // ATTENTION: This is a security control. All the "select" widget on the 
    // "aa_groups" table are filtered with the allowed groups.
    if ($destTable == "aa_groups") {
      $ret = array_flip($_SESSION["synGroupChild"]);
    } else {
      $res = $db->Execute($qry);
      if ($null == true) 
        $ret[] = '';
      while ($arr = $res->FetchRow()) {
        if ($ownerField == "" OR $arr[$ownerField] == "" OR in_array($arr[$ownerField], $_SESSION["synGroupChild"]) OR $this->selected == $arr[0]) { 
          $ret[$arr[0]] = $arr[1];
        } 
      }
    } //end if aa_groups    
    
    return $ret;
  }
  
  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmQry, $synElmPath;
    global $synChkKey, $synChkVisible, $synChkEditable,$synChkMultilang;
    global $db;
    $synHtml = new synHtml();
    $selected = "";

    $res=$db->Execute("SELECT * FROM aa_services order by name");
    $txt="<select name=\"synElmQry[$i]\" >";
    while ($arr=$res->FetchRow()) {
      if(isset($synElmQry[$i])) {  
        if (strpos($synElmQry[$i]." ","FROM ".$arr["syntable"]." ")===false ) $selected=""; 
        else $selected="selected=\"selected\""; 
      }
      if ($arr["syntable"]!="") $txt.="<OPTION VALUE=\"SELECT * FROM ".$arr["syntable"]."\" $selected>".translate($arr["name"])."</option>";
    }
    $txt.="</select>\n";

    $this->configuration[4]="Query: ".$txt;

    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") 
      $synElmSize[$i]=$this->size;
    $this->configuration[5]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    if (!isset($synElmPath[$i]) or $synElmPath[$i]=="") 
      $checked=""; 
    else 
      $checked=" checked='checked' ";
    $this->configuration[6]="NULL: ".$synHtml->hidden(" name=\"synElmPath[$i]\" value=\"\"").$synHtml->check(" name=\"synElmPath[$i]\" value=\"1\" $checked");
    
    //enable or disable the 3 check at the last configuration step
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) 
      return $this->configuration;
    else 
      return $this->configuration[$k];
  }
  
  
} //end of class inputfile

?>
