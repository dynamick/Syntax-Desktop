<?php

/*************************************
* class CHECK                        *
* Create a input type="text" obj     *
**************************************/
class synCheck extends synElement {

  var $selected;
  //var $container;

  //constructor(name, value, label, size, help)
  function synCheck($n="", $v="", $l="", $s=255, $h="") {
    global $$n;
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "checkbox";
    $this->name  = $n;
    if (isset($_REQUEST[$n]) && $_REQUEST[$n]!="") {
      $this->value = $_REQUEST[$n];
      $this->selected = true;
    } else {
      $this->value = $v;
      $this->selected = false;
    }
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " varchar(".$this->size.") NOT NULL";
    if (isset($_REQUEST[$n])) $this->selected = $_REQUEST[$n];
    //$this->container = getContainer();
  }


  //private function
  function _html() {
    if ($this->selected==true) $selected="checked=\"checked\""; else $selected="";
    return "<input type='checkbox' name='".$this->name."' maxsize='".$this->size."' value='".$this->value."' $selected />"; 
  }

  //set the value of the element
  function setValue($v) {
    if ($v=="") $this->selected=false; else $this->selected=true;
  }  

  //get the value of the element
  function getValue() {
    if ($this->selected==true) return $this->value;
    else return "";
  }  
  
  //get the value for listing of the element
  function getCell() {
    global $key;
    if ($this->selected==true) $selected="checked=\"checked\""; else $selected="";
    return "<input type='checkbox' name='".$this->name."' maxsize='".$this->size."' value='".$this->value."' $selected onclick=\"callServer('".$this->container->getKey()."', '".$this->name."', this.checked==false?'':'".$this->value."');\" />"; 
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    global $synElmValue;
    $synHtml = new synHtml();
    //parent::configuration();
    if (!isset($synElmValue[$i]) || $synElmValue[$i]=="") $synElmValue[$i]="1";
    $this->configuration[8]="Valore: ".$synHtml->text(" name=\"synElmValue[$i]\" value=\"$synElmValue[$i]\"");
    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=0;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
  
} //end of class check

?>
