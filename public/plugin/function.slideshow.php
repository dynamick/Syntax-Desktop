<?php
function smarty_function_slideshow($params, &$smarty){
  global $db;

  #$pag = $smarty->get_template_vars('synPageId');
  $alb   = '';
  $nav   = $params['nav'];
  $gal   = $params['gal'];
  $where = ($alb!="") ? "WHERE album='{$alb}'" : '';
  $thumb = '';
  $html  = '';
  $slide = '';
  
  $qry = "SELECT * FROM photos {$where} ORDER BY `ordine`";
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
    $html .= <<<EOHTML
    <div id="gallery-out" class="{$class}">
      <div class="gallery-in">
        <ul id="slide-nav">
        {$thumb}
        </ul>
      </div>
    </div>
EOHTML;
  }

  return $html;
}

// EOF