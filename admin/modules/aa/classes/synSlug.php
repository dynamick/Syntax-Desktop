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
    return "<input disabled='disabled' type='text' name='".$this->name."' maxsize='".$this->size."' value=\"".$value."\" class=\"form-control\"/>"; 
  }
  
  
  function initCallback() {  
    $container = synContainer::getInstance();
    $container->add_callback('update', array($this, 'updateSlug'));
    $container->add_callback('insert', array($this, 'insertSlug'));
  }

  
  function updateSlug() {
    $key = $this->container->getKeyValue();
    updateSlug($key);
    
    return true;    
  }
  
  function insertSlug() {
    $key = $this->container->getKeyValue();
    insertSlug($key);
    
    return true;    
  }
  
  
  //function for the auto-configuration
  function configuration($i='',$k=99) {
    global
      $synElmLabel, $synElmName, $synElmSize, $synChkMultilang, $synElmPath, $synChkVisible,  
      $synElmValue, $synElmType, $synElmHelp, $synChkEditable, $synChkKey;
  
    
    $synHtml = new synHtml();
    if ( !isset($synElmSize[$i]) 
      || $synElmSize[$i] == ''
      ) 
      $synElmSize[$i] = $this->size;
      
    $this->configuration[4] = "Dimensione: ".$synHtml->text(" name=\"synElmSize[{$i}]\" value=\"{$synElmSize[$i]}\"");
    
    //enable or disable the 3 check at the last configuration step
    $_SESSION['synChkKey'][$i]       = 1;
    $_SESSION['synChkVisible'][$i]   = 1;
    $_SESSION['synChkEditable'][$i]  = 1;
    $_SESSION['synChkMultilang'][$i] = 1;

    if ($k==99) 
      return $this->configuration;
    else 
      return $this->configuration[$k];
  }
  

  
} 

//end of class synSlug

