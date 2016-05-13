<?php
/*************************************
* class TEXTAREA CKEditor 4.0        *
* Create a input type="text" obj     *
**************************************/
class synTextArea extends synElement {
  var $type;
  var $big;

  //constructor(name, value, label, size, help)
  function __construct($n='', $v=null , $l=null, $s=150, $h='') {
    if ($n=='')
      $n            = 'textarea'.date('his');
    if ($l=='')
      $l            = ucfirst($n);

    $this->type     = 'Default';
    $this->name     = $n;
    if ($v==null) {
      global $$n;
      $this->value  = $$n;
    } else
      $this->value  = $v;
    $this->label    = $l;
    $this->size     = $s;
    $this->help     = $h;
    $this->db       = ' text NOT NULL';
  }

  //set the Type of TextArea
  function setPath($value) {
    $this->type = $value;
  }

  //get the label of the element
  function getCell() {
    return  mb_substr( strip_tags( $this->translate( $this->getValue(), false ) ), 0, 200, 'UTF-8');
  }

  //private function
  function _html() {
    global $synAdminPath, $synPublicPath, $mat, $synAbsolutePath;

    if (!isset($_SESSION))
      session_start();

    $height   = $this->size;
    $value    = $this->translate( $this->value );
    $ckConfig = $synAdminPath . '/assets/js/vendor/ckeditor/syntax-config.js';
    $lang     = isset( $_SESSION['aa_CurrentLangInitial'] )
              ? $_SESSION['aa_CurrentLangInitial']
              : 'en';
    $contents = <<<EOC
    <textarea name="{$this->name}" id="ck_{$this->name}" class="form-control" style="height:{$height}px;">{$value}</textarea>
EOC;

    $_SESSION['KCFINDER']['disabled'] = false;
    $_SESSION['KCFINDER']['uploadURL'] = $synPublicPath . $mat . DIRECTORY_SEPARATOR;

    enqueue_js( "CKEDITOR.replace( 'ck_{$this->name}', {customConfig:'{$ckConfig}', toolbar:'{$this->type}', height:{$height}, language:'{$lang}'} );" );

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
    $value = "";
    if (isset($synElmPath[$i])) $value = $synElmPath[$i];
    $this->configuration[5]="Tipo: ".$synHtml->select(" name=\"synElmPath[$i]\"",$array,$value);

#    if (!isset($synElmQry[$i]) or $synElmQry[$i]=="") $checked=""; else $checked=" checked='checked' ";
#    $this->configuration[6]="Gigante: ".$synHtml->check(" name=\"synElmQry[$i]\" value=\"1\" $checked");

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
