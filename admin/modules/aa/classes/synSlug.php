<?php

/*************************************
* class SLUG                         *
* Create a input type="text" obj     *
**************************************/
class synSlug extends synElement {

  //constructor(name, value, label, size, help)
  function synSlug($n="", $v=null , $l=null, $s=1024, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) {
      global $$n;
      $this->value = (isset($_REQUEST[$n])) ? $_REQUEST[$n] : '';
    } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " VARCHAR(".$this->size.") NOT NULL";

    $this->configuration();
    $this->initCallback();
  }

  
  
  //private function
  function _html() {
    $value = str_replace("\"", "&quot;", ($this->translate($this->getValue()))); 
    return "<input disabled='disabled' type='text' name='".$this->name."' maxsize='".$this->size."' value=\"".$value."\" style='width: 100%'/>"; 
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
    global $synChkKey, $synChkVisible, $synChkEditable,$synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=1;
    $_SESSION["synChkMultilang"][$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
  function initCallback() {  
    $container = synContainer::getInstance();
    $container->add_callback('update', array($this, 'update'));
    $container->add_callback('insert', array($this, 'insert'));
  }

  function update() {
    
  }
  
  function insert() {
    
  }  
  
} //end of class text

?>
