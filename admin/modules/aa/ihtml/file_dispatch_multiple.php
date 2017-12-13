<?php
  //session_id($_GET["session_id"]);
  include_once ("../../../config/cfg.php");
  global $synAbsolutePath;
  global $synPublicPath;

  function tags($media_id) {
    global $db;
    $qry="SELECT * FROM tags JOIN tagged ON tags.id=tagged.tag_id WHERE tagged.media_id = ".$media_id;
    $res=$db->Execute($qry);
    while ($arr=$res->FetchRow()) {
      $tags[] = $arr["tag"];
    }
    return implode(", ",$tags);
  }


  $tags     = isset($_GET["tags"]) ?  $_GET['tags'] : null;
  $selected = isset($_GET["selected"]) ? $_GET['selected'] : null;
  if ($tags == "" and $selected == "") die("Testo vuoto, nessun risultato");

  if ($tags != "") {
    $qry = <<<EOQ
   SELECT d.id, d.file, t1.it AS titolo, t2.it AS description
     FROM `documents` d
LEFT JOIN aa_translation t1 ON d.title=t1.id
LEFT JOIN aa_translation t2 ON d.description=t2.id
    WHERE t1.it LIKE '%{$tags}%'
       OR t2.it LIKE '%{$tags}%'
    LIMIT 0 , 30
EOQ;

  } elseif ($selected!="") {

    $selected = str_replace('|',',',$selected);
    $qry = <<<EOQ
   SELECT d.id, d.file, t1.it AS titolo, t2.it AS description
     FROM `documents` d
LEFT JOIN aa_translation t1 ON d.title=t1.id
LEFT JOIN aa_translation t2 ON d.description=t2.id
    WHERE d.id IN ({$selected})
EOQ;
  }


  $count=0;
  $res = $db->Execute($qry);
  $ret = '';
  while ($arr = $res->FetchRow()) {
    $filename    = 'documents_file_id'.$arr['id'];
    $ext         = $arr['file'];
    $fullpath    = $synPublicPath . $mat . '/documents/' . $filename .  '.' . $ext;
    $title       = $arr['titolo'] ? $arr['titolo'] : 'documents_file_id' . $arr['id'] . '.' . $arr['file'];
    $description = $arr['description'] ? $arr['description'] : 'file .' . $ext;

    $ret .= '<div class="list-group-item">';
    $ret .=   '<button type="button" data-item="' . $arr['id'] . '" class="scBtn btn btn-success pull-right"><i class="fa fa-plus-circle"></i></button>';
    $ret .=   '<h5 class="list-group-item-heading"><a href="'. $fullpath . '" target="_blank">' . $title . '</a></h5>';
    $ret .=   '<p class="list-group-item-text">' . $description . '</p>';
    $ret .=   "<input name=\"pic_id_{$arr['id']}\" class=\"pic_id\" value=\"{$arr['id']}\" type=\"hidden\">";
    $ret .= '</div>';
  }
  echo $ret;
  if ($res->RecordCount()==0) echo "<p>Nessun risultato ottenuto con queste parole chiave</p>";
?>
