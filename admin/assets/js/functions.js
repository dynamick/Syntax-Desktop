var options_frame = window.parent.frames['option'];

function action(txtBtn, act) {
  var actions = options_frame.actionArray;
  switch(txtBtn) {
    case 'newBtn'     : actions[0] = act; break;
    case 'saveBtn'    : actions[1] = act; break;
    case 'removeBtn'  : actions[2] = act; break;
    case 'refreshBtn' : actions[3] = act; break;
    case 'backBtn'    : actions[4] = act; break;
  }
}

//init toolbar
function initToolbar (newBtn, saveBtn, removeBtn, refreshBtn, homeBtn, backBtn) {
  var btns = [newBtn, saveBtn, removeBtn, refreshBtn, backBtn];
  for ( i in btns) {
    $( '#button_' + i, options_frame.document ).prop( 'disabled', !btns[i]);
  }
}

function addBox(id, txt) {
  if (id == null)
    id = 'custom';
  var
    $parent = $( '#optionPane', options_frame.document ),
    $el = $( '#'+id, options_frame.document );
  if ($el == null) {
    $el = $( '<div id="' + id + '"/>' );
    $parent.append( $el );
  }
  if ( txt )
    $el.addClass('panel');
  else
    $el.removeClass('panel');
  $el.html( txt );
}


$.notifyDefaults({
  offset: {
    x: 15,
    y: 20
  },
  animate: {
    exit: 'animated lightSpeedOut'
  }
});


function sendNotify( obj ) {
  if ( obj instanceof Array) {
    for ( o in obj)
      sendNotify( obj[o] );
  } else {
    var icon, type, delay = 5000;
    switch (obj.type) {
      case 1:
      case 4:
        icon = 'fa fa-exclamation-triangle';
        type = 'danger';
        delay = 0;
        break;
      case 2:
        icon = 'fa fa-exclamation-circle';
        type = 'warning';
        break;
      case 8:
        icon = 'fa fa-info-circle';
        type = 'info';
        break;
      default:
        icon = 'fa fa-check-circle';
        type = 'success';
        break;
    }
    $.notify(
      { icon: icon, message: obj.message },
      { type: type, delay:delay }
    );
  }
}
