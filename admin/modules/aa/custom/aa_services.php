<?php
$label = 'service-o-matic';

global $cmd;
  switch($cmd) {
    case "":
      $contenitore->buttons[$label] = $label;
      break;
    case JSON:
      //$contenitore->buttons["&nbsp; <img src='images/service_icon/wand.png' alt=\"Service-O-Matic\"> &nbsp;"]="?cmd=1&aa_service=129&aa_join=container";
      //$button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"button\" title=\"Service-O-Matic\">";
      //$button.= "<img src=\"images/service_icon/wand.png\" alt=\"Service-O-Matic\"></a>";
      $button = "<a class=\"btn btn-xs btn-bl-ock btn-warning %s\" href=\"?cmd=1&amp;aa_service=129&amp;aa_join=container&amp;synPrimaryKey=%s\" title=\"{$label}\">";
      $button.= "<i class=\"fa fa-magic\"></i></a>";
      $contenitore->buttons[$button] = $label;
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

  if ($cmd!=RPC && $cmd!=JSON) {
    /*
    echo "<script>";
    echo "window.parent.content.initToolbar (true,false,true,true,true,true,false);";
    echo "window.parent.content.action('newBtn','window.parent.document.location.href=\"index.php?aa_service=129&aa_join=container&aa_value=0&cmd=1\";');";
    echo "</script>\n";
    */
    enqueue_js( 'window.parent.content.initToolbar( true, false, true, true, true, true, false );' );
    enqueue_js( 'window.parent.content.action("newBtn", "window.parent.document.location.href=\'index.php?aa_service=129&aa_join=container&aa_value=0&cmd=1\';");' );
  }

?>
