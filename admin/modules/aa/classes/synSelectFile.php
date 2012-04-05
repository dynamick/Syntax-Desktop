<?

/*************************************
* class SELECT                       *
* Create a input select obj          *
**************************************/
class synSelectFile extends synElement {

  var $selected;
  var $qry;
  var $path;

  //constructor(name, value, label, size, help)
  function synSelectfile($n="", $v="", $l="", $s=255, $h="") {
    global $$n;

    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);
    $this->type = "file";
    $this->name  = $n;
    if (isset($$n) and $$n) { $this->selected = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " varchar(".$this->size.") NOT NULL";
  }

  //private function
  function _html() {
    $this->value = $this->createArray($this->translatePath(),$this->path);
    $txt="<select name='".$this->name."' onchange=\"document.getElementById('select".$this->name."').src='".$this->translatePath()."'+this.value;\">";
    if (is_array($this->value)) {
      while (list ($k, $v) = each ($this->value)) {
        if ($this->translate($this->getValue())==$k) $selected="selected=\"selected\""; else $selected="";
        if(
          stripos($k, '.gif')>0 ||
          stripos($k, '.jpg')>0 ||
          stripos($k, '.png')>0
        ){// se � una immagine la uso come sfondo (solo Firefox)
          $icon = $this->translatePath().'/'.$k;
          $style =" style=\"padding:2px 0 2px 20px; background:url('$icon') no-repeat 0 50%;\"";
        } else $style = '';
        $txt.="<option value=\"".$k."\"{$selected}{$style}>".$v."</option>\n";
      }
      reset($this->value);
    }
    $txt.="</select> <img id=\"select".$this->name."\" height=\"20\" src=\"".$this->translatePath().$this->translate($this->getValue())."\" onerror=\"this.src='images/blank.gif' \"  />\n";
    return $txt;
  }

  //sets the value of the element
  function setValue($v) {
    #global $$n;
    #if (!isset($_REQUEST[$$n])) 
    $this->value = $this->createArray($this->translatePath(),$this->path);
    $this->selected = $v;
  }

  //sets the value of the element
  function getValue() {
    return $this->selected;
  }

  //get the label of the element
  function getCell() {
    if (is_array($this->value)) {
      if (array_key_exists($this->translate($this->selected), $this->value))
        //return "<img id=\"select".$this->name."\" height=\"20\" src=\"".$this->translatePath().$this->translate($this->getValue())."\" onerror=\"this.src='images/blank.gif' \" />\n ".$this->translate($this->getValue());
        return $this->translate($this->getValue());
      else return "<font color='red'>x</font>";
    } else return $this->selected;
  }

  function setPath($path) {$this->path=$path;}

  //translate path and insert dynamic content
  function translatePath() {
    global $synAdminPath;
    $path=$this->qry;
    if (strpos($path,"�syntaxRelativePath�")!==false) $path=str_replace("�syntaxRelativePath�",$synAdminPath,$path);
    return $path;
  }

  function setQry($qry) {$this->qry=$qry;}

  function createArray($path,$null=false) {
    global $synAbsolutePath;
    if ($null==true) $ret['']="[null]";
    if ($dir = @opendir($synAbsolutePath.$path)) {
      while (false !== ($file = readdir($dir))) {
        if (
          $file!="." &&
          $file!=".." &&
          $file{0}!='_' //esclude tutti i file che iniziano con _
        ) $ret[$file]=$file;
      }
      closedir($dir);
      unset($file);
      asort($ret); // ordine alfabetico
    }
    return $ret;
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp,$synElmPath;
    global $synElmQry;
    $synHtml = new synHtml();
    //parent::configuration();
    $this->configuration[4]="Path: ".$synHtml->text(" name=\"synElmQry[$i]\" value=\"".htmlentities($synElmQry[$i])."\"")."<br><span style='color: gray'>Insert directory path without DOCUMENT ROOT.<br />I.e. <strong>/mysite/syntax/public/templates/</strong> <br> Use <strong>§syntaxRelativePath§</strong><br />for dynamically insert Syntax Desktop relative path.</span>";

    if (!isset($synElmPath[$i]) or $synElmPath[$i]=="") $checked=""; else $checked=" checked='checked' ";
    $this->configuration[5]="NULL: ".$synHtml->hidden(" name=\"synElmPath[$i]\" value=\"\"").$synHtml->check(" name=\"synElmPath[$i]\" value=\"1\" $checked");

    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
    $this->configuration[5].=$synHtml->hidden(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }




} //end of class inputfile

?>
