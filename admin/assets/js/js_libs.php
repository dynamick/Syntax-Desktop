<?php
// minifies and packs js libraries
include('../../config/config.php');
include('../../includes/php/Minifier.php');
ob_start("ob_gzhandler");

$debug  = isset($_GET['debug'])
        ? true
        : false;
$scope  = isset($_GET['scope'])
        ? strip_tags($_GET['scope'])
        : null;

header( 'Content-Type: text/javascript; charset=utf-8' );
if ( !$debug ) {
  header( 'Expires: ' . gmdate('D, d M Y H:i:s', time() + (7*24*60*60)).' GMT' );
  header( 'Last-Modified: '.gmdate( 'D, d M Y H:i:s', getlastmod()) . ' GMT' );
  header( 'Cache-Control: max-age=315360000' );
}
echo "var apikey = '{$synGoogleAPIKey}';\n";
// base scripts
$base = array(
  'vendor/jquery.js',
  'vendor/bootstrap.min.js',
  'vendor/ie10-viewport-bug-workaround.js',
  'vendor/animsition.min.js'
  );

// scripts for desktop managing
$desktop = array(
  'desktop.js'
  );

// scripts for tree view
$tree = array(
  'vendor/bootstrap-treenav.min.js'
  );

// scripts for toast and alerts
$messaging = array(
  'vendor/bootbox.js',
  'vendor/bootstrap-notify.min.js',
  );

// bootstrap table scripts
$table = array(
  'vendor/bootstrap-table.js',
  'vendor/bootstrap-table-cookie.min.js',
  'vendor/tableExport.min.js',
  'vendor/bootstrap-table-export.min.js',
  'vendor/bootstrap-table-it-IT.min.js',
  );

// scripts for index view
$index = array(
  'jquery.quickPreview.js',
  'functions.js',
  'index_functions.js'
  );

// form controls
$form = array(
  'vendor/bootstrap-switch.min.js',
  'vendor/moment-with-locales.min.js',
  'vendor/bootstrap-datetimepicker.min.js',
  'vendor/fontawesome-iconpicker.min.js',
  'vendor/bootstrap-multiselect.js',
  'vendor/bootstrap-maxlength.js',

  'vendor/fileinput/plugins/canvas-to-blob.min.js',
  'vendor/fileinput/plugins/sortable.min.js',
  'vendor/fileinput/fileinput.min.js',
  'vendor/fileinput/locales/it.js',
  'vendor/fileinput/themes/fa/theme.js',

  // WARNING - don't update typeahead.bundle until typeahead.addresspicker supports v.0.11!
  'vendor/typeahead.bundle.min.js',
  'vendor/typeahead-addresspicker.js',
  'load-google-maps.js',
  'synAddressPicker.js',
  'vendor/jquery.alphanumeric.js',
  );

// rich text editor
$ckeditor = array(
  'vendor/ckeditor/ckeditor.js',
  'vendor/ckeditor/adapters/jquery.js',
  'vendor/ckeditor/lang/it.js',
  'vendor/ckeditor/styles.js',
  'vendor/ckeditor/plugins/link/dialogs/anchor.js',
  'vendor/ckeditor/plugins/table/dialogs/table.js',
  'vendor/ckeditor/plugins/image/dialogs/image.js'
  );

// scripts for item view
$single = array(
  'functions.js',
  'form_functions.js'
  );

// sometimes minification can break things!!
$do_not_minify = array(
  'vendor/jquery.js',
  'vendor/ckeditor/ckeditor.js',
  'vendor/moment-with-locales.min.js',
  );

switch ($scope) {
  case 'index':
    $files = array_merge( $base, $messaging, $table, $index );
    break;
  case 'single':
    $files = array_merge( $base, $messaging, $form, $ckeditor, $single );
    break;
  case 'tree':
    $files = array_merge( $base, $tree );
    break;
  case 'option':
    $files = array_merge( $base, $messaging );
    break;
  case 'desktop':
    $files = array_merge( $base, $desktop );
    break;
  default:
    $files = $base;
    break;
  }

foreach( $files as $file ) {
  if ( is_file($file) ) {
    $buffer = file_get_contents( $file );
    $path_parts = pathinfo( $file );

    if ($path_parts['extension'] == 'php'){
      ob_start();
      eval( $buffer );
      $buffer = ob_get_contents();
      ob_end_clean();
    }
    if ( !$debug
      && !in_array( $file, $do_not_minify )
      ){
      $buffer = \JShrink\Minifier::minify( $buffer );
    }
    $label = str_pad(" {$file} ", 94, '-', STR_PAD_BOTH);
    $buffer = "/* {$label} */" . PHP_EOL  . $buffer;

    echo $buffer, PHP_EOL, PHP_EOL;

  } else {
    echo "//== {$file} not found!!! ===================================//" . PHP_EOL;
    echo "alert('{$file} not found!!');" . PHP_EOL;
    die();
  }
}
ob_flush();
// EOF
