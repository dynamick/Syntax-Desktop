<?php

  function indent($x, $rec){  // set indentation
    $space = "  ";
    $plus  = str_repeat($space, $x);
    $base  = str_repeat($space, $rec);
    return $base.$plus;
  }

  $tree = array();

  function createMenu2($selected=0) {
    $items = serviceTree($selected);
    echo makeMenuHtml($items);
  }


  function makeMenuHtml($items, $level = 0) {
    $ret         = '';
    $indent      = str_repeat(' ', $level * 2);

    switch ($level) {
      case 0 : $attr = ' class="nav navbar-nav" id="main-menu"'; break;
      //case 2 : $attr = ' class="dropdown-menu multi-level l'.$level.'"'; break;
      default: $attr = ' class="dropdown-menu multi-level l'.$level.'"'; break;
    }
    //data-toggle="dropdown" class="dropdown-toggle" href="#"

    $ret        .= sprintf("%s<ul%s>\n", $indent, $attr);
    $indent      = str_repeat(' ', ++$level * 2);
    foreach ($items as $key => $value) {
      $tpl       = '%s<li class="%s"><a href="%s" %s><i class="fa %s"></i> %s</a>';
      $li_class  = ($value['active_children']) ? 'active' : null;

      $active    = ($value['active']) ? ' class="active"' : null;
      //$arrow     = (isset($value['children']) && $level > 1) ? ' <span class="fa fa-caret-right"></span>' : null;
      //$ret      .= sprintf($tpl, $indent, $active_li, $value['link'], $active, $value['icon'], $value['name'].$arrow);
      if (isset($value['children']) && is_array($value['children'])) {
        $active .= ' data-toggle="dropdown" class="dropdown-toggle"';
        $li_class .= ($level > 1) ? ' dropdown-submenu' : null;
        $ret    .= sprintf($tpl, $indent, $li_class, '#', $active, $value['icon'], $value['name'] );
        $ret    .= "\n";
        $ret    .= makeMenuHtml($value['children'], $level + 1);
        $ret    .= $indent;
      } else {
        $ret    .= sprintf($tpl, $indent, $li_class, $value['link'], $active, $value['icon'], $value['name'] );
      }
      $ret      .= sprintf("</li>\n", $indent);
    }
    $indent      = str_repeat(' ', --$level * 2);
    $ret        .= sprintf("%s</ul>\n", $indent);

    return($ret);
  }



  //http://blog.ideashower.com/post/15147134343/create-a-parent-child-array-structure-in-one-pass
  function serviceTree($selected=0) {
    global $db;

    $user = getSynUser();
    $refs = array();
    $list = array();

    $sql = <<<EOQRY
    SELECT gs.*, s.icon AS service_icon
      FROM aa_group_services gs
      JOIN aa_users u ON gs.`group` = u.`id_group`
 LEFT JOIN aa_services s ON s.id = gs.service
     WHERE u.id_group = gs.group
       AND u.id = '{$user}'
  ORDER BY `order`

EOQRY;

    $res = $db->Execute($sql);
    while ($arr = $res->FetchRow()) {
      $thisref = &$refs[ $arr['id'] ];

      $thisref['parent'] = $arr['parent'];
      $thisref['name'] = translateDesktop($arr['name']);

      if ( !empty($arr['service']) ){
        //$link = "index.php?aa_service={$arr["service"]}&amp;aa_group_services={$arr["group"]}";
        $link = "javascript:createWindow('{$thisref['name']}','modules/aa/index.php?aa_service={$arr["service"]}&amp;aa_group_services={$arr["id"]}')";
      } elseif ($arr['link']){
        //$link = $arr['link'];
        $link = "javascript:createWindow('','{$arr['link']}')";
      } else {
        $link = "javascript:void(0)";
      }
      $thisref['link'] = $link;

      if (!empty($arr['service_icon'])) {
        $icon = "modules/aa/".$arr['service_icon'];
      } elseif (!empty($arr['icon'])) {
        $icon = "modules/aa/images/service_icon/".$arr['icon'];
      }
      $thisref['icon'] = translateIcon($icon);

      $active = 0;
      if ( $arr['service'] == $selected ) {
        $active = 1;
        $parent = $arr['parent'];
        while (isset($refs[$parent])) {
          $refs[$parent]['active_children'] = 1;
          $parent = $refs[$parent]['parent'];
        }
      }
      $thisref['active'] = $active;
      $thisref['active_children'] = 0;

      if ($arr['parent'] == 0) {
        $list[ $arr['id'] ] = &$thisref;
      } else {
        $refs[ $arr['parent'] ]['children'][ $arr['id'] ] = &$thisref;
      }
    }
    //echo '<pre>', print_r($list), '</pre>';
    return $list;
  }

  // transitional
  // converte le icone di Syntax in classi di font-awesome
  function translateIcon($icon) {
    $icon = str_replace('modules/aa/images/service_icon/', '', $icon);
    switch($icon){
      case 'accept.png':
        $class = 'fa-check-circle';
        break;
      case 'application_double.png':
        $class = 'fa-files-o';
        break;
      case 'application_form_edit.png':
        $class = 'fa-pencil-square-o';
        break;
      case 'book_open.png':
        $class = 'fa-book';
        break;
      case 'bricks.png':
        $class = 'fa-hdd-o';
        break;
      case 'chart_organisation.png':
        $class = 'fa-sitemap';
        break;
      case 'cog.png':
        $class = 'fa-cog';
        break;
      case 'database_save.png':
        $class = 'fa-download';
        break;
      case 'folder_page.png':
        $class = 'fa-folder-o';
        break;
      case 'group.png':
        $class = 'fa-users';
        break;
      case 'help.png':
        $class = 'fa-question-circle';
        break;
      case 'image.png':
        $class = 'fa-camera';
        break;
      case 'images.png':
        $class = 'fa-picture-o';
        break;
      case 'layout.png':
        $class = 'fa-columns';
        break;
      case 'lightning.png':
        $class = 'fa-bolt';
        break;
      case 'newspaper.png':
        $class = 'fa-bullhorn';
        break;
      case 'page_white_edit.png':
        $class = 'fa-file-text-o';
        break;
      case 'page_white_stack.png':
        $class = 'fa-file-text-o';
        break;
      case 'plugin.png':
        $class = 'fa-puzzle-piece';
        break;
      case 'report.png':
        $class = 'fa-list-alt';
        break;
      case 'seasons.png':
        $class = 'fa-th-large';
        break;
      case 'star.png':
        $class = 'fa-star';
        break;
      case 'table.png':
        $class = 'fa-table';
        break;
      case 'tag_blue.png':
        $class = 'fa-tag';
        break;
      case 'user.png':
        $class = 'fa-user';
        break;
      case 'user_gray.png':
        $class = 'fa-user-md';
        break;
      case 'vcard.png':
        $class = 'fa-credit-card';
        break;
      case 'wand.png':
        $class = 'fa-magic';
        break;
      case 'wrench_orange.png':
        $class = 'fa-wrench';
        break;
      default:
        $class = 'fa-folder-o';
        break;
    }

    return $class;
  }

// EOF