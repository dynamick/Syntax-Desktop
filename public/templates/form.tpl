<!DOCTYPE html>
<html lang="{$synLangInitial}">
{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}

  <!-- Page Content -->
  <div class="section">
    <div class="container">
      <!-- Page Heading/Breadcrumbs -->
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">
            {$synPageTitle}
          </h1>
          {include file="partial/_breadcrumbs.tpl"}
        </div>
      </div>
      <!-- /.row -->

      <!-- Intro Content -->
      <div class="row">
        <div class="col-md-8">
        {form page=$synPageId}
        {append var=synPageScript value=$pageScript scope=parent}
        </div>

        <div class="col-md-4">
        {include file="partial/_sidebar.tpl"}
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container -->
  </div>

  {sedi}
  {if !empty($sedi)}
    <div id="googleMapNew" class="map-container"></div>
    {capture name=googleMaps}
    <script src="https://maps.googleapis.com/maps/api/js?key={$smarty.const.GMAPS_API}&language={$synLangInitial}&sensor=false&extension=.js"></script>
    <script>
      google.maps.event.addDomListener(window, 'load', init_googleMaps);
      var myMarkers = [];
      var map;
      function init_googleMaps() { 
        var bounds = new google.maps.LatLngBounds();
        var mapElement = document.querySelector("#googleMapNew");
        //var markericon = "/public/img-kleis/marker-icon.png";
        var mapOptions = { 
          center : new google.maps.LatLng(42.5207, 12.213134),
          zoom : 6,
          zoomControl : true,
          zoomControlOptions : { 
            style : google.maps.ZoomControlStyle.LARGE,
            position : google.maps.ControlPosition.RIGHT_BOTTOM
            }  ,
          disableDoubleClickZoom : false,
          mapTypeControl : false,
          mapTypeControlOptions : { 
            style : google.maps.MapTypeControlStyle.DROPDOWN_MENU,
            }  ,
          scaleControl : false,
          scrollwheel : false,
          panControl : true,
          panControlOptions : { 
            position : google.maps.ControlPosition.RIGHT_BOTTOM
            }  ,

          streetViewControl : false,
          draggable : true,
          overviewMapControl : true,
          overviewMapControlOptions : { 
            opened : false,
            }  ,
          mapTypeId : google.maps.MapTypeId.ROADMAP
        } 
        map = new google.maps.Map(mapElement, mapOptions);
        var locations = [ 
          
          {foreach $sedi as $sede}
            { 
              'sede': '{$sede.sede|escape:javascript}', 
              'indirizzo': '{$sede.indirizzo|escape:javascript}', 
              'telefono': '{$sede.telefono|escape:javascript}', 
              'latlon': [{$sede.lat|escape:javascript}, {$sede.long|escape:javascript}] 
            },
          {/foreach}
        
        ];
        var infowindow = new google.maps.InfoWindow();

        for ( i = 0; i < locations.length; i++) { 

          marker = new google.maps.Marker(  { 
            //icon      : markericon,
            position  : new google.maps.LatLng(locations[i].latlon[0], locations[i].latlon[1]),
            map       : map,
            sede      : locations[i].sede,
            indirizzo : locations[i].indirizzo,
            telefono  : locations[i].telefono,
            email     : locations[i].email,

          }  );

          myMarkers.push(marker);
          bounds.extend( marker.getPosition() );
          google.maps.event.addListener(marker, 'click', function()  { 
            var html = '';
            html += '<div class="marker-content">';
            if( this.sede != '')
            html += '  <span class="marker-title"><strong>' + this.sede + '</strong></span>';
            if( this.indirizzo != '')
            html += '  <br>' + this.indirizzo;
            if( this.telefono != '')
            html += '  <br>' + this.telefono;
            html += '</div>';

            infowindow.setContent(html);
            infowindow.open(map, this);
          } );

        } 

        map.fitBounds(bounds);
      } 

      function checkString(parseAddress, arrStringSearch ) { 
        for(var search = 0; search < arrStringSearch.length; search++) { 
          if(parseAddress.indexOf(arrStringSearch[search]) === -1)
            return false
        } return true;
      } 

      function setMapState(filterForm)  { 
        var countResult = 0;
        var bounds = new google.maps.LatLngBounds();
        var arrStringSearch = filterForm.toLowerCase().split(" ");

        for (var i = 0; i < myMarkers.length; i++)  { 
          var marker = myMarkers[i];
          var parseAddress = marker.indirizzo.toLowerCase();

          if( checkString(parseAddress, arrStringSearch) ) { 
            marker.setMap(map);
            bounds.extend( marker.getPosition() );
            countResult ++;
          } else { 
            marker.setMap(null);
          } 
        } 
        if(countResult > 0)
          map.fitBounds(bounds);
      } 

      var searchForm = document.getElementById("punti-vendita");
      searchForm.addEventListener("submit", function(event) { 
        event.preventDefault();
        setMapState(document.getElementById("punti-vendita-address").value);
      } );

    </script>
    {/capture}
    {append var=synPageScript value=$smarty.capture.googleMaps scope=parent}
  {/if}
  
  {include file="partial/_cta.tpl"}
  {include file="partial/_footer.tpl"}
  {include file="partial/_scripts.tpl"}
</body>
</html>
