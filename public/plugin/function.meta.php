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
  $title_page 			= $smarty->getTemplateVars( 'synPageTitle' );
  $description_page = $smarty->getTemplateVars( 'synPageMetadesc' );
  $keyword_page     = $smarty->getTemplateVars( 'synPageMeta_keywords' );  
  $visible          = explode('|', $smarty->getTemplateVars( 'synPageVisible' ));
  $ogmeta           = $smarty->getTemplateVars( 'ogmeta' );
  $canonical        = $smarty->getTemplateVars( 'canonical' );

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

	if (trim($description)=='')
  	$description = $description_page;

  $meta[] = '<title>'.str_replace('"', null, $title.' > '.$synWebsiteTitle).'</title>';
  $meta[] = '<meta name="description" content="'.str_replace('"', null, $description).'">';
  $meta[] = '<meta name="keywords" content="'.$keyword_page.'" />';
  
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
  $meta[] = '<link rel="canonical" href="'.$canonical.'" />';
  
  if (empty($visible)) {
    $meta[] = '<meta name="robots" content="noindex, nofollow" />';
  } else {
    $meta[] = '<meta name="robots" content="index, follow" />';
    foreach( $visible as $lang_visible ){
      if ( $lang_visible != $langId ) {
        $initial = $languages['list'][$lang_visible];
        $href = $server.createPath( $page_id, $initial );
        $meta[] = '<link rel="alternate" hreflang="'.$initial.'" href="'.$href.'" />';
      }
    }
    
  }
  
  /*
  $languages = getOtherLangs();
  foreach( $languages as $lang ){
    $meta[] = '<link rel="alternate" hreflang="'.$lang.'" href="'.$server.createPath( $page_id, $lang ).'" />';
  } */ 
  
  if (!empty($ogmeta)) {
    foreach( $ogmeta as $s) {
      foreach( $s['props'] as $prop => $val ) {
        if (!empty($val)) {
          if (is_array( $val )) {
            foreach( $val as $v)
              $meta[] = '<meta '.$s['attr'].'='.$s['prefix'].':'.$prop.'" content="'.$v.'" />';
          } else {
            $meta[] = '<meta '.$s['attr'].'="'.$s['prefix'].':'.$prop.'" content="'.$val.'" />';
          }
        }
      }
    }
  }
  
  //print_debug($meta);
  $smarty->assign('metatags', array_filter($meta) );
  
  /*
    <head> 
    <meta charset="utf-8"> 
    <title>Quality Web Design, Affordabilty you can keep </title> 
    <meta property="og:image" content="http://triwebworks.com/images/triwebworkslogo%20%282%29.jpg" /> 
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" /> 
    <meta name="description" content="Tri Web Works is a web based development company in California. The company profile was developed in early 2014 from an ongoing project titled Source Vibe. Tri Web Works has within it, a revolutionary culture taking what is the norm of common web interfaces and making them different." /> 
    <meta name="keywords" content="web, design, tri, web, works, joseph, fleck," /> 
    <meta name="author" content="Joseph Fleck"> 
    <meta name="robots" content="index, follow" /> 
    <link href="main.css" rel="stylesheet" type="text/css">   
  
  */
  
  
  /*
  $meta = array(
    'tag' => 'meta',
    'attributes' => array(
      'name' => 'description',
      'content' => 'blabla'
    )
    'type' => array(
      'attr'=>'', 
      'value'=>''
      ),
    'content' => array(
      'attr'=>'', 
      'value'=>''
      )
  );*/
}

// EOF

/*
function smarty_function_metaOLD($params, &$smarty){
  global $db, $synWebsiteTitle;

  $req      = isset($_GET['id'])
            ? intval($_GET['id'])
            : 0;
  //$line = isset($_GET['idlinea']) ? intval($_GET['idlinea']) : 0;
  //$area = isset($_GET['idarea'])  ? intval($_GET['idarea']) : 0;
  $lang     = getLangInitial();
  $server   = 'http://'.$_SERVER['SERVER_NAME'];

  $page_id          = $smarty->getTemplateVars('synPageId');
  $template         = $smarty->getTemplateVars('synTemplate');
  $title_page       = $smarty->getTemplateVars('synPageMeta_title')
                    ? $smarty->getTemplateVars('synPageMeta_title')
                    : $smarty->getTemplateVars('synPageTitle');
  $description_page = $smarty->getTemplateVars('synPageMeta_description');
  $keyword_page     = $smarty->getTemplateVars('synPageMeta_keywords');
  $canonical        = '';
  $qry              = '';
  $qry_model        = <<<EOQ

       SELECT i.id,
              t1.{$lang} AS meta_title, t2.{$lang} AS meta_description, t3.{$lang} AS meta_keywords, t4.{$lang} AS title
         FROM %s i
    LEFT JOIN aa_translation t1 ON t1.id = i.meta_title
    LEFT JOIN aa_translation t2 ON t2.id = i.meta_description
    LEFT JOIN aa_translation t3 ON t3.id = i.meta_keywords
    LEFT JOIN aa_translation t4 ON t4.id = i.%s
        WHERE i.id = '%d'

EOQ;

  //echo $qry.'<br>';
  if (!empty($qry)) {
    $res = $db->Execute($qry);
    if($arr = $res->FetchRow()){
      extract($arr);
      $title       = ($meta_title!='') ? $meta_title : $title; //fallback su titolo dell'item
      $description = $meta_description;
      $keyword     = $meta_keywords;
    }
  }

  if(trim($title)=='')
    $title = $title_page;

  if(trim($description)=='')
    $description = $description_page;

  if(trim($keyword)=='')
    $keyword = $keyword_page;

  if ( isset($_GET['synSiteLang'])
    || isset($_GET['_next_page'])
    ){

    $page_path = createPath( $page_id );

    if (isset($_GET['title'])){
      $page_path .= $_GET['title'].'~'.$_GET['id'].'.html';
    }
    if (isset($_GET['parent'])){
      $page_path .= htmlspecialchars($_GET['parent']);
    }
    $canonical = "<link rel=\"canonical\" href=\"{$server}{$page_path}\">\n";
  }

  $languages = getOtherLangs();
  $hreflang = array();
  foreach( $languages as $lang ){
    // createPath($id, $lang)$
    $hreflang[] = array(
      'lang' => $lang,
      'url' => $server.createPath( $page_id, $lang )
    );
  }

  //print_debug($hreflang); die('!');

  $smarty->assign('meta_title',       $title.' > '.$synWebsiteTitle);
  $smarty->assign('meta_description', $description);
  $smarty->assign('meta_keywords',    $keyword);
  $smarty->assign('meta_canonical',   $canonical);
  $smarty->assign('meta_hreflang',    $hreflang);

}
*/
// EOF