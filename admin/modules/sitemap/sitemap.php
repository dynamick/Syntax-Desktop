<?php
# ==============================================================================
#   ____  _ _                                ____        _ _     _           
#  / ___|(_) |_  ___ _ __ ___   __ _ _ __   | __ ) _   _(_) | __| | ___ _ __ 
#  \___ \| | __|/ _ \ '_ ` _ \ / _` | '_ \  |  _ \| | | | | |/ _` |/ _ \ '__|
#   ___) | | |_|  __/ | | | | | (_| | |_) | | |_) | |_| | | | (_| |  __/ |   
#  |____/|_|\__|\___|_| |_| |_|\__,_| .__/  |____/ \__,_|_|_|\__,_|\___|_|   
#                                   |_|                                      
#  v. 0.2
# ==============================================================================
# Marco Pozzato, 2010-08-31
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


# Creare una chiamata per ogni servizio da indicizzare:
# buildTree(
#  $table      = nome della tabella mysql del servizio - OBBLIGATORIO
#  $titleField = nome del campo titolo
#  $where      = filtro per mysql (es. 'visible=1')
#  $order      = nome del campo che ordina i record - OBBLIGATORIO
#  $parentPage = id della pagina di riferimento (in caso di collezioni, es. news) 
#  $recursive  = se l'oggetto ha figli 
#  $freq       = frequenza di aggiornamento (def. 'monthly'),
#  $priority   = priorit� dell'elemento nella gerarchia del sito (max 1.0, min. 0.0, def. 0.5) 
#  $niceurl    = url interpretato da htaccess (true/false), 
#  $recursion  = 0
# )

// pagine
$items1 = buildTree('aa_page', 'title', 'parent=22 AND visible=1', 'order', '', true, 'monthly', 0.6, false);

// news
$items2 = buildTree('news', 'title', '', 'date', '55', false, 'daily', 0.8, true);

// fondo gli array in uno solo
$items = array_merge($items1, $items2);

// home page
$entries[] = new xml_sitemap_entry('/', '1.0', 'daily'); 

foreach($items AS $v){
  $entries[] = new xml_sitemap_entry($v['url'], str_replace(',','.',$v['priority']), $v['freq']);
}

# istanza della classe xml sitemap generator
$conf = new xml_sitemap_generator_config;
$conf->setDomain($domain);
$conf->setPath($synAbsolutePath.$path);
$conf->setFilename($filename);
$conf->setEntries($entries);

$generator = new xml_sitemap_generator($conf);
$output = $generator->write(false);

$html = "";
# ok, stampo l'output
if($output=1){
  $html .= "<h2>Sitemap generated</h2>\n";
  $html .= "<p>".count($entries)." items listed.</p>\n";
  $html .= "<a href=\"".$path.$filename."\">".$filename."</a>\n";
  foreach($mdr as $k=>$v){
    $html .= "<p>{$k} pinged (".pingUrl("{$v}sitemap=".urlencode('http://'.$domain.$path.$filename)).")</p>";
  }
  echo $html;

} else {
  echo '<p>Errors encountered, operation failed.</p>';
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
?>
