<?
  session_id($_GET["session_id"]);
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


  $tags=$_GET["tags"];
  if ($tags=="") die("Testo vuoto, nessun risultato");
  $tag_arr = explode(",",$tags);  

/*
SELECT tagged.media_id
FROM tagged 
JOIN tags ON tagged.tag_id=tags.id 
WHERE tags.tag LIKE '%giacomo%' AND tagged.media_id IN (
SELECT tagged.media_id 
FROM tagged 
JOIN tags ON tagged.tag_id=tags.id 
WHERE tags.tag LIKE '%lucia%')
*/



  if (is_array($tag_arr)) {
    $count=0;
    $qry="";
    $closures = "";
    foreach ($tag_arr as $t) {
      $t = addslashes(trim($t));
      if ($count>0) {
        $qry .= " AND tagged.media_id IN ( ";
        $closures .= ")";
        $qry .= " SELECT tagged.media_id FROM media JOIN tagged ON media.id=tagged.media_id JOIN tags ON tagged.tag_id=tags.id WHERE tags.tag LIKE '%$t%' "; 
      } else {
        $qry .= " SELECT DISTINCT media.*, tagged.media_id FROM media JOIN tagged ON media.id=tagged.media_id JOIN tags ON tagged.tag_id=tags.id WHERE tags.tag LIKE '%$t%' "; 
      }
      $count++; 
    }
    $qry = $qry.$closures;
 
  }

  $count=0;
  $res=$db->Execute($qry);
  while ($arr=$res->FetchRow()) {
    $filename_parts = pathinfo($arr["filename"]);
    $ret .= "<div class=\"picture_result\" >";
    #$ret .= $arr["path"]."/".$filename_parts["filename"].".".$filename_parts["extension"];
    $ret .= cleverThumbTag($arr["path"]."/", $filename_parts["filename"], $filename_parts["extension"], "", $arr["caption"], 120, 120, false, " rel=\"".$arr["id"]."\" width=\"120\" height=\"120\"");
    $ret .= "<table cellpadding=\"0\" cellspacing=\"0\">";
    $ret .= "<tr><td class=\"label\">Filename: </td><td>".$arr["filename"]."</td></tr>";
    if ($arr["title"]!="") $ret .= "<tr><td class=\"label\">Title: </td><td>".$arr["title"]."</td></tr>";
    if ($arr["caption"]!="") $ret .= "<tr><td class=\"label\">Caption: </td><td>".$arr["caption"]."</td></tr>";
    if ($arr["author"]!="") $ret .= "<tr><td class=\"label\">Author: </td><td>".$arr["author"]."</td></tr>";
    $ret .= "<tr><td class=\"label\">Tags: </td><td>".tags($arr["id"])."</td></tr>";
    $ret .= "</table>";
    $ret .= "</div>";
    if ($count++%4==3) $ret.="<div class=\"clear\"></div>";
  }
  echo $ret;
  if ($res->RecordCount()==0) echo "<p>Nessun risultato ottenuto con queste parole chiave</p>";
?>