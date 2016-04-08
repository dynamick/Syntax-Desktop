<?php

/*************************************
* class JSON hash                    *
* stores data in Json format         *
**************************************/
class synJsonHash extends synElement {
  var $type;

  //constructor(name, value, label, size, help)
  function synJsonHash( $n='', $v=null , $l=null, $s=0, $h='' ) {
    if ($n=="")
      $n = 'json' . date( 'his' );
    if ($l=="")
      $l = ucfirst( $n );

    $this->type  = 'textarea';
    $this->name  = $n;
    if ($v==null)
      $this->value = null;
    else
      $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = ' text NOT NULL';
  }

  //set the Type of TextArea
  function setPath($value) {
    $this->type = $value;
  }

  //private function
  function _html() {
    $value = json_decode( $this->translate( $this->value ), true );
    if ( is_array($value) ) {
      $contents = '<table class="table table-striped table-bordered">';
      foreach( $value as $k => $v ) {
        if (is_array($v))
          $v = implode( ', <br>', $v );
        $contents .= "<tr><th width=\"15%\">{$k}</th><td>{$v}</td></tr>\n";
      }
      $contents .= '</table>';

      return $contents;
    }
    return false;
  }

  // get the label of the element
  function getCell() {
    $value = json_decode( $this->translate( $this->value ), true );
    $ar_content = array();
    $max = 2048;
    $count = 0;
    if ( is_array($value) ) {
      foreach( $value as $k => $v ) {
        if ( is_array($v) )
          $v = implode( ', ', $v );
        $piece = "<span class=\"text-info\">{$k}:</span> {$v}";
        $count += strlen( $piece );
        // can't break an html string. truncate it carefully cut instead:
        if ( $count > $max ) {
          $ar_content[] = '...';
          break;
        }
        $ar_content[] = $piece;
      }
      //return mb_substr( implode( '; ', $ar_content ), 0, 180, 'UTF-8' );
      return implode( '; ', $ar_content );
    }
    return false;
  }



  //function for the auto-configuration
  function configuration( $i='', $k=99 ) {
    global
      $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp, $synElmPath,
      $synElmSize, $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;

    $synHtml = new synHtml();
    //parent::configuration();
    /*if ( !isset($synElmSize[$i])
      || $synElmSize[$i] == ''
       )*/
    $synElmSize[$i] = 0;
    //$this->configuration[4] = 'Max Size: ' . $synHtml->text(" name=\"synElmSize[{$i}]\" value=\"{$synElmSize[$i]}\"") . ' (0 if unlimited text size)';

    $_SESSION['synChkKey'][$i]        = 1;
    $_SESSION['synChkVisible'][$i]    = 1;
    $_SESSION['synChkEditable'][$i]   = 1;
    $_SESSION['synChkMultilang'][$i]  = 1;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }


} //end of class text

?>
