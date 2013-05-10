<?php
/*
  PARAMETERS: 

  startPage=tree menu starting node. If null, the menu start from home page 
  expand=the menu is full expanded
  includeParent=show the starting node

*/

function smarty_function_submenu($params, &$smarty){
  global $db;

  $nodeArr       = $smarty->synPageNode;
  $level         = ($nodeArr[1]['id']==9999) ? 2 : 1;  
  
  $startPage     = empty($params['startPage'])     ? $nodeArr[$level]['id'] : $params['startPage'];
  $expand        = empty($params['expand'])        ? false : $params['expand'];
  $includeParent = empty($params['includeParent']) ? false : $params['includeParent'];
  $firstChild    = empty($params['firstChild'])    ? false : $params['firstChild'];
  
 
  $ret  = "<h3 class=\"menu-header\">".$nodeArr[$level]['title']."</h3>\n";
  $ret .= createSubmenu($startPage, $expand, $includeParent, $firstChild, "title");

  return $ret;
}

// EOF