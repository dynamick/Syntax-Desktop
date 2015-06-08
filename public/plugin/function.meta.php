<?php
function smarty_function_meta($params, &$smarty){
  global $db, $synWebsiteTitle, $languages;

  $lang             = getLangInitial();
  $langId           = getLangId();
  $server           = 'http://'.$_SERVER['SERVER_NAME'];
  $req              = isset( $_GET['id'] )
                    ? intval( $_GET['id'] )
                    : 0;
//$template         = $smarty->getTemplateVars('synTemplate');
  $page_id          = $smarty->getTemplateVars( 'synPageId' );
  $title_page       = $smarty->getTemplateVars( 'synPageTitle' );
  $description_page = $smarty->getTemplateVars( 'synPageMetadescription' );
  $keywords         = $smarty->getTemplateVars( 'synPageMetakeywords' );
  $visible          = explode('|', $smarty->getTemplateVars( 'synPageVisible' ));
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

  if ( empty($title) )
    $title = $title_page;
  else
    $title .= ' > '.$title_page;

  // title is a required tag!
  $meta[] = '<title>'.str_replace('"', null, $title.' > '.$synWebsiteTitle).'</title>';

  if (trim($description)=='')
    $description = $description_page;

  if (!empty( $description ))
    $meta[] = '<meta name="description" content="'.str_replace('"', null, $description).'">';

  if (!empty( $keywords ))
    $meta[] = '<meta name="keywords" content="'.$keywords.'">';

  if (empty( $canonical )) {
    if ( isset($_GET['synSiteLang'])
      || isset($_GET['_next_page'])
      ){

      $page_path = createPath( $page_id );

      if (isset($_GET['title'])){
        $page_path .= $_GET['title'].'~'.$_GET['id'].'.html';
      }
      $canonical = $server.$page_path;
    }
  }

  if ( !empty($ogmeta) ) {
    // ogmeta present
    foreach( $ogmeta as $s) {
      foreach( $s['props'] as $prop => $val ) {
        if (!empty($val)) {
          if (is_array( $val )) {
            foreach( $val as $v)
              $meta[] = '<meta '.$s['attr'].'="'.$s['prefix'].':'.$prop.'" content="'.$v.'">';
          } else {
            $meta[] = '<meta '.$s['attr'].'="'.$s['prefix'].':'.$prop.'" content="'.$val.'">';
          }
        }
      }
    }
  }

  if ( !in_array($langId, $visible) ) {
    // page not visible in current lang, block spiders
    $meta[] = '<meta name="robots" content="noindex, nofollow">';

  } else {
    // page visible in current lang, let spiders indexing
    $meta[] = '<meta name="robots" content="index, follow">';

    if ( empty( $alternate ) && empty( $item )) {
      foreach( $visible as $lang_visible ){
        // for each language different from the selected one, provide an alternate href
        if ( $lang_visible != $langId && in_array($langId, $visible) ) {
          $initial  = $languages['list'][$lang_visible];
          $href     = $server.createPath( $page_id, $initial );
          $meta[]   = '<link rel="alternate" hreflang="'.$initial.'" href="'.$href.'">';
        }
      }
    } else {
      // it's an item and there are alternate
      foreach( $alternate as $alt_lang => $alt_link )
        $meta[] = '<link rel="alternate" hreflang="'.$alt_lang.'" href="'.$alt_link.'">';
    }
  }

  if (!empty( $canonical ))
    $meta[] = '<link rel="canonical" href="'.$canonical.'">';

  // prev-next navigation for news items
  if ( !empty($item) && is_array($item['navlinks']) ) {
    foreach( $item['navlinks'] as $nav ) {
      $meta[] = '<link rel="'.$nav['type'].'" href="'.$server.$nav['url'].'" title="'.attributize( $nav['title'] ).'">';
    }
  }

  //print_debug($meta);
  $smarty->assign( 'metatags', array_filter($meta) );
}

// EOF