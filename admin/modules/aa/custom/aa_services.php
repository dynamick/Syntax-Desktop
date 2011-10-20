<?
global $cmd;
	switch($cmd) {
		case "":
      //$contenitore->buttons["&nbsp; <img src='images/service_icon/wand.png' alt=\"Service-O-Matic\"> &nbsp;"]="?cmd=1&aa_service=129&aa_join=container";
      $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"button\" title=\"Service-O-Matic\">";
      $button.= "<img src=\"images/service_icon/wand.png\" alt=\"Service-O-Matic\"></a>";
      $contenitore->buttons[$button]="?cmd=1&aa_service=129&aa_join=container";

    break;
		case ADD:  
    break;
		case MODIFY:  
    break;
		case CHANGE:  
    break;
		case INSERT:
    break;
		case DELETE: 
      $synPrimaryKey=stripslashes(urldecode($_REQUEST["synPrimaryKey"]));

      // remove the table from the database
      $res=$db->Execute("select * from $synTable where $synPrimaryKey");
      $arr=$res->FetchRow();
      $tableToBeRemoved=$arr["syntable"];
      $qry="DROP TABLE IF EXISTS `$tableToBeRemoved` ";
      $db->Execute($qry);

      // remove the service from the menu tree
      $qry="DELETE FROM `aa_group_services` WHERE ".str_replace("id","service",$synPrimaryKey);
      $db->Execute($qry);
      break;

    case MULTIPLEDELETE:
      //TODO: attenzione alle chiavi. Prende solamente l'id!!!!!!!!!
    	$i=0;
      if (isset($checkrow)) {
  			foreach ($checkrow as $id) {
          $synPrimaryKey=urldecode($id);
          $res=$db->Execute("select * from $synTable where $synPrimaryKey");
          $arr=$res->FetchRow();
          $tableToBeRemoved=$arr["syntable"];
          $qry="DROP TABLE IF EXISTS `$tableToBeRemoved` ";
          $db->Execute($qry);
        }
      }    
    break;
    case RPC:
    break;
  }

  if ($cmd!=RPC) {
    echo "<script>";
    echo "window.parent.content.initToolbar (true,false,true,true,true,true,false);";
    echo "window.parent.content.action('newBtn','window.parent.document.location.href=\"index.php?aa_service=129&aa_join=container&aa_value=0&cmd=1\";');";
    echo "</script>\n";      
  }

?>
