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

  $newsPage   = createPath( PAGE_NEWS );
  $albumPage  = createPath( PAGE_GALLERY );
  $lng        = getLangInitial();
  $server     = 'http://'.$_SERVER['SERVER_NAME'];
  $maxitems   = isset( $params['maxitems'] )
              ? intval( $params['maxitems'] )
              : 10;
  $pager      = null;
  $list       = null;

  // search string, passed via $_GET for tracking reasons (can be logged in Google Analytics)
  $needle = strtolower( addslashes( strip_tags( $_GET['q'] ) ) );

  if ( isset($needle)
    && $needle != ''
    && $needle != 'cerca nel sito'
    ){

    // search on news, pages, album. You can add here all the relevant queries
    // don't forget to declare the 'type'!
    $qry = <<<ENDOFQUERY

( SELECT n.id, t1.{$lng} AS title, t2.{$lng} AS text, 'news' AS type
  FROM news n
  LEFT JOIN aa_translation t1 ON n.title=t1.id
  LEFT JOIN aa_translation t2 ON n.text=t2.id
  WHERE ((LOWER(t1.{$lng}) LIKE '%{$needle}%') OR (LOWER(t2.{$lng}) LIKE '%{$needle}%'))

) UNION (

  SELECT a.id, t1.{$lng} AS title, t2.{$lng} AS text, 'page' AS type
  FROM aa_page a
  LEFT JOIN aa_translation t1 ON a.title=t1.id
  LEFT JOIN aa_translation t2 ON a.text=t2.id
  WHERE a.visible=1
  AND ((LOWER(t1.{$lng}) LIKE '%{$needle}%') OR (LOWER(t2.{$lng}) LIKE '%{$needle}%'))

) UNION (

  SELECT a.id, a.title, NULL AS text, 'album' AS type
  FROM album a
  WHERE (LOWER(a.title) LIKE '%{$needle}%')

) ORDER BY title

ENDOFQUERY;
    // echo $qry;

    $pgr = new synPagerPublic($db, '', '', '', true);
    $pgr->current_template = '<li class="active"><a>%s <span class="sr-only">(current)</span></a></li>';
    $pgr->link_template = '<li><a href="%s">%s</a></li>';

    $res = $pgr->Execute($qry, $maxitems, "q={$needle}");
    $tot = $pgr->rs->maxRecordCount();

    while ( $arr = $res->FetchRow() ) {
      // build the url of the item, based on its type
      switch ($arr['type']){
        case 'news':
          $path = $newsPage . createItemPath( $arr['title'], $arr['id'] );
          break;
        case 'album':
          $path = $albumPage . createItemPath( $arr['title'], $arr['id'] );
          break;
        // add types as needed
        default:
          $path = createPath( $arr['id'] );
          break;
      }

      // list item
      if ( !empty($arr['text']) ) {
        $abstract = strip_tags( $arr['text'] );
        if ( stripos($abstract, $needle) !== FALSE ) {
          $abstract = str_ireplace($needle, "<mark>{$needle}</mark>", $abstract );
        }
        $abstract = excerpt( $abstract, $needle, 500 ); // see misc.functions.php
      }

      $list[] = array(
        'title'     => $arr['title'],
        'abstract'  => $abstract,
        'url'       => $path,
        'permalink' => $server.$path,
        'type'      => $arr['tipo']
        );
    }

    if ( $pgr->rs->LastPageNo()>1 )
      $pager = $pgr->pagerArrList();

    $smarty->assign('needle', $needle);
    $smarty->assign('found', $tot);
    $smarty->assign('items', $list);
    $smarty->assign('pagination', $pager);

  } else return;
}

// EOF function.search.php