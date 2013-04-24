<?php
# ==============================================================================
#   ____  _ _                                ____        _ _     _           
#  / ___|(_) |_  ___ _ __ ___   __ _ _ __   | __ ) _   _(_) | __| | ___ _ __ 
#  \___ \| | __|/ _ \ '_ ` _ \ / _` | '_ \  |  _ \| | | | | |/ _` |/ _ \ '__|
#   ___) | | |_|  __/ | | | | | (_| | |_) | | |_) | |_| | | | (_| |  __/ |   
#  |____/|_|\__|\___|_| |_| |_|\__,_| .__/  |____/ \__,_|_|_|\__,_|\___|_|   
#                                   |_|                                      
#  v. 0.3
# ==============================================================================
# Marco Pozzato, 2013-04-10
#
# basato sulla libreria di Alan Miller: 
# http://www.alanmiller.com/blog/article/php-xml-sitemap-generator
#
# vedi anche:
# http://www.sitemaps.org/protocol.php
# ==============================================================================

require_once('../../config/cfg.php');
require_once('./class.xmlSitemapGenerator.php');

# configurazione
$domain   = getenv('SERVER_NAME');
$path     = '/public/mat/';
$filename = 'sitemap.xml';
$mdr      = array(
 'Google' => 'www.google.com/ping?'
 , 'Bing' => 'www.bing.com/webmaster/ping.aspx?'
  , 'Ask' => 'submissions.ask.com/ping?'
);

$languages = getLangList2();
/*
echo '<pre>', print_r($languages), '</pre>'; 

foreach($languages['list'] AS $l => $iso){
  echo "{$l} => {$iso}<br>";
}
die();
*/

$templates = array(
  11 => array('album', 'id', 'title', '%parent%/?id=%id%'),
  12 => array('news',  'id', 'title', '%parent%/%title%~%id%.html'),
);

$tree = array();

function pageTree(&$tree, $parent=0, $base=''){
  global $db, $languages;
  //$tree = array();

  $db->setFetchMode(ADODB_FETCH_ASSOC);
  $sql = "SELECT p.id AS pageid, p.template, p.visible, t.* FROM aa_page p JOIN aa_translation t ON p.slug = t.id WHERE p.parent='{$parent}' ORDER BY p.`order`";
    echo $sql.'<hr>';
  $res = $db->execute($sql);

  while($arr = $res->fetchRow()){
    //extract($arr); //, EXTR_PREFIX_ALL, 'page');
      //echo '<pre>', print_r($arr), '</pre>';
      
    $ar_visible = explode('|', $arr['visible']);
      //echo '<pre>', print_r($ar_visible), '</pre>';
    
    foreach($languages['list'] AS $l => $iso){
      if($parent==0 && $iso != $languages['default']) {
        $arr[$iso] = $iso.'/';
      }
      
      if(in_array($l, $ar_visible)){
        echo "è in array slug: {$arr[$iso]}<br>";
        $tree[] = $base.'/'.$arr[$iso];
      }
    }
//echo 'tree1: <pre>', print_r($tree), '</pre>';    
    $children = pageTree($tree, $arr['pageid'], $arr[$iso]);
    
    /*if(is_array($children) && !empty($children)){
      array_merge($tree, $children);
    }*/
//echo 'tree2: <pre>', print_r($tree), '</pre>'; 
      //die();
  }
  //return $tree;
}

//echo '<pre>', print_r(pageTree()), '</pre>';
  pageTree($tree);
  echo '<pre>', print_r($tree), '</pre>';
die();








// istanza della classe xml sitemap generator
/*
$conf = new xml_sitemap_generator_config;
$conf->setDomain($domain);
$conf->setPath($synAbsolutePath.$path);
$conf->setFilename($filename);
$conf->setEntries($entries);

$generator = new xml_sitemap_generator($conf);
$output = $generator->write(false);
*/
$html = "";
# ok, stampo l'output
if($output=1){
  $html .= "<h2>Sitemap generated</h2>\n";
  $html .= "<p>".count($entries)." items listed.</p>\n";
  $html .= "<a href=\"".$path.$filename."\">".$filename."</a>\n";
  foreach($mdr as $k=>$v){
    $html .= "<p>{$k} pinged</p>\n";
    //$html .= "<p>{$k} pinged (".pingUrl("{$v}sitemap=".urlencode('http://'.$domain.$path.$filename)).")</p>";
  }
  echo $html;

} else {
  echo '<p>Errors encountered, operation failed.</p>';
}


function getLangList2(){
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


# funzione che recupera i dati
function buildTree($table, $titleField='title', $where, $order, $parentPage, $recursive, $freq='monthly', $priority=0.5, $niceurl=true, $recursion=0){
  global $db;
  
  $ret = "";
  
  if($table=='') return;
  $recursion++;
  $priority = ($recursion>1 && $priority>0) ? $priority-0.1 : $priority;
  
  $path = ($parentPage) ? createPath($parentPage) : '';
  $cwhr = ($where) ? " WHERE $where " : '';
  $cord = ($order) ? " ORDER BY `$order`" : '';

  $sql = "SELECT id, `$titleField` FROM `$table` $cwhr $cord";
  $res = $db->execute($sql);

  while ($arr=$res->fetchRow()) {
    $id = $arr[0];
    $title = (is_numeric($arr[1])) ? translate($arr[1]) : $arr[1];
    if($parentPage){
      # collection
      if($niceurl){
        $url = $path.sanitizePath($title).'~'.$id.'.html';
      } else {
        $url = $path.'?id='.$id;
      }
    } else {
      $url = createPath($id);
    }
    if($recursive){
      $child = buildTree($table, $titleField, 'parent='.$id.' AND visible=1', $order, $parentPage, $recursive, $freq, $priority, $niceurl, $recursion);
    }
    $ret[] = array('url'=>$url, 'priority'=>$priority, 'freq'=>$freq);
    if(isset($child) and $child)     
      $ret = array_merge($ret, $child);
  }
  return $ret;
}

# ping ai mdr
function pingUrl($url=NULL){  
  if($url == NULL) return false;  
  $ch = curl_init($url);  
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
  $data = curl_exec($ch);  
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
  curl_close($ch);
  if($httpcode>=200 && $httpcode<300){  
    return 'http:'.$httpcode.', ok';  
  } else {  
    return 'http:'.$httpcode.', down';    
  }  
}

// EOF