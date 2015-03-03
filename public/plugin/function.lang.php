<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.lang.php
* Type:     function
* Name:     lang
* Purpose:  outputs the list of flags and sets the current language
* -------------------------------------------------------------
*/
function smarty_function_lang($params, &$smarty){
  global $db, $synPublicPath;

  $session_lang = getLangId();
  $session_lang_initial = getLangInitial();

  $active_lang = null;
  $list = array();

  $qry = 'SELECT * FROM `aa_lang` WHERE `active`="1"';
  $res = $db->Execute($qry);
  while ( $arr = $res->FetchRow() ) {
    extract($arr);
    if ( $id == $session_lang ) {
      $selected = true;
      $active_lang = array(
        'id' => $id,
        'name' => $lang,
        'initial' => $initial
        );
    } else {
      $selected = false;
    }
    $path = ($default == '1')
          ? '/'
          : '/'.$initial.'/';
    $list[] = array(
      'path'    => $path,
      'name'    => $lang,
      'initial' => $initial,
      'active'  => $selected,
      'flag'    => "{$synPublicPath}/mat/flag/{$flag}"
      );
  }

  $smarty->assign( 'active_lang', $active_lang );
  $smarty->assign( 'langlist', $list );
}

// EOF