<?
//ALTER TABLE `` ADD `` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;
/*************************************
* class TEXT                         *
* Create a input type="text" obj     *
**************************************/
class synLastUpdate extends synElement {

  //constructor(name, value, label, size, help)
  function synLastUpdate($n="", $v=null , $l=null, $s=255, $h="") {
    if ($n=="") $n = "text".date("his");
    if ($l=="") $l = ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";

    $this->configuration();
  }

  //private function
  function _html() {
    if ($this->value=="") $ret="<img src=\"img/tree_delete.png\" alt=\"Not defined\" />";
    else $ret=$this->value."\n";
    
    return $ret;
  }
  
  //return the sql values (i.e. 'gigi'). In this case none
  function getSQLValue() {
    return "NOW()";
  }

  //return the sql field name (i.e. `name`). In this case none
  //function getSQLName() {
  //  return;
  //}

  
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    $this->configuration[8]="<span style=\"color: darkblue;\">Current Date</span>";

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=0;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
} //end of class text

?>