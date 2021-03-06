<?php
//ALTER TABLE `` ADD `` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;
/*************************************
* class TEXT                         *
* Create a input type="text" obj     *
**************************************/
class synLastUpdate extends synElement {

  //constructor(name, value, label, size, help)
  function __construct($n="", $v=null , $l=null, $s=null, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " DATETIME NULL DEFAULT NULL";

    $this->configuration();
  }

  function _html() {
    $html = '';
    $this->value = date('Y-m-d H:i:s');

    //if primaryKey is not empty
    if ( isset($_REQUEST["synPrimaryKey"]) ) {
      $html .= "<p class=\"form-control-static gost\">{$this->value}</p>";
      
    }
    $html .= "<input type='hidden' name='{$this->name}' value='{$this->value}' />";
    return $html;
  }


  //get the selected/typed value
  function getValue() {
    global ${$this->name};
    if (${$this->name}=="") return $this->value;
    else return $this->dateIsoFormat(${$this->name});
  }

  //get the label of the element
  function getCell() {
    return $this->dateHumanFormat($this->getValue());
  }

  //sets the value of the element
  function setValue($v) {
    $this->value = $this->dateHumanFormat($v);
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,
            $synElmType,
            $synElmLabel,
            $synElmSize,
            $synElmHelp;
    $synHtml = new synHtml();
    //parent::configuration();
    $this->configuration[8]="<span style=\"color: darkblue;\">Data Corrente</span>";

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkReadonly;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    //$_SESSION["synChkReadonly"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }

  //private: formats the date in a dd-mm-yyyy (Hours:Min:sec)format
  function dateIsoFormat($value) {
    if(preg_match('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}$/', $value)){
      $dateHour=explode(' ',$value);
      $splitted=explode('-',$dateHour[0]);
      $value=$splitted[2]."-".$splitted[1]."-".$splitted[0];
      return $value." ".$dateHour[1];
    } else return $value;
  }

  function dateHumanFormat($value) {
   if(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)){
      $dateHour=explode(' ',$value);
      $splitted=explode('-',$dateHour[0]);
      $value=$splitted[2]."-".$splitted[1]."-".$splitted[0];
      return $value." ".$dateHour[1];
    } else return $value;
  }

} //end of class text

?>