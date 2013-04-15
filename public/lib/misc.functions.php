<?php
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

if(!function_exists('tabIndex')) {
  function tabindex(){
    static $tab = 1;
    return $tab ++;
  }
}

if(!function_exists('str_makerand')) {
  function str_makerand ($minlength, $maxlength, $useupper, $usespecial, $usenumbers) {
  /*
  Author: Peter Mugane Kionga-Kamau
  http://www.pmkmedia.com
  Modify at will.
  */
    $charset = "abcdefghijklmnopqrstuvwxyz";
    if ($useupper) $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($usenumbers) $charset .= "0123456789";
    if ($usespecial) $charset .= "~@#$%^*()_+-={}|]["; // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";
    if ($minlength > $maxlength) $length = mt_rand ($maxlength, $minlength);
    else $length = mt_rand ($minlength, $maxlength);
    for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
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

/*
EXAMPLE: htmlmail('user@domain.com', 'Look ma, HTML e-mails','You just got <a href="http://www.yougotrickrolled.com/">Rick Rolled</a>');
NOTE: $headers is optional, but can be used to set From, CC, etc. Go to http://www.htmlite.com/php029.php for info
*/
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

/******************************************************************************/
/********************      LIST MESSENGER INTEGRATION     *********************/
/******************************************************************************/
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
  } #else $ret = "<li>".$file." non trovato</li>\n";
  
  return $ret;
}

if(!function_exists('byteConvert')) {
  function byteConvert( $bytes ) {
    if ($bytes<=0) return '0 Byte';
    $convention=1000; //[1000->10^x|1024->2^x]
    $s=array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB');
    $e=floor(log($bytes,$convention));
    return round($bytes/pow($convention,$e),2).' '.$s[$e];
  }
}

?>
