<?php
ob_start("ob_gzhandler");
// minifies and packs js libraries

header("Expires: ".gmdate("D, d M Y H:i:s", time()+(7*24*60*60))." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s", getlastmod())." GMT");
header("Cache-Control: max-age=315360000");
header("Content-Type: text/javascript");

$script = array(
    'vendor/jquery.js',
    'vendor/moment-with-locales.min.js',
    'vendor/bootstrap.min.js',
    'vendor/bootstrap-switch.min.js',
    'vendor/bootstrap-datetimepicker.min.js',
    'vendor/fontawesome-iconpicker.min.js',
    'vendor/bootstrap-multiselect.js',
    'vendor/bootstrap-maxlength.min.js',
    'vendor/fileinput.min.js',
    'vendor/bootbox.min.js',
    'vendor/bootstrap-notify.min.js',
    'vendor/bloodhound.min.js',
    'vendor/typeahead.jquery.min.js',
    'vendor/typeahead-addresspicker.js',

    'vendor/ckeditor/ckeditor.js',
    'vendor/ckeditor/adapters/jquery.js',
    'vendor/ckeditor/lang/it.js',
    'vendor/ckeditor/styles.js',
    'vendor/ckeditor/plugins/link/dialogs/anchor.js',
    'vendor/ckeditor/plugins/table/dialogs/table.js',
    'vendor/ckeditor/plugins/image/dialogs/image.js',

    'functions.js',
    'form_functions.js'
);

foreach($script as $file){
  if (is_file($file)){
    readfile($file);
    echo PHP_EOL, PHP_EOL;
  } else die("alert('{$file} not found!!');\n//== {$file} not found!!! ===================================//");
}

ob_flush();
// EOF