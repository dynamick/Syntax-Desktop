<?php

/*************************************
* class ELEMENT                      *
* Create a basic element             *
**************************************/
class synElement {
  var $type;
  var $name;
  var $value;
  var $label;
  var $size;
  var $help;
  var $is_key = false;
  var $is_join = false;
  var $container;
  //the field is visible in the column
  var $list;
  //the name of the column
  var $colname;
  //the field is editable from index
  var $editable;
  //the field is multilanguage
  var $multilang=false;
  //the configuration array
  var $configuration;
  //allowed file types
  var $mime_type_allowed = array(
    'application/pdf',
    'application/zip',
    'application/x-zip',
    'application/x-gzip',
    'application/x-zip-compressed',
    'application/msword',
    'application/msexcel',
    'application/mspowerpoint',
    'application/vnd.ms-excel',
    'application/vnd.ms-powerpoint',
    'application/png',
    'application/jpg',
    'application/x-png',
    'image/x-png',
    'application/x-jpg',
    'image/gif',
    'image/tiff',
    'image/bmp',
    'image/x-xbitmap',
    'image/png',
    'image/x-png',
    'image/jpg',
    'image/jpeg',
    'image/pjpeg',
    'image/jpe_',
    'image/jp_',
    'image/pipeg',
    'image/vnd.swiftview-jpeg',
    'audio/mpeg',
    'audio/x-mpeg',
    'audio/x-wav',
    'video/mpeg',
    'video/quicktime',
    'video/msvideo',
    'video/x-msvideo'
    );

  //constructor
  function synElement() {
    $this->configuration();
  }
  
  //get the htmlElement with a particular value
  function getHtml() {
    return $this->_html();
  }

  //get the htmlElement with a particular value for the multilang 
  function getHtmlMultilang() {
    $ret="<input type=\"hidden\" name=\"".$this->name."_synmultilang\" value=\"".htmlentities($this->getValue())."\" />";
    return $ret;
  }
  

  //get the label of the element
  function getLabel() {
    return "<div class=\"label\" tooltip=\"".$this->help."\">".$this->label."</a>";
  }

  //get the label help of the element
  function getHelp() {
    return "<span class=\"help\">".$this->help."</span>\n";
  }

  //get the selected/typed value
  function getValue() {
    if (isset($_REQUEST[$this->name]))$ret=$_REQUEST[$this->name];
    else $ret=$this->value;
    return $ret;                       
  }


  //return the sql values (i.e. 'gigi')
  function getSQLValue() {
    $ret=$this->getValue();
    if (!get_magic_quotes_gpc()) {
      $ret=str_replace("\\","\\\\",$ret);
      $ret=str_replace("\\'","'",$ret);
      $ret=str_replace("'","\'",$ret);
    }
    return "'".($ret)."'";
  } 

  //return the sql field name (i.e. `name`). Overload this method to return a null value.
  function getSQLName() {
    return "`".$this->name."`";
  }
  
  //return the sql statement (i.e. `name`='gigi')
  function getSQL() {
    if ($this->getSQLname()!="") 
      $ret=$this->getSQLname()."=".fixEncoding($this->getSQLValue());
    else $ret="";  //getSQLName returns a null value when the element isn't to be stored in the db. I.e.: a preview button
    return $ret;
  }

  //return the URL statement (i.e. name=gigi)
  function getURL() {
    if ($this->getSQLname()!="") 
      $ret=$this->name."=".$this->getValue();
    else $ret="";  //getSQLName returns a null value when the element isn't to be stored in the db. I.e.: a preview button
    return $ret;
  }
  
  
 
  
  //get the label of the element
  function getCell() {
    return ($this->translate($this->getValue(),true));
  }
  
  
  //sets the value of the element
  function setValue($v) {
    $this->value = $v;
  }  

  //function parse to parse some piece of text
  function synParse($txt) {
    global $synWebsite;
    $pos=strpos($txt,"§SiteURL§");
    if ($pos!==false) $txt=str_replace("§SiteURL§",$synWebsite,$txt);
    return $txt;
  }
  
  //sets if the obj is to see in the list, and his column header name
  function isListable($v=false, $cn=null, $editable=false) {
    $this->list = $v;
    $this->editable = $editable;
    if ($cn==null) $this->colname = $this->getLabel();
    else $this->colname = $cn;
  }  

  //sets the value of the element
  function checkValue($v) {
    if (!isset($v)) $v="";
  }  

  //sets if it is a primary key
  function setKey($value) {
    $this->is_key=$value;
  }  

  //check if it is a primary key
  function isKey() {
    return $this->is_key;
  }  

  //sets if it is a join
  function setJoin($value) {
    $this->is_join=$value;
  }  

  //check if it is a primary key
  function isJoin() {
    return $this->is_join;
  }  

  //sets if it is a multilanguage element
  function setMultilang($value) {
    $this->multilang=$value;
  }  
  //check if it is a multilanguage element
  function isMultilang() {
    if ($this->multilang=="1") return true;
    else return false;
  }  
  
  //function setContainer($container) {
  function setContainer() {  
    $this->container = synContainer::getInstance();
  }

   
  function insert() {
    return true;
  }
  
  function delete() {
    return true;
  }
  
  function update() {
    return true;
  }
  

  //normally an element hasn't a document to upload (only synInputfile)
  function uploadDocument() {
  }

  //normally an element hasn't a document to delete (only synInputfile)
  function deleteDocument() {
  }

  //translate an element. If err==true display the error message
  function translate($id,$err=false) {
    global $db;
    if ($this->multilang==1 and $id!="" ) {
      //if a service field will be transformed in a multilanguage field,
      //uncomment this two lines, navigate all the rows with syntax, then
      //re-comment
      if (!is_int(trim($id))) { 
         //$this->container->updateMultilangValue($this);
         //return $id;
      }
      $qry="SELECT * FROM aa_translation WHERE id='".addslashes($id)."'";
      $res=$db->Execute($qry);
      if ($res->RecordCount()==0) {  //umm... the field is multilang but there isn't a row in the translation table... 
        $ret=$id;
      } else {
        $arr=$res->FetchRow();
        $ret=$arr[$this->getLang($_SESSION["aa_CurrentLang"])];
        if ($ret=="" and $err===true) {
          foreach ($arr as $mylang=>$mytrans) 
            if (!is_numeric($mylang) and $mylang!="id" and $mytrans!="") $alt.="\n$mylang: ".substr(strip_tags($mytrans),0,10);
          if ($alt!="") $ret="<span style='color: gray' title=\"".htmlentities("Other Translations:".$alt)."\">[no translation]</span>";
        }
      }
    } else $ret=$id;
    return $ret;
  }

  //translate an element. If err==true display the error message
  function getLang() {
    global $db,$aa_CurrentLang;
    $qry="SELECT * FROM aa_lang WHERE id='".$aa_CurrentLang."'";
    $res=$db->Execute($qry);
    $arr=$res->FetchRow();
    return $arr["initial"];
  }
  
  // check if selected field is multilang.
  // Since 2.9.2.1
  function chkTargetMultilang($qry='') {
    global $db;
    if($qry=='') return;
    $ismultilang = 0;
# echo $qry, '##<br>';
    preg_match("/^SELECT ([a-zA-Z0-9-_, `\*]+) FROM ([a-zA-Z-_`]+)(?:.*)?$/i", $qry, $matches);
    $field = str_replace('`','',$matches[1]);
    $table = str_replace('`','',$matches[2]);
# echo $field, ' - ', $table, '<br>';
    if ($table!='') {
      if($field=='*'){
        $sql = "SELECT se.name, se.ismultilang FROM aa_services_element se JOIN aa_services s ON se.container=s.id WHERE s.syntable='{$table}' ORDER BY se.`order` LIMIT 1,1";
        $res = $db->Execute($sql);
        list($name, $ismultilang) = $res->FetchRow();

      } else {
        $field = explode(',', $field);
        $sql = "SELECT se.name, se.ismultilang FROM aa_services_element se JOIN aa_services s ON se.container=s.id WHERE s.syntable='{$table}' ORDER BY se.`order`";
# echo $sql, '<br>';
# echo '<pre>', print_r($field), '</pre><br>';
        $res = $db->Execute($sql);
        while($arr=$res->fetchRow()){
#         echo '<pre>', print_r($arr), '</pre><br>';
          if($arr['name']==trim($field[1])){
            #$name = $arr['name'];
            $ismultilang = $arr['ismultilang'];
          }
        }
      }
    }
    #echo $name, ' is multilang: ', intval($ismultilang), '<br><br>';
    return intval($ismultilang);
  }
  
  // replace some markers in the query with live values
  function compileQry($qry) {
    $hash = Array();
    if (isset($_SESSION["aa_joinStack"]) && is_array($_SESSION["aa_joinStack"])) {
      foreach ($_SESSION["aa_joinStack"] as $v) {
        $joinId=$v["idjoin"];
        $value=$v["value"];
        $hash['join|value|id_join='.$joinId] = $value;
      }
    }
    $qry=interpolate($qry,$hash);
    return $qry;
  }

  //function for the auto-configuration
  function configuration($i=0, $k=99) {
    global $db, $synElmName, $synElmType, $synElmLabel, $synElmLabelLang, $synElmSize, $synElmHelp, $synElmHelpLang, $synElmJoinsItem, $synElmOrder, $synElmFilter, $synInitOrder;
    $initOrderChk = "";
    $initOrder = $i+1;
    if ($synInitOrder==0) $synInitOrder=1;
    if (abs($synInitOrder)==$initOrder) {
      $initOrderChk = " checked=\"checked\" ";
      $initOrder = $synInitOrder;
    }
    if (!isset($synElmName[$i]))  $synElmName[$i] = '';
    if (!isset($synElmType[$i]))  $synElmType[$i] = '';
    if (!isset($synElmOrder[$i])) $synElmOrder[$i] = '';
    if (!isset($synElmLabel[$i])) $synElmLabel[$i] = '';
    if (!isset($synElmHelp[$i]))  $synElmHelp[$i] = '';
    if (!isset($synElmLabelLang[$i]) || $synElmLabelLang[$i]=='') $synElmLabelLang[$i] = translate($synElmLabel[$i]);
    if (!isset($synElmHelpLang[$i])  || $synElmHelpLang[$i]=='')  $synElmHelpLang[$i]  = translate($synElmHelp[$i]);

    $synHtml = new synHtml();
    $this->configuration[1] = $synHtml->text(" name=\"synElmName[$i]\" value=\"$synElmName[$i]\" tabindex=\"".($initOrder*2)."\" style=\"font-weight: bold; color: darkred;\" ");
    $this->configuration[2] = $synHtml->select(" name=\"synElmType[$i]\"  tabindex=\"".($initOrder*2+1)."\" ", "SELECT id,name FROM aa_element order by `order`", $synElmType[$i]);
  //$this->configuration[3] = $synHtml->text(" name=\"synElmLabel[$i]\" value=\"$synElmLabel[$i]\" ");
    $this->configuration[3] = $synHtml->text(" name=\"synElmLabelLang[$i]\" value=\"$synElmLabelLang[$i]\" ");
  //$this->configuration[4] = $synHtml->text(" name=\"synElmHelp[$i]\" value=\"$synElmHelp[$i]\" ");
    $this->configuration[4] = $synHtml->text(" name=\"synElmHelpLang[$i]\" value=\"$synElmHelpLang[$i]\" ");
    if ($synElmOrder[$i]==0) $synElmOrder[$i]=$i*10;
    $this->configuration[6] = $synHtml->text(" name=\"synElmOrder[$i]\" value=\"$synElmOrder[$i]\" size=\"2\" ");
    $this->configuration[7] = $synHtml->radio(" name=\"synInitOrder\" value=\"$initOrder\" $initOrderChk ");
    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }

  function isFileTypeAllowed($mime){
    if(in_array($mime, $this->mime_type_allowed)):
      return true;
    else:
      return false;
    endif;
  }
}
?>
