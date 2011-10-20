<?php
  session_start();
  require_once ("../../includes/php/utility.php");
  lang(getSynUser(),$str);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
  <head profile="http://www.w3.org/2005/10/profile">
    <title> </title>
    <meta http-equiv="content-type"         content="text/html; charset=ISO-8859-1" />
    <meta http-equiv="content-language"     content="it" />
    <meta http-equiv="imagetoolbar"         content="no" />
    <meta http-equiv="msthemecompatible"    content="yes" />
    <meta http-equiv="content-script-type"  content="text/javascript" />
    <meta name="robots"                     content="noindex, nofollow" />
    <script type="text/javascript">
    //<![CDATA[
    var actionArray = Array();

    //array dei pulsanti [icona, actionId, label, separatore]
    //['img/tool_frame.png',     'switchBtn',  '<?=$str["toggle"]?>',  'frame'],
    var toolArray = [
      ['img/tool_add.png',       'newBtn',     '<?=$str["new"]?>',     'add'],
      ['img/tool_save.png',      'saveBtn',    '<?=$str["save"]?>',    'save'],
      ['img/tool_cancel.png',    'removeBtn',  '<?=$str["delete"]?>',  'del'],
      ['img/tool_separator.png', '',           '',                     false],
      ['img/tool_refresh.png',   'refreshBtn', '<?=$str["refresh"]?>', 'f5'],
      ['img/tool_home.png',      'homeBtn',    '<?=$str["home"]?>',    'home'],
      ['img/tool_undo.png',      'backBtn',    '<?=$str["back"]?>',    'back']
    ];

    function makeToolBar(where){//crea i pulsanti
      var str='';
      var index = 0;
      for(i=0; i<toolArray.length; i++){
        var img='';
        var ls_class='';
        img = '<img src="'+toolArray[i][0]+'" alt="'+toolArray[i][2]+'" />';
        if(toolArray[i][2]){
          var img = "<a href=\"javascript:void(0)\" onclick=\"eval(actionArray["+index+"]); this.blur();\" title=\""+toolArray[i][2]+"\" id=\"button_"+index+"\">"+img+"</a>";
          index++;
        }
        if(toolArray[i][3]){
          var ls_class = ' class=\"'+toolArray[i][3]+'\"';
        }
        str += "  <li"+ls_class+">"+img+"</li>\n";
      }
      document.getElementById(where).innerHTML = str;
    }

    window.onload=new Function("makeToolBar('menu1')");
    //]]>
    </script>

    <style type="text/css">
    html, body {margin:0; padding:0; height:100%; width:230px;}
    body {background: #f5f5f5 url('img/sfondo_right.png') repeat-y 0 0; font:11px Arial,Helvetica,sans-serif; color:#333; text-align: center;}
    a {text-decoration:none;}
    a:focus {outline: none;}
    p {margin:0; padding:0}

    h2 {margin:0; padding:0 4px; line-height:28px; font-size:12px; background:#CDCDCD url('img/tool_sfondo.png') repeat-x 0 50%; border:1px outset #f0f0f0; text-align:left;}
    h2 span {display:block; padding-left:16px; background: url('img/tool_zigrinatura.png') no-repeat 0 50%;}
    h4 {margin:0 0 4px; font-size:11px; font-weight:normal;}
    div#wrapper {position:relative; min-height:100%; _height:100%; background:url('img/logo.png') no-repeat 50% bottom;}
    div#optionPane {padding-bottom:50px;}
    div.box {margin:5px; padding:10px 5px; background-color:#f7f7f7; border-width:1px; border-style:solid; border-top-color:#fff; border-right-color:#ccc; border-bottom-color:#ccc; border-left-color:#fff; text-align:center;}
    div#logo {position:relative; margin-top:-40px; text-align:center;}
    div#logo img {display:block; margin:0 65px;}

    ul {margin:0; padding:0; list-style-type:none;}
    /* menu */
    ul#menu1 {padding:0; width:100%; overflow:hidden;}
    ul#menu1 li {float:left; padding:0 2px; background-repeat:no-repeat; background-position:6px 4px; line-height:28px;}
    ul#menu1 li.add {background-image:url('img/tool_add_off.png');}
    ul#menu1 li.save {background-image:url('img/tool_save_off.png');}
    ul#menu1 li.del {background-image:url('img/tool_cancel_off.png');}
    /*ul#menu1 li.frame {background-image:url('img/tool_frame_off.png');}*/
    ul#menu1 li.f5 {background-image:url('img/tool_refresh_off.png');}
    ul#menu1 li.home {background-image:url('img/tool_home_off.png');}
    ul#menu1 li.back {background-image:url('img/tool_undo_off.png');}
    ul#menu1 li a {display:block; padding:3px; border:1px solid #f5f5f5;}
    ul#menu1 li a:hover {background:#fff; border-style:outset;}
    ul#menu1 li a:active {padding-top:4px; padding-bottom:2px; background:#f0f0f0; border-style:inset;}
    ul#menu1 li img {display:block; border:none;}
    /* paginazione */
    ul.paginazione {margin:0 0 8px; padding:4px 0; line-height:22px;}
    ul.paginazione li {position:relative; display:inline-block; margin:0 4px; padding:0 12px; font-weight:bold;}
    ul.paginazione li a {position:absolute; left:0; top:-4px; width:22px; height:22px; text-indent:-9999px; overflow:hidden; background-image:url('img/tool_pages.png');}
    ul.paginazione li a#first {background-position:0 0;}
    ul.paginazione li a#first:hover {background-position:0 -22px;}
    ul.paginazione li a#first.disabled {background-position:0 -44px;}
    ul.paginazione li a#prev {background-position:-22px 0;}
    ul.paginazione li a#prev:hover {background-position:-22px -22px;}
    ul.paginazione li a#prev.disabled {background-position:-22px -44px;}
    ul.paginazione li a#next {background-position:-44px 0;}
    ul.paginazione li a#next:hover {background-position:-44px -22px;}
    ul.paginazione li a#next.disabled {background-position:-44px -44px;}
    ul.paginazione li a#last {background-position:-66px 0;}
    ul.paginazione li a#last:hover {background-position:-66px -22px;}
    ul.paginazione li a#last.disabled {background-position:-66px -44px;}
    ul.pages {margin:0; padding:4px 0; width:100%; text-align:center;}
    ul.pages li {display:inline;}
    ul.pages li a {padding:0 2px; color:#428BFF;}
    ul.pages li a:hover {color:#73A2FE;}
    /* bandiere */
    ul.flags {text-align:center;}
    ul.flags li {display:inline; padding:0 4px;}
    ul.flags li img {padding:2px; border:1px solid #fafafa;}
    ul.flags li a:hover img {border-color:orange;}
    ul.flags li img.currentFlag {padding:2px; border-color:#428BFF;}

    form {margin:0; padding:0 4px;}
    form label {float:left; width:90px;}
    form p {margin-bottom:8px; text-align:left; line-height:22px;}
    form p.buttons {text-align:center;}
    form select {width:100px;}
    form input.text {padding:1px 0; width:96px;  font-size:11px;}
    form button {margin:0 3px; font-size:11px;}
    form button img {vertical-align:absmiddle;}
    </style>
<!--[if lt IE 8]>
    <style type="text/css">
    ul#menu1 li,
    form label,
    ul.paginazione li {display:inline;}
    div#wrapper {height:100%;}
    ul#menu1,
    ul.paginazione li a,
    div#wrapper,
    div#logo,
    ul.paginazione li {zoom:1;}
    div#logo {position:relative; zoom:1;}
    ul.paginazione {height:22px;}
    ul.paginazione li a {top:10px;}
    </style>
<![endif]-->
  </head>
  <body>
    <div id="wrapper">
      <h2><span>Toolbar</span></h2>
      <div id="optionPane">
        <div class="box">
          <ul id="menu1"></ul>
        </div>
        <div class="box">
          <ul class="paginazione">
            <li><a class="disabled" id="first" title="<?=$str["first"]?>"><?=$str["first"]?></a></li>
            <li><a class="disabled" id="prev" title="<?=$str["prev"]?>"><?=$str["prev"]?></a></li>
            <li><a class="disabled" id="next" title="<?=$str["next"]?>"><?=$str["next"]?></a></li>
            <li><a class="disabled" id="last" title="<?=$str["last"]?>"><?=$str["last"]?></a></li>
          </ul>

          <ul class="pages" id="pages"></ul>

          <p id="status"></p>
        </div>

        <div class="box" id="custom"></div>

        <div class="box">
          <form action="content.php" target="content" method="post" id="synSearch">
              <p>
                <label for="colsearch" accesskey="c"><?=$str["column"]?> [c]</label>
                <select name="field" id="colsearch" tabindex="1"></select>
              </p>
              <p>
                <label for="type" accesskey="b"><?=$str["searchby"]?> [b]</label>
                <select name="type" id="type" tabindex="2">
                  <option value="like"><?=$str["like"]?></option>
                  <option value="="><?=$str["equal"]?></option>
                  <option value=">"><?=$str["gt"]?></option>
                  <option value="<"><?=$str["lt"]?></option>
                  <option value="acceso">checked</option>
                  <option value="spento">not checked</option>
                </select>
              </p>
              <p>
                <label for="keyword" accesskey="t"><?=$str["searchtext"]?> [t]</label>
                <input type="text" class="text" name="keyword" id="keyword" size="10" tabindex="3" />
              </p>
              <p class="buttons">
                <input type="hidden" name="aa_search" value="1" />
                <button type="submit">
                  <img src="img/tool_search.png" alt="filtra i risultati" /> <?=$str["search"]?>
                </button>
                <button type="reset" onclick="window.parent.content.document.location.href='content.php?aa_search_clean=1'">
                  <img src="img/tool_search_remove.png" alt="annulla filtro" /> <?=$str["cancel"]?>
                </button>
              </p>
          </form>
        </div>
      </div>
    </div>
    <!--div id="logo"><img src="img/logo.png" alt="Syntax Desktop" id="logo" /></div-->
  </body>
</html>
