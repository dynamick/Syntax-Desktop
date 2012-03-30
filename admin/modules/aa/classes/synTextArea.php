<?php
/*************************************
* class TEXTAREA CKEditor 3.0        *
* Create a input type="text" obj     *
**************************************/
class synTextArea extends synElement {
  var $type;
  var $big;

  //constructor(name, value, label, size, help)
  function synTextArea($n="", $v=null , $l=null, $s=150, $h="") {
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

  //get the label of the element
  function getCell() {
    return $this->translate(substr(strip_tags($this->getValue()),0,200),true);
  }


  //private function
  function _html() {
    global $synAdminPath, $synPublicPath, $mat;
    $height = $this->size;
    $value = htmlentities($this->translate($this->value));
    $ckConfig = $synAdminPath.'/includes/js/ckeditor/syntax.config.php';
    $contents = <<<EOC
  <textarea name="{$this->name}" id="{$this->name}" class="editor" style="height:{$height}px" rel="{$this->type}">
    {$value}
  </textarea>
  <script type="text/javascript" src="{$synAdminPath}/includes/js/ckeditor/ckeditor.js"></script>
  <script type="text/javascript">
   CKEDITOR.replace('{$this->name}', {customConfig:'{$ckConfig}', toolbar:'{$this->type}', height:$height});
  </script>
EOC;
    // NB: quando passiamo a jquery lo script sopra va centralizzato!

    $_SESSION['KCFINDER']['disabled'] = false;
    $_SESSION['KCFINDER']['uploadURL'] = $synPublicPath.$mat.'/';
  //$_SESSION['KCFINDER']['uploadDir'] = getenv('DOCUMENT_ROOT').$synPublicPath.$mat.'/';

    if ($this->big==1) $contents = '</td></tr><tr><td colspan=2>'.$contents;
    return $contents;
  }

  function setQry($qry) {
    $this->big=$qry;
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmPath, $synElmQry;
    global $synElmSize;
    $synHtml = new synHtml();

    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Altezza: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    $array=array("Default"=>"Default", "Basic"=>"Basic", "Deluxe"=>"Deluxe");
    $this->configuration[5]="Tipo: ".$synHtml->select(" name=\"synElmPath[$i]\" value=\"$synElmPath[$i]\"",$array,$synElmPath[$i]);

    if (!isset($synElmQry[$i]) or $synElmQry[$i]=="") $checked=""; else $checked=" checked='checked' ";
    $this->configuration[6]="Gigante: ".$synHtml->check(" name=\"synElmQry[$i]\" value=\"1\" $checked");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable,$synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=1;
    $_SESSION["synChkMultilang"][$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class text

?>
