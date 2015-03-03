<?php
ob_start("ob_gzhandler");
// incorpora e cacha le librerie javascript

//header("Expires: ".gmdate("D, d M Y H:i:s", time()+(7*24*60*60))." GMT");
//header("Last-Modified: ".gmdate("D, d M Y H:i:s", getlastmod())." GMT");
//header("Cache-Control: max-age=315360000");
header("Content-Type: text/javascript");

$script = array(
	'vendor/jquery.js',
	'vendor/bootstrap.min.js',
  'vendor/jquery.validate.min.js',
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
