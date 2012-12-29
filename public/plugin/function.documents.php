<?php
function smarty_function_documents($params, &$smarty) {
  global $db, $synPublicPath, $synAbsolutePath;
  
  if(!isset($_SESSION))
    session_start();

  $cat       = isset($params['cat']) ? $params['cat'] : '';
  $userid    = isset($_COOKIE['web_user']['id']) ? $_COOKIE['web_user']['id'] : 0;
  $usergroup = isset($_COOKIE['web_user']['group']) ? $_COOKIE['web_user']['group'] : 0;
  $lng       = $_SESSION['synSiteLangInitial'];
  $html      = '';
//echo $usergroup;

  if($usergroup != "") {
    $usergroup = explode('|', $usergroup);
  } else 
    $usergroup = array();

  $qry = <<<EOQ
   SELECT d.id, d.file, d.date, d.status, d.enabled_groups, d.category_id,
          t1.{$lng} AS title, t2.{$lng} AS category, t3.{$lng} AS description
     FROM documents d
     JOIN categories      c ON d.category_id = c.id
LEFT JOIN aa_translation t1 ON d.title = t1.id
LEFT JOIN aa_translation t2 ON c.category = t2.id
LEFT JOIN aa_translation t3 ON d.description = t3.id
 ORDER BY c.`order`, d.`date` DESC, d.title
EOQ;

  //$pgr = new synPager($db, '', '', '', false, true);
  //$res = $pgr->Execute($qry, 8, "cat=".$cat);
  //$nav = $pgr->renderPagerPublic('', true, true);
  $res = $db->execute($qry);

  if($arr=$res->FetchRow()) {
    $t = multiTranslateDictionary(array('doc_riservato','doc_no_abilitazione'));
    do {
      $catid = $arr['category_id'];
      $catname = $arr['category'];

      $html .= "<div class=\"download-content\">\n";
      $html .= "  <h4>{$catname}</h4>\n";
      $html .= "  <ul class=\"file\">\n";
      do {
        $ext        = $arr['file'];
        $file       = "{$synPublicPath}/mat/documents/documents_file_id{$arr['id']}.{$ext}";
        $size       = @filesize($synAbsolutePath.$file);
        $file_label = $ext.", ".byteConvert($size);
        $status     = $arr['status'];

        if($arr['enabled_groups']){
          $owner = explode('|', $arr['enabled_groups']);
        } else 
          $owner = array();

        switch(strtolower($ext)) {
          case "xlsx" :
          case "xls" :
            $class= "xls"; break;
          case "pdf" :
            $class= "pdf"; break;
          case "zip" :
            $class= "zip"; break;
          default :
            $class= "pdf"; break;
        }
        
        $intersect = array_intersect($usergroup, $owner);
        
        if(is_array($intersect) && count($intersect) > 0) $have_same_group = true;
        else $have_same_group = false;
        
        if (
//            ($status == 'secret' && ((in_array($usergroup, $owner) || !$owner) && $userid != '') ) ||
            ($status == 'secret' && ($have_same_group && $userid != '') ) ||
            ($status == 'private' && $userid!='' ) ||
            ($status == 'public') ||
            ($status == 'protected')
          ){
          # file pubblico o autorizzato per l'utente o visibile
          $privato = ($status != 'public') ? ' class=\"privato"' : '';
          //$testo   = ($arr['abstract']) ? $arr['abstract']."<br />" : '';
          if(
            ($status == 'public') ||
            ($status == 'protected' && $userid != '') ||
//            (($status == 'private' || $status == 'secret') && ((in_array($usergroup, $owner) || !$owner) && $userid != '') )
            (($status == 'private' || $status == 'secret') && ($have_same_group && $userid != '') )
            ){
            # file pubblico o autorizzato per l'utente
            $link  = $file;
          } else {
            # file privato o non autorizzato per l'utente
            $alert = ($userid ? $t['doc_no_abilitazione'] : $t['doc_riservato']);
            $link  = "javascript:alert('{$alert}')";
          }

          $html .= <<<EOHTML
          <li>
            <a class="download" href="{$link}">
              <span class="icon {$class}"></span>
              <h4>{$arr['title']}</h4>
              <p>{$arr['description']}</p>
              <span>{$file_label}</span>
            </a>
          </li>
EOHTML;
        }
        $next = ($arr=$res->FetchRow());
      } while ($next && $catid==$arr['category_id']);

      $html .= "  </ul>\n";
      $html .= "</div>\n";
    } while ($next);
    
  } else {
    $html .= $nav;
    $html .= "<h4>Nessun elemento disponibile.</h4>";
  }

  return $html;
}

// EOF