<?php

/*************************************
* class KEY                          *
* Create a input type="hidden" obj   *
**************************************/
class synKey extends synElement {

  //constructor(name, value, label, size, help)
  function synKey($n="", $v=null , $l=null, $s=11, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "key";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = 11;
    $this->help  = $h;
    $this->db    = " int(".$this->size.") NOT NULL AUTO_INCREMENT ";
    $this->is_key=true;
  }

  //private function
  function _html() {
    $value = $this->value ? $this->value : '&nbsp;';
    $html = "<div class=\"form-control-static\">{$value}</div>"
          . "<input type=\"hidden\" name=\"{$this->name}\" maxsize=\"{$this->size}\" value=\"{$this->value}\">";
    return $html;
  }

  //get the selected/typed value
  function getValue() {
    global ${$this->name}, $db;
    $c = $this->container;
    $table= $c->table;

    //echo "<hr>";
    //echo ${$this->name}." .name<br>";
    //echo $this->value." .value<br>";
    //echo $table." .tablename<br>";
    //echo "<hr>";

    //calculate the next key
    $res=$db->Execute("SHOW TABLE STATUS LIKE \"$table\"");
    $arr=$res->FetchRow();
    $nextKey=$arr["Auto_increment"];
    //echo $nextKey." .nextKey<br>";

    if ($this->value=="") return $nextKey;
    else return $this->value;

  }


  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    $synHtml = new synHtml();
    $synElmSize[$i]=11;
    $this->configuration[8]="<span style=\"color: darkgreen;\">Auto incrementante (size: ".$synElmSize[$i].")</span>";

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class text

?>
