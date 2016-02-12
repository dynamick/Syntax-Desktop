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
  $('.multi-select').each(function(){
    // different initialization for each element
    var
      $this = $(this),
      optionName = $this.data('option-name');
    $this.multiselect({
      enableClickableOptGroups: true,
      disableIfEmpty: true,
      selectedClass: 'multiselect-selected',
      checkboxName: optionName,
      includeSelectAllOption: true,
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      maxHeight: 200
    });
  });

  // init icon-picker
  $('.icp').iconpicker();

  // address picker
  if ($('.map-widget').length > 0) {
    var apikey = null;
    $.when( loadGoogleMaps( 3, apikey, 'it', ['places'] ) ).then( function() {
      synAddressPicker.init();
    });
  }

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

  //enableable input
  jQuery.fn.extend({
    triggerify: function(){
      var $this = $(this), $input = $this.find('input[type="text"]'), $trigger = $this.find('.trigger-input');
      console.log('triggerified');
      $trigger.change(function(){
        var $this = $(this), checked = $this.is(':checked');
        console.log('clicked');
        $input.prop('readonly', !checked ).focus();
      });
      $input.focusout(function(){
        var $this = $(this);
        if ($this.val() == '')
          $this.val( $this.data('oldvalue') );
        $this.prop('readonly', true);
        $trigger.prop('checked', false);
      });
    }
  });
  $('.triggerable-group').triggerify();
});
