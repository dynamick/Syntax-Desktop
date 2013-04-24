<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.menu.php
* Type:     function
* Name:     menu
* Purpose:  output the menu in a list form
* -------------------------------------------------------------
*/
/*
  PARAMETERS: 

  startPage=tree menu starting node. If null, the menu start from home page 
  includeParent=show the starting node

*/

function smarty_function_menu($params, &$smarty){
  global $db;

  $nodeArr       = $smarty->synPageNode;
  $startPage     = isset($params["startPage"])     ? $params["startPage"]     : $nodeArr[0]["id"];
  $includeParent = isset($params["includeParent"]) ? $params["includeParent"] : true;
  $firstChild    = isset($params["firstChild"])    ? $params["firstChild"]    : false;

  $ret = createMenu($startPage, $includeParent, $firstChild);
  return "<ul class=\"main-menu\">".$ret."</ul>";
}
?>
