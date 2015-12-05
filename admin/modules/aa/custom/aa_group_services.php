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
      $grp = "<select name=\"group2copy\" class=\"form-control\">{$grp}</select>";
//        '<label class="control-label">{$str["in_this_group"]}</label>',
      $script = <<<EOSCRIPT
      var form = new Array(
        '<div class="panel-heading">',
          '<h3 class="panel-title">{$str["menu_copy"]}</h3>',
        '</div>',
        '<div class="panel-body">',
          '<form class="box" action="content.php?cmd=copy" target="content" method="post">',
            '<div class="form-group">{$grp}</div>',
            '<input type="submit" value="{$str['copy']}" class="btn btn-success btn-block">',
            '<input type="hidden" name="aa_value" value="{$_GET['aa_value']}">',
          '</form>',
        '</div>'
      );
      window.parent.content.addBox( 'custom', form.join('') );
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
    case 'copy':
      $dest_group = $_REQUEST['aa_value'];
      $group2copy = $_POST['group2copy'];
      $qry = "SELECT `order`, `parent`, `id`, `name`, `group`, `service`, `filter`, `icon` FROM aa_group_services WHERE `group` = {$group2copy}";
      $res = $db->Execute($qry);
      while ( list($order, $parent, $id, $name, $group, $service, $filter, $icon) = $res->FetchRow() ) {
        //if ($parent!=0) $parent=$search[$parent];
        $qry = "INSERT INTO aa_group_services (`order`,`parent`,`name`,`group`,`service`,`filter`,`icon`) VALUES ('$order','$parent','$name','$dest_group','$service','$filter','$icon')";
        $db->Execute($qry);
        $search[$id] = $db->Insert_ID();
      }

      $qry = "SELECT * FROM aa_group_services WHERE `group` = '{$dest_group}'";
      $res = $db->Execute($qry);
      while ($arr = $res->FetchRow()) {
        $new_parent = 0;
        $current_id = $arr['id'];
        $old_parent = $arr['parent'];
        $new_parent = $search[$old_parent];

        $qry = "UPDATE aa_group_services SET parent = '{$new_parent}' WHERE `id`='{$current_id}'";
        $db->Execute($qry);
      }
      echo "<script>document.location.href='content.php';</script>";
    break;
  }

// EOF