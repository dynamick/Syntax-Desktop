<?
function valorizza ($testo, $search, $replace) {
  if (substr($search, -11)=="_previewImg") {
    $imgField=substr($search,0,strlen($search)-11);
    $testo=valorizzaImg($testo,$imgField,$replace);
  } else {
    $search="preview=\"$search\"";
    $pos=strpos($testo,$search);
    if ($pos !== false) {
      $tag_inizio=strrpos(substr($testo,0,$pos),"<");
      $tag_fine=strpos($testo," ",$tag_inizio);
      $tag=substr($testo, $tag_inizio+1, $tag_fine-$tag_inizio-1);

      $pos_inizio=strpos($testo,">",$pos)+1;
      $pos_fine=strpos($testo,"</$tag",$pos);
      $testo=substr_replace ($testo, stripslashes($replace), $pos_inizio , $pos_fine-$pos_inizio);
    }
  }
  return $testo;
}

function valorizzaImg ($testo, $search, $replace) {
  @$arr = getimagesize($replace);
  if ($arr===false) $replace="images/blank.gif";
  
  $search="preview=\"$search\"";
  $pos=strpos($testo,$search);
  if ($pos !== false) {
    $tag_inizio=strrpos(substr($testo,0,$pos),"<");
    $tag_fine=strpos($testo," ",$tag_inizio);
    $tag=substr($testo, $tag_inizio+1, $tag_fine-$tag_inizio-1);
    if (strtolower($tag)=="img") {
      $pos_inizio=strpos($testo,"src=\"",$tag_inizio)+5;
      $pos_fine=strpos($testo,"\"",$pos_inizio);
      $testo=substr_replace ($testo, $replace, $pos_inizio , $pos_fine-$pos_inizio);
    }
  }
  return $testo;
}


  $target=$_GET["synTarget"];
  ob_start();
    //include("../../../$target"); //vulnerability to be fixed: http://www.securityfocus.com/bid/33601/exploit
  $contents=ob_get_contents();
  ob_end_clean();
  
  foreach($_POST as $key=>$value) {
    $contents=valorizza($contents,$key,$value);
  }

  foreach($_FILES as $key=>$value) {
    //$contents=valorizzaImg($contents,$key,$value["tmp_name"]);
  }
  echo $contents;

?>