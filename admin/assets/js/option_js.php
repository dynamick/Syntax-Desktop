<?php
ob_start("ob_gzhandler");
// minifies and packs js libraries

header("Expires: ".gmdate("D, d M Y H:i:s", time()+(7*24*60*60))." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s", getlastmod())." GMT");
header("Cache-Control: max-age=315360000");
header("Content-Type: text/javascript");

$script = array(
  'vendor/jquery.js',
  'vendor/bootstrap.min.js',
  'vendor/ie10-viewport-bug-workaround.js',
  'vendor/bootbox.min.js',
);

foreach($script as $file){
  if (is_file($file)){
    readfile($file);
    echo PHP_EOL, PHP_EOL;
  } else die("alert('{$file} not found!!');\n//== {$file} not found!!! ===================================//");
}

ob_flush();
// EOF