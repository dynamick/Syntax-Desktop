<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.pageInfo.php
* Type:     function
* Name:     menu
* Purpose:  Ritorna l'informazione della pagina richiesta
* -------------------------------------------------------------
*/
/*
  PARAMETERS: 

  page = l'id della pagina 
  info = il campo di cui si vuole il valore

*/
function smarty_function_pageInfo($params, &$smarty) {
  global $db;

  $ret = "";
  if (isset($params["page"]) && isset($params["info"])) {
    $id = intval($params["page"]);
    $info = $params["info"];
    if($info=="path") {
      $ret = createPath($id);
    } else {    
      $qry="SELECT * FROM `aa_page` WHERE `id`='$id' ";
      $res=$db->Execute($qry);
      if($arr=$res->FetchRow()) {  
        $ret = translateSite($arr[$params["info"]]);
      }
    }  
  }  
  return $ret;
}
?> 