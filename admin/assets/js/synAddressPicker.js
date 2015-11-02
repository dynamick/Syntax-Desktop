var synAddressPicker = (function(){
  var
    $elem = $('#address-picker'),
    $data = $('#address-data'),
    $lat = $('#lat'),
    $lng = $('#lng'),
    getLatLng = function(){
      var lat = 45.403757,  lng = 10.978879;
      if ( $data.val() != '' ) {
        var coords = $data.val().split('|');
        lat = coords[1];
        lng = coords[2];
      }
      if (window.google && google.maps) {
        return new google.maps.LatLng( lat, lng );
      } else {
        console.log( 'not available yet...' );
      }
    },
    pickerOptions = {
      map: {
        id: '#map',
        center: null, // google APIs may not be available yet
        reverseGeocoding: true,
        displayMarker: true,
        zoom: 16
      }, marker: {
        draggable: true,
        visible: true
      },
      zoomForLocation: 12,
      draggable: true,
      reverseGeocoding: true,
      autocompleteService: {
        types: [ 'geocode', 'establishment']
      }
    },
    initMap = function(){
      if ( typeof( AddressPicker ) === 'function' ) {
        pickerOptions.map.center = getLatLng(); // now google APIs should be available
        var addressPicker = new AddressPicker( pickerOptions );

        $elem.typeahead(null, {
          displayKey: 'description',
          source: addressPicker.ttAdapter()
        });
        addressPicker.bindDefaultTypeaheadEvent( $elem );
        $(addressPicker).on( 'addresspicker:selected', function (event, result) {
          $elem.val( result.placeResult.formatted_address );
          $data.val( $elem.val() + '|' + result.lat() + '|' + result.lng() );
          $lat.html( result.lat() );
          $lng.html( result.lng() );
        });
      } else {
        console.error('AddressPicker not available!');
      }
    },
    start = function(){
      if ($elem.length == 0) {
        console.warn('#address-picker not found');
      } else {
        if (window.google && google.maps) {
          initMap();
        } else {
          console.error('google maps not available!');
        }
      }
    };
  return {
    start: start,
    init: initMap,
    getLatLng: getLatLng,
    options: pickerOptions
  };
})();