<?php
function smarty_function_gallery($params, &$smarty) {
  global $db, $synPublicPath;

  $page   = $smarty->getTemplateVars( 'synPageId' );
  $path   = createPath( $page );
  $ptitle = $smarty->getTemplateVars( 'synPageTitle' );
  $req    = isset($_GET['id'])
          ? intval($_GET['id'])
          : null;

  $html   = '';
  $list   = '';

  if (!empty($_GET['_next_page'])) {
    $_SESSION['next_page'][$page] = intval($_GET['_next_page']);
  }

  if ($req==0) {
    #-------------------------------- ELENCO ALBUM -----------------------------#
    $list     = array();
    $pager    = null;
    $maxitems = 10;

    $qry = <<<EOQ

    SELECT a.id, a.title, a.date,
           p.id AS photoid, p.photo
      FROM photos p
      JOIN album AS a ON p.album = a.id
  GROUP BY p.album
  ORDER BY a.date DESC,
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
        $url   = $path.sanitizePath($title)."~{$id}.html";
        $src   = $synPublicPath."/mat/photos_photo_id{$photoid}.{$photo}";
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
    #--------------------------- DETTAGLIO ALBUM ------------------------------#
    $qry = <<<EOQ

    SELECT p.id, p.title, p.photo, p.album AS aid,
           a.title AS albumtitle, a.date
      FROM photos p
      JOIN album a ON p.album = a.id
     WHERE p.album = '{$req}'
  ORDER BY p.ordine ASC,
           p.title

EOQ;

    $res = $db->Execute($qry);
    $tot = $res->recordCount();

    $list = false;
    $album = false;

    if ($tot>0) {
      $list = array();

      while ($arr = $res->FetchRow()) {
        extract($arr);
        $cnt ++;

        $src = $synPublicPath."/mat/photos_photo_id{$id}.{$photo}";
        $alt = htmlspecialchars(trim(strip_tags($title)));

        $list[] = array(
          'id' => $id,
          'src' => $src,
          'alt' => $alt,
          'title' => $title
        );
      }

      $permalink = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
      $safeurl = rawurlencode($permalink);
      $safettl = rawurlencode($albumtitle);
      $fdate = sql2human($date, '%d %B %Y');

      $album = array(
        'title' => $albumtitle,
        'date' => $date,
        'fdate' => $fdate,
        'permalink' => $permalink
      );


      $pageScript = <<<EOPS
      <script type="text/javascript">
      $(document).ready(function(){
        $('.popup').colorbox({
            maxWidth:'90%'
          , maxHeight:'90%'
          , slideshow:true
          , slideshowAuto:false
          , slideshowSpeed:4000
          , transition: 'elastic'
          , scrolling: false
          , loop: false
          , rel: 'group1'
          , onComplete: function(){}

          , previous : '<i class="fa fa-chevron-left"></i>'
          , next : '<i class="fa fa-chevron-right"></i>'
          , current : '{current}/{total}'
          , slideshowStart : '<i class="fa fa-play"></i>'
          , slideshowStop : '<i class="fa fa-pause"></i>'
          , close : '<i class="fa fa-remove"></i>'
        });
      });
      </script>
EOPS;

      $smarty->assign('pageScript', $pageScript);
    }
    $smarty->assign('photos', $list);
    $smarty->assign('album', $album);
  }
}

// EOF function.gallery.php