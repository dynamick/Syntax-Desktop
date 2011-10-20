<?

/*************************************
* class DATETIME                     *
* Create a input type "Date" obj     *
**************************************/
class synDateTime extends synElement {

  //constructor(name, value, label, size, help)
  function synDateTime($n="", $v=null , $l=null, $s=null, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " DATETIME NOT NULL";

    $this->configuration();
  }

  //private function
  function _html() {
    if ($this->value=="0000-00-00 00:00:00" or $this->value=="") $this->value=date("Y-m-d H:i:s");
    $datepicker="<a href=\"javascript:NewCal('".$this->name."','ddmmyyyy',true)\"><img src=\"images/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Scegli una data\"></a>";
    return "<input type='text' size='25' id='".$this->name."' name='".$this->name."' value='".$this->dateFormat($this->value)."'/> (gg-mm-aaaa) $datepicker"; 
  }
  
  //get the selected/typed value
  function getValue() {
    global ${$this->name};
    if (${$this->name}=="") return $this->value;
    else return $this->dateFormat(${$this->name});
  }

  //get the label of the element
  function getCell() {
    return $this->dateFormat($this->getValue());
  }
  
  //sets the value of the element
  function setValue($v) {
    $this->value = $v;
  }  
  
  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    $this->configuration[8]="<span style=\"color: darkblue;\">Data Corrente</span>";

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
  //private: formats the date in a dd-mm-yyyy (Hours:Min:sec)format
  function dateFormat($value) {
   #$dateHour=split(" ",$value);
   #$splitted=split("-",$dateHour[0]);
    $dateHour=explode(' ',$value);
    $splitted=explode('-',$dateHour[0]);
    $value=$splitted[2]."-".$splitted[1]."-".$splitted[0];
    return $value." ".$dateHour[1];  
  }

  
} //end of class text

?>
