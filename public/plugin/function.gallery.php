<?php
function smarty_function_gallery($params, &$smarty) {
  global $db, $synPublicPath;

  $server = 'http://'.$_SERVER['SERVER_NAME'];
  $page   = $smarty->getTemplateVars( 'synPageId' );
  $path   = createPath( $page );
  $ptitle = $smarty->getTemplateVars( 'synPageTitle' );
  $req    = isset($_GET['id'])
          ? intval($_GET['id'])
          : 0;
  $lang   = getLangInitial();
  $langId = getLangId();
  $html   = '';
  $list   = '';

  if (!empty($_GET['_next_page'])) {
    $_SESSION['next_page'][$page] = intval($_GET['_next_page']);
  }

  if (0 === $req) {
    // -------------------------------- ELENCO ALBUM ----------------------------- //
    $list     = array();
    $pager    = null;
    $maxitems = 10;

    $qry = <<<EOQ
      SELECT  a.id, a.date, t1.{$lang} AS title,
              p.id AS photoid, p.photo

        FROM  photos p
  INNER JOIN  album a ON p.album = a.id
   LEFT JOIN  aa_translation t1 ON a.title = t1.id

       WHERE  CONCAT('|',a.visible,'|') LIKE '%{$langId}%'
    GROUP BY  p.album
    ORDER BY  a.date DESC,
              p.ordine DESC
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

        $fdate = sql2human($date, '%d %B %Y');
        $url   = $path.createItemPath( $title, $id );
        $src   = $synPublicPath."/mat/album/{$id}/photos_photo_id{$photoid}.{$photo}";
        $alt   = htmlspecialchars(trim(strip_tags($title)));

        $list[] = array(
          'id' => $id,
          'url' => $url,
          'src' => $src,
          'alt' => $alt,
          'date' => $date,
          'fdate' => $fdate,
          'title' => $title
        );
      }

      //stampo i bottoni della paginazione
      if ($pgr->rs->LastPageNo()>1)
        $pager = $pgr->pagerArrList();

    } //if($tot==0)

    $smarty->assign('albums', $list);
    $smarty->assign('pagination', $pager);


  } else {
    // --------------------------- DETTAGLIO ALBUM ------------------------------ //
    $qry = <<<EOQ

      SELECT  p.id, p.title, p.photo, p.album AS aid,
              a.date, a.visible, a.title AS title_id,
              t1.{$lang} AS albumtitle

        FROM  photos p
  INNER JOIN  album a ON p.album = a.id
   LEFT JOIN  aa_translation t1 ON a.title = t1.id

       WHERE  p.album = '{$req}'
    ORDER BY  p.ordine ASC,
              p.title
EOQ;

    $res = $db->Execute($qry);
    $tot = $res->recordCount();

    $list = false;
    $album = false;
    $mainfoto = array();

    if ($tot>0) {
      $list = array();
      $cnt = 0;
      while ($arr = $res->FetchRow()) {
        extract($arr);
        $cnt ++;

        $src = $synPublicPath."/mat/album/{$aid}/photos_photo_id{$id}.{$photo}";
        $alt = attributize( $title );

        $list[] = array(
          'id' => $id,
          'src' => $src,
          'alt' => $alt,
          'title' => $title
        );

        if ($cnt < 4)
          $mainfoto[] = $src; // per openGraph
      }

      $permalink = $server.$path.createItemPath( $albumtitle, $aid );
      $safeurl = rawurlencode( $permalink );
      $safettl = rawurlencode( $albumtitle );
      $social_share = social_share( $safeurl, $safettl );
      $fdate = sql2human($date, '%d %B %Y'); // TODO: formato localizzato

      $album = array(
        'id'           => $aid,
        'title'        => $albumtitle,
        'date'         => $date,
        'fdate'        => $fdate,
        'permalink'    => $permalink,
        'social_share' => $social_share
      );

      $visible_arr  = explode( '|', $visible );
      $locales      = getLocaleCodes( $visible_arr );
      $alt_links    = getAlternateLinks( $page, $title_id, $aid, $visible_arr );
      $ogmeta       = getOpenGraph( $albumtitle, null, $mainfoto, $permalink, 'article', $date, $locales );

      $smarty->assign( 'photos', $list );
      $smarty->assign( 'item', $album );
      $smarty->assign( 'ogmeta', $ogmeta );
      $smarty->assign( 'canonical', $permalink );
      $smarty->assign( 'alternate', $alt_links );

    } else {
      header('HTTP/1.0 404 Not Found');
      header('Location: /404/');
    }
  }
}

// EOF function.gallery.php