<?php

/*************************************
* class PASSWORD                     *
* Create a input type="text" obj     *
**************************************/
class synPassword extends synElement {

  //constructor(name, value, label, size, help)
  function __construct( $n = '', $v = null, $l = null, $s = 255, $h = '' ) {
    if ($n == '')
      $n = 'pass'.date('his');
    if ($l == '')
      $l = ucfirst($n);

    $this->type  = 'text';
    $this->name  = $n;
    if ($v == null) {
      global $$n;
      $this->value = $$n;
    } else {
      $this->value = $v;
    }
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " VARCHAR({$this->size}) NULL DEFAULT NULL";

    $this->configuration();
  }

  //private function
  function _html() {
    if (empty($this->value))
      $is_set = 'inserisci '.$this->name;
    else
      $is_set = $this->name.' impostata';
    //return "<input type=\"password\" name=\"{$this->name}\" maxsize=\"{$this->size}\" value=\"\"> {$setted}";
    $input = <<<EOINPUT
    <div class="input-group col-md-3">
      <input type="password"
        name="{$this->name}"
        value=""
        size="{$this->size}"
        class="form-control"
        placeholder="{$is_set}">
      <span class="input-group-addon">
        <i class="fa fa-key"></i>
      </span>
    </div>
EOINPUT;
    return $input;
  }

  //get the selected/typed value
  function getValue() {
    global ${$this->name}, $synRootPasswordSalt;
    if (!isset(${$this->name}))
      return $this->value;
    else
      if (${$this->name} != '')
        return md5(${$this->name}.$synRootPasswordSalt);
    else
      return;
  }

  //return the sql statement (i.e. `name`='gigi')
  function getSQL() {
    $ret="";
    if ($this->getValue()!="") {
      $ret=$this->getSQLname()."=".fixEncoding($this->getSQLValue());
    }
    return $ret;
  }


  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();

    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="" or $synElmSize[$i]==0) $synElmSize[$i]=$this->size;
    $this->configuration[4]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");
    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class password

?>
