<?php
function smarty_function_product($params, &$smarty) {
  global $db, $synPublicPath;

  $server           = 'http://'.$_SERVER['SERVER_NAME'];
  $req              = isset($_GET['id']) ? intval($_GET['id']) : 0;  
  $lang             = getLangInitial();
  $langId           = getLangId();

  if ( $req > 0) {
    // -------------------------------- DETTAGLIO PRODOTTO ----------------------------- //
    $qry = <<<EOQ
    SELECT  p.id,
            p.allegato,
            t1.{$lang} AS titolo, 
            t2.{$lang} AS descrizione
      FROM  prodotti p
 LEFT JOIN  aa_translation t1 ON p.titolo = t1.id
 LEFT JOIN  aa_translation t2 ON p.descrizione = t2.id
     WHERE  p.id = {$req} AND CONCAT('|', p.visibile, '|') LIKE '%{$langId}%'
  ORDER BY  p.ordine
EOQ;

    $res = $db->Execute($qry);
    if ($arr = $res->FetchRow()) {
      extract($arr);
      $alt = attributize( $titolo );
        
      $output = array(
        'id'            => $id,
        'titolo'        => $titolo,
        'descrizione'   => $descrizione,
        'src'           => product_gallery($db, $synPublicPath, $id),
        'alt'           => $alt,
        'allegato'      => $allegato
      );
      
      $smarty->assign('product', $output);

    } else {
      header('HTTP/1.0 404 Not Found');
      header('Location: /404/');
    }
    
  }  
}

function product_gallery($db, $synPublicPath, $product_id, $limit=NULL){
  $gallery = array();

  $limitRes = $limit != NULL ? "LIMIT 0," . $limit : "";

  $qry = <<<HTML
    SELECT f.id, f.img
    FROM foto_prodotti f
    WHERE f.id_prodotto = '{$product_id}' 
    ORDER BY f.ordine
    {$limitRes}
HTML;
  $res = $db->Execute($qry);
  while ( $arr = $res->FetchRow() ) {
    extract( $arr );

    $src = "";
    if ($img) {
      $src = "{$synPublicPath}/mat/products/{$product_id}/foto_prodotti_img_id{$id}.{$img}";
    }

    $gallery[] = array(
      'id'          => $id,
      'src'         => $src,
    );
  }

  return $gallery;
  
}


// EOF function.categorie_prodotti.php