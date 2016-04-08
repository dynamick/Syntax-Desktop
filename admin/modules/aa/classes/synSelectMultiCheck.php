<?php

/*************************************
* class SELECT Multi Check           *
* Create a input select obj          *
**************************************/
class synSelectMultiCheck extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synSelectMultiCheck( $n = '', $v = '', $l = '', $s = 255, $h = '' ) {
    global $$n;

    if ($n=='')
      $n = 'text'.date('his');
    if ($l=='')
      $l = ucfirst($n);
    $this->type  = 'text';
    $this->name  = $n;
    if (isset($$n) and $$n){
      $this->selected = $$n;
    } else {
      $this->value = $v;
    }
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = ' varchar(255) NOT NULL ';
  }

  //private function
  function _html() {
    // relies on http://davidstutz.github.io/bootstrap-multiselect/
    $input  = "<input name=\"{$this->name}\" type=\"hidden\" value=\"\">"; // in case of empty selection, otherwise nothing passes to the DB
    $input .= "<select id=\"{$this->name}\" name=\"{$this->name}\" multiple=\"multiple\" class=\"multi-select\" data-option-name=\"{$this->name}[]\">";
    if ($this->chkTargetMultilang($this->qry) == 1)
      $this->multilang = 1;

    $this->value = $this->createArray2( $this->qry, $this->path );
    $selArr      = explode("|", $this->selected);

    if (is_array($this->value)) {
      $current_group = '';
      foreach( $this->value as $v ) {
        $id       = isset($v['id'])    ? $v['id'] : '';
        $value    = isset($v['value']) ? $v['value'] : '';
        $group    = isset($v['group']) ? trim($v['group']) : '';
        $selected = (in_array($id, $selArr)) ? ' selected="selected"' : '';

        if (!empty($group)) {
          if ($group != $current_group) {
            if (!empty($current_group))
              $input .= "</optgroup>\n";
            $input .= "<optgroup label=\"{$group}\">\n";
            $current_group = $group;
          }
        }
        //$txt .= "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$this->name}[]\" value=\"{$id}\"{$selected}/> {$this->translate($value, true)}</label></div>".$eol;
        $input .= "<option value=\"{$id}\"{$selected}>{$this->translate($value, true)}</option>\n";
      }
      if (!empty($current_group))
        $input .= "</optgroup>\n";
    }
    $input .= '</select>';

    return $input;
  }

  //sets the value of the element
  function setValue($v) {
    $this->value = $this->createArray( $this->qry, $this->path );
    $this->selected = $v;
    return;
  }

  //sets the value of the element
  function getValue() {
    if ( is_array($this->selected) )
      return implode( '|', $this->selected );
    else
      return;
  }

  //get the label of the element
  function getCell() {
    $ret = '';
    if ($this->chkTargetMultilang($this->qry) == 1)
      $this->multilang = 1;
    $this->value = $this->createArray2( $this->qry, $this->path );
    $selArr = explode('|', $this->selected);
    if ( is_array($this->value) ) {
      $ret = array();
      foreach($this->value as $v)
        if (in_array($v['id'], $selArr))
          $ret[] = $this->translate( $v['value']);
      $ret = implode(', ', $ret);
    }
    return $ret;
  }

  function setPath($path) {
    $this->path = $path;
  }

  function setQry($qry) {
    $this->qry = $qry;
  }

  function createArray( $qry, $null = false ) {
    global $db;
    $qry = $this->compileQry($qry);
    $res = $db->Execute($qry);
    if ($null === true)
      $ret['NULL'] = '';
    while ($arr = $res->FetchRow())
      $ret[$arr[0]] = $arr[1];
    return $ret;
  }

  function createArray2( $qry, $null=false ) {
    global $db;
    $qry = $this->compileQry($qry);
    $res = $db->Execute($qry);
    $ret = array();
    if ( $null === true )
      $ret['NULL'] = '';
    while ( $arr = $res->FetchRow() ) {
      $r['id'] = $arr[0];
      $r['value'] = $arr[1];
      if ( isset($arr[2]) && !empty($arr[2]) )
        $r['group'] = $arr[2];
      $ret[] = $r;
    }
    return $ret;
  }

  //function for the auto-configuration
  function configuration( $i='', $k=99 ) {
    global
      $synElmName,
      $synElmType,
      $synElmLabel,
      $synElmSize,
      $synElmHelp,
      $synElmPath,
      $synElmQry,
      $synChkKey,
      $synChkVisible,
      $synChkEditable,
      $synChkMultilang;

    $synHtml = new synHtml();
    $tmp_val = isset($synElmQry[$i])
             ? htmlentities($synElmQry[$i])
             : '';
    $this->configuration[4] = "Query: " . $synHtml->text(" name=\"synElmQry[{$i}]\" value=\"{$tmp_val}\"");

    //enable or disable the 3 check at the last configuration step
    $_SESSION['synChkKey'][$i] = 1;
    $_SESSION['synChkVisible'][$i] = 1;
    $_SESSION['synChkEditable'][$i] = 0;
    $_SESSION['synChkMultilang'][$i] = 0;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }

} //end of class inputfile

?>
