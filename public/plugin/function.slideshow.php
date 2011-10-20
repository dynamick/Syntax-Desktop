<?php
function smarty_function_slideshow($params, &$smarty){
  global $db;

  #$pag = $smarty->get_template_vars('synPageId');
  $alb = "";
  $nav = $params['nav'];
  $gal = $params['gal'];
  if ($alb!="") $where="WHERE album=$alb";
  $qry = "SELECT * FROM photos $where ORDER BY `ordine`";
  $res = $db->Execute($qry);

  while($arr = $res->FetchRow()){
    extract($arr);

    $slide .= cleverThumb('', "photos_photo_id{$id}", $photo, '', $title, 678, 383).PHP_EOL;

    if($nav==true){
      $thumbimg = cleverThumb('', "photos_photo_id{$id}", $photo, 'thumb_', $title, 79, 48).PHP_EOL;
      $thumb .= "      <li><a href=\"#\" class=\"\">{$thumbimg}</a></li>\n";
    }
  }

  $html .= "<div class=\"slideshow\">\n";
  $html .= $slide;
  $html .= "</div>\n";

  if($nav==true){
    $class = ($gal) ? '' : 'small';
    $html .= "<div id=\"gallery-out\" class=\"{$class}\">\n";
    $html .= "  <div class=\"gallery-in\">\n";
    $html .= "    <ul id=\"slide-nav\">\n";
    $html .= $thumb;
    $html .= "    </ul>\n";
    $html .= "  </div>\n";
    $html .= "</div>\n";

  }

  return $html;
}
?>
