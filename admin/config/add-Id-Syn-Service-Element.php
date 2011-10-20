<?
  include ("cfg.php");
  
  $first=true;
  
  $qry=" ALTER TABLE `aa_element` CHANGE `id` `classname` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  ";
  if ($first) $db->Execute($qry);
  
  $qry="ALTER TABLE `aa_element` ADD `id` INT( 11 ) NOT NULL FIRST ;";
  if ($first) $db->Execute($qry);
  
  
  
  $count=1;
  $qry="SELECT * FROM aa_element ORDER BY `order`";
  $res=$db->Execute($qry);
  while ($arr=$res->FetchRow()) {
    $qry="UPDATE aa_element SET id=".$count." WHERE classname='".$arr["classname"]."'";
    $db->Execute($qry);
    echo $qry."<br>";
    $conversion[$arr["classname"]]=$count;
    $count++;
  }

  $qry="ALTER TABLE `aa_element` ADD PRIMARY KEY(`id`) ;";
  if ($first) $db->Execute($qry);

  $qry="ALTER TABLE `aa_element` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;";  
  if ($first) $db->Execute($qry);

  $qry="SELECT * FROM aa_services_element";
  $res=$db->Execute($qry);
  while ($arr=$res->FetchRow()) {
    $type=$conversion[$arr["type"]];
    $qry="UPDATE aa_services_element SET type='".$type."' WHERE id='".$arr["id"]."'";
    if ($first) $db->Execute($qry);
    echo $qry."<br>";
  }
  
  $qry="ALTER TABLE `aa_services_element` CHANGE `type` `type` INT( 11 ) NOT NULL";  
  if ($first) $db->Execute($qry);
?> 