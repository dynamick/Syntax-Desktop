<?php

/*************************************
* class SELECT Multi Check           *
* Create a input select obj          *
**************************************/
class synSelectMultiCheck extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synSelectMultiCheck($n="", $v="", $l="", $s=255, $h="") {
    global $$n;
    
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);
    $this->type = "text";
    $this->name  = $n;
    if ($$n) { $this->selected = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " varchar(".$this->size.") NOT NULL";
  }

  //private function
  function _html() {
    if ($this->chkTargetMultilang($this->qry)==1)
      $this->multilang = 1;
    $this->value = $this->createArray2($this->qry, $this->path);
    $selArr      = explode("|",$this->selected);
    if (is_array($this->value)) {
      $current_group = '';
      foreach($this->value as $v) {
        $id       = $v['id'];
        $value    = $v['value'];
        $group    = trim($v['group']);
        $eol      = "<br/>\n";
        $selected = (in_array($id, $selArr)) ? ' checked="checked"' : '';

        if(trim($group)!=''){
          if ($group != $current_group) {
            if($current_group!='') $txt .= "</fieldset>\n";
            $txt .= "<fieldset><legend>{$group}</legend>\n";
            $current_group = $group;
          }
          $eol = ' ';
        }

        $txt .= "<label><input type=\"checkbox\" name=\"".$this->name."[]\" value=\"{$id}\"{$selected}/> ".$this->translate($value, true).'</label>'.$eol;
      }
      if($current_group!='') $txt .= "</fieldset>\n";
    }
    return $txt;
  }
  
  //sets the value of the element
  function setValue($v) {
    global $$n;
    if (!isset($_REQUEST[$$n])) $this->value = $this->createArray($this->qry,$this->path);
    $this->selected = $v;
    return;
  }  

  //sets the value of the element
  function getValue() {
    if (is_array($this->selected)) 
      return implode("|",$this->selected);
    else return;
  }  

  //get the label of the element
  function getCell() {
    if ($this->chkTargetMultilang($this->qry)==1) $this->multilang=1;
    $this->value = $this->createArray2($this->qry,$this->path);
    $selArr=explode("|",$this->selected);
    if (is_array($this->value)) {
      foreach($this->value as $v)
        if (in_array($v["id"],$selArr)) $ret .= $this->translate($v["value"]).", ";
      $ret=substr($ret,0,-2);
      return $ret;      
    } 
  }
  
  function setPath($path) {$this->path=$path;}
  function setQry($qry) {
    $this->qry=$qry;
  }

  function createArray($qry,$null=false) {
    global $db;
    $qry=$this->compileQry($qry);
    $res=$db->Execute($qry);
    if ($null==true) $ret['NULL']="";
    while ($arr=$res->FetchRow()) $ret[$arr[0]]=$arr[1];
    return $ret;
  }

  function createArray2($qry,$null=false) {
    global $db;
    $qry=$this->compileQry($qry);
    $res=$db->Execute($qry);
    if ($null==true) $ret['NULL']="";
    while ($arr=$res->FetchRow()) {  
      $r["id"]=$arr[0];
      $r["value"]=$arr[1];
      if ($arr[2]!="") $r["group"]=$arr[2];
      $ret[]=$r;
    }
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
      if (strpos($this->qry,$table)!==false)  $ret=$arr["multilang"];
    }
    return $ret;
  }
*/
  
  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp,$synElmPath;
    global $synElmQry;
    $synHtml = new synHtml();
    //parent::configuration();
    $this->configuration[4]="Query: ".$synHtml->text(" name=\"synElmQry[$i]\" value=\"".htmlentities($synElmQry[$i])."\"");

    if (!isset($synElmPath[$i]) or $synElmPath[$i]=="") $checked=""; else $checked=" checked='checked' ";
    $this->configuration[5]="NULL: ".$synHtml->check(" name=\"synElmPath[$i]\" value=\"1\" $checked");

    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[6]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
} //end of class inputfile

?>
