<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.path.php
* Type:     function
* Name:     path
* Purpose:  Ritorna il path "Briciole di pane" dalla posizione corrente
* -------------------------------------------------------------
*/
function smarty_function_breadCrumb($params, &$smarty) {
  global $db;

  $nodeArr = $smarty->synPageNode;
  $currPage = $smarty->getTemplateVars('synPageId');
  foreach($nodeArr as $node) $idArr[]= array('id'=>$node["id"], 'title'=>$node["title"]);

  $reqtitle = strip_tags($_GET['title']);

  foreach ($nodeArr as $k => $page){
    $title = $page['title'];
    if ($page['id']!=$currPage){
      $path = createPath($page['id']);
      $ret .= " <a href=\"{$path}\">{$title}</a> &rsaquo; ";
    } else {
      if($reqtitle!=''){
        $path = createPath($page['id']);
        $ret .= " <a href=\"{$path}\">{$title}</a> &rsaquo; ";
        $ret .= ucwords(url_decode($reqtitle));
      } else {
        $ret .= ucwords($title);
      }
    }
  }
  return $ret;
}
?>
