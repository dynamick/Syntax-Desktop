<?php
/*************************************
* class DATE                         *
* Create a input type "Date" obj     *
**************************************/
class synDate extends synElement {

  var $path;

  //constructor(name, value, label, size, help)
  function synDate($n="", $v=null , $l=null, $s=null, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = str_replace(" ","_",$n);
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $default     = ($this->path ? " DEFAULT '$this->path'" : '');
    $this->db    = " DATE NOT NULL".$default;

    $this->configuration();
  }

  //private function
  function _html() {
    if($this->path!=''):
      $this->value=$this->path;
    else:
      if ($this->value=="0000-00-00" or $this->value=="") $this->value=date("Y-m-d");
    endif;
    $datepicker="<a href=\"javascript:NewCal('".$this->name."','ddmmyyyy')\"><img src=\"images/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Scegli una data\"></a>";
    return "<input type='text' size='25' id='".$this->name."' name='".$this->name."' value='".$this->dateFormat($this->value)."'/> (gg-mm-aaaa) $datepicker";
  }

  //get the selected/typed value
  function getValue() {
    global ${$this->name};
    if (${$this->name}=="") return $this->value;
    else return $this->dateFormat(${$this->name});
  }

  function setPath($value) {
    $this->path=$value;
  }

  //get the label of the element
  function getCell() {
    return $this->dateFormat($this->getValue());
  }

  //sets the value of the element
  function setValue($v) {
    $this->value = $v;
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp, $synElmPath;
    $synHtml = new synHtml();

    $this->configuration[4]="Default (empty=today's date): ".$synHtml->text(" name=\"synElmPath[$i]\" value=\"$synElmPath[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }

  //private: formats the date in a dd-mm-yyyy format
  function dateFormat($value) {
    $splitted=explode("-",$value);
    $value=$splitted[2]."-".$splitted[1]."-".$splitted[0];
    return $value;
  }


} //end of class text

?>
