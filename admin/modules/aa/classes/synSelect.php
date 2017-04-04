<?php

/*************************************
* class SELECT                       *
* Create a input select obj          *
**************************************/
class synSelect extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function __construct($n="", $v="", $l="", $s=11, $h="") {
    global $$n;

    if ($n=="")
      $n =  "text".date("his");
    if ($l=="")
      $l =  ucfirst($n);
    $this->type = "file";
    $this->name  = $n;
    if (isset($$n) and $$n)
      $this->selected = $$n;
    else
      $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = ' INT(' . $this->size . ') NOT NULL DEFAULT 0';
  }

  //private function
  function _html() {
    $disable = "";
    $selected = "";
    $this->value = $this->createArray( $this->qry, $this->path );
    if ( $this->chkTargetMultilang() == 1 )
      $this->multilang=1;
    $txt = "<select name=\"{$this->name}\" {$disable} class=\"form-control\">";
    if (is_array($this->value)) {
      foreach ($this->value as $k => $v) {
        $selected = ($this->selected == $k) ? 'selected="selected"' : NULL;
        $label = ($this->multilang==1) ? $this->translate($v, true) : $v;
        $txt .= "<option value=\"{$k}\" {$selected}>{$label}</option>";
      }
    }
    $txt .= "</select>\n";

    return $txt;
  }

  //sets the value of the element
  function setValue($v) {
    global $n, $$n;
    //if (is_array($v)) $this->value = $v;
    if (!isset($_REQUEST[$$n]))
      $this->value = $this->createArray($this->qry, $this->path);
    $this->selected = $v;
  }

  //sets the value of the element
  function getValue() {
    return $this->selected;
  }

  function getSQLValue() {
    return intval($this->getValue());
  }


  //get the label of the element
  function getCell() {
    if ( $this->chkTargetMultilang() == 1 )
      $this->multilang = 1;
    if (is_array($this->value)) {
      if (array_key_exists($this->selected, $this->value))
        return $this->translate($this->value[$this->selected], true);
      else
        return '<span class="text-danger">x</font>';
    } else
      return $this->selected;
  }

  //check if the target service is multilang
  function chkTargetMultilang($val='') {
    global $db;
    $ret = false;
    // discover an "owner field" in the service
    $table = preg_match( "/from (\w+)[\s]?(.*)/i", $this->qry, $matches );
    $destTable = $matches[1];
    if ($destTable != "") {
      $qry1 = "SELECT * FROM aa_services WHERE syntable = '{$destTable}'";
      $res = $db->Execute($qry1);
      $arr = $res->FetchRow();
      $id = $arr["id"];
      $qry2 = "SELECT * FROM aa_services_element WHERE container='{$id}' ORDER BY `order` LIMIT 1, 1";
      $res = $db->Execute($qry2);
      //$arr=$res->FetchRow(); //salto il primo elemento: campo "value" dell'option
      $arr = $res->FetchRow(); //prendo il secondo elemento: campo "innerHTML" dell'option
      $ret = $arr["ismultilang"];
    }
    return $ret;
  }

  function setPath($path) {
  	$this->path = $path;
  }

  function setQry($qry) {
    $this->qry = $qry;
  }

  function createArray($qry,$null=false) {
    global $db;
    $qry = $this->compileQry($qry);

    $ret = array();
    $ownerField = '';
    // discover an "owner field" in the service
    $table = preg_match("/from (\w+)[\s]?(.*)/i", $qry, $matches);
    $destTable = $matches[1];
    if ($destTable != '') {
      $qry1 = "SELECT * FROM aa_services WHERE syntable='{$destTable}'";
      $res = $db->Execute($qry1);
      $arr = $res->FetchRow();
      $id = $arr["id"];
      $qry2 = "SELECT ase.*,ae.classname FROM aa_services_element ase JOIN aa_element ae ON ase.type=ae.id WHERE ase.container='{$id}' ORDER BY ase.`order`";
      $res = $db->Execute($qry2);
      while ($arr = $res->FetchRow()) {
        if (strtolower($arr["classname"]) == "synowner")
          $ownerField = $arr["name"];
      }
    }

    // ATTENTION: This is a security control. All the "select" widget on the
    // "aa_groups" table are filtered with the allowed groups.
    if ($destTable == "aa_groups") {
      $ret = array_flip($_SESSION["synGroupChild"]);
    } else {
      $res = $db->Execute($qry);
      if ($null == true)
        $ret[] = '';
      while ($arr = $res->FetchRow()) {
        if ($ownerField == "" OR $arr[$ownerField] == "" OR in_array($arr[$ownerField], $_SESSION["synGroupChild"]) OR $this->selected == $arr[0]) {
          $ret[$arr[0]] = $arr[1];
        }
      }
    } //end if aa_groups

    return $ret;
  }

  //function for the auto-configuration
  function configuration( $i = '', $k = 99 ) {
    global
      $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp, $synElmQry, $synElmPath,
      $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang, $db;

    $synHtml = new synHtml();
    $options = array();

    // Select service/table
    $res = $db->Execute( "SELECT * FROM `aa_services`" );
    while ($arr = $res->FetchRow() )
      if ( $arr['syntable'] != '' )
        $options[ "SELECT * FROM {$arr['syntable']}" ] = trim( translate($arr['name']) );
    asort( $options, SORT_NATURAL | SORT_FLAG_CASE ); // sort by translated name
    $params = array(
      'name' => "synElmQry[{$i}]"
    );
    $this->configuration[4] = 'Query: ' . $synHtml->select( $params, $options, $synElmQry[$i], $synElmPath[$i] );


    // Length of the field
    if ( !isset($synElmSize[$i]) or $synElmSize[$i] == '' )
      $synElmSize[$i] = $this->size;
    $params = array(
      'name'  => "synElmSize[{$i}]",
      'value' => $synElmSize[$i],
      'step'  => 1,
      'min'   => 1
    );
    $this->configuration[5] = 'Dimensione: ' . $synHtml->number( $params );


    // Accept NULL values?
    $params = array(
      'name'  => "synElmPath[{$i}]",
      'value' => 1,
      'class' => 'syn-check',
      //'data-size' => 'small'
    );
    if ( isset($synElmPath[$i]) && !empty( $synElmPath[$i] ) )
      $params['checked'] = 'checked';
    $this->configuration[6] = 'NULL: <br>' . $synHtml->boolean( $params ) ;


    //enable or disable the 3 check at the last configuration step
    $_SESSION['synChkKey'][$i]        = 1;
    $_SESSION['synChkVisible'][$i]    = 1;
    $_SESSION['synChkEditable'][$i]   = 0;
    $_SESSION['synChkMultilang'][$i]  = 0;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }


} //end of class select

?>
