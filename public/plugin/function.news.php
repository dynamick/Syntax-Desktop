<?php
function smarty_function_news($params, &$smarty) {
  global $db, $smarty, $synPublicPath;

  $newsPage = createPath(55);
  $req      = intval($_GET['id']); //$params['dettaglio']);
  $lang     = $_SESSION['synSiteLangInitial'];
  $html     = '';

  if ($req==0) {
    #-------------------------------- ELENCO NEWS -----------------------------#
    $maxitems = 10;
    $imgw     = 100;
    $imgh     = 120;
    
    $qry = <<<EOQ
   SELECT n.id, n.date, n.image,
          t1.{$lang} AS titolo, t2.{$lang} AS testo
     FROM news n
LEFT JOIN aa_translation t1 ON n.title = t1.id
LEFT JOIN aa_translation t2 ON n.text = t2.id
 ORDER BY n.`date` DESC
EOQ;

    
    $pgr = new synPager($db, '', '', '', true);
    $res = $pgr->Execute($qry, $maxitems);
    $tot = $pgr->rs->maxRecordCount();
    $prt = $res->RecordCount();
    $pag = $pgr->curr_page;
    $start = ($maxitems*($pag-1));
    $step = ($start+1).' - '.($start+$prt);
    # echo '<pre>', print_r(array($tot, $prt, $pag, $start, $step)), '</pre>', PHP_EOL;

    if ($tot==0) {
      $html = "<p>Nessuna notizia disponibile.</p>\n";

    } else {
      while ($arr=$res->FetchRow()) {
        extract($arr);
        unset($imgtag);
        $cnt ++;
        $date = htmlentities(sql2human($date, '%d %B %Y'));
        $link = $newsPage.sanitizePath($titolo)."~{$id}.html"; // nice url
        $abstract = troncaTesto(strip_tags($testo), 120);

        if($image){
          $src = "{$synPublicPath}/mat/news_image_id{$id}.{$image}";
        } else {
          $src = $synPublicPath.'/mat/default.jpg';
        }

        $thumb = "{$synPublicPath}/lib/phpthumb/phpThumb.php?src={$src}&amp;w={$imgw}&amp;h={$imgh}&amp;aoe=1&amp;far=C";
        $alt   = troncaTesto(strip_tags($titolo), 20);

        $list .= <<<EOLIST

        <li>
          <a href="{$link}"><img width="{$imgw}" height="{$imgh}" alt="{$alt}" src="{$thumb}"/></a>
          <div>
            <h3>{$titolo}</h3>
            <p><strong>{$date}</strong> - {$abstract}</p>
            <a class="more" href="{$link}">Continua</a>
          </div>
        </li>

EOLIST;
      }


      $html .= "<h2>".$smarty->getTemplateVars('synPageTitle')."</h2>\n";
      $html .= "<ul class=\"items\">\n".$list." </ul>\n";

    } //if($tot==0)

    //stampo i bottoni della paginazione
    $html .= "<div class=\"pager\">\n";
    if ($pgr->rs->LastPageNo()>1) $html .= $pgr->renderPgr();
    $html .= "  <span class=\"status\">Notizie <b>{$step}</b> di <b>{$tot}</b></span>";
    $html .= "</div>\n";


  } else {
    #---------------------------- DETTAGLIO NEWS ------------------------------#
    $imgw     = 200;
    $imgh     = 150;
    $html     = '';
    
    $qry = <<<EOQ
   SELECT n.id, n.image, n.date,
          t1.{$lang} AS title, t2.{$lang} AS text
     FROM news n
LEFT JOIN aa_translation t1 ON n.title=t1.id
LEFT JOIN aa_translation t2 ON n.text=t2.id
    WHERE n.id = '{$req}'
EOQ;

    $res = $db->Execute($qry);
    if($arr = $res->FetchRow()){
      extract($arr);

      $permalink = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
      $safeurl = rawurlencode($permalink);
      $safettl = rawurlencode($title);
      $safeabs = rawurlencode(troncaTesto(strip_tags($text),100));
      $data  = htmlentities(sql2human($date));

      if($image){
        $src = "{$synPublicPath}/mat/news_image_id{$id}.{$image}";
      } else {
        $src = $synPublicPath.'/mat/default.jpg';
      }

      $thumb = "{$synPublicPath}/lib/phpthumb/phpThumb.php?src={$src}&amp;w={$imgw}&amp;h={$imgh}&amp;zc=1";
      $navlinks = getPrevNextLinks($db, $date, $newsPage, $lang);
      
      $html = <<<EOHTML
      <h2>{$title}</h2>
      <a href="{$src}" class="imgzoom" title="{$titolo}"><img src="{$thumb}" width="{$imgw}" height="{$imgh}" alt="{$titolo}" /></a>
      <div class="rich-text">
        <p class="date"><b>{$data}</b></p>
        {$text}
      </div>
      
      <div class="share">
        <g:plusone size="tall" href="{$permalink}"></g:plusone>
        <iframe src="//www.facebook.com/plugins/like.php?app_id=264494650237692&amp;href={$permalink}&amp;send=false&amp;layout=box_count"
          scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:62px;" allowTransparency="true"></iframe>
        <a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-lang="it">Tweet</a>
      </div>
      
      <div class="newsnav">
        <a href="{$newsPage}">&uarr; torna all archivio</a>
        {$navlinks}
      </div>
      
      <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
      <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
EOHTML;

    }
  }

  return $html;
}

function getPrevNextLinks($db, $date, $page, $lang='it'){
  $ret = '';
  $sql = <<<EOSQL
(
   SELECT n.id, t1.{$lang} AS title, 'prev' AS type
     FROM news n
LEFT JOIN aa_translation t1 ON n.title=t1.id
    WHERE n.date < '{$date}'
 ORDER BY date DESC
    LIMIT 0,1

) UNION (

   SELECT n.id, t1.$lang AS title, 'next' AS type
     FROM news n
LEFT JOIN aa_translation t1 ON n.title=t1.id
    WHERE n.date > '{$date}'
 ORDER BY date ASC
    LIMIT 0,1
)
EOSQL;

  $res = $db->Execute($sql);
  while($arr = $res->fetchRow()){
    extract($arr);
    $url = $page.sanitizePath($title).'~'.$id.'.html';
    switch($type){
      case 'prev':
        $ret .= "  <a class=\"prev\" href=\"{$url}\">&larr; Evento precedente</a>\n";
        break;
      case 'next':
        $ret .= "  <a class=\"next\" href=\"{$url}\">Evento successivo &rarr;</a>\n";
        break;
    }
  }
  return $ret;
}
?>
