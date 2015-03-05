<?php
function smarty_function_socialLinks($params, &$smarty) {
  global $db;

  $ret = array();
  $qry = "SELECT * FROM social_network WHERE visible='1'";
  $res = $db->execute( $qry );
  while ( $arr = $res->fetchRow()){
    $ret[] = array(
      'url' => $arr['url'],
      'icon' => $arr['social']
    );
  }

  $smarty->assign( 'social_links', $ret );
}

// EOF function.socialLinks.php