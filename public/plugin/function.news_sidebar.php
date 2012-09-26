<?php
function smarty_function_news_sidebar($params, &$smarty) {
  global $db;

  $newsPage = createPath(55);
  $langId   = $_SESSION["synSiteLang"];
  $lang     = $_SESSION["synSiteLangInitial"];
  $html     = "";

  $qry = <<<EOQ
    SELECT n.id, n.date, t.{$lang} AS titolo
      FROM news n
      JOIN aa_translation t ON n.title=t.id
     WHERE CONCAT('|', n.`visible`, '|') LIKE '%|{$langId}|%'       
  ORDER BY n.`date` DESC
     LIMIT 0,5
EOQ;
  $res = $db->Execute($qry);
  if (!is_object($res)) {
    $html = "<p>Nessuna news inserita.</p>\n";
  } else {

    while ($arr=$res->FetchRow()) {
      extract($arr);
      $date  = sql2date($date);
      $link  = $newsPage.sanitizePath($titolo)."~{$id}.html"; // nice url

      $list .= "  <li>\n";
      $list .= $date;
      $list .= "    <h4>{$titolo}</h4>\n";
      $list .= "    <a class=\"follow\" href=\"{$link}\">leggi tutto</a>\n";
      $list .= "  </li>\n";

    }

    $html .= "<ul class=\"news\">\n".$list." </ul>\n";
  }

  return $html;
}
?>

