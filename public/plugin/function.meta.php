<?php
function smarty_function_meta($params, &$smarty){
  global $db, $synWebsiteTitle, $languages;

  $lang             = getLangInitial();
  $langId           = getLangId();
  $default_server   = 'http://' . $_SERVER['SERVER_NAME'];
  $req              = isset( $_GET['id'] )
                    ? intval( $_GET['id'] )
                    : 0;
//$template         = $smarty->getTemplateVars('synTemplate');
  $page_id          = $smarty->getTemplateVars( 'synPageId' );
  $title_page       = $smarty->getTemplateVars( 'synPageTitle' );
  $metatitle_page   = $smarty->getTemplateVars( 'synPageMetatitle' );
  $description_page = $smarty->getTemplateVars( 'synPageMetadescription' );
  $keywords         = $smarty->getTemplateVars( 'synPageMetakeywords' );

  $visible          = array_filter( explode('|', $smarty->getTemplateVars( 'synPageVisible' )) );
  $ogmeta           = $smarty->getTemplateVars( 'ogmeta' );
  $canonical        = $smarty->getTemplateVars( 'canonical' );
  $item             = $smarty->getTemplateVars( 'item' );
  $alternate        = $smarty->getTemplateVars( 'alternate' );

  $title            = (isset($ogmeta['fb']['props']['title']))
                    ? $ogmeta['fb']['props']['title']
                    : null;
  $description      = (isset($ogmeta['fb']['props']['description']))
                    ? $ogmeta['fb']['props']['description']
                    : null;
  $meta             = array();

  // if set, give priority to metatitle
  if ( !empty($metatitle_page) )
    $title_page = $metatitle_page;
  else
    $title_page = $title_page . ' > ' . $synWebsiteTitle;

  if ( empty($title) )
    $title = $title_page;
  else
    $title .= ' > ' . $title_page;

  // title is a required tag!
  $meta[] = '<title>' . attributize( $title ) . '</title>';

  if ( empty( $description ) )
    $description = trim( $description_page );

  if ( !empty( $description ) )
    $meta[] = '<meta name="description" content="' . attributize( $description ) . '">';

  if ( !empty( $keywords ) )
    $meta[] = '<meta name="keywords" content="' . attributize( $keywords ) . '">';

  if ( empty( $canonical ) ){
    $page_path = getLanguageDomain( $langId ) . createPath( $page_id, $lang );
    // remove query string, just in case
    if (strpos($page_path, '?'))
      $page_path = strstr($page_path, '?', true);
    if ( isset($_GET['synSiteLang'])
      || isset($_GET['_next_page'])
      || $page_path != $default_server . $_SERVER['REQUEST_URI']
      ){
      if ( isset($_GET['title']) ){
        $page_path .= createItemPath( $_GET['title'], $req );
      }
      $canonical = $default_server . $page_path;
    }
  }

  if ( !empty($ogmeta) ) {
    // ogmeta present
    foreach( $ogmeta as $s) {
      foreach( $s['props'] as $prop => $val ) {
        if (!empty($val)) {
          if (is_array( $val )) {
            foreach( $val as $v)
              $meta[] = '<meta ' . $s['attr'] . '="' . $s['prefix'] . ':' . $prop . '" content="' . attributize( $v ) . '">';
          } else {
            $meta[] = '<meta ' . $s['attr'] . '="' . $s['prefix'] . ':' . $prop . '" content="' . attributize( $val ) . '">';
          }
        }
      }
    }
  }

  if ( !in_array($langId, $visible) ) {
    // page not visible in current lang, block spiders
    $meta[] = '<meta name="robots" content="noindex, nofollow">';

  } else {
    // page visible in current lang, let spiders index it
    $meta[] = '<meta name="robots" content="index, follow">';

    if ( empty( $alternate ) && empty( $item )) {
      foreach( $visible as $lang_visible ){
        // for each language different from the selected one, provide an alternate href (if is visible)
        if ( !empty($visible)
          && $lang_visible != $langId
          && in_array($langId, $visible)
          ){
          $initial  = $languages['list'][$lang_visible];
          $href     = getLanguageDomain( $langId) . createPath( $page_id, $initial );
          $meta[]   = '<link rel="alternate" hreflang="' . $initial . '" href="' . attributize( $href ) . '">';
        }
      }
    } else {
      // it's an item and there are alternates
      foreach( $alternate as $alt_lang => $alt_link )
        $meta[] = '<link rel="alternate" hreflang="' . $alt_lang . '" href="' . attributize( $alt_link ) . '">';
    }
  }

  if (!empty( $canonical ))
    $meta[] = '<link rel="canonical" href="' . attributize( $canonical ) . '">';

  // previous/next navigation for blog-like items
  if ( !empty($item)
    && isset($item['navlinks'])
    && is_array($item['navlinks'])
    ){
    foreach( $item['navlinks'] as $nav ) {
      $meta[] = '<link rel="' . $nav['type'] . '" href="' . $default_server . $nav['url'] . '" title="' . attributize( $nav['title'] ) . '">';
    }
  }

  //print_debug($meta);
  $smarty->assign( 'metatags', array_filter($meta) );
}

// EOF