<?php

/*************************************
* class TEXT Numeric                 *
* Create a input type="text" obj     *
**************************************/
class synTextDecimal extends synElement {

  //constructor(name, value, label, size, help)
  function synTextDecimal($n="", $v=null , $l=null, $s=8, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " DEC(".$this->size.",2) NOT NULL";

    $this->configuration();
  }

  //private function
  function _html() {
    return "<input type='text' name='".$this->name."' maxsize='".$this->size."' value='".$this->value."'/>";
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }

  function getSQLValue() {
    return floatval($this->getValue());
  }


} //end of class text

?>
