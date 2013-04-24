<?php
function smarty_function_gallery($params, &$smarty) {
  global $db, $synPublicPath, $synAbsolutePath;

  $page   = $smarty->getTemplateVars('synPageId');
  $path   = createPath($page);
  $ptitle = $smarty->getTemplateVars('synPageTitle');
  $req    = isset($_GET['id']) ? intval($_GET['id']) : '';

  $html   = '';
  $list   = '';
  $imgw   = 280;
  $imgh   = 175;
  
  if(!empty($_GET['_next_page'])){
    $_SESSION['next_page'][$page] = intval($_GET['_next_page']);
  }

  if($req==0) {
    #-------------------------------- ELENCO ALBUM -----------------------------#

    
    $qry = <<<EOQ
    
    SELECT a.id, a.title, a.date,
           p.id AS photoid, p.photo
      FROM photos p
      JOIN album AS a ON p.album=a.id
  GROUP BY p.album
  ORDER BY a.date DESC, p.ordine DESC
  
EOQ;

    $maxitems = 10;
    $pgr = new synPager($db, '', '', '', true);
    $res = $pgr->Execute($qry, $maxitems);
    $tot = $pgr->rs->maxRecordCount();
    $prt = $res->RecordCount();
    $pag = $pgr->curr_page;
    $start = ($maxitems*($pag-1));
    $step = ($start+1).' - '.($start+$prt);
    # echo '<pre>', print_r(array($tot, $prt, $pag, $start, $step)), '</pre>', PHP_EOL;

    if ($tot==0) {
      $html = "<div class=\"alert\">Nessun album disponibile.</div>\n";

    } else {
      $count = 0;
      while ($arr = $res->FetchRow()) {
        extract($arr);
        unset($imgtag);

        $count  ++;
        $data   = sql2human($date, '%d %B %Y');
        $link   = $path.sanitizePath($title)."~{$id}.html";
        //$src    = $synPublicPath."/mat/photos/{$id}/photos_photo_id{$photoid}.{$photo}";
        $src    = $synPublicPath."/mat/photos_photo_id{$photoid}.{$photo}";
        $thumb  = htmlentities("{$synPublicPath}/thumb.php?src={$src}&w={$imgw}&h={$imgh}&zc=1");
        $imgtag = "<img src=\"{$thumb}\" alt=\"{$title}\" width=\"{$imgw}\" height=\"{$imgh}\">";

        if($count==1){
          $list  .= "<div class=\"clearfix gallery-items\">\n";
        }        
        
        $list .= <<<EOLIST
            <article class="third">
              <a href="{$link}" class="item">
                <img class="item-figure" src="{$thumb}" alt="{$title}" width="{$imgw}" height="{$imgh}">
                <h1>{$title}</h1>
                <time class="item-time" datetime="{$date}" pubdate>{$data}</time>
              </a>
            </article>        
EOLIST;

        if($count == 3){
          $list  .= "</div>\n";
          $count  = 0;
        }
      }
      
      if($count>0){
        $list  .= "</div>\n";
      }

      $html .= $list;

    } //if($tot==0)

    //stampo i bottoni della paginazione
/*    
    $html .= "<div class=\"pager\">\n";
    if ($pgr->rs->LastPageNo()>1) $html .= $pgr->renderPgr();
    $html .= "  <span class=\"status\">Album <b>{$step}</b> di <b>{$tot}</b></span>";
    $html .= "</div>\n";
*/

  } else {
    #--------------------------- DETTAGLIO ALBUM ------------------------------#
    $qry = <<<EOQ
    
    SELECT p.id, p.title, p.photo, p.album AS aid,
           a.title AS albumtitle, a.date
      FROM photos p
      JOIN album a ON p.album=a.id
     WHERE p.album='{$req}'
  ORDER BY p.ordine ASC, p.title
  
EOQ;

    $res = $db->Execute($qry);
    $tot = $res->recordCount();
    $cnt = 0;
    $list = '';
    
    while($arr = $res->FetchRow()){
      //echo '<pre>', print_r($arr), '</pre>';

      extract($arr);
      $cnt ++;
      
      //$src    = $synPublicPath."/mat/photos/{$aid}/photos_photo_id{$id}.{$photo}";
      $src    = $synPublicPath."/mat/photos_photo_id{$id}.{$photo}";
      $thumb  = htmlentities("{$synPublicPath}/thumb.php?src={$src}&w={$imgw}&h={$imgh}&zc=1");
     
      if ($cnt == 1) {
        $list .= "<div class=\"clearfix gallery-items\">\n";
      }
      
      $list  .= <<<EOLIST
      
            <article class="fifth">
              <a href="{$src}" class="item colorbox" rel="gallery{$aid}" title="{$title}">
                <img class="item-figure" src="{$thumb}" alt="{$title}">
                <strong>{$title}</strong>
              </a>
            </article>
            
EOLIST;
      
      if ($cnt == 5) {
        $list .= "</div>\n";
        $cnt   = 0;
      }
   
    }
    
    if ($cnt > 0) {
      $list .= "</div>\n";
    }

    $permalink = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $safeurl = rawurlencode($permalink);
    $safettl = rawurlencode($albumtitle);
    $data = sql2human($date, '%d %B %Y');

    $html .= $list; 
  }

  return $html;
}

// EOF function.gallery.php