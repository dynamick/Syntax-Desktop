var
  $w = $(window),
  width = $w.width(),
  height = $w.height(),
  topbar = $('#desktopbar_top'),
  topBarHeight = topbar.height(),
  bottombar = $('#desktopbar_bottom'),
  bottomBarHeight = bottombar.outerHeight(),
  contentarea = null,

  anim_options = {
    inClass: 'fade-in-up',
    outClass: 'fade-out-down',
    inDuration: 800,
    outDuration: 500,
    linkElement: '.animsition-link',
    loading: true,
    loadingParentElement: 'body',
    loadingClass: 'animsition-loading',
    loadingInner: '', // e.g '<img src="loading.svg" />'
    timeout: false,
    timeoutCountdown: 5000,
    onLoadEvent: true,
    browser: [ 'animation-duration', '-webkit-animation-duration'],
    overlay : false,
    overlayClass : 'animsition-overlay-slide',
    overlayParentElement : 'body',
    transition: function(url){ window.location.href = url; }
  };

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
    destroyWindow();
  contentarea = createElement( file, dim.w, dim.h );

  if ( $('#closeBtn').length < 1 )
    setWindowButtons();
}
function hideWindow() {
  $('#closeBtn').hide();
  $(contentarea).animsition('out', $(this), 'javascript:destroyWindow()' );
}
function destroyWindow() {
  $(contentarea).remove();
  contentarea = null;
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
      border: 0,
    })
    .addClass('animsition')
    .attr( 'src', file )
    .appendTo('body')
    .animsition( anim_options )
    .animsition('in');

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
      hideWindow();
      $el.remove();
    }).appendTo( $el );
}

