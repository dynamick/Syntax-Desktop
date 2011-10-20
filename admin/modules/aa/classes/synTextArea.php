<?
include_once("fckeditor.php");

/*************************************
* class TEXTAREA                     *
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
    if ($this->type=="") $this->type="Default";
    ob_start();
    //$oFCKeditor = new FCKeditor ;
    //$oFCKeditor->Value = $this->translate($this->value);
    //$oFCKeditor->ToolbarSet = $this->type ;
    //$oFCKeditor->CreateFCKeditor( $this->name, "100%", $this->size ) ;

    $oFCKeditor = new FCKeditor($this->name) ;
    $oFCKeditor->Value = $this->translate($this->value);
    $oFCKeditor->ToolbarSet = $this->type ;
    $oFCKeditor->Height = $this->size+72; //72 = barra fckeditor con 3 righe di icone
    $oFCKeditor->Create() ;

    $contents=ob_get_contents();
    ob_end_clean();
    if ($this->big==1) return "</td></tr><tr><td colspan=2>".$contents;
    else return $contents;
        
  }

  function setQry($qry) {
    $this->big=$qry;
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmPath, $synElmQry;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Altezza: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    $array=array(
      "Default" => "Default",
        "Basic" => "Basic",
       "Deluxe" => "Deluxe"
    );
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
