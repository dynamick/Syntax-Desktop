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
      optionName = $this.data('option-name'),
      last = false;
    if ( $this.parents('.form-group').is('div:last-of-type') ) {
      last = true;
    }
    $this.multiselect({
      enableClickableOptGroups: true,
      disableIfEmpty: true,
      selectedClass: 'multiselect-selected',
      checkboxName: function(option) {
        return optionName;
      },
      includeSelectAllOption: true,
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      maxHeight: 200
    });
    if (last) {
      $this.next('.btn-group').addClass('dropup');
    }
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
      theme: 'fa'
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
      $trigger.change(function(){
        var $this = $(this), checked = $this.is(':checked');
        $input.prop('readonly', !checked ).focus();
      });
      $input.focusout(function(){
        var $this = $(this);
        if ($this.val() == '')
          $this.val( $this.data('oldvalue') );
        $this.prop('readonly', true);
        $trigger.prop('checked', false);
      }).alphanumeric({
        nocaps: true,
        allow: '-'
      });
    }
  });
  $('.triggerable-group').triggerify();

  var $synpicture = $('.synpicture'); 
  if ($synpicture.length > 0) {
    $synpicture.each( function(){
      var _this = $(this), _input = _this.find('input[type="text"]'), 
        _preview = _this.find('.preview'), _clean = _this.find('.btn-clean'), 
        _trigger = _this.find('.btn-browse'), _modal = _this.find('.modal'), 
        tmb_url = _this.data('tmb-url'), kc_url = _this.data('kc-url');
      _clean.click(function(){
        _input.val('');
        _preview.empty();
      });
      _trigger.click(function(){
        window.KCFinder = {
          callBack: function(url) {
            window.KCFinder = null;
            _modal.modal('hide');
            _input.val( url );
            var src = tmb_url.replace('%s', url);
            _preview.html('<img src="' + src +'" width="250" height="250" class="thumbnail" alt="' + url + '">');
          }
        };
        _modal.removeData('bs.modal');
        _modal.find('.modal-body').html( '<iframe name="kcfinder_iframe" src="' + kc_url + '"' +
          ' frameborder="0" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" />' );
        _modal.modal('show');
      });
    });
  }

  var $syndocument = $('.syndocument'); 
  if ($syndocument.length > 0) {
    $syndocument.each( function(){
      var _this = $(this), _input = _this.find('input[type="text"]'), 
        _trigger = _this.find('.btn-browse'), _modal = _this.find('.modal'),
        _clean = _this.find('.btn-clean'), _download = _this.find('.btn-download'),
        kc_url = _this.data('kc-url'), 
        _update_function = function(url) {
          if ( url == '' ) 
            _download.hide();
          else
            _download.show();
          _download.attr('href', url);
        };
      _clean.click(function(){
        _input.val('');
        _update_function('');
      });
      _trigger.click(function(){
        window.KCFinder = {
          callBack: function(url) {
            window.KCFinder = null;
            _modal.modal('hide');
            _input.val( url );
            _update_function(url);
          }
        };
        _modal.removeData('bs.modal');
        _modal.find('.modal-body').html( '<iframe name="kcfinder_iframe" src="' + kc_url + '"' +
          ' frameborder="0" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" />' );
        _modal.modal('show');
      });
      _update_function(_input.val());
    });
  }  
});
