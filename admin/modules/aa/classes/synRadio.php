<?php
/*************************************
* class Radio                        *
* Create a input radio obj           *
**************************************/
class synRadio extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synRadio($n="", $v="", $l="", $s="", $h="") {
    global $$n;
    
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);
    $this->type    = "enum";
    $this->name    = $n;
    $this->label   = $l;
    $this->help    = $h;
    $arvalues      = explode("|", $this->qry);
    //$this->db      = "  enum('".implode("','", $arvalues)."') NOT NULL default '".$arvalues[0]."'";
  }

  //private function
  function _html() {
    if ($this->chkTargetMultilang()==1) $this->multilang=1;
    $selected = ($this->value ? $this->value : false);
    $options = $this->createArray2($this->qry,$this->path);

    if (is_array($options)) {
      $count = 0;
      foreach($options as $v) {
        $id      = $v["id"];
        $value   = $v["value"];
        $checked = (($id==$selected || !$selected && $count==0) ? ' checked="checked"' : '');
        $txt    .= " <input type=\"radio\" name=\"".$this->name."\" VALUE=\"".$id."\"".$checked."/> ".$value;
        $count ++;
      }
    }
    return $txt;
  }

  function setPath($path) {
    $this->path=$path;
  }
  
  function setQry($qry) {
    $this->qry=$qry;
    $arvalues = explode("|", $this->qry);
    $this->db = "  enum('".implode("','", $arvalues)."') NOT NULL default '".$arvalues[0]."'";
  }

  function createArray($qry,$null=false) {
    if ($null==true) $ret['NULL']="";
    $arvalues = explode("|", $qry);
    foreach ($arvalues as $k=>$v) $ret[$k]=$v;
    return $ret;
  }

  function createArray2($qry,$null=false) {
    if ($null==true) $ret['NULL']="";
    $arvalues = explode("|", $qry);
    foreach ($arvalues as $v) {  
      $r["id"]=$v;
      $r["value"]=$v;
      $ret[]=$r;
    }
    return $ret;
  }
  
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
