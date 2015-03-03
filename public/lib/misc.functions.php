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
/* DEPRECATED *
if(!function_exists('cleverThumb')) {
  function cleverThumb($path="", $filename, $foto, $suffix="", $alt="", $width=50, $height=50, $rotate=false, $attr='', $mode=1, $mask=false, $center=true){
    global $synPublicPath, $synAbsolutePath;
    if($filename=='' || $foto=='') return;
    $ROOT        = $synAbsolutePath;
    $img         = $synPublicPath."/mat/".$path.$filename.".".$foto;
    $resultimg   = "/cache/".$suffix.$filename.".".$foto;
    if(file_exists($ROOT.$img)) {
      $tmb_exists  = file_exists($ROOT.$resultimg);
      $img_created = date("Y-m-d H:i:s", @filectime($ROOT.$img));
      $tmb_created = date("Y-m-d H:i:s", @filectime($ROOT.$resultimg));
      if ($tmb_exists) {
        list($w,$h) = @getimagesize($ROOT.$resultimg);
        $sameSize   = ($w==$width) && ($h==$height);
      }
      if ( !$tmb_exists
        || ($tmb_exists && ($img_created > $tmb_created))
        || !$sameSize
        ){
        $thumbnail = new Image_Toolbox($ROOT.$img);
        $thumbnail-> setResizeMethod('resample');
        $thumbnail-> newOutputSize($width, $height, $mode, $rotate, '#FFFFFF', $center);
        if($mask!=''){
          $thumbnail-> addImage($ROOT.$synPublicPath."/mat/{$mask}.png");
          $thumbnail-> blend(0, 0, IMAGE_TOOLBOX_BLEND_COPY, 100);
        }
        $thumbnail-> save($ROOT."/".$resultimg, "jpg");
      }
      return '<img src="'.$resultimg.'" alt="'.str_replace("\"","",htmlentities($alt)).'" width="'.$width.'" height="'.$height.'" '.$attr.'/>';
    } #else echo $ROOT.$img.' non trovato!<br>';
  }
}
* DEPRECATED */

/*
EXAMPLE: htmlmail('user@domain.com', 'Look ma, HTML e-mails','You just got <a href="http://www.yougotrickrolled.com/">Rick Rolled</a>');
NOTE: $headers is optional, but can be used to set From, CC, etc. Go to http://www.htmlite.com/php029.php for info
*/

/* DEPRECATED *
if(!function_exists('htmlmail')) {
  function htmlmail($to, $subject, $message, $from = NULL) {
  	$mime_boundary = md5(time());

  	$headers .= "Message-ID: <".time()." system@{$_SERVER['SERVER_NAME']}>\n";
  	$headers .= $from;
  	$headers .= "X-Mailer: PHP ".phpversion()."\n";
  	$headers .= "MIME-Version: 1.0\n";
  	$headers .= "Content-Type: multipart/alternative;\n";
    $headers .= " boundary=\"{".$mime_boundary."}\"\n";
  	$headers .= "Content-Transfer-Encoding: 7bit\n\n";

    # text message
  	$newmessage  = "This is a multi-part message in MIME format.\n\n";
  	$newmessage .= "--{".$mime_boundary."}\n";
  	$newmessage .= "Content-type: text/plain;charset=utf-8\n";
  	$newmessage .= "Content-Transfer-Encoding: 7bit\n\n";
  	$newmessage .= strip_tags(str_replace(array('<br>', '<br />'), "\n", $message))."\n\n";

    # html message
  	$newmessage .= "--{".$mime_boundary."}\n";
  	$newmessage .= "Content-type: text/html;charset=utf-8\n";
  	$newmessage .= "Content-Transfer-Encoding: 7bit\n\n";
  	$newmessage .= '<body style="margin:0"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"><tr><td bgcolor="#ffffff" valign="top"><table width="750" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td bgcolor="#ffffff" width="750">';
  	$newmessage .= $message;
  	$newmessage .= '</td></tr></table></td></tr></table></body>';
  	$newmessage .= "\n\n--{".$mime_boundary."}--\n";

  	return mail($to, $subject, $newmessage, $headers);
  }
}
* DEPRECATED */

/******************************************************************************/
/********************      LIST MESSENGER INTEGRATION     *********************/
/******************************************************************************/
/* DEPRECATED *
function _checkMailExists($email){
  global $db;
  if(trim($email)=='') return false;
  $qry="SELECT COUNT(*) as numero FROM `lm_users` WHERE email_address='".addslashes($email)."' ";
  $res=$db->Execute($qry);
  if(($res!==false)&&($arr=$res->FetchRow())){
    $tot=intval($arr['numero']);
  }
  if($tot>0) return true; else return false;
}
function _addUserToNewsletter($email,$group,$name='',$surname=''){
  global $db;
  $qry="INSERT INTO `lm_users`(`group_id`,`firstname`,`lastname`,`email_address`) ";
  $qry.="VALUES ('".addslashes($group)."','".addslashes($name)."','".addslashes($surname)."','".addslashes($email)."') ";
  $res=$db->Execute($qry);
  if($res!==false) return true; else return false;
}
function _delUserFromNewsletter($email){
  global $db;
  $qry="DELETE FROM `lm_users` WHERE `email_address`='".addslashes($email)."' ";
  $res=$db->Execute($qry);
  if($res!==false) return true; else return false;
}
* DEPRECATED */

/* DEPRECATED *
function getDocument($productname, $document, $line, $classe, $spessore=''){
  global $synPublicPath, $synAbsolutePath;
  $ret = NULL;

  switch($line){
    case 1: $lineinitial = 'C'; break;
    case 2: $lineinitial = 'R'; break;
    case 3: $lineinitial = 'S'; break;
    case 4: $lineinitial = 'D'; break;
  }

  $tail = '';

  switch($document){
    case 'CE': $label = 'PDF Cartiglio CE'; break;
    case 'CT':
      $label = 'PDF Conduttivit&agrave; term.';
      if(substr_count(strtolower($productname), 'trieste')>0) {
        $tail = ($spessore ? '_sp'.$spessore : '');
      }
      break;
    case 'CM':
      $label = 'PDF resistenza comp.';
      break;
    case 'CR':
      $label = 'PDF Resistenza al fuoco';
      $tail = ($spessore ? '_sp'.$spessore : '');
      break;
    //case 'VC': $label = 'PDF voci di capitolato'; break;
    case 'RD':
      $label = 'PDF cert. radioattivit&agrave;';
      $pos = strpos($productname, ' ');
      $productname = substr($productname, 0, $pos);
      break;
    case 'PF':
      $label = 'PDF potere fonoassorb.';
      $tail = ($spessore ? '_sp'.$spessore : '');
      break;
    case 'CL':
      $label = 'PDF dilatazione lineare';
      //$pos = strpos($productname, ' ');
      //$productname = substr($productname, 0, $pos);
      //$tail = ($spessore ? '_sp'.$spessore : '');
      break;
    case 'CU':
      $label = 'PDF dilatazione umidit&agrave;';
      //$pos = strpos($productname, ' ');
      //$productname = substr($productname, 0, $pos);
      //$tail = ($spessore ? '_sp'.$spessore : '');
      break;
  }

  $productname = str_replace(' ', '_', strtolower($productname));
  $productname = str_replace('/', '-', strtolower($productname));

  $file = $synPublicPath."/mat/prodotti/".$document."_".$lineinitial."_".$productname.$tail;
  $file_pdf = $file.".pdf";
  $file_doc = $file.".doc";

  if(file_exists($synAbsolutePath.$file_pdf)) {
    $ret = "      <li><a class=\"dl $classe\" href=\"$file_pdf\">$label</a></li>\n";
  } else if (file_exists($synAbsolutePath.$file_doc)) {
    $ret = "      <li><a class=\"dl $classe\" href=\"$file_doc\">$label</a></li>\n";
  } //else $ret = "<li>".$file." non trovato</li>\n";

  return $ret;
}
* DEPRECATED */


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

// EOF misc.functions.php