<?php

/*************************************
* class SIMPLE TEXTAREA              *
* Create a textarea obj              *
**************************************/
class synTextAreaSimple extends synElement {
  var $type;

  //constructor(name, value, label, size, help)
  function synTextAreaSimple($n="", $v=null , $l=null, $s=0, $h="") {
    if ($n=="") $n =  "textarea".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "textarea";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " text NOT NULL";
  }

  //set the Type of TextArea
  function setPath($value) {
    $this->type=$value;
  }

  //private function
  function _html() {
    $value=($this->translate($this->value));
    $contents = "";
    /*
    if ($this->size==0) {
      $contents.="<textarea name=\"{$this->name}\" class=\"form-control\" rows=\"8\" >{$value}</textarea>\n";
    } else {
      $actualSize=strlen(trim($this->value));
      $contents.="&nbsp;Current <font color=\"#CC0000\"><span id=\"messageCount_".$this->name."\">$actualSize</span></font> characters / Maximum <font color=\"#CC0000\">".$this->size." </font>characters<br>\n";
      $contents.="<textarea name=\"".$this->name."\" wrap=\"VIRTUAL\" class=\"form-control\" rows=\"8\" onkeyUp=\"textLimitCheck(this,'{$this->name}', {$this->size});\">{$value}</textarea>\n";
    }*/
    $class = 'form-control';
    $maxlength = null;
    if ($this->size > 0) {
      $class .= ' input-limited';
      $maxlength = "maxlength=\"{$this->size}\"";
      //$contents .= '<span class="label label-default textarea-indicator"><b>0</b> characters</span>'.PHP_EOL;
    }
    $contents .= "<textarea name=\"{$this->name}\" class=\"{$class}\" rows=\"8\"{$maxlength}\">{$value}</textarea>\n";

    return $contents;
  }

  //get the label of the element
  function getCell() {
    //return $this->translate(substr(strip_tags($this->getValue()),0,200),true);
    $max = 80;
    $str = strip_tags( $this->translate( $this->getValue(), true ) );
    if (strlen($str) > $max)
      $str = substr($str, 0, 80).'&hellip;';
    return $str;
  }

  //get the label of the element
  function getCell1() {
    //return $this->translate(substr(strip_tags($this->getValue()),0,200),true);
    $text = strip_tags( $this->translate($this->getValue()) );
    $text = str_replace( array("\r", "\r\n", "\n"), '', $text );
    return "<div class=\"text-holder\">{$text}</div>"; //substr($text, 0, 80);
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmPath;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Max Size: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"")." (0 if unlimited text size)";

    //$array=array("Default" => "Default", "Basic" => "Basic", "Accessibility" => "Accessibility", "Source" => "Source");
    //$this->configuration[5]="Tipo: ".$synHtml->select(" name=\"synElmPath[$i]\" value=\"$synElmPath[$i]\"",$array,$synElmPath[$i]);

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=1;
    $_SESSION["synChkMultilang"][$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class text

?>
