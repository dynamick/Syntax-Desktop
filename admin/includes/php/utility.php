<?php
require_once (dirname(__FILE__).'/../../config/cfg.php');

/******************************************************************************
***                                USER/GROUP FUNCTIONS
*******************************************************************************/

function username ($id) {
  global $db;
  $res=$db->Execute("select login from aa_users where id='$id'");
  list($login)=$res->FetchRow();
  return $login;
}

function groupname ($id) {
  global $debug, $db;
  $res=$db->Execute("SELECT aa_groups.name from aa_users,aa_groups where aa_users.id='$id' and aa_users.id_group=aa_groups.id");
  list($gruppo)=$res->FetchRow();
  return $gruppo;
}

/******************************************************************************
***                                  MENU FUNCTIONS
*******************************************************************************/

// get the service id from the group_service table
function extractService($group_service) {
  global $db;
  $qry="SELECT * FROM aa_group_services WHERE id=".$group_service;
  $res=$db->Execute($qry);
  $arr=$res->FetchRow();
  return $arr["service"];
}

/******************************************************************************
***                                  AUTH FUNCTIONS
*******************************************************************************/

function auth() {
  global $synAdminPath;
  if (isset($_SESSION['synUser'])) {
    return true;
  } else {
    die("<script type=\"text/javascript\">top.location.href='{$synAdminPath}';</script>");
  }
}

function getGroupTree($gid,$tree) {
  global $db;
  $qry="SELECT * FROM aa_groups WHERE id=$gid";

  $res=$db->Execute($qry);
  if ($arr=$res->FetchRow()) {
    $parentId=$arr["parent_id"];
    if ($parentId!="") $tree=getGroupTree($parentId,$tree);
    $tree[]=$gid;
  }
  return $tree;
}

function getGroupChild($gid,$tree) {
  global $db;
  $qry="SELECT * FROM aa_groups WHERE id=$gid";
  $res=$db->Execute($qry);
  $arr=$res->FetchRow();
  $name=$arr["name"];

  $qry="SELECT * FROM aa_groups WHERE parent_id=$gid";
  $res=$db->Execute($qry);
  if ($res->RecordCount()==0) $tree[$name]=$gid;
  while ($arr=$res->FetchRow()) {
    $id=$arr["id"];
    if ($id!="") $tree=getGroupChild($id,$tree);
    $tree[$name]=$gid;
  }
  return $tree;
}

function getSynUser() {
  if (isset($_SESSION["synUser"])) {
    $ret=$_SESSION["synUser"];
  } else
    $ret=false;

  return $ret;
}

function getUsersInGroup() {
  global $db;
  $num = array();
  foreach ($_SESSION["synGroupChild"] as $k=>$v) {
    $qry="SELECT * FROM aa_users WHERE id_group=$v";
    $res=$db->Execute($qry);
    $num[$v]=$res->RecordCount();
  }
  return $num;
}

// to be developed...
define("OFFSET", "17");
# Potrei farne una sola ma mi viene comodo che abbiano nomi diversi:
function encode($str) {
    $new='';
    for ($i=0; $i<strlen($str); $i++) {$new.=chr(ord($str[$i])+OFFSET);}
    return $new;
}
function decode($str) {
    $new='';
    for ($i=0; $i<strlen($str); $i++) {$new.=chr(ord($str[$i])-OFFSET);}
    return $new;
}

/******************************************************************************
***                                  LANG FUNCTIONS
*******************************************************************************/

function lang($id,&$str) {
  global $db;

  if(!isset($_SESSION))
    session_start();

  $file = $ret = null;

  $a = $db->MetaTables();
  if ($a!==false AND count($a)==0) $sigla="en";
  elseif (!isset($_SESSION['aa_CurrentLang']) || $_SESSION['aa_CurrentLang']=='') {
    if ($id!="") $lang=getUserLang();
    else {
      $res=$db->Execute("SELECT id FROM aa_lang");
      list($lang)=$res->FetchRow();
    }
    setLang($lang);
  }

  $qry="SELECT initial FROM aa_lang JOIN aa_users ON aa_users.lang = aa_lang.id WHERE aa_users.id='".$_SESSION["synUser"]."'";
  $res=$db->Execute($qry);
  list($sigla)=$res->FetchRow();
  $file=dirname(__FILE__)."/../../lang/$sigla/translation.php";

  if (file_exists($file)) require($file);
  else require(dirname(__FILE__)."/../../lang/en/translation.php");

  foreach($strJs as $original=>$translated) {
    $ret.="  str['$original']=\"$translated\";\n";
  }
  $ret = "\n  top.str=new Array();\n$ret";

  return $ret;

}

  //return the current user language
  function getUserLang() {
    global $db;
    if ($_SESSION["synCustomLang"]=="user") {
      $res=$db->Execute("select lang from aa_users where id='".$_SESSION["synUser"]."'");
      list($lang)=$res->FetchRow();
    } else $lang=$_SESSION["synCustomLang"];
    return $lang;
  }

  //return the current user language
  function getLang($initials=false) {
    global $db;
    $lang=getUserLang();
    if ($initials===false) $ret=$lang;
    else {
      $res=$db->Execute("SELECT initial FROM aa_lang WHERE id='".$lang."'");
      list($ret)=$res->FetchRow();
    }
    return $ret;
  }

  function getLangInfo($id, $data='lang') {
    global $db;
    $res = $db->Execute("SELECT `{$data}` FROM aa_lang WHERE id='{$id}'");
    list($ret) = $res->FetchRow();

    return $ret;
  }


  // set the current language
  function setLang( $id = 0 ) {
    global $db;

    if(!isset($_SESSION))
      session_start();

    $lang = intval($id);
    if ( $lang == 0 ) {
      $qry = "SELECT id FROM aa_lang WHERE `default` = 1 ORDER BY `order` LIMIT 0, 1 ";
      $res = $db->Execute( $qry );
      if ( $res->RecordCount() > 0 ) {
        $arr = $res->FetchRow();
        $lang = $arr['id'];
      } else {
        throw new Exception("Error default language not found");
      }
    }

    //get the current lang
    $qry = "SELECT initial FROM aa_lang WHERE id='{$lang}'";
    $res = $db->Execute($qry);
    if ($res->RecordCount()>0) {
      $arr = $res->FetchRow();
      $currlang = $arr[0];

      $_SESSION['aa_CurrentLang'] = $lang;
      $_SESSION['aa_CurrentLangInitial'] = $currlang;

      setlocale(LC_ALL, strtolower($currlang)."_".strtoupper($currlang));
      return true;

    } else {
      return false;
    }
  }


  function getLangProperties($lang){
    global $db;

    $qry = "SELECT * FROM aa_lang WHERE id='{$lang}'";
    $res = $db->Execute($qry);
    if($arr = $res->fetchRow()){
      return $arr;
    }
    return false;
  }


  //translate an element. If err==true display the error message
  function translate($id,$err=false) {
    global $db;
//echo "{$_SESSION['aa_CurrentLang']} - {$_SESSION['aa_CurrentLangInitial']}<hr>";
    if(!isset($_SESSION))
      session_start();

    //if ($this->multilang==1 and $id!="") {
      $qry="SELECT * FROM aa_translation WHERE id='".addslashes($id)."'";
      $res=$db->Execute($qry);
      if ($res->RecordCount()==0) {  //umm... the field is multilang but there isn't a row in the translation table...
        $ret=$id;
      } else {
        $arr=$res->FetchRow();
        //$ret=$arr[$_SESSION["synSiteLangInitial"]];
        $ret = $arr[$_SESSION['aa_CurrentLangInitial']];
        if ($ret=="" and $err===true) {
          foreach ($arr as $mylang=>$mytrans)
            if (!is_numeric($mylang) and $mylang!="id" and $mytrans!="")
              $alt .= "\n$mylang: ".substr(strip_tags($mytrans),0,10);
          $ret="<span style='color: gray' title=\"".htmlentities("Other Translations:".$alt)."\">[no translation]</span>";
        }
      }
    //} else $ret=$id;
    return $ret;
  }

  //translate an element for the desktop. If err==true display the error message
  function translateDesktop($id, $err=false) {
    global $db;

    if(!isset($_SESSION))
      session_start();

    //if ($this->multilang==1 and $id!="") {
      $qry="SELECT * FROM aa_translation WHERE id='".addslashes($id)."'";
      $res=$db->Execute($qry);
      if ($res->RecordCount()==0) {  //umm... the field is multilang but there isn't a row in the translation table...
        $ret=$id;
      } else {
        $arr = $res->FetchRow();
        $ret = $arr[getUserLang()];
        if ($ret=="" && $err===true) {
          foreach ($arr AS $mylang => $mytrans)
            if ( !is_numeric($mylang)
              && $mylang != 'id'
              && $mytrans != ''
              ) $alt .= "\n{$mylang}: ".substr(strip_tags($mytrans),0,10);

          $ret = "<span style='color: gray' title=\"".htmlentities("Other Translations:".$alt)."\">[no translation]</span>";
        }
      }
    //} else $ret=$id;
    return $ret;
  }

  //translate an element for the desktop. If err==true display the error message
  function translateSite_DEPRECATED($id,$err=false) {
    global $db;
      if (isset($_GET["synSiteLang"])) setLang($_GET["synSiteLang"]);
      if ($_SESSION["synSiteLang"]=="" or !isset($_SESSION["synSiteLang"])){
        $res=$db->Execute("SELECT id,initial FROM aa_lang ORDER BY id");
        if($res!=false){
          $arr=$res->FetchRow();
          $_SESSION["synSiteLang"]=$arr["id"];
          $_SESSION["synSiteLangInitial"]=$arr["initial"];
          setlocale(LC_ALL, strtolower($arr["initial"])."_".strtoupper($arr["initial"]));
        }
      }
    //if ($this->multilang==1 and $id!="") {
      $qry="SELECT * FROM aa_translation WHERE id='".addslashes($id)."'";
      $res=$db->Execute($qry);
      if ($res->RecordCount()==0) {  //umm... the field is multilang but there isn't a row in the translation table...
        $ret=$id;
      } else {
        $arr=$res->FetchRow();
        $ret=$arr[$_SESSION["synSiteLangInitial"]];

        if ($ret=="" and $err===true) {
          foreach ($arr as $mylang=>$mytrans) if (!is_numeric($mylang) and $mylang!="id" and $mytrans!="") $alt.="\n$mylang: ".substr(strip_tags($mytrans),0,10);
          $ret="<span style='color: gray' title=\"".htmlentities("Other Translations:".$alt)."\">[no translation]</span>";
        }
      }
    //} else $ret=$id;
    return $ret;
  }

  function updateLang() {
    global $db, $synSiteLang;
    if (isset($_GET["synSiteLang"])) setLang($_GET["synSiteLang"]);

    //check if exist a language id that match the session variable
    if ($_SESSION["synSiteLang"]!="") {
      $res=$db->Execute("SELECT id FROM aa_lang WHERE id=".$_SESSION["synSiteLang"]);
    }

    if ($_SESSION["synSiteLang"]=="" or $res->RecordCount()==0) {
      $prefLang=getenv("HTTP_ACCEPT_LANGUAGE");
      $res=$db->Execute("SELECT id FROM aa_lang WHERE initial='$prefLang'");
      if ($res->RecordCount()>0) list($_SESSION["synSiteLang"])=$res->FetchRow();

      else {
        $res=$db->Execute("SELECT id FROM aa_lang");
        if ($res->RecordCount()>0) list($_SESSION["synSiteLang"])=$res->FetchRow();
      }
    }

    //get the current lang
    $qry="SELECT initial FROM aa_lang WHERE id=".$_SESSION["synSiteLang"];
    $res=$db->Execute($qry);
    $arr=$res->FetchRow();
    $currlang=$arr[0];

    $_SESSION["aa_CurrentLangInitial"]=$currlang;
    $_SESSION["synSiteLangInitial"]=$currlang;
    setlocale(LC_ALL, strtolower($currlang)."_".strtoupper($currlang));

  }


  //insert a row in the translation table and return the id of the new row
  function insertTranslation_DEPRECATED($value) {
    global $db;
    $languagelist = "";
    $valuelist = "";

    //$lang=getLang(true);

    //get the list of languages
    $res=$db->Execute("SELECT initial FROM aa_lang");
    while (list($lang)=$res->FetchRow()) {
      $languagelist.=$lang.", ";
      $valuelist.="'".addslashes($value)."', ";
    }
    $languagelist=substr($languagelist,0,-2);
    $valuelist=substr($valuelist,0,-2);

    //insert the row in each language
    $qry="INSERT INTO aa_translation ($languagelist) VALUES ($valuelist)";
    $res=$db->Execute($qry);
    $ret=$db->Insert_ID();
    return $ret;
  }


  //insert a row in the translation table and return the id of the new row
  function insertTranslation($value) {
    global $db;

    $languages = getLangList();
    $fields = implode('`, `', $languages);

    if (!is_array($value)) {
      $temp_val = array();
      foreach($languages as $l){
        $temp_val[$l] = addslashes($value);
      }
      $value = $temp_val;
    }
    $values = implode("', '", $value);

    //insert the row in each language
    $qry = "INSERT INTO aa_translation (`{$fields}`) VALUES ('{$values}')";
    $res = $db->Execute($qry);

    return $db->Insert_ID();
  }


  //update the translation table by changing a the $id row with $value
  function updateTranslation(&$id, $value) {
    global $db;

    $lang = $_SESSION['aa_CurrentLangInitial'];

    $qry = "SELECT * FROM aa_translation WHERE id='".addslashes($id)."'";
    $res = $db->Execute($qry);
    if ($res->RecordCount()==0) {
      $id = insertTranslation($value);
    } else {

      if(is_array($value)){
        //get the list of languages
        $languages = getLangList();
        $values = array();
        foreach($value as $k => $v){
          $values[] = "`{$languages[$k]}` = '".addslashes($v)."'";
        }
        $sqlvalues = implode(', ', $values);
        $qry = "UPDATE aa_translation SET {$sqlvalues} WHERE id='{$id}'";

      } else {
        $qry = "UPDATE aa_translation SET {$lang} = '".addslashes($value)."' WHERE id='{$id}'";
      }
      $res = $db->Execute($qry);
    }
    return true;
  }

  //return the language list
  function getLangList() {
    global $db;

    $languages = array();

    //get the list of languages
    $res = $db->Execute('SELECT initial FROM aa_lang');
    while($arr = $res->FetchRow()) {
      $languages[] = $arr['initial'];
    }

    return $languages;
  }


/******************************************************************************
***                                  PAGE FUNCTIONS
*******************************************************************************/

function createPath($id) {
  global $db;
  $lng = $_SESSION['synSiteLangInitial'];
  $qry = <<<EOQ
   SELECT p.parent, t.$lng AS title
     FROM aa_page p
LEFT JOIN aa_translation t ON p.title = t.id
    WHERE p.id=$id
      AND p.parent>0
EOQ;
  $res = $db->Execute($qry);
  if ($res->RecordCount()==0) return "/";
  $a = $res->FetchRow();
  if($a['parent']!=$id && $a['parent']>0):
    $path = createPath($a['parent']);
  else:
    $path = '';
  endif;
  return $path.sanitizePath($a['title'])."/";
}

function sanitizePath($txt) {
  # sostituzione caratteri cirillici
  $enlow =  array("a", "a", "b", "b", "v", "v", "g", "g", "d", "d", "je", "je", "jo", "jo", "zh", "zh", "z", "z", "i", "i", "j", "j", "k", "k", "l", "l", "m", "m", "n", "n", "o", "o", "p", "p", "r", "r", "s", "s", "t", "t", "u", "u", "f", "f", "h", "h", "ts", "ts", "ch", "ch", "sh", "sh", "shch", "shch", "", "", "y", "y", "", "", "e", "e", "ju", "ju", "ja", "ja");
  $ru = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж", "ж", "З", "з", "И", "и", "Й", "й", "К", "к", "Л", "л", "М", "м", "Н", "н", "О", "о", "П", "п", "Р", "р", "С", "с", "Т", "т", "У", "у", "Ф", "ф", "Х", "х", "Ц", "ц", "Ч", "ч", "Ш", "ш", "Щ", "щ", "Ъ", "ъ", "Ы", "ы", "Ь", "ь", "Э", "э", "Ю", "ю", "Я", "я");
  $uni = array("А" => "А","а" => "а","Б" => "Б","б" => "б","В" => "В","в" => "в","Г" => "Г","г" => "г","Д" => "Д","д" => "д","Е" => "Е","е" => "е","Ж" => "Ж","ж" => "ж","З" => "З","з" => "з","И" => "И","и" => "и","Й" => "Й","й" => "й","К" => "К","к" => "к","Л" => "Л","л" => "л","М" => "М","м" => "м","Н" => "Н","н" => "н","О" => "О","о" => "о","П" => "П","п" => "п","Р" => "Р","р" => "р","С" => "С","с" => "с","Т" => "Т","т" => "т","У" => "У","у" => "у","Ф" => "Ф","ф" => "ф","Х" => "Х","х" => "х","Ц" => "Ц","ц" => "ц","Ч" => "Ч","ч" => "ч","Ш" => "Ш","ш" => "ш","Щ" => "Щ","щ" => "щ","Ъ" => "Ъ","ъ" => "ъ","Ы" => "Ы","ы" => "ы","Ь" => "Ь","ь" => "ь","Э" => "Э","э" => "э","Ю" => "Ю","ю" => "ю","Я" => "Я","я" => "я");

  $txt =  stripslashes(str_replace($ru, $enlow, strtr($txt, $uni)));
  $txt =  arabic_transliteration($txt);

  # accent folding
  $search = array(
    'À','Á','Â','Ä','Å','Æ','à','á','â','ä','å','æ',
    'ß',
    'Ç','ç',
    'È','É','Ë','è','é','ë',
    'Ì','Í','Ï','ì','í','ï',
    'Ñ','ñ',
    'Ò','Ó','Ô','Ö','Ø','ò','ó','ô','ö','ø',
    'Ù','Ú','Ü','ù','ú','ü',
    '%','=',',','/'
  );
  $replace= array(
    'A','A','A','A','A','AE','a','a','a','a','a','ae',
    'ss',
    'C','c',
    'E','E','E','e','e','e',
    'I','I','I','i','i','i',
    'N','n',
    'O','O','O','O','O','o','o','o','o','o',
    'U','U','U','u','u','u',
    'percent','eq','_','-'
  );
  $txt=str_replace($search,$replace,$txt);
  $txt=trim(strtolower(preg_replace('/[^A-Za-z0-9_]/',' ',$txt)));
  $txt=str_replace(' ','-',$txt);
  while (strstr($txt,'--')) $txt=str_replace('--','-',$txt);

  return $txt;
}

function getSqlTree($id) {
  global $db;
  $qry="SELECT * FROM aa_page WHERE parent='$id'";
  $res=$db->Execute($qry);
  while ($arr=$res->FetchRow()) {
    $id=$arr["id"];
    $ret.=getSqlTree($id)." OR sezione='$id'";
  }
  return $ret;
}

/*
function createSlugs($parent_id = '', $parent_slug = ''){
  global $db, $aa_CurrentLang;

  $qry = "SELECT * FROM aa_page WHERE parent='{$parent_id}' ORDER BY `order`";
  $res = $db->Execute($qry);
  while ($arr = $res->FetchRow()) {
    extract($arr);


    if(empty($parent_id)){
      $lang = getLangProperties($aa_CurrentLang);
      if($lang['default']==''){
        $title = $lang['initial'].'/';
      } else {
        $title = '';
      }
    } else {
      $title = translateSite($title);
    }
    $myslug = createSlug($parent_slug, $title, $slug);

      //$qry = "UPDATE aa_page SET slug = '{$myslug}' WHERE id='{$id}'";
      //$db->execute($qry);

    createSlugs($id, $myslug);
  }

}


function createSlug($parent, $str, $idtrans){
  global $db, $aa_CurrentLang;
  // insert into aa_translation

  $slug = $parent.sanitizePath($str).'/';

  updateTranslation($idtrans, $slug);

  return $slug;
}
*/

// aggiorna lo slug nella lingua corrente
function updateSlug($id){
  global $db;

  $qry = "SELECT `title`, `parent`, `slug` FROM `aa_page` WHERE `id` = '{$id}'";
  $res = $db->Execute($qry);
  if ($arr = $res->FetchRow()) {
    extract($arr);

    if(empty($parent)){
      $title = '';
    } else {
      $title = translate($title);
    }

    $new_slug = createUniqueSlug( $title, $id );

    updateTranslation($slug, $new_slug);
    return true;
  }
  return false;
}


// inserisce gli slug per tutte le lingue
function insertSlug($id){
  global $db;
  $languages = getLangList();

  $qry = "SELECT p.title, p.parent, p.slug, t.* FROM `aa_page` p LEFT JOIN aa_translation t ON p.title = t.id WHERE p.id = '{$id}'";
  $res = $db->Execute($qry);
  if ($arr = $res->FetchRow()) {
    $slug_array = array();
    foreach($languages AS $l){
      if(empty($arr['parent'])){
        $title = '';
      } else {
        $title = translate($arr[$l]);
      }
      $slug_array[] = createUniqueSlug($title, $id);
    }

    updateTranslation($arr['slug'], $slug_array);
    return true;
  }
  return false;
}




function createUniqueSlug( $slug, $id = 0){
  global $db;
  // verifica univocità

  if(!isset($_SESSION))
    session_start();
  $aa_CurrentLangInitial = $_SESSION['aa_CurrentLangInitial'];

  $slug = sanitizePath($slug);

  $existing = array();
  $qry = "SELECT t.{$aa_CurrentLangInitial} AS existing FROM `aa_page` p LEFT JOIN `aa_translation` t ON p.slug = t.id WHERE p.id <> '{$id}' ";

  $res = $db->Execute($qry);
  while($arr = $res->fetchrow()){
    $existing[] = $arr['existing'];
  }

  if($slug!='' && in_array($slug, $existing)){
    $count = 0;
    $slug_tmp = $slug;
    while (in_array($slug, $existing)){
      $count ++;
      $slug = $slug_tmp.$count;
    }
  }
//echo 'slug: ', $slug, '</br>'; die();
  return $slug;
}


/******************************************************************************
***                              IMAGE MANIPULATION
*******************************************************************************/

/*
  cleverThumb �
  crea il thumbnail SOLO se non esiste oppure l'immagine madre viene aggiornata.
  ritorna un tag <img>.
  ------------------------------------------------------------------------------
  parametri:
  - path successivo a mat/ dell'immagine madre [optional]
  - nome immagine madre
  - estensione immagine madre
  - suffisso del nome del thumbnail (es. "small", "medium" ecc.) [optional]
  - testo alt del thumbnail [optional]
  - larghezza
  - altezza
  - rotazione (inverte altezza e larghezza se l'immagine � verticale) [optional]
  - attributi html (classe, id, ecc.) [optional]
*/
function cleverThumb($path="", $filename, $foto, $suffix="", $alt="", $width=50, $height=50, $rotate=false, $attr=""){
  global $synPublicPath;
  if($filename=='' || $foto=='') return;
  $img         = $synPublicPath."/mat/".$path.$filename.".".$foto;
  $resultimg   = $synPublicPath."/mat/thumb/".$suffix.$filename.".".$foto;
  if(file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {
    $tmb_exists  = file_exists($_SERVER['DOCUMENT_ROOT'].$resultimg);
    $img_created = date("Y-m-d H:i:s", @filectime($_SERVER['DOCUMENT_ROOT'].$img));
    $tmb_created = date("Y-m-d H:i:s", @filectime($_SERVER['DOCUMENT_ROOT'].$resultimg));
    if ($tmb_exists) {
      list($w,$h) = @getimagesize(getenv("DOCUMENT_ROOT").$resultimg);
      $sameSize= ($w==$width) and ($h==$height);
    }
    if (!$tmb_exists || ($tmb_exists && ($img_created > $tmb_created)) || !$sameSize ){
      $thumbnail = new Image_Toolbox(getenv("DOCUMENT_ROOT").$img);
      $thumbnail-> setResizeMethod('resample');
      $thumbnail-> newOutputSize($width, $height, 1, $rotate, '#FFFFFF');
      $thumbnail-> save(getenv("DOCUMENT_ROOT")."/".$resultimg, "jpg");
    }
    return "<img src=\"".$resultimg."\" alt=\"".str_replace("\"","",htmlentities($alt))."\"".$attr."/>";
  }
}


function cleverThumbTag($path="", $filename, $foto, $suffix="", $alt="", $width=50, $height=50, $rotate=false, $attr=""){
  global $synPublicPath;
  if($filename=='' || $foto=='') return;
  $img         = $path.$filename.".".$foto;
  $resultimg   = $synPublicPath."/mat/thumb/".$suffix.$filename.".".$foto;
  if(file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {
    $tmb_exists  = file_exists($_SERVER['DOCUMENT_ROOT'].$resultimg);
    $img_created = date("Y-m-d H:i:s", @filectime($_SERVER['DOCUMENT_ROOT'].$img));
    $tmb_created = date("Y-m-d H:i:s", @filectime($_SERVER['DOCUMENT_ROOT'].$resultimg));
    if ($tmb_exists) {
      list($w,$h) = @getimagesize(getenv("DOCUMENT_ROOT").$resultimg);
      $sameSize= ($w==$width) and ($h==$height);
    }
    if (!$tmb_exists || ($tmb_exists && ($img_created > $tmb_created)) || !$sameSize ){
      $thumbnail = new Image_Toolbox(getenv("DOCUMENT_ROOT").$img);
      $thumbnail-> setResizeMethod('resample');
      $thumbnail-> newOutputSize($width, $height, 1, $rotate, '#FFFFFF');
      $thumbnail-> save(getenv("DOCUMENT_ROOT")."/".$resultimg, "jpg");
    }
    return "<img src=\"".$resultimg."\" alt=\"".str_replace("\"","",htmlentities($alt))."\"".$attr."/>";
  }
}


/******************************************************************************
***                                  VARIOUS FUNCTIONS
*******************************************************************************/

if (!function_exists('glob')) {
  function glob ($pattern) {
    $path_parts = pathinfo ($pattern);
    $pattern = '^' . str_replace (array ('*',  '?'), array ('(.+)', '(.)'), $path_parts['basename'] . '$');
    $dir = opendir (".{$path_parts['dirname']}/");
    while ($file = readdir ($dir)) {
      if (ereg ($pattern, $file)) $result[] = "{$path_parts['dirname']}/$file";
    }
    closedir ($dir);
    return $result;
  }
}


if (!function_exists("file_put_contents")) {
  function file_put_contents($filename,$content,$mode="w+") {
    if(!$file = @fopen($filename, $mode)) return false;
    if($file) {
      if(!@fwrite($file,$content)) return false;
      fclose($file);
      //@chmod($file, 0755);
    }
    return true;
  }
}


function is_connected() {
  //check to see if the local machine is connected to the web
  //uses sockets to open a connection to apisonline.com
  $connected = @fsockopen("www.syntaxdesktop.com", 80, $errno, $errstr, 10);
  if ($connected){
    $is_conn = true;
    fclose($connected);
  }else{
    $is_conn = false;
  }
  return $is_conn;

}//end is_connected function


function byteConvert( $bytes ) {
  if ($bytes<=0) return '0 Byte';
  $convention=1000; //[1000->10^x|1024->2^x]
  $s=array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB');
  $e=floor(log($bytes,$convention));
  return round($bytes/pow($convention,$e),2).' '.$s[$e];
}


function interpolate($template, $hash, $prefix = '#{', $postfix = '}' ) {
  $tokenize = create_function('$token', 'return "'. $prefix .'".$token."'.$postfix.'";');
  $keys = array_keys($hash);
  $values = array_values($hash);
  $keys = array_map($tokenize, $keys);

  return str_replace($keys, $values, $template);
}

function fixEncoding($str) {
  $cur_encoding = mb_detect_encoding($str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($str,"UTF-8"))
  	$ret = $str;
  else
  	$ret = utf8_encode($str);
  return $ret;
}

$arabic_transliteration_constants = array(
  // letters
  'alef' => 0x0627,
  'ba' => 0x0628,
  'ta' => 0x062A,
  'tha' => 0x062B,
  'jim' => 0x062C,
  'hha' => 0x062D,
  'kha' => 0x062E,
  'dal' => 0x062F,
  'dhal' => 0x0630,
  'ra' => 0x0631,
  'zay' => 0x0632,
  'sin' => 0x0633,
  'shin' => 0x0634,
  'sad' => 0x0635,
  'dad' => 0x0636,
  'tta' => 0x0637,
  'zza' => 0x0638,
  'ayn' => 0x0639,
  'ghayn' => 0x063A,
  'fa' => 0x0641,
  'qaf' => 0x0642,
  'kaf' => 0x0643,
  'lam' => 0x0644,
  'mim' => 0x0645,
  'nun' => 0x0646,
  'ha' => 0x0647,
  'waw' => 0x0648,
  'ya' => 0x064A,

  // other letters
  'alef_with_wasla' => 0x0671,
  'alef_with_sup_hamza' => 0x0623,
  'alef_with_sub_hamza' => 0x0625,
  'alef_maqsura' => 0x0649,
  'alef_with_madda' => 0x0622,
  'ta_marbuta' => 0x0629,
  'waw_with_hamza' => 0x0624,
  'ya_with_hamza' => 0x0626,
  'hamza' => 0x0621,

  // harakat
  'fatha' => 0x064E,
  'damma' => 0x064F,
  'kasra' => 0x0650,
  'sukun' => 0x0652, // (not a haraka! rather, a non-haraka!)

  // tanween
  'fathatan' => 0x064B,
  'dammatan' => 0x064C,
  'kasratan' => 0x064D,

  // other tashkil
  'shadda' => 0x0651,

  // other
  'dagger_alef' => 0x0670,
  'tatwil' => 0x0640,
);

foreach($arabic_transliteration_constants as $name => $value){
  $arabic_transliteration_constants[$name] = arabic_transliteration_convert_to_utf8($value);
}

$arabic_transliteration_constants['hamzas'] =
  $arabic_transliteration_constants['alef_with_sup_hamza'] .
  $arabic_transliteration_constants['alef_with_sub_hamza'] .
  $arabic_transliteration_constants['alef_with_madda'] .
  $arabic_transliteration_constants['waw_with_hamza'] .
  $arabic_transliteration_constants['ya_with_hamza'] .
  $arabic_transliteration_constants['hamza'];

$arabic_transliteration_constants['sun_letters'] =
  $arabic_transliteration_constants['ta'] .
  $arabic_transliteration_constants['tha'] .
  $arabic_transliteration_constants['dal'] .
  $arabic_transliteration_constants['dhal'] .
  $arabic_transliteration_constants['ra'] .
  $arabic_transliteration_constants['zay'] .
  $arabic_transliteration_constants['sin'] .
  $arabic_transliteration_constants['shin'] .
  $arabic_transliteration_constants['sad'] .
  $arabic_transliteration_constants['dad'] .
  $arabic_transliteration_constants['tta'] .
  $arabic_transliteration_constants['zza'] .
  $arabic_transliteration_constants['lam'] .
  $arabic_transliteration_constants['nun'];

$arabic_transliteration_constants['moon_letters'] =
  $arabic_transliteration_constants['hamzas'] .
  $arabic_transliteration_constants['ba'] .
  $arabic_transliteration_constants['jim'] .
  $arabic_transliteration_constants['hha'] .
  $arabic_transliteration_constants['kha'] .
  $arabic_transliteration_constants['ayn'] .
  $arabic_transliteration_constants['ghayn'] .
  $arabic_transliteration_constants['fa'] .
  $arabic_transliteration_constants['qaf'] .
  $arabic_transliteration_constants['kaf'] .
  $arabic_transliteration_constants['mim'] .
  $arabic_transliteration_constants['ha'] .
  $arabic_transliteration_constants['waw'] .
  $arabic_transliteration_constants['ya'];

$arabic_transliteration_constants['standard_letters'] =
  $arabic_transliteration_constants['hamzas'] .
  $arabic_transliteration_constants['ba'] .
  $arabic_transliteration_constants['ta'] .
  $arabic_transliteration_constants['tha'] .
  $arabic_transliteration_constants['jim'] .
  $arabic_transliteration_constants['hha'] .
  $arabic_transliteration_constants['kha'] .
  $arabic_transliteration_constants['dal'] .
  $arabic_transliteration_constants['dhal'] .
  $arabic_transliteration_constants['ra'] .
  $arabic_transliteration_constants['zay'] .
  $arabic_transliteration_constants['sin'] .
  $arabic_transliteration_constants['shin'] .
  $arabic_transliteration_constants['sad'] .
  $arabic_transliteration_constants['dad'] .
  $arabic_transliteration_constants['tta'] .
  $arabic_transliteration_constants['zza'] .
  $arabic_transliteration_constants['ayn'] .
  $arabic_transliteration_constants['ghayn'] .
  $arabic_transliteration_constants['fa'] .
  $arabic_transliteration_constants['qaf'] .
  $arabic_transliteration_constants['kaf'] .
  $arabic_transliteration_constants['lam'] .
  $arabic_transliteration_constants['mim'] .
  $arabic_transliteration_constants['nun'] .
  $arabic_transliteration_constants['ha'] .
  $arabic_transliteration_constants['waw'] .
  $arabic_transliteration_constants['ya'];

$arabic_transliteration_constants['standard_letters_without_lam'] =
  $arabic_transliteration_constants['hamzas'] .
  $arabic_transliteration_constants['ba'] .
  $arabic_transliteration_constants['ta'] .
  $arabic_transliteration_constants['tha'] .
  $arabic_transliteration_constants['jim'] .
  $arabic_transliteration_constants['hha'] .
  $arabic_transliteration_constants['kha'] .
  $arabic_transliteration_constants['dal'] .
  $arabic_transliteration_constants['dhal'] .
  $arabic_transliteration_constants['ra'] .
  $arabic_transliteration_constants['zay'] .
  $arabic_transliteration_constants['sin'] .
  $arabic_transliteration_constants['shin'] .
  $arabic_transliteration_constants['sad'] .
  $arabic_transliteration_constants['dad'] .
  $arabic_transliteration_constants['tta'] .
  $arabic_transliteration_constants['zza'] .
  $arabic_transliteration_constants['ayn'] .
  $arabic_transliteration_constants['ghayn'] .
  $arabic_transliteration_constants['fa'] .
  $arabic_transliteration_constants['qaf'] .
  $arabic_transliteration_constants['kaf'] .
  //$arabic_transliteration_constants['lam'] .
  $arabic_transliteration_constants['mim'] .
  $arabic_transliteration_constants['nun'] .
  $arabic_transliteration_constants['ha'] .
  $arabic_transliteration_constants['waw'] .
  $arabic_transliteration_constants['ya'];

$arabic_transliteration_constants['extraneous_letters'] =
  $arabic_transliteration_constants['alef_with_wasla'] .
  $arabic_transliteration_constants['alef_with_sup_hamza'] .
  $arabic_transliteration_constants['alef_with_sub_hamza'] .
  $arabic_transliteration_constants['alef_maqsura'] .
  $arabic_transliteration_constants['alef_with_madda'] .
  $arabic_transliteration_constants['ta_marbuta'] .
  $arabic_transliteration_constants['waw_with_hamza'] .
  $arabic_transliteration_constants['ya_with_hamza'] .
  $arabic_transliteration_constants['hamza'];

$arabic_transliteration_constants['standard_harakat'] =
  $arabic_transliteration_constants['fatha'] .
  $arabic_transliteration_constants['damma'] .
  $arabic_transliteration_constants['kasra'];

$arabic_transliteration_constants['tanween'] =
  $arabic_transliteration_constants['fathatan'] .
  $arabic_transliteration_constants['dammatan'] .
  $arabic_transliteration_constants['kasratan'];

$arabic_transliteration_constants['tashkil'] =
  $arabic_transliteration_constants['standard_harakat'] .
  $arabic_transliteration_constants['sukun'] .
  $arabic_transliteration_constants['tanween'] .
  $arabic_transliteration_constants['shadda'];

$arabic_transliteration_constants['non_transforming_prefixed_particles'] =
  $arabic_transliteration_constants['ba'] . $arabic_transliteration_constants['kasra'] . '|' .
  $arabic_transliteration_constants['waw'] . $arabic_transliteration_constants['fatha'] . $arabic_transliteration_constants['ba'] . $arabic_transliteration_constants['kasra'] . '|' .
  $arabic_transliteration_constants['fa'] . $arabic_transliteration_constants['fatha'] . $arabic_transliteration_constants['ba'] . $arabic_transliteration_constants['kasra'] . '|' .

  $arabic_transliteration_constants['kaf'] . $arabic_transliteration_constants['fatha'] . '|' .
  $arabic_transliteration_constants['waw'] . $arabic_transliteration_constants['fatha'] . $arabic_transliteration_constants['kaf'] . $arabic_transliteration_constants['fatha'] . '|' .
  $arabic_transliteration_constants['fa'] . $arabic_transliteration_constants['fatha'] . $arabic_transliteration_constants['kaf'] . $arabic_transliteration_constants['fatha'] . '|' .

  $arabic_transliteration_constants['ta'] . $arabic_transliteration_constants['fatha'] . '|' .
  $arabic_transliteration_constants['waw'] . $arabic_transliteration_constants['fatha'] . $arabic_transliteration_constants['ta'] . $arabic_transliteration_constants['fatha'] . '|' .
  $arabic_transliteration_constants['fa'] . $arabic_transliteration_constants['fatha'] . $arabic_transliteration_constants['ta'] . $arabic_transliteration_constants['fatha'] . '|' .

  $arabic_transliteration_constants['waw'] . $arabic_transliteration_constants['fatha'] . '|' .
  $arabic_transliteration_constants['fa'] . $arabic_transliteration_constants['fatha'];

function arabic_transliteration_convert_to_utf8($unicode , $encoding = 'UTF-8'){
  return mb_convert_encoding("&#{$unicode};", $encoding, 'HTML-ENTITIES');
}

function arabic_transliteration($content, $options = array()) {
  $default_options = array(
    'stop-on-sukun' => 1,
    'ta-marbuta-becomes-ha' => 0,
  );

  foreach($default_options as $key => $default_value){
    if(!array_key_exists($key, $options)){
      $options[$key] = $default_value;
    }
  }

  // tags
  $content = strip_tags($content);

  // remove extraneoous whitespace
  $content = arabic_transliteration_replace("\s+", " ", $content);

  $content = arabic_transliteration_transform($content, $options);
  $content = arabic_transliteration_translate($content, $options);

  // cleanup
  $content = preg_replace("/[\x{0590}-\x{06FF}]/u", "", $content);

  return $content;
}

function arabic_transliteration_transform($content, $options){
  global $arabic_transliteration_constants;
  extract($arabic_transliteration_constants);

  // move shadda next to letter
  $content = arabic_transliteration_replace("([$standard_harakat$sukun$tanween])($shadda)", "\\2\\1", $content);

  // one-letter words should always have its haraka transliterated
  if(preg_match("/(?:^| )[$sun_letters$moon_letters$extraneous_letters][$tashkil]*$/u", $content)){
    $options['stop-on-sukun'] = 0;
  }

  // anti at end of sentence should always be written out
  if(preg_match("/(?:^| )[$alef$alef_with_sup_hamza]$fatha?$nun$sukun?$ta$kasra$/u", $content)){
    $options['stop-on-sukun'] = 0;
  }



  /* ALEF WITH WASLA */
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef_with_wasla([$standard_letters])$sukun([$standard_letters][$fatha$kasra$tanween])", "\\1\\2$alef_with_wasla$kasra\\3$sukun\\4", $content);
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef_with_wasla([$standard_letters])$sukun([$standard_letters][$damma])", "\\1\\2$alef_with_wasla$damma\\3$sukun\\4", $content);

  // unmarked alef with wasla indicated by sukun on next letter
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef([$standard_letters_without_lam])$sukun([$standard_letters_without_lam][$fatha$kasra])", "\\1\\2$alef_with_wasla$kasra\\3$sukun\\4", $content);
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef([$standard_letters_without_lam])$sukun([$standard_letters_without_lam][$damma])", "\\1\\2$alef_with_wasla$damma\\3$sukun\\4", $content);
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef([$standard_letters])$sukun", "\\1\\2$alef_with_wasla\\3$sukun", $content);

  // TODO: fi`l amr with lam

  // unmarked alef with wasla indicated by no sign on next letter, shadda on 2nd next letter
  $content = arabic_transliteration_replace("$alef([$standard_letters][$standard_letters])$shadda", "$alef_with_wasla\\1$shadda", $content);

  // regular alef, lam, and regular letter not marked by any tashkil
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef$lam([$standard_letters])", "\\1\\2$alef_with_wasla$lam-\\3", $content);

  // alef-lam in "allah" should not have dash (=> al-lāh), so remove first lam for it not to be considered as a regular alef-lam
  $content = arabic_transliteration_replace("$alef_with_wasla$lam$lam$shadda$fatha$dagger_alef?$ha", "$alef_with_wasla$lam$shadda$fatha$dagger_alef$ha", $content);

  // alladhee/allatee should have alef with wasla
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$alef$lam$shadda$fatha?([$dhal$ta])$kasra?$ya", "\\1\\2$alef_with_wasla$lam$shadda$fatha\\3$kasra$ya", $content);



  /* DAGGER ALEF */

  // rahman should have alef lam
  $content = arabic_transliteration_replace("$lam$ra$shadda?$fatha?$hha$sukun?$mim$fatha?$nun", "$lam$ra$shadda$fatha$hha$sukun$mim$fatha$dagger_alef$nun", $content);
  // lillah should have dagger alef
  $content = arabic_transliteration_replace("(^| )($fa$fatha|$waw$fatha)?$lam$kasra?$lam(?:$shadda|$shadda$fatha)?$ha$kasra?($| )", "\\1\\2$lam$kasra$lam$shadda$fatha$dagger_alef$ha$kasra\\3", $content);
  // dhalika should have dagger alef
  $content = arabic_transliteration_replace("(^| )($non_transforming_prefixed_particles)?$dhal$fatha?$lam$kasra?$kaf$fatha?($| )", "\\1\\2$dhal$fatha$dagger_alef$lam$kasra$kaf$fatha\\3", $content);

  // dagger alef
  $content = arabic_transliteration_replace($dagger_alef, $alef, $content);



  /* SUN/MOON LETTERS */

  // sun letters
  $content = arabic_transliteration_replace("(^| )$alef_with_wasla$lam([$sun_letters])$shadda", "\\1$alef_with_wasla\\2-\\2", $content);

  // moon letters
  $content = arabic_transliteration_replace("(^| )$alef_with_wasla$lam$sukun?([$moon_letters])", "\\1$alef_with_wasla$lam-\\2", $content);

  //

  /*
  // prevent lam becoming "-" if succeeded by tanween
  $content = arabic_transliteration_replace("لاً", "لْاً", $content);
  // allah (common spelling: defective tashkil)
  $content = arabic_transliteration_replace("الله", "ٱلْلَاه", $content);
  $content = arabic_transliteration_replace("اللَّه", "ٱلْلَاه", $content);

  // ta marbutah without preceding fathah
  $content = arabic_transliteration_replace("([^$fatha])$ta_marbuta", "\$1$fatha$ta_marbuta", $content);

  // ana
  $content = arabic_transliteration_replace("$alef_with_sup_hamza$fatha?$nun$fatha?$alef$", "أَنَ$sukun", $content);
  $content = arabic_transliteration_replace("$alef_with_sup_hamza$fatha?$nun$fatha?$alef ", "أَنَ$sukun ", $content);
  // anti
  $content = arabic_transliteration_replace("أَنْتِ$", "أَنْتِ$sukun", $content);
  */



  /* SPECIAL LETTERS */

  // tatwil
  $content = arabic_transliteration_replace("$tatwil", "", $content); // todo: add to constants

  // alif maqsura
  $content = arabic_transliteration_replace("$alef_maqsura", "$alef", $content);

  // hamza in beginning of words (with harakah)
  $content = arabic_transliteration_replace("^[$alef_with_sup_hamza$alef_with_sub_hamza$hamza]([$standard_harakat])", "\$1", $content);
  $content = arabic_transliteration_replace(" [$alef_with_sup_hamza$alef_with_sub_hamza$hamza]([$standard_harakat])", " \$1", $content);
  // hamza in beginning of words (without harakah)
  $content = arabic_transliteration_replace("^$alef_with_sup_hamza", "a", $content);
  $content = arabic_transliteration_replace(" $alef_with_sup_hamza", " a", $content);
  $content = arabic_transliteration_replace("^$alef_with_sub_hamza", "i", $content);
  $content = arabic_transliteration_replace(" $alef_with_sub_hamza", " i", $content);
  $content = arabic_transliteration_replace("^$hamza", "'", $content);
  $content = arabic_transliteration_replace(" $hamza", " '", $content);
  // hamza inside words
  $content = arabic_transliteration_replace("([^-])[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]", "\$1'", $content);
  $content = arabic_transliteration_replace("[$alef_with_sup_hamza$alef_with_sub_hamza$hamza$waw_with_hamza$ya_with_hamza]", "", $content);

  // alif with wasla preceded by haraka
  $content = arabic_transliteration_replace("([$standard_harakat])( )?$alef_with_wasla([$standard_harakat])?", "\\1\\2", $content);

  // alif with wasla preceded by long a
  $content = arabic_transliteration_replace("([$standard_letters])$fatha?$alef $alef_with_wasla([$standard_harakat])?", "\\1$fatha ", $content);
  // alif with wasla preceded by long u
  $content = arabic_transliteration_replace("$damma$waw $alef_with_wasla([$standard_harakat])?", "$damma ", $content);
  // alif with wasla preceded by long i
  $content = arabic_transliteration_replace("$kasra$ya $alef_with_wasla([$standard_harakat])?", "$kasra ", $content);
  // alif with wasla preceded by sukun
  $content = arabic_transliteration_replace("([$standard_letters])$sukun $alef_with_wasla([$standard_harakat])?", "\\1$kasra ", $content);

  // alif with wasla
  //$content = arabic_transliteration_replace("$alef_with_wasla", "a", $content);
  // alif with madda
  $content = arabic_transliteration_replace("$alef_with_madda", "$alef", $content);

  // ta marbuta at end of word sequence
  if($options['ta-marbuta-becomes-ha'] == 1){
    $content = arabic_transliteration_replace("$ta_marbuta([$tashkil]*)$", "$ha\\1", $content);
  } else {
    $content = arabic_transliteration_replace("$ta_marbuta([$tashkil]*)$", "\\1", $content);
  }

  // question mark
  $content = arabic_transliteration_replace("؟", "?", $content);



  /* SPECIAL CASES */

  // i - mi'ah
  $content = arabic_transliteration_replace("$kasra$alef", "$kasra", $content);

  /* SHADDA */

  // vowels
  $content = arabic_transliteration_replace("$damma$waw$shadda", "$damma$waw$sukun$waw", $content);
  $content = arabic_transliteration_replace("$kasra$ya$shadda", "$kasra$ya$sukun$ya", $content);

  // regular
  $content = arabic_transliteration_replace("(.)$shadda", "\$1$sukun\$1", $content);

  //shadda of two-letter transliterated letters
  $content = arabic_transliteration_replace("($tha|$kha|$dhal|$shin|$ghayn)$sukun\\1", "\\1$sukun-\\1", $content);



  /* STOP ON SUKUN */

  if($options['stop-on-sukun']){
    // tanween
    $content = arabic_transliteration_replace("$fathatan$alef$", "$fatha$alef", $content);
    $content = arabic_transliteration_replace("$fathatan$", "", $content);
    $content = arabic_transliteration_replace("$dammatan$", "", $content);
    $content = arabic_transliteration_replace("$kasratan$", "", $content);
    // harakat
    $content = arabic_transliteration_replace("$fatha$", "", $content);
    $content = arabic_transliteration_replace("$damma$", "", $content);
    $content = arabic_transliteration_replace("$kasra$", "", $content);
  }

  return $content;
}

function arabic_transliteration_translate($content, $options){
  global $arabic_transliteration_constants;
  extract($arabic_transliteration_constants);

  $translation = array(
    // alef with fathatan
    "$fathatan$alef" => $fathatan,
    "$alef$fathatan" => $fathatan,

    // tanween
    $fathatan => 'an',
    $kasratan => 'in',
    $dammatan => 'un',

    // consonants
    $ba => 'b',
    $ta => 't',
    $tha => 'th',
    $jim => 'j',
    $hha => 'ḥ',
    $kha => 'kh',
    $dal => 'd',
    $dhal => 'dh',
    $ra => 'r',
    $zay => 'z',
    $sin => 's',
    $shin => 'sh',
    $sad => 'ṣ',
    $dad => 'ḍ',
    $tta => 'ṭ',
    $zza => 'ẓ',
    $ayn => 'ʿ',
    $ghayn => 'gh',
    $fa => 'f',
    $qaf => 'q',
    $kaf => 'k',
    $lam => 'l',
    $mim => 'm',
    $nun => 'n',
    $ha => 'h',

    $hamza => '\'',
    $ta_marbuta => 't',

    // waw
    "$damma$waw$fatha" => "{$damma}w$fatha",
    "$damma$waw$sukun$waw$fatha" => "{$damma}ww$fatha",
    "$damma$waw$alef" => "{$damma}w$alef",
    "$damma$waw$sukun$waw$alef" => "{$damma}ww$alef",
    "$damma$waw" => "ū",
    $waw => 'w',

    // ya
    "$kasra$ya$sukun$ya$fatha" => "{$kasra}yy$fatha",
    "$kasra$ya$fatha" => "{$kasra}y$fatha",
    "$kasra$ya$sukun$ya$alef" => "{$kasra}yy$alef",
    "$kasra$ya$alef" => "{$kasra}y$alef",
    "$kasra$ya" => "ī",
    $ya => 'y',

    // vowels
    "$fatha$alef" => 'ā',
    $alef => 'ā',

    "$alef_with_wasla$fatha" => 'a',
    "$alef_with_wasla$kasra" => 'i',
    "$alef_with_wasla$damma" => 'u',
    "$alef_with_wasla" => 'a',

    // harakat
    $fatha => 'a',
    $kasra => 'i',
    $damma => 'u',
  );

  $content = str_replace(array_keys($translation), array_values($translation), $content);

  return $content;
}

function arabic_transliteration_replace($pattern, $replace, $subject){
  return preg_replace("/$pattern/u", $replace, $subject);
}


/*
 * Notify Functions
 */

function setAlert($message, $type='info'){
  if (!isset($_SESSION))
    session_start();

  if (!isset($_SESSION['synAlert']))
    $_SESSION['synAlert'] = array();

  if (!empty($message)) {
    $_SESSION['synAlert'][] = array(
      'message' => $message,
      'type' => $type
    );
  }
}

function getAlert(){
  if(!isset($_SESSION))
    session_start();

  $ret = null;

  if (isset($_SESSION['synAlert'])){
    foreach ($_SESSION['synAlert'] as $key => $alert) {
      extract ($alert);
      switch ($type) {
        case 'success'  : $icon = 'fa-check-square'; break;
        case 'warning'  : $icon = 'fa-exclamation-circle'; break;
        case 'danger'   : $icon = 'fa-exclamation-triangle'; break;
        default         : $icon = 'fa-info-circle'; break;
      }
      $ret .= <<<ENDOFRET
      $.notify(
        { icon: 'fa {$icon}', message: '{$message}' },
        { type: '{$type}' }
      );
ENDOFRET;

      unset( $_SESSION['synAlert'][$key] );
    }
  }
  return $ret;
}

function enqueue_js( $script ) {
  global $js;
  array_push($js, $script);
}

?>