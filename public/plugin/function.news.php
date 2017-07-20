<?php
function smarty_function_news($params, &$smarty) {
  global $db, $synPublicPath;

  $server   = 'http://'.$_SERVER['SERVER_NAME'];
  $newsPage = $smarty->getTemplateVars( 'synPageId' );
  $newsPath = createPath( $newsPage );
  $req      = isset($_GET['id'])
            ? intval($_GET['id'])
            : 0;
  $lang     = getLangInitial();
  $langId   = getLangId();

  if ( $req == 0) {
    // -------------------------------- NEWS LIST ----------------------------- //
    $maxitems = 12;
    $pager    = null;
    $list     = null;

    $qry = <<<EOQ

    SELECT  n.id, n.date, n.image,
            t1.{$lang} AS title, t2.{$lang} AS text

      FROM  news n
 LEFT JOIN  aa_translation t1 ON n.title = t1.id
 LEFT JOIN  aa_translation t2 ON n.text  = t2.id

     WHERE  CONCAT('|', n.visible, '|') LIKE '%{$langId}%'
  ORDER BY  n.`date` DESC

EOQ;


    $pgr = new synPagerPublic($db, '', '', '', true);
    $pgr->current_template = '<li class="active"><a>%s <span class="sr-only">(current)</span></a></li>';
    $pgr->link_template = '<li><a href="%s">%s</a></li>';

    $res = $pgr->Execute($qry, $maxitems);
    $tot = $pgr->rs->maxRecordCount();

    if ($tot>0) {
      $list = array();
      while ($arr = $res->FetchRow()) {
        extract($arr);
        unset($src);

        $url = $newsPath . createItemPath( $title, $id );
        $alt = attributize( $title );
        $fdate = htmlentities(sql2human($date, '%d %B %Y'));
        $abstract = troncaTesto( strip_tags($text), 150 );

        $permalink = $server.$url;
        $safeurl = rawurlencode($permalink);
        $safettl = rawurlencode($title);
        $social_share = social_share( $safeurl, $safettl );
        
        if ($image) {
          $src = $image;
        } else {
          $src = $synPublicPath.'/mat/default.png';
        }

        $list[] = array(
          'id'            => $id,
          'url'           => $url,
          'src'           => $src,
          'alt'           => $alt,
          'date'          => $date,
          'fdate'         => $fdate,
          'title'         => $title,
          'abstract'      => $abstract,
          'social_share'  => $social_share
        );
      }

    }

    // print navigation buttons
    if ($pgr->rs->LastPageNo()>1)
      $pager = $pgr->pagerArrList();

    $smarty->assign('news', $list);
    $smarty->assign('pagination', $pager);


  } else {
    // ---------------------------- NEWS DETAIL ------------------------------ //
    $qry = <<<EOQ
    SELECT n.id, n.image, n.attached, n.date, n.visible, n.title AS title_id,
           t1.{$lang} AS title, t2.{$lang} AS text

      FROM news n
 LEFT JOIN aa_translation t1 ON n.title = t1.id
 LEFT JOIN aa_translation t2 ON n.text = t2.id

     WHERE n.id = '{$req}'
EOQ;

    $res = $db->Execute($qry);
    if ( $arr = $res->FetchRow() ) {
      extract( $arr );

      $permalink    = $server.$newsPath.createItemPath( $title, $id );
      $safeurl      = rawurlencode( $permalink );
      $safettl      = rawurlencode( $title );
      $fdate        = sql2human( $date, '%d %B %Y' ); // TODO: formato localizzato
      $src          = null;

      if ($image) {
        $src = $image;
      } else {
        $src = $synPublicPath.'/mat/default.png';
      }

      $file = '';
      if ($attached) {
        $file = $attached;
      }

      $social_share = social_share( $safeurl, $safettl );
      $navlinks = getPrevNextLinks( $db, $date, $newsPath, $lang );

      $output = array(
        'id'            => $id,
        'title'         => $title,
        'text'          => $text,
        'src'           => $src,
        'file'          => $file,
        'date'          => $date,
        'fdate'         => $fdate,
        'permalink'     => $permalink,
        'navlinks'      => $navlinks,
        'social_share'  => $social_share
      );

      $visible_arr = explode( '|', $visible );
      $locales = getLocaleCodes( $visible_arr );
      $alt_links = getAlternateLinks( $newsPage, $title_id, $id, $visible_arr );
      $ogmeta = getOpenGraph( $title, $text, $src, $permalink, 'article', $date, $locales );

      $smarty->assign( 'item', $output );
      $smarty->assign( 'ogmeta', $ogmeta );
      $smarty->assign( 'canonical', $permalink );
      $smarty->assign( 'alternate', $alt_links );

    } else {
      header('HTTP/1.0 404 Not Found');
      header('Location: /404/');
    }
  }

} // end plugin





function getPrevNextLinks($db, $date, $page, $lang='it'){
  $ret = array();
  $sql = <<<EOSQL
(
   SELECT n.id, t1.{$lang} AS title, 'prev' AS type
     FROM news n
LEFT JOIN aa_translation t1 ON n.title = t1.id
    WHERE n.date < '{$date}'
 ORDER BY date DESC
    LIMIT 0,1

) UNION (

   SELECT n.id, t1.$lang AS title, 'next' AS type
     FROM news n
LEFT JOIN aa_translation t1 ON n.title = t1.id
    WHERE n.date > '{$date}'
 ORDER BY date ASC
    LIMIT 0,1
)
EOSQL;

  $res = $db->Execute($sql);
  while($arr = $res->fetchRow()){
    extract($arr);
    $url = $page.createItemPath( $title, $id );

    $ret[] = array(
      'url' => $url,
      'type' => $type,
      'title' => troncaTesto( $title, 40 )
    );
  }
  return $ret;
}


// EOF function.news.php
