<?php
global $cmd;

  //get the current join from the join stack
  if (is_array($_SESSION["aa_joinStack"])) {
    $stackKeys=array_keys($_SESSION["aa_joinStack"]);
    $stackLastKey=$stackKeys[count($stackKeys)-1];
    $join=new synJoin($_SESSION["aa_joinStack"][$stackLastKey]["idjoin"]);
  } 

    
	switch($cmd) {
		case "":
    break;
		case ADD:
      $fromqry=$contenitore->element[2]->qry;
      $contenitore->element[2]->qry="select id, name from `aa_services_element` where container='".$_SESSION["aa_joinStack"][$stackLastKey]["value"]."'";
    break;
		case MODIFY:  
      $fromqry=$contenitore->element[2]->qry;
      $contenitore->element[2]->qry="select id, name from `aa_services_element` where container='".$_SESSION["aa_joinStack"][$stackLastKey]["value"]."'";
    break;
		case CHANGE:  
      echo "<script>alert('Modify section is disabled. Please delete the join and insert a new one. Thanks');location.href='$PHP_SELF'</script>";
      die;
    break;
		case INSERT:
      
      $f=fixEncoding($contenitore->element[2]->getSQLValue());
      $t=fixEncoding($contenitore->element[3]->getSQLValue());
      //echo "from $f to $t<br>";
      $qry="SELECT `aa_services_element`.name, syntable FROM `aa_services_element`,`aa_services` WHERE `aa_services_element`.container=aa_services.id and  `aa_services_element`.id=$f";
      $res=$db->Execute($qry);
      $arr=$res->FetchRow();
      $fName=$arr[0];
      $fTable=$arr[1];
      //echo "$fName [$fTable]<br>";

      $qry="SELECT `aa_services_element`.name, syntable FROM `aa_services_element`,`aa_services` WHERE `aa_services_element`.container=aa_services.id and  `aa_services_element`.id=$t";
      $res=$db->Execute($qry);
      $arr=$res->FetchRow();
      $tName=$arr[0];
      $tTable=$arr[1];
      //echo "$tName [$tTable]<br>";
      /*
      //create the INDEX
      $qry="ALTER TABLE `$tTable` ADD INDEX `$tName` (`$tName`)";
      $db->Execute($qry);
      //echo $qry."<br>";
      
      //create the foreing key
      $qry="ALTER TABLE `$tTable` ADD  FOREIGN KEY $tName$tTable (`$tName`) REFERENCES `$fTable` (`$fName`) ";
      $qry.="ON DELETE CASCADE";
      //echo $qry;
      $db->Execute($qry);
      */
    break;
		case DELETE: 
      $synPrimaryKey=stripslashes(urldecode($_REQUEST["synPrimaryKey"]));
      $res=$db->Execute("select * from $synTable where $synPrimaryKey");
      $arr=$res->FetchRow();
      $t=$arr["to"];

      $qry="SELECT `aa_services_element`.name, syntable FROM `aa_services_element`,`aa_services` WHERE `aa_services_element`.container=aa_services.id and  `aa_services_element`.id=$t";
      $res=$db->Execute($qry);
      $arr=$res->FetchRow();
      $tName=$arr[0];
      $tTable=$arr[1];
      
      //drop index
      $qry="ALTER TABLE `$tTable` DROP INDEX `$tName` ";
      //$db->Execute($qry);
      
      //search for foreign key
      //$qry="SHOW CREATE TABLE $tTable";
      //$res=$db->Execute($qry);
      //$arr=$res->FetchRow();
      //echo "<pre>";print_r($arr);echo "</pre>";
      
      
      //drop the foreing key ------------------> TO BE ACTIVATED FROM MySQL 4.0.18
      $qry="ALTER TABLE `$tTable` DROP FOREIGN KEY $tName$tTable";
      //$db->Execute($qry);
    break;
    case MULTIPLEDELETE:
      echo "<script>alert('Multiple delete is disabled. Please delete each one row. Thanks');location.href='$PHP_SELF'</script>";
      die;
    break;
    case RPC:
    break;
  }
  

?>
