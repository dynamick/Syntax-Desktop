<?php

/*************************************
* class DATE                         *
* Create a input type "Date" obj     *
**************************************/
class synDate extends synElement {

  var $path;

  //constructor(name, value, label, size, help)
  function __construct($n='', $v=null , $l=null, $s=null, $h="") {
    if ($n=='') $n =  'text'.date('his');
    if ($l=='') $l =  ucfirst($n);

    $this->type = 'text';
    $this->name  = str_replace(' ', '_', $n);
    if ($v == null) {
      global $$n;
      $this->value = $$n;
    } else {
      $this->value = $v;
    }
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $default     = ($this->path ? " DEFAULT '{$this->path}'" : '');
    $this->db    = ' DATE NOT NULL'.$default;

    $this->configuration();
  }

  //private function
  function _html() {
    if ($this->path != '') :
      $this->value = $this->path;
    else :
      if ($this->value == '0000-00-00' || $this->value == '00-00-0000' || $this->value == '')
        $this->value = date('Y-m-d');
    endif;
    // relies on https://github.com/Eonasdan/bootstrap-datetimepicker
    // IMPORTANT: date-format sets the datepicker behaviour.
    // uses moment's formats - see http://momentjs.com/docs/#/displaying/format/
    $dateFormat = 'DD-MM-YYYY';
    $input = <<<EOINPUT
    <div class="date input-group col-md-3">
      <input type="text"
        name="{$this->name}"
        value="{$this->dateHumanFormat( $this->value )}"
        size="25"
        id="{$this->name}"
        class="form-control"
        data-date-format="{$dateFormat}">
      <span class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </span>
    </div>
EOINPUT;
    return $input;
  }

  //get the selected/typed value
  function getValue() {
    global ${$this->name};
    if (empty(${$this->name}))
      return $this->value;
    else
      return $this->dateIsoFormat(${$this->name});
  }

  function setPath($value) {
    $this->path = $value;
  }

  //get the label of the element
  function getCell() {
    return $this->dateHumanFormat( $this->getValue() );
  }

  //sets the value of the element
  function setValue($v) {
    $this->value = $this->dateHumanFormat($v);
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp, $synElmPath;
    $synHtml = new synHtml();
    $tmp_val = isset($synElmPath[$i]) ? $synElmPath[$i] : "";
    $this->configuration[4] = "Default (empty=today's date): ".$synHtml->text(" name=\"synElmPath[$i]\" value=\"".$tmp_val."\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }

  //private: formats the date in a dd-mm-yyyy format
  function dateIsoFormat($value) {
    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)){
      $splitted = explode("-",$value);
      $value = $splitted[2]."-".$splitted[1]."-".$splitted[0];
    }
    return $value;
  }

  function dateHumanFormat($value) {
    if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)){
      $splitted = explode("-",$value);
      $value = $splitted[2]."-".$splitted[1]."-".$splitted[0];
    }
    return $value;
  }

} //end of class date

?>
