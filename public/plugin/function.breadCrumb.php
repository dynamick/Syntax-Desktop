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
function smarty_function_breadCrumb($params, &$smarty) {

  $nodeArr = $smarty->synPageNode;
  foreach($nodeArr as $node) {
    $pageArr[sanitizePath($node['title'])] = $node['title'];
  }

  // array elementi URL
  $uri = $_SERVER['REQUEST_URI'];
  $uri_elems = array_filter(explode('/', $uri));

  // aggiungo la Home come primo elemento
  array_unshift($uri_elems, sanitizePath($nodeArr[0]['title']));

  $depth = count($uri_elems);
  $crumbs = $url = '';

  foreach ($uri_elems as $k => $elem){
    if(strpos($elem, '~')>0){
      // prodotto/categoria...
      $title = ucwords(url_decode(strtok($elem, '~'))); //strstr($elem, '~', true); php 5.3
      $url .= $elem.'/';

    } elseif(preg_match('/index(\d)\.html/', $elem, $matches)) {
      // synPager
      $title = ' Pagina '.$matches[1];

    } elseif(preg_match('/([a-zA-Z-_]+)\.html/', $elem, $matches)) {
      // account area
      $title = ' '.ucwords($matches[1]);

    } else {
      // pagina
      $title = $pageArr[$elem];
      if($k==0) $elem = '';
      $url .= $elem.'/';
    }

    if(($k+1)<$depth){
      $crumbs .= " <a href=\"{$url}\">{$title}</a> &rsaquo; ";
    } else {
      // ultimo nodo!
      $crumbs .= $title;
    }
  }

  return $crumbs;
}
?>
