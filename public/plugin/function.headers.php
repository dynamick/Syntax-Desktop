<?php
function smarty_function_headers($params, &$smarty) {
  global $db, $synPublicPath;

  $ret            = array();
  $lang           = getLangInitial();
  $langId         = getLangId();
  $titlePage      = $smarty->getTemplateVars( 'synPageTitle' );
  $currentPage    = $smarty->getTemplateVars( 'synPageId' );
  $destPath       = createPath( $currentPage );

  $qry = <<<HTML
    SELECT h.id, h.titolo, t1.{$lang} as titolo, t2.{$lang} as sottotitolo, h.img
    FROM headers h
    LEFT JOIN aa_translation t1 ON h.titolo = t1.id
    LEFT JOIN aa_translation t2 ON h.sottotitolo = t2.id 
    WHERE h.id_pagina = {$currentPage}
HTML;

  $count = 0;
  $res = $db->execute( $qry );

  while ($arr = $res->fetchRow()) {
    extract($arr);
    
    if($count <= 0){
      $first_title = ($titolo != '') ? $titolo : $titlePage;
      $first_subtitle = $sottotitolo;
    }

    if ($img) {
      $src = $img;
    } else {
      $src = $synPublicPath.'/mat/default.png';
    }

    $ret[] = array(
      'id'          => $id,
      'titolo'      => ($titolo != '') ? $titolo : $first_title,
      'sottotitolo' => ($sottotitolo != '') ? $sottotitolo : $first_subtitle,
      'alt'         => attributize($titolo),
      'src'         => $src
    );
    $count ++;
  }

  $smarty->assign( 'headers', $ret );
}