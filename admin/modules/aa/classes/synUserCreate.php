<?php
/*************************************
* class USERCREATE                   *
* puts a signature of the user who   *
* created the record.                *
* (it cannot be modified!)           *
**************************************/
class synUserCreate extends synElement {

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
    $disabled = ($this->value!="" ? " disabled=\"disabled\"" : "");
    // if empty, get the name of the actual user
    if(!isset($_REQUEST["synPrimaryKey"]) or $_REQUEST["synPrimaryKey"]=="") {
      $value = getSynUser();
    } else{
       $value = $this->value;
    }
    if ($value) {
      $html  = "<input type='hidden' name='{$this->name}' maxsize='{$this->size}' value='{$value}'{$disabled}/>";
      $html .= "<p class='form-control-static'>" . username($value) . "&nbsp;<span class='badge'>" . groupname($value) . "</span></p>\n";
      return $html;
    }
  }

  //return the sql statement (i.e. `name`='gigi')
  function getSQL() {
    $ret="";
    //if primaryKey is empty, then it's a new record: put the creator's id
    if($_REQUEST['synPrimaryKey']=="") {
      if ($this->getValue()!="") {
        $ret=$this->getSQLname()."=".fixEncoding($this->getSQLValue());
      }
    }
    return $ret;
  }

  function getCell() {
    //return "<span>".username($this->value)."</span>";
    return username( $this->value );
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
