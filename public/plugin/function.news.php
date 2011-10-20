<?php
function smarty_function_news($params, &$smarty) {
  global $db, $smarty, $synPublicPath;

  $newsPage = createPath(55);
  $req      = intval($_GET['id']); //$params['dettaglio']);
  $lang     = $_SESSION['synSiteLangInitial'];
  $html     = '';

  if ($req==0) {
    #-------------------------------- ELENCO NEWS -----------------------------#
    $qry = <<<EOQ
    SELECT n.id, n.date, n.image, t.$lang AS titolo, t2.$lang AS testo
      FROM news n
      JOIN aa_translation t ON n.title=t.id
      JOIN aa_translation t2 ON n.text=t2.id
  ORDER BY n.`date` DESC
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
      $html = "<p>Nessuna notizia disponibile.</p>\n";

    } else {
      while ($arr=utf8($res->FetchRow())) {
        extract($arr);
        unset($imgtag);
        $cnt ++;
        $date = htmlentities(sql2human($date));
        $link = $newsPage.sanitizePath($titolo)."~{$id}.html"; // nice url
        $abstract = troncaTesto(strip_tags($testo), 120);

        if($image){
          $imgname = "news_image_id$id";
        } else {
          $image   = 'jpg';
          $imgname = 'default';
        }

        $imgtag = cleverThumb('', $imgname, $image, '', $titolo, 100, 120);

        $list .= "  <li>\n";
        $list .= "    {$imgtag}\n";
        $list .= "    <div>\n";
        $list .= "      <h3>{$titolo}</h3>\n";
        $list .= "      <p><strong>{$date}</strong> - {$abstract}</p>\n";
        $list .= "      <a class=\"more\" href=\"{$link}\">Continua</a>\n";
        $list .= "    </div>\n";
        $list .= "  </li>\n";
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
    $qry = <<<EOQ
    SELECT n.id, n.image, n.date,
           t1.$lang AS title, t2.$lang AS text
      FROM news n
      JOIN aa_translation t1 ON n.title=t1.id
      JOIN aa_translation t2 ON n.text=t2.id
     WHERE n.id=$req
EOQ;

    $res = $db->Execute($qry);
    $arr = $res->FetchRow();
    extract($arr);

    $permalink = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $data  = htmlentities(sql2human($date));
    $html .= "<h2>{$title}</h2>\n";
    $html .= "<div class=\"rich-text\">\n";

    if($image){
      $file   = "news_image_id{$id}";
      $imgtag = cleverThumb('', $file, $image, "big_", $titolo, 506, 226);
      $html  .= "<a class=\"zoom\" title=\"{$title}\" href=\"{$synPublicPath}/mat/{$file}.{$image}\">".$imgtag."</a>\n";
    }
    $html .= "<p class=\"date\"><b>{$data}</b></p>\n";
    $html .= $text;
    $html .= "</div>\n";

    $safeurl = rawurlencode($permalink);
    $safettl = rawurlencode($title);
    $safeabs = rawurlencode(troncaTesto(strip_tags($text),100));
    $html .= <<<SOCIAL
<div class="social">
  <strong>Condividi questo link su:</strong>
  <ul>
    <li><a title="Google Bookmarks"
      href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk={$safeurl}&amp;title={$safettl}&amp;annotation={$safeabs}"
      rel="nofollow" class="ggl">Google Bookmarks</a></li>
    <li><a title="del.icio.us"
      href="http://delicious.com/post?url={$safeurl}&amp;title={$safettl}&amp;notes={$safeabs}"
      rel="nofollow" class="dlc">del.icio.us</a></li>
    <li><a title="Facebook"
      href="http://www.facebook.com/share.php?u={$safeurl}&amp;t={$safettl}"
      rel="nofollow" class="fcb">Facebook</a></li>
    <li><a title="Twitter"
      href="http://twitter.com/home?status={$safettl}%20-%20{$safeurl}"
      rel="nofollow" class="twt">Twitter</a></li>
  </ul>
</div>
SOCIAL;

    $html .= "<div class=\"newsnav\">\n";
    $html .= "  <a href=\"{$newsPage}\">&uarr; torna all'archivio</a>\n";

    # evento precedente
    $qp = "SELECT n.id, t1.$lang AS title FROM news n JOIN aa_translation t1 ON n.title=t1.id WHERE n.date<'{$date}' ORDER BY date DESC LIMIT 0,1";
    $rp = $db->Execute($qp);
    $ap = $rp->fetchRow();

    if($ap['id']){
      $purl = sanitizePath($ap['title']).'~'.$ap['id'].'.html';
      $html .= "  <a class=\"prev\" href=\"{$newsPage}{$purl}\">&larr; Evento precedente</a>\n";
    }

    # evento successivo
    $qn = "SELECT n.id, t1.$lang AS title FROM news n JOIN aa_translation t1 ON n.title=t1.id WHERE n.date>'{$date}' ORDER BY date ASC LIMIT 0,1";
    $rn = $db->Execute($qn);
    $an = $rn->fetchRow();

    if($an['id']){
      $nurl = sanitizePath($an['title']).'~'.$an['id'].'.html';
      $html .= "  <a class=\"next\" href=\"{$newsPage}{$nurl}\">Evento successivo &rarr;</a>\n";
    }
    $html .= "</div>\n";
  }

  return $html;
}
?>
