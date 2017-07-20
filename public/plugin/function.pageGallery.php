<?php
function smarty_function_pageGallery($params, &$smarty) {
  global $db, $synPublicPath;

  $gallery_id = $smarty->getTemplateVars( 'synPageGallery' );
  $lang   = getLangInitial();
  $langId = getLangId();
  $list   = array();

  // -------------------------------- PAGE GALLERY ----------------------------- //
    
  $qry = <<<EOQ
    SELECT  p.id, p.photo, p.album
      FROM  photos p
     WHERE  p.album = $gallery_id
  ORDER BY  p.ordine
EOQ;

  $res = $db->Execute($qry);
  while ($arr = $res->FetchRow()) {
    extract($arr);
    
    $src   = $synPublicPath."/mat/album/{$album}/photos_photo_id{$id}.{$photo}";
    $alt   = htmlspecialchars(trim(strip_tags($title)));

    $list[] = array(
      'id' => $id,
      'url' => $url,
      'src' => $src,
      'alt' => $alt,
      'date' => $date,
      'fdate' => $fdate,
      'title' => $title
    );
  }
  $smarty->assign('pagePhotos', $list);

}

// EOF function.gallery.php