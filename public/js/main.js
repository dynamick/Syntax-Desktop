$(document).ready(function(){
  $('.colorbox').colorbox({
    previous : '&larr;',
    next     : '&rarr;',
    close    : '&times;',
    current  : '{current}/{total}'
  });
  
  $('.synform input, .synform textarea, .synform select').focus(function(){
    $(this).parents('div').addClass('focused');
  }).blur(function(){
    $(this).parents('div').removeClass('focused');
  });

});