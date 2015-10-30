/*
 * quick image preview plugin
 * Marco Pozzato 2012-08-18
 * inspired by http://cssglobe.com/easiest-tooltip-and-image-preview-using-jquery/
 */
(function($) {
  jQuery.fn.quickPreview = function() {
    var xOffset = 30, yOffset = 10;

    $(document).on({
      mouseenter: function (e) {
        var $this = $(this), caption = [], size = [], el;

        if ($this.data('ext'))
          caption.push( '<span class="label label-success">'+$this.data('ext')+'</span>' );
        if ($this.data('size'))
          caption.push( '<span class="label label-warning">'+$this.data('size')+'</span>' );
        if ($this.data('width'))
          size.push( $this.data('width') );
        if ($this.data('height'))
          size.push( $this.data('height') );
        if (size.length > 0)
          caption.push( '<span class="label label-info ">'+size.join('&times')+'</span>');

        el = [
          '<div id="preview" class="thumbnail">',
          '<img src="'+ this.href +'">',
          caption.join(' '),
          '</div>'
        ];

        $('body').append( el.join('') );
        $('#preview').css({
          'top': e.pageY+yOffset+'px',
          'left': e.pageX+xOffset+'px'
          }).fadeIn('fast');
      },
      mouseleave: function () {
        $('#preview').remove();
      },
      mousemove: function(e) {
        $('#preview').css({
          'top': e.pageY+yOffset+'px',
          'left': e.pageX+xOffset+'px'
        });
      }
    }, '.preview');
  };
})(jQuery);