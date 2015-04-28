<?php

/*************************************
* class ICON                         *
* Create a Font Awesome Icon picker  *
**************************************/
class synIcon extends synElement {

  //constructor(name, value, label, size, help)
  function synIcon( $n = '', $v = null , $l = null, $s = 30, $h = '' ) {
    if ($n == '')
      $n = 'text'.date('his');
    if ($l == '')
      $l = ucfirst($n);

    $this->type  = 'text';
    $this->name  = $n;
    $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
      // 13/3/2015 provata questa impostazione. Quando una riga non ha valore
      // sul getRow() prende il valore della prededente. Percheeeè?????!!!
      //$this->db    = " VARCHAR(30) DEFAULT NULL";
    $this->db    = " VARCHAR({$this->size}) NOT NULL";

    $this->configuration();
  }


  //private function
  function _html() {
    // relies on https://github.com/mjolnic/fontawesome-iconpicker
    $input = <<<EOINPUT
    <div class="input-group col-md-3">
      <input type="text"
        name="{$this->name}"
        value="{$this->value}"
        class="form-control icp"
        data-placement="right"
        data-selected="{$this->value}">
      <span class="input-group-addon">
        <i class="fa fa-fw {$this->value}"></i>
      </span>
    </div>
EOINPUT;

    return $input;
  }

  function getCell() {
    $value = $this->getValue();
    if (!empty($value))
      $ret = "<i class=\"fa fa-fw {$value}\"></i>";
    else
      $ret = '';
    return $ret;
  }

  //function for the auto-configuration
  function configuration( $i = '', $k = 99) {
    global $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp, $synElmSize;
    $synHtml = new synHtml();

    if (!isset($synElmSize[$i]) or empty($synElmSize[$i]))
      $synElmSize[$i] = $this->size;
    $this->configuration[4] = $synHtml->hidden(" name=\"synElmSize[{$i}]\" value=\"{$this->size}\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable,$synChkMultilang;
    $_SESSION['synChkKey'][$i]        = 0;
    $_SESSION['synChkVisible'][$i]    = 1;
    $_SESSION['synChkEditable'][$i]   = 0;
    $_SESSION['synChkMultilang'][$i]  = 0;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }


} //end of class icon

?>
