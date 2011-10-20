<?php
#ini_set('display_errors','E_STRICT');
session_start();

class synCaptcha {

  var $colors = array(
    // hexadecimal, alpha (0=opaque, 255=transparent)
    array('#6E6E6E',80),
    array('#03BB3F',60),
    array('#FF0000',80),
    array('#FF7800',60),
    array('#09A186',70)
    );

  var $fonts = array(
    './MARYJSC_.ttf',
    './Verahb__.ttf',
    './VeraMono.ttf'
    );

  var $dict_location = './';
  
	function generateCode($characters) {
		// lista di caratteri possibili
		$possible = '23456789abcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) {
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

  
	function getRandom($array) {
    if(is_array($array)) {
      $max = count($array)-1;
      $rand = mt_rand(0, $max);
    }
    return $array[$rand];
	}

  
  function hex2RGB($hexStr) {
    // converte i colori esadecimali in RGB
    // http://www.php.net/manual/en/function.hexdec.php#99478
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
      $colorVal = hexdec($hexStr);
      $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
      $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
      $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
      $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
      $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
      $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
      return false; //Invalid hex color code
    }
    return $rgbArray;
  }   

  
	function synCaptcha($width='250',$height='80',$characters='5',$use_dict=true,$lines=true,$noise=false) {

    $img = imagecreatetruecolor($width, $height);

    // alloco un colore per lo sfondo
    $background = imagecolorallocate($img,255,255,255);
    // coloro lo sfondo creando un rettangolo
    imagefilledrectangle($img, 0, 0, $width-1, $width-1, $background);

    // genero il testo
    if ($use_dict) {
      if($_SESSION['synSiteLangInitial']=='it'){
        $this->dict_location .= 'parole.txt';
      } else {
        $this->dict_location .= 'words.txt';
      }
    	// load dictionary and choose random word
    	$words = @file($this->dict_location);
    	$codice = strtolower($words[mt_rand(0, sizeof($words))]);
    	// cut off line endings/other possible odd chars
    	$codice = preg_replace("/[^a-z]/","",$codice);
    	// might be large file so forget it now (frees memory)
    	unset($words);
    } else {
      $codice = $this->generateCode($characters);
    }

    shuffle($this->colors);

    for ($i=0; $i<strlen($codice); $i++) {
      $size  = mt_rand(($height*0.50),($height*0.70));
      $angle = mt_rand(-35,25); // angolo di rotazione
  		$x     = ((($width/1.1)/$characters)*($i+0.3));
  		$y     = $height/1.5;
      $hex   = $this->hex2RGB($this->colors[$i][0]); // prendo il colore
      $color = imagecolorallocatealpha($img,$hex['red'],$hex['green'],$hex['blue'],$this->colors[$i][1]); // ...e lo alloco
      $font  = $this->getRandom($this->fonts); // scelgo un font a caso
      $char  = $codice{$i}; // carattere da disegnare

  		// passo tutti i parametri alla libreria
      imagettftext($img, $size, $angle, $x, $y, $color, $font, $char);

      if ($lines) {
			  imageline($img, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width*2), mt_rand(0,$height*2), $color);
      }

      if ($noise) {
        imagefilledellipse($img, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), imagecolorallocatealpha($img,$hex['red'],$hex['green'],$hex['blue'],110));
      }
    }

    // invio l'immagine al browser
    header("Content-type: image/png");
    imagepng($img);
    imagedestroy($img);

    $_SESSION['security_code'] = $codice;
	}
}

$width   = intval($_GET['width']);
$height  = intval($_GET['height']);
$chars   = intval($_GET['characters']);
$dict    = $_GET['use_dict'];
$lines   = $_GET['lines'];
$noise   = $_GET['noise'];
$captcha = new synCaptcha($width, $height, $chars, $dict, $lines, $noise);
?>
