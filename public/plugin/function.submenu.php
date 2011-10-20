<?php
/*
  PARAMETERS: 

  startPage=tree menu starting node. If null, the menu start from home page 
  expand=the menu is full expanded
  includeParent=show the starting node

*/

function smarty_function_submenu($params,&$smarty){
  global $db;

  $nodeArr=$smarty->synPageNode;
  $startPage=$params["startPage"];
  $expand=$params["expand"];
  $includeParent=$params["includeParent"];
  $firstChild=$params["firstChild"];
  
  if ($startPage=="") $startPage=$nodeArr[1]["id"];
  if ($expand=="") $expand=false;
  if ($includeParent=="") $includeParent=false;
  if ($firstChild=="") $firstChild=false;
  
  $ret .= "<h3>".$nodeArr[1]['title']."</h3>\n";
  $ret .= createSubmenu($startPage, $expand, $includeParent, $firstChild, "title");

  return $ret;
}
?>
