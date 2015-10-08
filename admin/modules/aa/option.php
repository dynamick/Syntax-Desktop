<?php
  session_start();
  require_once ('../../includes/php/utility.php');
  lang( getSynUser(), $str);

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Options Frame</title>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:700,300,600,400&subset=latin,cyrillic">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/syntax.css" />

    <script type="text/javascript">
    var
      actionArray = Array(),
      toolArray = [ //array dei pulsanti [icona, actionId, label, separatore]
        // 0              1             2                       3
        ['plus-circle',   'newBtn',     '<?php echo $str["new"]?>',     'btn-success'],
        ['save',          'saveBtn',    '<?php echo $str["save"]?>',    'btn-primary'],
        ['times-circle',  'removeBtn',  '<?php echo $str["delete"]?>',  'btn-danger'],
        ['refresh',       'refreshBtn', '<?php echo $str["refresh"]?>', 'btn-info'],
        ['reply',         'backBtn',    '<?php echo $str["back"]?>',    'btn-info']
      ];

    function makeToolBar(where){//crea i pulsanti
      var str='';
      var index = 0;
      for( i = 0; i < toolArray.length; i++){
        var
          ico       = '<i class="fa fa-md fa-' + toolArray[i][0] +'"></i>',
          ls_class  = null,
          tt        = null;
        if (toolArray[i][2]){
          tt = ' data-toggle="tooltip" data-placement="bottom" title="'+ toolArray[i][2] +'"';
        }
        if (toolArray[i][3]){
          ls_class = toolArray[i][3];
        }
        var btn = '<button type="button" id="button_'+index+'" class="btn btn-default '+ls_class+'" onclick="eval(actionArray['+index+']); this.blur();"'+tt+'>'+ ico +'</button>'
        index ++;
        str += '<div class="btn-group" role="group">'+btn+'</div>';
      }
      document.getElementById(where).innerHTML = str;
    }
    </script>
  </head>
  <body class="right-bar">
    <div class="container-fluid">
      <div id="optionPane" class="text-center">
        <div class="box pagination">
          <div id="menu1" class="btn-group btn-group-justified" role="group"></div>
        </div>
      </div>
      <div id="multilang" class="panel-default"></div>
      <div id="custom" class="panel-default"></div>
    </div>

    <script type="text/javascript" src="../../assets/js/js_libs.php?scope=option&amp;v=<?php echo $synVersion ?>"></script>
    <script>
    $(document).ready(function(){
      makeToolBar('menu1');
      $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
      });

      // syncronous delete
      $('.btn-delete').click(function(e){
        e.preventDefault();
        bootbox.confirm( '<?php echo $str["sure_delete"] ?>', function(result) {
          if (result == true) {
            $(e.currentTarget).unbind( 'click' ).trigger( 'click' );
            //alert('ok');
          } else {
            return true;
          }
        });
      });
    });
    </script>
  </body>
</html>