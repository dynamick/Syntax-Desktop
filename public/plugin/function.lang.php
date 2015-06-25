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
  global $db, $synPublicPath, $languages;

  $session_lang = getLangId();
  $session_lang_initial = getLangInitial();

  $server    = 'http://'.$_SERVER['SERVER_NAME'];
  $page_id   = $smarty->getTemplateVars( 'synPageId' );
  $alternate = $smarty->getTemplateVars( 'alternate' );
  $item      = $smarty->getTemplateVars( 'item' );
  $visible   = explode('|', $smarty->getTemplateVars( 'synPageVisible' ));
  $alt_uris  = array();

  if ( empty( $alternate ) && empty( $item )) {
    foreach( $visible as $lang_visible ){
      // for each language different from the selected one, provide an alternate href
      if ( in_array($session_lang, $visible) ) {
        $initial  = $languages['list'][$lang_visible];
        $alt_uris[ $initial ] = $server.createPath( $page_id, $initial );
      }
    }
  } else {
    // it's an item and there are alternate
    foreach( $alternate as $alt_lang => $alt_link )
      $alt_uris[ $alt_lang ] = $alt_link;
  }

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

    if ( isset($alt_uris[ $initial ]) ) {
      // recupero l'uri dagli alternate uris
      $path = $alt_uris[ $initial ];
    } else {
      // se non è visibile in questa lingua, rimando alla home
      $path = '/' . ($default == '1') ? NULL : $initial.'/';
    }

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