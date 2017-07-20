<?php
ob_start("ob_gzhandler");
// incorpora e cacha le librerie javascript

header( 'Content-Type: text/javascript' );
if ( isset($_GET['development']) ) {
  // prevent caching
  header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
  header( 'Cache-Control: no-store, no-cache, must-revalidate' ); // HTTP/1.1
  header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
  header( 'Pragma: no-cache' ); // HTTP/1.0
  header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' ); // Date in the past

} else {
  header( 'Expires: '       . gmdate( 'D, d M Y H:i:s', time()+(7*24*60*60) ) . ' GMT' );
  header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', getlastmod() )        . ' GMT' );
  header( 'Cache-Control: max-age=315360000' );
}

$script = array(
	'vendor/jquery.js',
	'vendor/bootstrap.min.js',
  'vendor/jquery.colorbox-min.js',
  'vendor/jquery.validate.min.js',
  'vendor/owl.carousel.min.js',
  'jquery.cookieDisclaimer.js',
	'main.js'
);

foreach($script as $file){
  if (file_exists($file)){
    readfile($file);
    echo PHP_EOL, PHP_EOL;
  } else die("alert('{$file} not found!!');\n//== {$file} not found!!! ===================================//");
}

ob_flush();
?>
