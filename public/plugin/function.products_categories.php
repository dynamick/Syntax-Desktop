<?php
function smarty_function_products_categories($params, &$smarty) {
  global $db, $synPublicPath;

  $server           = 'http://'.$_SERVER['SERVER_NAME'];
  $sezione          = $smarty->getTemplateVars( 'synPageId' );
  $idcategoria      = isset($_GET['idcategoria']) ? intval($_GET['idcategoria']) : 0;  
  $lang             = getLangInitial();
  $langId           = getLangId();
  $list             = array();

  if ( $idcategoria === 0) {
    // -------------------------------- ELENCO CATEGORIE ----------------------------- //
    $maxitems = 12;
    $pager    = null;
    $list     = null;

    $qry = <<<EOQ
    SELECT  c.id, c.img,
            t1.{$lang} AS titolo, 
            t2.{$lang} AS descrizione
      FROM  categorie_prodotti c
 LEFT JOIN  aa_translation t1 ON c.titolo = t1.id
 LEFT JOIN  aa_translation t2 ON c.descrizione = t2.id
     WHERE  CONCAT('|', c.visibile, '|') LIKE '%{$langId}%'
  ORDER BY  c.ordine
EOQ;

    $pgr = new synPagerPublic($db, '', '', '', true);
    $pgr->current_template = '<li class="active"><a>%s <span class="sr-only">(current)</span></a></li>';
    $pgr->link_template = '<li><a href="%s">%s</a></li>';
    
    $res = $pgr->Execute($qry, $maxitems);
    $tot = $pgr->rs->maxRecordCount();

    if ($tot>0) {
      while ($arr = $res->FetchRow()) {
        extract($arr);
        $url = createPath($sezione) . createItemPath( $titolo, $id, false );
        $alt = attributize( $titolo );
        
        if ($img) {
          $src = $img;
        } else {
          $src = $synPublicPath.'/mat/default.png';
        }

        $list[] = array(
          'id'            => $id,
          'url'           => $url,
          'src'           => $src,
          'alt'           => $alt,
          'titolo'        => $titolo,
          'descrizione'   => $descrizione,
        );
      }
    
    }

    // print navigation buttons
    if ($pgr->rs->LastPageNo()>1)
      $pager = $pgr->pagerArrList();
    
    $smarty->assign('products_categories', $list);
    $smarty->assign('pagination', $pager);
  }  
}


// EOF function.categorie_prodotti.php