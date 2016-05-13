<?php

/*************************************
* class TEXT                         *
* Create a input type="text" obj     *
**************************************/
class synAddressPicker extends synElement {

  //constructor(name, value, label, size, help)
  function __construct($n="", $v=null , $l=null, $s=255, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) {
      global $$n;
      $this->value = (isset($_REQUEST[$n])) ? $_REQUEST[$n] : '';
    } else
      $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " VARCHAR(" . $this->size . ") NOT NULL";

    $this->configuration();
  }



  //private function
  function _html() {
    $value = str_replace("\"", "&quot;", ($this->translate($this->getValue())));
    if( trim( $value ) == "" )
      $value = "|0|0";

    $address = explode("|", $value);
    // WARNING - don't update typeahead.bundle until typeahead.addresspicker supports v.0.11
    $html = <<<HTML
      <div class="map-widget">
        <div class="map-widget-map" id="map"></div>
        <div class="map-widget-controls">
          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-map-marker"></i>
            </span>
            <input type="text" value="{$address[0]}" class="form-control"
              id="address-picker" placeholder="Enter an address"/>
            <span class="input-group-addon">
              <b>Lat:</b>
              <span id="lat">{$address[1]}</span>
            </span>
            <span class="input-group-addon">
              <b>Lon:</b>
              <span id="lng">{$address[2]}</span>
            </span>
          </div>
        </div>
      </div>
      <input type="hidden" name="{$this->name}" id="address-data" value="{$value}">
HTML;

    return $html;
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
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=1;
    $_SESSION["synChkMultilang"][$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class text

?>
