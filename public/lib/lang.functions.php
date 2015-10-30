<?php
/******************************************************************************
***                                  LANG FUNCTIONS
*******************************************************************************/

function s_start() {
  if ( !isset($_SESSION) ) {
    ini_set( 'session.cookie_httponly', 1 );
    if ( !session_start() ) {
      throw new Exception( 'Session cannot be started', 1);
    }
  }
}

//set the current language
function setLang($id, $initial='') {
  global $db;

  s_start(); // session_start

  $lang = intval($id);

  if ($lang == 0) {
    return false;

  } else {
    if ( empty($initial) ){
      // get the current initial for retro-compatibility
      $qry = "SELECT initial FROM aa_lang WHERE id='{$lang}' AND `active`='1'";
      $res = $db->Execute($qry);
      if ($arr = $res->fetchRow()) {
        $initial = $arr['initial'];
      } else {
        // è stata richiesta una lingua non attiva?
        $qry = "SELECT initial FROM aa_lang WHERE `active`='1'";
        $res = $db->Execute($qry);
        if ($arr = $res->fetchRow()) {
          $initial = $arr['initial'];
        } else
          throw new Exception("No available languages!"); // l'initial DEVE essere valorizzata...
      }
    }

    $_SESSION['synSiteLang'] = $lang;
    $_SESSION['synSiteLangInitial'] = $initial;

    //setlocale(LC_ALL, strtolower($currlang)."_".strtoupper($currlang));
    return true;
  }
}

function getLangList() {
  global $db;
  $ret = array(
    'list' => array(),
    'domain' => array(),
    'default' => ''
  );
  $res = $db->Execute("SELECT * FROM aa_lang WHERE `active` = '1'");
  while($arr = $res->fetchrow()){
    extract($arr, EXTR_PREFIX_ALL, 'lang');
    $ret['list'][$lang_id] = $lang_initial;

    if ( !empty($lang_domain) ) {
      // check protocol presence
      $domain = ensureUrlScheme( $lang_domain );
      if ( filter_var( $domain, FILTER_VALIDATE_URL) )
        $ret['domain'][$lang_id] = $domain;
      else
        throw new Exception("Domain not valid for language '{$lang_initial}'");

      // manage default lang per-domain
      if ( $lang_default == '1'){
        if ( !is_array($ret['default']) )
          $ret['default'] = array();
        $ret['default'][$domain] = $lang_initial;
      }
    } else {
      if ( $lang_default == '1' ) {
        $ret['default'] = $lang_initial;
      }
    }
  }
  //$bt = debug_backtrace();
  //print_debug( $bt[0]['file'] );
  //print_debug($ret);

  return $ret;
}

//return languages array
function getLangArr() {
  global $db;
  $ret = array();
  $res = $db->Execute("SELECT initial FROM aa_lang");
  while( list($l) = $res->FetchRow() )
    $ret[] = $l;
  return $ret;
}

function getLanguageDomain( $lang ) {
  global $languages;

  if (empty($languages))
    $languages  = getLangList();

  if (is_numeric($lang)) {
    $lang_id = $lang;
  } else {
    $lang_id = array_search( $lang, $languages['list'] );
    if (!$lang_id)
      $lang_id = array_search( $languages['default'], $languages['list'] );
  }

  if (isset( $languages['domain'][$lang_id]) )
    $server = $languages['domain'][$lang_id];
    //elseif (isset( $languages['domain'][$default_lang_id]) )
  else
    $server = 'http://' . getenv('SERVER_NAME');

  return $server;
}

function getDomainDefaultLanguage( $domain = null ) {
  global $languages;

  if (empty($languages))
    $languages = getLangList();
  if (empty($domain))
    $domain = 'http://' . getenv('SERVER_NAME');

  if ( isset($languages['domain'])
    && isset($languages['default'][$domain])
    ){
    $default_lang = $languages['default'][$domain];
  } else {
    //$default_lang = is_array($languages['default']) ? array_shift($languages['default']) : $languages['default'];
    $default_lang = is_array($languages['default']) ? reset($languages['default']) : $languages['default'];
  }
  //echo 'trovato '. $default_lang.' come default per ' . $domain . '<br>'; //die();

  return $default_lang;
}

//translate an element for the desktop. If err==true display the error message
function translateSite($id, $err=false) {
  global $db;

  s_start(); // session_start

  if (isset($_GET["synSiteLang"]))
    setLang($_GET["synSiteLang"]);

  if ($_SESSION["synSiteLang"]=="" or !isset($_SESSION["synSiteLang"]))
    updateLang();

  $qry = "SELECT * FROM aa_translation WHERE id='".addslashes($id)."'";
  $res = $db->Execute($qry);
  if ($res->RecordCount()==0) {  //umm... the field is multilang but there isn't a row in the translation table...
    $ret = $id;
  } else {
    $arr = $res->FetchRow();
    $ret = $arr[$_SESSION["synSiteLangInitial"]];

    if ($ret=="" and $err===true) {
      foreach ($arr as $mylang=>$mytrans) {
        if (!is_numeric($mylang) and $mylang!="id" and $mytrans!="")
          $alt .= "\n{$mylang}: ".substr(strip_tags($mytrans),0,10);
      }
      $ret="<span style='color: gray' title=\"".htmlentities("Other Translations:".$alt)."\">[no translation]</span>";
    }
  }

  return $ret;
}

function updateLang() {
  global $db, $synSiteLang;

  s_start(); // session_start

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

// DEPRECATED??
// if not already exists, insert a row in the translation table and return the
// translation for the current selected lang
function l($value,$replace="") {
  global $db;
  //$lang=getLang(true);

  //get the list of languages
  $res = $db->Execute( "SELECT initial FROM aa_lang" );
  while (list($lang) = $res->FetchRow()) {
    $languagelist .= $lang . ", ";
    $valuelist .= "'" . addslashes($value) . "', ";
    $select .= $lang . "= '" . addslashes($value)."' OR ";
  }
  $languagelist = substr($languagelist, 0, -2);
  $valuelist = substr($valuelist, 0, -2);
  $select = substr($select, 0, -3);
  //search for the string if already into database
  $qry = "SELECT * FROM aa_translation WHERE {$select}";
  $res = $db->Execute($qry);
  $count = $res->RecordCount();
  // already exists
  if ($count>0) {
    $arr = $res->FetchRow();
    $id = $arr["id"];
  } else {
  //insert the row in each language
    $qry = "INSERT INTO aa_translation ({$languagelist}) VALUES ({$valuelist})";
    $res = $db->Execute($qry);
    $id = $db->Insert_ID();
  }
  $ret = translateSite($id);
  if ($replace != "")
    $ret = str_replace("###", $replace, $ret);

  return $ret;
}


function translateDictionary($label){
  // traduzione singola
  // es. translateDictionary("home_title_realizzazioni")
  global $db;

  $lng = getLangInitial();
  $qry = <<<EOQRY
  SELECT t.{$lng} AS value
    FROM dictionary v
    JOIN aa_translation t ON v.value = t.id
   WHERE v.label = '{$label}'
EOQRY;
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

  $lng = getLangInitial();
  $ret = array();
  $qry = "SELECT v.label, t.{$lng} AS value "
       . "FROM dictionary v "
       . "LEFT JOIN aa_translation t ON v.value=t.id "
       . "WHERE v.label = '".implode("' OR v.label='", $labels)."'";
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
	}	else {
    // nothing found
		$user_languages[0] = '';
	}

  return $user_languages;
}

function getLangFromUrl() {
  global $languages;

  if (empty($languages))
    $languages  = getLangList();

  if (isset($_GET['synSiteLang']) && !empty($_GET['synSiteLang'])) {
    $requested_lang = intval($_GET['synSiteLang']);
    if (isset($languages['list'][$requested_lang]))
      return $languages['list'][$requested_lang];
  }

  if ( !isset($languages['domain'])
    || empty($languages['domain'])
    ){
    // serve user's preferred language in browser settings, if available
    $user_languages = get_languages();
    $user_available_languages = array_intersect( $user_languages, $languages['list'] );
    $lang = array_shift( $user_available_languages );

  } else {
    // language domain set
    $domain = 'http://' . getenv('SERVER_NAME'); // TODO: dynamic protocol?

    if (in_array( $domain, $languages['domain']) ) {
      // search registered domain to get its relative language id
      $lang_id = array_search( $domain, $languages['domain'] );
      $lang = $languages['list'][$lang_id];

    } else {
      // domain not found, serve default language
      if ( is_array($languages['default']) ) { // it should be, in case of domain languages
        if ( isset($languages['default'][$domain]) )
          $lang = $languages['default'][$domain];
        else
          $lang = reset($languages['default']);

      } else {
        if ( isset($languages['default']) )
          $lang = $languages['default'];
        else
          $lang = reset($languages['list']);
      }
    }
  }
  return $lang;
}

// returns the active language
function getActiveLang($variable = 'synSiteLangInitial') {
  s_start(); // session_start

  if (!isset($_SESSION[ $variable ]))
    updateLang();

  return $_SESSION[ $variable ];
}


// shorthand to get active language's abbreviation (ISO code)
function getLangInitial() {
  return getActiveLang('synSiteLangInitial');
}


// shorthand to get the active language's ID
function getLangId() {
  return getActiveLang('synSiteLang');
}


// returns the non-active languages
function getOtherLangs( $lang = '' ){
  global $languages;

  if (empty($lang))
    $lang = getLangId();

  $lang_list = empty($languages)
             ? getLangList()
             : $languages;

  unset( $lang_list['list'][ intval($lang) ] );

  return $lang_list['list'];
}


function getLocaleCodes( $filter = array() ){
  global $db, $languages;

  if ( empty($filter) )
    $filter = array_keys( $languages );

  $lang = getLangInitial();
  $locale = array( 'active' => '', 'alternate' => array() );

  foreach( $languages['list'] as $k => $l ){
    if ( in_array( $k, $filter ) ) {
      $iso_code = strtolower($l) . '_' . strtoupper($l);
      if ($l == $lang)
        $locale['active'] = $iso_code;
      else
        $locale['alternate'][] = $iso_code;
    }
  }
  return $locale;
}


// EOF lang.functions.php