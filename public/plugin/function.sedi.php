<?php
function smarty_function_sedi($params, &$smarty) {
  global $db, $synPublicPath;
  $lang     = getLangInitial();
  $langId   = getLangId();
  $list     = array();

  $qry = <<<EOQ
    SELECT s.id, t1.{$lang} AS sede, t2.{$lang} AS indirizzo, s.lat, s.long
    FROM sedi s
    LEFT JOIN  aa_translation t1 ON s.sede = t1.id
    LEFT JOIN  aa_translation t2 ON s.indirizzo = t2.id
    WHERE CONCAT('|', s.visibile, '|') LIKE '%{$langId}%'
    ORDER BY s.ordine
EOQ;

  $res = $db->execute( $qry );
  while ( $arr = $res->fetchRow()){
    extract($arr);

    $list[] = array(
      'id'        => $id,
      'sede'      => $sede,
      'indirizzo' => $indirizzo,
      'lat'       => $lat,
      'long'      => $long
    );

  }

  $smarty->assign('sedi', $list);

} // end plugin
