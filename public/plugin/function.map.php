<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.map.php
* Type:     function
* Name:     map
* Purpose:  crea una google map con vari punti
* -------------------------------------------------------------
*/
function smarty_function_map($params, &$smarty){
  global $db, $synPublicPath;
  $sedi = array();

  $qry = "SELECT * FROM sedi ORDER BY id";
  $res = $db->Execute($qry);
  while ($arr = $res->FetchRow()){
        $id = $arr['id'];
      $nome = $arr['nome'];
 $indirizzo = $arr['indirizzo'];
       $tel = $arr['tel'];
       $web = $arr['web'];
      $foto = $arr['foto'];
       $lat = $arr['lat'];
       $lng = $arr['long'];

    $sedi[] = <<<EOS
        {id : "$id",
       nome : "$nome",
  indirizzo : "$indirizzo",
   telefono : "$tel",
        web : "$web",
       foto : "/public/mat/sedi_foto_id$id.$foto",
        lat : $lat,
        lng : $lng}

EOS;
  }

  ob_start();
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  var infowindow = null;
  $(document).ready(function(){
    initialize();
  });

  function initialize() {
    var myOptions = {
      zoom: 13,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

    var bounds = new google.maps.LatLngBounds();
    setMarkers(map, arrSedi, bounds);
    map.fitBounds(bounds);

    infowindow = new google.maps.InfoWindow({
      content: "loading..."
    });
  }

  var arrSedi = [
<?= implode(",\n", $sedi); ?>
  ];

  function setMarkers(map, markers, bounds) {
    for (var i = 0; i < markers.length; i++) {
      var item = markers[i];
      var siteLatLng = new google.maps.LatLng(item.lat, item.lng);
      var marker = new google.maps.Marker({
        position: siteLatLng,
        map: map,
        title: item.name,
        animation: google.maps.Animation.DROP,
        icon: '<?= $synPublicPath ?>/img/m_red.png',
        html: '<h4 style="margin:0">'+ item.nome +'<\/h4><\/div>'
            + '<img src="'+ item.foto +'" class="foto-sede" width="213" height="72">'
            + '<div><address>'+ item.indirizzo +'<br>'+ item.telefono +'<\/address><\/div>'
            + '<div><a href="http://'+ item.web +'" target="_blank">'+ item.web +'<\/a><\/div>'
      });
      bounds.extend(marker.position);

      var contentString = "Some content";
      google.maps.event.addListener(marker, "click", function () {
        infowindow.setContent(this.html);
        infowindow.open(map, this);
      });
    }
  }
</script>
<?php
  $contents = ob_get_contents();
  ob_end_clean();

  return $contents;
}
?>
