<?php

/*************************************
* class TreeGroup                       *
* Set the group permissions for tree    *
**************************************/
class synTreeGroup extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synTreeGroup($n="", $v="", $l="", $s=255, $h="") {
    global $$n;
    
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);
    $this->type = "file";
    $this->name  = $n;
    if (isset($$n) and $$n) { $this->selected = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " varchar(".$this->size.") NOT NULL";
  }

  //private function
  function _html() {
    global $synGroup;
    $this->value = $this->createArray($this->qry,$this->path);

    //$this->isKey()?$disable=" disabled=\"disabled\" ":$disable="";
    $txt="<select name='".$this->name."'>";
    if ($this->selected=="") $this->selected=$synGroup;
    if (is_array($this->value)) {
      while (list ($k, $v) = each ($this->value)) {
        if ($this->selected==$k) $selected="selected=\"selected\""; else $selected="";
        $txt.="<OPTION VALUE=\"".$k."\" $selected>".$v."</option>";
      }
      reset($this->value);  
    }

    $txt.="</select>\n";
    return $txt; 
  }
  
  //sets the value of the element
  function setValue($v) {
    //if (is_array($v)) $this->value = $v;
    if (!isset($_REQUEST[$$n])) $this->value = $this->createArray($this->qry,$this->path);
    $this->selected = $v;
  }  

  //sets the value of the element
  function getValue() {
    return $this->selected;
  }  

  //get the label of the element
  function getCell() {
    if (is_array($this->value)) {
      if (array_key_exists($this->selected, $this->value)) 
        return $this->value[$this->selected];
      else return "<font color='red'>x</font>";
    } else return $this->selected;
  }
  
  
  function setPath($path) {$this->path=$path;}
  function setQry($qry) {$this->qry=$qry;}
  function createArray($qry,$null=false) {
    global $db;
    $res=$db->Execute($qry);
    if ($null==true) $ret['NULL']="";
    while ($arr=$res->FetchRow())  
      $ret[$arr[0]]=$arr[1];
    return $ret;
  }
  
  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmQry;
    global $synElmQry;
    $synHtml = new synHtml();
    
    
    global $db;
    $res=$db->Execute("SELECT * FROM aa_services WHERE id=3 order by name");
    $txt="<select name=\"synElmQry[$i]\" >";
    while ($arr=$res->FetchRow()) {
      if (strpos($synElmQry[$i],$arr["syntable"])===false ) $selected=""; else $selected="selected=\"selected\""; 
      if ($arr["syntable"]!="") $txt.="<OPTION VALUE=\"SELECT * FROM ".$arr["syntable"]."\" $selected>".$arr["name"]."</option>";
    }
    $txt.="</select>\n";

    $this->configuration[4]="Control Service: ".$txt;

    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[5]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
  
} //end of class inputfile

?>