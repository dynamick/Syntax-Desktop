<?php
function smarty_function_news($params, &$smarty) {
  global $db, $synPublicPath;

  $newsPage = createPath(55);
  $req      = isset($_GET['id']) ? intval($_GET['id']) : 0;
  $langId   = $_SESSION['synSiteLang'];
  $lang     = $_SESSION['synSiteLangInitial'];
  $html     = '';

  if ($req==0) {
    #-------------------------------- ELENCO NEWS -----------------------------#
    $maxitems = 10;
    $imgw     = 180;
    $imgh     = 180;
    $cnt      = 0;
    $list     = '';
    
    $qry = <<<EOQ
   SELECT n.id, n.date, n.image,
          t1.{$lang} AS titolo, t2.{$lang} AS testo
    FROM news n
      LEFT JOIN aa_translation t1 ON n.title = t1.id
      LEFT JOIN aa_translation t2 ON n.text = t2.id
    WHERE CONCAT('|', `visible`, '|') LIKE '%|{$langId}|%' 
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
      $html = "<div class=\"alert\">Nessuna notizia disponibile.</div>\n";

    } else {
      while ($arr=$res->FetchRow()) {
        extract($arr);
        unset($imgtag);
        $cnt ++;
        $data = htmlentities(sql2human($date, '%d %B %Y'));
        $link = $newsPage.sanitizePath($titolo)."~{$id}.html"; // nice url
        $abstract = troncaTesto(strip_tags($testo), 150);

        if($image){
          $src = "{$synPublicPath}/mat/news_image_id{$id}.{$image}";
        } else {
          $src = $synPublicPath.'/mat/default.jpg';
        }

        $thumb = htmlentities("{$synPublicPath}/thumb.php?src={$src}&w={$imgw}&h={$imgh}&zc=1");
        $alt   = troncaTesto(strip_tags($titolo), 20);

        $list .= <<<EOLIST

        <li>
          <article class="clearfix">
            <img class="item-figure" src="{$thumb}" alt="{$alt}" width="{$imgw}" height="{$imgh}">
            <a href="{$link}" class="block container">
              <header>
                <h1>{$titolo}</h1>
                <time class="item-subheader" datetime="{$date}" pubdate>{$data}</time>
              </header>
              <p>
                {$abstract}<br>
                <span class="follow">continua &rarr;</span>
              </p>
            </a>
          </article>
        </li>

EOLIST;
      }


      $html = <<<EOHTML
      
      <div class="content">
        <ol class="item-list">
{$list}
        </ol>
      </div>
      
EOHTML;

    } //if($tot==0)

    //stampo i bottoni della paginazione
    if ($pgr->rs->LastPageNo()>1){
      $html .= "<div class=\"pagination\">\n";
      $html .= "  <nav>\n";
      $html .= $pgr->renderPgr();
      /*        <span class="disabled">&larr;</span>
              <span class="active">1</span>
              <a href="#">2</a>
              <a href="#">3</a>
              <a href="#" class="next">&rarr;</a>*/
      $html .= "  </nav>\n";
      $html .= "</div>\n";
    }
    
    $smarty->assign('tagline', '<h2 class="tagline">ultime novità</h2>');

  } else {
    #---------------------------- DETTAGLIO NEWS ------------------------------#
    $imgw     = 1280;
    $imgh     = 400;
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
      $data  = sql2human($date, '%d %B %Y');

      if($image){
        $src = "{$synPublicPath}/mat/news_image_id{$id}.{$image}";
      } else {
        $src = $synPublicPath.'/mat/default.jpg';
      }

      $thumb = htmlentities("{$synPublicPath}/thumb.php?src={$src}&w={$imgw}&h={$imgh}&zc=1");
      $navlinks = getPrevNextLinks($db, $date, $newsPage, $lang);
      
      $html = <<<EOHTML
    
      <figure class="article-figure">
        <img src="{$thumb}" alt="{$safettl}">
      </figure>
      
      <div class="content">
        <div class="rich-text">
          {$text}
        </div>
        
        <aside class="social-share">
          <iframe src="//www.facebook.com/plugins/like.php?href={$permalink}&amp;send=false&amp;layout=box_count&amp;width=75&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font=arial&amp;height=62"
            scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:62px;" allowTransparency="true"></iframe>
          <a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-lang="it">Tweet</a>
          <g:plusone size="tall" href="{$permalink}"></g:plusone>        
        </aside>

        <div class="pagination">
          <nav>
            {$navlinks}
            <!--span class="disabled">&larr; precedente</span>
            <a href="#" class="next">successiva &rarr;</a-->
          </nav>
        </div>
      </div>

      <script src="https://apis.google.com/js/plusone.js"></script>
      <script src="http://platform.twitter.com/widgets.js"></script>
      
EOHTML;

      $smarty->assign('pagetitle', $title);
      $smarty->assign('tagline', "<time class=\"tagline\" time=\"{$date}\">{$data}</time>");
    }
  }

  $smarty->assign('output', $html);
  //return $html;
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
        $ret .= "  <a class=\"prev\" href=\"{$url}\">&larr; precedente</a>\n";
        break;
      case 'next':
        $ret .= "  <a class=\"next\" href=\"{$url}\">successiva &rarr;</a>\n";
        break;
    }
  }
  return $ret;
}


// EOF function.news.php