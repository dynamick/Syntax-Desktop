$(function() {
  // init checkbox switches
  $('.syn-check').bootstrapSwitch();

  // init date/time picker
  $('.date').datetimepicker({
    locale: syntax.service.lang,
    icons: {
      time: 'fa fa-clock-o',
      date: 'fa fa-calendar',
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down',
      previous: 'fa fa-chevron-left',
      next: 'fa fa-chevron-right',
      today: 'fa fa-crosshairs',
      clear: 'fa fa-trash'
    }
  });

  // init multi-select
  $('.multi-select').multiselect({
    enableClickableOptGroups: true,
    disableIfEmpty: true,
    selectedClass: 'multiselect-selected',
    includeSelectAllOption: true
  });

  // init icon-picker
  $('.icp').iconpicker();

  synAddressPicker = function(){
    var
      $elem = $('#address-picker'),
      $data = $('#address-data'),
      $lat = $('#lat'),
      $lng = $('#lng'),
      pickerOptions = {
        map: {
          id: '#map',
          center: getLanLng,
          reverseGeocoding: true,
          displayMarker: true
        }, marker: {
          draggable: true,
          visible: true
        },
        zoomForLocation: 18,
        draggable: true,
        reverseGeocoding: true,
        autocompleteService: {
          types: [ 'geocode', 'establishment']
        }
      },
      getLanLng = function(){
        var lat = 45.403757,  lng = 10.978879;
        if ( $data.val() != '' ) {
          var coords = $data.val().split('|');
          lat = coords[1];
          lng = coords[2];
        }
        //console.info(lat, lng);
        return new google.maps.LatLng( lat, lng );
      },
      apiLoad = function() {
        // remotely get googlemaps api
        $.getScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=synAddressPicker')
          .fail(function (jqxhr) {
            console.error('Could not load Google Maps: ' + jqxhr);
          });
      },
      initPicker = function() {
        if ( typeof( AddressPicker ) === 'function' ) {
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
      };
    if ($elem.length > 0){
      // element #address-picker exists, proceed
      if (window.google && google.maps) {
        // google maps available, launch picker
        initPicker();
      } else {
        // load google maps, then re-launch
        apiLoad();
      }
    }
  }
  synAddressPicker();


  // init address picker
  /*
  if ($('#address-picker').length > 0){
    var addressPicker = new AddressPicker({
       map: {
        id: '#map',
        center: getLanLng(),
        zoom: 16
       }
       , marker: {
        draggable: true,
        visible: true
       }
       , autocompleteService: {
        types: ['geocode', 'establishment']
       }
    });
    $('#address-picker').typeahead(null, {
      displayKey: 'description',
      source: addressPicker.ttAdapter()
    });
    addressPicker.bindDefaultTypeaheadEvent($('#address-picker'))
    $(addressPicker).on('addresspicker:selected', function (event, result) {
      $('#address-picker').val(result.placeResult.formatted_address);
      $('#address-data').val($("#address-picker").val()+"|"+result.lat()+"|"+result.lng());
      $('#lat').html(result.lat());
      $('#lng').html(result.lng());
    });
  }*/

  // init file input
  $('.file-input-control').each( function(){
    var
      $this = $(this),
      name = $this.data('name'),
      initial = preview[ name ];
    $this.fileinput({
      showUpload: false,
      previewFileType: 'any',
      initialPreview: [ initial['src'] ],
      initialCaption: initial['label'],
      browseIcon: '<i class="fa fa-folder-open-o"></i> ',
      removeIcon: '<i class="fa fa-trash"></i> ',
      layoutTemplates: {
        icon: '<span class="fa fa-file kv-caption-icon"></span> '
      }
    }).on( 'fileloaded', function() {
      $this.attr('name', name);
    }).on('filecleared', function() {
      $this.attr('name', name);
    });
  });

  // init tooltip
  $('[data-toggle="tooltip"]').tooltip({
    container: 'body'
  })

  // syncronous delete
  $('.btn-delete').click(function(e){
    e.preventDefault();
    bootbox.confirm( syntax.str.confirm, function(result) {
      if (result == true) {
        $(e.currentTarget).unbind( 'click' ).trigger( 'click' );
      } else {
        return true;
      }
    });
  });

  // limited textarea
  $('.input-limited').maxlength({
    alwaysShow: true,
    twoCharLinebreak: true,
    validate: false
  });
});