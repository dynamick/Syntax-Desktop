/*
 * quick image preview plugin
 * Marco Pozzato 2012-08-18
 * inspired by http://cssglobe.com/easiest-tooltip-and-image-preview-using-jquery/
 */
(function($) {
  jQuery.fn.quickPreview = function() {
    var xOffset = 30;
    var yOffset = 10;

    $(this).hover(function(e){
      this.t = this.title;
      this.title = "";	
      var c = (this.t != "") ? "<br/>" + this.t : "";
      $("body").append("<div id='preview' class='thumbnail'><img src='"+ this.href +"' alt='Image preview' />"+ c +"</div>"); 
      $("#preview").css({
        'top': e.pageY+yOffset+'px', 
        'left': e.pageX+xOffset+'px'
        }).fadeIn('fast');
    }, function(){
      this.title = this.t;	
      $("#preview").remove();
    });
    $(this).mousemove(function(e){
      $("#preview").css({
        'top': e.pageY+yOffset+'px', 
        'left': e.pageX+xOffset+'px'
      });
    });
  };
})(jQuery);