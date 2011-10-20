<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.lang.php
* Type:     function
* Name:     lang
* Purpose:  outputs the list of flag and set the current language
* -------------------------------------------------------------
*/
function smarty_function_lang($params, &$smarty){
  global $db, $synPublicPath, $synSiteLang;
  $get = "";

  if (getenv("QUERY_STRING")!="") {
    $getArray=explode("&",getenv("QUERY_STRING"));
    foreach ($getArray as $k=>$v)
      if (substr($v,0,11)!="synSiteLang") $get.=$v."&amp;";
  }

  $attr=$params["attr"];
  $qry="SELECT * FROM `aa_lang` WHERE `active`=1";
  $res=$db->Execute($qry);
  while ($arr=$res->FetchRow()) {
    $id          = $arr["id"];
    $lang        = $arr["lang"];
    $initial     = $arr["initial"];
    $flag        = "<img src=\"{$synPublicPath}/mat/flag/".$arr['flag']."\" alt=\"{$lang}\" /> ";
    $selected    = (intval($id)==intval($_SESSION["synSiteLang"])) ? ' class="selected"' : '';
    $getWithLang = $get."synSiteLang=$id";

    $html .= "<li><a href=\"{$PHP_SELF}?{$getWithLang}\" title=\"{$lang}\"{$selected}>{$flag}{$lang}</a></li>\n";
  }
  return $html;
}
?>
