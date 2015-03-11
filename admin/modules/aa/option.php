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
    //<![CDATA[
    var actionArray = Array();

    //array dei pulsanti [icona, actionId, label, separatore]
    var toolArray = [
      // 0              1             2                       3
      ['plus-circle',   'newBtn',     '<?=$str["new"]?>',     'btn-success'],
      ['save',          'saveBtn',    '<?=$str["save"]?>',    'btn-primary'],
      ['times-circle',  'removeBtn',  '<?=$str["delete"]?>',  'btn-danger'],
      ['refresh',       'refreshBtn', '<?=$str["refresh"]?>', 'btn-info'],
      ['reply',         'backBtn',    '<?=$str["back"]?>',    'btn-info']
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
        <nav>
          <ul class="pagination">
            <li class="disabled"><a id="first" aria-label="<?= $str['first'] ?>"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>
            <li class="disabled"><a id="prev" aria-label="<?= $str['prev'] ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
            <li class="disabled"><a id="next" aria-label="<?= $str['next'] ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
            <li class="disabled"><a id="last" aria-label="<?= $str['last'] ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>
          </ul>
        </nav>
        <ul class="pagination pagination-sm" id="pages"></ul>
        <p id="status"></p>
      </div>

        <div id="custom"></div>

        <div id="multilang" class="panel panel-default"></div>

       
        
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Filtra risultati</h3>
          </div>
          <div class="panel-body">
            <form action="content.php" target="content" method="post" id="synSearch" class="form-horizontal">
              <div class="form-group">
                <label for="colsearch" accesskey="c" class="col-sm-2 control-label"><?=$str["column"]?> [c]</label>
                <div class="col-sm-10">
                  <select name="field" class="form-control input-sm" id="colsearch" tabindex="1"></select>
                </div>
              </div>
              <div class="form-group">
                <label for="type" accesskey="b" class="col-sm-2 control-label"><?=$str["searchby"]?> [b]</label>
                <div class="col-sm-10">
                  <select name="type" id="type" tabindex="2" class="form-control input-sm">
                    <option value="like"><?=$str["like"]?></option>
                    <option value="="><?=$str["equal"]?></option>
                    <option value=">"><?=$str["gt"]?></option>
                    <option value="<"><?=$str["lt"]?></option>
                    <option value="acceso">checked</option>
                    <option value="spento">not checked</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="keyword" accesskey="t" class="col-sm-2 control-label"><?=$str["searchtext"]?> [t]</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control input-sm" name="keyword" id="keyword" size="10" tabindex="3" />
                </div>
              </div>
              <input type="hidden" name="aa_search" value="1" />
              <div class="btn-group btn-group-justified">
                <div class="btn-group">
                  <button type="submit" class="btn btn-default btn-sm">
                    <i class="fa fa-search"></i><br>
                    <?= $str['search'] ?>
                  </button>
                </div>
                <div class="btn-group">
                  <button type="reset" class="btn btn-default btn-sm" 
                    onclick="window.parent.content.document.location.href='content.php?aa_search_clean=1'">
                    <i class="fa fa-times-circle"></i><br>
                    <?= $str['cancel'] ?>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        
      </div>
    </div>

    <script src="../../assets/js/jquery.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    <script>
    $(document).ready(function(){
      makeToolBar('menu1');
      $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
      })
    });
    </script>    
  </body>
</html>