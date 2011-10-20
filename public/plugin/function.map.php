<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.path.php
* Type:     function
* Name:     path
* Purpose:  Ritorna il path "Briciole di pane" dalla posizione corrente
* -------------------------------------------------------------
*/

/*
  Questo plugin va posizionato nell'header come primo plugin di tutta la pagina.
  Si devono poi utilizzare le due variabili {$script} e {$onload} che vanno posizionate
  correttamente nell'<head> della pagina.

  Infine, il codice sotto va posizinato nel template nel punto esatto dove andrà 
  visualizzata la mappa
  
  <div id="map" style="width: 400px; height: 400px"></div>

*/

function smarty_function_map($params, &$smarty)
{
  if ($smarty->tpl_vars[synPageId]!=50) return;

  global $db;
  $key="ABQIAAAAtbdsvAeiRWKDV6mXFLfs3BQZ8zL4PBcW1ezs8ByFDtcU30FomRSHR9by5gRow5SVu4ycKTmDKglMzg";    
  $startLat=45.43347361805792;
  $startLon=12.33914852142334;
  $startZoom=14;

  // Performing SQL query
  $query = "SELECT * FROM punti";
  $result = $db->Execute($query) or die('Query failed: ' . mysql_error());
  while ($row = $result->FetchRow($result)) {
    //printf("ID: %s  Name: %s", $row[0], $row[1]);
    $id=$row["id"];
    $lat=$row["lat"];
    $long=$row["long"];

    $titolo=translateSite($row["titolo"]);
    $descrizione=translateSite($row["descrizione"]);
    $url=$row["link"];
    if ($url!="" and $url!="http://") $titolo="<a href='$url'>$titolo</a>";

    $txt.="var point$count = new GLatLng($long,$lat);\n";
    $txt.="map.addOverlay(createMarker(point$count,\"$titolo\",\"$descrizione\",\"$url\"));\n";
    $count++; 
  }
  
  ob_start();?>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=$key?>" type="text/javascript"></script>
    <script type="text/javascript">
      function load() {
        var map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(<?=$startLat?>,<?=$startLon?>), <?=$startZoom?>);
        map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());
        map.setMapType(G_HYBRID_MAP);
        <?=$txt?>
      }
      function createMarker(point, txt, desc, url) {
        var icon = new GIcon();
        icon.image = "http://www.javaopenbusiness.it:80/com.icteam.ospmi.presentation.common.HtmlDriverOspmi/img/1_employee.png";//rosso omino
        icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
        icon.iconSize = new GSize(12, 20);
        icon.shadowSize = new GSize(22, 20);
        icon.iconAnchor = new GPoint(6, 20);
        icon.infoWindowAnchor = new GPoint(5, 1);
        var marker = new GMarker(point, icon);
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml("<b>" + txt + "</b><p>" + desc+ "</p>");
        });
        return marker;
      }
    </script>
  <?
  $contents=ob_get_contents();
  ob_end_clean();
  
  $smarty->assign("script",$contents);
  $smarty->assign("onload","onload=\"load()\" onunload=\"GUnload()\"");

  
  return;   
}
?>
