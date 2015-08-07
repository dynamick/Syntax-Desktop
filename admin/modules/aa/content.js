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
    //if (!backBtn) getButton(4).tooltip('hide');
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
