<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.lang.php
* Type:     function
* Name:     lang
* Purpose:  outputs the list of flags and sets the current language
* -------------------------------------------------------------
*/
function smarty_function_lang($params, &$smarty){
  global $db, $synPublicPath;

  if (!isset($_SESSION))
    session_start();

  $session_lang = (isset($_SESSION['synSiteLang'])) 
                  ? intval($_SESSION['synSiteLang']) 
                  : 0;
  $html = '';
  $list = '';

  $qry = 'SELECT * FROM `aa_lang` WHERE `active`="1"';
  $res = $db->Execute($qry);

  while ($arr = $res->FetchRow()) {
    extract($arr);
    $flag     = "<img src=\"{$synPublicPath}/mat/flag/{$flag}\" alt=\"{$lang}\" />";
    $selected = ($id == $session_lang) 
                ? ' class="active"' 
                : null;
    $path     = ($default == '1')      
                ? null 
                : $initial.'/';
    $list    .= "  <li><a href=\"/{$path}\" title=\"{$lang}\"{$selected}>{$flag} {$lang}</a></li>\n";
  }
  
  if (!empty($list)) {
    $html = "<ul class=\"lang-selector\">\n"
          . $list
          . "</ul>\n";
  }
  
  return $html;
}

// EOF