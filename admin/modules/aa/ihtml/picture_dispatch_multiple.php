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


  $tags     =$_GET["tags"];
  $selected = $_GET["selected"];
  if ($tags == "" and $selected == "") die("Testo vuoto, nessun risultato");

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
  if ($tags != "") {
    $tag_arr = explode(",",$tags);  
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
  } 
  
  if ($selected!="") {
    $selected=str_replace('|',',',$selected);
    $qry = "SELECT DISTINCT media.* FROM media WHERE media.id IN (".$selected.")";
  }  
  

  $count=0;
  $res=$db->Execute($qry);
  while ($arr=$res->FetchRow()) {
    $filename_parts = pathinfo($arr["filename"]);
    list($width, $height, $type, $attr) = getimagesize(getenv("DOCUMENT_ROOT").$arr["path"]."/".$arr["filename"]);
    $ret .= "<div class=\"scProductListItem\" >";
    $ret .= cleverThumbTag($arr["path"]."/", $filename_parts["filename"], $filename_parts["extension"], "", $arr["caption"], 120, 0, false, " rel=\"".$arr["id"]."\" width=\"120\" height=\"120\" class=\"scProductListItemImg\" ");
    $ret .= "<table cellpadding=\"0\" cellspacing=\"0\" class=\"scProductListItemInfo\">";
    $ret .= "<tr><td class=\"label\">Filename: </td><td>".$arr["filename"]."</td></tr>";
    if ($arr["title"]!="") $ret .= "<tr><td class=\"label\">Title: </td><td><span id=\"prod_name".$arr['id']."\">".$arr["title"]."</span></td></tr>";
    if ($arr["caption"]!="") $ret .= "<tr><td class=\"label\">Caption: </td><td>".$arr["caption"]."</td></tr>";
    if ($arr["author"]!="") $ret .= "<tr><td class=\"label\">Author: </td><td>".$arr["author"]."</td></tr>";
    $ret .= "<tr><td class=\"label\">Dimensioni: </td><td>".$width."x".$height."px</td></tr>";
    if ($width>$height) $ret .= "<tr><td class=\"label\">Orientamento: </td><td>Orizzontale</td></tr>";
    else $ret .= "<tr><td class=\"label\">Orientamento: </td><td>Verticale</td></tr>";
    $ret .= "<tr><td class=\"label\">Tags: </td><td>".tags($arr["id"])."</td></tr>";
    $ret .= "</table>";
    $ret .= "<input name=\"pic_id_".$arr['id']."\" class=\"pic_id\" value=\"".$arr['id']."\" type=\"hidden\">";
		$ret .= "<input type=\"button\" rel=\"".$arr['id']."\" class=\"scItemButton scBtn\" value=\"Aggiungi\">";
    $ret .= "</div>";
    
  }
  echo $ret;
  if ($res->RecordCount()==0) echo "<p>Nessun risultato ottenuto con queste parole chiave</p>";
?>