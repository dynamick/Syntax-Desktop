<?php

/*************************************
* class JOIN                         *
* Join two object by their names     *
**************************************/
class synJoin  {
var $id;
var $fromElm;
var $fromElmName;
var $toElm;
var $toElmName;
var $toService;
var $name;
var $icon;

  //constructor(from_element_id, to_element_id, service_name)
  function synJoin($id) {
    global $db;

    $qry="SELECT * FROM aa_service_joins WHERE `id` ='$id'";
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $this->id = $id;
      $this->fromElm=$arr["from"];
      $this->fromElmName=$this->getFieldName($arr["from"]);
      $this->toElm=$arr["to"];
      $this->toElmName=$this->getFieldName($arr["to"]);
      $this->name=$arr["title"];
      $this->fromService = $this->getService($this->fromElm);
      $this->toService = $this->getService($this->toElm);

      $resicon=$db->Execute("SELECT s.icon FROM aa_services s, aa_services_element se WHERE s.id=se.container and se.id='".$this->toElm."'");
      list($icon)=$resicon->FetchRow();
      $this->icon = $icon;
    }
  }

  //get the db field name from the element id
  function getFieldName($id) {
    global $db;
    $res=$db->Execute("SELECT name FROM aa_services_element WHERE id=$id");
    list($name)=$res->FetchRow();
    return $name;
  }

  //get the service id from the element id
  function getService($id) {
    global $db;
    $res=$db->Execute("SELECT container FROM aa_services_element WHERE id=$id");
    list($container)=$res->FetchRow();
    return $container;
  }

  //get the service id from the element id
  function getServiceName($id) {
    global $db;
    $res=$db->Execute("SELECT name FROM aa_services WHERE id=$id");
    list($name)=$res->FetchRow();
    return translateDesktop($name);
  }

  function getCount($key) {
    global $db;
    $res=$db->Execute("SELECT s.syntable FROM aa_services s WHERE s.id=".$this->toService);
    list($toTable)=$res->FetchRow();
    $res = $db->Execute ("SELECT count(id) FROM `$toTable` WHERE `$this->toElmName` = '".intval($key)."'");
    list($count)=$res->FetchRow();
    return $count;
  }

  
  // experimental
  function getCaptionValue($value) {
    global $db;
    $qry="SELECT s.syntable,se.name FROM aa_services s INNER JOIN aa_services_element se ON s.id=se.container WHERE s.id=".$this->fromService." AND se.type=2";
    $res=$db->Execute($qry);
    if ($res->RecordCount()>0) {
      $arr=$res->FetchRow();
      $qry="SELECT ".$arr["name"]." FROM ".$arr["syntable"]." WHERE id=".$value;
      $res=$db->Execute($qry);
      $arr=$res->FetchRow();
      $ret=translateSite($arr[0]);
    } else $ret="";
    return $ret;
  }
  
} //end of class text

?>
