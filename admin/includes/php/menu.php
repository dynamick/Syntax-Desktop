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
  function serviceTree( $selected = 0 ) {
    global $db;

    $user = getSynUser();
    $refs = array();
    $list = array();

    $service_path = 'modules/aa/index.php?aa_service=%d&amp;aa_group_services=%d';
    $window_link = "javascript:createWindow('%s', '%s')";
    $fake_link = 'javascript:void(0)';

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

      if ( !empty($arr['service']) )
        $link = sprintf( $window_link, $thisref['name'], sprintf( $service_path, $arr['service'], $arr['id']) );
      elseif ($arr['link'])
        $link = sprintf( $window_link, '', $arr['link'] );
      else
        $link = $fake_link;

      $thisref['link'] = $link;

      if ( !empty($arr['service_icon']) )
        $icon = checkIcon( $arr['service_icon'] );
      elseif ( !empty($arr['icon']) )
        $icon = checkIcon( $arr['icon'] );
      else
        $icon = 'fa-file-o';
      $thisref['icon'] = $icon;

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

      if ($arr['parent'] == 0)
        $list[ $arr['id'] ] = &$thisref;
      else
        $refs[ $arr['parent'] ]['children'][ $arr['id'] ] = &$thisref;
    }
    //echo '<pre>', print_r($list), '</pre>';
    return $list;
  }


  // transitional
  // check if an icon is an image file or a font-awesome glyph
  function checkIcon($icon_str) {
    if (preg_match( "/(fa-)\w+/i", $icon_str )) {
      // font-awesome icon
      $icon = $icon_str;
    } else {
      // old image icon
      $path_parts = pathinfo( $icon_str );
      $filename = $path_parts['basename'];
      $icon = translateIcon( $filename );
    }
    return $icon;
  }


  // transitional
  // converte le icone di Syntax in classi di font-awesome
  function translateIcon($icon) {
    $icon = str_replace('modules/aa/images/service_icon/', '', $icon);
    $fa_icons = array(
      'accept.png' => 'check-circle',
      'add.png' => 'plus-circle',
      'anchor.png' => 'anchor',
      'application_double.png' => 'files-o',
      'application_form_edit.png' => 'pencil-square-o',
      'application_view_tile.png' => 'columns',
      'arrow_redo.png' => 'repeat',
      'arrow_rotate_anticlockwise.png' => 'rotate-left',
      'arrow_undo.png' => 'undo',
      'asterisk_orange.png' => 'asterisk',
      'attach.png' => 'paperclip',
      'award_star_gold_2.png' => 'star-o',
      'basket.png' => 'shopping-cart',
      'bell.png' => 'bell',
      'bin.png' => 'trash',
      'bomb.png' => 'bomb',
      'book.png' => 'book',
      'book_open.png' => 'book',
      'brick.png' => 'cube',
      'bricks.png' => 'cubes',
      'briefcase.png' => 'briefcase',
      'bug.png' => 'bug',
      'building.png' => 'building-o',
      'cake.png' => 'birthday-cake',
      'calculator.png' => 'calculator',
      'calendar.png' => 'calendar',
      'calendar_view_day.png' => 'calendar-o',
      'camera.png' => 'camera',
      'cancel.png' => 'times-circle',
      'car.png' => 'car',
      'cart.png' => 'chopping-cart',
      'cd.png' => 'circle',
      'chart_bar.png' => 'bar-chart',
      'chart_curve.png' => 'line-chart',
      'chart_organisation.png' => 'sitemap',
      'chart_pie.png' => 'pie-chart',
      'clock_red.png' => 'clock',
      'cog.png' => 'cog',
      'coins.png' => 'tint',
      'color.png' => 'paint-brush',
      'color_swatch.png' => 'th',
      'comment.png' => 'comment-o',
      'comments.png' => 'comments-o',
      'compress.png' => 'hdd-o',
      'computer.png' => 'desktop',
      'control_power_blue.png' => 'power-off',
      'controller.png' => 'gamepad',
      'creditcards.png' => 'credit-card',
      'cross.png' => 'times',
      'cut.png' => 'cut',
      'database.png' => 'database',
      'database_gear.png' => 'database',
      'database_save.png' => 'database',
      'date.png' => 'calednar-o',
      'delete.png' => 'minus-circle',
      'disk_black.png' => 'floppy-o',
      'door_in.png' => 'sign-in',
      'drink.png' => 'glass',
      'email.png' => 'envelope',
      'email_open.png' => 'envelope-o',
      'emoticon_smile.png' => 'smile-o',
      'error.png' => 'warning',
      'exclamation.png' => 'exclamation-circle',
      'feed.png' => 'rss',
      'film.png' => 'film',
      'find.png' => 'search',
      'flag_blue.png' => 'flag',
      'flag_orange.png' => 'flag-o',
      'folder.png' => 'folder',
      'folder_image.png' => 'folder-o',
      'folder_page.png' => 'folder-open',
      'group.png' => 'users',
      'heart.png' => 'heart',
      'help.png' => 'question-circle',
      'hourglass.png' => 'paper-plane',
      'house.png' => 'home',
      'image.png' => 'picture',
      'images.png' => 'picture',
      'information.png' => 'info-circle',
      'key.png' => 'key',
      'layout.png' => 'columns',
      'lightbulb.png' => 'lightbulb-o',
      'lightning.png' => 'flash',
      'link.png' => 'link',
      'lock.png' => 'lock',
      'lorry.png' => 'truck',
      'male.png' => 'male',
      'medal_gold_1.png' => 'trophy',
      'money.png' => 'money',
      'money_euro.png' => 'euro',
      'newspaper.png' => 'newspaper-o',
      'note.png' => 'file-text-o',
      'package.png' => 'dropbox',
      'package_green.png' => 'inbox',
      'page_white.png' => 'file-o',
      'page_white_copy.png' => 'copy',
      'page_white_edit.png' => 'edit',
      'page_white_stack.png' => 'copy',
      'page_white_word.png' => 'file-word-o',
      'pencil.png' => 'pencil',
      'phone.png' => 'mobile-phone',
      'photo.png' => 'photo',
      'picture.png' => 'picture-o',
      'pill.png' => 'medkit',
      'plugin.png' => 'puzzle-piece',
      'printer.png' => 'print',
      'reload.png' => 'refresh',
      'report.png' => 'book',
      'rosette.png' => 'dribbble',
      'ruby.png' => 'diamond',
      'seasons.png' => 'th-large',
      'share.png' => 'share-al',
      'shield.png' => 'shield',
      'sound.png' => 'volume-up',
      'star.png' => 'star',
      'table.png' => 'table',
      'table_multiple.png' => 'table',
      'table_relationship.png' => 'caret-square-o-down ',
      'tag_blue.png' => 'tag',
      'telephone.png' => 'phone',
      'thumb_up.png' => 'thumbs-up',
      'tick.png' => 'check',
      'time.png' => 'clock-o',
      'user.png' => 'user',
      'user_gray.png' => 'user-secret',
      'vcard.png' => 'archive',
      'wand.png' => 'magic',
      'world.png' => 'globe',
      'wrench_orange.png' => 'wrench',
      'zoom.png' => 'search-plus',
    );
    $class = isset($fa_icons[$icon])
           ? 'fa-'.$fa_icons[$icon]
           : 'fa-folder-o';

   return $class;
  }

// EOF