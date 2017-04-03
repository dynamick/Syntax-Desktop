<?php

/*************************************
* class USERMODIFIED                 *
* puts a signature of the last user  *
* who modified the record.           *
**************************************/
class synUserModified extends synElement {

  //constructor(name, value, label, size, help)
  function __construct($n="", $v=null , $l=null, $s=255, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) {
      global $$n;
      if(isset($_REQUEST[$n])) $this->value = $_REQUEST[$n];
    } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " VARCHAR({$this->size}) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";

    $this->configuration();
  }

  //private function
  function _html() {
    $html = '';
    //if primaryKey is not empty it means we're modifying an exisiting record: put the editing user's id
    if ( isset($_REQUEST["synPrimaryKey"]) ) {
      $html .= "<input type='hidden' name='{$this->name}' maxsize='{$this->size}' value='".getSynUser()."'/>";
    }
    $html .= "<p class='form-control-static'>" . username($this->value) . "&nbsp;<span class='badge'>" . groupname($this->value) . "</span></p>\n";
    return $html;
  }

  function getCell() {
    return username($this->value);
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
    $_SESSION["synChkKey"][$i]=0;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class text

?>
