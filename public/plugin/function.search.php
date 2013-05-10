<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.search.php
 * Type:     function
 * Name:     Search
 * Purpose:  Performs a site search, given a text string
 * Author:   Marco
 * -------------------------------------------------------------
 */
function smarty_function_search($params, &$smarty) {
  global $db;
  $newsPage = createPath(22);
  $albumPage = createPath(54);

  // search string, passed via $_GET for tracking reasons (can be logged in Google Analytics)
  $ago = strtolower(addslashes(strip_tags($_GET['q'])));

  if ( isset($ago) 
    && $ago != ''
    && $ago != "cerca nel sito"
    ){
    $lng = $_SESSION["synSiteLangInitial"];

    // search on news, pages, album. You can add here all the relevant queries
    $qry = <<<ENDOFQUERY
    
( SELECT n.id, t1.{$lng} AS title, t2.{$lng} AS text, 'news' AS tipo
  FROM news n
  LEFT JOIN aa_translation t1 ON n.title=t1.id
  LEFT JOIN aa_translation t2 ON n.text=t2.id
  WHERE ((LOWER(t1.{$lng}) LIKE '%{$ago}%') OR (LOWER(t2.{$lng}) LIKE '%{$ago}%'))

) UNION (

  SELECT a.id, t1.{$lng} AS title, t2.{$lng} AS text, 'pagina' AS tipo
  FROM aa_page a
  LEFT JOIN aa_translation t1 ON a.title=t1.id
  LEFT JOIN aa_translation t2 ON a.text=t2.id
  WHERE a.visible=1
  AND ((LOWER(t1.{$lng}) LIKE '%{$ago}%') OR (LOWER(t2.{$lng}) LIKE '%{$ago}%'))

) UNION (

  SELECT a.id, a.title, NULL AS text, 'album' AS tipo
  FROM album a
  WHERE (LOWER(a.title) LIKE '%{$ago}%')
) ORDER BY title

ENDOFQUERY;
    // echo $qry;

    $maxitems = 10;
    $pgr = new synPager($db, '', '', '', true);
    $res = $pgr->Execute($qry, $maxitems, 'q='.$ago);
    $tot = $pgr->rs->maxRecordCount();
    $prt = $res->RecordCount();
    $pag = $pgr->curr_page;
    $start = ($maxitems*($pag-1));
    $step = ($start+1).' - '.($start+$prt);


    while ($arr = $res->FetchRow()) {
      // build the url of the item, based on its type
      switch ($arr['tipo']){
        case 'prodotto':
          //$path = sanitizePath($arr['title'])."~".$arr['id'].".html"; break;
          $path = "/index.php?prod_id=".$arr['id']; 
          break;
        case 'news':
          $path = $newsPage.sanitizePath($arr['title']).'~'.$arr['id'].'.html'; 
          break;
        case 'album':
          $path = $albumPage.sanitizePath($arr['title']).'~'.$arr['id'].'.html'; 
          break;
        case 'allegato':
          $path = '/public/mat/docs/allegati_file_id'.$arr['id'].'.'.$arr['ext']; 
          break;
        default:
          $path = createPath($arr["id"]); 
          break;
      }

      $abstract = $arr['text'] ? troncaTesto(strip_tags($arr['text']), 50) : 'nessuna descrizione;';
      $list .= <<<EOITEM
      <li>
        <article>
          <a href="{$path}" class="block">
            <header>
              <h1>{$arr['title']}</h1>
              <span class="item-subheader">{$path}</span>
            </header>
            <p>{$abstract} <span class="follow">continua &rarr;</span></p>
          </a>
        </article>
      </li>      
EOITEM;
    }

    // putting it all together
    $html .= "<p>Hai cercato <mark class=\"search-result\">{$mark}</mark>, trovate {$tot} occorrenze.</p>\n";
    $html .= "<ol class=\"item-list\">\n";
    $html .= $list;
    $html .= "</ol>\n";
    if ($pgr->rs->LastPageNo()>1){
      $html .= "<div class=\"pagination\">\n";
      $html .= $pgr->renderPgr();
      $html .= "</div>\n";
    }
    
    return $html;

  } else return;
}

// EOF function.search.php