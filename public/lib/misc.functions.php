<?php

/******************************************************************************
 ***                        MISCELLANEOUS FUNCTIONS                         ***
 ******************************************************************************
 */

/* DEPRECATED *
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
* DEPRECATED */

if(!function_exists('file_put_contents')) {
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

if(!function_exists('troncaTesto')) {
  function troncaTesto($testo, $caratteri=50) {
    if (strlen($testo) <= $caratteri)
      return $testo;
    $nuovo = wordwrap($testo, $caratteri, '|');
    $nuovotesto = explode('|',$nuovo);
    return $nuovotesto[0].'&hellip;';
  }
}


if(!function_exists('excerpt')) {
  //http://stackoverflow.com/questions/1436582/how-to-generate-excerpt-with-most-searched-words-in-php/2159813#2159813
  function excerpt($text, $phrase, $span = 100, $delimiter = '...'){
    $phrases = preg_split('/\s+/', $phrase);

    $regexp = '/\b(?:';
    foreach ($phrases as $phrase) {
      $regexp .= preg_quote($phrase, '/') . '|';
    }

    $regexp = substr($regexp, 0, -1) . ')\b/i';
    $matches = array();
    preg_match_all($regexp, $text, $matches, PREG_OFFSET_CAPTURE);
    $matches = $matches[0];

    $nodes = array();
    foreach ($matches as $match) {
      $node = new stdClass;
      $node->phraseLength = strlen($match[0]);
      $node->position = $match[1];
      $nodes[] = $node;
    }

    if (count($nodes) > 0) {
      $clust = new stdClass;
      $clust->nodes[] = array_shift($nodes);
      $clust->length = $clust->nodes[0]->phraseLength;
      $clust->i = 0;
      $clusters = new stdClass;
      $clusters->data = array($clust);
      $clusters->i = 0;
      foreach ($nodes as $node) {
        $lastClust = $clusters->data[$clusters->i];
        $lastNode = $lastClust->nodes[$lastClust->i];
        $addedLength = $node->position - $lastNode->position - $lastNode->phraseLength + $node->phraseLength;
        if ($lastClust->length + $addedLength <= $span) {
          $lastClust->nodes[] = $node;
          $lastClust->length += $addedLength;
          $lastClust->i += 1;
        } else {
          if ($addedLength > $span) {
            $newClust = new stdClass;
            $newClust->nodes = array($node);
            $newClust->i = 0;
            $newClust->length = $node->phraseLength;
            $clusters->data[] = $newClust;
            $clusters->i += 1;
          } else {
            $newClust = clone $lastClust;
            while ($newClust->length + $addedLength > $span) {
              $shiftedNode = array_shift($newClust->nodes);
              if ($shiftedNode === null) {
                break;
              }
              $newClust->i -= 1;
              $removedLength = $shiftedNode->phraseLength;
              if (isset($newClust->nodes[0])) {
                $removedLength += $newClust->nodes[0]->position - $shiftedNode->position;
              }
              $newClust->length -= $removedLength;
            }
            if ($newClust->i < 0) {
              $newClust->i = 0;
            }
            $newClust->nodes[] = $node;
            $newClust->length += $addedLength;
            $clusters->data[] = $newClust;
            $clusters->i += 1;
          }
        }
      }
      $bestClust = $clusters->data[0];
      $bestClustSize = count($bestClust->nodes);
      foreach ($clusters->data as $clust) {
        $newClustSize = count($clust->nodes);
        if ($newClustSize > $bestClustSize) {
          $bestClust = $clust;
          $bestClustSize = $newClustSize;
        }
      }
      $clustLeft = $bestClust->nodes[0]->position;
      $clustLen = $bestClust->length;
      $padding = round(($span - $clustLen)/2);
      $clustLeft -= $padding;
      if ($clustLeft < 0) {
        $clustLen += $clustLeft*-1 + $padding;
        $clustLeft = 0;
      } else {
        $clustLen += $padding*2;
      }
    } else {
      $clustLeft = 0;
      $clustLen = $span;
    }

    $textLen = strlen($text);
    $prefix = '';
    $suffix = '';

    if ( !ctype_space($text[intval($clustLeft)])
      && isset($text[$clustLeft-1])
      && !ctype_space($text[intval($clustLeft-1)])
      ){
      while (!ctype_space($text[intval($clustLeft)])) {
        $clustLeft += 1;
      }
      $prefix = $delimiter;
    }

    $lastChar = $clustLeft + $clustLen;

    if ( isset($text{intval($lastChar)})
      && !ctype_space($text[intval($lastChar)])
      && isset($text[intval($lastChar+1)])
      && !ctype_space($text[intval($lastChar+1)])
      ){
      while (!ctype_space($text[intval($lastChar)])) {
        $lastChar -= 1;
      }
      $suffix = $delimiter;
      $clustLen = $lastChar - $clustLeft;
    }

    if ($clustLeft > 0) {
      $prefix = $delimiter;
    }

    if ($clustLeft + $clustLen < $textLen) {
      $suffix = $delimiter;
    }
    return $prefix . trim(substr($text, $clustLeft, $clustLen+1)) . $suffix;
  }
}


if(!function_exists('pulisciTesto')) {
  function pulisciTesto($testo) {
    return str_replace("<p>\r\n\t&nbsp;</p>", "", $testo);
  }
}

if(!function_exists('tabIndex')) {
  function tabindex(){
    static $tab = 1;
    return $tab ++;
  }
}


// sanitizes text to be used as html attribute
if(!function_exists('attributize')) {
  function attributize($str, $cut = NULL) {
    $str = str_replace('"', NULL, $str);
    $str = filter_var(html_entity_decode($str), FILTER_SANITIZE_STRING);
    $str = trim(preg_replace('/\s+/', ' ', $str));

    if ($cut)
      $str = html_entity_decode(troncaTesto($str, $cut));
    return $str;
  }
}

if (!function_exists('getOpenGraph')) {
  // prepara l'array per costruire i metadati openGraph (facebook/twitter)
  function getOpenGraph( $title, $text, $image, $url, $type = 'article', $time = null, $locale = null ) {
    global $synWebsiteTitle, $synPublicPath;

    $server = 'http://'.$_SERVER['SERVER_NAME'];
    $title = attributize($title);
    $text = attributize($text, 150);

    if (empty($locale))
      $locale = getLocaleCodes();

    if (is_array($image)) {
      $imagearr = array();
      foreach($image as $i) {
        if (!empty($i))
          $imagearr[] = htmlentities("{$server}{$synPublicPath}/thumb.php?src={$i}&w=600&h=400&zc=1");
      }
      $image = $imagearr;

    } elseif (!empty($image))
      $image = htmlentities("{$server}{$synPublicPath}/thumb.php?src={$image}&w=600&h=400&zc=1");

    $meta = array(
      'tw' => array(
        'attr' => 'name',
        'prefix' => 'twitter',
        'props' => array(
          'title'       => $title,
          'description' => $text,
          'image'       => $image,
          'url'         => $url,
          'card'        => 'summary'
        )
      ),
      'fb' => array(
        'attr' => 'property',
        'prefix' => 'og',
        'props' => array(
          'title'       => $title,
          'description' => $text,
          'image'       => $image,
          'url'         => $url,
          'type'        => $type,
          'site_name'   => $synWebsiteTitle,
        )
      )
    );

    if ( isset($locale['active']) )
      $meta['fb']['props']['locale'] = $locale['active'];

    if ( isset($locale['alternate']) ) {
      $meta['fb']['props']['locale:alternate'] = array();
      foreach( $locale['alternate'] as $alt )
        $meta['fb']['props']['locale:alternate'][] = $alt;
    }

    if ($type == 'article') {
      $date = new DateTime($time);
      $meta['article'] = array(
        'attr' => 'property',
        'prefix' => 'article',
        'props' => array(
          'published_time' => $date->format( DateTime::ATOM )
        )
      );
    }

    return array_filter($meta);
  }
}


if(!function_exists('str_makerand')) {
  function str_makerand ($minlength, $maxlength, $useupper, $usespecial, $usenumbers) {
  /*
  Author: Peter Mugane Kionga-Kamau
  http://www.pmkmedia.com
  Modify at will.
  */
    $key = '';
    $charset = "abcdefghijklmnopqrstuvwxyz";
    if ($useupper)
      $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($usenumbers)
      $charset .= "0123456789";
    if ($usespecial)
      $charset .= "~@#$%^*()_+-={}|]["; // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";
    if ($minlength > $maxlength)
      $length = mt_rand ($maxlength, $minlength);
    else
      $length = mt_rand ($minlength, $maxlength);
    for ($i=0; $i<$length; $i++)
      $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
    return $key;
  }
}



if(!function_exists('byteConvert')) {
  function byteConvert( $bytes ) {
    if ($bytes <= 0)
      return '0 Byte';

    $convention = 1000; //[1000->10^x|1024->2^x]
    $s = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB');
    $e = floor(log($bytes,$convention));

    return round($bytes/pow($convention,$e),2).' '.$s[$e];
  }
}


// hashes a string
if (!function_exists('hash')) {
  function hash($str){
    global $synRootPasswordSalt;
    return md5($str.$synRootPasswordSalt);
  }
}


if (!function_exists('social_share')) {
  function social_share($permalink, $title, $resource_path = ''){
    global $enabled_socials;
    $social_share = array(
      'facebook' => array(
        'icon'  => 'facebook-square',
        'link'  => 'http://www.facebook.com/sharer.php?u='.$permalink.'&amp;t='.$title,
        'title' => 'Share on Facebook'
      ),
      'twitter' => array(
        'icon'  => 'twitter-square',
        'link'  => 'http://twitter.com/home/?status='.$title.'%20-%20'.$permalink,
        'title' => 'Tweet this!'
      ),
      'linked-in' => array(
        'icon'  => 'linkedin-square',
        'link'  => 'http://www.linkedin.com/shareArticle?mini=true&amp;title='.$title.'&amp;url=.'.$permalink,
        'title' => 'Share on LinkedIn'
      ),
      'pinterest' => array(
        'icon'  => 'pinterest',
        'link'  => 'http://pinterest.com/pin/create/button/?url='.$permalink.'&media='.$resource_path,
        'title' => 'Share in Pinterest'
      ),
      'google-plus' => array(
        'icon'  => 'google-plus-square',
        'link'  => 'https://plus.google.com/share?url='.$permalink,
        'title' => 'Share on Google Plus'
      ),
      'delicious' => array(
        'icon'  => 'delicious',
        'link'  => 'http://del.icio.us/post?url='.$permalink.'&amp;title='.$title,
        'title' => 'Bookmark on del.icio.us'
      ),
      'reddit' => array(
        'icon'  => 'reddit',
        'link'  => 'http://www.reddit.com/submit?url='.$permalink.'&amp;title='.$title,
        'title' => 'Vote on Reddit'
      ),
      'stumble-upon' => array(
        'icon'  => 'stumbleupon-circle',
        'link'  => 'http://www.stumbleupon.com/submit?url='.$permalink.'&amp;title='.$title,
        'title' => 'Stumble it'
      ),
      'digg' => array(
        'icon'  => 'digg',
        'link'  => 'http://digg.com/submit?url='.$permalink.'&amp;title='.$title,
        'title' => 'Digg this!'
      )
    );
    // I need keys for comparison
    $enabled_socials_keys = array_combine( $enabled_socials, $enabled_socials );
    // return only $social_share elements whose key is present in $enabled_socials
    return array_intersect_key( $social_share, $enabled_socials_keys );
  }
}


// puts message in session
if(!function_exists('set_flash_message')) {
  function set_flash_message($message, $type=null){
    if (!isset($_SESSION))
      session_start();

    $_SESSION['flash_message'] = array('text' => $message, 'type' => $type);
  }
}


// reads message from session (and optionally deletes it)
if(!function_exists('get_flash_message')) {
  function get_flash_message($clean = TRUE) {
    if (!isset($_SESSION))
      session_start();

    $ret = null;
    if ( isset($_SESSION['flash_message'])
      && !empty($_SESSION['flash_message'])
      ){
      $message = $_SESSION['flash_message']['text'];
      $type = $_SESSION['flash_message']['type'];
      if ($clean)
        unset($_SESSION['flash_message']);

      $ret = <<<EORET
      <div class="alert {$type}">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {$message}
      </div>
EORET;
    }

    return $ret;
  }
}

if (!function_exists('print_debug')) {
  function print_debug( $var, $dump = false ) {
    if ($dump)
      echo '<pre>', htmlspecialchars( var_dump(  $var, 1 ) ), '</pre>';
    else
      echo '<pre>', htmlspecialchars( print_r(  $var, 1 ) ), '</pre>';
  }
}


if (!function_exists('alternate_column_languages')) {
  function alternate_column_languages( $languages, $table = 't', $alias = 'title' ) {
    $ret = array();
    foreach( $languages as $lang ) {
      $ret[] = "{$table}.{$lang} AS {$alias}_{$lang}";
    }
    return implode( ',', $ret );
  }
}


if (!function_exists('ensureUrlScheme')) {
  function ensureUrlScheme( $url, $scheme = 'http://' ) {
    return parse_url($url, PHP_URL_SCHEME) === null ?
      $scheme . $url : $url;
  }
}

/*
 * check if variable exists and is not null, optionally returns a default value.
 * Useful to avoid 'undefined variable' notices.
 */
if (!function_exists('safe_get')) {
  function safe_get( $var, $default = NULL, $callback = NULL ) {
    if ( isset($var) ) {
      if ( isset($callback) )
        $var = call_user_func( $callback, $var );
      return $var;
    } else
      return $default;
  }
}

// EOF misc.functions.php