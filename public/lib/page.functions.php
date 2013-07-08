<?php
/******************************************************************************
***                                  PAGE FUNCTIONS
*******************************************************************************/
$languages = null;
/*
function createPath_DEPRECATED($id) {
  global $db;
  if($id==0) return;
  
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
*/

function createPath($id) {
  global $db, $languages;
  
  if($id == 0) return;
  //echo 'languages: <pre>', var_dump($languages), '</pre>';
  
  $lng = $_SESSION['synSiteLangInitial'];
  $qry = <<<EOQ
  
     SELECT p.parent, t.{$lng} AS slug
       FROM aa_page p
  LEFT JOIN aa_translation t ON p.slug = t.id
      WHERE p.id = '{$id}'
      
EOQ;
  //echo $qry.'<br>';
  $res = $db->Execute($qry);
  if($arr = $res->FetchRow()){
    $path = '';
    if (intval($arr['parent'])>0) :
      $path = createPath($arr['parent']);
    else :
      $path = ($lng == $languages['default']) ? '' : '/'.$lng;
    endif;
    $path .= $arr['slug'];
  }

  return $path.'/';
}




function sanitizePath($txt) {
  # sostituzione caratteri cirillici
  $enlow =  array('a', 'a', 'b', 'b', 'v', 'v', 'g', 'g', 'd', 'd', 'je', 'je', 'jo', 'jo', 'zh', 'zh', 'z', 'z', 'i', 'i', 'j', 'j', 'k', 'k', 'l', 'l', 'm', 'm', 'n', 'n', 'o', 'o', 'p', 'p', 'r', 'r', 's', 's', 't', 't', 'u', 'u', 'f', 'f', 'h', 'h', 'ts', 'ts', 'ch', 'ch', 'sh', 'sh', 'shch', 'shch', '', '', 'y', 'y', '', '', 'e', 'e', 'ju', 'ju', 'ja', 'ja');
  $ru = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е', 'Ё', 'ё', 'Ж', 'ж', 'З', 'з', 'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р', 'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш', 'Щ', 'щ', 'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я');
  $uni = array('А'=>'А', 'а'=>'а', 'Б'=>'Б', 'б'=>'б', 'В'=>'В', 'в'=>'в', 'Г'=>'Г', 'г'=>'г', 'Д'=>'Д', 'д'=>'д', 'Е'=>'Е', 'е'=>'е', 'Ж'=>'Ж', 'ж'=>'ж', 'З'=>'З', 'з'=>'з', 'И'=>'И', 'и'=>'и', 'Й'=>'Й', 'й'=>'й', 'К'=>'К', 'к'=>'к', 'Л'=>'Л', 'л'=>'л', 'М'=>'М', 'м'=>'м', 'Н'=>'Н', 'н'=>'н', 'О'=>'О', 'о'=>'о', 'П'=>'П', 'п'=>'п', 'Р'=>'Р', 'р'=>'р', 'С'=>'С', 'с'=>'с', 'Т'=>'Т', 'т'=>'т', 'У'=>'У', 'у'=>'у', 'Ф'=>'Ф', 'ф'=>'ф', 'Х'=>'Х', 'х'=>'х', 'Ц'=>'Ц', 'ц'=>'ц', 'Ч'=>'Ч', 'ч'=>'ч', 'Ш'=>'Ш', 'ш'=>'ш', 'Щ'=>'Щ', 'щ'=>'щ', 'Ъ'=>'Ъ', 'ъ'=>'ъ', 'Ы'=>'Ы', 'ы'=>'ы', 'Ь'=>'Ь', 'ь'=>'ь', 'Э'=>'Э', 'э'=>'э', 'Ю'=>'Ю', 'ю'=>'ю', 'Я'=>'Я', 'я'=>'я');
  $txt =  stripslashes(str_replace($ru, $enlow, strtr($txt, $uni)));
  
  // accent folding
  $search = array(
    'À','Á','Â','Ä','Å','Æ','Ã','Ă','à','á','â','ä','å','æ','ã','ă',
    'ß',
    'Ç','Ć','Ĉ','Ċ','Č','ç','ć','ĉ','ċ','č',
    'Ď','Đ','ď','đ',
    'È','É','Ë','Ē','Ĕ','Ě','Ê','è','é','ë','ē','ĕ','ě','ê',
    'Ĝ','Ğ','Ġ','Ģ','ĝ','ğ','ġ','ģ', 
    'Ĥ','Ħ','ĥ','ħ',
    'Ì','Í','Ï','Î','Ĩ','ì','í','ï', 'î','ĩ',
    'Ñ','ñ',
    'Ò','Ó','Ô','Ö','Ø','Ő','Ô','ò','ó','ô','ö','ø','ő','ô',
    'Ù','Ú','Ü','Ű','Û','ù','ú','ü','ű','û',
    '%','=',',','/','+','.'
  );
  $replace= array(
    'A','A','A','AE','A','AE','A','A','a','a','a','ae','a','ae','a','a',
    'ss',
    'C','C','C','C','C','c','c','c','c','c',
    'D','D','d','d',
    'E','E','E','E','E','E','E','e','e','e','e','e','e','e',
    'G','G','G','G','g','g','g','g',
    'H','H','h','h',
    'I','I','IE','I','I','i','i','ie','i','i',
    'N','n',
    'O','O','O','OE','O','O','O','o','o','o','oe','o','o','o',
    'U','U','UE','U','U','u','u','ue','u','u',
    '-percent','-eq','_','-','-plus','-point'
  );
    
  $txt = str_replace($search, $replace, $txt);
  $txt = trim(strtolower(preg_replace('/[^A-Za-z0-9_]/', ' ', $txt)));
  $txt = str_replace(' ', '-', $txt);
  while (strstr($txt, '--')) 
    $txt = str_replace('--','-',$txt);

  return $txt;
}


function getDomain(){
  //estraggo il dominio
  $domainArr = explode(".", getenv("SERVER_NAME"));
  $domain = $domainArr[(count($domainArr)-2)];

  //estraggo il sottodominio
  $subdomainArr = explode(".", getenv("HTTP_X_FORWARDED_HOST"));
  $subdomain = $subdomainArr[0];

  if($subdomain=="www") 
    $subdomain = '';
  //return
  return array(
    "domain" => $domain,
    "subdomain" => $subdomain
  );
}


function getHomepageId() {
  global $db, $synEntryPoint;
  
  extract(getDomain());
  if ( is_array($synEntryPoint) 
    && array_key_exists($domain, $synEntryPoint)
    ){
    $ret = $synEntryPoint[$domain]; 
  } else {
    $qry = "SELECT id FROM aa_page WHERE parent = 0 LIMIT 0,1";
    $res = $db->Execute($qry);
    $arr = $res->FetchRow();
    $ret = $arr['id'];
  }
  return $ret;
}

/*
function get404pageId() {
  global $db;
  
  $qry = "SELECT p.id FROM aa_page p LEFT JOIN aa_translation t ON p.title = t.id WHERE  LIMIT 0,1";
  $res = $db->Execute($qry);
  $arr = $res->FetchRow();
  $ret = $arr['id'];
  return $ret;
  
  // http://www.phpliveregex.com/
  // filtra tutto quello che non è una pagina
  // OCCHIO: se manca il trailing slash si mangia anche l'ultima pagina!
  $filter_pattern = '/^(?:[^\?#~\.]+)(\/.*?)$/i'; 
  
  // filtra l'ultima pagina e l'eventuale lingua
  $page_pattern = '/^\/([a-z]{2}\/)*([a-z0-9-_\+]+\/)*$/i';
  
  if(!empty($qs)){
    echo "QS presente: {$qs}<br>";
    $uri = str_replace('?'.$qs, '', $uri);
  }
}*/


function getPageId() {
  global $db, $synEntryPoint, $languages;
  
  $pattern = '/^\/([a-z]{2}\/)*'        // matcha la lingua, es. 'en/' - opzionale
           . '([a-z0-9-_\+]+\/)*'       // matcha 'pagina/' - opzionale (cattura solo l'ultima occorrenza)
           . '(?:[a-z0-9-_~\.\/]+)?$/'; // matcha 'cat~1/', 'pippo~1.html' o 'index.html' - opzionale (NON viene catturato)
  
  if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
  }  
  
  if(isset($_GET['spt'])) {
    $uri = DIRECTORY_SEPARATOR . $_GET['spt'];
  } else {
    $uri = $_SERVER['REQUEST_URI'];
  }
  
  $ret = false;
  
  if (empty($languages))
    $languages  = getLangList();
  
  if ( empty($uri) 
    || $uri == 'index.php' 
    || $uri == '/'
    ){ 
    // URI vuoto
    $ret = getHomepageId();
    $lang = $languages['default'];
    
  } else {
    if (preg_match($pattern, $uri, $matches)) {
      //echo 'matches: <pre>', print_r($matches), '</pre>';

      $lang = rtrim($matches[1], '/');
      if ( empty($lang) // lingua non passata
        || !in_array($lang, $languages['list']) // lingua non disponibile
        ){
        // utilizzo lingua di default
        $lang = $languages['default'];
      }
      $required_slug = rtrim($matches[2], '/');
      if (empty($required_slug)) {
        // slug vuoto
        $ret = getHomepageId();
      } else {
        // cerco lo slug
        $qry = "SELECT p.id FROM aa_page p LEFT JOIN aa_translation t ON p.slug = t.id WHERE t.{$lang} = '{$required_slug}'";
        // echo $qry.'<br>';
        $res = $db->execute($qry);
        if ($arr = $res->fetchRow()) {
          $ret = $arr['id'];
        } else {
          // slug non trovato
          echo 'slug non trovato<br>';
        }
      }
    } else {
      // uri non valido? 404
      echo 'uri non valido<br>';
    }
  }

  // imposto la lingua selezionata
  $lang_id = array_search($lang, $languages['list']);  
  setLang($lang_id, $lang);
  
  // TODO: recuperare dinamicamente la 404?
  if($ret === false){
    //echo "{$uri} not found";
    // $ret = '404';
    // pagina non trovata o non valida
    header('HTTP/1.0 404 Not Found');
    header('Location: /404/');
    exit;
  }

  return $ret;
}



/*
function getPageId_DEPRECATED() {
  global $db,$synEntryPoint;

  if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
      $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
  }
  #echo $_SERVER['REQUEST_URI'] ." - ". $_SERVER['HTTP_X_REWRITE_URL'];

  if(isset($_GET['spt'])) {
    $uri = "/".$_GET['spt'];
  } else {
    $uri = $_SERVER["REQUEST_URI"];
  }
  $page404 = false;
  $homepageId = getHomepageId();
  $ret = false;

  // create an array of pages
  $langArr = getLangArr();
  $pageArr = array();

  $qry = "SELECT p.id AS pageid, t.* FROM aa_page p LEFT JOIN aa_translation t ON p.title=t.id";
  $res = $db->Execute($qry);
  while ($arr=$res->FetchRow()) {
    extract($arr);
    foreach ($langArr as $l) {
      $l=trim($l);
      $pageArr[$l][$pageid] = sanitizePath($$l);
      if ($pageArr[$l][$pageid]=="404" && !$page404)
        $page404 = $pageArr[$l][$pageid];
    }
  }
  #echo 'PageArr:<pre>', print_r($pageArr), '</pre>', PHP_EOL; //die();

  // delete the trailing "/" and the GET information from uri
  if (strpos($uri,"?")!==false) {
    $uri = substr($uri, 0, strpos($uri, "?"));
    if (substr($uri, -1)=="?") {
      $uri = substr($uri, 0, -1);
    }
  }
  if (substr($uri, -1)=="/") {
    $uri = substr($uri, 0, -1);
  }


  // split the path
  $arr = explode("/", $uri);
  if ($arr===false or $uri=="") return $homepageId;
  $count = count($arr);

  if (substr($uri, -5, 5)==".html" or substr($uri, -4, 4)==".php") {
    //file
    $filename = $arr[count($arr)-1];
    array_pop($arr);
  } else {
    //directory
    $filename = '';
  }

  //check if it's the homepage
  if ($filename=="index.php" and count($arr)==1) 
    return $homepageId;

  // search for page match
  $lastParent="";
  $level=0;
  foreach($arr as $npage=>$page) {
    $found=false;
    foreach($langArr as $l) {
      if ($page=="") $found=$homepageId;
      else {
       if(is_array($pageArr[$l])){
          $matchingKeys = array_keys($pageArr[$l], $arr[$npage]);
          if (is_array($matchingKeys)) {
            foreach ($matchingKeys as $id) {
              $parent=pageParent($id);
              if ($parent==$lastParent || ($level<=1 && pageParent($parent)==0 && !in_array($parent,$synEntryPoint) ) ) {
                $found = $id;
                break;
              }
            }
          }
        }
      }
    }
    // add level
    $level++;

    // Error 404
    if ($found===false) {
      header("HTTP/1.0 404 Not Found");
      if ($page404!==false) header("Location: /".$page404);
      exit;
    } else {
      $lastParent=$found;
    }
  }

  return $found;
}
*/

function pageParent($id) {
  global $db;
  $qry="SELECT parent FROM aa_page WHERE id=$id";
  $res=$db->Execute($qry);
  $arr=$res->FetchRow();
  return $arr["parent"];
}


function url_decode($s){
  return ucwords(str_replace(array('-','-amp-'), array(' ',' &amp; '), $s));
}


//create the entire menu
function createMenu($id=0, $includeParent=false, $first_child=false) {
  global $db,$smarty,$synPublicPath;;
  $ret="";
  $nodeArr = $smarty->synPageNode;
  foreach($nodeArr as $node) 
    $idArr[] = $node["id"];
  $currPage = $smarty->getTemplateVars('synPageId');
  $lang = $_SESSION["synSiteLang"];
  
  $qry="SELECT * FROM `aa_page` WHERE CONCAT('|', `visible`, '|') LIKE '%|{$lang}|%' AND `parent`=$id ORDER BY `order`";
  $res=$db->Execute($qry);
  $rows=$res->RecordCount();
  $count=1;
  while ($arr=$res->FetchRow()) {
    $title=translateSite($arr["title"]);
    if($first_child==true) {
      $qry="SELECT * FROM `aa_page` WHERE CONCAT('|', `visible`, '|') LIKE '%|{$lang}|%' AND `parent`=".$arr["id"]." ORDER BY `order`";
      $res_c=$db->Execute($qry);
      if ($arr_c=$res_c->FetchRow()) {
        if (trim($arr_c['url'])==''){
          $link=createPath($arr_c["id"]);
          $event = " rel=\"\" ";
          $img = "";
        } else {
          $link=$arr_c["url"];
          $event = " onclick=\"window.open(this.href); return false;\"";
          $img = " <img src=\"".$synPublicPath."/img/link_site.gif\" alt=\"External Site\" />";
        }
      } else {
        if (trim($arr['url'])=='') {
          $link=createPath($arr["id"]);
          $event = " rel=\"\" ";
          $img = "";
        } else {
          $link=$arr["url"];
          $event = " onclick=\"window.open(this.href); return false;\"";
          $img = " <img src=\"".$synPublicPath."/img/link_site.gif\" alt=\"External Site\" />";
        }
      }
    } else {
      if (trim($arr['url'])=='') {
        $link=createPath($arr["id"]);
        $event = " rel=\"\" ";
        $img = "";
      } else {
        $link=$arr["url"];
        $event = " onclick=\"window.open(this.href); return false;\"";
        $img = " <img src=\"".$synPublicPath."/img/link_site.gif\" alt=\"External Site\" />";
      }
    }

    if(($arr["id"]==$currPage)||((is_array($idArr))&&(in_array($arr["id"],$idArr)))){
      $class=" class=\"active\" ";
    }else{
      $class="";
    }

    $class_li = ($count==$rows) ? ' class="last"' : '';

    $ret .= "<li{$class_li}><a href=\"{$link}\" {$event}{$class}>{$title}{$img}</a></li>\n";
    $count ++;
  }
  
  if($includeParent===true){
    $qry="SELECT * FROM `aa_page` WHERE CONCAT('|', `visible`, '|') LIKE '%|{$lang}|%' AND `id`=$id";
    $res=$db->Execute($qry);
    if($res!=false){
      $arr=$res->FetchRow();
      $title=translateSite($arr["title"]);

      if (translateSite($arr["url"])=="") {
        $link = createPath($arr["id"]);
          //die('429: '.$link.'|');
        $event = " rel=\"\" ";
        $img = "";
      } else {
        $link = translateSite($arr["url"]);
        $event = " onclick=\"window.open(this.href); return false;\"";
        $img = " <img src=\"".$synPublicPath."/img/link_site.gif\" alt=\"External Site\" />";
      }

      if($arr["id"]==$currPage) $class=" class=\"active\" "; else $class="";
      $ret="<li><a href=\"$link\" $event $class>".$title.$img."</a></li>\n".$ret;
    }
  }
  return $ret;
}


// function createSubmenu
// create the entire tree
//
// $id: starting page id from which create submenu
// $expand: explode all the tree branch
// $includeParent: include the parent node itself
// $title: database field used for node label
// $first_child: parent node link is set to the first child link

function createSubmenu($id=0, $expand=false, $includeParent=false, $first_child=false, $field="title") {
  $temp=null;
  return createSubmenuPrivate($id, $expand, $includeParent, $first_child, $field, 0, $temp);
}


//create the entire tree
function createSubmenuPrivate($id=0, $expand=false, $includeParent=false, $first_child=false, $field="title" , $count=0, &$link_child) {
  global $db,$smarty,$synPublicPath;
  $ret="";
  $count++;
  $nodeArr=$smarty->synPageNode;
  foreach($nodeArr as $node) $idArr[]=$node["id"];
  $currPage = $smarty->getTemplateVars('synPageId');
  $lang = $_SESSION["synSiteLang"];

  if ($includeParent===true) {
    $qry="SELECT * FROM `aa_page` WHERE CONCAT('|', `visible`, '|') LIKE '%|{$lang}|%' AND `id`=$id";
    $res=$db->Execute($qry);
    if ($arr=$res->FetchRow()) {
      $title=translateSite($arr[$field]);

      if (trim($arr['url'])=='') {
        $link = createPath($arr["id"]);
        $event = " rel=\"\" ";
        $img = "";
      } else {
        $link = $arr["url"];
        $event = " onclick=\"window.open(this.href); return false;\"";
        $img = " <img src=\"".$synPublicPath."/img/link_site.gif\" alt=\"External Site\" />";
      }

      if ($arr["id"]==$currPage) $class=" class=\"active\" ";
      else $class="";

      $ret.="<li><a href=\"".$link."\"".$event.$class.">".$title.$img."</a></li>";
    }
  }

  $qry="SELECT * FROM `aa_page` WHERE CONCAT('|', `visible`, '|') LIKE '%|{$lang}|%' AND `parent`=$id ORDER BY `order`";
  $res=$db->Execute($qry);
  $num_child=0;
  while($arr=$res->FetchRow()) {
    $title=translateSite($arr[$field]);

    if (trim($arr['url'])=='') {
      $link = createPath($arr["id"]);
      $event = " rel=\"\" ";
      $img = "";
    } else {
      $link = $arr["url"];
      $event = " onclick=\"window.open(this.href); return false;\"";
      $img = " <img src=\"".$synPublicPath."/img/link_site.gif\" alt=\"External Site\" />";
    }

    if($num_child==0)$link_child=$link;
    $num_child++;

    $link_my_child="";
    $childret = createSubmenuPrivate($arr["id"], false, false, $first_child, $field, $count, $link_my_child);

    if($first_child && $link_my_child!="") 
      $link = $link_my_child;

    if(($arr["id"]==$currPage) || ((is_array($idArr)) && (in_array($arr["id"],$idArr))))
      $class=" class=\"active\" ";
    else 
      $class="";

    $ret.="<li>";
    //$ret.="<a href=\"$link\" $class>".$title."</a>";
    $ret.="<a href=\"".$link."\"".$event.$class.">".$title.$img."</a>";

    if ((($arr["id"]==$currPage)||((is_array($idArr))&&(in_array($arr["id"],$idArr)))) || $expand===true) $ret.=$childret;
    $ret.="</li>\n";

  }
  if($ret!="") $ret="<ul class=\"menu lv".$count."\">".$ret."</ul>";
  return $ret;
}
?>
