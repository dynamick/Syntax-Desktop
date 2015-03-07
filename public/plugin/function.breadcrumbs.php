<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.breadCrumb.php
* Type:     function
* Name:     breadcrumb
* Purpose:  Ritorna il path "Briciole di pane" dalla posizione corrente
* -------------------------------------------------------------
*/
function smarty_function_breadcrumbs($params, &$smarty) {

  $lang       = getLangInitial();
  $langs      = getLangList();
  $nodes      = $smarty->synPageNode;
  $pages      = array();
  $crumbs     = array();
  $url        = '/';
  $item       = $smarty->getTemplateVars( 'item' );
  $base_title = (isset($params['base_title']))
              ? $params['base_title']
              : 'Home';

  // array delle pagine a livello root che devono figurare come home page
  $home_siblings = array( 57 );

  foreach( $nodes as $k => $node ) {
    // se è di livello root sostituisco il titolo con il base_title
    // es. 'pagine di servizio' diventa un link alla home page
    if ( in_array($node['id'], $home_siblings) ) {
      $node['title'] = $base_title;
    }

    $pages[$node['slug']] = $node['title'];

    if ( $k == 0 ) {
      if ( $langs['default'] != $lang )
        $node['slug'] = $lang;
      $pages[ $node['slug'] ] = $base_title;
    }
  }

  // array elementi URL
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri_elems = array_values(array_filter(explode('/', $uri)));

  if ($uri_elems[0] != $lang){
    // aggiungo la Home come primo elemento
    array_unshift($uri_elems, $nodes[0]['slug']);
  }

  $depth = count($uri_elems) - 1;

  foreach ($uri_elems as $k => $elem) {
    if ( strpos($elem, '~') > 0 ) {
      // prodotto/categoria...
      if ( isset($item['title'])) {
        $title = $item['title'];
      } else {
        $title = ucwords(url_decode(strtok($elem, '~'))); //strstr($elem, '~', true); php 5.3
      }
      $url .= $elem . '/';

    } elseif (strpos($elem, '.')>0) {
      if (preg_match('/index(\d)\.html/', $elem, $matches)) {
        // synPager
        $title = ' Pagina ' . $matches[1];

      } elseif (preg_match('/([a-zA-Z-_]+)\.html/', $elem, $matches)) {
        // account area
        $title = ' ' . ucwords($matches[1]);
      }
    } else {
      // pagina
      $title = $pages[$elem];
      $url  .= (!empty($elem))
             ? $elem . '/'
             : null;
    }

    if ($k == $depth) {
      // ultimo nodo!
      $crumbs[] = array('title' => $title, 'active' => true);
    } else {
      $crumbs[] = array('title' => $title, 'url' => $url, 'active' => false);
    }
  }
  // print_debug( $crumbs );
  $smarty->assign('breadcrumbs', $crumbs);
}

// EOF function.breadcrumb.php
