<?

/*************************************
* class SELECT                       *
* Create a input select obj          *
**************************************/
class synOwner extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synOwner($n="", $v="", $l="", $s=11, $h="") {
    global $$n;
    
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);
    $this->type = "file";
    $this->name  = $n;
    if (isset($$n) and $$n) { $this->selected = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " int(".$this->size.") NOT NULL";
  }

  //private function
  function _html() {
    $disable = "";
    $selected="";
    $txt="<select name='".$this->name."' $disable >";
    //if (is_array($this->value)) {
    if (is_array($_SESSION["synGroupChild"])) {
      foreach ($_SESSION["synGroupChild"] as $v=>$k) {
        $selected = ($this->selected==$k ? " selected=\"selected\"" : "");
        $me = ($k==$_SESSION["synGroup"] ? "[me] " : "");
        $nUser = $_SESSION["synUsersInGroup"][$k];
        $opt = $me.$v." (".$nUser." ".($nUser==1 ? "user" : "users").")";

        $txt.=" <option value=\"".$k."\"".$selected.">".$opt."</option>\n";
      }
    } else $txt.="  <option value=\"".$_SESSION["synGroup"]."\">(me)</option>\n";

    $txt.="</select>\n";
    return $txt;
  }

  //sets the value of the element
  function setValue($v) {
    global $n, $$n;
    //if (is_array($v)) $this->value = $v;
    if (!isset($_REQUEST[$$n])) $this->value = $_SESSION["synGroupChild"];
    $this->selected = $v;
  }  

  //sets the value of the element
  function getValue() {
    if ($this->selected=="") return $_SESSION["synGroup"];
    return $this->selected;
  }  

  //get the label of the element
  function getCell() {
    global $db;
    $qry="SELECT * FROM aa_groups WHERE id=".$this->selected;
    if ($res=$db->Execute($qry)) {
      $arr=$res->FetchRow();
      $ret="<em>".$arr["name"]."</em>";
      if ($arr["id"]==$_SESSION["synGroup"]) $ret="me"; 
    } else $ret="";
    return $ret;
  }
  
  
  function setPath($path) {$this->path=$path;}

  function setQry($qry) {
    $this->qry=$qry;
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmQry;
    global $synElmQry;
    $synHtml = new synHtml();
    $this->configuration=array();
    
    global $db;
    //$res=$db->Execute("SELECT * FROM aa_services order by name");
    //$txt="<select name=\"synElmQry[$i]\" >";
    //while ($arr=$res->FetchRow()) {
    //  if (strpos($synElmQry[$i],$arr["syntable"])===false ) $selected=""; else $selected="selected=\"selected\""; 
    //  if ($arr["syntable"]!="") $txt.="<OPTION VALUE=\"SELECT * FROM ".$arr["syntable"]."\" $selected>".translate($arr["name"])."</option>";
    //}
    //$txt.="</select>\n";

    //$this->configuration[4]="Query: ".$txt;

    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    //$this->configuration[]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable,$synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
  
} //end of class inputfile

?>
