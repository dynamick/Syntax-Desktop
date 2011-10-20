<div style="position: absolute; top: 150px;">
<table id="iconTable" onselectstart="return false">
<tr><td name="win_1" align="center" ondblclick="openWindow(this.name)" type="icon" onclick="iconActive(this)" class="inactiveIcon">
 <img src="./images/bacheca.gif" width="32" height="32" border=0><br>Bacheca</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td name="win_2" align="center" ondblclick="openWindow(this.name)" type="icon" onclick="iconActive(this)" class="inactiveIcon">
 <img src="./images/stats.gif" width="32" height="32" border=0><br>Statistiche</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td name="win_3" align="center" ondblclick="openWindow(this.name)" type="icon" onclick="iconActive(this)" class="inactiveIcon">
 <img src="./images/recent.gif" width="32" height="32" border=0><br>Credits</td></tr>
</table>
</div>

<div id="win_1" class="window" type="url"></div>
<div id="win_2" class="window" type="txt"></div>
<div id="win_3" class="window" type="url">modules/credits/</div>

<script>
makeWindow(100,100,300,500)
makeWindow(200,200,300,450)
makeWindow(50,300,400,350)
//openWindow('win_1')
</script>
<!--<a onclick="minimizeWindows();"><h1>Minimize</h1></a>-->

