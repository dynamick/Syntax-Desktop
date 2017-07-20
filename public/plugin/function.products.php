<?php
function smarty_function_products($params, &$smarty) {
  global $db, $synPublicPath;

  $server           = 'http://'.$_SERVER['SERVER_NAME'];
  $sezione          = $smarty->getTemplateVars( 'synPageId' );
  $idcategoria      = isset($_GET['idcategoria']) ? intval($_GET['idcategoria']) : 0;  
  $lang             = getLangInitial();
  $langId           = getLangId();
  $list             = array();

  if ( $idcategoria !== 0) {
    // -------------------------------- ELENCO PRODOTTI DI CATEGORIA ----------------------------- //
    $maxitems = 12;
    $pager    = null;
    $list     = null;

    $qry = <<<EOQ
    SELECT  p.id, fp.id as foto_id, c.id AS id_categoria, fp.img,
            t1.{$lang} AS titolo, 
            t2.{$lang} AS descrizione,
            t3.{$lang} AS titolo_categoria
      FROM  prodotti p
 LEFT JOIN  categorie_prodotti c ON p.id_categoria = c.id 
 LEFT JOIN  foto_prodotti fp ON fp.id_prodotto = p.id
 LEFT JOIN  aa_translation t1 ON p.titolo = t1.id
 LEFT JOIN  aa_translation t2 ON p.descrizione = t2.id
 LEFT JOIN  aa_translation t3 ON c.titolo = t3.id
     WHERE  p.id_categoria = {$idcategoria} AND CONCAT('|', p.visibile, '|') LIKE '%{$langId}%'
  GROUP BY  p.id
  ORDER BY  p.ordine, fp.ordine
EOQ;

  //die($qry);

    $pgr = new synPagerPublic($db, '', '', '', true);
    $pgr->current_template = '<li class="active"><a>%s <span class="sr-only">(current)</span></a></li>';
    $pgr->link_template = '<li><a href="%s">%s</a></li>';
    
    $res = $pgr->Execute($qry, $maxitems);
    $tot = $pgr->rs->maxRecordCount();

    if ($tot>0) {
      while ($arr = $res->FetchRow()) {
        extract($arr);
        $url = createPath($sezione) . createItemPath( $titolo_categoria, $id_categoria, false ) . createItemPath( $titolo, $id );
        $alt = attributize( $titolo );
        
        if ($img) {
          $src = "{$synPublicPath}/mat/products/{$id}/foto_prodotti_img_id{$foto_id}.{$img}";;
        } else {
          $src = $synPublicPath.'/mat/default.png';
        }

        $list[] = array(
          'id'            => $id,
          'titolo'        => $titolo,
          'abstract'      => troncaTesto( strip_tags($descrizione), 150 ),
          'src'           => $src,
          'alt'           => $alt,
          'url'           => $url
        );
      }
    
    }

    // print navigation buttons
    if ($pgr->rs->LastPageNo()>1)
      $pager = $pgr->pagerArrList();
    
    $smarty->assign('category', $titolo_categoria);
    $smarty->assign('products', $list);
    $smarty->assign('pagination', $pager);
  }  
}


// EOF function.categorie_prodotti.php