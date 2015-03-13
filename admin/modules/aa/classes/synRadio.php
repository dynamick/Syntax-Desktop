<?php
/*************************************
* class Radio                        *
* Create a input radio obj           *
**************************************/
class synRadio extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synRadio( $n = '', $v = '', $l = '', $s = '', $h = '' ) {
    global $$n;

    if ($n == '')
      $n = 'text'.date('his');
    if ($l == '')
      $l = ucfirst($n);
    $this->type    = 'enum';
    $this->name    = $n;
    $this->label   = $l;
    $this->help    = $h;
    $arvalues      = explode('|', $this->qry);
    //$this->db      = "  enum('".implode("','", $arvalues)."') NOT NULL default '".$arvalues[0]."'";
  }

  //private function

  // input select
  function _html() {
    if ($this->chkTargetMultilang() == 1)
      $this->multilang = 1;
    $selected = ($this->value ? $this->value : false);
    $options = $this->createArray2( $this->qry, $this->path );

    // relies on http://davidstutz.github.io/bootstrap-multiselect/
    $input = "<select id=\"{$this->name}\" name=\"{$this->name}\" class=\"multi-select\">";
    if (is_array($options)) {
      $count = 0;
      foreach($options as $v) {
        $id      = isset($v['id'])    ? $v['id']    : '';
        $value   = isset($v['value']) ? $v['value'] : '';
        $checked = (($id==$selected || !$selected && $count == 0) ? ' selected="selected"' : '');
        $input  .= "<option value=\"{$id}\"{$checked}/>{$value}</option>\n";

        $count ++;
      }
    }
    $input .= '</select>';

    return $input;
  }

  // input radio
  function _html_RADIO() {
    if ($this->chkTargetMultilang() == 1)
      $this->multilang = 1;
    $selected = ($this->value ? $this->value : false);
    $options = $this->createArray2( $this->qry, $this->path );

    if (is_array($options)) {
      $count = 0;
      $txt = '';
      foreach($options as $v) {
        $id      = isset($v['id']) ? $v['id'] : '';
        $value   = isset($v['value']) ? $v['value'] : '';
        $checked = (($id==$selected || !$selected && $count == 0) ? ' checked="checked"' : '');
        //$txt    .= " <input type=\"radio\" name=\"".$this->name."\" VALUE=\"".$id."\"".$checked."/> ".$value;
        $txt    .= "<div class=\"radio\"><label><input type=\"radio\" name=\"{$this->name}\" value=\"{$id}\" {$checked}> {$value}</label></div>";
        $count ++;
      }
    }
    return $txt;
  }

  function setPath($path) {
    $this->path = $path;
  }

  function setQry($qry) {
    $this->qry = $qry;
    $arvalues = explode('|', $this->qry);
    $this->db = "  enum('".implode("','", $arvalues)."') NOT NULL default '{$arvalues[0]}' ";
  }

  function createArray($qry,$null=false) {
    if ($null==true)
      $ret['NULL'] = '';
    $arvalues = explode('|', $qry);
    foreach ($arvalues as $k=>$v)
      $ret[$k] = $v;
    return $ret;
  }

  function createArray2($qry,$null=false) {
    if ($null==true)
      $ret['NULL'] = '';
    $arvalues = explode('|', $qry);
    foreach ($arvalues as $v) {
      $r['id'] = $v;
      $r['value'] = $v;
      $ret[] = $r;
    }
    return $ret;
  }

  //check if the target service is multilang
  function chkTargetMultilang($qry='') {
    global $db;
    $ret = false;
    $qry = "SELECT * FROM aa_services";
    $res = $db->Execute($qry);
    while ( $arr = $res->FetchRow()) {
      $table = $arr['syntable'];
      if (strpos($this->qry, $table) !== false)
        $ret = $arr['multilang'];
    }
    return $ret;
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp,$synElmPath;
    global $synElmQry;
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;

    $synHtml = new synHtml();
    //parent::configuration();

    $value = '';
    if (isset($synElmQry[$i])) $value = htmlentities($synElmQry[$i]);
    $this->configuration[4]="Query: ".$synHtml->text(" name=\"synElmQry[$i]\" value=\"{$value}\"");

    //enable or disable the 3 check at the last configuration step
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }

} //end of class radio

?>