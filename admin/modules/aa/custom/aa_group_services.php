<?php
global $cmd;

	switch($cmd) {
		case '':
      $grp = '';
      $qry = 'SELECT * FROM aa_groups ORDER BY name';
      $res = $db->Execute($qry);
      while ( list($id, $name) = $res->FetchRow()) {
        $grp .= "<option value=\"{$id}\">{$name}</option>";
      }
      $grp = "<select name=\"group2copy\">{$grp}</select>";

      //echo "<script>";
      //echo "  var txt=\"<form class='box' action='content.php?cmd=copy' target='content' method='post'>".$str["menu_copy"]."<br />$grp</br /> ".$str["in_this_group"]."<br /><input type='submit' value='".$str["copy"]."' class='action_button'  /><input type='hidden' name='aa_value' value='".$_GET["aa_value"]."' /></form>\"; ";
      //echo "  window.parent.content.addBox('copy',txt);";
      //echo "</script>";
      $script = <<<EOSCRIPT
      var form = new Array(
        '<form class="box" action="content.php?cmd=copy" target="content" method="post">',
        '{$str["menu_copy"]}<br />',
        '{$grp}</br />',
        '{$str["in_this_group"]}<br />',
        '<input type="submit" value="{$str['copy']}" class="action_button">',
        '<input type="hidden" name="aa_value" value="{$_GET['aa_value']}">',
        '</form>'
      );
      window.parent.content.addBox( 'copy', form.join('') );
EOSCRIPT;
      enqueue_js( $script );
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
    break;
    case MULTIPLEDELETE:
    break;
    case RPC:
    break;
    case "copy":
      $dest_group=$_REQUEST["aa_value"];
      $group2copy=$_POST["group2copy"];
      $qry="SELECT `order`, `parent`, `id`, `name`, `group`, `service`, `filter`, `icon` FROM aa_group_services WHERE `group`=$group2copy";
      $res=$db->Execute($qry);
      while (list($order,$parent,$id,$name,$group,$service,$filter,$icon)=$res->FetchRow()) {

        //if ($parent!=0) $parent=$search[$parent];
        $qry="INSERT INTO aa_group_services (`order`,`parent`,`name`,`group`,`service`,`filter`,`icon`) VALUES ('$order','$parent','$name','$dest_group','$service','$filter','$icon')";
        $db->Execute($qry);
        $search[$id]=$db->Insert_ID();
      }

      $qry = "SELECT * FROM aa_group_services WHERE `group`='$dest_group'";
      $res = $db->Execute($qry);
      while ($arr = $res->FetchRow()) {
        $new_parent = 0;

        $current_id = $arr["id"];
        $old_parent = $arr["parent"];
        $new_parent = $search[$old_parent];

        $qry = "UPDATE aa_group_services SET parent = '".$new_parent."' WHERE `id`='".$current_id."'";
        $db->Execute($qry);
      }

      echo "<script>document.location.href='content.php';</script>";
    break;
  }


?>