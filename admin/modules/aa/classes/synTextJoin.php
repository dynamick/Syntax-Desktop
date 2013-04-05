<?php
/**
 * class SynTextJoin
 *
 * Handles "many to many" joins between two services.
 *
 * @author Marco Pozzato <marco@kleis.it>
 * @author Luciano Melotti <luciano@kleis.it>
 * @version 1.0 (2010-03-08)
 */

class synTextJoin extends synElement {

  var $selected;
  var $qry;
  var $path;
  var $table_join;

  //constructor(name, value, label, size, help)
  function synTextJoin($n="", $v="", $l="", $s=255, $h="") {
    global $$n;
    
    if ($n=="") $n =  "text".date("his");
    if ($l=="") $l =  ucfirst($n);
    $this->type = "text";
    $this->name  = $n;
    if (isset($$n) and $$n) { $this->selected = $$n; } else $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " varchar(".$this->size.") NOT NULL";
    $this->is_join = true;

    $this->initCallback();
  }

  //private function
  function _html() {
    $txt = "";
    $value = $this->createArray();
    if (is_array($value)) { 
      foreach($value as $v) {
        $id = $v['key'];
        $name = $v['name'];
        $value = $v['value'];
        $txt .= '<p>'.translateDesktop($name).' <input type="text" name="'.$this->name.'_value['.$id.']" value="'.$value.'"/></p>'.PHP_EOL;
      }
    }  
    return $txt; 
  }
  
  //sets the value of the element
  function setValue($v) {
    global $$n;
    if (!isset($_REQUEST[$$n])) $this->value = $this->createArray($this->qry, $this->path);
    $this->selected = $v;
    return;
  }  

  //get the label of the element
  function getCell() {
    return false;
  }
  
  function setQry($qry) {
    $this->qry = $qry;
    #$this->table_join = array_pop(explode(" ",$this->qry));
  	$pattern = '/SELECT (?:[a-zA-Z-_`, *]?)+ FROM ([a-zA-Z-_`]+)(?:[a-zA-Z-_`,=0-9 ]?)+/';
  	preg_match($pattern, $this->qry, $match);
  	$this->table_join = $match[1];
  }

  function setPath($path) {
    $this->path=$path;
  }
  
  function createArray() {
    global $db;
    $container = $this->container; //synContainer::getInstance();
    $key    = $container->getKeyValue();
    $table1 = $container->table;
    $table2 = $this->table_join;
    $field  = $this->name;
    $values = array();

    $res = $db->execute("SELECT id_{$table2} AS k, `{$field}` FROM `{$table1}-{$table2}` WHERE `id_{$table1}`={$key}");
    while($val=$res->fetchrow()){
      $values[$val['k']] = $val[$field]; 
    }


    $res = $db->Execute($this->qry);
    while ($arr=$res->FetchRow()) {
      $tmp_val = isset($values[$arr[0]]) ? $values[$arr[0]] : "";
      $ret[] = array('key'=>$arr[0], 'name'=>$arr[1], 'value'=>$tmp_val);
    }
    return $ret;
  }

  function initCallback() {  
    $container = synContainer::getInstance();
    $container->add_callback('update', array($this, 'update'));
    $container->add_callback('insert', array($this, 'insert'));
    $container->add_callback('delete', array($this, 'delete'));
  }



  function update() {
    global $db;
    $container = $this->container;     
    $key    = $container->getKeyValue();
    $table1 = $container->table;
    $table2 = $this->table_join;
    $field  = $this->name;
    $post   = $_POST[$field.'_value'];
    $ret    = true;

    foreach($post as $k=>$v){
      // does record exist?
      $check = "SELECT $field FROM `{$table1}-{$table2}` WHERE (`id_{$table1}`={$key} AND `id_{$table2}` = '{$k}')";
      $res = $db->execute($check);
      $tot = $res->recordCount();
      if($tot>0){
        // yes, update it
        $upd = $db->execute("UPDATE `{$table1}-{$table2}` SET `{$field}` = '$v' WHERE (`id_{$table1}`={$key} AND `id_{$table2}` = '{$k}')");
        $ret = $ret && $upd;
      } else {
        // nope, insert it      
        $ins = $db->execute("INSERT INTO `{$table1}-{$table2}` (`id_{$table1}`, `id_{$table2}`, `{$field}`) VALUES ('{$key}','{$k}','{$v}')");
        $ret = $ret && $ins;
      }
    }
    return $ret;
  }
  


  function insert() {
    global $db;
    $container = $this->container;     
    $key    = $container->getKeyValue();
    $table1 = $container->table;
    $table2 = $this->table_join;
    $field  = $this->name;
    $post   = $_POST[$field.'_value'];
    $values = array();

    $qry  = "INSERT INTO `{$table1}-{$table2}` (`id_{$table1}`, `id_{$table2}`, `{$field}`) VALUES ";
    //foreach($_POST[$this->table_join.'_value'] as $k=>$v){
    foreach($post as $k=>$v){
      $values[] = "({$key}, {$k}, '{$v}')";      
    }
    $qry .= implode(', ', $values).";";

    return $db->execute($qry);
  }

  
  function delete() {
    global $db;

    $container = $this->container;    
    $key    = $container->getKeyValue();
    $table1 = $container->table;
    $table2 = $this->table_join;

    $qry = "DELETE FROM `{$table1}-{$table2}` WHERE `id_{$table1}`=$key";
    $del = $db->execute($qry);

    return $del;
  }

    
  //function for the auto-configuration
  function configuration($i="",$k=99) {
    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp, $synElmQry;
    global $synElmQry;
    global $db;
    global $synChkKey, $synChkVisible, $synChkEditable,$synChkMultilang;

    $synHtml = new synHtml();
    
    
    $res=$db->Execute("SELECT * FROM aa_services order by name");
    $txt="<select name=\"synElmQry[$i]\" >";
    while ($arr=$res->FetchRow()) {
      if (isset($synElmQry[$i]) and strpos($synElmQry[$i]." ","FROM ".$arr["syntable"]." ")===false ) $selected=""; else $selected="selected=\"selected\""; 
      if ($arr["syntable"]!="") $txt.="<OPTION VALUE=\"SELECT * FROM ".$arr["syntable"]."\" $selected>".translate($arr["name"])."</option>";
    }
    $txt.="</select>\n";

    $this->configuration[4]="Query: ".$txt;

#    if (!isset($synElmSize[$i]) or $synElmSize[$i]=="") $synElmSize[$i]=$this->size;
#    $this->configuration[5]="Dimensione: ".$synHtml->text(" name=\"synElmSize[$i]\" value=\"$synElmSize[$i]\"");

#    if (!isset($synElmPath[$i]) or $synElmPath[$i]=="") $checked=""; else $checked=" checked='checked' ";
#    $this->configuration[6]="NULL: ".$synHtml->check(" name=\"synElmPath[$i]\" value=\"1\" $checked");
    
    //enable or disable the 3 check at the last configuration step
    $_SESSION["synChkKey"][$i]=1;
    $_SESSION["synChkVisible"][$i]=1;
    $_SESSION["synChkEditable"][$i]=0;
    $_SESSION["synChkMultilang"][$i]=0;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }
  
} //end of class inputfile
?>
