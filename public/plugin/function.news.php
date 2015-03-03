<?php
function smarty_function_news($params, &$smarty) {
  global $db, $synPublicPath;

  $newsPage = $smarty->getTemplateVars( 'synPageId' );
  $newsPath = createPath( $newsPage );
  $req      = isset($_GET['id'])
            ? intval($_GET['id'])
            : 0;
  $lang     = getLangInitial();

  if ($req==0) {
    #-------------------------------- ELENCO NEWS -----------------------------#
    $maxitems = 10;
    $pager    = null;
    $list     = null;

    $qry = <<<EOQ

    SELECT n.id, n.date, n.image,
           t1.{$lang} AS titolo, t2.{$lang} AS testo

      FROM news n
 LEFT JOIN aa_translation t1 ON n.title = t1.id
 LEFT JOIN aa_translation t2 ON n.text  = t2.id

  ORDER BY n.`date` DESC

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

        $url = $newsPath.sanitizePath($titolo)."~{$id}.html";
        $alt = htmlspecialchars(trim(strip_tags($titolo)));
        $fdate = htmlentities(sql2human($date, '%d %B %Y'));
        $abstract = troncaTesto(strip_tags($testo), 150);

        if ($image) {
          $src = "{$synPublicPath}/mat/news_image_id{$id}.{$image}";

        } else {
          $src = $synPublicPath.'/mat/default.jpg';
        }

        $list[] = array(
          'id' => $id,
          'url' => $url,
          'src' => $src,
          'alt' => $alt,
          'date' => $date,
          'fdate' => $fdate,
          'title' => $titolo,
          'abstract' => $abstract
        );
      }

    }

    //stampo i bottoni della paginazione
    if ($pgr->rs->LastPageNo()>1)
      $pager = $pgr->pagerArrList();

    $smarty->assign('news', $list);
    $smarty->assign('pagination', $pager);


  } else {
    #---------------------------- DETTAGLIO NEWS ------------------------------#

    $qry = <<<EOQ
    SELECT n.id, n.image, n.date,
           t1.{$lang} AS title, t2.{$lang} AS text

      FROM news n
 LEFT JOIN aa_translation t1 ON n.title = t1.id
 LEFT JOIN aa_translation t2 ON n.text = t2.id

     WHERE n.id = '{$req}'
EOQ;

    $res = $db->Execute($qry);
    if ($arr = $res->FetchRow()) {
      extract($arr);

      $permalink = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
      //$safeurl = rawurlencode($permalink);
      //$safettl = rawurlencode($title);
      //$safeabs = rawurlencode(troncaTesto(strip_tags($text),100));
      $fdate  = sql2human($date, '%d %B %Y');
      $src = null;

      if ($image) {
        $src = "{$synPublicPath}/mat/news_image_id{$id}.{$image}";

      } /*else {
        $src = $synPublicPath.'/mat/default.jpg';
      }*/

      $navlinks = getPrevNextLinks($db, $date, $newsPath, $lang);

      $output = array(
        'id' => $id,
        'title' => $title,
        'text' => $text,
        'src' => $src,
        'date' => $date,
        'fdate' => $fdate,
        'permalink' => $permalink,
        'navlinks' => $navlinks
      );

      $smarty->assign('item', $output);

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
   SELECT n.id, t1.{$lang} AS title, 'previous' AS type
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
    $url = $page.sanitizePath($title).'~'.$id.'.html';

    $ret[] = array(
      'url' => $url,
      'type' => $type,
      'title' => troncaTesto($title, 40)
    );
  }
  return $ret;
}


// EOF function.news.php