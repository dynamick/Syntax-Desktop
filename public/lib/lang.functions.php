<?php
/******************************************************************************
***                                  LANG FUNCTIONS
*******************************************************************************/

//set the current language
function setLang($id, $initial='') {
  global $db;
  
  if(!isset($_SESSION))
    session_start();
    
  $lang = intval($id);

  if ($lang == 0) {
    return false;

  } else {
    if($initial==''){
      //get the current initial for retro-compatibility
      $qry = "SELECT initial FROM aa_lang WHERE id='{$lang}' AND `active`=1";
      $res = $db->Execute($qry);
      if ($arr = $res->fetchRow()) {
        $initial = $arr['initial'];
      } else {
        // è stata richiesta una lingua non attiva?
        $qry = "SELECT initial FROM aa_lang WHERE `active`=1";
        $res = $db->Execute($qry);
        if ($arr = $res->fetchRow()) {
          $initial = $arr['initial'];
        } else die('nessuna lingua attivata'); // l'initial DEVE essere valorizzata...
      }
    }

    $_SESSION['synSiteLang'] = $lang;
    $_SESSION['synSiteLangInitial'] = $initial;

    //setlocale(LC_ALL, strtolower($currlang)."_".strtoupper($currlang));
    return true;
  }
}

function getLangList(){
  global $db;
  $ret = array();
  $res = $db->Execute("SELECT * FROM aa_lang WHERE `active`=1");
  while($arr = $res->fetchrow()){
    extract($arr, EXTR_PREFIX_ALL, 'lang');
    if($lang_default=='1')
      $ret['default'] = $lang_initial;
    $ret['list'][$lang_id] = $lang_initial;
  }
  return $ret;
}

//DEPRECATED - return the language list (i.e. en,it,es)
function getLangList_DEPRECATED() {
  global $db;
  $res=$db->Execute("SELECT initial FROM aa_lang ");
  while (list($lang)=$res->FetchRow()) $ret.=$lang.", ";
  return substr($ret,0,-2);
}

//return languages array
function getLangArr() {
  global $db;
  $ret = array();
  $res = $db->Execute("SELECT initial FROM aa_lang");
  while(list($l)=$res->FetchRow()) $ret[] = $l;
  return $ret;
}

//translate an element for the desktop. If err==true display the error message
function translateSite($id, $err=false) {
  global $db;

  if(!isset($_SESSION))
    session_start();

  if (isset($_GET["synSiteLang"])) setLang($_GET["synSiteLang"]);
  if ($_SESSION["synSiteLang"]=="" or !isset($_SESSION["synSiteLang"])){
    updateLang();
  }

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

  return $ret;
}


function updateLang() {
  global $db, $synSiteLang;

  if(!isset($_SESSION))
    session_start();

  if (isset($_GET['synSiteLang']))
    setLang(intval($_GET['synSiteLang']));

  //check if a language id that matches the session variable exists
  if ( isset($_SESSION['synSiteLang'])
    && $_SESSION['synSiteLang'] != ''
    ){
    $sql = "SELECT id FROM aa_lang WHERE id=".intval($_SESSION['synSiteLang']).' AND `active`=1';
    $res = $db->Execute($sql);
    if(!$res->fetchrow()){
      $_SESSION['synSiteLang'] = '';
    }
  }

  if ( !isset($_SESSION['synSiteLang'])
    || $_SESSION['synSiteLang'] == ''
    ){
    $available = array();
    $preferred = implode("', '", array_reverse(get_languages()));

/*
    perchè $preferred è girato al contrario?
    ORDER BY FIELD ritorna PRIMA i record non elencati, POI quelli elencati
    nell'ordine dato. Il DESC inverte questa logica, ma per mantenere l'ordine
    di preferenza devo girare anche $preferred.

    Es.: ho it, en, es, fr. Il browser vuole it o en.
    con ORDER BY FIELD(initial, 'it', 'en') ottengo:
    1. es
    2. fr
    3. it
    4. en

    con ORDER BY FIELD(initial, 'en', 'it') DESC ottengo:
    1. it
    2. en
    3. es
    4. fr
*/

    $sql = <<<EOSQL
    SELECT id, initial
      FROM aa_lang
     WHERE `active`=1
  ORDER BY FIELD(initial, '{$preferred}') DESC
     LIMIT 0,1
EOSQL;

    $res = $db->Execute($sql);
    if ($arr = $res->fetchrow()){
      $preferred_id = $arr['id'];
      $preferred_initial = $arr['initial'];
    }

    setLang($preferred_id, $preferred_initial);
  }
  //echo '<pre>', print_r($_SESSION), '</pre>';
}



// if not already exists, insert a row in the translation table and return the
// translation for the current selected lang
function l($value,$replace="") {
  global $db;
  //$lang=getLang(true);

  //get the list of languages
  $res=$db->Execute("SELECT initial FROM aa_lang");
  while (list($lang)=$res->FetchRow()) {
    $languagelist.=$lang.", ";
    $valuelist.="'".addslashes($value)."', ";
    $select.=$lang."= '".addslashes($value)."' OR ";
  }
  $languagelist=substr($languagelist,0,-2);
  $valuelist=substr($valuelist,0,-2);
  $select=substr($select,0,-3);
  //search for the string if already into database
  $qry="SELECT * FROM aa_translation WHERE $select";
  $res=$db->Execute($qry);
  $count=$res->RecordCount();
  // already exists
  if ($count>0) {
    $arr=$res->FetchRow();
    $id=$arr["id"];
  } else {
  //insert the row in each language
    $qry="INSERT INTO aa_translation ($languagelist) VALUES ($valuelist)";
    $res=$db->Execute($qry);
    $id=$db->Insert_ID();
  }
  $ret=translateSite($id);
  if ($replace!="") $ret=str_replace("###",$replace,$ret);

  return $ret;
}


function translateDictionary($label){
  // traduzione singola
  // es. translateDictionary("home_title_realizzazioni")
  global $db;

  if(!isset($_SESSION))
    session_start();
    
  $lng = $_SESSION['synSiteLangInitial'];
  $qry = "SELECT t.{$lng} AS value FROM dictionary v JOIN aa_translation t ON v.value=t.id WHERE v.label='{$label}'";
  $res = $db->Execute($qry);
  if ($res->RecordCount()==0) {
    $ret = $label;
  } else {
    $arr = $res->FetchRow();
    $ret = $arr['value'];
  }
  return $ret;
}


function multiTranslateDictionary($labels=array(), $auto_insert=false){
  // traduzione multipla, ritorna un array
  global $db;

  if(!isset($_SESSION))
    session_start();

  $lng = $_SESSION["synSiteLangInitial"];
  $ret = array();
  $qry = "SELECT v.label, t.{$lng} AS value "
       . "FROM dictionary v "
       . "LEFT JOIN aa_translation t ON v.value=t.id "
       . "WHERE v.label='".implode("' OR v.label='", $labels)."'";
  $res = $db->Execute($qry);
  $ret = array();
  while ($arr = $res->FetchRow()) {
    $ret[$arr['label']] = !empty($arr['value']) ? $arr['value'] : "<mark>[missing translation]</mark> {$arr['label']}";
  }
  // aggiungo le chiavi non trovate
  foreach ($labels as $label) {
    if (!array_key_exists($label, $ret)) {
      $ret[$label] = "<mark>[missing translation]</mark> {$label}";
      if ($auto_insert == true) {
        // inserisco nel dizionario la voce mancante
        $qry = "INSERT INTO dictionary (`label`) VALUES ('{$label}')";
        $res = $db->Execute($qry);
      }
    }
  }
  return $ret;
}




function get_languages(){
	$user_languages = array();

	//check to see if language is set
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		$languages = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		// $languages = ' fr-ch;q=0.3, da, en-us;q=0.8, en;q=0.5, fr;q=0.3';
		$languages = explode(',', str_replace(' ', '', $languages));

		foreach ( $languages as $language_list ){
      $language = substr( $language_list, 0, strcspn( $language_list, ';' ));
      // lingua tipo 'IT-IT'
      if(strpos($language, '-')>0){
        //cambio 'it-it' in 'it_it' per compatibilità con mySql - non sono sicuro sia necessario... !!!
        $language = str_replace('-', '_', trim($language));
        //versione corta: cambio 'it_it' in 'it'
        $langshort = substr($language, 0, 2);
      }

      if(!in_array($language, $user_languages)){
        $user_languages[] = $language;
      }
      if(isset($langshort) && !in_array($langshort, $user_languages)){
        $user_languages[] = $langshort;
        unset($langshort);
      }
		}
	}	else {// trovato niente
		$user_languages[0] = '';
	}

  return $user_languages;
}

// EOF lang.functions.php