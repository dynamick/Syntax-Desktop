<?php

/*************************************
* class SLUG                         *
* Create a input type="text" obj     *
**************************************/
class synSlug extends synElement {

  //constructor(name, value, label, size, help)
  function __construct( $n='', $v=null , $l=null, $s=1024, $h='' ) {
    if ($n=='')
      $n = 'text' . date('his');
    if ($l=='')
      $l = ucfirst($n);

    $this->type = 'text';
    $this->name = $n;
    if ($v==null)
      $this->value = ( isset( $_REQUEST[$n] ) ) ? $_REQUEST[$n] : '';
    else
      $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = ' VARCHAR(' . $this->size . ') NOT NULL';

    $this->configuration();
    $this->initCallback();
  }


  //private function
  function _html1() {
    $value = str_replace( "\"", "&quot;", ( $this->translate( $this->getValue() ) ) );
    return "<input disabled type='text' name='{$this->name}' maxsize='{$this->size}' value=\"{$value}\" class=\"form-control\"/>";
  }

  protected function _html() {
    $value = str_replace( "\"", "&quot;", ( $this->translate( $this->getValue() ) ) );
    $ret = <<<EOHTML
    <div class="input-group triggerable-group">
      <input type="text" class="form-control" name="{$this->name}" maxsize="{$this->size}" value="{$value}" readonly="readonly" data-oldvalue="{$value}" />
      <span class="input-group-addon">
        <input type="checkbox" class="trigger-input" aria-label="trigger slug input" tabindex="-1"
          data-original-title="Enable editing" data-placement="top" data-toggle="tooltip">
      </span>
    </div>
EOHTML;

    return $ret;
  }


  function initCallback() {
    $container = synContainer::getInstance();
    $container->add_callback( 'update', array( $this, 'updateSlug' ) );
    $container->add_callback( 'insert', array( $this, 'insertSlug' ) );
  }


  function updateSlug() {
    $key = $this->container->getKeyValue();
    updateSlug($key);
    // TODO: if changed, automatically add a redirect
    return true;
  }


  function insertSlug() {
    $key = $this->container->getKeyValue();
    insertSlug($key);

    return true;
  }


  //function for the auto-configuration
  function configuration($i='',$k=99) {
    global
      $synElmLabel, $synElmName, $synElmSize, $synChkMultilang, $synElmPath, $synChkVisible,
      $synElmValue, $synElmType, $synElmHelp, $synChkEditable, $synChkKey;

    $synHtml = new synHtml();
    if ( !isset($synElmSize[$i])
      || $synElmSize[$i] == ''
      )
      $synElmSize[$i] = $this->size;

    $this->configuration[4] = 'Dimensione: ' . $synHtml->text(" name=\"synElmSize[{$i}]\" value=\"{$synElmSize[$i]}\" ");

    //enable or disable the 3 check at the last configuration step
    $_SESSION['synChkKey'][$i]       = 1;
    $_SESSION['synChkVisible'][$i]   = 1;
    $_SESSION['synChkEditable'][$i]  = 1;
    $_SESSION['synChkMultilang'][$i] = 1;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }
}

//end of class synSlug
