<?php
#require_once("synElement.php");

/*************************************
* class CONTAINER                    *
* Create a class container           *
**************************************/
class synContainer {
  var $element=array();
  var $buttons;
  var $multidelete;
  var $table;
  var $title;
  var $description;
  var $joins=array();
  var $multilang;
  var $ownerField;
  var $hooks = array(); 

  //private var
  var $even;

  //constructor
  function synContainer($table, $buttons, $multidelete, $title="", $description="",$multilang) {
    $this->table=$table;
    $this->element=array();
    $this->buttons=$buttons;
    $this->multidelete=$multidelete;
    $this->title=$title;
    $this->description=$description;
    $this->multilang=$multilang;
    #self::$instance = $this;
  }

  // this implements the 'singleton' design pattern.
  static function getInstance($table='', $buttons='', $multidelete='', $title="", $description="",$multilang=''){
    static $instance;
    if (!isset($instance)) {
      $c = __CLASS__;
      $instance = new $c($table, $buttons, $multidelete, $title, $description, $multilang);
    }
    return $instance;
  }
  
  //getter for table name
  function getTable() {return $this->table;}

  //getter for title
  function getTitle() {return $this->title;}

  //getter for description
  function getDescription() {return $this->description;}

  //getter for element name
  function getElement($name) {
    $ret=null;
    //while (list ($k, $v) = each ($this->element))
    foreach($this->element as $k=>$v)
      if ($this->element[$k]->name==$name) $ret=$this->element[$k];
    //reset($this->element);
    return $ret;
  }

  //add an element to the container
  function addElement($ref) {
    $this->element[]=&$ref;
    $ref->setContainer($this);
    if (strtolower(get_class($ref))=="synowner") $this->ownerField=$ref->name;
  }

  //write out the insert/modify mask
  function getHtml() {
    $bc = '#F6F6F6';
    $html = '';
    //echo "<table style='margin: 20px 0 0 20px;width: 95%'>\n";
    //while (list ($k, $v) = each ($this->element)) {
    foreach( $this->element as $k => $v ) {
      $help = $this->element[$k]->getHelp();

      //join case (get the current join from the join stack)
      if (isset($_SESSION['aa_joinStack']) && is_array($_SESSION['aa_joinStack'])) {
        $stackKeys = array_keys($_SESSION["aa_joinStack"]);
        $stackLastKey = $stackKeys[count($stackKeys)-1];
        $join = new synJoin($_SESSION["aa_joinStack"][$stackLastKey]["idjoin"]);
        $toElmName = $join->toElmName;
      } else 
        $toElmName = '';

      if ($toElmName == $this->element[$k]->name) {
        echo "<input type=\"hidden\" name=\"{$this->element[$k]->name}\" value=\"{$_SESSION["aa_joinStack"][$stackLastKey]["value"]}\" />";
      } else {
      //normal case
        $bc = ($bc=='#F6F6F6') ? '#FAFAFA' : '#F6F6F6';
        $multilang = ($v->multilang==1) ? $v->getHtmlMultilang() : '';
        $flag = ($v->multilang==1) ? ' '.$this->getLangFlag('', '') : ''; //"height='12' style='float: right'"
       
        $label_elm = $v->getLabel();
        $input_elm = $v->getHtml();
        $help_elm = (!empty($help)) ? "<span class=\"help-block\"><i class=\"fa fa-info-circle\"></i> {$help}</span>\n" : null;
        
        $html .= <<<EOHTML
        <div class="form-group">
          <label class="col-sm-2 control-label">{$label_elm}{$flag}</label>
          <div class="col-sm-10">
            {$input_elm}{$multilang}
            {$help_elm}
          </div>
        </div>
        <hr>
EOHTML;
      }
    }
    //echo "</table>\n";
    echo $html;
  }

  //update the values of each element, given an associative array
  function updateValues($arr) {
    foreach($this->element as $k=>$v) {
      if (isset($arr[$v->name]))
        $this->element[$k]->setValue($arr[$v->name]);
    }
  }

  //Upload documents.
  function uploadDocument() {
    foreach($this->element as $k=>$v) {
      $el=$this->element[$k];
      $el->uploadDocument();
    }
  }

  //delete documents.
  function deleteDocument() {
    $key=$this->getKey();
    foreach($this->element as $k=>$v) {
      $el=$this->element[$k];
      $el->deleteDocument();
    }
  }

  //getTree
  function getTree($qry) {
    global $db;

    $ret = "";
    $startQry = "";
    if ($this->table=="aa_page") $qry.=",`order`";

    ////////////////////////////
    // get the starting qry
    // elements
    foreach($this->element as $k=>$v) {
      if (strtolower(get_class($this->element[$k]))=="syntreegroup")
        $groupField=$this->element[$k]->name;
      if (strtolower(get_class($this->element[$k]))=="syntree")
        $treeField=$this->element[$k]->name;
      if (strtolower(get_class($this->element[$k]))=="synkey")
        $keyField=$this->element[$k]->name;
    }

    if (isset($groupField) && $groupField!='') {
      $res=$db->Execute($qry);
      while ($arr=$res->FetchRow()) {
        $id=$arr["id"];
        $parent=$arr[$treeField];
        if (strpos($qry,"WHERE")==false) $myqry=str_replace("ORDER BY", " WHERE `$keyField` ='$parent' ORDER BY",$qry);
        else $myqry=str_replace("ORDER BY", " AND `$keyField` ='$parent' ORDER BY",$qry);
        //echo $myqry."<br>";
        $res2=$db->Execute($myqry);
        //echo $id." padri: ".$res2->RecordCount()."<br>";
        if ($res2->RecordCount()==0) $ret.="OR `$keyField`='$id' ";
      }
      if (strlen($ret)>0) {
        $startQry=str_replace("ORDER BY "," AND (".substr($ret,3).") ORDER BY ",$qry);
      }
    } else $startQry="";
    ///////////////////////

    //elements
    foreach($this->element as $k=>$v) {
      if (strtolower(get_class($this->element[$k]))=="syntree")
        $this->element[$k]->getTree($qry,$startQry);
    }
  }

  //return a sql string like AND id='22' AND id='8' AND id='10' AND id='11' AND id='23'
  //manage the group access point in a data tree structure
  function getTreePermission() {
    global $db;
    $ret = $groupField = $treeField = '';

    //elements
    foreach($this->element as $k=>$v) {
      if (strtolower(get_class($this->element[$k]))=="syntreegroup")
        $groupField=$this->element[$k]->name;
      if (strtolower(get_class($this->element[$k]))=="syntree")
        $treeField=$this->element[$k]->name;
      if (strtolower(get_class($this->element[$k]))=="synkey")
        $keyField=$this->element[$k]->name;
    }
    if ($treeField=="" or $groupField=="") return false;
    $qry="SELECT * FROM `".$this->table."`  WHERE `$groupField` ='".$_SESSION["synGroup"]."'";
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $id=$arr["id"];
      $ret.=" OR ".$keyField."='$id'";
      $ret.=$this->getTreeChild($id,$treeField,$keyField);
    }
    if (strlen($ret)==0) return " 0=1";
    else return " (".substr($ret,3).") ";
  }

  //return a sql string like AND id='22' AND id='8' AND id='10' AND id='11' AND id='23'
  function getTreeChild($id,$treeField,$keyField) {
    global $db;
    $ret="";
    $qry="SELECT * FROM `".$this->table."`  WHERE `$treeField` ='$id'";
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $id=$arr["id"];
      $ret.=" OR ".$keyField."='$id'";
      $ret.=$this->getTreeChild($id,$treeField,$keyField);
    }
    return $ret;
  }

  //Check if exists a syntree in the container
  function treeExists() {
    $ret=false;
    foreach($this->element as $k=>$v) {
      if (strtolower(get_class($this->element[$k]))=="syntree")
        $ret=true;
    }
    return $ret;
  }

  //check if the row is deleteble (to be called after a updateValues function)
  function isDeletable() {
    global $db,$str;
    $tree="";
    if (is_array($this->element)) {
      foreach ($this->element as $e) {
        if (strtolower(get_class($e))=="syntree") $tree=$e;
        if (strtolower(get_class($e))=="synkey")  $id=$e->getValue();
      }
    }

    //the node has child nodes
    if ($tree!="") {
      $qry="SELECT * FROM `".$this->table."` WHERE ".$tree->getSQLName()."=".$id ;
      $res=$db->Execute($qry);
      $count=$res->RecordCount();
      if ($count>0) return $str["cant_delete_child_nodes"];
    }

    // there's no owner field, no tree field or the node has no parent.
    if ($this->ownerField=="" or $tree=="" or $tree->getValue()==0) return true;


    $qry="SELECT ".$this->ownerField." FROM `".$this->table."` WHERE id=".$tree->getValue();
    $res=$db->Execute($qry);
    $arr=$res->FetchRow();
    if (in_array($arr[$this->ownerField],$_SESSION["synGroupChild"]))
      return true;
    else
      return $str["cant_delete"];
  }

  //return the Row (i.e.: <tr><td>michele</td><td>gobbi</td></tr> )
  function getRow() {
    $ret="";
    $flg=0;
    $even=$this->_getEven();
    $key=$this->getKey();

    //get the current join from the join stack
    if (isset($_SESSION["aa_joinStack"]) && is_array($_SESSION["aa_joinStack"])) {
      $stackKeys=array_keys($_SESSION["aa_joinStack"]);
      $stackLastKey=$stackKeys[count($stackKeys)-1];
      $join=new synJoin($_SESSION["aa_joinStack"][$stackLastKey]["idjoin"]);
      $toElmName=$join->toElmName;
    } else $toElmName="";
    
    
    if ($this->multidelete==true) {
      $ret .= $this->_col($even, $flg, $key, "", false);
      $ret .= "<input type=\"checkbox\" name=\"checkrow[]\" value=\"".$key."\"/>";// onmouseup=\"selectRow(this)\" onkeyup=\"selectRow(this)\" />";
      $ret .= $this->_col_c(false);
      $flg=0;
    }
    //elements
    foreach($this->element as $k=>$v) {
      if ($this->element[$k]->list==true) {
        $field = $this->element[$k]->name;
        if ($toElmName==$field) continue;
        if ($flg==0) $flg=1; else $flg=0;
        $ret  .= $this->_col($even, $flg, $key, $field, $this->element[$k]->editable );
        $ret  .= $this->element[$k]->getCell();
        $ret  .= $this->_col_c($this->element[$k]->editable);
      }
    }

    //joins
    foreach($this->joins as $k=>$v) {
      $elm=$this->getElement($v->fromElmName);
      if ($flg==0) $flg=1; else $flg=0;
      $ret .= $this->_col($even, $flg, null, null, false);
      //$ret .= "<a href=\"index.php?aa_service=".$v->service."&aa_join=".$v->id2."&aa_value=".$elm->getValue()."&aa_idjoin=".$v->id."\"  target='_parent'><img src=\"".$v->icon."\" alt=\"".$v->name."\"/></a>";

      $ret .= "<a href=\"index.php?aa_value=".$elm->getValue()."&aa_idjoin=".$v->id."\"  target='_parent'>";
      $ret .= "<img src=\"".$v->icon."\" alt=\"".$v->name."\" style=\"vertical-align:bottom\"/> ";
      $ret .= "<span style=\"font-size:80%\">(".$v->getCount($this->getKeyValue()).")</span></a>";

      $ret .= $this->_col_c(false);
    }

    //buttons
    foreach($this->buttons as $k=>$v) {
      if ($flg==0) $flg=1; else $flg=0;
      $ret .= $this->_col($even, $flg, null, null, false);
      if ($v!="") {
        $ret .= sprintf($k, $v, $this->getKey());
      }
      $ret .= $this->_col_c(false);
    }
    //$ret.="<!-- fine -->\n";
    return $ret;
  }

  //return the Header row (i.e.: <tr><td>Name</td><td>Surname</td></tr> )
  function getHeader() {
    $ret="";
    
    //get the current join from the join stack
    if (isset($_SESSION["aa_joinStack"]) && is_array($_SESSION["aa_joinStack"])) {
      $stackKeys=array_keys($_SESSION["aa_joinStack"]);
      $stackLastKey=$stackKeys[count($stackKeys)-1];
      $join=new synJoin($_SESSION["aa_joinStack"][$stackLastKey]["idjoin"]);
      $toElmName=$join->toElmName;
    } else $toElmName="";
    
    if ($this->multidelete==true) $ret .= "        <th><input type=\"checkbox\" id=\"checkall\" title=\"Select all\"/></th>\n";

    foreach($this->element as $k=>$v) {
      if ($this->element[$k]->list == true && $toElmName != $this->element[$k]->name) {
        $ret .= "        <th scope='col'>\n";
        //$ret .= "          <a href=\"".$_SERVER["PHP_SELF"]."?aa_order=".$this->element[$k]->name."\" title=\"".translateDesktop($this->element[$k]->help)."\">";
        $ret .= "          <a href=\"".$_SERVER["PHP_SELF"]."?aa_order=".$this->element[$k]->name."\" title=\"".strip_tags($this->element[$k]->help)."\">"; // perchè lo traduce 2 volte?????
        if ($v->multilang==1) 
          $ret.= $this->getLangFlag("","")."&nbsp;";
        $ret .= translateDesktop($this->element[$k]->colname);
        if (isset($_SESSION["aa_order"]) and $_SESSION["aa_order"]==$this->element[$k]->name)
          $_SESSION["aa_order_direction"]==" ASC" ? $ret.=" <img src=\"images/up.gif\" />" : $ret.=" <img src=\"images/down.gif\" />";
        //else $ret.=" <img src=\"images/normal.gif\"/>";
        $ret .= "</a>\n";
        $ret .= "        </th>\n";
      }
    }

    foreach($this->joins as $k=>$v)
      $ret .= "        <th>".$v->name."</th>\n";

    foreach($this->buttons as $k=>$v)
      $ret .= "        <th>&nbsp;</th>\n";

    return $ret;
  }
 
  //update the col field search (i.e.: addSearchField('name', 'Nome'), ...)
  function getColumnSearch() {
    $ret="";

    $searchable = array( //searcheable content
      'syntext',
      'syntextarea',
      'syntextareasimple',
      'syncheck',
      'syntextnumeric',
      'syndate',
      'syndatetime',
      'syninputfile',
      'synpassword'
      );

    foreach($this->element as $k=>$v) {
      $class = strtolower(get_class($this->element[$k]));
      if ($this->element[$k]->list==true and in_array($class, $searchable)) {
        $ret .= "addSearchField('".translateDesktop($this->element[$k]->colname)."', '".$this->element[$k]->name."'); \n";
      }
    }
    //reset($this->element);

    return "<script type=\"text/javascript\">\n".$ret."</script>\n";
  }
  
  //insert the translation of a specific field of a service in the translation table
  //and return the key of translation
  function insertMultilangValue(&$el) {
    global $db, $synInsertValueInAllLang;

    $key=fixEncoding($el->getSQLValue());    
    //insert in all the language the same value?
    if ($synInsertValueInAllLang==true) {
      //get the list of languages
      $res=$db->Execute("SELECT `initial` FROM `aa_lang`");
      $languagelist = '';
      $valuelist = '';
      while (list($lang)=$res->FetchRow()) {
        $languagelist.="`{$lang}`, ";
        $valuelist.="{$key}, ";
      }
      $languagelist=substr($languagelist,0,-2);
      $valuelist=substr($valuelist,0,-2);
      $insertQry="INSERT INTO aa_translation ({$languagelist}) VALUES ({$valuelist})";
    } else {
      //get the current lang
      $qry="SELECT initial FROM aa_lang WHERE id=".$_SESSION["aa_CurrentLang"];
      $res=$db->Execute($qry);
      $arr=$res->FetchRow();
      $currlang=$arr[0];
      $insertQry="INSERT INTO aa_translation ({$currlang}) VALUES ({$key})";
    }

    //check if already exist a translation for this key
    //$qry="SELECT * FROM aa_translation WHERE id=$key";
    //$res=$db->Execute($qry);
    //if ($res->RecordCount()==0) {
      $res=$db->Execute($insertQry);
      $ret="'".$db->Insert_ID()."'";
      return $ret;
    //} else {
    //  die ("vediamo se riusciamo ad entrare in questo ramo dell'if");
      //$res=$db->Execute("INSERT INTO aa_translation ($currlang) VALUES ($key)");
    //}
  }

  //update the translation of a specific field of a service in the translation table
  //and return the key of translation
  function updateMultilangValue(&$el) {
    global $db;

    //get the current lang
    $qry="SELECT initial FROM aa_lang WHERE id=".$_SESSION["aa_CurrentLang"];
    $res=$db->Execute($qry);
    $arr=$res->FetchRow();
    $currlang=$arr[0];

    //this is the value of element
    $value=fixEncoding($el->getSQLValue());
    //check if already exist a translation for this key
    $key=$_REQUEST[$el->name."_synmultilang"];
    if ($key=="") {
      $res=$db->Execute("INSERT INTO aa_translation ($currlang) VALUES ($value)");
      $key=$db->Insert_ID();
    }

    $qry="SELECT * FROM aa_translation WHERE id='".addslashes($key)."'";
    $res=$db->Execute($qry);
    if ($res->RecordCount()>0 and is_numeric($key)) {
      $res=$db->Execute("UPDATE aa_translation set $currlang=$value where id='$key'");
      $ret=$el->getSQLName()."='$key'";
      return $ret;
    } else {

      //get the list of languages
      $res=$db->Execute("SELECT initial FROM aa_lang");
      while (list($lang)=$res->FetchRow()) {
        $languagelist.="`$lang`, ";
        $valuelist.="'".addslashes(translate($key))."', ";
      }
      $languagelist=substr($languagelist,0,-2);
      $valuelist=substr($valuelist,0,-2);
      $res=$db->Execute("INSERT INTO aa_translation ($languagelist) VALUES ($valuelist)"); //in this case the key contains the real string

      //$res=$db->Execute("INSERT INTO aa_translation ($currlang) VALUES ('$key')"); //in this case the key contains the real string
      $key=$db->Insert_ID();
      $res=$db->Execute("UPDATE `".$this->table."` set ".$el->getSQLName()."='$key' WHERE ".urldecode($this->getKey()) );
      $res=$db->Execute("UPDATE aa_translation set $currlang=$value where id='$key'");
      $ret=$el->getSQLName()."='$key'";
    }

  }

  //return the list of field's value for a SQL INSERT (i.e.: 'michele', 'gobbi' )
  function getInsertString() {
    $ret="";
    foreach($this->element as $k=>$v) {
      if(!($v->isJoin())) {
        if ($v->multilang==1) $sql=$this->insertMultilangValue($v);
        else $sql=fixEncoding($v->getSQLValue());
        if ($sql!="") $ret.=$sql.", ";
      }
    }
    return substr($ret, 0, strlen($ret)-2);
  }

  //return the list of fields for a SQL INSERT (i.e.: 'michele', 'gobbi' )
  function getFieldsString() {
    $ret="";
    foreach($this->element as $k=>$v) {
      if(!($v->isJoin())) {
        $n=$this->element[$k]->getSQLName();
        if ($n!="") $ret.=$n.", ";
      }
    }
    return substr($ret, 0, strlen($ret)-2);
  }

  //return the list of field for a SQL UPDATE (i.e.: name='michele', surname='gobbi' )
  function getUpdateString() {
    $ret="";
    foreach($this->element as $k=>$v) {
      //$el=$this->element[$k];
      if (!($v->isJoin())) {
        if ($v->multilang==1) $sql=$this->updateMultilangValue($v);
        else $sql=$v->getSQL();
        $sql = fixEncoding($sql);
        if ($sql!="") $ret.=$sql.", ";
      }
    }
    $ret=substr($ret, 0, strlen($ret)-2);
    return $ret;
  }


  //return the field name that manage the multilanguage
  function isMultilang() {
    $ret=false;
    foreach($this->element as $k=>$v) {
      if ($v->multilang=="1") {$ret=true;} //echo"<pre>";print_r ($this->element);echo"</pre>";}
    }
    return $ret;
  }
  
  //return the html for the multilanguage box
  //act=1  --> list of rows
  //act=2  --> insert/modify: submit and change lang
  function getMultilangBox($act=1) {
    global $db,$str;
 		$aa_CurrentLang=$_SESSION["aa_CurrentLang"];
    $ret = '';
    if ($this->isMultilang()===true) {
      $qry = "SELECT * FROM aa_lang";
      $res = $db->Execute($qry);
      $ret = $html = '';
      while ($arr=$res->FetchRow()) {
        $id=$arr["id"];
        //if (isset($_GET["synSetLang"])) $aa_CurrentLang=$_GET["synSetLang"];
        switch ($act) {
          case 1:
            if ($aa_CurrentLang==$id) {
              $html.= "<li>".$this->getLangFlag($id, ' class="currentFlag"')."</li> ";
            }else{
              $html.= "<li><a href=\"content.php?synSetLang=$id\" target=\"content\">".$this->getLangFlag($id,"")."</a></li> ";
            }
            $txt="<h4>".$str["switchto"]."</h4> ";
          break;
          case 2:
            if ($aa_CurrentLang==$id) {
              $html.= "<li>".$this->getLangFlag($id," class=\"currentFlag\"")."</li> ";
            }else{
              $html.="<li><a href=\"javascript:void(0)\" onclick=\"window.parent.content.document.forms[0].changeto.value='$id'; window.parent.content.document.forms[0].submit();\">".$this->getLangFlag($id,"")."</a></li> ";
            }
            $txt="<h4>Save and change to:</h4> ";
          break;
        }
      }
      $html= $txt." <ul class=\"flags\"> ".$html." </ul>";
      $ret.="<script type=\"text/javascript\">\n";
      $ret.="  var txt=\"".addslashes($html)."\";\n";
      $ret.="  window.parent.content.addBox('multilang',txt);\n";
      $ret.="</script>\n";
    }
    return $ret;
  }

  function getMultilangBoxNew($act=1) {
    global $db, $str;
 		$aa_CurrentLang = $_SESSION["aa_CurrentLang"];
    $ret = '';
    if ($this->isMultilang()===true) {
      $qry = "SELECT * FROM aa_lang";
      $res = $db->Execute($qry);
      $flags = '';
      while ( $arr = $res->FetchRow() ) {
        $id = $arr["id"];
        if ($act==1) {
          if ($aa_CurrentLang == $id) {
            $flags .= "<li>".$this->getLangFlag($id, ' class="currentFlag"')."</li>";
          } else {
            $flags .= "<li><a href=\"content.php?synSetLang=$id\" target=\"content\">".$this->getLangFlag($id,"")."</a></li>";
          }
          $txt = $str['switchto'];
          
        } elseif ($act==2) {
          if ($aa_CurrentLang == $id) {
            $flags .= "<li>".$this->getLangFlag($id," class=\"currentFlag\"")."</li> ";
          }else{
            $flags .= "<li><a href=\"javascript:void(0)\" onclick=\"window.parent.content.document.forms[0].changeto.value='$id'; window.parent.content.document.forms[0].submit();\">".$this->getLangFlag($id,"")."</a></li>";
          }
          $txt = 'Save and change to:';
        }
      }

      $html = "<div class=\"panel-heading\"><h3 class=\"panel-title\">{$txt}</h3></div><div class=\"panel-body\"><ul class=\"list-inline\">{$flags}</ul></div>";
      $safehtml = addslashes($html);

      $ret = <<<EORETURN
      <script type="text/javascript">
        var txt="{$safehtml}";
        window.parent.content.addBox('multilang', txt);
      </script>
EORETURN;
    }
    return $ret;
  }  
  
  
  //return the image of the flag id. If id is null return the session language flag
  function getLangFlag($id="", $attr="") {
    global $db,$synPublicPath;
    if ($id=="") $id=$_SESSION["aa_CurrentLang"];
    $qry="SELECT * FROM aa_lang WHERE id='$id'";
    $res=$db->Execute($qry);
    $arr=$res->FetchRow();
    $flag=$arr["flag"];
    $lang=htmlentities($arr["lang"]);
    //$ret="<img src=\"$synPublicPath/mat/flag/$flag\" alt=\"$lang\" $attr > ";
    $ret="<img src=\"$synPublicPath/mat/flag/$flag\" alt=\"$lang\"$attr/>";
    return $ret;
  }
/******************Manage Primary Key*****************************************/

  //return the list of keys for SQL queries (i.e.: id=34, key='house' )
  function getKeyString() {
    $ret="";
    foreach($this->element as $k=>$v) {
      if ($this->element[$k]->isKey()) {
        $sql=$this->element[$k]->getSQL();
        if ($sql!="") $ret.=$sql.", ";
        //$ret.=$this->element[$k]->name." = '".$this->element[$k]->getValue()."', ";
      }
    }
    return substr($ret, 0, strlen($ret)-2);
  }

  //return the keys value (i.e.: 34)
  function getKeyValue() {
    $ret="";
    foreach($this->element as $k=>$v) {
      if ($this->element[$k]->isKey()) {
        $ret=$this->element[$k]->getValue();
      }
    }
    return $ret;
  }

  
  function setKeyValue($id) {
    foreach($this->element as $k=>$v) {
      if ($this->element[$k]->isKey()) {
        $this->element[$k]->setValue($id);
      }
    }
  }
  
  //return the list of keys for SQL queries (i.e.: id=34&key=house )
  function getKeyURLString() {
    $ret="";
    foreach($this->element as $k=>$v) {
      if ($this->element[$k]->isKey()) {
        $ret.=$this->element[$k]->getURL()."&";
      }
    }
    //echo "<script>alert('$ret')</script>";
    return substr($ret, 0, strlen($ret)-1);
  }

  //return the keys value
  function getKey() {
    $ret="";
    foreach($this->element as $k=>$v) {
      if ($this->element[$k]->isKey()) {
        $ret.=$this->element[$k]->getSQL()." AND ";
      }
    }
    return urlencode(substr($ret, 0, strlen($ret)-4));
  }

  
  function dbSynchronize() {
    global $db;
    $primaryKeys = $resetKey = null;
    //$db->debug=true;
    $tables = $db->MetaTables('TABLES');
    if (!in_array($this->table, $tables))
      $db->Execute("CREATE TABLE IF NOT EXISTS `{$this->table}` (`tobedropped` VARCHAR(1) NOT NULL) DEFAULT CHARSET=utf8 ENGINE=INNODB;");

    $columns = $db->MetaColumns($this->table);
    foreach($this->element as $k => $v) {
      //Add fields to table
      if ($this->element[$k]->name != '') {
        $name              = $this->element[$k]->name;
        $fieldType         = $this->element[$k]->db;
        $fieldSpecificType = explode(' ', $fieldType);
        $fieldSpecificType = explode('\(', $fieldSpecificType[1]);
        $fieldSpecificType = strtolower($fieldSpecificType[0]);
        
        if ( !isset($columns[strtoupper($name)]) 
          || $columns[strtoupper($name)]->name!=$name
          ){
          if (!$v->isJoin()) {
            $dboption = $v->isKey() ? ' PRIMARY KEY' : '';

            $qry = "ALTER TABLE `{$this->table}` ADD `{$name}` {$fieldType}{$dboption}";
            $db->Execute($qry);
          }
        }
      }
      
      //Modify fields
      $colSize = 0;
      $colType = 0;
      $colTypeSize = 0;
      if (isset($columns[strtoupper($name)])) {
        $colSize = $columns[strtoupper($name)]->max_length;
        $colType = $columns[strtoupper($name)]->type;

        if($colType=='varchar') // tacon?
          $colSize = $colSize/3;
          
        $colTypeSize = ($colType == 'text') ? $colType : "{$colType}({$colSize})";
      }
      if ($colSize==-1) 
        $colSize = $colType;

      if ( $fieldSpecificType !== $colTypeSize 
        && !empty($fieldType)
        ){
            //if ($v->isKey()) $dboption=" PRIMARY KEY"; else $dboption="";
            //$db->Execute("ALTER TABLE `".$this->table."` CHANGE `".$name."` `".$name."` ".$fieldType);
            
        if ($v->isJoin()) {
          $table2 = $v->table_join;
          $qry = <<<ENDOFQUERY
          
          CREATE TABLE IF NOT EXISTS `{$this->table}-{$table2}` (
            `id_{$this->table}` int(11) NOT NULL, 
            `id_{$table2}` int(11) NOT NULL, 
            `{$name}` varchar(255), 
            PRIMARY KEY (`id_{$this->table}`, `id_{$table2}`)
          ) DEFAULT CHARSET=utf8 ENGINE=INNODB
          
ENDOFQUERY;

        } else {
          $qry = "ALTER TABLE `{$this->table}` CHANGE `{$name}` `{$name}` {$fieldType}";
        }
        $db->Execute($qry);    
      }

      //check the primary keys
      if ( isset($columns[strtoupper($name)]) 
        && $v->isKey()!=$columns[strtoupper($name)]->primary_key
        ) $resetKey=1;
      if ($v->isKey() === true) 
        $primaryKeys .= $this->element[$k]->getSQLName().",";

     //echo $v->isKey()." - ".$columns[strtoupper($name)]->primary_key."<br>";
    }
    reset($this->element);

    //index regeneration if something changed
    if (strlen($primaryKeys)>0) 
      $primaryKeys = substr($primaryKeys,0,-1);
      
    if ($resetKey==1) {
      $qry="ALTER TABLE `".$this->table."` DROP PRIMARY KEY , ADD PRIMARY KEY ( {$primaryKeys} )";
      //echo "$primaryKeys - Da resettare - $qry";
      $db->Execute($qry);
    }

    //drop unused fields
    foreach ($columns as $k => $v) {
      $colName = $v->name;
      $ret = false;
      foreach($this->element as $ke=>$ve) {
        if ($ve->name == $colName) 
          $ret = true;
      }
      if ($ret == false) {
        $db->Execute("ALTER TABLE `{$this->table}` DROP `{$colName}`");
      }
    }
    reset($this->element);
  }

/*
  function prepare_update() {
    $ret = true;
    foreach($this->element as $ke=>$ve) {
      $f = $ve->prepare_update();
      $ret = $ret && $f;
    }  
    return $ret;
  }
 */ 
  function prepare_update() {
    foreach($this->element as $ke=>$ve) {
      $this->add_callback('prepare_update_element', array($ve, 'prepare_update'));
    }  
    return $this->execute_callbacks('prepare_update_element');
  }
  
  /* add_filter functionality */  
  function add_callback($hook, $func=array()) {//array($obj, $function)
    if(!isset($this->hooks[$hook]) or !is_array($this->hooks[$hook])) $this->hooks[$hook]=array();  
    array_push($this->hooks[$hook], $func);
    return true;
  }  

/*
  function add_callback($hook, $func) {//array($obj, $function)
    if(!is_array($this->hooks[$hook])) $this->hooks[$hook]=array();  
    array_push($this->hooks[$hook], $func);
    return true;
  }  
*/

  function execute_callbacks($hook) {
    $ret = true;
    if(isset($this->hooks[$hook])) {
    $set = $this->hooks[$hook];
      if (is_array($set)) {
        foreach($set as $h) {
          $f = call_user_func_array($h, array());
          $ret = $ret && $f;  
        }
        unset($this->hooks[$hook]);
      }
    }
    return $ret;
  }

/*
  function execute_callbacks($hook) {
    $ret = true;
echo '1. <pre>', print_r($this->hooks[$hook]), '</pre>#<br>';    
    if (is_array($this->hooks[$hook])) {
      echo '<ol>';
      foreach($this->hooks[$hook] as $h) {
        $cnt ++;
        echo '<li><pre>', print_r($h), '</pre></li>';      
        $f = call_user_func($h);
        $ret = $ret && $f;  
      }
      echo '</ol>';      
      unset($this->hooks[$hook]);
    }
echo '2. <pre>', print_r($this->hooks[$hook]), '</pre>#<br>';  
    return $ret;
  }
*/

  //*****************************JOIN METHODS**********************************
  function checkJoins($fromid) {
    global $db;

    $qry="SELECT * FROM aa_service_joins WHERE `from` ='$fromid'";
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $joinid=$arr["id"];
      $this->joins[]=new synJoin($joinid);
      //$this->joins[]=new synJoin($joinid, $fromid, $toid, $title, $icon);
    }
  }

  //*****************************PRIVATE METHOD********************************
  function _col($riga, $colonna, $key, $field, $editable=false) {
    $ret="";
    if ($editable==true) {
      $ret = "        <td class='row".$riga.$colonna."' onmouseover='sel(this);' onmouseout='desel(this);'>\n";
      $ret.= "          <div key=\"$key\" field=\"$field\" CONTENTEDITABLE onclick='contentClick(this)' onkeypress='contentEnter(event, this);' onfocusout='contentCheck(this);' onfocusin='contentStore(this);' title='clicca per modificare, poi premi INVIO per salvare il dato'>";
    } else {
      $ret = "        <td class='row".$riga.$colonna."'>";
    }
    return $ret;
  }

  function _col_c($editable=false) {
    $ret="";
    if ($editable==true) $ret .= "</div>\n        ";
    $ret .= "</td>\n";
    return $ret;
  }

  function _getEven() {
    if ($this->even==true) {
      $ret=0;
      $this->even=false;
    } else {
      $ret=1;
      $this->even=true;
    }
    return $ret;
  }
  //*************************END PRIVATE METHOD********************************


} //end of class

?>
