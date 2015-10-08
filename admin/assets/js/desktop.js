var
  $w = $(window),
  width = $w.width(),
  height = $w.height(),
  topbar = $('#desktopbar_top'),
  topBarHeight = topbar.height(),
  bottombar = $('#desktopbar_bottom'),
  bottomBarHeight = bottombar.outerHeight(),
  contentarea = null;

$(window).resize(function(){
  var dim = winSize();

  if ( contentarea !== null ) {
    $(contentarea).width( dim.w );
    $(contentarea).height( dim.h );
  }
});

function winSize(){
  var
    gutter = topBarHeight + bottomBarHeight,
    width = $w.width(),
    height = $w.outerHeight() - gutter;
  return {w: width, h:height};
}

function createWindow(titolo,file) {
  var dim = winSize();
  if ( contentarea !== null )
    removeWindow();
  contentarea = createElement( file, dim.w, dim.h );

  if ( $('#closeBtn').length < 1 )
    setWindowButtons();
}
function removeWindow() {
  $('#closeBtn').hide();
  $(contentarea).fadeOut( 350, function(){
    $(this).remove();
  });
}

function createElement( file, width, height ){
  var $el = $(document.createElement('iframe'));
  $el
    .css({
      display:'block',
      position:'absolute',
      left:0,
      top: topBarHeight,
      right:0,
      bottom: bottomBarHeight,
      width: width,
      height: height,
      backgroundColor: '#fff',
      border: 0
    })
    .attr('src', file)
    .hide()
    .appendTo('body')
    .fadeIn();

  return $el;
}

function setWindowButtons() {
  var $el = $('<div/>', {
      'id': 'closeBtn'
    }).css({
      position: 'absolute',
      top: 58,
      right: 15,
      zIndex: 100
    }).appendTo('body');
  var $btn = $('<button/>', {
      'class': 'btn btn-default btn-xs',
      'type': 'button',
      'html': '<i class="fa fa-times"></i>'
    }).on('click', function(){
      removeWindow();
      $el.remove();
    }).appendTo( $el );
}
