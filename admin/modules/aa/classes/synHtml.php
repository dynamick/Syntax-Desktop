<?php
/*************************************
* class Html                         *
* Create a class container           *
**************************************/
class synHtml {

  //constructor
  function __construct() {
  }

  //add a form tag
  function form($attribute) {
    return "<form class=\"form-horizontal has-toolbar\" $attribute >\n";
  }

  //add a form tag
  function form_c() {
    return "</form>\n";
  }

  //add a form tag
  function text($attribute, $class='') {
    return "<input type='text' class=\"form-control {$class}\" $attribute />\n";
  }

  //add a form tag
  function hidden( $attributes ) {
    return '<input type="hidden" ' . $this->implodeAttributes( $attributes ) . "/>\n";
  }

  //add a form tag
  function button($attribute, $label, $type='submit') {
    return "<button type='{$type}' {$attribute}>{$label}</button>\n";
  }

  //add a form tag
  function check( $attributes ) {
    return '<input type="checkbox" ' . $this->implodeAttributes( $attributes ) . "/>\n";
  }

  //add a form tag
  function radio($attribute) {
    return "<input type='radio' $attribute />\n";
  }

  function select( $attributes, $qry, $value='', $blank=false) {
    global $db;
    //$db->debug=true;
    $attribute = $this->implodeAttributes( $attributes );
    $ret = "<select class=\"form-control\" {$attribute}>\n";
    if ($blank)
      $ret .= "<option value=\"\"></option>\n";
    if ( !is_array($qry) ) {
      // get options from DB
      $res = $db->Execute($qry);
      while ($arr = $res->FetchRow()) {
        if ($value == $arr[0])
          $sel = ' selected="selected"';
        else
          $sel = '';
        $ret .= "<option value=\"{$arr[0]}\"{$sel}>{$arr[1]}</option>\n";
      }
    } else {
      // get options from array
      foreach ($qry as $k => $v) {
        if ($value == $k)
          $sel = ' selected="selected"';
        else
          $sel = '';
        $ret .= "<option value=\"{$k}\"{$sel}>{$v}</option>\n";
      }
    }
    $ret .= "</select>\n";
    return $ret;
  }

  //add a label tag
  function label( $label, $input='' ) {
    return '<label class="control-label" for="' . $input .'">' . $label . '</label>';
  }

  //add a form tag
  function number( $attributes, $class='' ) {
    return "<input type=\"number\" class=\"form-control {$class}\" " . $this->implodeAttributes( $attributes ) . "/>\n";
  }

  // adds a checkbox with hidden default (always passes a value)
  function boolean( $attributes, $class='' ) {
    $ret = '';
    if ( !is_array($attributes) )
      $attributes = exlpode( ' ', $attributes );

    $hidden_params = array();
    if ( isset($attributes['name']) )
      $hidden_params['name'] = $attributes['name'];
    if ( isset($attributes['value']) )
      $hidden_params['value'] = '';

    return $this->hidden( $hidden_params ) . $this->check( $attributes, $class );
  }

  // array to key=value string
  private function implodeAttributes( $attributes ) {
    if ( is_array( $attributes ) ) {
      //$attributes = http_build_query( $attributes, '', ' ' );
      $attributes = implode( ' ', array_map(
        function ($v, $k) {
          return sprintf( '%s="%s"', $k, $v );
        }, $attributes, array_keys( $attributes )
      ));
    }
    return $attributes;
  }

} //end of class
?>
