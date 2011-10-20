<?
die("Configure config.php in the config directory");
session_start();

///////////////////////////////////////functions//////////////////////////////

if (!function_exists("file_put_contents")) {
   function file_put_contents($filename,$content) {
       if(!$file = fopen($filename, "w+")) return false;
       if($file) {
           if(!fwrite($file,$content)) return false;
           fclose($file);
       }
       return true;
   }
}
function valorizza ($testo, $search, $replace) {
    $pos=strpos($testo,$search);
    if ($pos !== false) {
      $tag_inizio=strrpos(substr($testo,0,$pos),"<");
      $tag_fine=strpos($testo," ",$tag_inizio);
      $tag=substr($testo, $tag_inizio+1, $tag_fine-$tag_inizio-1);

      $pos_inizio=strpos($testo,"\"",$pos)+1;
      $pos_fine=strpos($testo,"\";",$pos);
      $testo=substr_replace ($testo, stripslashes($replace), $pos_inizio , $pos_fine-$pos_inizio);
    }
  return $testo;
}

function leggi ($testo, $search) {
    $pos=strpos($testo,$search);
    if ($pos !== false) {
      $tag_inizio=strrpos(substr($testo,0,$pos),"<");
      $tag_fine=strpos($testo," ",$tag_inizio);
      $tag=substr($testo, $tag_inizio+1, $tag_fine-$tag_inizio-1);

      $pos_inizio=strpos($testo,"\"",$pos)+1;
      $pos_fine=strpos($testo,"\";",$pos);
      $ret=substr ($testo, $pos_inizio, $pos_fine-$pos_inizio);
    }
  return $ret;
}
//////////////////////////////////end of functions//////////////////////////////

ob_start();
  readfile(dirname(realpath(__FILE__))."/../../config/cfg.php");
  $contents=ob_get_contents();
ob_end_clean();

$err=$_GET["err"];

if (isset($_POST["login"]) and isset($_POST["password"])) {
    $contents=valorizza($contents, "synDbUser", $_POST["login"]);
    $contents=valorizza($contents, "synDbPassword", $_POST["password"]);
    $contents=valorizza($contents, "synDbHost", $_POST["host"]);
    $contents=valorizza($contents, "synDbName", $_POST["dbname"]);

    $filename="../../config/cfg.php";
    file_put_contents($filename,$contents);

    if (!getSynUser()) {header("location: ../../"); die;}
    else $err="Configuration saved!";
} else {
  $login=leggi($contents, "synDbUser");
  $password=leggi($contents, "synDbPassword");
  $host=leggi($contents, "synDbHost");
  $dbname=leggi($contents, "synDbName");
}

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="login.css" />
</head>
<body style="overflow-y: hidden">
<div id="login">
  <fieldset>
    <form action="<?=$PHP_SELF?>" method="post">
      <table>
        <tr><td></td><td style="text-align: center; color: darkred" class="label"><strong>Database</strong></td></tr>
        <tr><td class="label">Host:</td><td><input type="text" name="host" tabindex="1" id="start" value="<?=$host?>"/></td></tr>
        <tr><td class="label">Username:</td><td><input type="text" name="login" tabindex="2" value="<?=$login?>"/></td></tr>
        <tr><td class="label">Password:</td><td><input type="password" name="password" tabindex="3" value="<?=$password?>"/></td></tr>
        <tr><td class="label">Db name:</td><td><input type="text" name="dbname" tabindex="4" value="<?=$dbname?>"/></td></tr>
      </table>
      <table>
        <tr><td></td><td style="text-align: center"><div><input type="image" src="images/login.gif" tabindex="5"/><?=$err;?></td></tr>
      </table>
    </form>
  </fieldset>
</div>
<script>document.getElementById("start").focus();</script>
</body>
</html>
