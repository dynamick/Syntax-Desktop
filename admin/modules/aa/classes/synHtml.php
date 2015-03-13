<?php
/*************************************
* class Html                         *
* Create a class container           *
**************************************/
class synHtml {
  
  //constructor
  function html() {
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
  function text($attribute) {
    return "<input type='text' class=\"form-control\" $attribute />\n";
  }
  
  //add a form tag
  function hidden($attribute) {
    return "<input type='hidden' $attribute />\n";
  }
  
  //add a form tag
  function button($attribute, $label, $type='submit') {
    return "<button type='{$type}' {$attribute}>{$label}</button>\n";
  }

  //add a form tag
  function check($attribute) {
    return "<input type='checkbox' $attribute />\n";
  }

  //add a form tag
  function radio($attribute) {
    return "<input type='radio' $attribute />\n";
  }
  
  function select($attribute, $qry, $value="",$blank=false) {
    global $db;
    //$db->debug=true;
    $ret="<select class=\"form-control\" $attribute>\n";
    if ($blank) $ret.="<option value=\"\"></option>";
    //qry based select
    if (!is_array($qry)) {
      $res=$db->Execute($qry);
      while ($arr=$res->FetchRow()) {
        if ($value==$arr[0]) $sel="selected=\"selected\" "; else $sel=""; 
        $ret.="<option value=\"".$arr[0]."\" $sel>".$arr[1]."</option>\n";
      }
    } else {
    //array based select
      foreach ($qry as $k=>$v) {
        if ($value==$v) $sel="selected=\"selected\" "; else $sel=""; 
        $ret.="<option value=\"".$k."\" $sel>".$v."</option>\n";
      }
    }
    $ret.="</select>\n";
    return $ret;
  }

} //end of class 
?>
