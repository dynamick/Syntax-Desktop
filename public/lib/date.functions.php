<?php
/*==============================================================================

  `7MM"""Yb.      db   MMP""MM""YMM   db          `7MMF'      `7MMF'`7MM"""Yp,
    MM    `Yb.   ;MM:  P'   MM   `7  ;MM:           MM          MM    MM    Yb
    MM     `Mb  ,V^MM.      MM      ,V^MM.          MM          MM    MM    dP
    MM      MM ,M  `MM      MM     ,M  `MM          MM          MM    MM"""bg.
    MM     ,MP AbmmmqMA     MM     AbmmmqMA         MM      ,   MM    MM    `Y
    MM    ,dP'A'     VML    MM    A'     VML        MM     ,M   MM    MM    ,9
  .JMMmmmdP'.AMA.   .AMMA..JMML..AMA.   .AMMA.    .JMMmmmmMMM .JMML..JMMmmmd9

==============================================================================*/

// funzioni deprecate
function sql2date($data) {
  if (!strpos($data,":")) {
  /* ritorna la data da sql(aaaa-mm-gg) a (gg-mm-aaaa) */
  $ar=explode("-",$data);
  return $ar[2]."-".$ar[1]."-".$ar[0];
} else {
  /* ritorna la data da sql(aaaa-mm-gg hh:mm:ss) a (gg-mm-aaaa) */
  $dd=explode(" ",$data);
  $ar=explode("-",$dd[0]);
  return $ar[2]."-".$ar[1]."-".$ar[0];
  }
}

// ritorna anche l'orario
function sql2datetime($data) {
  if (!strpos($data,":")) {
    /* ritorna la data da sql(aaaa-mm-gg) a (gg-mm-aaaa) */
    $ar=explode("-",$data);
    return $ar[2]."-".$ar[1]."-".$ar[0];
  } else {
    /* ritorna la data da sql(aaaa-mm-gg hh:mm:ss) a (gg-mm-aaaa) */
    $dd=explode(" ",$data);
    $ar=explode("-",$dd[0]);
    return $ar[2]."-".$ar[1]."-".$ar[0]." [".$dd[1]."]";

  }
}
// fine funzioni deprecate



function synSetLocale(){
  # setta LC_TIME in base alla lingua selezionata da Syntax
  session_start();
  $lng = $_SESSION['synSiteLangInitial'];
  $double_string = strtolower($lng).'_'.strtoupper($lng);
  return setlocale(LC_TIME, $double_string.'@euro', $double_string, $lng, $double_string.'.UTF-8');
}


function sql2human($data, $format='%A %e %B %Y') {
  # documentazione sui format:
  # http://it.php.net/manual/en/function.strftime.php
  synSetLocale();
  if (!strpos($data,":")) {
    # converte la data da sql(aaaa-mm-gg) a $format
    $ar=explode("-",$data);
    return strftime($format, mktime(0, 0, 0, $ar[1], $ar[2], $ar[0]));

  } else {
    # converte la data da sql(aaaa-mm-gg hh:mm:ss) a $format
    $dd=explode(" ",$data);
    $ar=explode("-",$dd[0]);
    return strftime($format, mktime(0, 0, 0, $ar[1], $ar[2], $ar[0]));
  }
}


function interval2human($data1, $data2) {
  synSetLocale();
  # ritorna un intervallo fra due date in formato umano
  $arData1 = explode("-", $data1);
  $arData2 = explode("-", $data2);

  if ($data1==$data2) {
    # le date coincidono, ne ritorno una sola
    $data = strftime("%A %e %B %Y", mktime(0, 0, 0, $arData1[1], $arData1[2], $arData1[0]));
  } else {
    # le date NON coincidono
    if($arData1[1]==$arData2[1]){
      # le date sono nello stesso mese
      $giorno1 = strftime("%A %e", mktime(0, 0, 0, $arData1[1], $arData1[2], $arData1[0]));
      $giorno2 = strftime("%A %e", mktime(0, 0, 0, $arData2[1], $arData2[2], $arData2[0]));
    }else{
      # le date sono in mesi diversi
      $giorno1 = strftime("%A %e %B", mktime(0, 0, 0, $arData1[1], $arData1[2], $arData1[0]));
      $giorno2 = strftime("%A %e", mktime(0, 0, 0, $arData2[1], $arData2[2], $arData2[0]));
    }
    $giorno = $giorno1." - ".$giorno2;
    $mese = strftime("%B", mktime(0, 0, 0, $arData2[1], $arData2[2], $arData2[0]));
    $data = $giorno." ".$mese." ".$arData2[0];
  }
  return $data;
}
?>
