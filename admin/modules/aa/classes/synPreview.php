<?

/******************************************
* class synPreview                        *
* Create a preview button obj             *
* PS: remember to insert an attribute 
* preview="fieldname" in the pubblic site
* page where the preview must work.
* fieldname=the name of field the content of
* which will be replaced by preview.
* I.E. in the article page of the pubblic site
* we insert <h1 preview="title">Goal!!!</h1>
* and the txt Goal!! will be replaced by
* the content of title field in the admin
* area.
******************************************/
class synPreview extends synElement {
  var $mat;

  //constructor(name, value, label, size, help)
  function synPreview($n="", $v=null , $l=null, $s=255, $h="") {
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);

    $this->type = "text";
    $this->name  = $n;
    if ($v==null) { global $$n; $this->value = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " VARCHAR(".$this->size.") NOT NULL";

    $this->configuration();
  }

  //private function
  function _html() {
    //return "<input type='submit' value='".$this->name."' onclick=\"window.open('preview','preview','width=800,height=600'); document.forms[0].target='preview'; document.forms[0].action='preview.php'; \"/>"; 
    return "<a onclick=\"window.open('blank.html','preview','width=830,height=600,scrollbars=yes,left=20,top=20'); document.forms[0].target='preview'; document.forms[0].oldaction=document.forms[0].action; document.forms[0].action='preview.php?synTarget=$this->mat'; document.forms[0].submit(); document.forms[0].target='_self'; document.forms[0].action=document.forms[0].oldaction;\"/><img src=\"images/preview.gif\" alt=\"preview\" style=\"cursor: pointer; margin-left: 20px;\" onmouseover=\"this.src='images/preview_over.gif';\" onmouseout=\"this.src='images/preview.gif';\" ></a>"; 
  }
  
  //set the target path
  function setPath($path) {
    $this->mat = $path;
    return true;
  }

  //return the sql values (i.e. 'gigi'). In this case none
  function getSQLValue() {
    return;
  }

  //return the sql field name (i.e. `name`). In this case none
  function getSQLName() {
    return;
  }
  
  
  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp,$synElmPath;
    global $synElmSize;
    $synHtml = new synHtml();
    //parent::configuration();
    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[4]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //if (!isset($synElmPath[$i]) or $synElmPath[$i]=="") $synElmPath[$i]=$this->mat;
    $tmp_path = isset($synElmPath[$i]) ? $synElmPath[$i] : "";
    $this->configuration[5]="Pagina di preview: ".$synHtml->text(" name=\"synElmPath[$i]\" value=\"".$tmp_path."\"")."<br><span class='help'>Per esempio: /index.php o /news/art.php</span> ";

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable;
    $_SESSION["synChkKey"][$i]=0;
    $_SESSION["synChkVisible"][$i]=0;
    $_SESSION["synChkEditable"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
  
} //end of class text

?>
