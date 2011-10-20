<?
global $JSLIB_INC;
if (!isset($JSLIB_INC)) {
$JSLIB_INC=1;
/************************************************************************************************/
/*  JJJJJJJ  SSSS   L      I BBBBB                                                              */
/*     J    S    S  L      I B    B                                                             */
/*     J    S       L      I B    B                                                             */
/*     J     SSSS   L      I BBBBB                                                              */
/*     J         S  L      I B    B                                                             */
/*     J    S    S  L      I B    B                                                             */
/*  JJJ      SSSS   LLLLLL I BBBBB                                                              */
/*                                                                                              */
/************************************************************************************************/
/*
DOCHEAD
javascript library

<pre>
datalib.inc ver 1.01 29-9-2001 -- part of MyLib PhP project (a support php library for db and other web-development needs).
Copyright (C) 2001 Dimitri Giardina

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

You can email me at dimitri.giardina@tiscalinet.it or siddartha@sourceforge.net
</pre>

<pre>
TODO:
</pre>

/DOCHEAD
*/

	function js_declarescript($script) {
		/* inserisce un codice javascript */
	?><? echo $script;?><?
	}

	function js_declare() {
		/* inizia una dichiarazione jscript */
	?><script><?
	}

	function js_declare_c() {
		/* chiude una dichiarazione jscript */
	?></script><?
	}

	function js_declare_popup() {
		/* dichiara la funzione di popup */
		$text="function MM_openBrWindow(theURL,winName,features) { //v2.0\n";
		$text.="\twindow.open(theURL,winName,features);\n";
		$text.="}\n";
		js_declarescript($text);
	}
	
	function js_launchpopup($url,$windowName,$features) {
		/* lancia un popup */
		$ret="MM_openBrWindow('$url','$windowName','$features');";		
		return $ret;
	}

	function js_makeWinFeatures($left="",$top="",$width="",$height="",$resizable="",$scrollable="") {
		/* crea le features di una finestra */
		if ($left!="") { $feats="left=$left"; }
		if ($top!="") { if ($feats!="") $feats.=", "; $feats.="top=$top"; }
		if ($width!="") { if ($feats!="") $feats.=", "; $feats.="width=$width"; }
		if ($height!="") { if ($feats!="") $feats.=", "; $feats.="height=$height"; }
		if ($resizable!="") { if ($feats!="") $feats.=", "; $feats.="resizable=$resizable"; }
		if ($scrollable!="") { if ($feats!="") $feats.=", "; $feats.="scrollbars=$scrollable"; }
		return $feats;
	}
	function js_alert($msg) {
		/* lancia un messaggio in javascript */
	?><script>alert("<? echo $msg;?>");</script><?
	}
	function js_location($location) {
		/* riposiziona la pagina */
	?><script>if (!window.top.synDebug) window.location="<? echo $location;?>";</script><?
	}
}
?>
