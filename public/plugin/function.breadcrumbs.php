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

  if(!isset($_SESSION))
    session_start();

  $divider = (isset($params['divider'])) ? $params['divider'] : '&rsaquo;';
  $lang    = $_SESSION['synSiteLangInitial'];
  $nodes   = $smarty->synPageNode;
  $pages   = array();
  $crumbs  = array();
  //$crumbs  = '';
  $url     = '/';

  //$base_title = 'Home'; // titolo per tutte le pagine di livello root
  $base_title = '<span class="fa fa-home"></span>'; // titolo per tutte le pagine di livello root
  $home_siblings = array(48); // id delle pagine a livello root

  foreach($nodes as $k => $node) {
    if (in_array($node['id'], $home_siblings))
      $node['title'] = $base_title; // se � di livello root cambio il titolo

    $pages[$node['slug']] = $node['title'];

    if($k == 0) {
      $pageArr[$lang] = $node['title'];
      $pages[$node['slug']] = $base_title; // <-- (!)
    }
  }

  // array elementi URL
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri_elems = array_values(array_filter(explode('/', $uri)));

  if($uri_elems[0] != $lang){
    // aggiungo la Home come primo elemento
    array_unshift($uri_elems, $nodes[0]['slug']);
  }

  $depth  = count($uri_elems)-1;

  foreach ($uri_elems as $k => $elem) {
    if (strpos($elem, '~')>0) {
      // prodotto/categoria...
      $title = ucwords(url_decode(strtok($elem, '~'))); //strstr($elem, '~', true); php 5.3
      $url .= $elem.'/';

    } elseif (strpos($elem, '.')>0) {
      if (preg_match('/index(\d)\.html/', $elem, $matches)) {
        // synPager
        $title = ' Pagina '.$matches[1];

      } elseif (preg_match('/([a-zA-Z-_]+)\.html/', $elem, $matches)) {
        // account area
        $title = ' '.ucwords($matches[1]);
      }
    } else {
      // pagina
      $title = $pages[$elem];
      $url .= (!empty($elem)) ? $elem.'/' : null;
    }

    if ($k == $depth) {
      // ultimo nodo!
      //$crumbs[] = "<span>{$title}</span>";
      $crumbs[] = array('title' => $title, 'active' => true);
    } else {
      //$crumbs[] = "<a href=\"{$url}\">{$title}</a> {$divider} ";
      $crumbs[] = array('title' => $title, 'url' => $url, 'active' => false);
    }
  }
  //return $crumbs;
  $smarty->assign('breadcrumbs', $crumbs);
}

// EOF function.breadcrumb.php